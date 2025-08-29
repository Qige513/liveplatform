<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>MyLiveStudio - 类似 LDLive 的直播平台</title>
    <style> body { font-family: Arial; } form { margin: 20px; } </style>
</head>
<body>
    <h1>欢迎来到 MyLiveStudio</h1>
    <p>基于 Mux 的高质量低延迟直播，一键创建专属直播间。</p>

    <?php
    if (isset($_SESSION['user_id'])) {
        header('Location: dashboard.php');
        exit;
    }

    // 处理注册
    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->execute(['username' => $username, 'password' => $password]);
            echo "<p>注册成功！请登录。</p>";
        } catch (PDOException $e) {
            echo "<p>注册失败: 用户名已存在。</p>";
        }
    }

    // 处理登录
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: dashboard.php');
            exit;
        } else {
            echo "<p>登录失败: 用户名或密码错误。</p>";
        }
    }
    ?>

    <h2>注册</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="用户名" required><br>
        <input type="password" name="password" placeholder="密码" required><br>
        <button type="submit" name="register">注册</button>
    </form>

    <h2>登录</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="用户名" required><br>
        <input type="password" name="password" placeholder="密码" required><br>
        <button type="submit" name="login">登录</button>
    </form>
</body>
</html>