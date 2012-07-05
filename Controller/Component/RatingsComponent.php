<?php

App::uses('Component', 'Controller');

class RatingsComponent extends Component {

	public $components = array('Cookie', 'Session', 'Auth', 'RequestHandler');
	public $enabled = true;

	public $modelName = null;

	public $parameters = array('rate' => true, 'rating' => true, 'redirect' => true);

	public function initialize(&$Controller) {
		$this->Controller = $Controller;
		if ($this->enabled == true) {
			$this->Controller->request->params['isJson'] = (isset($this->Controller->request->params['url']['ext']) && $this->Controller->request->params['url']['ext'] === 'json');
			if ($this->Controller->request->params['isJson']) {
				Configure::write('debug', 0);
			}
			if (empty($this->modelName)) {
				$this->modelName = $Controller->modelClass;
			}
			if (!$Controller->{$this->modelName}->Behaviors->attached('Giveme')) {
				$Controller->{$this->modelName}->Behaviors->load('Givrate.Giveme', $this->settings);
			}
			$Controller->helpers[] = 'Givrate.Givrate';
		}
	}

	public function startup(&$Controller) {
		$message = '';
		$rating = null;
		$params = $Controller->request->params['named'];
		if (empty($params['rating']) && !empty($Controller->request->data[$Controller->modelClass]['rating'])) {
			$params['rating'] = $Controller->request->data[$Controller->modelClass]['rating'];
		}
		if (!method_exists($Controller, 'rate')) {
			if (isset($params['rate']) && isset($params['rating']) && $this->enabled == true) {
				$this->rate($params['rate'], $params['rating'], $Controller->Auth->user('id'), !empty($params['redirect']));
			}
		}
	}

	/*
	 * FIXME: rate processing into behavior but still not stable.
	 */
	public function rate($rate, $rating, $user, $alias = null, $redirect = false) {

		if (!empty($alias)) {
			$this->modelName = $alias;
			$this->Controller->modelClass = $alias;
		}

		$Controller = $this->Controller;

		$Controller->{$this->modelName}->id = $rate;
		if ($Controller->{$this->modelName}->exists(null)) {
			if ($Controller->{$this->modelName}->saveRating($rate, $user, $rating)) {
				$message = __d('givrate', 'Your rate was successfull.');
				$status = 'success';
			} else {
				$message = __d('givrate', 'You have already rated.');
				$status = 'error';
			}
		} else {
			$message = __d('givrate', 'Invalid rate.');
			$status = 'error';
		}

		$result = compact('status', 'message', 'rating');
		$this->Controller->set($result);
		if (!empty($redirect)) {
			if (is_bool($redirect)) {
				$this->redirect($this->referer());
			} else {
				$this->redirect($redirect);
			}
		} else {
			return $result;
		}
	}

}
