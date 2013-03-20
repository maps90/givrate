<?php

class GivrateActivation {

	public function beforeActivation(&$controller) {
		return true;
	}

/**
 * Called after activating the plugin in ExtensionsPluginsController::admin_toggle()
 *
 * @param object $controller Controller
 * @return void
 */
	public function onActivation(&$controller) {
		// ACL: set ACOs with permissions
		$controller->Croogo->addAco('Givrate/Ratings/submit', array('registered'));
		$controller->Croogo->addAco('Givrate/Ratings/vote', array('registered'));
		$controller->Croogo->addAco('Givrate/RateCalculates/admin_index', array('admin'));
		$controller->Croogo->addAco('Givrate/UserPoints/admin_index', array('admin'));


		$controller->Setting->write('Givrate.vote_approved', '1', array('editable' => 1, 'title' => 'Vote Number Approved (Separated with comma)'));
		$controller->Setting->write('Givrate.error_msg_vote', 'Voting failed!. Please try again.', array('editable' => 1, 'title' => 'Error Message for vote'));
		$controller->Setting->write('Givrate.error_msg_checking_vote', 'Failed voting or you already voted this content.', array('editable' => 1, 'title' => 'Error Message for vote more than once'));
		$controller->Setting->write('Givrate.error_msg_rating', 'Rating failed!. Please try again.', array('editable' => 1, 'title' => 'Error Message for rating'));
	}

	public function beforeDeactivation(&$controller) {
		return true;
	}

/**
 * Called after deactivating the plugin in ExtensionsPluginsController::admin_toggle()
 *
 * @param object $controller Controller
 * @return void
 */
	public function onDeactivation(&$controller) {
		$controller->Croogo->removeAco('Givrate');

		$controller->Setting->deleteKey('Givrate.vote_approved');
		$controller->Setting->deleteKey('Givrate.error_msg_vote');
		$controller->Setting->deleteKey('Givrate.error_msg_checking_vote');
		$controller->Setting->deleteKey('Givrate.error_msg_rating');
	}
}
?>
