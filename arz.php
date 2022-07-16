<?php

error_reporting(E_ALL);
ini_set('display_errors','0');
ini_set('memory_limit' , '-1');
ini_set('max_execution_time','0');
ini_set('display_startup_errors','1');
date_default_timezone_set('Asia/Tehran');

if(isset($_GET["command"]) && $_GET["command"]=="restart"){
	file_put_contents("restart.txt", "restart");
	exit();
}

$sendchannel = '';

/*if (!file_exists('data.json')) {
    file_put_contents('data.json', '{"channels":{},"sendchannel":"'.$sendchannel.'"}');
}*/

if (file_exists('../vendor/autoload.php')) {
    require '../vendor/autoload.php';
} else {
    if (!file_exists('/madeline.php')) {
        copy('https://phar.madelineproto.xyz/madeline.php','madeline.php');
    }
    require 'madeline.php';
}

/*if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}

include 'madeline.php';
*/

use \danog\MadelineProto\API;
use \danog\Loop\Generic\GenericLoop;
use \danog\MadelineProto\EventHandler;

class XHandler extends EventHandler
{
    const Admins = [113269090,5199508308,-1001642497103,107966283,397141121];
    const Report = '-1001669042880';
    const arzgp = '-1001632034437';
    
    public function getReportPeers()
    {
        return [self::Report];
    }
    
    public function genLoop1min()
    {
    	//Keep Online
        yield $this->account->updateStatus([
            'offline' => false
        ]);
        
        //Auto Reset
        $memory=intval(memory_get_usage()/1024/1024);
       if($memory>40){
        	yield $this->messages->sendMessage([
	            'peer'                => self::Report,
	            'message'       => 'Bot Auto Restarted At : ' . date('H:i:s'),
                'parse_mode' => 'MarkDown',
	        ]);
            yield $this->restart();
		}
		
		//Handly Reset
        $reset=file_exists("restart.txt")?file_get_contents("restart.txt"):false;
        if($reset=="restart"){
   	     yield $this->messages->sendMessage([
         	   'peer'          => self::Report,
  	          'message' => 'Bot Handly Restarted At : ' . date('H:i:s'),
      	  ]);
			unlink("restart.txt");
  	      yield $this->restart();
        }
        return 60000;
    }
		
	public function genLoop5min()
    {
    	yield $this->messages->sendMessage([
         	   'peer'          => self::arzgp,
  	          'message' => 'نرخ ارز',
		]);
		return 300000;
    }
    
    public function onStart()
    {
        $genLoop1min = new GenericLoop([$this, 'genLoop1min'], 'update Status');
        $genLoop1min ->start();
        
        $genLoop5min = new GenericLoop([$this, 'genLoop5min'], 'update Status');
        $genLoop5min->start();
    }
    
    public function numberOfDecimals($value)
	{
	    if ((int)$value == $value)
	    {
	        return 0;
	    }
	    else if (! is_numeric($value))
	    {
	        return false;
	    }
	
	    return strlen($value) - strrpos($value, '.') - 1;
	}
    
    public function onUpdateNewChannelMessage($update)
    {
        yield $this->onUpdateNewMessage($update);
    }
    
