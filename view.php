<?php
include("header.php");
$directory = "data/";
$filename = $_GET["file"] ?? "";

if (!$filename || !file_exists($directory . $filename)) {
    die("❌ ไม่พบไฟล์ JSON");
}

$jsonData = json_decode(file_get_contents($directory . $filename), true);
$dataName = $jsonData["name"] ?? "ไม่ระบุ";
$dataDate = $jsonData["date"] ?? "ไม่ระบุ";
$tableData = $jsonData["table"] ?? [];
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📊 ข้อมูล: <?= htmlspecialchars($dataName) ?></title>
</head>
<body>
    <div class="container">
        <h2>📊 ข้อมูล: <?= htmlspecialchars($dataName) ?></h2>
        <p>📅 วันที่จัดเก็บ: <?= htmlspecialchars($dataDate) ?></p>
        <input type="text" id="searchInput" class="form-control" onkeyup="searchTable()" placeholder="🔍 ค้นหาข้อมูล...">
        <br><br>
        <input type="text" id="newFileName" class="form-control" placeholder="📂 กรอกชื่อแฟ้มใหม่ ด้วยภาษาอังกฤษ">
        <br><br>
        <button class="my" onclick="saveChanges()">💾 บันทึกการเปลี่ยนแปลง</button>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        table { width: 90%; margin: auto; border-collapse: collapse; margin-top: 20px; overflow-x: auto; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #f2f2f2; }
        .container { text-align: center; margin-bottom: 20px; }
        #searchInput, #newFileName { width: 80%; padding: 10px; margin-bottom: 10px; }
        .highlight { background-color: yellow; }

        .my {
            box-shadow: inset 0px 7px 0px 0px #9acc85;
            background: linear-gradient(to bottom, #74ad5a 5%, #68a54b 100%);
            background-color: #74ad5a;
            border-radius: 24px;
            border: 1px solid #3b6e22;
            display: inline-block;
            cursor: pointer;
            color: #ffffff;
            font-family: Arial;
            font-size: 13px;
            font-weight: bold;
            padding: 8px 28px;
            text-decoration: none;
        }
        .my:hover {
            background: linear-gradient(to bottom, #68a54b 5%, #74ad5a 100%);
            background-color: #68a54b;
        }
        .my:active {
            position: relative;
            top: 1px;
        }

        .form-control, .form-select {
            padding: 10px;
            background-color: #F0F0EF;
            border: 1px solid #E6E6E4;
            border-radius: 20px;
            color: #000;
        }

        .form-control:focus, .form-select:focus {
            background-color: #F0F0EF;
            border-color: #E6E6E4;
            border-radius: 20px;
            box-shadow: none;
            color: #000;
        }
    </style>
    <script>
        function searchTable() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let table = document.getElementById("dataTable");
            let rows = table.getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) {
                let cells = rows[i].getElementsByTagName("td");
                let rowMatch = false;

                for (let j = 0; j < cells.length; j++) {
                    let cell = cells[j];
                    let text = cell.textContent.toLowerCase();
                    if (text.includes(input)) {
                        rowMatch = true;
                        cell.classList.add("highlight");
                    } else {
                        cell.classList.remove("highlight");
                    }
                }
                rows[i].style.display = rowMatch ? "" : "none";
            }
        }

        function exportToExcel() {
            let table = document.getElementById("dataTable");
            let wb = XLSX.utils.table_to_book(table);
            XLSX.writeFile(wb, "exported_data.xlsx");
        }

        function saveChanges() {
            let table = document.getElementById("dataTable");
            let rows = table.getElementsByTagName("tr");
            let updatedData = [];

            for (let i = 1; i < rows.length; i++) {
                let row = rows[i];
                let cells = row.getElementsByTagName("td");
                let rowData = [];

                for (let j = 0; j < cells.length; j++) {
                    rowData.push(cells[j].textContent); // Get the text content of each cell
                }
                updatedData.push(rowData);
            }

            // Get the new file name from the input field
            let newFileName = document.getElementById("newFileName").value;

            // Prepare the JSON data to be saved
            let updatedJsonData = {
                "name": newFileName || "ชื่อข้อมูลใหม่", // Use the new file name if provided
                "date": new Date().toISOString(),
                "table": updatedData
            };

            // Send the data back to the server (use AJAX function to save the data)
            fetch("http://app.procadet.com/Excel/save_json.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(updatedJsonData)
            })
            .then(response => response.json())
            .then(data => {
        alert("📥 บันทึกค่าแล้ว!");
        window.location.href = "list.php"; // เปลี่ยนหน้าไปยัง list.php
    })
            .catch(error => console.error("เกิดข้อผิดพลาดในการบันทึกข้อมูล:", error));
        }
    </script>

    <table id="dataTable">
        <?php if (empty($tableData)): ?>
            <tr><td colspan="100%">❌ ไม่มีข้อมูลในไฟล์นี้</td></tr>
        <?php else: ?>
            <?php foreach ($tableData as $rowIndex => $row): ?>
                <tr>
                    <?php foreach ($row as $cell): ?>
                        <td contenteditable="true"><?= htmlspecialchars($cell) ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </table><br><br>
    <button class="my" onclick="exportToExcel()">📥 ดาวน์โหลดเป็นไฟล์ Excel</button>
    
    <br>
    <br>
    <br>
</body>
</html>
