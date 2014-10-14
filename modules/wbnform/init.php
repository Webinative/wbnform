<?php defined('SYSPATH') or die('No direct script access.');

class WBNForm_Autoloader {
    
    public static function autoload($class) {
        if(strpos($class, 'WBN') === 0) {
            include_once Kohana::find_file('classes', $class);
        }
    }
}

spl_autoload_register(array('WBNForm_Autoloader', 'autoload'));