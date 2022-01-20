<?php

namespace app\core;

class Session
{
    protected const FLASH_KEY = 'flash_messages';

    public function __construct()
    {
        session_start ();
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];                      // 1.get messages array
        //array(1) {
        //  ["success"]=>   \\**$key argument of setFlash() from controller
        //  array(2) {
        //    ["remove"]=>
        //    bool(false)   \\**setFlash() make this false**
        //    ["value"]=>
        //    string(23) "Thanks for registering!"  \\**$messages argument of setFlash() from controller
        //  }
        //}
        //use & to make $flashMessage to references of a pointer pointing to $flashMessageS
        foreach ($flashMessages as $key => &$flashMessage) {                    // 2.iterate over those
            //if not use &, this just a copies of pointer $flashMessageS
            $flashMessage['remove'] = true;//mark to be removed                 // 3.mark them remove
        }
        //$_SESSION['flash_messages']['remove=>true']['message']
        $_SESSION[self::FLASH_KEY] = $flashMessages;                            // 4.then set message back into session
//        var_dump ($_SESSION[self::FLASH_KEY]);
    }

    public function setFlash($key, $message)//controller use this
    {
        //ex: $_SESSION['flash_messages']['success']['remove'=>false, 'value'=>'hey yo, congratulation...']
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message,
        ];
    }

    public function getFlash($key)//view use this
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        return $_SESSION[$key] ?? false;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public function __destruct()
    {
        //iterate over marked
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        //array(1) {
        //  ["success"]=>
        //  array(2) {
        //    ["remove"]=>
        //    bool(true)    \\**__construct make it from false to true
        //    ["value"]=>
        //    string(23) "Thanks for registering!"
        //  }
        //}
        foreach ($flashMessages as $key => &$flashMessage) {//use & for reference, not a copies
            if($flashMessage['remove']){//if remove = true
                unset($flashMessages[$key]);//$_SESSION['flash_messages']['*destroy all of key*']
            }
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;//now is array(0);
//        var_dump ($_SESSION[self::FLASH_KEY]);
    }
}