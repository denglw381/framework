<?php

//error_reporting(E_ALL);
error_reporting(E_STRICT);

require_once(dirname(__FILE__).'/../utile/phpMail/class.phpmailer.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mail             = new PHPMailer();

//$body             = file_get_contents('contents.html');
//$body             = eregi_replace("[\]",'',$body);
$body 				= '今天是星期一';

$mail->IsSMTP(); // telling the class to use SMTP
$mail->Host       = "smtp.126.com"; // SMTP server
$mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Host       = "smtp.126.com"; // sets the SMTP server
$mail->Port       = 25;                    // set the SMTP port for the GMAIL server
$mail->Username   = "webmaster@conjure.com.cn"; // SMTP account username
$mail->Password   = "htexam";        // SMTP account password
$mail->CharSet = "UTF-8";
$mail->SetFrom('webmaster@conjure.com.cn', 'denglianwen');

$mail->AddReplyTo("denglianwen@zhisland.com","dengliawnen");

$mail->Subject    = "测试邮件";

$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

$mail->MsgHTML($body);

$address = "530989121@qq.com";
$mail->AddAddress($address, "John Doe");

$mail->AddAttachment("images/phpmailer.gif");      // attachment
$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}

?>
