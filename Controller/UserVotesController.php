<?php
App::uses('GivrateAppController', 'Givrate.Controller');
/**
 * UserVotes Controller
 *
 * @property UserVote $UserVote
 */
class UserVotesController extends GivrateAppController {

	public $components = array(
		'Search.Prg' => array(
			'presetForm' => array(
				'paramType' => 'querystring',
			),
			'commonProcess' => array(
				'paramType' => 'querystring',
				'filterEmpty' => true,
			),
		)
	);

/**
 * admin_index method
 *
 * @return void
 */
	public function admin_index() {
		$this->Prg->commonProcess();
		$searchFields = array('user' => array('type' => 'text'), 'date');
		$this->UserVote->recursive = 0;
		$this->paginate['conditions'] = $this->UserVote->parseCriteria($this->request->query);
		$userVotes = $this->paginate();
		$this->set(compact('userVotes', 'searchFields'));
	}

/**
 * admin_view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function admin_view($id = null) {
		if (!$this->UserVote->exists($id)) {
			throw new NotFoundException(__d('croogo', 'Invalid user vote'));
		}
		$options = array('conditions' => array('UserVote.' . $this->UserVote->primaryKey => $id));
		$this->set('userVote', $this->UserVote->find('first', $options));
	}

/**
 * admin_add method
 *
 * @return void
 */
	public function admin_add() {
		if ($this->request->is('post')) {
			$this->UserVote->create();
			if ($this->UserVote->save($this->request->data)) {
				$this->Session->setFlash(__d('croogo', 'The user vote has been saved'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('croogo', 'The user vote could not be saved. Please, try again.'), 'default', array('class' => 'error'));
			}
		}
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
		if (!$this->UserVote->exists($id)) {
			throw new NotFoundException(__d('croogo', 'Invalid user vote'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->UserVote->save($this->request->data)) {
				$this->Session->setFlash(__d('croogo', 'The user vote has been saved'), 'default', array('class' => 'success'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('croogo', 'The user vote could not be saved. Please, try again.'), 'default', array('class' => 'error'));
			}
		} else {
			$options = array('conditions' => array('UserVote.' . $this->UserVote->primaryKey => $id));
			$this->request->data = $this->UserVote->find('first', $options);
		}
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
		$this->UserVote->id = $id;
		if (!$this->UserVote->exists()) {
			throw new NotFoundException(__d('croogo', 'Invalid user vote'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->UserVote->delete()) {
			$this->Session->setFlash(__d('croogo', 'User vote deleted'), 'default', array('class' => 'success'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__d('croogo', 'User vote was not deleted'), 'default', array('class' => 'error'));
		$this->redirect(array('action' => 'index'));
	}
}
