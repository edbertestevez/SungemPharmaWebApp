<?php
  $name = $_POST['txt_fullname'];
  $from = $_POST['txt_email'];
  $comment = $_POST['txt_comment'];
  
	$subject = 'Inquiry from ' . $from;
	$message = $comment;
	$headers = 'From: '.$from . "\r\n" .
			'Reply-To: '.$from . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
	
    
		if(mail("jacotabio@gmail.com", $subject, $message, $headers) && 
		mail("aldearickyofficial@gmail.com", $subject, $message, $headers) &&
    mail("trebdenosaj@gmail.com", $subject, $message, $headers) &&
    mail("johnbarrobang@gmail.com", $subject, $message, $headers)){
        header("location: index.php?inquiry=sent");
    }
?>