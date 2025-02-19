
<?php 




include("header.php");


?>


<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📂Excel to Table</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <style>
      
        #drop-area { width: 50%; margin: auto; padding: 20px; border: 2px dashed #ccc; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .form-container { margin-top: 20px;
        
            
            
        
        }
        
     


.my {
	box-shadow:inset 0px 7px 0px 0px #9acc85;
	background:linear-gradient(to bottom, #74ad5a 5%, #68a54b 100%);
	background-color:#74ad5a;
	border-radius:24px;
	border:1px solid #3b6e22;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:Arial;
	font-size:13px;
	font-weight:bold;
	padding:8px 28px;
	text-decoration:none;
}
.my:hover {
	background:linear-gradient(to bottom, #68a54b 5%, #74ad5a 100%);
	background-color:#68a54b;
}
.my:active {
	position:relative;
	top:1px;
}

      #excel-table-container {
            width: 100%;
            overflow-x: auto;
            
            margin-top: 20px;
        }
        #excel-table {
            width: 100%;
            border-collapse: collapse;
        }
        #excel-table th, #excel-table td {
           
            text-align: left;
        }
        #excel-table th {
            background-color: #f2f2f2;
        } 
                   .form-control, .form-select {
                     max-width: 40%;
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

    <h2 style="text-align: center;">ลากไฟล์ Excel มาวางที่นี่</h2>
    <p style="text-align: center;">หากเป็นไฟล์ชนิดอื่นค่าจะไม่แสดงผล</p>
    <div id="drop-area">📂 ลากไฟล์ Excel หรือ <input type="file" id="file-input"></div>
    
   <div id="excel-table-container">
        <table id="excel-table">
            <!-- Table content will be populated here -->
        </table>
    </div>

 <center> <div class="form-container">
        <label>ชื่อข้อมูล: <input type="text" class="form-control" id="data-name" placeholder="📂กรอกเป็นภาษาอังกฤษ"></label>
     <br> <br>
        <label>วันที่จัดเก็บ: <input type="date" class="form-control" id="data-date"></label>
     <br>
       <br>
        <button  class="my" onclick="saveJSON()">📥 บันทึกเข้าฐานข้อมูล</button>
    </div></center>  
<br><br><br><br><br>
    <script>
        const dropArea = document.getElementById("drop-area");
        const fileInput = document.getElementById("file-input");
        const table = document.getElementById("excel-table");
        let excelData = []; // เก็บข้อมูลจาก Excel

        // ฟังก์ชันโหลดไฟล์และแปลงเป็นตาราง
        function handleFile(file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const data = new Uint8Array(event.target.result);
                const workbook = XLSX.read(data, { type: "array" });
                const sheetName = workbook.SheetNames[0];
                const sheet = workbook.Sheets[sheetName];
                const jsonData = XLSX.utils.sheet_to_json(sheet, { header: 1 });

                excelData = jsonData; // เก็บข้อมูล Excel ในตัวแปร
                renderTable(jsonData);
            };
            reader.readAsArrayBuffer(file);
        }

        // ฟังก์ชันแสดงข้อมูลเป็นตาราง HTML
        function renderTable(data) {
            table.innerHTML = "";
            data.forEach((row, rowIndex) => {
                let tr = document.createElement("tr");
                row.forEach((cell) => {
                    let cellElement = rowIndex === 0 ? document.createElement("th") : document.createElement("td");
                    cellElement.textContent = cell;
                    tr.appendChild(cellElement);
                });
                table.appendChild(tr);
            });
        }

        // ฟังก์ชันบันทึกข้อมูลเป็น JSON และส่งไปที่ save.php
       // ฟังก์ชันบันทึกข้อมูลเป็น JSON และส่งไปที่ save.php
function saveJSON() {
    const dataName = document.getElementById("data-name").value;
    const dataDate = document.getElementById("data-date").value;

    if (!dataName || !dataDate) {
        alert("กรุณากรอกชื่อข้อมูลและวันที่จัดเก็บ");
        return;
    }

    const jsonData = {
        name: dataName,
        date: dataDate,
        table: excelData
    };

    fetch('http://app.procadet.com/Excel/save.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(jsonData)
    })
    .then(response => response.json())
    .then(data => {
        alert("📥 บันทึกค่าแล้ว!");
        window.location.href = "list.php"; // เปลี่ยนหน้าไปยัง list.php
    })
    .catch(error => console.error('Error:', error));
}


        // อัปโหลดไฟล์ผ่าน Input
        fileInput.addEventListener("change", (event) => handleFile(event.target.files[0]));

        // Drag & Drop
        dropArea.addEventListener("dragover", (event) => {
            event.preventDefault();
            dropArea.style.border = "2px solid #333";
        });

        dropArea.addEventListener("dragleave", () => dropArea.style.border = "2px dashed #ccc");

        dropArea.addEventListener("drop", (event) => {
            event.preventDefault();
            dropArea.style.border = "2px dashed #ccc";
            handleFile(event.dataTransfer.files[0]);
        });
    </script>

</body>
</html>
