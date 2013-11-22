<?php
App::uses('GivrateAppModel', 'Givrate.Model');
/**
 * RateCalculate Model
 *
 */
class RateCalculate extends GivrateAppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(

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

		'count' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),

		'point' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),

		'avg' => array(
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

	var $findMethods = array(
		'bestRate' => true,
		);

	public function calculating($userId, $alias, $foreignKey, $value, $type, $status) {
		$rated = $this->find('first', array(
			'recursive' => -1,
			'conditions' => array(
				'RateCalculate.user_id' => $userId,
				'RateCalculate.model' => $alias,
				'RateCalculate.foreign_key' => $foreignKey,
				'RateCalculate.type' => $type,
				'RateCalculate.status' => $status,
				)
			));

		App::uses('PointUtil', 'Givrate.Utility');
		$this->Point = new PointUtil;
		if (!empty($rated)) {
			$count = $this->Point->rateCount($rated['RateCalculate']['count']);
			$sum = $this->Point->rateSum($rated['RateCalculate']['point'], $value);
			$avg = $this->Point->rateAvg($sum, $count);
		} else {
			$count = $this->Point->rateCount(0);
			$sum = $this->Point->rateSum(0, $value);
			$avg = $this->Point->rateAvg($sum, $count);
		}

		$data['RateCalculate']['count'] = $count;
		$data['RateCalculate']['point'] = $sum;
		$data['RateCalculate']['avg'] = $avg;
		$data['RateCalculate']['type'] = $type;
		$data['RateCalculate']['status'] = $status;

		if (!empty($rated)) {
			$this->id = $rated['RateCalculate']['id'];
		} else {
			$data['RateCalculate']['user_id'] = $userId;
			$data['RateCalculate']['model'] = $alias;
			$data['RateCalculate']['foreign_key'] = $foreignKey;
			$data['RateCalculate']['type'] = $type;
			$data['RateCalculate']['status'] = $status;
			$this->create();
		}

		if ($this->save($data)) {
			return true;
		} else {
			return false;
		}
	}

/**
 * getPoint Method
 * Get point from token
 */
	public function getPoint($token, $type, $status, $options = array()) {
		if (isset($options['recursive'])) {
			$this->recursive = $options['recursive'];
		}
		$token = ClassRegistry::init('Givrate.Token')->findByToken($token);
		$result = $this->find('first', array(
			'conditions' => array(
				'RateCalculate.model' => $token['Token']['model'],
				'RateCalculate.foreign_key' => $token['Token']['foreign_key'],
				'RateCalculate.type' => $type,
				'RateCalculate.status' => $status,
			)
		));
		return $result;
	}

	public function _findBestRate($state, $query, $results = array()) {
		if ($state === 'before') {
			$limit = isset($query['limit']) ? $query['limit'] : null;
			$alias = isset($query['alias']) ? $query['alias'] : null;

			if ($limit != null) {
				$query['limit'] = $limit;
			}
			$query = Set::merge($query, array(
				'conditions' => array(
					'RateCalculate.model' => $alias,
					),
				'order' => 'RateCalculate.avg DESC',
				'limit' => $limit,
				));
			return $query;
		} else {
			return $results;
		}
	}

/**
 * countPoint Method
 * Count point
 *
 * @type default value 'vote'
 */
	public function countPoint($model, $userId, $type, $status = null) {
		if (empty($type)) {
			return false;
		}
		if ($type == 'vote') {
			$field = 'point';
		} else {
			$field = 'avg';
		}
		if ($status != null) {
			$sql = sprintf("
				SELECT sum(%s) as Total
				FROM rate_calculates
				WHERE model = '%s'
				AND user_id = %d
				AND type = '%s'
				AND status = '%s'
				", $field, $model, $userId, $type, $status);
		} else {
			$sql = sprintf("
				SELECT sum(%s) as Total
				FROM rate_calculates
				WHERE model = '%s'
				AND user_id = %d
				", $field, $model, $userId, $type);
		}

		$total = $this->query($sql);
		return $total[0][0];
	}
}
