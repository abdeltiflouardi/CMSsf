<?php

namespace App\CoreBundle\Controller;

class ErrorController extends BaseController
{

    public static $errors = array(
        404 => 'Item not found!',
        405 => 'Action not allowed',
        900 => 'An error occured!',
    );

    public function error403Action()
    {
        return $this->renderTpl('Error:error', array('message' => ''));
    }

    public static function error($code = 900, $message = null)
    {

        if (!isset(self::$errors[$code])) {
            $code = 900;
        }

        if ($message === null) {
            if (isset(self::$errors[$code])) {
                $message = self::$errors[$code];
            }
        }

        return compact('message');
    }
}
