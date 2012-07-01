<?php
App::uses('GivrateAppController', 'Givrate.Controller');
/**
 * RateCalculates Controller
 *
 * @property RateCalculate $RateCalculate
 */
class RateCalculatesController extends GivrateAppController {


/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->RateCalculate->recursive = 0;
		$this->set('rateCalculates', $this->paginate());
	}

/**
 * admin_view method
 *
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		$this->RateCalculate->id = $id;
		if (!$this->RateCalculate->exists()) {
			throw new NotFoundException(__('Invalid rate calculate'));
		}
		$this->set('rateCalculate', $this->RateCalculate->read(null, $id));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->RateCalculate->create();
			if ($this->RateCalculate->save($this->request->data)) {
				$this->Session->setFlash(__('The rate calculate has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The rate calculate could not be saved. Please, try again.'));
			}
		}
	}

/**
 * admin_edit method
 *
 * @param string $id
 * @return void
 */
	public function admin_edit($id = null) {
		$this->RateCalculate->id = $id;
		if (!$this->RateCalculate->exists()) {
			throw new NotFoundException(__('Invalid rate calculate'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->RateCalculate->save($this->request->data)) {
				$this->Session->setFlash(__('The rate calculate has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The rate calculate could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->RateCalculate->read(null, $id);
		}
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
		$this->RateCalculate->id = $id;
		if (!$this->RateCalculate->exists()) {
			throw new NotFoundException(__('Invalid rate calculate'));
		}
		if ($this->RateCalculate->delete()) {
			$this->Session->setFlash(__('Rate calculate deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Rate calculate was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
