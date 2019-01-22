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

class DepositJob extends Job
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
        $activity = json_decode($this->user->activity, true);
        if (isset($activity['step']) && $activity['step'] == 1) {
            $input = $this->update->getMessage()->getText();
            $text = '';
            if (is_numeric($this->changeToNormal($input))) {
                $price = $this->changeToNormal($input);
            } else {
                $price = $this->getPriceAnyWay($input);
                $text = 'رقم معادل ورودی شما برابر است با: ' . $price . " تومان\n\n";
            }
            $url = $this->getPaymentLink($this->user, $price);
            if ($url == '') {
                $text = 'مشکلی در ارتباط با درگاه بانکی پیش آمده است. لطفا دوباره تلاش نمایید.';
                $keyboard = Keyboard::inlineButton([
                    'text' => 'تلاش مجدد',
                    'callback_data' => 'DepositJob'
                ]);
            } else {
                $text .= "برای پرداخت، لطفا دکمه ی زیر را لمس نمایید.\nدر صورتی که می خواهید مبلغ را تصحیح کنید، مبلغ جدید را مجددا وارد نمایید.";
                $keyboard = Keyboard::inlineButton([
                    'text' => 'شارژ حساب مهربانی',
                    'url' => $url
                ]);
            }
            $replyMarkup = Keyboard::make()->inline();
            $replyMarkup->row([$keyboard]);

            $this->telegram->sendMessage([
                'chat_id' => $this->user->telegram_id,
                'text' => $text,
                'reply_markup' => $replyMarkup,
            ]);
        } else {
            $activity = [
                'job' => 'DepositJob',
                'step' => 1
            ];
            $this->user->activity = json_encode($activity);
            $this->user->save();

            $this->telegram->sendMessage([
                'chat_id' => $this->user->telegram_id,
                'text' => 'مبلغ مورد نظرتون رو به تومان وارد کنید.',
            ]);
        }
        return true;
    }
}
