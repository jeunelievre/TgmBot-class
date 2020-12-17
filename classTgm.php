<?

class TgmBot
{
	private $token;
	private $webHook;
	private $data;
	
	public function __construct($token)
	 {
		$this->token = $token;
		$this->phpInput = file_get_contents('php://input');
		if ($this->phpInput != "")
		{
			$this->webHook = json_decode($this->phpInput, true);
			
			$this->fromUsername = $this->webHook['message']['from']['username'];
			$this->fromId = $this->webHook['message']['from']['id'];
			$this->fromFirstName = $this->webHook['message']['from']['first_name'];
			$this->fromLastName = $this->webHook['message']['from']['last_name'];
			$this->fromLanguageCode = $this->webHook['message']['from']['language_code'];
			
			$this->messageText = $this->webHook['message']['text'];
			$this->messageDate = $this->webHook['message']['date'];
			$this->messageId = $this->webHook['message']['message_id'];
			
			$this->updateId = $this->webHook['update_id'];
			
			$this->chatId = $this->webHook['message']['chat']['id'];
			$this->chatTitle = $this->webHook['message']['chat']['title'];
			$this->chatType = $this->webHook['message']['chat']['type'];

			
			
		} else {
			echo "empty webHook";
		}
	 }
	
	
	protected function Request($action,$data=NULL)
	{
		$token = $this->token;
		$tgmRequest = curl_init('https://api.telegram.org/bot' . $token . '/'.$action);  
		curl_setopt($tgmRequest, CURLOPT_POST, 1);  
		curl_setopt($tgmRequest, CURLOPT_POSTFIELDS, $data);
		curl_setopt($tgmRequest, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($tgmRequest, CURLOPT_HEADER, false);
		$output = curl_exec($tgmRequest);
		curl_close($tgmRequest);
		return $output;
	}
	
	public function getMe()
	{
		return $this->Request("getMe");
	}
	
	public function getChat($chat_id)
	{
		$data = array(
			'chat_id' => $chat_id
		);
		return $this->Request("getChat",$data);
	}
	
	public function parseInput()
	{
		$data = file_get_contents('php://input');
		$data = json_decode($data, true);
		ob_start();
		print_r($data);
		$out = ob_get_clean(); 
		file_put_contents(__DIR__ . '/messageteletest.txt', $out);
		return $data;
	}
	
	public function getFrom()
	{
		return $this->fromUsername;
	}
	
	// https://core.telegram.org/bots/api#sendmessage
	public function sendMessage($chat_id, $text="NULL", $disable_notification=NULL, $reply_markup=NULL,$reply_to_message_id=NULL,$disable_web_page_preview=NULL,$parse_mode=NULL)
	{
		$data = array(
			'chat_id' => $chat_id, 
			'text' => $text,
			'parse_mode' => $parse_mode,
			'disable_web_page_preview' => $disable_web_page_preview,
			'disable_notification' => $disable_notification,
			'reply_to_message_id' => $reply_to_message_id,
			'reply_markup' => $reply_markup
		);
		if (isset($chat_id))
		{
			return $this->Request("sendMessage", $data);
		} else {
			return "chat_id is empty";
		}
	}

	// https://core.telegram.org/bots/api#forwardmessage
	public function forwardMessage($chat_id, $from_chat_id, $message_id, $disable_notification)
	{
		$data = array(
			'chat_id' => $chat_id, 
			'from_chat_id' => $from_chat_id,
			'message_id' => $message_id,
			'disable_notification' => $disable_notification,
		);
		return $this->Request("forwardMessage", $data);
	}

	// https://core.telegram.org/bots/api#sendlocation
	public function sendLocation($chat_id, $latitude, $longitude)
	{
		$data = array(
			'chat_id' => $chat_id, 
			'latitude' => $latitude,
			'longitude' => $longitude
		);
		return $this->Request("sendLocation", $data);
	}
	
	// https://core.telegram.org/bots/api#sendchataction
	public function sendChatAction($chat_id, $action)
	{
		$data = array(
			'chat_id' => $chat_id,
			'action' => $action
		);
		return $this->Request("sendChatAction", $data);
	}
	
	// https://core.telegram.org/bots/api#answercallbackquery
	public function answerCallbackQuery($callback_query_id,$text=NULL,$show_alert=NULL,$url=NULL,$cache_time=NULL)
	{
		$data = array(
			'callback_query_id' => $callback_query_id
		);
		return $this->Request("answerCallbackQuery", $data);
	}
	
	// https://core.telegram.org/bots/api#editmessagetext
	public function editMessageText($chat_id, $message_id, $text, $reply_markup)
	{
		$data = array(
			'chat_id' => $chat_id,
			'message_id' => $message_id,
			'text' => $text,
			'reply_markup' => $reply_markup
		);
		return $this->Request("editMessageText", $data);
	}
	
	
	
}

?>