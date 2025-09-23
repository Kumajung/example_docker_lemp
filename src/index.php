<?php
$databaseStatus = 'not connected';
$message = '';

try {
    $dsn = 'mysql:host=db;port=3306;dbname=app_db;charset=utf8mb4';
    $pdo = new PDO($dsn, 'app_user', 'app_pass', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $databaseStatus = 'connected';
    $statement = $pdo->query('SELECT NOW() AS current_time');
    $row = $statement->fetch();
    $message = 'Database time: ' . ($row['current_time'] ?? 'unknown');
} catch (Throwable $error) {
    $message = $error->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
     <meta charset="UTF-8">
     <title>Docker PHP Stack</title>
     <style>
     body {
          font-family: Arial, sans-serif;
          margin: 2rem;
     }

     .status {
          padding: 1rem;
          border-radius: 4px;
          margin-bottom: 1rem;
     }

     .ok {
          background: #e6ffed;
          border: 1px solid #2ecc71;
     }

     .fail {
          background: #ffecec;
          border: 1px solid #e74c3c;
     }

     code {
          background: #f4f4f4;
          padding: 0.2rem 0.4rem;
          border-radius: 3px;
     }
     </style>
</head>

<body>
     <h1>PHP + Nginx + MySQL + phpMyAdmin</h1>
     <div class="status <?php echo $databaseStatus === 'connected' ? 'ok' : 'fail'; ?>">
          <strong>Database status:</strong> <?php echo htmlspecialchars($databaseStatus, ENT_QUOTES, 'UTF-8'); ?><br>
          <strong>Message:</strong> <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
     </div>
     <p>Serve this page at <code>http://localhost:8080</code>.</p>
     <p>phpMyAdmin is available at <code>http://localhost:8081</code> using the credentials:</p>
     <h1>PHP Didy Test Deploy</h1>
     <h2>Didy.com</h2>
     <h3>Nong Kuma Testing</h3>
     <ul>
          <li>Server: <code>db</code></li>
          <li>Username: <code>app_user</code></li>
          <li>Password: <code>app_pass</code></li>
     </ul>
</body>

</html>