<?php
// person table name constants
define("PERSON_TABLE_NAME", "people");
define("PERSON_ID", "person_id");
define("PERSON_PK", PERSON_TABLE_NAME . "." . PERSON_ID);
define("PERSON_GENDER", "gender");
define("PERSON_BIRTH_DATE", "birthdate");
define("PERSON_NAME", "last_name");
/*
 * discriminator column for person subclasses used by JPA.
* 0 = customer
* 1 = employee
* 2 = supplier
*/
define("PERSON_TYPE", "type");
// define("PERSON_NAME_FIELD", PERSON_TABLE_NAME . "." . PERSON_NAME);
define("PERSON_FIRST_NAME", "first_name");
define("PERSON_LAST_NAME", "last_name");
define("PERSON_EMAIL", "email");
define("PERSON_IN_MAILING_LIST", "in_mailing_list");
define("PERSON_CONSENT", "consent");
define("PERSON_TELEPHONE", "phone_number");
define("PERSON_ADDRESS", "address_1");
define("PERSON_ADDRESS_2", "address_2");
define("PERSON_COMMENTS", "comments");
define("PERSON_COUNTRY", "country");
define("PERSON_STATE", "state");
define("PERSON_CITY", "city");
define("PERSON_ZIP", "zip");

define("EXISTING_CUSTOMER", "existing_customer");
define("SHOE_MODEL", "shoe_model");
define("SHOE_SIZE", "shoe_size");
define("GAIT_VIDEO", "gait_video");
define("GAIT_VIDEO_PATH", "gait_video_path");

/** some array keys */
define("CAPTCHA", "captcha");
define ("GIF_PIXEL", "R0lGODlhAQABAJAAAP8AAAAAACH5BAUQAAAALAAAAAABAAEAAAICBAEAOw==");

class Site extends CI_Controller
{

    public $CI = NULL;

    function __construct()
    {
        parent::__construct();
        $this->CI = & get_instance();
        $this->load->library('email');
        $this->load->library('form_validation');
        $this->load->model('Analysis');
        /* Set locale to Dutch */
        setlocale(LC_ALL, 'nl_BE');
    }

    function _gait_video_dir()
    {
        return "./uploads";
    }

    function _gait_video_filename($first_name, $last_name, $extension)
    {
        return "${first_name}_${last_name}.$extension";
    }

    function handle_video_upload()
    {
        $first_name = $this->input->post(PERSON_FIRST_NAME);
        $last_name = $this->input->post(PERSON_LAST_NAME);
        if (!empty($first_name) && !empty($last_name))
        {
            $filename = $_FILES["file"]["name"];
            $file_ext = pathinfo($filename,PATHINFO_EXTENSION);

            $gait_video_dir = $this->_gait_video_dir();
            $gait_video_filename = $this->_gait_video_filename($first_name, $last_name, $file_ext);
            $gait_video_path = $gait_video_dir . DIRECTORY_SEPARATOR . $gait_video_filename;

            $config = array(
                'upload_path' => $gait_video_dir,
                'allowed_types' => 'video/*',
                'max_size' => '16384',
                'file_name' => $gait_video_filename
            );

            $this->load->library('upload', $config);
            if (!$this->upload->do_upload("file"))
            {
                $this->output->set_status_header(400);
                echo $this->upload->display_errors();
            }
            else
            {
                if (strlen($this->upload->display_errors()) == 0)
                {
                    $this->output->set_status_header(400);
                    echo $this->lang->line('upload_no_file_selected');
                }
            }
        }
        else
        {
            $this->output->set_status_header(400);
            echo $this->lang->line("gait_video_customer_name_required");
        }
    }

    function video_form()
    {
        $this->load->view('site/video_form');
    }

