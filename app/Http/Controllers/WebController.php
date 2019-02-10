<?php

namespace App\Http\Controllers;

use App\Card;
use App\Category;
use Illuminate\Http\Request;

class WebController extends Controller
{
    /** @var Card $card */
    protected $card;

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
}
