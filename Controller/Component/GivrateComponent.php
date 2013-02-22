<?php

class GivrateComponent extends Component {

	var $controller = null;

	public function startup(Controller $controller) {
		$this->controller =& $controller;
		$this->Rating = ClassRegistry::init('Givrate.Rating');
	}

/**
 * Sending Point
 * @return boolean
 *
 * @token	: Token value.
 * @type	: Type of data "vote" or "rating".
 * @value	: Value point.
 * @user_id	: Auth user_id.
 * @owner	: User_id of the content belongs to them.
 */
	public function sendTo($token, $type, $value, $user_id, $owner) {
		return $this->Rating->rate($token, $type, $value, $user_id, $owner);
	}

/**
 * Vote Checking
 * @return boolean
 *
 * @vote	: value vote number.
 */
	public function voteChecking($vote) {
		$voteNumbers = Configure::read('Givrate.vote_approved');
		$voteNumbers = explode(',', $voteNumbers);
		$votes = array();
		foreach ($voteNumbers as $voteNumber) {
			$votes[] = $voteNumber;
		}

		if (in_array($vote, $votes) === true) {
			return true;
		}
		return false;
	}
}
