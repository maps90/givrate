<?php
/**
 * RateCalculateFixture
 *
 */
class RateCalculateFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'count' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'sum' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'avg' => array('type' => 'float', 'null' => true, 'default' => '0.0000', 'length' => '8,4'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'un_rate_calculate' => array('column' => array('foreign_key', 'model'), 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'model' => 'Lorem ipsum dolor sit amet',
			'foreign_key' => 1,
			'count' => 1,
			'sum' => 1,
			'avg' => 1,
			'created' => '2012-07-01 15:31:15',
			'modified' => '2012-07-01 15:31:15'
		),
	);
}
