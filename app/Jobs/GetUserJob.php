<?php

namespace App\Jobs;

use App\Helpers\Telegram;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Telegram\Bot\Objects\Update;

class GetUserJob extends Job
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $update;

    /**
     * GetUserJob constructor.
     * @param Telegram $telegram
     * @param Update $update
     */
    public function __construct(Telegram $telegram, Update $update)
    {
        parent::__construct($telegram);
        $this->update = $update;
    }

    /**
     * @return User|null
     */
    public function handle()
    {
        if ($this->update->detectType() == 'callback_query') {
            $from = $this->update->getCallbackQuery()->getFrom();
        } else {
            $from = $this->update->getMessage()->getFrom();
        }

        $telegramId = $from->getId();
        /** @var User $user */
        $user = User::query()->where('telegram_id', $telegramId)->first();
        if (!$user) {
            return null;
        }

        $user->name = $from->getFirstName();
        $user->last_name = $from->getLastName();
        $user->username = $from->getUsername();
        $user->save();

        return $user;
    }
}
