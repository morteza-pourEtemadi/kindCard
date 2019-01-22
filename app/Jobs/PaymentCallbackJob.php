<?php

namespace App\Jobs;

use App\Helpers\Telegram;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Telegram\Bot\Objects\Update;

class PaymentCallbackJob extends Job
{
    use InteractsWithQueue, Queueable, SerializesModels, DispatchesJobs;

    protected $telegram;
    /** @var User $user */
    private $user;
    private $message;

    /**
     * PaymentCallbackJob constructor.
     * @param $token
     * @param $userId
     * @param $message
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function __construct($token, $userId, $message)
    {
        $telegram = new Telegram($token);
        parent::__construct($telegram);

        $this->telegram = $telegram;
        $this->user = User::query()->find($userId);
        $this->message = $message;
    }

    /**
     * @return bool
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function handle()
    {
        $this->telegram->sendMessage([
            'chat_id' => $this->user->telegram_id,
            'text' => $this->message
        ]);

        $update = new Update([]);
        $this->dispatchNow(new MainMenuJob($this->telegram, $update, $this->user));
        return true;
    }
}
