<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
$user_id = $_SESSION['user_id'];

// 获取用户直播间
$stmt = $pdo->prepare("SELECT * FROM live_streams WHERE user_id = :user_id LIMIT 1");
$stmt->execute(['user_id' => $user_id]);
$stream = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>我的直播间</title>
    <script src="https://cdn.mux.com/player/mux-player.js"></script>
    <style> mux-player { width: 640px; height: 360px; display: block; margin: 20px auto; } </style>
</head>
<body>
    <h1>欢迎，用户 ID: <?php echo $user_id; ?></h1>
    <a href="logout.php">登出</a>

    <?php if (!$stream): ?>
        <h2>创建您的专属直播间</h2>
        <form method="POST" action="create_stream.php">
            <button type="submit">一键创建</button>
        </form>
    <?php else: ?>
        <h2>您的专属直播间</h2>
        <p>Stream Key: <?php echo htmlspecialchars($stream['stream_key']); ?> </br>(用于 OBS 推流，RTMP URL: rtmps://global-live.mux.com:443/app)</p>
        <iframe
            src="https://player.mux.com/<?php echo htmlspecialchars($stream['playback_id']); ?>?metadata-video-title=Q直播"
            style="aspect-ratio: 16/9; width: 100%; border: 0;"
            allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
            allowfullscreen="true"
        ></iframe>
        <p>低延迟模式已启用（~5-10s）。开始推流即可观看！</p>
    <?php endif; ?>
    
    <p>版权所有 Q 2025
</body>
</html>