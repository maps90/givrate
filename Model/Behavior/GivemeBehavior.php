<?php

class GivemeBehavior extends ModelBehavior {

	public $settings = array();

	protected $_defaults = array(
		'modelClass' => null,
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
			'RateCalculate' => array(
				'className' => $this->settings[$Model->alias]['rateCalClass'],
				'foreignKey' => $this->settings[$Model->alias]['foreignKey'],
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'dependent' => true
			)
		)), false);

		$Model->RateCalculate->bindModel(array('belongsTo' => array(
			$Model->alias => array(
				'className' => $this->settings[$Model->alias]['modelClass'],
				'foreignKey' => 'foreign_key',
				'counterCache' => $this->settings[$Model->alias]['countRates']
			)
		)), false);
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

	public function beforeFind(Model $Model, $query) {
		$Model->bindModel(array('hasMany' => array(
			'RateCalculate' => array(
				'className' => $this->settings[$Model->alias]['rateCalClass'],
				'foreignKey' => $this->settings[$Model->alias]['foreignKey'],
				'unique' => true,
				'conditions' => array('RateCalculate.model' => $Model->alias),
				'fields' => '',
				'dependent' => true
			)
		)), false);
		return $query;
	}

}
