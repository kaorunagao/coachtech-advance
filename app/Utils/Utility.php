<?php
namespace App\Utils;

use App\Models\Stamp;

class Utility{
    public static function registerStamp($user_id,$attendance,$rest){
        Stamp::updateOrCreate(
            ['user_id' => $user_id],
            ['attendance' => $attendance ,'rest' => $rest,]
        );
    }
}