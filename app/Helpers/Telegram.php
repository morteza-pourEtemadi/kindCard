<?php

namespace App\Helpers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Message as MessageObject;

class Telegram extends Api
{
    use DispatchesJobs;

    public $accessToken;

    /**
     * @param array $params
     * @return MessageObject
     */
    public function sendMessage(array $params = []): MessageObject
    {
        try {
            $params['parse_mode'] = 'HTML';
            $message = parent::sendMessage($params);
        } catch (\Exception $e) {
            $message = new MessageObject([]);
        }
        return $message;
    }

}
