<?php

class GivrateHelper extends AppHelper {

	public $helpers = array('Html', 'Form', 'Js' => 'Jquery');

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
	 * options:
	 *	- userId : owner id for user point. If empty will not creating for user point.
	 */
	public function star($token, $options = array()) {
		if (empty($token)) {
			throw new Exception(__d('givrate', 'You must set the id of the item you want to rate.'), E_USER_NOTICE);
		}
		$js = 'javascript:;';
		$options = Set::merge($options, array(
			'class' => 'rating',
			'link' => true,
			'value' => 0,
			'stars' => 5,
		));
		if (isset($options['userId'])) {
			$options = Set::merge($options, array('data-id' => 's'.$options['userId']));
			unset($options['userId']);
		}

		$stars = null;
		for ($i = 1; $i <= $options['stars']; $i++) {
			$title = array(1 => 'bad', 'good enough', 'good', 'awesome', 'amazing');
			$link = null;
			$options = Set::merge($options, array(
				'class' => 'rate-link',
				'data-token' => $token,
				'data-rating' => 's'.$i,
				'title' => $title[$i],
				'escape' => false,
			));
			$link = $this->Html->link('&nbsp;', $js, $options);
			$stars .= $this->Html->tag('li', $link, array('class' => 'star' . $i));
$script =<<<EOF
$('body').on('click', '.rate-link', Givrate.Ratings.star);
EOF;
		}

		$stars = $this->Html->div('stars', $this->Html->tag('ul', $stars, array('class' => 'rating')));
		$this->Js->buffer($script);
		return $stars;
	}

}
