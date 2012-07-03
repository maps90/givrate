<?php

class GivrateHelper extends AppHelper {

	public $helpers = array('Html', 'Form', 'Js' => 'Jquery');

	public $allowedTypes = array('ul', 'ol', 'radio');

	public $defaults = array(
		'stars' => 5,
		'item' => null,
		'value' => 0,
		'type' => 'ul',
		'createForm' => false,
		'url' => array(),
		'link' => true,
		'redirect' => true,
		'class' => 'rating'
		);

	/*
	 * FIXME: display rate link but still unstable
	 */
	public function display($options = array(), $urlHtmlAttributes = array()) {
		$options = array_merge($this->defaults, $options);
		if (empty($options['item'])) {
			throw new Exception(__d('givrate', 'You must set the id of the item you want to rate.'), E_USER_NOTICE);
		}

		$stars = null;
		for ($i = 1; $i <= $options['stars']; $i++) {
			$link = null;
			if ($options['link'] == true) {
				$url = array_merge($options['url'], array('rate' => $options['item'], 'rating' => $i));
				if ($options['redirect']) {
					$url['redirect'] = 1;
				}
				$link = $this->Html->link($i, $url, $urlHtmlAttributes);
			}
			$stars .= $this->Html->tag('li', $link, array('class' => 'star' . $i));
		}

		if (in_array($options['type'], $this->allowedTypes)) {
			$type = $options['type'];
		} else {
			$type = 'ul';
		}

		$stars = $this->Html->tag($type, $stars, array('class' => $options['class'] . ' ' . 'givrate-' . round($options['value'], 0)));
		return $stars;
	}

}
