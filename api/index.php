<?php 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    header('Content-Type: text/html; charset=UTF-8');

    require "vendor/autoload.php";

    $recipient = $_POST["recipient"];
    $subject = "The Great Piranha";
    $firstname = ucfirst($_POST["firstname"]);

    $signdata = str_replace(' ','+',$_POST['signdata']);
    $signdata = substr($signdata,strpos($signdata,",")+1);
    $signdata = base64_decode($signdata);

    $data = substr($signdata, strpos($signdata, ",")+1);
    $filename = "Sign.png";
    $encoding = "base64";
    $type = "image/png";
    $message = $_POST["message"];
    if ($message == "") {
        $message = "Dear parents of " . $firstname . ",
            <br><br>
            We are electronically delivering " . $firstname . "'s Sign (attached below) instead of taping it to your front door. Download the image, and be sure to print it before Saturday. On Friday night, tape the print-out to the front door.
            <br><br>
            - The Great Piranha";
    }

    $body = "<div style='color: black !important;'>". $message . "</div>";

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = "smtp-relay.sendinblue.com";
        $mail->SMTPAuth   = true;
        $mail->Username   = "thegreatpiranha@gmail.com";
        $mail->Password   = "RvmJXYDM70z9dVsB";
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom("thegreatpiranha@gmail.com", "The Great Piranha");
        $mail->addAddress($recipient);
        $mail->AddStringAttachment($signdata, $filename, $encoding, $type);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = strip_tags($body);

        $mail->send();

        echo "sent";
    } catch (Exception $e) {
        echo $firstname . "'s Sign failed to send: {$mail->ErrorInfo}";
    }

?>
