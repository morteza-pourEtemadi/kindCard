<?php

namespace App\Jobs;

use App\Helpers\Telegram;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Objects\Update;

class MainMenuJob extends Job
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $telegram;
    private $update;
    private $user;

    /**
     * MainMenuJob constructor.
     * @param Telegram $telegram
     * @param Update $update
     * @param User $user
     */
    public function __construct(Telegram $telegram, Update $update, User $user)
    {
        parent::__construct($telegram);
        $this->telegram = $telegram;
        $this->update = $update;
        $this->user = $user;
    }


    /**
     * @return bool
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function handle()
    {
        $this->user->activity = null;
        $this->user->save();

        $keyboard = [
            ['نیاز داری بردار', 'نیاز نداری بذار']
        ];
        $replyMarkup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $this->user->telegram_id,
            'text' => "حساب مهربانی در خدمت شماست.",
            'reply_markup' => $replyMarkup
        ]);

        return true;
    }
}
