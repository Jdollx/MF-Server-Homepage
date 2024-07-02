<?php
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
