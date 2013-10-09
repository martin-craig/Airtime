<?php

class Application_Common_DateHelper
{
    private $_dateTime;

    function __construct()
    {
        $this->_dateTime = date("U");
    }

    /**
     * Get time of object construction in the format
     * YYYY-MM-DD HH:mm:ss
     */
    function getTimestamp()
    {
        return date("Y-m-d H:i:s", $this->_dateTime);
    }

    /**
     * Get time of object construction in the format
     * YYYY-MM-DD HH:mm:ss
     */
    function getUtcTimestamp()
    {
        return gmdate("Y-m-d H:i:s", $this->_dateTime);
    }

    /**
     * Get date of object construction in the format
     * YYYY-MM-DD
     */
    function getDate()
    {
        return gmdate("Y-m-d", $this->_dateTime);
    }

    /**
     * Get time of object construction in the format
     * HH:mm:ss
     */
    function getTime()
    {
        return gmdate("H:i:s", $this->_dateTime);
    }
    
    /**
     * Get the week start date of this week in the format
     * YYYY-MM-DD
     *
     * @return String - week start date
     */
    function getWeekStartDate()
    {
        // our week starts on monday, but php week starts on sunday.
        $startDate = date('w') == 0 ? date('Y-m-d', strtotime('monday last week')) : date('Y-m-d', strtotime('monday this week'));
        $startDateTime = new DateTime($startDate);
        return $startDateTime->format('Y-m-d H:i:s');
    }

    /**
     * Set the internal timestamp of the object.
     */
    function setDate($dateString)
    {
        $dateTime = new DateTime($dateString, new DateTimeZone("UTC"));
        $this->_dateTime = $dateTime->getTimestamp();
    }

    /**
     * Calculate and return the timestamp for end of day today
     * in local time.
     *
     * For example, if local time is 2PM on 2011-11-01,
     * then the function would return 2011-11-02 00:00:00
     *
     * @return  End of day timestamp in local timezone
     */
    public static function GetDayEndTimestamp($time = "") {
        $dateTime = $time == "" ? new DateTime(date("Y-m-d")) : new DateTime($time);
        $dateTime->add(new DateInterval('P1D'));
        return $dateTime->format('Y-m-d H:i:s');
    }
    
    public static function GetDayEndTimestampInUtc($time = "") {
        $dayEndTimestamp = Application_Common_DateHelper::GetDayEndTimestamp($time);
        return Application_Common_DateHelper::ConvertToUtcDateTimeString($dayEndTimestamp);
    }

    /**
     * Find the epoch timestamp difference from "now" to the beginning of today.
     */
    function getNowDayStartDiff()
    {
        $dayStartTs = ((int)($this->_dateTime/86400))*86400;
        return $this->_dateTime - $dayStartTs;
    }

    /**
     * Find the epoch timestamp difference from "now" to the end of today.
     */
    function getNowDayEndDiff()
    {
        $dayEndTs = ((int)(($this->_dateTime+86400)/86400))*86400;
        return $dayEndTs - $this->_dateTime;
    }

    function getEpochTime()
    {
        return $this->_dateTime;
    }

    /**
     * Returns the offset in seconds, between local and UTC timezones.
     * E.g., if local timezone is -4, this function
     * returns -14400.
     *
     * @return type     offset in int, between local and UTC timezones
     */
    function getLocalTimeZoneOffset() {
        $dateTime = new DateTime("@".$this->_dateTime, new DateTimeZone("UTC"));
        $timezone = new DateTimeZone(date_default_timezone_get());
        return $timezone->getOffset($dateTime);
    }

    /**
     * Returns the offset hour in int, between local and UTC timezones.
     * E.g., if local timezone is -4:30, this function
     * returns -4.
     *
     * @return type     offset hour in int, between local and UTC timezones
     */
    function getLocalOffsetHour() {
        $offset = $this->getLocalTimeZoneOffset();
        return (int)($offset / 3600);
    }

