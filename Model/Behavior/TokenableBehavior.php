<?php

class TokenableBehavior extends ModelBehavior {

	var $__settings = array();

	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->_settings[$Model->alias])) {
			$this->__settings[$Model->alias] = array(
				'enabled' => true,
				'foreignKey' => 'id',
				'tokenField' => 'token',
				'tokenLength' => 5,
				'maxIterations' => 10,
			);
		}
		$this->__settings[$Model->alias] = Set::merge($this->__settings[$Model->alias], $settings);
		$Model->bindModel(array('hasOne' => array(
			'Token' => array(
				'className' => 'Givrate.Token',
				'foreignKey' => 'foreign_key',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
			)
		)), false);
	}

	public function beforeSave(Model $Model, $options = array()) {
		if (!$this->__settings[$Model->alias]['enabled']) {
			return false;
		}

		$tokenField = $this->__settings[$Model->alias]['tokenField'];
		if ($Model->id && isset($Model->data[$Model->alias][$tokenField]) && $Model->data[$Model->alias][$tokenField] != 'default') {
			return true;
		}

		$this->Token =& ClassRegistry::init('Givrate.Token');
		$len = $this->__settings[$Model->alias]['tokenLength'];

		for ($i = 0; $i < 10; $i++) {
			$token = $this->__GenerateUniqid($len);
			if ($this->__isValidToken($token)) {
				$Model->data[$Model->alias][$tokenField] = $token;
				return true;
			}
		}
		trigger_error('Cannot generate token after ' . $maxIterations . ' iterations');
		return false;
	}

	public function afterSave(Model $Model, $created, $options = array()) {
		$tokenField = $this->__settings[$Model->alias]['tokenField'];
		if ($created) {
			return $this->__saveToken($Model, $Model->data[$Model->alias][$tokenField]);
		}
		return true;
	}

	public function __saveToken(&$Model, $token) {
		$this->Token =& ClassRegistry::init('Givrate.Token');
		$token = $this->Token->create(array(
			'model' => $Model->alias,
			'foreign_key' => $Model->id,
			'token' => $token,
		));
		return $this->Token->save($token);
	}

	public function __isValidToken($token) {
		$this->Token =& ClassRegistry::init('Givrate.Token');
		$count = $this->Token->find('count', array(
			'conditions' => array(
				'Token.token' => $token,
			),
		));
		return 0 == $count;
	}

	public function __GenerateUniqid($len) {
		return substr(uniqid(), -$len);
	}

	public function beforeFind(Model $Model, $query) {
		$Model->bindModel(array('hasOne' => array(
			'Token' => array(
				'className' => 'Givrate.Token',
				'foreignKey' => 'foreign_key',
				'unique' => true,
				'conditions' => array('Token.model' => $Model->alias),
				'fields' => '',
			)
		)), false);
		return $query;
	}
}
