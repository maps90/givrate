<?php
App::uses('GivrateAppModel', 'Givrate.Model');
/**
 * Rating Model
 *
 * @property User $User
 */
class Rating extends GivrateAppModel {

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

		'owner_id' => array(
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
			'className' => 'Users.User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function checking($token, $userId, $type, $status, $ownerId, $options = array()) {
		if (isset($options['recursive'])) {
			$this->recursive = $options['recursive'];
		}
		$Token = ClassRegistry::init('Givrate.Token');
		$tokenData = $Token->findByToken($token);
		$result = $this->find('first', array(
			'conditions' => array(
				'Rating.model' => $tokenData['Token']['model'],
				'Rating.foreign_key' => $tokenData['Token']['foreign_key'],
				'Rating.user_id' => $userId,
				'Rating.owner_id' => $ownerId,
				'Rating.type' => $type,
				'Rating.status' => $status,
				)
			));
		return $result;
	}

	protected function _calculateRating($data) {
		$RateCalculate = ClassRegistry::init('Givrate.RateCalculate');
		if (!empty($data)) {
			$alias = $data['Rating']['model'];
			$foreignKey = $data['Rating']['foreign_key'];
			$value = $data['Rating']['value'];
			$type = $data['Rating']['type'];
			$status = $data['Rating']['status'];
			$RateCalculate->calculating($alias, $foreignKey, $value, $type, $status);
		}
	}

	public function rate($token, $type, $rating, $userId, $status, $ownerId, $userPoint = null) {
		$rated = $this->checking($token, $userId, $type, $status, $ownerId, array('recursive' => -1));
		if ($rated) {
			return false;
		}
		$Token = ClassRegistry::init('Givrate.Token');
		$tokenData = $Token->findByToken($token);
		$data = array(
			'user_id' => $userId,
			'owner_id' => $ownerId,
			'model' => $tokenData['Token']['model'],
			'foreign_key' => $tokenData['Token']['foreign_key'],
			'value' => $rating,
			'type' => $type,
			'status' => $status,
		);
		if ($this->save($data)) {
			if ($userPoint != null) {
				$UserPoint = ClassRegistry::init('Givrate.UserPoint');
				$UserPoint->countMyPoint($ownerId, $rating, $type, $status);
			}
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