    /**
     * Returns the offset minute in int, between local and UTC timezones.
     * E.g., if local timezone is -4:30, this function
     * returns -30.
     *
     * @return type     offset minute in int, between local and UTC timezones
     */
    function getLocalOffsetMinute() {
        $offset = $this->getLocalTimeZoneOffset();
        return (int)(($offset % 3600) / 60);
    }
    
    public static function TimeAdd($time1, $time2)
    {
        return strtotime($time2) + strtotime($time1);
    }

    /**
     * This function formats a time by removing seconds
     *
     * When we receive a time from the database we get the
     * format "hh:mm:ss". But when dealing with show times, we
     * do not care about the seconds.
     *
     * @param int $p_dateTime
     *      The value which to format.
     * @return int
     *      The timestamp with the new format "hh:mm", or
     *      the original input parameter, if it does not have
     *      the correct format.
     */
    public static function removeSecondsFromTime($p_dateTime)
    {
        //Format is in hh:mm:ss. We want hh:mm
        $timeExplode = explode(":", $p_dateTime);

        if (count($timeExplode) == 3)
            return $timeExplode[0].":".$timeExplode[1];
        else
            return $p_dateTime;
    }

    public static function getDateFromTimestamp($p_dateTime){
        $explode = explode(" ", $p_dateTime);
        return $explode[0];
    }

    public static function getTimeFromTimestamp($p_dateTime){
        $explode = explode(" ", $p_dateTime);
        return $explode[1];
    }

    public static function ConvertToUtcDateTime($p_dateString, $timezone=null){
        if (isset($timezone)) {
            $dateTime = new DateTime($p_dateString, new DateTimeZone($timezone));
        }
        else {
            $dateTime = new DateTime($p_dateString, new DateTimeZone(date_default_timezone_get()));
        }
        $dateTime->setTimezone(new DateTimeZone("UTC"));

        return $dateTime;
    }

    public static function ConvertToSpecificTimezoneDateTime($p_dateString, $timezone){
        $dateTime = new DateTime($p_dateString, new DateTimeZone("UTC"));
        $dateTime->setTimezone(new DateTimeZone($timezone));

        return $dateTime;
    }

    public static function ConvertToLocalDateTime($p_dateString){
        $dateTime = new DateTime($p_dateString, new DateTimeZone("UTC"));
        $dateTime->setTimezone(new DateTimeZone(Application_Model_Preference::GetTimezone()));

        return $dateTime;
    }
    
    /* Convenience method to return a date formatted into a String rather than a
     * DateTime object. Note that if an empty string is provided for $p_dateString
     * then the current time is provided.
     *
     * @param $p_dateString
     *      Date string in UTC timezone.
     * @param $p_format
     *      Format which the string should be returned in.
     *
     * @return string
     *      Date String in localtime
     * */
    public static function ConvertToLocalDateTimeString($p_dateString, $p_format="Y-m-d H:i:s"){
        if (is_null($p_dateString) || strlen($p_dateString) == 0)
            return $p_dateString;
        return self::ConvertToLocalDateTime($p_dateString)->format($p_format);
    }
    
    public static function ConvertToUtcDateTimeString($p_dateString, $p_format="Y-m-d H:i:s"){
        if (is_null($p_dateString) || strlen($p_dateString) == 0)
            return $p_dateString;
        return self::ConvertToUtcDateTime($p_dateString)->format($p_format);
    }
    
    /*
     * Example input: "00:02:32.746562". Output is a DateInterval object
     * representing that 2 minute, 32.746562 second interval.
     *
     */
    public static function getDateIntervalFromString($p_interval){
        list($hour_min_sec, $subsec) = explode(".", $p_interval);
        list($hour, $min, $sec) = explode(":", $hour_min_sec);
        
        return new DateInterval("PT{$hour}H{$min}M{$sec}S");
    }
    
