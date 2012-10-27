SELECT  `events`.`ohanah_event_id` AS `eventid`,
        ''                         AS `eventtypeid`,
        ''                         AS `eventtype`,
        `events`.`title`           AS `title`,
        --`events`.`description`     AS `description`,
        DATE_FORMAT(`events`.`date`,'%m/%d/%Y')     AS `startdate`,
        DATE_FORMAT(`events`.`end_date`,'%m/%d/%Y') AS `enddate`,
        CASE `events`.`isRecurring` WHEN '1' THEN
            CONCAT('Recurring every ',
                   `events`.`everyNumber`,' ',
                   `events`.`everyWhat`,' until ',
                   DATE_FORMAT(`events`.`endOnDate`,'%m/%d/%Y'), '.')
        ELSE
            ''
        END AS `recurrence`,
        ''                   AS `time`,
        `events`.`venue`     AS `location`,
        ''                   AS `phone`,
        ''                   AS `admission`,
        ''                   AS `website`,
        ''                   AS `imagefile`,
        `events`.`address`   AS `address`,
        ''                   AS `city`,
        ''                   AS `state`,
        ''                   AS `zip`,
        `events`.`latitude`  AS `latitude`,
        `events`.`longitude` AS `longitude`,
        CASE `events`.`featured` WHEN '1' THEN 'Yes' ELSE 'No' END AS `featured`,
        ''                   AS `listingid`,
        DATE_FORMAT(`events`.`created_on`,'%m/%d/%Y %r') AS `created`,
        DATE_FORMAT(`events`.`created_on`,'%m/%d/%Y %r') AS `lastupdated`,
        `cat`.`ohanah_category_id` AS `categoryid`,
        `cat`.`title`              AS `categoryname`
  FROM  `jos_ohanah_events`        AS `events` INNER JOIN `jos_ohanah_categories` AS `cat` ON `cat`.`ohanah_category_id` = `events`.`ohanah_category_id`
 WHERE `events`.`recurringParent` = 0


--get childs
SELECT `events`.`eventid`, DATE_FORMAT(`events`.`date`,'%m/%d/%Y') AS `startdate`
  FROM `jos_ohanah_events` AS `events`
 WHERE `events`.`recurringParent` = 1
    OR `events`.`eventid`              = 1


SELECT
  `events`.`ohanah_event_id`,
  `events`.`ohanah_category_id`,
  `events`.`title`,
  `events`.`slug`,
  `events`.`header`,
  `events`.`date`,
  `events`.`end_date`,
  `events`.`start_time`,
  `events`.`end_time`,
  `events`.`created_by`,
  `events`.`created_on`,
  `events`.`enabled`,
  `events`.`featured`,
  `events`.`address`,
  `events`.`latitude`,
  `events`.`longitude`,
  `events`.`timezone`,
  `events`.`geolocated_city`,
  `events`.`geolocated_country`,
  `events`.`geolocated_state`,
  `events`.`venue`,
  `events`.`ohanah_venue_id`,
  `events`.`limit_number_of_attendees`,
  `events`.`attendees_limit`,
  `events`.`ticket_cost`,
  `events`.`payment_currency`,
  `events`.`frontendsubmission`,
  `events`.`created_by_name`,
  `events`.`created_by_email`,
  `events`.`mailchimp_list_id`,
  `events`.`isRecurring`,
  `events`.`everyNumber`,
  `events`.`everyWhat`,
  `events`.`endOnDate`,
  `events`.`endAfterNumber`,
  `events`.`endAfterWhat`,
  `events`.`recurringParent`,
  `events`.`picture`,
  `events`.`frontend_submitted`,
  `events`.`end_time_enabled`,
  `events`.`who_can_register`,
  `events`.`close_registration_day`,
  `events`.`custom_payment_url`,
  `events`.`custom_registration_url`,
  `events`.`payment_gateway`,
  `events`.`registration_system`,
  `events`.`allow_only_one_ticket`
  FROM `jos_ohanah_events` AS `events`
  WHERE `events`.`recurringParent` = 0