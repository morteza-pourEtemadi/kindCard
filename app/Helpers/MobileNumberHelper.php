<?php

namespace App\Helpers;

class MobileNumberHelper
{

    /**
     * @param $mobileNumber
     * @return bool|string
     */
    public static function trim($mobileNumber)
    {
        $mobileNumber = (string)(int)$mobileNumber;

        if (strlen($mobileNumber) < 10 || strlen($mobileNumber) > 11) {
            return null;
        }

        if (strlen($mobileNumber) == 10) {
            $mobileNumber = '0' . $mobileNumber;
        }

        return (string)$mobileNumber;
    }

}
