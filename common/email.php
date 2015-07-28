<?php

require('lib/PHPMailer/PHPMailerAutoload.php');

class Email extends PHPMailer
{
    public function __construct($config, $isHTML = false)
    {
        $this->isSMTP();
        $this->SMTPAuth = true;
        $this->SMTPSecure = 'ssl';
        $this->Port = 465;

        $this->Host = $config['smtp_host'];
        $this->Username = $config['smtp_user'];
        $this->Password = $config['smtp_password'];

        $this->From = $config['email_system'];
        $this->FromName= 'no-reply';

        $this->isHTML($isHTML);
    }

    public function SendEmail($to, $subject, $body)
    {
        $this->addAddress($to);
        $this->Subject = $subject;
        $this->Body = $body;

        return $this->send();
    }
}