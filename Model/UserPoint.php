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

	public function countMyPoint($userId, $value, $type) {
		$userPoint = $this->find('first', array(
			'conditions' => array(
				'UserPoint.user_id' => $userId,
				'UserPoint.type' => $type,
			)
		));

		App::uses('PointUtil', 'Givrate.Utility');
		$this->Point = new PointUtil;
		if (empty($userPoint)) {
			$this->create();
			$data['UserPoint']['user_id'] = $userId;
			$data['UserPoint']['raters'] = 1;
			$data['UserPoint']['points'] = $value;
			$data['UserPoint']['type'] = $type;
		} else {
			$this->id = $userPoint['UserPoint']['id'];
			$data['UserPoint']['raters'] = $userPoint['UserPoint']['raters'] + 1;
			$data['UserPoint']['points'] = $userPoint['UserPoint']['points'] + $value;
			$data['UserPoint']['type'] = $type;
		}
		$avg = $this->Point->rateAvg($data['UserPoint']['points'], $data['UserPoint']['raters']);
		$data['UserPoint']['avg'] = $avg;

		$date = new	DateTime();
		$data['UserPoint']['point_date'] = $date->format('Y-m-d H:i:s');

		if ($this->save($data)) {
			return true;
		} else {
			return false;
		}
	}

	public function getMyPoint($userId) {
		$mypoint = $this->find('first', array(
			'conditions' => array(
				'UserPoint.user_id' => $userId
			)
		));
		return $mypoint;
	}
}
