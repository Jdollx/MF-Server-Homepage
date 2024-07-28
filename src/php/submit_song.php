<?php

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/../../vendor/autoload.php');

// Import PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../config/', '.env');
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


// Function to send email
function sendMail($discordUser, $songLink)
{
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com"; // Change this to match your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['USE']; // Your SMTP username
        $mail->Password = $_ENV['USE_PASSWORD']; // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender
        $mail->setFrom("musicfeedbackdisc@gmail.com", "Music Feedback Discord");

        // Recipients
        $mail->addAddress("musicfeedbackdisc@gmail.com"); // Receiver email

        // Content
        $mail->isHTML(true);
        $mail->Subject = "New Song Submission from $discordUser";
        $mail->Body = "A new song has been submitted.<br>Discord User: $discordUser<br>Spotify Link: $songLink";

        // Send email
        $mail->send();
        echo "success";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// verify that the user is in the server
function verifyUser($discordUser) {
    $token = $_ENV['TOKEN'];
    $server_id = $_ENV['SERVER_ID'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://discord.com/api/v10/guilds/$server_id/members/search?query=$discordUser");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bot $token"
    ]);

    $result = curl_exec($ch);
    curl_close($ch);

    $members = json_decode($result, true);

    foreach ($members as $member) {
        if (isset($member['user']['username']) && $member['user']['username'] == $discordUser) {
            return true;
        }
    }
    return false;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if discord_user and song_link are set
    if (isset($_POST["discord_user"]) && isset($_POST["song_link"])) {
        $discordUser = $_POST["discord_user"];
        $songLink = $_POST["song_link"];

        // Verify Discord user
        if (verifyUser($discordUser)) {
            // Send email
            $result = sendMail($discordUser, $songLink);
        } else {
            echo "Error: Discord user is not a member of the server";
        }
    } else {
        echo "Error: Discord user or song link not provided";
    }
} else {
    echo "Error: Form data not submitted";
}
?>
