<?php

namespace App\Validations;

use App\Event;

class Validator
{
    /**
     * @throws
    */
    public static function eventValidation($event)
    {
        if ($event == null) {
            throw(new \Exception("Event name not found , please create an event first"));
        }
    }
    /**
     * @throws
     */
    public static function urlValidation($url)
    {
        if (!(preg_match('%^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu', $url))) {
            throw(new \Exception("This is not a valid URL"));
        }
    }

    /**
     * @throws
     */
    public static function eventNameValidation($name)
    {
        $event = Event::where('name', $name)->first();
        if ($event !=null) {
            throw(new \Exception("Event Name Taken "));
        }
    }

}
