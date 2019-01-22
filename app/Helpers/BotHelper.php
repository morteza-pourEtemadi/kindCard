<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Telegram\Bot\Api;

class BotHelper extends Api
{

    use DispatchesJobs;


    /**
     * @param User $user
     * @return array|bool
     */
    public static function getTopQuestion(User $user)
    {
        $activity = json_decode($user->activity, true);

        $topQuestion = false;
        if (isset($activity['topQuestion'])) {
            $topQuestion = (array)$activity['topQuestion'];
        }

        return $topQuestion;
    }


    /**
     * @param User $user
     * @param $questionId
     * @return array
     */
    public static function getAnswerIds(User $user, $questionId)
    {
        $activity = json_decode($user->activity, true);
        $answers = isset($activity['answers']) ? (array)$activity['answers'] : [];

        foreach ($answers as $answer) {
            if ($answer['questionId'] == $questionId) {
                return isset($answer['answerIds']) ? $answer['answerIds'] : [];
            }
        }

        return [];
    }


    /**
     * @param array $question
     * @param array $answer
     * @return bool
     * @throws \Exception
     */
    public static function isFinished(array $question, array $answer)
    {
        if (is_null($question['type'])) {
            return true;
        }

        if ($question['type'] == 'dropdown') {
            return true;
        }

        if ($question['type'] == 'sortable') {
            return true;
        }

        throw new \Exception("[class BotHelper extends Api]->isFinished(), question['type'] (" . $question['type'] . ") is not implemented!");
    }


    /**
     * @param $items
     * @param $perPage
     * @param $pageStart
     * @return LengthAwarePaginator
     */
    public static function paginate($items, $perPage, $pageStart = 1)
    {
        // Start displaying items from this number;
        $offSet = ($pageStart * $perPage) - $perPage;

        // Get only the items you need using array_slice
        $itemsForCurrentPage = array_slice($items, $offSet, $perPage, true);

        return new LengthAwarePaginator($itemsForCurrentPage, count($items), $perPage, $pageStart, array('path' => Paginator::resolveCurrentPath()));
    }


    /**
     * @param array $question
     * @return string
     */
    public static function getClassName(array $question)
    {
        $className = 'Other';
        if ($question['type']) {
            $className = ucfirst((string)$question['type']);
        }
        return "App\\Helpers\\Bot\\Question\\" . $className;
    }


    /**
     * @param User $user
     * @param array $question
     * @return array|bool
     */
    public static function getNextQuestion(User $user, array $question)
    {
        $activity = (array)json_decode($user->activity, true);


        // get $questionIndex
        foreach ($activity['page']['questions'] as $key => $q) {
            if ($question['id'] == $q['id']) {
                $questionIndex = $key;
                break;
            }
        }
        if (!isset($questionIndex)) {
            return false;
        }


        // check $nextQuestion
        $nextQuestionIndex = $questionIndex + 1;
        if (!isset($activity['page']['questions'][$nextQuestionIndex])) {
            return false;
        }


        // update $nextQuestion
        return (array)$activity['page']['questions'][$nextQuestionIndex];
    }

}
