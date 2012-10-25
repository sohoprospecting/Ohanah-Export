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
    $events   = $database->loadObjectList();

    //creates dom object
    $doc            = new DOMDocument('1.0', 'UTF-8');
    $results        = $doc->createElement('results');
    $eventsElement  = $doc->createElement('events');

    //loops onto data creating xml
    foreach($events as $event){
        $eventElement = $doc->createElement('event');

        $eventElement->appendChild($doc->createElement('eventid',     $event->eventid));
        $eventElement->appendChild($doc->createElement('eventtypeid', $event->eventtypeid));
        $eventElement->appendChild($doc->createElement('eventtype',   $event->eventtype));
        $eventElement->appendChild($doc->createElement('title',       $event->title));

        //Handling Description - CDATA
        $description = $doc->createElement('description');
        $description->appendChild($doc->createCDATASection($event->description));
        $eventElement->appendChild($description);

        $eventElement->appendChild($doc->createElement('startdate',   $event->startdate));
        $eventElement->appendChild($doc->createElement('enddate',     $event->enddate));
        $eventElement->appendChild($doc->createElement('recurrence',  $event->recurrence));
        $eventElement->appendChild($doc->createElement('time',        $event->time));
        $eventElement->appendChild($doc->createElement('location',    $event->location));
        $eventElement->appendChild($doc->createElement('phone',       $event->phone));
        $eventElement->appendChild($doc->createElement('admission',   $event->admission));
        $eventElement->appendChild($doc->createElement('website',     $event->website));
        $eventElement->appendChild($doc->createElement('imagefile',   $event->imagefile));
        $eventElement->appendChild($doc->createElement('address',     $event->address));
        $eventElement->appendChild($doc->createElement('city',        $event->city));
        $eventElement->appendChild($doc->createElement('state',       $event->state));
        $eventElement->appendChild($doc->createElement('zip',         $event->zip));
        $eventElement->appendChild($doc->createElement('latitude',    $event->latitude));
        $eventElement->appendChild($doc->createElement('longitude',   $event->longitude));
        $eventElement->appendChild($doc->createElement('featured',    $event->featured));
        $eventElement->appendChild($doc->createElement('listingid',   $event->listingid));
        $eventElement->appendChild($doc->createElement('created',     $event->created));
        $eventElement->appendChild($doc->createElement('lastupdated', $event->lastupdated));
        $eventElement->appendChild($doc->createElement('categoryid',  $event->categoryid));
        $eventElement->appendChild($doc->createElement('categoryname',$event->categoryname));

        $eventsElement->appendChild($eventElement);
    }

    $results->appendChild($eventsElement);
    $doc->appendChild($results);

    $doc->formatOutput      = true;
    $oc->preserveWhiteSpace = false;
    echo $doc->saveXML();

    ?>