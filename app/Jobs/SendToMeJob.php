<?php

namespace App\Jobs;

use App\Helpers\Telegram;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendToMeJob extends Job
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $text;

    /**
     * SendToMeJob constructor.
     * @param Telegram $telegram
     */
    public function __construct(Telegram $telegram, $text)
    {
        parent::__construct($telegram);
        $this->text = $text;
    }

    /**
     * @return bool
     */
    public function handle()
    {
        $this->telegram->sendMessage([
            'chat_id' => $this->admins['morteza'],
            'text' => $this->text
        ]);
        return true;
    }
}
