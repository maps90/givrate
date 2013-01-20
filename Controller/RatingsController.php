<?php
App::uses('GivrateAppController', 'Givrate.Controller');
/**
 * Ratings Controller
 *
 * @property Rating $Rating
 */
class RatingsController extends GivrateAppController {

	public function beforeFilter() {
		parent::beforeFilter();

		switch ($this->request->params['action']) {
			case 'submit':
				$this->Security->csrfCheck = false;
				$this->Security->validatePost = false;
			break;
		}
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
 * submit add rating
 */
	public function submit() {
		if ($this->request->is('post') || $this->request->is('put')) {
			$rating = $this->request->data['rating'];
			$user_id = $this->Session->read('Auth.User.id');
			$owner = $this->request->data['user'];
			$token = $this->request->data['token'];

			$star = range(1, Configure::read('Rating.star'));
			if (in_array($rating, array_values($star)) === true) {
				$result = $this->Rating->rate($token, $rating, $user_id, $owner);
				if ($result) {
					$response = true;
				} else {
					$response = false;
				}
			} else {
				$response = false;
			}
			$this->set(compact('response'));
			$this->set('_serialize', 'response');
		}
	}
}
