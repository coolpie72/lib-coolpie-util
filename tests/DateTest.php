<?php
namespace Coolpie\Util\Test;

use Coolpie\Util\Date\CDate;
use PHPUnit\Framework\TestCase;

class DateTest extends TestCase {

    public function test_create_literal() {
        $stamp = "2026-11-22 11:22:33";
        $dt = CDate::create()->fromLiteral($stamp);

        $this->assertEquals($dt->toLiteral(), $stamp);
    }

    public function test_create_local() {
        $stamp = "2026-11-22 07:22:33";
        $dt = CDate::create(CDate::TZ_WIB)->fromLiteral($stamp);

        $this->assertEquals($stamp, $dt->toLiteral());
        $this->assertEquals(CDate::TZ_WIB, $dt->getLocalTimezone());
        $this->assertEquals("2026-11-22T07:22:33+07:00", $dt->getLiteralTimeW3C());

        //change timezone to utc, mundur 7 jam
        $dt->toUtc();
        $this->assertEquals("2026-11-22 00:22:33", $dt->toLiteral()); //benar tapi dalam UTC
        $this->assertEquals(CDate::TZ_UTC, $dt->getLocalTimezone());
        $this->assertEquals("2026-11-22T00:22:33+00:00", $dt->getLiteralTimeW3C());

        //ubah lagi ke wib
        $dt->toTimezone(CDate::TZ_WIB);
        $this->assertEquals(CDate::TZ_WIB, $dt->getLocalTimezone());
        $this->assertEquals("2026-11-22T07:22:33+07:00", $dt->getLiteralTimeW3C());

    }

    public function test_add_hour() {
        $stamp = "2026-11-22 21:22:33";    
        $dt = CDate::create()->fromLiteral($stamp);
        $dt->addHour(7);
        $this->assertEquals("2026-11-23 04:22:33", $dt->toLiteral());
    }

    public function test_copy() {
        $stamp = "2026-11-22 21:22:33"; 
        $dt = CDate::create()->fromLiteral($stamp);
        
        $dt2 = $dt->copy();
        $dt2->addHour(7);

        //cek bahwa instance tidak sama
        $this->assertTrue($dt2 !== $dt);

        //cek bahwa dt tidak berubah
        $this->assertEquals($stamp, $dt->toLiteral());

        //cek bahwa dt2 berubah
        $this->assertEquals("2026-11-23 04:22:33", $dt2->toLiteral());

        //kembalikan dt2
        $dt2->addHour(-7);
        //cek match dengan dt1
        $this->assertEquals($dt->toLiteral(), $dt2->toLiteral());
    }

    public function test_get_component_date() {
        $stamp = "2026-01-05 21:22:33";    
        $dt = CDate::create()->fromLiteral($stamp);
        $expected = "2026-1-5";

        $actual = "{$dt->getYear()}-{$dt->getMonth()}-{$dt->getDay()}";
        $this->assertEquals($expected, $actual);
    }

    public function test_diff() {
        $d1 = CDate::create()->fromLiteral("2026-01-05 21:21:45");
        $d2 = CDate::create()->fromLiteral("2026-01-05 21:22:15");
        $expected = 30;
        $actual = $d2->diff($d1);

        $this->assertEquals($expected, $actual);

        $expected = -30;
        $actual = $d1->diff($d2);
        $this->assertEquals($expected, $actual);

        $d1 = CDate::create()->fromLiteral("2026-01-05 21:21:45");
        $d2 = $d1->copy();
        $expected = 0;
        $actual = $d2->diff($d1);
        $this->assertEquals($expected, $actual);

        $d1 = CDate::create()->fromLiteral("2026-01-05 21:21:45");
        $d2 = $d1->copy();
        $d2->addHour(5)->addMinute(12)->addSecond(30);

        $expected = 5*3600 + 12*60 + 30;
        $actual = $d2->diff($d1);
        $this->assertEquals($expected, $actual);

    }
}