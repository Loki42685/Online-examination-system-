<?php

use PHPMailer\PHPMailer\PHPMailer;

function configureMail($mail){

    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'onlineexaminationsystem000@gmail.com'; // NEW PROJECT GMAIL
    $mail->Password   = 'nomnewnqpjlfmuwi'; // NEW APP PASSWORD (no spaces)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    $mail->setFrom('onlineexaminationsystem000@gmail.com','Online Examination System');

}