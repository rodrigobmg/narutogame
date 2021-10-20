<?php
class Email {
	function Envia_Email($Nome_Remetente, $Email_Remetente, $Nome_Destinatario, $Email_Destinatario, $Assunto, $Msg, $rpName = "", $rpAddr = "") {
			require_once("email/email/email_message.php");
			unset($email_message);
			$email_message = new email_message_class;
		
			$from_address = $Email_Remetente;
			$from_name = $Nome_Remetente;
			$reply_name = $rpName ? $rpName : $from_name;
			$reply_address = $rpAddr ? $rpAddr : $from_address;
			$error_delivery_name = $from_name;
			$error_delivery_address = $from_address;
			$subject = $Assunto;
			$msg = $Msg;
			$to_name = $Nome_Destinatario;
			$to_address = $Email_Destinatario;
			//$to_address = "angelomrodrigues@gmail.com";
			$email_message->SetEncodedEmailHeader("To",$to_address,$to_name);
			$email_message->SetEncodedEmailHeader("From",$from_address,$from_name);
			$email_message->SetEncodedEmailHeader("Reply-To",$reply_address,$reply_name);
			$email_message->SetHeader("Sender",$from_address);
		
			if(defined("PHP_OS") && strcmp(substr(PHP_OS,0,3),"WIN")) {
				$email_message->SetHeader("Return-Path",$error_delivery_address);
			}
			$email_message->SetEncodedHeader("Subject",$subject);
			$html_message=stripslashes($msg);
			$email_message->CreateQuotedPrintableHTMLPart($html_message,"",$html_part);
			$related_parts = array( $html_part );
			$email_message->CreateRelatedMultipart($related_parts,$html_parts);
			$text_message="Esta é uma Mensagem em HTML. Caso não esteja vendo esta Mensagem direito, favor desbloquear a visualização de Imagens de seu leitor de E-mail";
			$email_message->CreateQuotedPrintableTextPart($email_message->WrapText($text_message),"",$text_part);
			$alternative_parts = array( $text_part, $html_parts );
			$email_message->AddAlternativeMultipart($alternative_parts);
			$error=$email_message->Send();
			if(strcmp($error,"")) { echo "<div class='Alert'>Error: $Email_Destinatario - $error\n</div><br>"; }
	}
}
?>