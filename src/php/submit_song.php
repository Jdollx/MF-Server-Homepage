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
$dotenv->load();

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
        $mail->Password = $_ENV['PASSWORD']; // Your SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Sender
        $mail->setFrom("musicfeedbackdisc@gmail.com", "Music Feedback Discord");

        // Recipients
        $mail->addAddress("musicfeedbackdisc@gmail.com"); // Receiver email

        // Content
        $mail->isHTML(true);
        $mail->Subject = "New Song Submission from $discordUser";
        $mail->Body = "A new song has been submitted.<br>Discord User: $discordUser<br>SoundCloud Link: $songLink";

        // Send email
        $mail->send();
        return "Email sent successfully";
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if discord_user and song_link are set
    if (isset($_POST["discord_user"]) && isset($_POST["song_link"])) {
        $discordUser = $_POST["discord_user"];
        $songLink = $_POST["song_link"];

        // Send email
        $result = sendMail($discordUser, $songLink);
        echo $result;
    } else {
        echo "Error: Discord user or song link not provided";
    }
} else {
    echo "Error: Form data not submitted";
}

?>
