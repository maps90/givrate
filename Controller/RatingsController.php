<?php
App::uses('GivrateAppController', 'Givrate.Controller');
App::uses('PointUtil', 'Givrate.Utility');
/**
 * Ratings Controller
 *
 * @property Rating $Rating
 */
class RatingsController extends GivrateAppController {

	public $components = array(
		'Givrate.Givrate'
	);

	public function beforeFilter() {
		parent::beforeFilter();

		switch ($this->request->params['action']) {
			case 'vote':
			case 'submit':
				$this->Security->csrfCheck = false;
				$this->Security->validatePost = false;
			break;
		}
		$this->Point = new PointUtil;
	}


/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Rating->recursive = 0;
		$this->set('ratings', $this->paginate());
	}

/**
 * admin_view method
 *
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->Rating->id = $id;
		if (!$this->Rating->exists()) {
			throw new NotFoundException(__('Invalid rating'));
		}
		$this->set('rating', $this->Rating->read(null, $id));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->Rating->create();
			if ($this->Rating->save($this->request->data)) {
				$this->Session->setFlash(__('The rating has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The rating could not be saved. Please, try again.'));
			}
		}
		$users = $this->Rating->User->find('list');
		$this->set(compact('users'));
	}

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->Rating->id = $id;
		if (!$this->Rating->exists()) {
			throw new NotFoundException(__('Invalid rating'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Rating->save($this->request->data)) {
				$this->Session->setFlash(__('The rating has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The rating could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Rating->read(null, $id);
		}
		$users = $this->Rating->User->find('list');
		$this->set(compact('users'));
	}

/**
 * admin_delete method
 *
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Rating->id = $id;
		if (!$this->Rating->exists()) {
			throw new NotFoundException(__('Invalid rating'));
		}
		if ($this->Rating->delete()) {
			$this->Session->setFlash(__('Rating deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Rating was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

/**
 * get related rating
 */
	public function related() {
		$ralated = array();
		if ($this->Auth->user('id') && !empty($this->request->query)) {
			extract($this->request->query);
			if (!empty($rate_id) && !empty($rating) && !empty($user_id) && !empty($alias)) {
				$related = $this->Rating->related($rate_id, $rating, $user_id, $alias);
			}
		}
		$this->set(compact('related'));
	}

/**
 * submit rating
 */
	public function submit() {
		if ($this->request->is('post') || $this->request->is('put')) {
			$rating = $this->request->data['rating'];
			$user_id = $this->Session->read('Auth.User.id');
			if (isset($this->request->data['id'])) {
				$owner = $this->request->data['id'];
			} else {
				$owner = null;
			}
			$token = $this->request->data['token'];
			$stars = $this->request->data['stars'];
			$type = $this->request->data['type'];
			$status = $this->request->data['rstatus'];

			$responseVal = array(
				'result' => false,
				'msg' => Configure::read('Givrate.error_msg_rating')
			);

			$star = range(1, $stars);
			if (in_array($rating, array_values($star)) === true) {
				$result = $this->Givrate->sendTo($token, $type, $rating, $user_id, $status, $owner);
				if ($result) {
					$RateCalculate = ClassRegistry::init('Givrate.RateCalculate');
					$rate = $RateCalculate->getPoint($token, $type, $status, array('recursive' => -1));
					$currentStars = $this->Point->currentStars($rate['RateCalculate']['avg'], $rate['RateCalculate']['point'], $rate['RateCalculate']['count']);

					$response = array(
						'result' => true,
						'avg' => $rate['RateCalculate']['avg'],
						'stars' => $currentStars
					);
				} else {
					$response = $responseVal;
				}
			} else {
				$response = $responseVal;
			}
			$this->set(compact('response'));
			$this->set('_serialize', 'response');
		}
	}

/**
 * vote
 */
	public function vote() {
		if ($this->request->is('post') || $this->request->is('put')) {
			$vote = $this->request->data['vote'];
			$user_id = $this->Session->read('Auth.User.id');
			if (isset($this->request->data['id'])) {
				$owner = $this->request->data['id'];
			} else {
				$owner = null;
			}
			$token = $this->request->data['token'];
			$type = $this->request->data['type'];
			$status = $this->request->data['rstatus'];

			$point = ClassRegistry::init('Givrate.RateCalculate')->getPoint($token, 'vote', $status, array('recursive' => -1));
			if (empty($point)) {
				$point = $vote;
			} else {
				$point = $point['RateCalculate']['point'] + $vote;
			}

			$response = array('result' => false);
			if ($voteChecking = $this->Givrate->voteChecking($vote)) {
				$result = $this->Givrate->sendTo($token, $type, $vote, $user_id, $status, $owner);
				if ($result) {
					$response = array(
						'result' => true,
						'point' => $point,
					);
				} else {
					$response = Set::merge($response, array(
						'msg' => Configure::read('Givrate.error_msg_checking_vote')
					));
				}
			} else {
				$response = Set::merge($response, array(
					'msg' => Configure::read('Givrate.error_msg_vote')
				));
			}

			$this->set(compact('response'));
			$this->set('_serialize', 'response');
		}
	}
}
