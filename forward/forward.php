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

if (!file_exists('data.json')) {
    file_put_contents('data.json', '{"channels":{},"sendchannel":"'.$sendchannel.'"}');
}

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
    const Admins = [113269090,5199508308,-1001642497103];
    const Report = '-1001669042880';
    
    public function getReportPeers()
    {
        return [self::Report];
    }
    
    public function genLoop1min()
    {
        //Auto Reset
		$memory=intval(memory_get_usage()/1024/1024);
		if($memory>20){
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
    
    public function onStart()
    {
        $genLoop1min = new GenericLoop([$this, 'genLoop1min'], 'update Status');
        $genLoop1min->start();
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
            @$data           = json_decode(file_get_contents("data.json"), true);
            $info				= yield $this->getInfo($peer);
            $type				= $info['type'];
            $me				 = yield $this->getSelf();
            $me_id			= $me['id'];
			
            if(in_array($peer, self::Admins)) {
				
				if(preg_match('/^([\/\#\!])?(add|افزودن|اضافه)$/i', $msgOrig)) {
					$info = yield $this->messages->getMessages([
						'id' => [$replyToId],
					]);
					$data['channels']['-100'.$info['messages'][0]['fwd_from']['from_id']['channel_id']]='-100'.$info['messages'][0]['fwd_from']['from_id']['channel_id'];
                    yield $this->messages->sendMessage([
                        'peer'                        => $peer,
                        'message'               => "کانال ".$info['chats'][0]['title']." با موفقیت اضافه شد!",
                        'reply_to_msg_id' => $messageId,
                    ]);
					file_put_contents("data.json", json_encode($data));
					yield $this->messages->sendMessage([
			            'peer'                => self::Report,
			            'message'       => 'A Channel Is Added At : ' . date('H:i:s'). ' , ID : '.'`-100'.$info['messages'][0]['fwd_from']['from_id']['channel_id'].'`',
                        'parse_mode' => 'MarkDown',
			        ]);
                }elseif(preg_match('/^([\/\#\!])?(del|delete|حذف)$/i', $msgOrig)) {
					$info = yield $this->messages->getMessages([
					    'id' => [$replyToId],
					]);
					unset($data['channels']['-100'.$info['messages'][0]['fwd_from']['from_id']['channel_id']]);
                    yield $this->messages->sendMessage([
                        'peer'                        => $peer,
                        'message'               => "کانال ".$info['chats'][0]['title']." با موفقیت حذف شد!",
                        'reply_to_msg_id' => $messageId,
                    ]);
					file_put_contents("data.json", json_encode($data));
					yield $this->messages->sendMessage([
			            'peer'                => self::Report,
			            'message'       => 'A Channel Is Deleted At : ' . date('H:i:s'). ' , ID : '.'`-100'.$info['messages'][0]['fwd_from']['from_id']['channel_id'].'`',
                        'parse_mode' => 'MarkDown',
			        ]);
                }elseif(preg_match('/^([\/\#\!])?(stats|آمار|امار)$/i', $msgOrig)) {
                	$i=0;
	                $list='';
                	foreach($data['channels'] as $channel){
						$chinf = yield $this->channels->getChannels([
						    'id' => [$channel],
						]);
						$i++;
						$list.="\t".$i.'⃣ ['.$chinf['chats'][0]['title']."](https://t.me/".$chinf['chats'][0]['username'].")\n";
					}
					$magsad = yield $this->channels->getChannels([
					    'id' => [$data['sendchannel']],
					]);
					$memory=intval(memory_get_usage()/1024/1024);
                    yield $this->messages->sendMessage([
                        'peer'                        => $peer,
                        'message'               => "
📌آمار

".($memory<=50? "✅":"⚠️")."میزان مصرف رم: ".$memory."MB

📜لیست کانال ها (تعداد: ".$i."⃣)
".$list."
📎کانال مقصد: [".$magsad['chats'][0]['title']."](https://t.me/".$magsad['chats'][0]['username'].")

",
                        'reply_to_msg_id' => $messageId,
                        'parse_mode'         => 'MarkDown',
                    ]);
                }elseif(preg_match('/^([\/\#\!])?forto \| (@[\w]+)$/i', $msgOrig, $text)) {
					$data['sendchannel']=$text[2];
                    yield $this->messages->sendMessage([
                        'peer'                        => $peer,
                        'message'               => "کانال مقصد به $text[2] با موفقیت تغییر یافت!",
                        'reply_to_msg_id' => $messageId,
                    ]);
					file_put_contents("data.json", json_encode($data));
					yield $this->messages->sendMessage([
			            'peer'                => self::Report,
			            'message'       => 'Out Channel Is Changed At : ' . date('H:i:s'). ' , UserName : '.$text[2],
                        'parse_mode' => 'MarkDown',
			        ]);
                }elseif(preg_match('/^([\/\#\!])?(راهنما|help|کمک)$/i', $msgOrig)) {
                    yield $this->messages->sendMessage([
                        'peer'                        => $peer,
                        'message'               => '
📌آمار

1️⃣ `/stats`



📌افزودن کانال

1️⃣ `/add` [reply on forwarded message]



📌حذف کانال

1️⃣ `/del` [reply on forwarded message]



📌تغییر کانال مقصد

1️⃣ ادمین کردن بات در کانال
2️⃣ `/forto |` [@username]
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
				}elseif(preg_match('/^([\/\#\!])?(report|ریپورت)$/i', $msgOrig)){
					yield $this->setReportPeers([$peer]);
					yield $this->report("Reported Success!");
					yield $this->restart();
				}
            }
			if(isset($data['channels'][$peer])){
                 yield $this->messages->forwardMessages([
                     'from_peer' => $peer,
                     'to_peer'      => $data['sendchannel'],
                     'id'                 => [$messageId],
                 ]);
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
            'database' => 'tabchisa_forward',
        ]
    ]
];

$bot = new \danog\MadelineProto\API('X.session', $settings);
$bot->startAndLoop(XHandler::class);
?>