<?php

namespace App\Http\Controllers;

use App\Card;
use App\Jobs\PaymentCallbackJob;
use App\Receipt;
use Illuminate\Http\Request;
use Larabookir\Gateway\Exceptions\InvalidRequestException;
use Larabookir\Gateway\Exceptions\NotFoundTransactionException;
use Larabookir\Gateway\Exceptions\PortNotFoundException;
use Larabookir\Gateway\Exceptions\RetryException;
use Larabookir\Gateway\Gateway;

class BotController extends Controller
{
    /** @var Card $card */
    protected $card;

    public function __construct()
    {
        date_default_timezone_set('Asia/Tehran');
        $this->card = Card::query()->find(1);
    }

    /**
     * @param Request $request
     * @param $token
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function paymentVerify(Request $request, $token)
    {
        $data = explode('/*/', base64_decode($token));
        $token = $data[0];
        if (isset($data[1])) {
            $userId = $data[1];
        } else {
            throw new \InvalidArgumentException('InvalidParameters! Go Away.', 502);
        }

        try {
            $gateway = Gateway::verify();
            // عملیات خرید با موفقیت انجام شده است
            // در اینجا کالا درخواستی را به کاربر ارائه میکنم
            if ($receipt = Receipt::query()->where('last_transaction_id', $gateway->transactionId())->first()) {
                $receipt->payed_at = now();
                $receipt->save();

                $this->card->balance += $receipt->price;
                $this->card->save();

                $message = "پرداخت با موفقیت انجام شد.\n\n";
                $message .= "با تشکر از شما خیر گرامی، موجودی حساب مهربانی به <b>" . $this->card->balance . "</b> تومان افزایش پیدا کرد.";
            } else {
                $catchMessage = 'transactionNotFound';
            }
        } catch (RetryException $e) {
            $catchMessage = $e->getMessage();
        } catch (PortNotFoundException $e) {
            $catchMessage = $e->getMessage();
        } catch (InvalidRequestException $e) {
            $catchMessage = $e->getMessage();
        } catch (NotFoundTransactionException $e) {
            $catchMessage = $e->getMessage();
        } catch (\Exception $e) {
            $catchMessage = $e->getMessage();
        }

        if (isset($catchMessage)) {
            $message = "متاسفانه مشکلی در تراکنش به وجود آمده است. در صورتی که مبلغ تراکنش تا 72 ساعت آینده به حساب شما واریز نشد، با آی دی @Ultimate_Developers_Admin تماس حاصل فرمایید.";
        }

        $this->dispatchNow(new PaymentCallbackJob($token, $userId, $message));
    }
}
