<?php
// 数据库配置
define('DB_HOST', 'sql');
define('DB_NAME', 'live');
define('DB_USER', '');
define('DB_PASS', '12345678');

// Mux API 配置
define('MUX_ACCESS_TOKEN', '111');
define('MUX_SECRET_KEY', '111');

// 启动 session
session_start();

// PDO 连接
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("数据库连接失败: " . $e->getMessage());
}
?>