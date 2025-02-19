<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="asset/style.css" /> 
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head> 
    <body>
        <nav class="navbar">
           
            
            <div class="navbar-center"> 
                <button >
                   
                </button>
                <button onclick="window.location.href='index.php';">
    <i class="fa-solid fa-house"></i> 
</button>

                <button onclick="window.location.href='list.php';">
                  <i class="fa-solid fa-list"></i>
                </button>
                
                
                <button>
                  
                </button>
            <!-- other buttons --> 
            </div> 
            
              
        </nav> 
    <br>
    <br>
      <br>  
        <br>
        <br>
        <br>
    <style>
    html, body{
    margin: 0;
    padding: 0;
    font-family: Arial, Helvetica, sans-serif;
        background: #ffffff; 
}
button {
    display: grid;
    place-items: center;
    background: transparent;
    color: #606770;
    border: 0;
}
button > i { 
    font-size: 20px; 
    cursor: pointer;
}
.navbar {
    position: fixed;
    top: 0; 
    left: 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 72px;
    width: 100%;
    border-bottom: 1px solid #e8e8e8;
    background: #ffffff; 
    box-shadow:
    0 3px 10px rgba(0, 0, 0, 0.08);
}
.navbar-logo, .navbar-avatar { 
    width: 40px;
    height: 40px;
}
.navbar-end {
    display: flex;
    gap: 6px;
    flex: 0 0 178px;
    padding: 0 20px;
}
.icon-button {
    width: 40px; 
    height: 40px;
    border-radius: 50%; 
    background: #f0f2f5;
}
.navbar-badge {
    position: absolute;
    z-index: 1;
    top: 8px;
    right: 54px;
    background: #e41e3f;
    color: #f9f9f9; 
    font-size: 12px;
    padding: 1px 4px; 
    border-radius: 10px; 
    transition: 0.3s;
}
.navbar-center {
    display: flex; 
    flex: 1 1 auto; 
    padding: 0 10px;
}
.navbar-center button {
    flex: 1 1 auto; 
    height: 72px; 
    padding-top: 4px;
    border-bottom: 4px solid transparent;
}
.navbar-center  button.active { 
    border-bottom-color: #1a74e5;
}
.navbar-center button.active i {
    color: #1a74e5;
}
.navbar-center button i { 
    font-size: 24px;
}
.navbar

@media(max-width: 768px) {
    .navbar-end {
    flex: 0 0 auto;
    }
    .navbar-center button {
    display: none;
    }
    
}
 @media(max-width: 580px) {
    
    .navbar-badge { 
        right: 10px; 
    }
}
    </style>