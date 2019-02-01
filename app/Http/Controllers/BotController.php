<?php

namespace App\Http\Controllers;

use App\Card;
use App\Helpers\CryptHelper;
use App\Jobs\PaymentCallbackJob;
use App\Receipt;
use App\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Larabookir\Gateway\Exceptions\InvalidRequestException;
use Larabookir\Gateway\Exceptions\NotFoundTransactionException;
use Larabookir\Gateway\Exceptions\PortNotFoundException;
use Larabookir\Gateway\Exceptions\RetryException;
use Larabookir\Gateway\Gateway;
use Larabookir\Gateway\Zarinpal\ZarinpalException;

class BotController extends Controller
{
    /** @var Card $card */
    protected $card;
    protected $bots = [
        '721417213:AAHo3TDlWq0th-yH7e7tJmo3JGGnH-ZbvgI' => 'kind_card_bot',
        '738430620:AAHFxDgdHy-pGedhV_yts0-msYlfdxz2S7w' => 'kindCardBot',
        '792906820:AAE95ig7jVTTGcBrx7MBgE8eKQV8jiQ5XT4' => 'kind_kart_bot',
        '634840089:AAFYcfYcrMGF5k0rJTFpekJ1cBkUw0G5xQk' => 'kindKartBot',
        '795096806:AAFRfjiQFub0pPTAysA3sh6qmxIGHraJDZg' => 'kindCartBot',
    ];

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
        $status = 'fail';
        $catchMessage = '';
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
                $status = 'success';
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
        } catch (ZarinpalException $e) {
            $catchMessage = ZarinpalException::$errors[$e->getCode()];
        } catch (\Exception $e) {
            $catchMessage = "متاسفانه مشکلی در تراکنش به وجود آمده است. در صورتی که مبلغ تراکنش تا 72 ساعت آینده به حساب شما واریز نشد، با آی دی @Ultimate_Developers_Admin تماس حاصل فرمایید.";
        }

        if ($status == 'fail') {
            $message = $catchMessage;
        }

        $this->dispatchNow(new PaymentCallbackJob($token, $userId, $message));

        $username = $this->bots[$token];
        $balance = $this->card->balance;
        return view('payment-result', compact('username', 'balance', 'status', 'catchMessage'));
    }

    /**
     * @param $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function withdrawalVerify($token)
    {
        try {
            $withdrawal = Withdrawal::query()->find(CryptHelper::decryptData($token));
            $withdrawal->status = Withdrawal::STATUS_NOT_PAYED;
            $withdrawal->save();

            return view('withdrawal-result', compact('token'));
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('InvalidParameters! Go Away.', 403);
        }
    }

    /**
     * @return string
     */
    public function cancelUnCompleteWithdrawals()
    {
        $till = date('Y-m-d H:i:s', time() - 30 * 3600);
        $withdrawalsQuery = Withdrawal::query()
            ->where('status', Withdrawal::STATUS_NOT_COMPLETED)
            ->whereDate('created_at', '<', $till);
        $withdrawals = $withdrawalsQuery->get();

        /** @var Withdrawal $withdrawal */
        foreach ($withdrawals as $withdrawal) {
            $this->card->balance += $withdrawal->price;
            $this->card->save();
            $withdrawal->status = Withdrawal::STATUS_CANCELED;
            $withdrawal->save();
        }

        return $withdrawalsQuery->count() . ' withdrawals canceled';
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function loginPage(Request $request)
    {
        if ($request->method() == 'GET') {
            return view('admin.login');
        } else {
            $data = $request->all();
            if (isset($data['username']) && isset($data['password']) && $data['username'] == '101538817' && $data['password'] == 'qazwsx') {
                Cookie::queue(Cookie::forever('user', base64_encode(base64_encode('101538817'))));
                return redirect()->back();
            } else {
                return view('admin.login');
            }
        }
    }
}
