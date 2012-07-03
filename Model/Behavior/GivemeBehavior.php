<?php

class GivemeBehavior extends ModelBehavior {

	public $settings = array();

	protected $_defaults = array(
		'modelClass' => null,
		'rateClass' => 'Givrate.Rating',
		'rateCalClass' => 'Givrate.RateCalculate',
		'foreignKey' => 'foreign_key',
		'countRates' => false,
		);

	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = $this->_defaults;
		}
		$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], $settings);
		if (empty($this->settings[$Model->alias]['modelClass'])) {
			$this->settings[$Model->alias]['modelClass'] = $Model->name;
		}

		$Model->bindModel(array('hasMany' => array(
			'Rating' => array(
				'className' => $this->settings[$Model->alias]['rateClass'],
				'foreignKey' => $this->settings[$Model->alias]['foreignKey'],
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'dependent' => true
			),
			'RateCalculate' => array(
				'className' => $this->settings[$Model->alias]['rateCalClass'],
				'foreignKey' => $this->settings[$Model->alias]['foreignKey'],
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'dependent' => true
			)
		)), false);

		$Model->Rating->bindModel(array('belongsTo' => array(
			$Model->alias => array(
				'className' => $this->settings[$Model->alias]['modelClass'],
				'foreignKey' => 'foreign_key',
				'counterCache' => $this->settings[$Model->alias]['countRates']
			)
		)), false);
	}

	/*
	 * saveRating for saving into table ratings with record model,
	 * foreign_key model and user_id
	 */
	public function saveRating(Model $Model, $foreignKey = null, $userId = null, $value = 0) {
		$type = 'saveRating';
		$this->beforeRateCallback($Model, compact('foreignKey', 'userId', 'value', 'update', 'type'));
		$rated = $this->isRatedBy($Model, $foreignKey, $userId);

		if (!$rated) {
			$data['Rating']['foreign_key'] = $foreignKey;
			$data['Rating']['model'] = $Model->alias;
			$data['Rating']['user_id'] = $userId;
			$data['Rating']['value'] = $value;

			$Model->Rating->create();
			if ($Model->Rating->save($data)) {
				$this->calculateRating($Model, $foreignKey, $value);
				$this->afterRateCallback($Model, compact('foreignKey', 'userId', 'value', 'result', 'type'));
				return true;
			} else {
				return false;
			}
		}

		return false;
	}

	/*
	 * isRatedBy is to checking user already rated in same model or not
	 */
	public function isRatedBy(Model $Model, $foreignKey = null, $userId = null) {
		$findMethod = 'first';
		if (is_array($foreignKey)) {
			$findMethod = 'all';
		}

		$entry = $Model->Rating->find($findMethod, array(
			'recursive' => -1,
			'conditions' => array(
				'Rating.foreign_key' => $foreignKey,
				'Rating.user_id' => $userId,
				'Rating.model' => $Model->alias
			)
		));

		if ($findMethod == 'all') {
			return Set::extract($entry, '{n}.Rating.foreign_key');
		}

		if (empty($entry)) {
			return false;
		}

		return $entry;
	}

	/*
	 * calculateRating is for saving and calculate count user, sum total value
	 * and average into rate_calculates table.
	 */
	public function calculateRating(Model $Model, $foreignKey = null, $value = null) {
		$rateCal = $Model->RateCalculate->find('first', array(
			'recursive' => -1,
			'conditions' => array(
				'RateCalculate.model' => $Model->alias,
				'RateCalculate.foreign_key' => $foreignKey
				)
			));
		$count = $this->_countRate($rateCal['RateCalculate']['count']);
		$sum = $this->_sumRate($rateCal['RateCalculate']['sum'], $value);

		$data['RateCalculate']['count'] = $count;
		$data['RateCalculate']['sum'] = $sum;
		$data['RateCalculate']['avg'] = $this->_averageRate($sum, $count);

		if (!empty($rateCal)) {
			$Model->RateCalculate->id = $rateCal['RateCalculate']['id'];
		} else {
			$data['RateCalculate']['model'] = $Model->alias;
			$data['RateCalculate']['foreign_key'] = $foreignKey;
			$Model->RateCalculate->create();
		}

		if ($Model->RateCalculate->save($data)) {
			return true;
		} else {
			return false;
		}
	}

	public function _averageRate($value = null, $count = null) {
		$avg = ($value / $count);
		return $avg;
	}

	public function _countRate($count = null) {
		$count = $count + 1;
		return $count;
	}

	public function _sumRate($oldValue = null, $value = null) {
		$sum = $oldValue + $value;
		return $sum;
	}

	public function afterRateCallback(Model $Model, $data = array()) {
		if (method_exists($Model, 'afterRate')) {
			$Model->afterRate($data);
		}
	}

	public function beforeRateCallback(Model $Model, $data = array()) {
		if (method_exists($Model, 'beforeRate')) {
			$Model->beforeRate($data);
		}
	}
}
