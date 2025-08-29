<?php
require 'config.php';

// 获取 playback_id
$playback_id = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($playback_id)) {
    die('错误: 无效的直播 ID');
}

// 验证 playback_id 是否存在
$stmt = $pdo->prepare("SELECT * FROM live_streams WHERE playback_id = :playback_id");
$stmt->execute(['playback_id' => $playback_id]);
$stream = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$stream) {
    die('错误: 直播间不存在');
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>观看直播</title>
    <script src="https://cdn.mux.com/player/mux-player.js"></script>
    <style> mux-player { width: 640px; height: 360px; display: block; margin: 20px auto; } </style>
</head>
<body>
    <h1>观看直播</h1>
        <iframe
            src="https://player.mux.com/<?php echo htmlspecialchars($stream['playback_id']); ?>?metadata-video-title=Q直播"
            style="aspect-ratio: 16/9; width: 100%; border: 0;"
            allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture;"
            allowfullscreen="true"
        ></iframe>
    <p>低延迟直播 (~5-10s)。享受观看！</p>
</body>
</html>