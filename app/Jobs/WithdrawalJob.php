<?php

namespace App\Jobs;

use App\Card;
use App\Category;
use App\Helpers\CryptHelper;
use App\Helpers\Telegram;
use App\User;
use App\Withdrawal;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Lcobucci\JWT\Signer\Key;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Objects\Update;


class WithdrawalJob extends Job
{
    use InteractsWithQueue, Queueable, SerializesModels, DispatchesJobs;

    protected $telegram;
    private $update;
    private $user;
    private $data;

    /**
     * WithdrawalJob constructor.
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
        $card = Card::query()->find(1);
        $activity = json_decode($this->user->activity, true);
        if (!isset($activity['step'])) {
            $activity = [
                'job' => 'WithdrawalJob',
                'step' => 1
            ];
            $this->user->activity = json_encode($activity);
            $this->user->save();

            $text = "الان کل موجودی حساب " . number_format($card->balance);
            $text .= " تومنه. با ادامه ی فرآیند، با تصمیم سیستم، یه بخشی از این مبلغ به شما تعلق می گیره.\n";
            $text .= "و لازمه بدونید که تو این فرآیند، شما باید شماره کارت، نام و نام خانوادگی ثبت شده روی کارت ";
            $text .= "و یک شماره موبایل رو در اختیار ربات بذارید، تا بتونه عملیات پرداخت رو انجام بده.\n\n";
            $text .= "ادامه بدیم؟";

            $keyboard = [
                ['قبول دارم. ادامه'],
                ['نه! برگرد'],
            ];
            $replyMarkup = Keyboard::make([
                'keyboard' => $keyboard,
                'resize_keyboard' => true,
            ]);

            $this->telegram->sendMessage([
                'chat_id' => $this->user->telegram_id,
                'text' => $text,
                'reply_markup' => $replyMarkup
            ]);
        } else {
            if ($activity['step'] == 1) {
                if ($this->update->getMessage()->text == 'قبول دارم. ادامه') {
                    $chosen = rand(10, 75);
                    $price = floor($card->balance * $chosen / 100);
                    if ($price > 10000000) {
                        $price = 10000000;
                    }
                    if ($price < 10000) {
                        if ($chosen < 60) {
                            $price = floor($card->balance * 0.6);
                        }
                    }
                    $card->balance -= $price;
                    $card->save();

                    $activity['step'] = 2;
                    $activity['price'] = $price;
                    $this->user->activity = json_encode($activity);
                    $this->user->save();

                    $text = "مبلغ مشخص شده از سمت سیستم برابر است با: $price تومان\n";
                    $keyboard = [
                        ['ادامه'],
                        ['انصراف'],
                    ];
                    $replyMarkup = Keyboard::make([
                        'keyboard' => $keyboard,
                        'resize_keyboard' => true,
                    ]);
                    $this->telegram->sendMessage([
                        'chat_id' => $this->user->telegram_id,
                        'text' => $text,
                        'reply_markup' => $replyMarkup
                    ]);
                } else {
                    $this->dispatchNow(new MainMenuJob($this->telegram, $this->update, $this->user));
                    return true;
                }
            } elseif ($activity['step'] == 2) {
                if ($this->update->getMessage()->text == 'ادامه') {
                    $withdrawal = new Withdrawal([
                        'price' => $activity['price'],
                        'status' => Withdrawal::STATUS_NOT_COMPLETED
                    ]);
                    $this->user->withdrawals()->save($withdrawal);
                    $withdrawal->save();

                    $activity['step'] = 3;
                    $activity['subStep'] = 1;
                    $activity['wid'] = $withdrawal->id;
                    unset($activity['price']);
                    $this->user->activity = json_encode($activity);
                    $this->user->save();

                    $replyMarkup = Keyboard::remove([
                        'remove_keyboard' => true
                    ]);
                    $this->telegram->sendMessage([
                        'chat_id' => $this->user->telegram_id,
                        'text' => "حالا شماره کارت 16 رقمی خود را بدون خط فاصله و علامت اضافی وارد نمایید.",
                        'reply_markup' => $replyMarkup
                    ]);
                } else {
                    $card->balance += $activity['price'];
                    $card->save();

                    $this->dispatchNow(new MainMenuJob($this->telegram, $this->update, $this->user));
                    return true;
                }
            } elseif ($activity['step'] == 3 && isset($activity['subStep'])) {
                $withdrawal = Withdrawal::query()->find($activity['wid']);
                switch ($activity['subStep']) {
                    case 1:
                        $cardNumber = trim($this->changeToNormal($this->update->getMessage()->text));
                        if (strlen($cardNumber) != 16 || !is_numeric($cardNumber)) {
                            $text = "شماره کارت وارد شده صحیح نمی باشد. لطفا در وارد کردن شماره کارت دقت فرمایید.\nلطفا مجددا سعی نمایید.";
                        } else {
                            $withdrawal->card_number = $cardNumber;
                            $withdrawal->save();

                            $activity['subStep'] = 2;
                            $this->user->activity = json_encode($activity);
                            $this->user->save();

                            $this->telegram->sendMessage([
                                'chat_id' => $this->user->telegram_id,
                                'text' => "شماره کارت با موفقیت ثبت شد"
                            ]);

                            $text = "نام و نام خانوادگی روی کارت را وارد نمایید.";
                        }
                        break;
                    case 2:
                        $withdrawal->full_name = $this->update->getMessage()->text;
                        $withdrawal->save();

                        $activity['subStep'] = 3;
                        $this->user->activity = json_encode($activity);
                        $this->user->save();

                        $this->telegram->sendMessage([
                            'chat_id' => $this->user->telegram_id,
                            'text' => "نام و نام خانوادگی ثبت شد."
                        ]);

                        $text = "شماره همراه خود را وارد نمایید.";
                        break;
                    case 3:
                        $withdrawal->mobile = $this->update->getMessage()->text;
                        $withdrawal->save();

                        $activity['subStep'] = 1;
                        $activity['step'] = 4;
                        $this->user->activity = json_encode($activity);
                        $this->user->save();

                        $this->telegram->sendMessage([
                            'chat_id' => $this->user->telegram_id,
                            'text' => "شماره همراه شما ثبت شد."
                        ]);

                        $text = "لطفا در صورت تمایل، مورد استفاده این مبلغ را در یک کلمه بنویسید.";
                        break;
                }
                $this->telegram->sendMessage([
                    'chat_id' => $this->user->telegram_id,
                    'text' => $text
                ]);
            } elseif ($activity['step'] == 4 && isset($activity['subStep'])) {
                /** @var Withdrawal $withdrawal */
                $withdrawal = Withdrawal::query()->find($activity['wid']);
                if ($activity['subStep'] == 1) {
                    $category = $this->getCategory($this->update->getMessage()->text);
                    $withdrawal->category()->associate($category);

                    $keyboard = Keyboard::inlineButton([
                        'text' => 'تکمیل فرایند',
                        'url' => route('withdrawal.verify', ['token' => CryptHelper::encryptData($withdrawal->id)])
                    ]);
                    $replyMarkup = Keyboard::make()->inline();
                    $replyMarkup->row($keyboard);

                    $this->telegram->sendMessage([
                        'chat_id' => $this->user->telegram_id,
                        'text' => 'برای تکمیل فرایند دریافت وجه، «حتما» روی دکمه ی زیر کلیک فرمایید.',
                        'reply_markup' => $replyMarkup,
                    ]);

                    $this->dispatchNow(new MainMenuJob($this->telegram, $this->update, $this->user));
                    return true;
                }
            }
        }
        return true;
    }

    /**
     * @param $cat
     * @return Category
     */
    public function getCategory($cat)
    {
        /** @var Category[] $categories */
        $categories = Category::query()->where('name', 'like', '%' . $cat . '%')->get();
        $maxPercent = 0;
        $theCat = null;
        foreach ($categories as $category) {
            $percent = 0;
            similar_text($cat, $category->name, $percent);
            if ($percent > $maxPercent) {
                $maxPercent = $percent;
                $theCat = $category;
            }
        }

        if (is_null($theCat) || !$theCat || (int)$maxPercent <= 90) {
            $theCat = new Category([
                'name' => $cat
            ]);
            $theCat->save();
        }

        return $theCat;
    }
}
