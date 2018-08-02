<?php
define(VK_TOKEN, 'token');
define(VK_VERSION, '5.69');
define(VK_API_METHOD, 'https://api.vk.com/method/');

function downloadAttachments($message_id) {
	$res = json_decode(file_get_contents(VK_API_METHOD . "messages.getById?access_token=" . VK_TOKEN . "&v=" . VK_VERSION . "&message_ids=" . $message_id));
$countAttach = sizeof($res->response->items[0]->attachments);
	for ($i = 0; $i < $countAttach; $i++) { 
		$media = $res->response->items[0]->attachments[$i]->type;
		$attach = $res->response->items[0]->attachments[$i]->$media;
		switch ($media) {
			case 'doc':
				$name = $attach->title;
				$url = $attach->url;
				break;
			case 'photo':
				if (!is_null($attach->photo_2560)) $url = $attach->photo_2560;
				elseif (!is_null($attach->photo_1280)) $url = $attach->photo_1280;
				elseif (!is_null($attach->photo_807)) $url = $attach->photo_807;
				elseif (!is_null($attach->photo_604)) $url = $attach->photo_604;
				elseif (!is_null($attach->photo_130)) $url = $attach->photo_130;
				elseif (!is_null($attach->photo_75)) $url = $attach->photo_75;
				preg_match('/\/([\w\-\.]+)$/', $url, $matches);
				$name = $matches[1];
				break;
			case 'audio':
				$url = $attach->url;
				$name = "{$attach->artist} - {$attach->title}.mp3";
				break;
		}
		file_put_contents(dirname(__FILE__)."\\attachment\\{$name}", file_get_contents($url));
	}
	return "Done!";
}