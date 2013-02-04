<?php
    $url = "http://maps.google.com/maps/api/geocode/xml?latitude=34.2162368&longitude=-119.0379904&sensor=false";
    $xml = simplexml_load_file($url) or die("connection error");

    echo $url."<br/>";

    print_r($xml);

?>