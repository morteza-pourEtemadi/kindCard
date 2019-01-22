<?php

namespace App\Jobs;

use App\Helpers\Telegram;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Telegram\Bot\Objects\Update;

class RegisterUserJob extends Job
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $update;

    /**
     * RegisterUserJob constructor.
     * @param Telegram $telegram
     * @param Update $update
     */
    public function __construct(Telegram $telegram, Update $update)
    {
        parent::__construct($telegram);
        $this->update = $update;
    }


    /**
     * @return User
     */
    public function handle()
    {
        if ($this->update->detectType() == 'callback_query') {
            $from = $this->update->getCallbackQuery()->getMessage()->getChat();
        } else {
            $from = $this->update->getMessage()->getFrom();
        }

        $user = new User();
        $user->telegram_id = $from->getId();
        $user->name = $from->getFirstName();
        $user->last_name = $from->getLastName();
        $user->username = $from->getUsername();
        $strRnd = sprintf("%06d", mt_rand(1, 999999));
        while ($oldUser = User::query()->where('code', $strRnd)->first()) {
            $strRnd = sprintf("%06d", mt_rand(1, 999999));
        }
        $user->code = $strRnd;
        $user->save();

        return $user;
    }
}
