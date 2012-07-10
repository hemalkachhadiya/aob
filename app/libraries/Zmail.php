<? if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * Creating and sending emails
 */
class Zmail
{
	protected $emails = array();
	protected $subject = "";
	protected $message = "";
	protected $headers = array();
	protected $attachments = array();
    private   $email;
    private   $CI;
	protected $_body;
	
	protected $tplPath = "app/views/mailer_templates/";
	
	static $from = "Pommelo <noreply@pommelo.com>";
	public function __construct(){
        $this->CI = & get_instance();

    }
    /**
     * setter methos
     * @param  $emails mixed - array of emails or one email
     * @return void
     */
	public function addEmail($emails){
        if (is_array($emails)){
            $this->emails = $emails;
        }else{
            $this->emails[] = $emails;
        }
	}
    /**
     * setting email subject
     * @param $subject
     */
	public function setSubject($subject){
		$this->subject = $subject;
	}
	/**
     * setting template path
     * @param $path
     */
	public function setTplPath($path){
		$this->tplPath = $path;
	}
    /**
     * rendering template with config array
     * @param $template
     * @param array $params
     */
	public function setMessageFromTemplate($template, $params=array()){
		foreach ($params as $k => $v):
			$params['{' . $k . '}'] = $v;
			unset($params[$k]);	
		endforeach;
		
		$this->message = file_get_contents($this->tplPath . $template . ".html");
		$this->message = strtr($this->message, $params);
	}
	/**
     * attaching files to email
     * @param $filepath
     * @param $mime
     * @param $filename
     * @return bool
     */
	public function attach($filepath, $mime, $filename)
	{
		$content = @file_get_contents($filepath);
		$attach= array('Content-Type: ' . $mime . '; name="' . $filename . '"', 'Content-Transfer-Encoding: base64', 
						'Content-Disposition: attachment; filename="' . $filename . '"', '', '', chunk_split(base64_encode($content)), '', '');
		$this->attachments[$filename] = implode("\r\n", $attach);
		return true;
	}
    /**
     * sending email
     * @param string $method
     */
	public function send($method="mail"){
	    error_reporting(E_ALL);
		if (empty($this->attachments))
		{
			$this->headers = array("MIME-Version: 1.0", "Content-type: text/html; charset=utf-8", "From: " . self::$from);
			$this->_body = $this->message;
		}
		else
		{
			$boundary = "==Multipart_Boundary_x" . md5(time()) . "x";
			$this->headers = array('MIME-Version: 1.0', "From: " . self::$from, 'Content-Type: multipart/mixed;', ' boundary="' . $boundary . '"');
			
			$this->_body = "--{$boundary}\r\nContent-type: text/html; charset=utf-8\r\nContent-Transfer-Encoding: base64\r\n\r\n" . 
							chunk_split(base64_encode($this->message))."\r\n\r\n";
	
			foreach ($this->attachments as $attachment)
				$this->_body .= "--{$boundary}\r\n" . $attachment;
				
			$this->_body .= "--{$boundary}--";
		}
		foreach ($this->emails as $email):
			
			if ($method == "mail"){
                $status = mail($email, $this->subject, $this->_body, implode("\r\n", $this->headers));
                //var_dump ($error);
            }

				
			if ($method == "smtp")
				smtpmail($email, $this->subject, $this->_body);
		endforeach;
	}
}


/* End of file Zmail.php */