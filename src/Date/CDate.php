<?php
namespace Coolpie\Util\Date;

use DateTime;
use DateTimeZone;
use DateInterval;
use Exception;

class CDate {

    private const FMT_STD_LITERAL = "Y-m-d H:i:s";
    private const FMT_STD_LITERAL_DATE_ONLY = "Y-m-d";
    
    public const TZ_UTC = "+00:00";
    public const TZ_WIB = "+07:00";

    private $stamp;
    private $date;

    private $localTimeZone;

    private function __construct($localTimezone = null) {
        if ($localTimezone === null) {
            $this->localTimeZone = self::TZ_UTC;
        } else {
            $this->localTimeZone = $localTimezone;
        }
    }
    
    //constructor method
    //$dt = CDate::create()
    public static function create($localTimeZone = null) {
        $dt = new CDate($localTimeZone);
        return $dt;
    }

    public function getLocalTimezone() {
        return $this->localTimeZone;
    }
    
    //modifier method
    //parse date string to date
    //$dt = CDate::create()->fromParse(fmt, date string)
    public function fromParse($fmt, $stampStr) {
        $res = DateTime::createFromFormat($fmt, $stampStr, new DateTimeZone($this->localTimeZone));
        //handle jika gagal parse
        if ($res === false) {
            return null;
        }
        $this->date = $res;
        return $this;
        // $this->setTimezone(self::$TZ_UTC);
    }

    //modifier method
    //$dt = CDate::create()->fromNowWib()
    //khusus utk tz WIB
    public function fromNowLocal() {
        return $this->fromNow($this->localTimeZone);
    }
    
    public function fromStamp($stamp, $tz) {
        $this->fromNow($tz);
//         $this->setTimezone($tz);
        $this->date->setTimestamp($stamp);
        return $this;
    }

    //modifier method
    //$dt = CDate::create()->fromNow()
    //assume default tz = UTC
    public function fromNow($tzString = null) {
        // $str = date(self::$FMT_STD_LITERAL);
        // echo "date-str: $str\n";
        // $this->fromLiteral($str);
        // $this->date->setTimezone(self::$TZ_UTC);
        //$this->date = new DateTime("now");
        $tzParam = $tzString == null ? self::TZ_UTC : $tzString;

        $this->date = new DateTime("now", new DateTimeZone($tzParam));
        // $this->date->setTimestamp(time());
        return $this;
    }

    //modifier method
    //ubah timezone (misal dari utc ke wib)
    //dapat gunakan konstanta TZ_UTC atau TZ_WIB
    public function setTimezone(string $tzString) {
        $this->date->setTimezone(new DateTimeZone($tzString));
        return $this;
    }

    //modifier method
    //shortcut utk from parse tp utk std stamp format
    public function fromLiteral(string $stampStr) {
        $this->fromParse(self::FMT_STD_LITERAL, $stampStr);
        return $this;
    }

    //stampStrDateOnly: string date: yyyy-mm-dd
    public function fromLiteralDateOnly(string $stampStrDateOnly) {
        //assume time part 00:00:00
        $this->fromParse(self::FMT_STD_LITERAL, "{$stampStrDateOnly} 00:00:00");
        return $this;
    }    

    //modifier method
    //copy this date from other date

    //asumsi timezone nya tidak berubah
    public function fromCopy(CDate $other) {
        $this->fromLiteral($other->toLiteral());
        return $this;
    }

    //kembalikan new date hasil copy dari this (semacam clone)
    public function copy() {
        return self::create($this->localTimeZone)->fromCopy($this);
    }

    //to string method
    //std literal output
    public function toLiteral() {
        return $this->format(self::FMT_STD_LITERAL);
    }

    //to string
    //print date part only yyyy-mm-dd
    public function toLiteralDateOnly() {
        return $this->format(self::FMT_STD_LITERAL_DATE_ONLY);
    }

    //custom to string format
    public function format(string $fmt) {
        return $this->date->format($fmt);
    }

    //add interval string ke date ini
    //spesifikasi interval string: https://www.php.net/manual/en/datetime.formats.relative.php
    //contoh interval:
    //7 day atau 7 days
    //2 hour atau 2 hours
    //1 hour 30 minute 55 second
    //-1 hour -30 minute -55 second
    public function addInterval(string $intervalString) {
        $intv = DateInterval::createFromDateString($intervalString);
        $this->date->add($intv);
        return $this;
    }

    //add hour to this date
    //hour bisa positif atau negatif (maju atau mundur)
    public function addHour(int $hour) {
        $absHour = abs($hour);
        $intv = new DateInterval("PT{$absHour}H");
        if ($hour >= 0) {
            $this->date->add($intv);
        } else {
            $this->date->sub($intv);
        }
        
        return $this;
    }

    //ISO 8601 (PnYnMnDTnHnMnS)
    public function addMinute(int $minute) {
        $absMinute = abs($minute);
        $intv = new DateInterval("PT{$absMinute}M");
        if ($minute >= 0) {
            $this->date->add($intv);
        } else {
            $this->date->sub($intv);
        }
        
        return $this;
    }

    public function addSecond(int $second) {
        $absSecond = abs($second);
        $intv = new DateInterval("PT{$absSecond}S");
        if ($second >= 0) {
            $this->date->add($intv);
        } else {
            $this->date->sub($intv);
        }
        
        return $this;
    }


    public function diff(CDate $other) {
        $res = $this->date->getTimestamp() - $other->date->getTimestamp();
        return $res;
    }

    public function getStamp() {
        return $this->date->getTimestamp();
    }

    //ubah internal date ke UTC
    //struktur date jg akan berubah
    public function toUtc() {
        $this->toTimezone(self::TZ_UTC);
    }

    //ubah internal date ke local
    public function toTimezone(string $localTimezone) {
        $this->localTimeZone = $localTimezone;
        return $this->setTimezone($this->localTimeZone);
    }

    public function addUnit(int $num, string $unit) {
        if ($unit == "hr" || $unit == "hour" || $unit == "h" || $unit == "H") {
            return $this->addHour($num);
        } else if ($unit == "min" || $unit == "m" || $unit == "M") {
            return $this->addMinute($num);
        } else if ($unit == "sec" || $unit == "s" || $unit == "S") {
            return $this->addSecond($num);
        }

        throw new Exception("Invalid date unit: $unit");
    }

    //full year, eg: 2025
    public function getYear() {
        return (int) $this->format("Y");
    }

    //month number: 1..12, without leading zeros
    public function getMonth() {
        return (int) $this->format("n");
    }

    //day of month without leading zeros
    public function getDay() {
        return (int) $this->format("j");
    }

    //format time menurut W3C
    //https://www.w3.org/TR/NOTE-datetime
    //tz: timezone part
    //eg: empty string, "Z" (UTC), or "+hh:mm", or "-hh:mm", eg: "+07:00" (WIB)
    public function getLiteralTimeW3C($tz = null) {
        $usedTz = $this->localTimeZone;
        if ($tz !== null) {
            $usedTz = $tz;
        }
        return $this->format("Y-m-d") .  "T" . $this->format("H:i:s") . $usedTz;
    }

}

