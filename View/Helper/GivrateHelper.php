<?php

class GivrateHelper extends AppHelper {

	public $helpers = array('Html', 'Form', 'Js' => 'Jquery');

	public $allowedTypes = array('ul', 'ol', 'radio');

	public function beforeRender() {
		$params = $this->_View->params;
		if (isset($params['isAjax']) && $params['isAjax'] === true) {
			return;
		}
		if (isset($params['admin']) && $params['admin'] === true) {
			return;
		}
		$this->Html->script(array(
			'/givrate/js/givrate'), array('inline' => false
		));
	}

	/*
	 * Givrate::star helper for submit rate
	 * @token: value
	 */
	public function star($token, $userId, $options = array()) {
		if (empty($token)) {
			throw new Exception(__d('givrate', 'You must set the id of the item you want to rate.'), E_USER_NOTICE);
		}
		$js = 'javascript:;';

		$defaults = array(
			'type' => 'ul',
			'class' => 'rating',
			'link' => true,
			'value' => 0,
			);
		if (isset($options['stars'])) {
			$options = Set::merge($defaults, array('stars' => $options['stars']));
		} else {
			$options = Set::merge($defaults, array('stars' => 5));
		}

		$stars = null;
		for ($i = 1; $i <= $options['stars']; $i++) {
			$title = array(1 => 'bad', 'good enough', 'good', 'awesome', 'amazing');
			$link = null;
			$options = Set::merge($options, array(
				'class' => 'rate-link',
				'data-token' => $token,
				'data-id' => $userId,
				'data-rating' => $i,
				'title' => $title[$i],
				'escape' => false,
				));
			$link = $this->Html->link('&nbsp;', $js, $options);
			$stars .= $this->Html->tag('li', $link, array('class' => 'star' . $i));
$script =<<<EOF
$('body').on('click', '.rate-link', Givrate.Ratings.star);
EOF;
		}
		if (in_array($options['type'], $this->allowedTypes)) {
			$type = $options['type'];
		} else {
			$type = 'ul';
		}

		$stars = $this->Html->div('stars', $this->Html->tag($type, $stars, array('class' => 'rating')));
		$this->Js->buffer($script);
		return $stars;
	}

}
