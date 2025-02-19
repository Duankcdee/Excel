<?php






include("header.php");



$directory = "data/";
$jsonFiles = glob($directory . "*.json");

// เรียงลำดับไฟล์ JSON ตามเวลาล่าสุด
usort($jsonFiles, function ($a, $b) {
    return filemtime($b) - filemtime($a);
});

// กำหนดจำนวนรายการต่อหน้า
$itemsPerPage = 30;
$totalItems = count($jsonFiles);
$totalPages = ceil($totalItems / $itemsPerPage);

// รับค่าหน้าปัจจุบันจากพารามิเตอร์ URL (ค่าเริ่มต้นเป็นหน้าแรก)
$currentPage = isset($_GET['page']) ? max(1, min($totalPages, intval($_GET['page']))) : 1;

// คำนวณ offset สำหรับตัดแบ่งข้อมูล
$offset = ($currentPage - 1) * $itemsPerPage;
$paginatedFiles = array_slice($jsonFiles, $offset, $itemsPerPage);

// ค้นหาข้อมูล
$searchQuery = $_GET['search'] ?? "";
$searchResults = [];
if (!empty($searchQuery)) {
    foreach ($jsonFiles as $file) {
        $jsonData = json_decode(file_get_contents($file), true);
        $date = $jsonData["date"] ?? "ไม่ระบุ";
        
        if (strpos(json_encode($jsonData, JSON_UNESCAPED_UNICODE), $searchQuery) !== false) {
            $searchResults[] = ["date" => $date, "file" => basename($file)];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📂 รายการข้อมูล JSON</title>
    <script>
        function confirmDelete(filename) {
            if (confirm("⚠️ คุณแน่ใจหรือไม่ว่าต้องการลบไฟล์นี้?")) {
                window.location.href = "delete.php?file=" + encodeURIComponent(filename);
            }
        }
    </script>
    <style>
       .container {
            max-width: 90%;
            margin: 0 auto;
            padding: 15px;
            background-color: transparent;
           
            box-shadow: 0 2px 1700px rgba(0, 0, 0, 0.1);
           
            
        }
  
@media (max-width: 768px){
            .container {
                max-width: 98%;
            }
    th, td { 
    padding: 4px;
        font-size:12px;
    }
    
    p{
        
      font-size:12px;  
        
    }
        }
        table { width: 100%; margin: auto; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { text-decoration: none; color: blue; }
        
        
        .pagination { margin-top: 20px; }
        .pagination a { padding: 8px 12px; margin: 0 5px; border: 1px solid #ddd; color: black; text-decoration: none; }
        .pagination a.active { background-color: #007bff; color: white; }
        .pagination a:hover { background-color: #ddd; }
        
            .form-control, .form-select {
	padding: 10px;
    background-color: #F0F0EF;
    border: 1px solid #E6E6E4;
       border-radius: 20px;      
    color: #000;
                width: 70%;
}

.form-control:focus, .form-select:focus {
	background-color: #F0F0EF;
    border-color: #E6E6E4;
     border-radius: 20px;
    box-shadow: none;
	color: #000;
}
    </style>
</head>
<div class="container" >

 <center>   <h2>📂 รายการข้อมูลที่บันทึก</h2>
    
    <form method="GET">
        <input type="text" name="search" class="form-control" placeholder="🔍 ค้นหาข้อมูล..." value="<?= htmlspecialchars($searchQuery) ?>">
        <button type="submit"></button>
    </form></center>

    <?php if (!empty($searchQuery)): ?>
        <h3>🔍 ผลลัพธ์การค้นหา: "<?= htmlspecialchars($searchQuery) ?>"</h3>
        <table>
            <tr>
                <th>วันที่จัดเก็บ</th>
                <th>ดูข้อมูล</th>
                <th>ลบ</th>
            </tr>
            <?php if (empty($searchResults)): ?>
                <tr><td colspan="3">❌ ไม่พบข้อมูลที่ตรงกัน</td></tr>
            <?php else: ?>
                <?php foreach ($searchResults as $result): ?>
                    <tr>
                        <td><?= $result["date"] ?></td>
                        
                        <td><a href="view.php?file=<?= urlencode($result["file"]) ?>" target="_blank">📄 ดูข้อมูล</a></td>
                        <td><button onclick="confirmDelete('<?= urlencode($result['file']) ?>')">🗑️ ลบ</button></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    <?php else: ?>
        <table>
            <tr>
                <th>ลำดับ</th>
                <th>วันที่จัดเก็บ</th>
                <th>ชื่อเรื่อง</th>
                <th>ดูข้อมูล</th>
                <th>ลบ</th>
            </tr>
            <?php if (empty($paginatedFiles)): ?>
                <tr><td colspan="5">❌ ไม่มีข้อมูลที่บันทึก</td></tr>
            <?php else: ?>
                <?php foreach ($paginatedFiles as $index => $file): ?>
                    <?php 
                        $jsonData = json_decode(file_get_contents($file), true);
                        $date = $jsonData["date"] ?? "ไม่ระบุ";
                        $name = $jsonData["name"] ?? "ไม่ระบุ";
                        $filename = basename($file);
                    ?>
                    <tr>
                        <td><?= $offset + $index + 1 ?></td>
                        <td><?= $date ?></td>
                        <td><?= $name ?></td>
                        <td><a href="view.php?file=<?= urlencode($filename) ?>" target="_blank">📄 ดูข้อมูล</a></td>
                        <td><button onclick="confirmDelete('<?= urlencode($filename) ?>')">🗑️ ลบ</button></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    <?php endif; ?>

    <div class="pagination">
        <?php if ($totalPages > 1 && empty($searchQuery)): ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= ($i == $currentPage) ? 'active' : '' ?>"> <?= $i ?> </a>
            <?php endfor; ?>
        <?php endif; ?>
    </div>
    </div>
</body>
</html>
