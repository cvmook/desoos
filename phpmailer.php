<?php 
use PHPMailer\PHPmailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("phpmailer/src/Exception.php");
require_once("phpmailer/src/PHPMailer.php");
require_once("phpmailer/src/SMTP.php");

if (isset($_POST["sendmail"])) {
    $Mail = new PHPMailer(true);

    $Mail->isSMTP();
    $Mail->Host = 'smtp.strato.com';
    $Mail->SMTPAuth = true;
    $Mail->Username = 'info@caspervanmook.nl';
    $Mail->Password = 'idhigjdjidhgjjoid';
    $Mail->SMTPSecure = 'tls';
    $Mail->Port = 587;

    $Mail->setFrom('info@caspervanmook.nl');
    $Mail->addAddress($_POST['email']);
    $Mail->isHTML(true);
    $Mail->Subject = "Bevestiging Sponsor Aanmelding";
    $Mail->Body = $_POST['message'];
    $Mail->send();

    echo "<script>alert('Email was sent!')</script>";

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>phpmailer test</title>
</head>
<body>
    <form action="" method="post">
        Email: <input type="email" name="email"><br>
        Message info: <input type="text" name="message"><br>
        <input type="submit" value="Versturen" name="sendmail">
    </form>
</body>
</html>