    public function onUpdateNewMessage($update)
    {
        try {
            $msgOrig        = $update['message']['message']?? null;
            $messageId   = $update['message']['id']?? 0;
            $fromId           = $update['message']['from_id']['user_id']?? 0;
            $replyToId      = $update['message']['reply_to']['reply_to_msg_id']?? 0;
            $peer               = yield $this->getId($update);
            $info				= yield $this->getInfo($peer);
            $type				= $info['type'];
            $me				 = yield $this->getSelf();
            $me_id			= $me['id'];
			
			if($peer == self::arzgp && $fromId == "690074377"){
				$aa=explode("\n",$msgOrig);
				foreach ($aa as $ad => $ab){
					if(($ad<15 && $ad>4) || ($ad<19 && $ad>15) || $ad==21){
						$ac[]=str_replace(',','',explode(' تومان', explode(" : ",$ab)[1])[0]);
					}elseif($ad==19){
						$ac[]=str_replace(',','',explode(' دلار', explode(" : ",$ab)[1])[0]);
					}elseif($ad>22 && $ad%2==1 &&$ad<42){
						$ac[]=str_replace(',','',explode('$', explode(" : ",$ab)[1])[0]);
					}
				}
				$names = array(
					array("USD","دلار آمریکا"),
					array("EUR","یورو اروپا"),
					array("GBP","پوند انگلیس"),
					array("CHF","فراک سوئیس"),
					array("CAD","دلار کانادا"),
					array("TRY","لیر ترکیه"),
					array("AFN","افغانی"),
					array("AED","درهم امارات"),
					array("IQD","دینار عراق"),
					array("CNY","یوان چین"),
					array("Imami Coin","سکه امامی"),
					array("Mesghal","مثقال طلا"),
					array("Gram Gold 18","گرم طلای ۱۸ عیار"),
					array("Ons Gold","انس طلا"),
					array("Tether","تتر"),
					array("Bitcoin","بیت کوین"),
					array("Ethereum","اتریوم"),
					array("Binance Coin","بایننس کوین"),
					array("Solana","سولانا"),
					array("Ripple","ریپل"),
					array("Dogecoin","دوج کوین"),
					array("SHIBA","شیبا"),
					array("TRON","ترون"),
					array("Bitcoin Cash","بیت کوین کش"),
				);
				$units = array(
					"تومان",
					"تومان",
					"تومان",
					"تومان",
					"تومان",
					"تومان",
					"تومان",
					"تومان",
					"تومان",
					"تومان",
					"تومان",
					"تومان",
					"تومان",
					"دلار",
					"تومان",
					"دلار",
					"دلار",
					"دلار",
					"دلار",
					"دلار",
					"دلار",
					"دلار",
					"دلار",
					"دلار",
				);
				foreach($ac as $key=>$value){
					$v[]=array("name"=>$names[$key], "amount"=>$value, "unit"=>$units[$key]);
				}
				file_put_contents("arz.json", json_encode($v,JSON_UNESCAPED_UNICODE));
			}
            if(in_array($peer, self::Admins)) {

                if(in_array($type, ["supergroup", "channel"])){
                	yield $this->channels->readHistory([
						'channel'          => $peer,
						'max_id' => $messageId,
					]);
                }else{
		        	yield $this->messages->readHistory([
						'peer'          => $peer,
						'max_id' => $messageId,
					]);
				}

				if(preg_match('/^([\/\#\!])?(stats|آمار|امار)$/i', $msgOrig)) {
					$memory=intval(memory_get_usage()/1024/1024);
                    yield $this->messages->sendMessage([
                        'peer'                        => $peer,
                        'message'               => "
📌آمار

".($memory<=50? "✅":"⚠️")."میزان مصرف رم: ".$memory."MB
",
                        'reply_to_msg_id' => $messageId,
                        'parse_mode'         => 'MarkDown',
                    ]);
                }elseif(preg_match('/^([\/\#\!])?(راهنما|help|کمک)$/i', $msgOrig)) {
                    yield $this->messages->sendMessage([
                        'peer'                        => $peer,
                        'message'               => '
📌آمار

1️⃣ `/stats`



📌نرخ ارز

1️⃣ `/arz`



📌خروجی جیسون

1️⃣ `/export`
                        ',
                        'reply_to_msg_id' => $messageId,
                        'parse_mode'         => 'MarkDown',
                    ]);
                }elseif(preg_match('/^([\/\#\!])?(reset|ریست|restart|ریستارت)$/i', $msgOrig)){
                    yield $this->messages->sendMessage([
                        'peer'                        => $peer,
                        'message'               => "ربات با موفقیت راه اندازی شد",
                        'reply_to_msg_id' => $messageId,
                    ]);
					yield $this->messages->sendMessage([
			            'peer'    => self::Report,
			            'message' => 'Bot Restarted At : ' . date('H:i:s'),
			        ]);
					yield $this->restart();
				}elseif(preg_match('/^([\/\#\!])?(export|خروجی|json|جیسون)$/i', $msgOrig)){
                    yield $this->messages->sendMessage([
                        'peer'                        => $peer,
                        'message'               => "Exported Success!",
                        'reply_to_msg_id' => $messageId,
                    ]);
                    
                    $file = "arz.json";
                    $message = "Exported Arz as Json";
					$sentMessage = yield $this->messages->sendMedia([
					    'peer' => $peer,
						'reply_to_msg_id' => $messageId,
					    'media' => [
					        '_' => 'inputMediaUploadedDocument',
					        'file' => $file,
					        'attributes' => [
					            [
									'_' => 'documentAttributeFilename',
									'file_name' => basename($file)
								],
					        ],
					    ],
					    'message' => $message,
					]);
				}elseif(preg_match('/^([\/\#\!])?(arz|ارز|نرخ|نرخ ارز)$/i', $msgOrig)){
					$arz="";
					$json=json_decode(file_get_contents("arz.json"),true);
					$countries = array(
						"🇺🇸",
						"🇪🇺",
						"🇬🇧",
						"🇨🇭",
						"🇨🇦",
						"🇹🇷",
						"🇦🇫",
						"🇦🇪",
						"🇮🇶",
						"🇨🇳",
						"🔘",
						"🔘",
						"🔘",
						"🔘",
						"©️",
						"©️",
						"©️",
						"©️",
						"©️",
						"©️",
						"©️",
						"©️",
						"©️",
						"©️",
					);
					foreach($json as $key=>$val){
						$numfor = yield $this->numberOfDecimals($val["amount"]);
						$arz .= $countries[$key]." ".$val["name"][1]." : ".number_format($val["amount"],$numfor)." ".$val["unit"]."\n";
					}
                    yield $this->messages->sendMessage([
                        'peer'                        => $peer,
                        'message'               => "
📌نرخ ارز

".$arz."
",
                        'reply_to_msg_id' => $messageId,
                    ]);
				}elseif(preg_match('/^([\/\#\!])?(report|ریپورت)$/i', $msgOrig)){
					yield $this->setReportPeers([$peer]);
					yield $this->report("Reported Success!");
					yield $this->restart();
				}
            }
        } catch (\Throwable $e){
            yield $this->report("Surfaced: $e");
        }
    }
}
$settings = [
    'serialization' => [
        'cleanup_before_serialization' => true,
    ],
    'logger' => [
        'max_size' => 1*1024*1024,
    ],
    'peer' => [
        'full_fetch' => false,
        'cache_all_peers_on_startup' => false,
    ],
    'db'            => [
        'type'  => 'mysql',
        'mysql' => [
            'host'     => 'localhost',
            'port'     => '3306',
            'user'     => 'tabchisa_admin',
            'password' => 'Mfamsh.83',
            'database' => 'tabchisa_arz',
        ],
    ],
];

$bot = new \danog\MadelineProto\API('X.session', $settings);
$bot->startAndLoop(XHandler::class);
?>