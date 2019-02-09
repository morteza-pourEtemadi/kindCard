<?php

namespace App\Http\Controllers;

use App\Card;
use App\Category;

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
}
