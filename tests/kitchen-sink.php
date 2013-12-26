<?php
define('BASE_PATH', realpath(__DIR__.'/..'));
require_once 'lib/Valid8r/Valid8r.php';

class Valid8rStringsTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Valid8r\Valid8r
	 */
	protected $validator;
	
	protected function setUp() {
		$this->validator = new Valid8r\Valid8r();
		$this->validator->setRulesFromFile(BASE_PATH.'/examples/kitchen-sink/kitchen-sink.json');
	}
	
	public function testLen() {
		$err = $this->validator->validate('min_len','aaaa');
		$this->assertNotEmpty($err);
		
		$err = $this->validator->validate('max_len','aaaaaaaaaaaaaaaaaaaaa');
		$this->assertNotEmpty($err);
		
		$err = $this->validator->validate('min_len','aaaaa');
		$this->assertEmpty($err);
	}
	
	public function testAlpha() {
		$err = $this->validator->validate('alpha', '.');
		$this->assertNotEmpty($err);
		
		$err = $this->validator->validate('alpha', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
		$this->assertEmpty($err);
	}
	
	public function testNumeric() {
		$err = $this->validator->validate('num', 'a');
		$this->assertNotEmpty($err);
		
		$err = $this->validator->validate('num_pos', '-77');
		$this->assertNotEmpty($err);
		
		$err = $this->validator->validate('num', '-77');
		$this->assertEmpty($err);
	}
	
	public function testFormatted() {
		$err = $this->validator->validate('formatted_as', 'a');
		$this->assertNotEmpty($err);
		
		$err = $this->validator->validate('formatted_as', '88/88/8888');
		$this->assertEmpty($err);
	}
	
	public function testFormatted2() {
		$err = $this->validator->validate('formatted_as2', 'a');
		$this->assertNotEmpty($err);
		
		$err = $this->validator->validate('formatted_as2', '(888) 888-8888');
		$this->assertEmpty($err);
	}
	
	public function testFormatted3() {
		$err = $this->validator->validate('formatted_as3', 'A');
		$this->assertNotEmpty($err);
		
		$err = $this->validator->validate('formatted_as3', 'AA88AA888888');
		$this->assertEmpty($err);
	}
	
	public function testRegex() {
		$err = $this->validator->validate('regex', 'a');
		$this->assertNotEmpty($err);
		$err = $this->validator->validate('regex', 'A0');
		$this->assertEmpty($err);
	}
	
	public function testIP() {
		$this->assertNotEmpty($this->validator->validate('ip','256.256.256.256'));
		$this->assertNotEmpty($this->validator->validate('ip','xe80::219:7eff:fe46:6c42'));
		$this->assertEmpty($this->validator->validate('ip','10.10.10.10'));
		$this->assertEmpty($this->validator->validate('ip','fe80::219:7eff:fe46:6c42'));
		
	}
	
}
