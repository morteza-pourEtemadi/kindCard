<?php

namespace App\Http\Controllers;

use App\Card;

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
        return view('welcome');
    }
}
