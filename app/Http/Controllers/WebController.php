<?php

namespace App\Http\Controllers;

use App\Card;
use App\Category;
use App\Mail\NewContactUsEmail;
use App\Receipt;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Larabookir\Gateway\Exceptions\InvalidRequestException;
use Larabookir\Gateway\Exceptions\NotFoundTransactionException;
use Larabookir\Gateway\Exceptions\PortNotFoundException;
use Larabookir\Gateway\Exceptions\RetryException;
use Larabookir\Gateway\Gateway;
use Larabookir\Gateway\Zarinpal\ZarinpalException;

class WebController extends Controller
{
    /** @var Card $card */
    protected $card;

    /**
     * WebController constructor.
     */
    public function __construct()
    {
        date_default_timezone_set('Asia/Tehran');
        $this->card = Card::query()->find(1);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $balance = $this->card->balance;
        $categories = $this->getCategoryGrouping();
        return view('welcome', compact('categories', 'balance'));
    }

    /**
     * @return array
     */
    protected function getCategoryGrouping()
    {
        $groups = [
            'غذا' => [
                'غذا', 'خوراکی', 'صبحانه', 'ناهار', 'شام', 'نان', 'گوشت', 'مرغ', 'سبزی', 'سبزیجات', 'برنج', 'حبوبات', 'غلات', 'شیرینی', 'شکلات',
            ],
            'درمان' => [
                'درمان', 'دارو', 'دکتر', 'پزشک', 'عمل', 'جراحی', 'قرص', 'آمپول', 'واکسن', 'شربت', 'قطره', 'بانداژ', 'بیمارستان', 'مطب', 'درمانگاه',
            ],
            'لباس' => [
                'لباس', 'کفش', 'شلوار', 'پیراهن', 'جوراب', 'روسری', 'چادر', 'مقنعه', 'مانتو', 'بلوز', 'تی شرت',
            ],
            'مسکن' => [
                'خانه', 'مسکن', 'منزل', 'اجاره', 'ودیعه', 'پول پیش',
            ],
            'تحصیلات' => [
                'لوازم', 'تحریر', 'مداد', 'خودکار', 'دفتر', 'کتاب', 'پاک کن', 'تراش', 'کاغذ', 'جامدادی',
            ],
            'جهیزیه' => [
                'جهیزیه', 'لوازم', 'اسباب', 'اثاثیه', 'یخچال', 'لباس شویی', 'اجاق', 'گاز', 'تلویزیون', 'تخت',
            ],
            'سایر' => []
        ];
        $groupedCategories = [];
        foreach ($groups as $cat => $group) {
            $groupedCategories[$cat] = [
                'count' => 0,
                'balance' => 0,
            ];
        }
        $categories = Category::all();
        foreach ($categories as $category) {
            $found = false;
            foreach ($groups as $cat => $group) {
                foreach ($group as $item) {
                    $percent = 0;
                    similar_text($category->name, $item, $percent);
                    if ($percent >= 90) {
                        $groupedCategories[$cat]['count']++;
                        $groupedCategories[$cat]['balance'] += $category->spent;
                        $found = true;
                    }
                }
            }
            if (!$found) {
                $groupedCategories['سایر']['count']++;
                $groupedCategories['سایر']['balance'] += $category->spent;
            }
        }

        return $groupedCategories;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mailChimpAction(Request $request)
    {
        $authKey = "debc7d7b322288a158105b7bdd7fb61b-us20";
        $server = explode('-', $authKey)[1];

        $url = 'https://' . $server . '.api.mailchimp.com/3.0/lists/b0dab3bcd5/members';
        $payload = [
            'email_address' => $request->post('email'),
            'status' => 'subscribed',
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_USERPWD, "sanjeman:" . $authKey);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_exec($curl);

        if (curl_error($curl)) {
            $response = [
                'type' => 'danger',
                'message' => 'خطایی پیش آمده است. لطفا بعدا مجددا تلاش نمایید'
            ];
        } else {
            $response = [
                'type' => 'success',
                'message' => 'شما با موفقیت در خبرنامه حساب مهربانی عضو شدید'
            ];
        }
        curl_close($curl);

        return response()->json($response);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contact(Request $request)
    {
        if ($request->method() == 'GET') {
            return view('contact-us');
        } else {
            try {
                $mail = Mail::to('e.morteza94@gmail.com')->send(new NewContactUsEmail($request->post('name'), $request->post('email'), $request->post('text')));
                logger(json_encode($mail));
            } catch (\Exception $e) {
                logger(json_encode($e));
            }
            return response()->json(['status' => 'success', 'message' => 'EIO']);
        }
    }

    public function sendAid(Request $request)
    {
        try {
            $price = $request->post('price');
            $user = Auth::user();
            if (!$user) {
                $user = new User([
                    'username' => md5(time()) . 'temp.kindcard.ir'
                ]);
                $user->save();
                Auth::login($user);
            }

            $url = $this->getPaymentLink($user, $price);
        } catch (\Exception $e) {
            return response()->json(['type' => 'danger', 'message' => 'متاسفانه مشکلی پیش آمده است. لطفا دوباره تلاش نمایید.']);
        }
        return response()->json(['type' => 'success', 'message' => 'کمک شما در راه است', 'url' => $url]);
    }

    /**
     * @param User $user
     * @param $price
     * @return mixed|string
     */
    protected function getPaymentLink($user, $price)
    {
        $receipt = new Receipt([
            'price' => $price,
        ]);
        $user->receipts()->save($receipt);

        $payPort = 'zarinpal';
        try {
            $gateway = Gateway::$payPort();
            $uri = 'web/payment/verify/' . base64_encode($receipt->id . '/*/' . $user->id);
            $gateway->setCallback(env('WEB_URL', 'https://kindCard.ir/') . $uri);
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

        $balance = $this->card->balance;
        return view('payment-result', compact('balance', 'status', 'catchMessage'));
    }
}
