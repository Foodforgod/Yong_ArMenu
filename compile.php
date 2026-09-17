<?php
$uploadError = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['menu_image'])) {
    $file = $_FILES['menu_image'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $destination = 'assets/images/menu.jpg';
            move_uploaded_file($file['tmp_name'], $destination);
            $uploadError = "Menu image updated successfully!";
        } else {
            $uploadError = "Invalid file extension. Please upload JPG, PNG, or WEBP.";
        }
    } else {
        $uploadError = "Upload failed with error code " . $file['error'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prepare AR Tracking - Italian Kitchen</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: sans-serif; background: #f4f4f9; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        h1 { color: #b22222; margin-top: 0; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-weight: bold; margin-bottom: 8px; }
        input[type="file"] { padding: 10px; background: #f9f9f9; border: 1px solid #ddd; width: 100%; box-sizing: border-box; border-radius: 5px; }
        .btn { background: #b22222; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; text-decoration: none; display: inline-block; }
        .btn:hover { background: #8b0000; }
        .alert { padding: 12px; background: #e2f0d9; color: #385723; border-radius: 5px; margin-bottom: 20px; }
        .preview-box { text-align: center; margin-top: 20px; }
        .preview-box img { max-width: 100%; max-height: 400px; border-radius: 5px; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fa-solid fa-sliders"></i> AR Tracking & Layout Manager</h1>
        <p>Upload a new menu poster or review configuration settings for AR mapping.</p>
        
        <?php if (!empty($uploadError)): ?>
            <div class="alert"><?php echo htmlspecialchars($uploadError); ?></div>
        <?php endif; ?>

        <form action="compile.php" method="POST" enctype="multipart/form-data" class="form-group">
            <label for="menu_image">Upload New Menu Poster (JPG, PNG)</label>
            <input type="file" name="menu_image" id="menu_image" required>
            <br><br>
            <button type="submit" class="btn"><i class="fa-solid fa-upload"></i> Upload & Replace</button>
        </form>

        <hr style="margin: 30px 0; border: 0; border-top: 1px solid #eee;">

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <a href="index.php" class="btn" style="background: #333;">Back to Home</a>
            <a href="ar.php" class="btn" style="background: #2e8b57;"><i class="fa-solid fa-camera"></i> Launch AR Scanner</a>
        </div>

        <div class="preview-box">
            <h3>Current Active Menu Reference</h3>
            <img src="assets/images/menu.jpg" alt="Active Menu Preview">
        </div>
    </div>
</body>
</html>