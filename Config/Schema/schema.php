<?php 
class GivrateSchema extends CakeSchema {

	public function before($event = array()) {
		return true;
	}

	public function after($event = array()) {
	}

	var $ratings = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11),
		'owner_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11),
		'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11),
		'value' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20),
		'status' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'params' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'ix_ratings' => array(
				'column' => array('user_id', 'owner_id', 'foreign_key'),
				'unique' => false
			)
		)
	);

	var $rate_calculates = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11),
		'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11),
		'count' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'point' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'avg' => array('type' => 'float', 'null' => true, 'default' => '0', 'length' => '8,4'),
		'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20),
		'status' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'ix_rate_calculates_fk' => array(
				'column' => array('user_id', 'foreign_key'),
				'unique' => false
			)
		)
	);

	var $tokens = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
		'model' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11),
		'token' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'un_tokens' => array('column' => 'token', 'unique' => 1),
			'un_tokens_foreign' => array('column' => array('foreign_key', 'model'), 'unique' => 1),
		)
	);

	var $user_points = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11),
		'raters' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'points' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'avg' => array('type' => 'float', 'null' => true, 'default' => '0', 'length' => '8,4'),
		'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 20),
		'status' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'point_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		)
	);

	var $user_votes = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULl, 'length' => 11),
		'foreign_key' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 11),
		'count' => array('type' => 'integer', 'null' => false, 'default' => 0, 'length' => 10),
		'vote_date' => array('type' => 'date', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
		)
	);
}
