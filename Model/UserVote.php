<?php
App::uses('GivrateAppModel', 'Givrate.Model');
/**
 * UserVote Model
 *
 * @property User $User
 */
class UserVote extends GivrateAppModel {


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

	public function counting($userId, $tokenId) {
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

	public function check($userId, $tokenId) {
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