    /**
     * returns true or false depending on input is wether in
     * valid range of SQL date/time
     * @param string $p_datetime
     *     should be in format of '0000-00-00 00:00:00'
     */
    public static function checkDateTimeRangeForSQL($p_datetime){
        $info = explode(' ', $p_datetime);
        $dateInfo = explode('-', $info[0]);
        if (isset($info[1])) {
            $timeInfo = explode(':', $info[1]);
        }
        $retVal = array();
        $retVal["success"] = true;
        
        $year = $dateInfo[0];
        $month = $dateInfo[1];
        $day = $dateInfo[2];
        // if year is < 1753 or > 9999 it's out of range
        if ($year < 1753) {
            $retVal['success'] = false;
            $retVal['errMsg'] = sprintf(_("The year %s must be within the range of 1753 - 9999"), $year);
        } else if (!checkdate($month, $day, $year)) {
            $retVal['success'] = false;
            $retVal['errMsg'] = sprintf(_("%s-%s-%s is not a valid date"), $year, $month, $day);
        } else {
            // check time
            if (isset($timeInfo)) {
                if (isset($timeInfo[0]) && $timeInfo[0] != "") {
                    $hour = intval($timeInfo[0]);
                } else {
                    $hour = -1;
                }
                
                if (isset($timeInfo[1]) && $timeInfo[1] != "") {
                    $min = intval($timeInfo[1]);
                } else {
                    $min = -1;
                }
                
                if (isset($timeInfo[2]) && $timeInfo[2] != "") {
                    $sec = intval($timeInfo[2]);
                } else {
                    $sec = -1;
                }
                
                if ( ($hour < 0 || $hour > 23) || ($min < 0 || $min > 59) || ($sec < 0 || $sec > 59) ) {
                    $retVal['success'] = false;
                    $retVal['errMsg'] = sprintf(_("%s:%s:%s is not a valid time"), $timeInfo[0], $timeInfo[1] ,$timeInfo[2]);
                }
            }
        }
        return $retVal;
    }
    
    /**
     * This function is used for calculations! Don't modify for display purposes!
     *
     * Convert playlist time value to a string value in seconds using precision math
     *
     * @param string $plt
     *         time in format HH:mm:ss.u
     * @return string
     *         time in format U.u
     */
    public static function playlistTimeToSeconds($plt)
    {
        $arr =  preg_split('/:/', $plt);
        $count = count($arr);
        
        //hours exist
        if ($count == 3) {
        	
        	$hours = intval($arr[0]) * 3600;
        	$minutes = intval($arr[1]) * 60;
        	$seconds = $arr[2];
        	
        	$res = $hours + $minutes;
        	$res = bcadd($res, $seconds, 6);	
        }
        
        //minutes exist.
        else if ($count == 2) {
        	
        	$minutes = intval($arr[0]) * 60;
        	$seconds = $arr[1];

        	$res = bcadd($minutes, $seconds, 6);
        }
        
        //seconds exist
        else {
        	$res = $arr[0];
        }
    
        return $res;
    }
    
    
    /**
     *  This function is used for calculations! Don't modify for display purposes!
     *
     * Convert seconds value to playlist time format
     *
     * @param  string  $seconds
     * 			time in format U.u
     * @return string
     *          time in format HH:mm:ss.u
     */
    public static function secondsToPlaylistTime($p_seconds)
    {
        $info = explode('.', $p_seconds);
        $seconds = $info[0];
        if (!isset($info[1])) {
            $milliStr = 0;
        } else {
            $milliStr = $info[1];
        }
        $hours = floor($seconds / 3600);
        $seconds -= $hours * 3600;
        $minutes = floor($seconds / 60);
        $seconds -= $minutes * 60;
    
        $res = sprintf("%02d:%02d:%02d.%s", $hours, $minutes, $seconds, $milliStr);
    
        return $res;
    }
    
    /**
     *
     * Finds the cliplength of a media item given a cuein and a cueout
     * (Uses precision math)
     *
     * @param  string  $cuein
     * 			time in format HH:mm:ss.u
     * 
     * @param  string  $cueout
     * 			time in format HH:mm:ss.u
     * 
     * @return string
     *          time in format HH:mm:ss.u
     */
    public static function findClipLength($cuein, $cueout)
    {
    	$cueinSec = self::playlistTimeToSeconds($cuein);
    	$cueoutSec = self::playlistTimeToSeconds($cueout);
    	
    	$durationSec = bcsub($cueoutSec, $cueinSec, 6);
    	
    	return self::secondsToPlaylistTime($durationSec);
    }
}

