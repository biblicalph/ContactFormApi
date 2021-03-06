<?php 
class MandrillEmailSender {
	protected $subject;
	protected $message;
	protected $fromEmail;
	protected $fromName;
	protected $to;
	protected $apiKey;

	public function __construct(array $options) {
		$this->apiKey = getenv('MANDRILL_API_KEY');
		
		$this->subject = isset($options['subject']) ? $options['subject'] : getenv('MAIL_SUBJECT');
		$this->message = isset($options['message']) ? $options['message'] : '';
		$this->fromEmail = isset($options['senderEmail']) ? $options['senderEmail'] : '';
		$this->fromName = isset($options['senderName']) ? $options['senderName'] : '';
		
		// Set the list of recipients
		if (isset($options['recipients'])) {
			$recipients = $options['recipients'];

			$this->to = [];
			$i = 0;
			foreach ($recipients as $rec) {
				$row = [];
				if (is_array($rec)) {
					if (isset($rec['email'])) 
						$row['email'] = $rec['email'];
					if (isset($rec['name']))
						$row['name'] = $rec['name'];
				} else {
					$row['email'] = $rec;
				}

				if (count($row) > 0) {
					$row['type'] = $i == 0 ? 'to' : 'cc';
					++$i;
					$this->to[] = $row;
				}
			}
		}
	}

	public function sendEmail() {
		try {
			$mandrill = new Mandrill($this->apiKey);
			$message = [
				'subject' => $this->subject,
				'text' => $this->message,
				'from_email' => $this->fromEmail,
				'from_name' => $this->fromName,
				'to' => $this->to
			];

			$result = $mandrill->messages->send($message);
		} catch (Mandrill_Error $e) {
			throw $e;
		}
		return $result;
	}
}
?>
