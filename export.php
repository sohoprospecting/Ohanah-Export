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

    function getGeoData($address){
        $xml = simplexml_load_file("http://maps.google.com/maps/api/geocode/xml?address=".$address.",United States&sensor=false") or die("connection error");

        // print_r($xml);

        if((is_object($xml)) && ($xml->status == 'OK')){
            $geodata = array("latitude"  => $xml->result->geometry->location->lat,
                             "longitude" => $xml->result->geometry->location->lng,
                             "address"   => $xml->result->address_component[0]->long_name.", ".$xml->result->address_component[1]->long_name,
                             "city"      => isset($xml->result->address_component[3]->long_name) ? $xml->result->address_component[3]->long_name : '',
                             "state"     => isset($xml->result->address_component[5]->long_name) ? $xml->result->address_component[5]->long_name : '',
                             "zipcode"   => isset($xml->result->address_component[7]->long_name) ? $xml->result->address_component[7]->long_name : '');
        }else{
            $geodata = array("latitude"  => 0,
                             "longitude" => 0,
                             "address"   => '',
                             "city"      => '',
                             "state"     => '',
                             "zipcode"   => '');
        }

        return $geodata;
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
    $query .= "        `events`.`geolocated_city`   AS `city`,                                   \n\r";
    $query .= "        `events`.`geolocated_state`  AS `state`,                                  \n\r";
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

        //handling address
        $geodata = getGeoData($event->address);
        $eventElement->appendChild($doc->createElement('address',     $geodata['address']));
        $eventElement->appendChild($doc->createElement('city',        $geodata['city']));
        $eventElement->appendChild($doc->createElement('state',       $geodata['state']));
        $eventElement->appendChild($doc->createElement('zip',         $geodata['zipcode']));
        $eventElement->appendChild($doc->createElement('latitude',    $geodata['latitude']));
        $eventElement->appendChild($doc->createElement('longitude',   $geodata['longitude']));

        $eventElement->appendChild($doc->createElement('featured',    $event->featured));
        $eventElement->appendChild($doc->createElement('listingid',   $event->listingid));
        $eventElement->appendChild($doc->createElement('created',     $event->created));
        $eventElement->appendChild($doc->createElement('lastupdated', $event->lastupdated));

        //handling categories
        $eventcategories = $doc->createElement('eventcategories');
        $eventcategory   = $doc->createElement('eventcategory');
        $eventcategory->appendChild($doc->createElement('categoryid',  $event->categoryid));
        $eventcategory->appendChild($doc->createElement('categoryname',$event->categoryname));
        $eventcategories->appendChild($eventcategory);
        $eventElement->appendChild($eventcategories);

        $eventsElement->appendChild($eventElement);
    }

    $results->appendChild($eventsElement);
    $doc->appendChild($results);

    $doc->formatOutput      = true;
    $oc->preserveWhiteSpace = false;
    echo $doc->saveXML();

    ?>