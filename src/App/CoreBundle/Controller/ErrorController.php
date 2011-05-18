<?php

namespace App\CoreBundle\Controller;

class ErrorController extends BaseController {

    static $_errors = array(
        404 => 'Item not found!',
        900 => 'An error occured!',
    );
    
    public function error403Action() {
        return $this->renderTpl('Error:error', array('message' => ''));
    }
    
    static function error($code = 900, $message = null) {
        
        if (!isset(self::$_errors[$code]))
                $code = 900;
        
        if ($message === null)
            if (isset(self::$_errors[$code]))
                    $message = self::$_errors[$code];
        
        return compact('message');
    }
}
