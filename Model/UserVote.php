<?php
App::uses('GivrateAppModel', 'Givrate.Model');
/**
 * UserVote Model
 *
 * @property User $User
 */
class UserVote extends GivrateAppModel {

	public $actsAs = array(
		'Search.Searchable'
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

	public $filterArgs = array(
		array('name' => 'user', 'type' => 'query', 'method' => 'filterUsers'),
		array('name' => 'date', 'type' => 'query', 'method' => 'filterDates'),
	);

	public function filterDates($data = array()) {
		if (empty($data['date'])) {
			return array();
		}
		$date = date('Y-m-d', strtotime($data['date']));
		return array(
			'UserVote.vote_date' => $date
		);
	}

	public function filterUsers($data = array()) {
		if (empty($data['user'])) {
			return array();
		}
		$user = '%' . $data['user'] . '%';
		return array(
			'OR' => array(
				array('User.name LIKE' => $user),
				array('User.username LIKE' => $user)
			)
		);
	}

	public function counting($userId, $tokenId) {
		if ($userId == 1) {
			return true;
		}
		$date = date('Y-m-d');
		$voting = $this->find('first', array(
			'conditions' => array(
				'UserVote.user_id' => $userId,
				'UserVote.foreign_key' => $tokenId
			)
		));
		$maxVote = Configure::read('Givrate.max_vote');
		if (!empty($voting)) {
			$voteDate = $voting['UserVote']['vote_date'];
			$voteCount = $voting['UserVote']['count'];
			if (($voteDate == $date) && ($voteCount >= $maxVote)) {
				return false;
			}
			$this->id = $voting['UserVote']['id'];
			if ($voteDate != $date) {
				$counting = 1;
			} else {
				$counting = $voteCount + 1;
			}
			$this->saveField('count', $counting);
			$this->saveField('vote_date', $date);
			return true;
		}
		$userVoteData = array(
			'user_id' => $userId,
			'foreign_key' => $tokenId,
			'count' => 1,
			'vote_date' => $date
		);
		$this->save($userVoteData);
		return true;
	}

/**
 * check method
 *
 * exclude role admin
 */
	public function check($userId, $tokenId) {
		if ($userId == 1) {
			return true;
		}
		$date = date('Y-m-d');
		$voting = $this->find('first', array(
			'conditions' => array(
				'UserVote.user_id' => $userId,
				'UserVote.foreign_key' => $tokenId,
				'UserVote.vote_date' => $date
			)
		));
		$maxVote = Configure::read('Givrate.max_vote');
		if (!empty($voting)) {
			$voteDate = $voting['UserVote']['vote_date'];
			$voteCount = $voting['UserVote']['count'];
			if (($voteDate == $date) && ($voteCount >= $maxVote)) {
				return true;
			}
		}
		return false;
	}

}
