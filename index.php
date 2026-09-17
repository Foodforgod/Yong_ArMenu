<?php
// Get current page URL automatically for QR Code generation
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$arUrl = "$protocol://$host$path/ar.php";
$qrApiUrl = "https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=" . urlencode($arUrl);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Italian Kitchen - Web AR Food Menu</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #fdfbf7; margin: 0; padding: 40px; display: flex; justify-content: center; align-items: center; min-height: 100vh; color: #333; }
        .container { background: #ffffff; padding: 40px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); text-align: center; max-width: 480px; width: 100%; border: 1px solid #eee; }
        .logo { font-size: 2.5rem; color: #b22222; margin-bottom: 5px; }
        h1 { color: #8b0000; margin-top: 0; font-size: 1.8rem; }
        p.subtitle { color: #666; font-size: 0.95rem; margin-bottom: 25px; }
        .btn-group { display: flex; flex-direction: column; gap: 12px; margin-bottom: 25px; }
        .btn { background: #b22222; color: white; border: none; padding: 14px 20px; border-radius: 8px; font-size: 1rem; font-weight: bold; cursor: pointer; text-decoration: none; transition: background 0.2s; box-shadow: 0 4px 12px rgba(178,34,34,0.2); }
        .btn:hover { background: #8b0000; }
        .btn-secondary { background: #333; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .btn-secondary:hover { background: #111; }
        .btn-success { background: #2e8b57; box-shadow: 0 4px 12px rgba(46,139,87,0.2); }
        .btn-success:hover { background: #226b43; }
        .qr-section { background: #faf9f6; padding: 20px; border-radius: 12px; border: 1px dashed #dcd6cd; margin-top: 20px; }
        .qr-section img { border-radius: 8px; border: 4px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.08); width: 140px; height: 140px; }
        .qr-section p { font-size: 0.85rem; color: #555; margin: 10px 0 0 0; font-weight: 600; }
        .instructions { text-align: left; background: #fffdf9; padding: 15px 20px; border-radius: 8px; border: 1px solid #faebd7; font-size: 0.85rem; color: #555; }
        .instructions h3 { margin: 0 0 8px 0; color: #8b0000; font-size: 0.95rem; }
        .instructions ol { margin: 0; padding-left: 20px; }
        .instructions li { margin-bottom: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">🍴</div>
        <h1>Italian Kitchen</h1>
        <p class="subtitle">Experience our signature menu items in interactive Augmented Reality</p>

        <div class="btn-group">
            <a href="ar.php" class="btn">🚀 Scan Menu in AR (Webcam)</a>
            <a href="menu.php" class="btn btn-secondary">📄 View Full Menu Poster</a>
            <a href="editor.php" class="btn btn-success">⚙️ Edit AR Menu Markers & Layout</a>
        </div>

        <div class="qr-section">
            <img src="<?php echo $qrApiUrl; ?>" alt="AR QR Code">
            <p>Scan with your phone camera to open AR view</p>
        </div>

        <div class="instructions" style="margin-top: 20px;">
            <h3>How It Works</h3>
            <ol>
                <li>Open the <strong>AR Scanner</strong> on your smartphone or webcam.</li>
                <li>Grant camera permissions when prompted.</li>
                <li>Point your camera directly at the menu poster.</li>
                <li>Click items or tap layout markers to switch 3D dishes!</li>
            </ol>
        </div>
    </div>
</body>
</html>