    function advice_form($embed_page = 0)
    {
        $data['embed_page'] = $embed_page;

        // set validation rules for subscription
        $this->form_validation->set_rules(PERSON_NAME, "lang:common_last_name", "trim|required|min_length[5]|max_length[50]");
        $this->form_validation->set_rules(PERSON_FIRST_NAME, "lang:common_first_name", "trim|required");
        $this->form_validation->set_rules(PERSON_ADDRESS, "lang:common_address_1", "trim|required");
        $this->form_validation->set_rules(PERSON_ZIP, "lang:common_zip", "trim|required");
        $this->form_validation->set_rules(PERSON_CITY, "lang:common_city", "trim|required");
        $this->form_validation->set_rules(PERSON_EMAIL, "lang:common_email", "trim|required|valid_email");
        $this->form_validation->set_rules(EXISTING_CUSTOMER, "lang:existing_customer", "required");
        $this->form_validation->set_rules(SHOE_SIZE, "lang:shoe_size", "trim|required|numeric|greater_than_equal_to[35]|less_than_equal_to[49]");
        $this->form_validation->set_rules(GAIT_VIDEO, "lang:gait_video", "callback_gait_video_check");

        $person_data = (array) $this->Person->get_populated_person(TRUE);

        if ($this->form_validation->run() == FALSE)
        {
            $data['person_info'] = $person_data;
            $data['error_messages'] = $this->form_validation->get_error_messages();
            $this->load->view('site/advice_form', $data);
        }
        else
        {
            $customer_data = array(PERSON_IN_MAILING_LIST => 1, PERSON_CONSENT => 1);

            if ($this->input->post(EXISTING_CUSTOMER))
            {
                $customers = $this->Customer->get_customer_by_name($this->input->post(PERSON_LAST_NAME, TRUE),
                    $this->input->post(PERSON_FIRST_NAME, TRUE))->result_array();
            }
            else
            {
                $customers = $this->Customer->get_customer_by_email($this->input->post(PERSON_LAST_NAME),
                    $this->input->post(PERSON_FIRST_NAME), $this->input->post(PERSON_EMAIL))->result_array();
            }

            $person_id = count($customers) ? $customers[0]['person_id'] : FALSE;

            unset($person_data['comments'], $person_data[PERSON_ID]);
            $customer_data = array('deleted' => 0);

            $analysis_data = array('start_date' => date('Y-m-d G:i:s'),
                'creation_date' => date('Y-m-d G:i:s'),
                'comments' => $this->input->post(ANALYSIS_COMMENTS),
                'person_id' => $customers[0]['person_id']);

            //TODO create new model for recording
            //TODO insert suspended sale for this customer (and check how to continue flow using quotes)

            if ($this->Customer->save_customer($person_data, $customer_data, $person_id) &&
                    $this->Analysis->save($analysis_data))
            {
               $email_data = array_merge($person_data, $analysis_data);
               // send mail to notify admin about user subscription
               $this->_send_advice_processed_mail($email_data);
               unset($_SESSION[GAIT_VIDEO_PATH]);
               $data['themessage'] = "Je gegevens werden goed verzonden.";
            }
            else
            {
                // redirect session variable not set, could be a naked post to this method
                $data['themessage'] = "Er is iets misgelopen bij het registreren van uw gegevens. Probeer het later nog eens opnieuws.";
            }
            $this->load->view('site/formsent', $data);
        }
    }

    function _send_advice_processed_mail($email_data)
    {
        $mail_body = $this->load->view('site/advice_processed_mail', $email_data, TRUE);
        $fullname = $email_data[ PERSON_NAME ] . " " . $email_data[ PERSON_FIRST_NAME ];
        $subject = sprintf($this->lang->line("advice_processed_subject"), $fullname);
        $this->email->send_mail($subject, $mail_body, $this->email->feedback_address, $fullname, $email_data[PERSON_EMAIL]);
        //echo $this->email->print_debugger();
    }


    function cancel_appointment()
	{
        $analysis_data = array('appointment_cancelled' => TRUE);
		$success = $this->Analysis->save_by_appointment_extref($analysis_data, $this->input->post('appointment_extref'));
		echo json_encode(array('success' => $success, 'data' => $analysis_data));
	}

	function reschedule_appointment()
	{
        $analysis_data = array('start_date' => date('Y-m-d G:i:s', $this->input->post('appointment_timestamp')));
		$success = $this->Analysis->save_by_appointment_extref($analysis_data, $this->input->post('appointment_extref'));
		echo json_encode(array('success' => $success, 'data' => $analysis_data));
	}
	
