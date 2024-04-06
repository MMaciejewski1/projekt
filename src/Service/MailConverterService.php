<?php

namespace App\Service;


class MailConverterService{

    public static function getMailData($mail,$option1,$option2) {
        $start = (strpos($mail,$option1)+strlen($option1)+1);
        $mail = substr($mail,$start);
        $end = strpos($mail,$option2);
        if(strlen($option2)<1){
            return $mail;
        }
        return substr($mail,0,$end);
    }
}