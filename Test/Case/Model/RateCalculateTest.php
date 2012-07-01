<?php
App::uses('RateCalculate', 'Givrate.Model');

/**
 * RateCalculate Test Case
 *
 */
class RateCalculateTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('plugin.givrate.rate_calculate');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->RateCalculate = ClassRegistry::init('RateCalculate');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->RateCalculate);

		parent::tearDown();
	}

}
