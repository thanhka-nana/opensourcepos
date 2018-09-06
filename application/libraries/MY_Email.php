<?php 

class MY_Email extends CI_Email {
	
	var	$feedback_address 		= "";
	var $default_cc_address 	= "";
	var $default_reply_name 	= "";
	var $default_email_address 	= "";
	var $default_sender_name 	= "";
	var $default_sender_address = "";
	var $default_bounce_address = "";
	
	function __construct($config = array())
	{
		parent::__construct($config);
	}
	function send_mail($subject, $body, $to = NULL, $reply_name = NULL, $reply_mail = NULL, $attachment = NULL) {$this->reply_to($reply_mail, $reply_name);
		$this->from($this->default_sender_address, $this->default_sender_name, $this->default_bounce_address);
		$this->set_mailtype('html');
		$this->subject($subject);
		$this->message($body);
		if ($to == NULL) {
			$to = $this->default_email_address;
			$this->cc($this->default_cc_address); 
		}
		if ($attachment) {
			$this->attach($attachment);
		}
		$this->to($to);
		return $this->send();
	}
 
    function send_newsletter($subject, $body, $to_mail, $letter_id) {
    	$this->set_alt_message("Je kan onze nieuwsbrief bekijken via de volgende link: http://www.runwalk.be/index.php/newsletters/show/".$letter_id);
    	$this->reply_to($this->default_email_address, $this->default_reply_name);
		$this->from($this->default_sender_address, $this->default_reply_name, $this->default_bounce_address);
		$this->set_mailtype('html');
		$subject = empty($subject) ? 'Nieuwsbrief #' . $letter_id : $subject;
		$this->subject($subject);
		$this->message($body);
		$this->to($to_mail);
		//$this->cc($this->default_cc_address); 
		$this->send();
    } 
    
}

?>