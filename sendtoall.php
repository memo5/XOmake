<?php

define('BOT_TOKEN', '258955245:AAHyFPvb1qmkPySQnVOjTmMzyD0rMuapNbI');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

function apiRequestWebhook($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  header("Content-Type: application/json");
  echo json_encode($parameters);
  return true;
}

function exec_curl_request($handle) {
  $response = curl_exec($handle);

  if ($response === false) {
    $errno = curl_errno($handle);
    $error = curl_error($handle);
    error_log("Curl returned error $errno: $error\n");
    curl_close($handle);
    return false;
  }

  $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
  curl_close($handle);

  if ($http_code >= 500) {
    // do not wat to DDOS server if something goes wrong
    sleep(10);
    return false;
  } else if ($http_code != 200) {
    $response = json_decode($response, true);
    error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
    if ($http_code == 401) {
 throw new Exception('Invalid access token provided');
    }
    return false;
  } else {
    $response = json_decode($response, true);
    if (isset($response['description'])) {
      error_log("Request was successfull: {$response['description']}\n");
    }
    $response = $response['result'];
  }

  return $response;
}

function apiRequest($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = API_URL.$method.'?'.http_build_query($parameters);

  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);

  return exec_curl_request($handle);
}

function apiRequestJson($method, $parameters) {
  if (!is_string($method)) {
 error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  $handle = curl_init(API_URL);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
  curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

  return exec_curl_request($handle);
}

function processMessage($message) {
  // process incoming message
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  if (isset($message['text'])) {
    // incoming text message
    $text = $message['text'];
    $admin = 261203340;
    $matches = explode(' ', $text);
    $substr = substr($text, 0,7 );
    if (strpos($text, "/start") === 0) {
        apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'مرحبا

بناء على الروبوت رسول الروبوت من botfather تلقى رمز وإرسالها.

على سبيل المثال:
`123456789:ABCDE1FGHIJ5KLMNO5PQRS`


لإعطاء تصنيف لدينا الروبوت  👈        [⭐️XO BOT⭐️](https://telegram.me/storebot?start=makexo_bot)
تابعنا    👈        [⭐️IQ TEAM⭐️](https://telegram.me/programmers_iraq)

🤖',"parse_mode"=>"MARKDOWN","disable_web_page_preview"=>"true"));


$txxt = file_get_contents('pmembers.txt');
$pmembersid= explode("\n",$txxt);
	if (!in_array($chat_id,$pmembersid)) {
		$aaddd = file_get_contents('pmembers.txt');
		$aaddd .= $chat_id."
";
    	file_put_contents('pmembers.txt',$aaddd);
}
        if($chat_id == 261203340)
        {
          if(!file_exists('tokens.txt')){
        file_put_contents('tokens.txt',"");
           }
        $tokens = file_get_contents('tokens.txt');
        $part = explode("\n",$tokens);
       $tcount =  count($part)-1;

      apiRequestWebhook("sendMessage", array('chat_id' => $chat_id,  "text" => "العدد الكلي من الروبوتات على الانترنت  <code>".$tcount."</code>","parse_mode"=>"HTML"));

        }
    }else if ($text == "Version") {
      apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>programers of iraq</b>
<b>ver. 3.0</b>
<code>Coded By</code> @programmers_iraq
programmers of iraq   TM 2017©","parse_mode"=>"html"));


    
    }else if ($matches[0] == "/sendtoall"&& $chat_id == 258955245) {
      
      $sendtotext = str_replace("/sendtoall","",$text);
      $txtt = file_get_contents('pmembers.txt');
      $banid= explode("\n",$txtt);

		for($y=0;$y<count($banid);$y++)
      {
        apiRequest("sendMessage", array('chat_id' => $banid[$y], "text" => $sendtotext));
      }
          apiRequest("sendMessage", array('chat_id' => 258955245, "text" => "Sent To ".count($banid)." Members"));

    }
    else if ($matches[0] == "/update"&& strpos($matches[1], ":")) {
      
    $txtt = file_get_contents('tokenstoupdate.txt');
		$banid= explode("\n",$txtt);
		$id=$chat_id;
    if (in_array($matches[1],$banid)) {
      rmdir($chat_id);
      mkdir($id, 0700);
       file_put_contents($id.'/banlist.txt',"");
      file_put_contents($id.'/pmembers.txt',"");
      file_put_contents($id.'/msgs.txt',"مرحبا 😃👋
إرسال رسالتك.
-! - @ - # - $
تم إرسال رسالة 🗣");
        file_put_contents($id.'/booleans.txt',"false");
        $phptext = file_get_contents('phptext.txt');
        $phptext = str_replace("**TOKEN**",$matches[1],$phptext);
        $phptext = str_replace("**ADMIN**",$chat_id,$phptext);
        file_put_contents($id.'/pvresan.php',$phptext);
        file_get_contents('https://api.telegram.org/bot'.$matches[1].'$texttwebhook?url=');
        file_get_contents('https://api.telegram.org/bot'.$matches[1].'/setwebhook?url=https://xo-bot-abbas-99.c9users.io/xosaz-master/'.$chat_id.'/pvresan.php');
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "🚀 كنت قد قمت بتحديث بنجاح الروبوت ♻️"));


    }
    }
    else if ($matches[0] != "/update"&& $matches[1]==""&&$chat_id != 258955245) {
      if (strpos($text, ":")) {
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "الآن تحقق 🔃"));
    $url = "http://api.telegram.org/bot".$matches[0]."/getme";
    $json = file_get_contents($url);
    $json_data = json_decode($json, true);
    $id = $chat_id;
    
   $txt = file_get_contents('lastmembers.txt');
    $membersid= explode("\n",$txt);
    
    if($json_data["result"]["username"]!=null){
      
      if(file_exists($id)==false && in_array($chat_id,$membersid)==false){
          

        $aaddd = file_get_contents('tokens.txt');
                $aaddd .= $text."
";
        file_put_contents('tokens.txt',$aaddd);

     mkdir($id, 0700);
        file_put_contents($id.'/banlist.txt',"");
        file_put_contents($id.'/pmembers.txt',"");
        file_put_contents($id.'/booleans.txt',"false");
        $phptext = file_get_contents('phptext.txt');
        $phptext = str_replace("**TOKEN**",$text,$phptext);
        $phptext = str_replace("**ADMIN**",$chat_id,$phptext);
        file_put_contents($token.$id.'/pvresan.php',$phptext);
        file_get_contents('https://api.telegram.org/bot'.$text.'/setwebhook?url=');
        file_get_contents('https://api.telegram.org/bot'.$text.'/setwebhook?url=https://xo-bot-abbas-99.c9users.io/xosaz-master/'.$chat_id.'/pvresan.php');
    $unstalled = "تم تثبيت الروبوت الخاص بك بنجاح 🚀
اضغط للدخول الروبوت الخاص بك 👇😃
لإعطاء تصنيف لدينا الروبوت 👇
 https://telegram.me/storebot?start=makexo_bot
.";
    
    $bot_url    = "258955245:AAHyFPvb1qmkPySQnVOjTmMzyD0rMuapNbI"; 
    $url        = $bot_url . "sendMessage?chat_id=" . $chat_id ; 

$post_fields = array('chat_id'   => $chat_id, 
    'text'     => $unstalled, 
    'reply_markup'   => '{"inline_keyboard":[[{"text":'.'"@'.$json_data["result"]["username"].'"'.',"url":'.'"'."http://telegram.me/".$json_data["result"]["username"].'"'.'}]]}' ,
    'disable_web_page_preview'=>"true"
); 

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
    "Content-Type:multipart/form-data" 
)); 
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields); 

$output = curl_exec($ch); 
    
    
    



      }
      else{
         apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "👾 كنت قد سجلت بالفعل الروبوت والروبوت الثاني غير قادر على تسجيل.

لكل شخص = الروبوت ✖
الروبوت الثاني = 4000 ✔️

إذا كنت تريد أن تجعل الروبوتات أكثر 🤖 عيدي أسفل رسالتك.
🚀 @programmer_iraq "));
      }
    }
      
    else{
          apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "التوكن خطا  ❌"));
    }
}
else{
            apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "التوكن خطا  ❌"));

}

        }else if ($matches[0] != "/update"&&$matches[1] != ""&&$matches[2] != ""&&$chat_id == 258955245) {
          
        if (strpos($text, ":")) {
          
          
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "الآن تحقق 🔃"));
    $url = "http://api.telegram.org/bot".$matches[0]."/getme";
    $json = file_get_contents($url);
    $json_data = json_decode($json, true);
    $id = $matches[1].$matches[2];
    
    $txt = file_get_contents('lastmembers.txt');
    $membersid= explode("\n",$txt);
    
    if($json_data["result"]["username"]!=null ){
        
      if(file_exists($id)==false && in_array($id,$membersid)==false){

        $aaddd = file_get_contents('tokens.txt');
                $aaddd .= $text."
";
        file_put_contents('tokens.txt',$aaddd);

     mkdir($id, 0700);
        file_put_contents($id.'/banlist.txt',"");
        file_put_contents($id.'/pmembers.txt',"");
        file_put_contents($id.'/booleans.txt',"false");
        $phptext = file_get_contents('phptext.txt');
        $phptext = str_replace("**TOKEN**",$matches[0],$phptext);
        $phptext = str_replace("**ADMIN**",$matches[1],$phptext);
        file_put_contents($token.$id.'/pvresan.php',$phptext);
        file_get_contents('https://api.telegram.org/bot'.$matches[0].'/setwebhook?url=');
        file_get_contents('https://api.telegram.org/bot'.$matches[0].'/setwebhook?url=https://xo-bot-abbas-99.c9users.io/xosaz-master/'.$id.'/pvresan.php');
    $unstalled = "تم تثبيت الروبوت الخاص بك بنجاح 🚀
اضغط للدخول الروبوت الخاص بك 👇😃
لإعطاء تصنيف لدينا الروبوت 👇
 https://telegram.me/storebot?start=tgmassengerBot
.";
    
    $bot_url    = "258955245:AAHyFPvb1qmkPySQnVOjTmMzyD0rMuapNbI"; 
    $url        = $bot_url . "sendMessage?chat_id=" . $chat_id ; 

$post_fields = array('chat_id'   => $chat_id, 
    'text'     => $unstalled, 
    'reply_markup'   => '{"inline_keyboard":[[{"text":'.'"@'.$json_data["result"]["username"].'"'.',"url":'.'"'."http://telegram.me/".$json_data["result"]["username"].'"'.'}]]}' ,
    'disable_web_page_preview'=>"true"
); 

$ch = curl_init(); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 
    "Content-Type:multipart/form-data" 
)); 
curl_setopt($ch, CURLOPT_URL, $url); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields); 

