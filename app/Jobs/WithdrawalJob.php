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

class WithdrawalJob extends Job
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $telegram;
    private $update;
    private $user;
    private $data;

    /**
     * DepositJob constructor.
     * @param Telegram $telegram
     * @param Update $update
     * @param User $user
     * @param $data
     */
    public function __construct(Telegram $telegram, Update $update, User $user, $data = null)
    {
        parent::__construct($telegram);
        $this->telegram = $telegram;
        $this->update = $update;
        $this->user = $user;
        $this->data = $data;
    }


    /**
     * @return bool
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function handle()
    {

        return true;
    }
}
