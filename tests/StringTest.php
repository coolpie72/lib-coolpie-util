<?php
namespace Coolpie\Util\Test;

use Coolpie\Util\Str\StringUtil;
use PHPUnit\Framework\TestCase;

class StringTest extends TestCase {

    public function testChop1() {
		$res = StringUtil::chopEnd("/var/www/html/foo/", 1);
		$this->assertEquals($res, "/var/www/html/foo");
	}

	public function testChop2() {
		$res = StringUtil::chopEnd("foo", 3);
		$this->assertEquals($res, "");
	}

	public function testChop3() {
		$res = StringUtil::chopEnd("foobar", 10);
		$this->assertEquals($res, "");
	}
	
	public function testStartsWith_Normal(): void {
		$res = StringUtil::startsWith("foo bar", "foo");
		$this->assertTrue($res);
	}
	
	public function testStartsWith_NormalFalse(): void {
		$res = StringUtil::startsWith("foo bar", "bar");
		$this->assertFalse($res);
	}

    public function testStartsWith_Equal(): void {
    	$res = StringUtil::startsWith("foo bar", "foo bar");
    	$this->assertTrue($res);
    }
    
    public function testStartsWith_EmptyString(): void {
    	$res = StringUtil::startsWith("foo bar", "");
    	$this->assertTrue($res);
    }
    
    public function testStartsWith_Middle1(): void {
    	$res = StringUtil::startsWith("foo bar", " ba");
    	$this->assertFalse($res);
    }

}