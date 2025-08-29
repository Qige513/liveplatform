<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
$user_id = $_SESSION['user_id'];

// 检查是否已有直播间
$stmt = $pdo->prepare("SELECT COUNT(*) FROM live_streams WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
if ($stmt->fetchColumn() > 0) {
    header('Location: dashboard.php');
    exit;
}

// 调用 Mux API
$ch = curl_init('https://api.mux.com/video/v1/live-streams');
$auth = base64_encode(MUX_ACCESS_TOKEN . ':' . MUX_SECRET_KEY);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, MUX_ACCESS_TOKEN . ':' . MUX_SECRET_KEY);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'playback_policy' => ['public'],
    'new_asset_settings' => ['playback_policy' => ['public']],
    'latency_mode' => 'low'  // 低延迟
]));

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true)['data'];
if (!$data) {
    die('Mux API 错误: ' . $response);
}

// 保存到数据库
$stmt = $pdo->prepare("INSERT INTO live_streams (user_id, mux_stream_id, stream_key, playback_id) VALUES (:user_id, :mux_id, :key, :playback)");
$stmt->execute([
    'user_id' => $user_id,
    'mux_id' => $data['id'],
    'key' => $data['stream_key'],
    'playback' => $data['playback_ids'][0]['id']
]);

header('Location: dashboard.php');
exit;
?>