	function schedule_appointment()
	{
		if ($this->input->post('existing_customer'))
		{
			$customers = $this->Customer->get_customer_by_name($this->input->post('last_name', TRUE),
				$this->input->post('first_name', TRUE))->result_array();
		}
		else
		{
			$customers = $this->Customer->get_customer_by_email($this->input->post('last_name'),
				$this->input->post('first_name'), $this->input->post('email'))->result_array();
		}

		$success = TRUE;
		$person_id = count($customers) ? $customers[0]['person_id'] : FALSE;

		$person_data = (array) $this->Person->get_populated_person(FALSE);
		unset($person_data['comments']);
		$customer_data = array('deleted' => 0);
		$success &= $this->Customer->save_customer($person_data, $customer_data, $person_id);
		$customers[] = $person_data;
		// customer found. just fetch id
		$analysis_data = array('start_date' => date('Y-m-d G:i:s', $this->input->post('appointment_timestamp')),
				'appointment_extref' => $this->input->post('appointment_extref'),
				'appointment_cancelled' => FALSE,
				'creation_date' => date('Y-m-d G:i:s'),
				'comments' => $this->input->post('comments'), 
				'person_id' => $customers[0]['person_id']);
		$success = $this->Analysis->save($analysis_data);
		echo json_encode(array('success' => $success, 'data' => $analysis_data));
	}

	function contact()
	{
		$data[ CAPTCHA ] = $this->_generate_captcha();
		$this->form_validation->set_rules(PERSON_NAME, "lang:site_name", "trim|required|min_length[5]|max_length[50]");
		$this->form_validation->set_rules(PERSON_EMAIL, "lang:common_email", "trim|required|valid_email");
		$this->form_validation->set_rules(PERSON_COMMENTS, "lang:common_comments", "trim|required");
		$this->form_validation->set_rules(CAPTCHA, "lang:common_captcha", "required|callback_captcha_check");
		if ($this->form_validation->run() == FALSE)
		{
			$data['error_messages'] = $this->form_validation->get_error_messages();
			$this->load->view('site/contact', $data);
		}
		else
		{
			$name = $this->input->post(PERSON_NAME, TRUE);
			$subject = "Vraag van $name ";
			$mail_body = "Er is een contactformulier vanaf je website verstuurd.<br>".
			"De volgende gegevens werden ingevoerd:<br><br>".
			"Naam:  " . $name ."<br>".
			"E-mailadres: " . $this->input->post(PERSON_EMAIL, TRUE) ."<br><br>".
			"Bericht: " . htmlspecialchars($this->input->post(PERSON_COMMENTS, TRUE)) ."<br><br>".
			"Je kan op deze mail antwoorden door op reply te drukken.";

			$this->email->send_mail($subject, $mail_body, NULL, $this->input->post(PERSON_NAME, TRUE), $this->input->post(PERSON_EMAIL, TRUE));

			$data['themessage'] = 'Er werd een e-mail verzonden. We zullen uw vragen zo snel mogelijk proberen te beantwoorden.';

			$this->load->view('site/formsent', $data);
		}
	}

	/**
	 * Functie om een e-mail adres te unsubscriben..
	 */
	function unsubscribe($id=0)
	{
		if ($id == 0) {
			redirect('site/', 'location');
		}
		$data['id'] = $id;
		$clientinfo = $this->Customer->get_info($id);
		$this->form_validation->set_rules(PERSON_EMAIL, "lang:common_email", 'trim|max_length[150]|valid_email|required');
		$this->form_validation->set_error_delimiters('<span class="boldred error">', '</span>');

		if ($this->form_validation->run() == FALSE)
		{
			$data['error_messages'] = $this->form_validation->get_error_messages();
			$this->load->view('newsletters/unsubscribe', $data);
		}
		else
		{
			$data['themessage'] = "Het opgegeven e-mail adres werd niet gevonden in onze database.";
			if (is_object($clientinfo)) {
				if ($this->input->post('mail') == $clientinfo->email)
				{
					if ($this->Customer->unsubscribe($id))
					{
						$data['themessage'] = "U werd met success van onze mailing list verwijderd. U zult onze nieuwsbrief in de toekomst niet meer ontvangen.";
						$body = $clientinfo->name . " " . $clientinfo->firstname . " heeft gevraagd om de nieuwsbrief niet meer aan te krijgen.<br><br>Het e-mail adres ".$clientinfo->website . " werd uitgeschreven.";
						$this->email->send_mail("Uitschrijving Nieuwsbrief", $body);
					}
				}
			}
			$this->load->view('site/formsent', $data);
		}
	}
	
	function _generate_captcha()
	{
		$this->load->helper('string');
		$this->load->helper('captcha');
		$this->load->library('session');
		$this->load->library('image_lib', array('image_library' => 'gd'));

		$word = random_string('numeric', 4);
		if ($this->session->flashdata(CAPTCHA))
		{
			$word = $this->session->flashdata(CAPTCHA);
		}
		else
		{
			$this->session->set_flashdata(CAPTCHA, $word);
		}

		return create_captcha(array(
			'word'   => $word,
			'img_path'  => './captcha/',
			'img_url'   => '/captcha/',
			'font_path'    => './system/fonts/texb',
			'img_width'    => 110,
			'img_height' => '30',
			'expiration' => 7200
		));

	}
	
