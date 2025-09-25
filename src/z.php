<?php
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fileToUpload'])) {
    $targetDir = __DIR__ . '/uploads/';
    $webPathPrefix = 'uploads/';

    if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true)) {
        $message = 'ไม่สามารถสร้างโฟลเดอร์สำหรับเก็บไฟล์ได้';
    } elseif (!is_writable($targetDir)) {
        $message = 'ไม่สามารถเขียนไฟล์ลงโฟลเดอร์อัปโหลดได้';
    } else {
        $file = $_FILES['fileToUpload'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $message = 'เกิดข้อผิดพลาดในการอัปโหลด (code ' . $file['error'] . ')';
        } else {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($file['tmp_name']);
            $allowedTypes = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
                'image/webp' => 'webp',
            ];

            if (!isset($allowedTypes[$mimeType])) {
                $message = 'อัปโหลดได้เฉพาะไฟล์รูปภาพ (jpg, png, gif, webp)';
            } else {
                $baseName = pathinfo($file['name'], PATHINFO_FILENAME);
                $safeName = preg_replace('~[^a-z0-9_-]~i', '_', $baseName);
                if ($safeName === '') {
                    $safeName = 'image';
                }
                $extension = $allowedTypes[$mimeType];
                $uniqueSuffix = date('Ymd-His') . '-' . bin2hex(random_bytes(3));
                $finalName = $safeName . '-' . $uniqueSuffix . '.' . $extension;
                $targetFile = $targetDir . $finalName;

                if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                    $message = 'ไฟล์ถูกอัปโหลดเรียบร้อยแล้ว: ' . htmlspecialchars($finalName, ENT_QUOTES, 'UTF-8');
                } else {
                    $message = 'ไม่สามารถย้ายไฟล์ที่อัปโหลดได้';
                }
            }
        }
    }
}

$imageFiles = [];
$uploadPattern = __DIR__ . '/uploads/*.{jpg,jpeg,png,gif,webp}';
$foundFiles = glob($uploadPattern, GLOB_BRACE);
if ($foundFiles !== false) {
    sort($foundFiles);
    foreach ($foundFiles as $absolute) {
        $imageFiles[] = $webPathPrefix . basename($absolute);
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>อัปโหลดและแสดงรูปภาพ</title>
    <style>
        body { font-family: sans-serif; max-width: 720px; margin: 2rem auto; }
        form { margin-bottom: 2rem; }
        .message { margin-bottom: 1rem; padding: 0.75rem; border-radius: 4px; background: #eef; }
        .gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 1rem; }
        .gallery img { width: 100%; height: auto; border: 1px solid #ccc; border-radius: 4px; object-fit: cover; }
        .empty { color: #666; }
    </style>
</head>
<body>
    <h1>อัปโหลดไฟล์รูปภาพ</h1>
    <?php if ($message !== ''): ?>
        <div class="message"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="fileToUpload">เลือกไฟล์รูปภาพ:</label>
        <input type="file" name="fileToUpload" id="fileToUpload" accept="image/*" required>
        <button type="submit">อัปโหลด</button>
    </form>

    <h2>รูปภาพที่อัปโหลดแล้ว</h2>
    <?php if (empty($imageFiles)): ?>
        <p class="empty">ยังไม่มีรูปภาพถูกอัปโหลด</p>
    <?php else: ?>
        <div class="gallery">
            <?php foreach ($imageFiles as $img): ?>
                <figure>
                    <img src="<?php echo htmlspecialchars($img, ENT_QUOTES, 'UTF-8'); ?>" alt="Uploaded image">
                    <figcaption><?php echo htmlspecialchars(basename($img), ENT_QUOTES, 'UTF-8'); ?></figcaption>
                </figure>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>
</html>
