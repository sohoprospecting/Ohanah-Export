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

    $query  = "SELECT  `events`.`ohanah_event_id` AS `eventid`,                                  \n\r";
    $query .= "        ''                         AS `eventtypeid`,                              \n\r";
    $query .= "        ''                         AS `eventtype`,                                \n\r";
    $query .= "        `events`.`title`           AS `title`,                                    \n\r";
    $query .= "        `events`.`description`     AS `description`,                              \n\r";
    $query .= "        DATE_FORMAT(`events`.`date`,'%m/%d/%Y')     AS `startdate`,               \n\r";
    $query .= "        DATE_FORMAT(`events`.`end_date`,'%m/%d/%Y') AS `enddate`,                 \n\r";
    $query .= "        CASE `events`.`isRecurring` WHEN '1' THEN                                 \n\r";
    $query .= "            CONCAT('Recurring every ',                                            \n\r";
    $query .= "                   `events`.`everyNumber`,' ',                                    \n\r";
    $query .= "                   `events`.`everyWhat`,' until ',                                \n\r";
    $query .= "                   DATE_FORMAT(`events`.`endOnDate`,'%m/%d/%Y'), '.')             \n\r";
    $query .= "        ELSE                                                                      \n\r";
    $query .= "            ''                                                                    \n\r";
    $query .= "        END AS `recurrence`,                                                      \n\r";
    $query .= "        ''                   AS `time`,                                           \n\r";
    $query .= "        `events`.`venue`     AS `location`,                                       \n\r";
    $query .= "        ''                   AS `phone`,                                          \n\r";
    $query .= "        ''                   AS `admission`,                                      \n\r";
    $query .= "        ''                   AS `website`,                                        \n\r";
    $query .= "        ''                   AS `imagefile`,                                      \n\r";
    $query .= "        `events`.`address`   AS `address`,                                        \n\r";
    $query .= "        ''                   AS `city`,                                           \n\r";
    $query .= "        ''                   AS `state`,                                          \n\r";
    $query .= "        ''                   AS `zip`,                                            \n\r";
    $query .= "        `events`.`latitude`  AS `latitude`,                                       \n\r";
    $query .= "        `events`.`longitude` AS `longitude`,                                      \n\r";
    $query .= "        CASE `events`.`featured` WHEN '1' THEN 'Yes' ELSE 'No' END AS `featured`, \n\r";
    $query .= "        ''                   AS `listingid`,                                      \n\r";
    $query .= "        DATE_FORMAT(`events`.`created_on`,'%m/%d/%Y %r') AS `created`,            \n\r";
    $query .= "        DATE_FORMAT(`events`.`created_on`,'%m/%d/%Y %r') AS `lastupdated`,        \n\r";
    $query .= "        `cat`.`ohanah_category_id` AS `categoryid`,                               \n\r";
    $query .= "        `cat`.`title`              AS `categoryname`                              \n\r";
    $query .= " FROM  `#__ohanah_events`          AS `events` INNER JOIN `#__ohanah_categories` AS `cat` ON `cat`.`ohanah_category_id` = `events`.`ohanah_category_id` \n\r";
    $query .= "WHERE `events`.`recurringParent` = 0                                              \n\r";

    //get data from database
    $database = JFactory::getDBO();
    $database->setQuery($query);
    $events = $database->loadObjectList();

    //creates dom object
    $doc = new DOMDocument('1.0', 'UTF-8');
    $results = $doc->createElement('results');
    $events  = $doc->createElement('events');


    //loops onto data creating xml
    foreach($events as $event){
        echo $event->eventid."<br/>";
        echo $event->title."<br/>";
    }

    $results->appendChild($events);
    $doc->appendChild($results);


    echo $doc->saveXML();

    ?>