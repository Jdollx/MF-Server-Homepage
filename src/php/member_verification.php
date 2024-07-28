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


// Discord API endpoint
$discordAPIEndpoint = 'https://discord.com/api/users/@me/guilds';

// Get the Discord username from the form input
$discordUser = $_POST['discord_user'];

// Set up cURL to make the request to Discord API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $discordAPIEndpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: ' . $_SERVER['TOKEN'],
]);

// Execute the request
$response = curl_exec($ch);

// Check for errors
if ($response === false) {
    die('Error fetching user guilds: ' . curl_error($ch));
}

// Close cURL session
curl_close($ch);

// Decode the JSON response
$guilds = json_decode($response, true);

// Check if the user is in the specific server
$serverID = 'YOUR_SERVER_ID';
$isInServer = false;
foreach ($guilds as $guild) {
    if ($guild['id'] === $serverID) {
        $isInServer = true;
        break;
    }
}

// Output result
if ($isInServer) {
    echo "$discordUser is in the server.";
    // Proceed with form submission or other actions
} else {
    echo "$discordUser is not in the server.";
    // Display an error message or take appropriate action
}
?>