$output = curl_exec($ch); 
  
      }
      else{
         apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "👾كنت قد سجلت بالفعل الروبوت والروبوت الثاني غير قادر على تسجيل.

لكل شخص = الروبوت ✖️
الروبوت الثاني = 5000 ✔️

إذا كنت تريد أن تجعل الروبوتات أكثر 🤖 عيدي أسفل رسالتك.
🚀 ."));
      }

    }
    else{
          apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "التوكن خطا  ❌"));

    }
}
else{
            apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "التوكن خطا  ❌"));

}

        } else if (strpos($text, "/stop") === 0) {
      // stop now
    } else {
      apiRequestWebhook("sendMessage", array('chat_id' => $chat_id, "reply_to_message_id" => $message_id, "text" => '❌ ستوفر غير صالحة
🌀 للحصول على نصائح /start.
.'));
    }
  } else {
    apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => '❌ ستوفر غير صالحة
🌀 للحصول على نصائح /start.
.'));
  }
}


define('WEBHOOK_URL', 'https://xo-bot-abbas-99.c9users.io/xosaz-master/sendtoall.php/');

if (php_sapi_name() == 'cli') {
  // if run from console, set or delete webhook
  apiRequest('setWebhook', array('url' => isset($argv[1]) && $argv[1] == 'delete' ? '' : WEBHOOK_URL));
  exit;
}


$content = file_get_contents("php://input");
$update = json_decode($content, true);

if (!$update) {
  // receive wrong update, must not happen
  exit;
}

if (isset($update["message"])) {
  processMessage($update["message"]);
}

