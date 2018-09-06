<?php
require_once ("secure_area.php");
class Newsletters extends Secure_area
{
	function __construct()
	{
		parent::__construct('newsletters');
		$this->load->library('email');
	}
	
	function index() {
		$data['controller_name']=$this->get_controller_name();
		$this->load->view($data['controller_name'] . '/manage', $data);
	}
	
	/**
	* Stuurt een nieuwsbrief met bepaald id naar alle klanten!
	* Hoe te werk gaan:
	* 1. Eerst testen of de listing met PKs en email adressen werkt
	* 2. Kijken of er geen dubbele email adressen tussen zitten
	* 3. Eerst een enkele email naar eigen adres sturen om te kijken of die goed doorkomt
	* 4. De return statement weghalen en verzenden maar!!
	**/
	function send($newsid, $mailcounter = 100) 
	{	
		$data['newsid'] = $newsid;
		$subject = 'Inzamelactie loopschoenen Tanzania';
		$clients = $this->Customer->get_customers_in_mailing_list()->result_array();
		//echo "access denied";
		//return;
		
		$_SESSION['mailcounter'] = (isset($_SESSION['mailcounter'])) ? $_SESSION['mailcounter'] + $mailcounter : $mailcounter;
		if ($_SESSION['mailcounter'] >= count($clients)) {
			unset($_SESSION['mailcounter']);
			echo "Alle mails zijn verzonden";
			return;
		}
			
		for ($i = $_SESSION['mailcounter']; $i < $_SESSION['mailcounter'] + $mailcounter && $i < count($clients); $i++)
		{
			$data['clientId'] = $clients[$i][PERSON_ID];
			$data['firstname'] = $clients[$i][PERSON_FIRST_NAME];
			$html = $this->load->view($this->get_controller_name() . '/newsletter'.$newsid, $data, true);
			echo $data['clientId'];
			$email = $clients[$i][PERSON_EMAIL];
			if (!empty($email)) {
				echo " - ". $email;
 				//$this->email->send_newsletter($subject, $html, $clients[$i][PERSON_EMAIL], $newsid);
 				//$this->email->send_newsletter($subject, $html, "jeroen_peelaerts@hotmail.com", $newsid);
 				return;
			}
			echo "<br>";
		}
		
	}
	
	function send_once($newsid, $id) 
	{
		$theclient = $this->Customer->get_info($id);
		$data['clientId'] = $id;
		$data['firstname'] = $theclient->first_name;
		$data['newsid'] = $newsid;
		$html = $this->load->view($this->get_controller_name() . '/newsletter'.$newsid, $data, true);
		$this->email->send_newsletter(NULL, $html, $theclient->email, $newsid);
		echo "Nieuwsbrief " . $newsid .  " verstuurd naar " . $theclient->email;
	}

		
	function show($id = 0, $clientid = 0) 
	{
		$newsletter_id = $this->input->post('newsletter_id');
		$data['newsid'] = empty($newsletter_id) ? $id : $newsletter_id;
		if ($data['newsid'] == 0) {
			echo "Niks te zien hier..";
		}
		if ($clientid != 0) {
			$theclient = $this->Customer->get_info($clientid);
			$data['firstname'] = $theclient->first_name;
			$data['clientId'] = $clientid;
		}
		$this->load->view($this->get_controller_name() . '/newsletter'.$data['newsid'], $data);
	}
	
}

?>