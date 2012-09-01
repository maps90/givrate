<?php
App::uses('GivrateAppController', 'Givrate.Controller');
/**
 * Ratings Controller
 *
 * @property Rating $Rating
 */
class RatingsController extends GivrateAppController {


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

	public function submit() {
		if (isset($this->request->params['rate']) || isset($this->request->params['rating']) || isset($this->request->params['user']) || isset($this->request->params['alias'])) {
			$userId = $this->request->params['user'];
			$alias = $this->request->params['alias'];
			$foreignKey = $this->request->params['rate'];
			$value = $this->request->params['rating'];
		} else {
			return false;
		}

		$rated = $this->Rating->isRated($alias, $foreignKey, $userId, array('recursive' => true));
		if ($rated) {
			return false;
		}

		$data = array(
			'user_id' => $this->request->params['user'],
			'model' => $this->request->params['alias'],
			'foreign_key' => $this->request->params['rate'],
			'value' => $this->request->params['rating'],
			);
		$this->Rating->create();

		if ($result = $this->Rating->save($data)) {
			$result = 'successful';
		} else {
			$result = 'failed';
		}
		$this->set(compact('result'));
		$this->set('_serialize', 'result');
	}
}
