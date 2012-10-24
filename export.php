<?php

    error_reporting(E_ALL);

    ini_set('memory_limit', '2048M');
    ini_set('max_execution_time', 900);

    try {
        if(!defined('_JEXEC')){
            define('_JEXEC', 1);
            define('JPATH_BASE', dirname(dirname(__FILE__)));
            define('DS', '/' );

            require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
            require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
            require_once ( JPATH_BASE .DS.'libraries'.DS.'joomla'.DS.'factory.php' );

            $mainframe =& JFactory::getApplication('site');
        }
    }catch (Exception $e) {
        echo "error: ".$e->message();
    }

    // $dom = new DOMDocument('1.0', 'UTF-8');
    // echo $dom->saveXML();

    echo "banana";

    ?>