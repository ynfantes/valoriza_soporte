<?php

class Auth {
    
    protected static $allow = ['Facebook','Twitter','Google'];
    
    protected static function issetRequest() {
        if ($_GET['login']) {
            if (in_array($_GET['login'],self::$allow)) {
                return true;
            }
        }
        return false;
    }
    
    public static function getUserAuth() {
        if (self::issetRequest()) {
            
            $service = $_GET['login'];
            $hybridAuth = new Hybridauth\Hybridauth(__DIR__.'\config.php');
            
            $adapter = $hybridAuth->authenticate($service); 
            
            $userProfile = $adaptar->getUserProfile();
            
            var_dump($userProfile);
            
            die();
        }
    }
}