	function feedback_form($feedback_token=0)
	{
		$this->load->model('Analysis');
		$this->form_validation->set_rules(ANALYSIS_SCORE, "lang:score", "required|callback_score_check");
		$this->form_validation->set_error_delimiters('<div class="boldred error">', '</div>');
		$analysis = $this->Analysis->get_analysis_by_token_id($feedback_token);
		if (sizeof($analysis) > 0 && is_null($analysis[ ANALYSIS_SCORE ]))
		{
			if ($this->form_validation->run() == FALSE)
			{
				$data['error_messages'] = $this->form_validation->get_error_messages();
				$data[ANALYSIS_FEEDBACK_TOKEN] = $feedback_token;
				$this->load->view("site/feedback_form", $data);
			}
			else
			{
				// update analysis record
                $analysis_data = array( 
					ANALYSIS_COMMENTS => $this->input->post(ANALYSIS_COMMENTS),
					ANALYSIS_SCORE => $this->input->post(ANALYSIS_SCORE),
                    ANALYSIS_FEEDBACK_DATE => date("Y-m-d H:i:s")
                );
				$this->Analysis->save($analysis_data, $analysis[ANALYSIS_ID]);
				$data['themessage'] = $this->lang->line('feedback_posted');
				$this->_send_feedback_processed_mail($analysis);
				$this->load->view("site/formsent", $data);
			}
		}
		else
		{
			// tell user feedback was already given..
			$data['themessage'] = $this->lang->line('feedback_given');
			$this->load->view("site/formsent", $data);
		}
	}
	
	function _send_feedback_processed_mail($analysis)
	{
		$analysis[ ANALYSIS_START_DATE ] = $feedback_date;
		$interval = $creation_date->diff($feedback_date);
		$analysis[ 'interval' ] = $interval->format('%r%a');
		$creation_date = strftime('%d %B %Y', $creation_date->getTimestamp());
		$analysis[ ANALYSIS_CREATION_DATE ] = $creation_date;
		
		$mail_body = $this->load->view('site/feedback_processed_mail', $analysis, TRUE);
		$subject = $this->lang->line("feedback_processed_subject");
		$fullname = $analysis[ PERSON_NAME ] . " " . $analysis[ PERSON_FIRST_NAME ];
		$this->email->send_mail($subject, $mail_body, $this->email->feedback_address, $fullname, $analysis[PERSON_EMAIL]);
		//echo $this->email->print_debugger();
	}
	
	function feedback_mails($feedback_id = 0)
	{
		$this->load->model('Analysis');
        if ($feedback_id > 0)
        {
            $this->load->library('session');
            if(!$this->Employee->is_logged_in())
            {
                redirect('login');
            }
            $analysis = $this->Analysis->get_analysis_by_feedback_id($feedback_id);
            $analyses = array(0 => $analysis);
        }
        else
        {
            $analyses = $this->Analysis->get_analysis_by_unsent_feedback();
        }

		foreach($analyses as $index => $analysis) {
			// compose a mail and send to this customer..
			$subject = $this->lang->line('feedback_mail_subject');
			$creation_date = strftime('%d %B %Y', strtotime($analysis[ANALYSIS_CREATION_DATE]));
			// generate token
			$feedback_token = md5(uniqid($analysis[ANALYSIS_FEEDBACK_TOKEN], true));
			$data[ANALYSIS_CREATION_DATE] = $creation_date;
			$data[ANALYSIS_FEEDBACK_TOKEN] = $feedback_token;
			// mark mail as sent and unread
            $analysis_data = array(
                    ANALYSIS_FEEDBACK_TOKEN => $feedback_token,
                    ANALYSIS_FEEDBACK_READ => false
            );
			$this->Analysis->save($analysis_data, $analysis[ANALYSIS_ID]);
			// compose and send mail
			$mail_body = $this->load->view('site/feedback_mail', $data, TRUE);
            $this->email->default_sender_name = 'Runwalk Herentals';
			$this->email->send_mail($subject, $mail_body, $analysis[ PERSON_EMAIL ]);
		}
		echo $this->email->print_debugger();
		// send mail to admin with emailed clients.. (cronjob needs to run after two weeks)
	}
	
