<?php
App::uses('GivrateAppModel', 'Givrate.Model');
/**
 * Rating Model
 *
 * @property User $User
 */
class Rating extends GivrateAppModel {
/**
 * Use database config
 *
 * @var string
 */
	public $useDbConfig = 'ratings';
/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(

		'user_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),

		'model' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),

		'foreign_key' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),

		'value' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function isRated($modelName, $foreignKey, $user, $options = array()) {
		if (isset($options['recursive']) && ($options['recursive'] == true)) {
			$this->recursive = -1;
		}
		$result = $this->find('first', array(
			'conditions' => array(
				'Rating.model' => $modelName,
				'Rating.foreign_key' => $foreignKey,
				'Rating.user_id' => $user
				)
			));
		return $result;
	}

	public function _calculateRating($data) {
		$RateCalculate = ClassRegistry::init('Givrate.RateCalculate');
		if (!empty($data)) {
			$alias = $data['Rating']['model'];
			$foreignKey = $data['Rating']['foreign_key'];
			$value = $data['Rating']['value'];
			$RateCalculate->calculating($alias, $foreignKey, $value);
		}
	}

	public function rate($rateId, $rating, $userId, $alias) {
		$rated = $this->isRated($alias, $rateId, $userId, array('recursive' => true));
		if ($rated) {
			return false;
		}
		$data = array(
			'user_id' => $userId,
			'model' => $alias,
			'foreign_key' => $rateId,
			'value' => $rating
			);
		if ($this->save($data)) {
			return true;
		} else {
			return false;
		}
	}

	public function afterSave($created) {
		if ($created) {
			$this->_calculateRating($this->data);
		}
	}
}
