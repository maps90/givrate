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
			'/givrate/js/givrate'), array('inline' => false));
	}

	/*
	 * Givrate::star helper for submit rate
	 * id: put id for rate
	 * userId: active session user.
	 * alias: Model name.
	 */
	public function star($id, $userId, $alias, $options = array()) {
		if (empty($id)) {
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
			$link = null;
			$options = Set::merge($options, array(
				'id' => 'rate-link'.$id,
				'data-alias' => $alias,
				'data-rate' => $id,
				'data-rating' => $i,
				'data-id' => $userId,
				'onclick' => "Givrate.startRate($id,$i);",
				));
			$link = $this->Html->link($i, $js, $options);
			$stars .= $this->Html->tag('li', $link, array('class' => 'star' . $i));
		}
		if (in_array($options['type'], $this->allowedTypes)) {
			$type = $options['type'];
		} else {
			$type = 'ul';
		}

		$Rating = ClassRegistry::init('Givrate.Rating');
		$isRated = $Rating->isRated($alias, $id, $userId, array('recursive' => true));

		if (!empty($isRated)) {
			$stars = 'Rated';
		} else {
			$stars = $this->Html->tag($type, $stars, array('class' => $options['class'] . ' ' . 'givrate-' . round($options['value'], 0)));
		}

		return $stars;
	}

}
