<?php
require_once(__DIR__ . '/../../vendor/autoload.php');

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../config/');
$dotenv->safeLoad();

// for docker
$use = $_ENV['USE'] ?? getenv('USE');
$password = $_ENV['USE_PASSWORD'] ?? getenv('USE_PASSWORD');
$discordWidget = $_ENV['DISCORDWIDGET'] ?? getenv('DISCORDWIDGET');
$email = $_ENV['EMAIL'] ?? getenv('EMAIL');
$emailPassword = $_ENV['EMAIL_PASSWORD'] ?? getenv('EMAIL_PASSWORD');
$token = $_ENV['TOKEN'] ?? getenv('TOKEN');
$jennId = $_ENV['JENN_ID'] ?? getenv('JENN_ID');
$makiId = $_ENV['MAKI_ID'] ?? getenv('MAKI_ID');
$strikeId = $_ENV['STRIKE_ID'] ?? getenv('STRIKE_ID');
$sonoId = $_ENV['SONO_ID'] ?? getenv('SONO_ID');
$tavId = $_ENV['TAV_ID'] ?? getenv('TAV_ID');
$davidId = $_ENV['DAVID_ID'] ?? getenv('DAVID_ID');
$amazeId = $_ENV['AMAZE_ID'] ?? getenv('AMAZE_ID');
$toastId = $_ENV['TOAST_ID'] ?? getenv('TOAST_ID');
$serverId = $_ENV['SERVER_ID'] ?? getenv('SERVER_ID');

function fetchUser($id) {
    $token = $_ENV['TOKEN'];
    $url = "https://discord.com/api/v9/users/$id";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bot $token",
        "Content-Type: application/json"
    ]);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpcode !== 200) {
        throw new Exception("Error status code: $httpcode");
    }

    curl_close($ch);
    $userData = json_decode($response, true);

    // Fetch avatar URL separately
    $avatarUrl = "https://cdn.discordapp.com/avatars/$id/{$userData['avatar']}.png";
    
    // Construct user data object
    $userDataObject = [
        'username' => $userData['username'],
        'avatarUrl' => $avatarUrl
    ];

    return $userDataObject;
}

// Initialize array to store users
$users = [];

// Fetch each user based on their ID from .env
$userIds = [
    'JENN_ID' => $_ENV['JENN_ID'],
    'MAKI_ID' => $_ENV['MAKI_ID'],
    'STRIKE_ID' => $_ENV['STRIKE_ID'],
    'SONO_ID' => $_ENV['SONO_ID'],
    'TAV_ID' => $_ENV['TAV_ID'],
    'DAVID_ID' => $_ENV['DAVID_ID'],
    'AMAZE_ID' => $_ENV['AMAZE_ID'],
    'TOAST_ID' => $_ENV['TOAST_ID'],
];

try {
    foreach ($userIds as $userId => $discordId) {
        $userData = fetchUser($discordId);
        $users[] = $userData;
    }
    header('Content-Type: application/json');
    echo json_encode($users);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