	function feedback_mail($feedback_token)
	{
		$this->load->model('Analysis');
		if (isset($feedback_token)) {
			$analysis = $this->Analysis->get_analysis_by_token_id($feedback_token);
			if (sizeof($analysis) > 0)
			{
				$creation_date = strftime('%d %B %Y', strtotime($analysis[ANALYSIS_CREATION_DATE]));
				$data[ANALYSIS_CREATION_DATE] = $creation_date;
				$data[ANALYSIS_FEEDBACK_TOKEN] = $feedback_token;
				$this->load->view('site/feedback_mail', $data);
			}
		}
	}
	
	function feedback_read($feedback_token)
	{
		$this->load->model('Analysis');
		if (isset($feedback_token))
		{
			$analysis = $this->Analysis->get_analysis_by_token_id($feedback_token);
			if (sizeof($analysis) > 0) 
			{
				$analysis_data = array(ANALYSIS_FEEDBACK_READ => true);
				$this->Analysis->save($analysis_data, $analysis[ANALYSIS_ID]);
            }
		}
        header('Content-Type: image/gif');
        echo base64_decode(GIF_PIXEL);
	}

	function subscribe($embed_page=0)
	{
		$data['embed_page'] = $embed_page;
		$data[ CAPTCHA ] = $this->_generate_captcha();

		// set validation rules for subscription
		$this->form_validation->set_rules(PERSON_NAME, "lang:common_last_name", "trim|required|min_length[5]|max_length[50]");
		$this->form_validation->set_rules(PERSON_EMAIL, "lang:common_email", "trim|required|valid_email");
		$this->form_validation->set_rules(PERSON_FIRST_NAME, "lang:common_first_name", "trim|required");
		$this->form_validation->set_rules(CAPTCHA, "lang:common_captcha", "required|callback_captcha_check");

        $person_data = (array) $this->Person->get_populated_person(TRUE);

        if ($this->form_validation->run() == FALSE)
		{
		    $data['person_info'] = $person_data;
			$data['error_messages'] = $this->form_validation->get_error_messages();
			$this->load->view('newsletters/subscribe', $data);
		}
		else
		{
			$customer_data = array(PERSON_IN_MAILING_LIST => 1, PERSON_CONSENT => 1);
			$person_data = (array) $this->Person->get_populated_person(FALSE);
			if ($this->Customer->save_customer($person_data, $customer_data))
			{
				// build client data array and prepare for insertion
				// make insertion, grab insert_id
				$data['themessage'] = "Je gegevens werden in onze database opgeslagen. ";
				$data['themessage'] .= "Vanaf nu zal je op de hoogte gehouden worden van de laatste promoties en nieuwtjes over de winkel.";
				// send mail to notify admin about user subscription
				$subject = "Nieuwe inschrijving via de website! ";
				$mail_body = "Er heeft zich iemand op de nieuwsbrief ingeschreven via de website.<br>".
				"De volgende gegevens werden ingevoerd:<br><br>".
				"Voornaam + Naam: " . $this->input->post(PERSON_FIRST_NAME) . " " . $this->input->post(PERSON_NAME) ."<br>";
				$this->email->send_mail($subject, $mail_body);
			}
			else
			{
				// redirect session variable not set, could be a naked post to this method
				$data['themessage'] = "Er is iets misgelopen bij het registreren van uw gegevens. Probeer het later nog eens opnieuws.";
			}
			$this->load->view('site/formsent', $data);
		}
	}
	
	function score_check($score)
	{
		if ($this->input->post(ANALYSIS_SCORE) == 0 && !$this->input->post(ANALYSIS_COMMENTS, TRUE))
		{
			$this->form_validation->set_message('score_check', $this->lang->line('feedback_comments_check'));
			$this->form_validation->set_rules(ANALYSIS_COMMENTS, "lang:common_comments", "required" );
			return false;
		}
		return true;
	}

	function gait_video_check($filename)
    {
        if (!$this->input->post('existing_customer') && !isset($_SESSION[GAIT_VIDEO_PATH]))
        {
            $this->form_validation->set_message('gait_video_check', $this->lang->line('gait_video_required'));
            return false;
        }
        return true;
    }

	function captcha_check($captcha)
	{
		if($captcha != $this->session->flashdata(CAPTCHA))
		{
			$this->form_validation->set_message('captcha_check', $this->lang->line('common_captcha_check'));
			return false;
		}
		return true;
	}

    public function is_required($field) {
        return ($this->form_validation->is_required($field)) ? array('class'=>'required'): array();
        //return array('class'=>'required');
    }

}
