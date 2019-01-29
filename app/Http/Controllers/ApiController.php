<?php

namespace App\Http\Controllers;

use App\Helpers\Telegram;
use App\Jobs\GetUserJob;
use App\Jobs\RegisterUserJob;
use App\Jobs\SendToMeJob;
use App\Jobs\StartJob;
use App\User;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    private $telegram;
    private $mainKeyboard = [
        'نیاز نداری بذار' => 'DepositJob',
        'نیاز داری بردار' => 'WithdrawalJob',
    ];
    private $innerCommands = [
        '/start' => 'StartJob',
        'start' => 'StartJob',
    ];

    /**
     * ApiController constructor.
     *
     * @param Request $request
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function __construct(Request $request)
    {
        date_default_timezone_set('Asia/Tehran');

        $urlPcs = explode('/', url()->current());
        $token = end($urlPcs);
        $this->telegram = new Telegram($token);
    }


    /**
     * @return string
     */
    public function webhook()
    {
        try {
            $update = $this->telegram->getWebhookUpdate();
            // Validate $update
            if ($update->detectType() != 'callback_query') {
                if (!$update->getMessage() || !$update->getMessage()->getFrom()) {
                    $text = "-- Error --" . "\n" .
                        'Message:' . "\n" . 'if (!$update->getMessage() || !$update->getMessage()->getFrom()) {' . "\n\n" .
                        'update:' . "\n" . json_encode($update);
                    $this->dispatchNow(new SendToMeJob($this->telegram, $text));
                    return 'sendToMe';
                }
            }

            // Get User
            $user = $this->dispatchNow(new GetUserJob($this->telegram, $update));

            // New User
            if (!$user) {
                $user = $this->dispatchNow(new RegisterUserJob($this->telegram, $update));
                if ($user instanceof User) {
                    // Direct Link
                    if ($update->getMessage() && is_string($update->getMessage()->getText())) {
                        $mainText = strtolower($update->getMessage()->getText());
                        $text = explode('?', $mainText)[0];

                        if (isset($this->innerCommands[$text])) {
                            $data = explode('.', $this->innerCommands[$text]);
                            $job = 'App\\Jobs\\' . $data[0];

                            if (!isset($data[1])) {
                                if (isset(explode('?', $mainText)[1])) {
                                    $data[1] = explode('?', $mainText)[1];
                                } else {
                                    $data[1] = null;
                                }
                            }
                            $this->dispatchNow(new $job($this->telegram, $update, $user, $data[1]));
                        }
                    }
                    return 'newUserRegistered';
                }
                return 'errorInRegistration';
            }

            // Main Menu Checking
            if ($update->getMessage() && is_string($update->getMessage()->text)) {
                $text = $update->getMessage()->text;

                if (isset($this->mainKeyboard[$text])) {
                    $data = explode('.', $this->mainKeyboard[$text]);
                    $job = 'App\\Jobs\\' . $data[0];

                    if (!isset($data[1])) {
                        $data[1] = null;
                    }
                    $this->dispatchNow(new $job($this->telegram, $update, $user, $data[1]));
                    return 'mainMenuDispatched';
                }
            }

            // Callback Query Commands
            if ($update->detectType() == 'callback_query') {
                $data = explode('.', $update->getCallbackQuery()->getData());

                $job = 'App\\Jobs\\' . $data[0];
                $this->dispatchNow(new $job($this->telegram, $update, $user));
                return 'callbackQueryProcessed';
            }

            // Inner Commands
            if ($update->getMessage() && is_string($update->getMessage()->getText())) {
                $text = strtolower($update->getMessage()->getText());

                if (isset($this->innerCommands[$text])) {
                    $data = explode('.', $this->innerCommands[$text]);
                    $job = 'App\\Jobs\\' . $data[0];

                    if (!isset($data[1])) {
                        $data[1] = null;
                    }
                    $this->dispatchNow(new $job($this->telegram, $update, $user, $data[1]));
                    return 'innerCommandDispatched';
                }
            }

            // Check Activity
            $activity = (array)json_decode($user->activity, true);
            if (!isset($activity['job'])) {
                $this->dispatchNow(new StartJob($this->telegram, $update, $user));
                return 'unexpectedMessageOccurred';
            }

            // Continue Working
            $job = 'App\\Jobs\\' . $activity['job'];
            $this->dispatchNow(new $job($this->telegram, $update, $user));
            return 'ActivityContinued';
        } catch (\Exception $e) {
            $text = "-- Error --" . "\n" .
                'Message:' . "\n" . $e->getMessage() . "\n\n" .
                'File:' . "\n" . $e->getFile() . "\n\n" .
                'Line:' . "\n" . $e->getLine();
            $this->dispatchNow(new SendToMeJob($this->telegram, $text));
            logger(json_encode($e));
            return 'ExceptionLogged';
        }
    }
}
