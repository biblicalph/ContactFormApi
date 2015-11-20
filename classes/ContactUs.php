<?php
header("Access-Control-Allow-Origin: *");
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../vendor/autoload.php';
require_once './Mailers/MandrillEmailSender.php';
/**
 * Description of ContactUs
 *
 * @author mpawa
 */
class ContactUs {

    protected $subject;
    protected $recipients;
    protected $fromName;
    protected $fromEmail;
    protected $message;
    
    public function __construct() {
        // Load environment variables
        $this->loadEnvConfig();
        $this->recipients = $this->getMailRecipient();

        $this->fromEmail = isset($_POST["fromEmail"]) ? $_POST["fromEmail"] : "";
        $this->fromName = isset($_POST["fromName"]) ? $_POST["fromName"] : "";
        $this->message = isset($_POST["message"]) ? $_POST["message"] : "";

        // Set the subject
        $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
        $this->subject = !empty($subject) ? $subject : getenv('MAIL_SUBJECT');
    }
    
    public function loadEnvConfig() {
    	$dotenv = new Dotenv\Dotenv($this->getEnvDirPath());
		$dotenv->load();
		$dotenv->required(['MANDRILL_API_KEY', 'RECIPIENT_EMAIL', 'RECIPIENT_PASSWORD', 'MAIL_SUBJECT', 'RECIPIENT_NAME'])->notEmpty();
    }
    
    public function getEnvDirPath() {
    	$dir = __DIR__;
    	
    	if (!preg_match("#\/$#", $dir)) {
    		$dir .= "/";
    	}
    	return $dir . '../';
    }
    
    public function getMailRecipient() {
    	return ['email' => getenv('RECIPIENT_EMAIL'), 'name' => getenv('RECIPIENT_NAME')];
    }

    public function isValidForm() {
        $isValidEmail = !empty($this->fromEmail) && filter_var($this->fromEmail, FILTER_VALIDATE_EMAIL); 

        return !empty($this->fromName) && !empty($this->message) && $isValidEmail;
    }

    public function getErrors() {
        $isValidEmail = !empty($this->fromEmail) && filter_var($this->fromEmail, FILTER_VALIDATE_EMAIL); 

        $errors = "";
        if (empty($this->fromName))
            $errors .= "The name field is required";
        if (empty($this->message)) {
            if (!empty($errors))
                $errors .= "<br/>";
            $errors .= "The message to send is required";
        }
        if (!$isValidEmail) {
            if (!empty($errors))
                $errors .= "<br/>";
            $errors .= "A valid email address is required";
        }

        return $errors;
    }

    public function sendEmail() {
        $output = array('wasSent' => false, 'error' => '', 'status' => '');

        if ($this->isValidForm()) {
            $options = array(
                "recipients" => $this->recipients,
                "subject" => $this->subject,
                "message" => $this->message,
                "senderEmail" => $this->fromEmail,
                "senderName" => $this->fromName
            );

            try {
                $mandrillMailer = new MandrillEmailSender($options);
                $output['result'] = $mandrillMailer->sendEmail();
                $output['wasSent'] = true;
            } catch (Exception $e) {
                $output['error'] = 'Exception occurred. ' . $e->getMessage();
            }

            if ($output["wasSent"]) {
                $output["status"] = "Thank you for contacting us. We will get back to you shortly";
            } else {
                $output["status"] = "Sorry! Your message was not sent. Please try again later. Thank you";
            }
        } else {
            $output['status'] = $this->getErrors();
        }

        echo json_encode($output);
    }

}

$obj = new ContactUs();
$obj->sendEmail();
