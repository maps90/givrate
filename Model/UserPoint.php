<?php
App::uses('GivrateAppModel', 'Givrate.Model');

class UserPoint extends GivrateAppModel {

	public $useDbConfig = 'ratings';

	public $validate = array(
		'user_id' => array(
			'notempty' => array(
				'rule' => array('notempty'),
			)
		)
	);

	public $belongsTo = array(
		'User' => array(
			'className' => 'Users.User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public function countMyPoint($userId, $value) {
		$userPoint = $this->find('first', array(
			'conditions' => array(
				'UserPoint.user_id' => $userId
			)
		));

		if (empty($userPoint)) {
			$this->create();
			$data['UserPoint']['user_id'] = $userId;
			$data['UserPoint']['raters'] = 1;
			$data['UserPoint']['points'] = $value;
		} else {
			$this->id = $userPoint['UserPoint']['id'];
			$data['UserPoint']['raters'] = $userPoint['UserPoint']['raters'] + 1;
			$data['UserPoint']['points'] = $userPoint['UserPoint']['points'] + $value;
		}

		if ($this->save($data)) {
			return true;
		} else {
			return false;
		}
	}
}
