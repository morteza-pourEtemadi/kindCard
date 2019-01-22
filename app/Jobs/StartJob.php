<?php

namespace App\Jobs;

use App\Helpers\Telegram;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Telegram\Bot\Objects\Update;

class StartJob extends Job
{
    use InteractsWithQueue, Queueable, SerializesModels, DispatchesJobs;

    protected $telegram;
    private $update;
    private $user;

    /**
     * StartJob constructor.
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
        $this->telegram->sendMessage([
            'chat_id' => $this->user->telegram_id,
            'text' => "سلام. به حساب مهربانی خوش آمدید",
        ]);

        $this->dispatchNow(new MainMenuJob($this->telegram, $this->update, $this->user));
        return true;
    }
}
