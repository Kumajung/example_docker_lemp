<?php
$host = getenv('MYSQL_HOST') ?: 'db';
$port = getenv('MYSQL_PORT') ?: '3306';
$database = getenv('MYSQL_DATABASE') ?: 'app_db';
$username = getenv('MYSQL_USER') ?: 'app_user';
$password = getenv('MYSQL_PASSWORD') ?: 'app_pass';

$dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $database);
$databaseStatus = 'not connected';
$message = '';
$attempts = 0;
$maxAttempts = 5;
$delaySeconds = 2;

while ($attempts < $maxAttempts) {
    try {
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $databaseStatus = 'connected';
        $statement = $pdo->query('SELECT NOW() AS server_time');
        $row = $statement->fetch();
        $message = 'Database time: ' . ($row['server_time'] ?? 'unknown');
        break;
    } catch (Throwable $error) {
        $databaseStatus = 'not connected';
        $message = $error->getMessage();
        $attempts++;
        if ($attempts < $maxAttempts) {
            sleep($delaySeconds);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Docker PHP Stack</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; }
        .status { padding: 1rem; border-radius: 4px; margin-bottom: 1rem; }
        .ok { background: #e6ffed; border: 1px solid #2ecc71; }
        .fail { background: #ffecec; border: 1px solid #e74c3c; }
        code { background: #f4f4f4; padding: 0.2rem 0.4rem; border-radius: 3px; }
        ul { list-style: none; padding: 0; }
        li { margin-bottom: 0.3rem; }
    </style>
</head>
<body>
<h1>PHP + Nginx + MySQL + phpMyAdmin</h1>
<div class="status <?php echo $databaseStatus === 'connected' ? 'ok' : 'fail'; ?>">
    <strong>Database status:</strong> <?php echo htmlspecialchars($databaseStatus, ENT_QUOTES, 'UTF-8'); ?><br>
    <strong>Message:</strong> <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
</div>
<h2>Connection Settings</h2>
<ul>
    <li>Host: <code><?php echo htmlspecialchars($host, ENT_QUOTES, 'UTF-8'); ?></code></li>
    <li>Port: <code><?php echo htmlspecialchars($port, ENT_QUOTES, 'UTF-8'); ?></code></li>
    <li>Database: <code><?php echo htmlspecialchars($database, ENT_QUOTES, 'UTF-8'); ?></code></li>
    <li>User: <code><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></code></li>
</ul>
<p>Serve this page at <code>http://localhost:<?php echo htmlspecialchars(getenv('HTTP_PORT') ?: '8080', ENT_QUOTES, 'UTF-8'); ?></code>.</p>
<p>phpMyAdmin is available at <code>http://localhost:<?php echo htmlspecialchars(getenv('PHPMYADMIN_PORT') ?: '8081', ENT_QUOTES, 'UTF-8'); ?></code>.</p>
</body>
</html>

