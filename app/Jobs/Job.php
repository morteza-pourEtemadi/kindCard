<?php

namespace App\Jobs;

use App\Helpers\Telegram;
use App\Receipt;
use App\User;
use Illuminate\Bus\Queueable;
use Larabookir\Gateway\Gateway;

abstract class Job
{
    use Queueable;

    protected $telegram;
    protected $admins = [
        'morteza' => '101538817'
    ];

    /**
     * Job constructor.
     */
    public function __construct(Telegram $telegram)
    {
        date_default_timezone_set('Asia/Tehran');
        $this->telegram = $telegram;
    }

    /**
     * @param $text
     * @return int
     */
    public function getPriceAnyWay($text)
    {
        $persianLetterArray = [
            'ا',
            'ب',
            'پ',
            'ت',
            'ث',
            'ج',
            'چ',
            'ح',
            'خ',
            'د',
            'ذ',
            'ر',
            'ز',
            'ژ',
            'س',
            'ش',
            'ص',
            'ض',
            'ط',
            'ظ',
            'ع',
            'غ',
            'ف',
            'ق',
            'ک',
            'گ',
            'ل',
            'م',
            'ن',
            'و',
            'ه',
            'ی',
        ];
        $englishLetterArray = [
            'a',
            'b',
            'c',
            'd',
            'e',
            'f',
            'g',
            'h',
            'i',
            'j',
            'k',
            'l',
            'm',
            'n',
            'o',
            'p',
            'q',
            'r',
            's',
            't',
            'u',
            'v',
            'w',
            'x',
            'y',
            'z',
            'z',
            'z',
            'z',
            'z',
            'z',
            'z',
        ];

        $text = $this->changeToNormal($text);
        $text = (string)str_replace($persianLetterArray, $englishLetterArray, $text);
        $text = strtolower($text);

        $price = '';
        $txtArray = str_split($text);
        foreach ($txtArray as $letter) {
            $price .= is_numeric($letter) ? (string)$letter : (string)((int)array_search($letter, $englishLetterArray) + 1);
        }
        return (int)$price;
    }

    /**
     * @param $text
     * @return string
     */
    public function changeToNormal($text)
    {
        $persianReplaceArray = [
            'ي',
            'ﯼ',
            'ة',
            'ك‌',
            '۰',
            '۱',
            '۲',
            '۳',
            '۴',
            '۵',
            '۶',
            '۷',
            '۸',
            '۹',
        ];
        $replaceArray = [
            'ی',
            'ی',
            'ه',
            'ک',
            '0',
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
        ];

        return (string)str_replace($persianReplaceArray, $replaceArray, $text);
    }

    /**
     * @param User $user
     * @param $price
     * @return string
     */
    public function getPaymentLink($user, $price)
    {
        $receipt = new Receipt([
            'price' => $price,
        ]);
        $user->receipts()->save($receipt);

        $payPort = 'zarinpal';
        try {
            $gateway = Gateway::$payPort();
            $uri = 'payment/verify/' . base64_encode($this->telegram->accessToken . '/*/' . $user->id);
            $gateway->setCallback(env('WEB_URL', 'https://kindCard.ultimate-developers.ir/') . $uri);
            $gateway->price($receipt->price * 10)->ready();
            $transID = $gateway->transactionId();

            $thisReceiptTrans = json_decode($receipt->transactions);
            $thisReceiptTrans[] = $transID;
            $receipt->transactions = json_encode($thisReceiptTrans);
            $receipt->last_transaction_id = $transID;
            $receipt->save();

            preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $gateway->redirect(), $url);
            $url = str_replace('ZarinGate', 'Sep', $url);
            if (is_array($url)) {
                $url = $url[0][0];
            }

            return $url;
        } catch (\Exception $e) {
            dd($e);
            return '';
        }
    }
}
