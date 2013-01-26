<?php
App::uses('GivrateAppModel', 'Givrate.Model');
/**
 * RateCalculate Model
 *
 */
class RateCalculate extends GivrateAppModel {
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

		'sum' => array(
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

	public function calculating($alias, $foreignKey, $value) {
		$rated = $this->find('first', array(
			'recursive' => -1,
			'conditions' => array(
				'RateCalculate.model' => $alias,
				'RateCalculate.foreign_key' => $foreignKey
				)
			));

		App::uses('PointUtil', 'Givrate.Utility');
		$this->Point = new PointUtil;
		if (!empty($rated)) {
			$count = $this->Point->rateCount($rated['RateCalculate']['count']);
			$sum = $this->Point->rateSum($rated['RateCalculate']['sum'], $value);
			$avg = $this->Point->rateAvg($sum, $count);
		} else {
			$count = $this->Point->rateCount(0);
			$sum = $this->Point->rateSum(0, $value);
			$avg = $this->Point->rateAvg($sum, $count);
		}

		$data['RateCalculate']['count'] = $count;
		$data['RateCalculate']['sum'] = $sum;
		$data['RateCalculate']['avg'] = $avg;

		if (!empty($rated)) {
			$this->id = $rated['RateCalculate']['id'];
		} else {
			$data['RateCalculate']['model'] = $alias;
			$data['RateCalculate']['foreign_key'] = $foreignKey;
			$this->create();
		}

		if ($this->save($data)) {
			return true;
		} else {
			return false;
		}
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
}
