<?php
App::uses('GivrateAppController', 'Givrate.Controller');
/**
 * UserPoints Controller
 *
 * @property UserPoint $UserPoint
 */
class UserPointsController extends GivrateAppController {

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->UserPoint->recursive = 0;
		$this->set('userPoints', $this->paginate());
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->UserPoint->exists($id)) {
			throw new NotFoundException(__('Invalid user point'));
		}
		$options = array('conditions' => array('UserPoint.' . $this->UserPoint->primaryKey => $id));
		$this->set('userPoint', $this->UserPoint->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->UserPoint->create();
			if ($this->UserPoint->save($this->request->data)) {
				$this->Session->setFlash(__('The user point has been saved'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user point could not be saved. Please, try again.'), 'default', array('class' => 'error'));
			}
		}
		$users = $this->UserPoint->User->find('list');
		$this->set(compact('users'));
	}

/**
 * admin_edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		if (!$this->UserPoint->exists($id)) {
			throw new NotFoundException(__('Invalid user point'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->UserPoint->save($this->request->data)) {
				$this->Session->setFlash(__('The user point has been saved'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user point could not be saved. Please, try again.'), 'default', array('class' => 'error'));
			}
		} else {
			$options = array('conditions' => array('UserPoint.' . $this->UserPoint->primaryKey => $id));
			$this->request->data = $this->UserPoint->find('first', $options);
		}
		$users = $this->UserPoint->User->find('list');
		$this->set(compact('users'));
	}

/**
 * admin_delete method
 *
 * @throws NotFoundException
 * @throws MethodNotAllowedException
 * @param string $id
 * @return void
 */
	public function admin_delete($id = null) {
		$this->UserPoint->id = $id;
		if (!$this->UserPoint->exists()) {
			throw new NotFoundException(__('Invalid user point'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->UserPoint->delete()) {
			$this->Session->setFlash(__('User point deleted'), 'default', array('class' => 'success'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User point was not deleted'), 'default', array('class' => 'error'));
		$this->redirect(array('action' => 'index'));
	}
}
