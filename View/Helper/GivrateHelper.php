<?php

class GivrateHelper extends AppHelper {

	public $helpers = array('Html', 'Form', 'Js');

	public function beforeRender() {
		$params = $this->_View->params;
		if (isset($params['isAjax']) && $params['isAjax'] === true) {
			return;
		}
		if (isset($params['admin']) && $params['admin'] === true) {
			return;
		}
		$this->Html->css('/givrate/css/style', null, array('inline' => false));
		$this->Html->script(array(
			'/givrate/js/givrate'), array('inline' => false
		));
	}

/*
 * Givrate::displayPoint
 * Display point rate with the stars rate.
 * @value : value data
 * @options: same value like Givrate::star
 */
	public function displayPoint($value, $options = array()) {
		if (empty($value)) {
			throw new Exception(__d('givrate', 'Empty value.'));
		}
		if (isset($value['RateCalculate'])) {
			$avg = empty($value['RateCalculate'][0]['avg']) ? 0 : number_format($value['RateCalculate'][0]['avg'], 1);
		}
		$rating = $this->star($value['Token']['token'], $options);
		$avg = $this->Html->div('span7', $this->Html->div('avg', $this->Html->tag('span', $avg)) . $rating);
		return $this->Html->div('row-fluid', $avg);
	}

/*
 * Givrate::star helper
 * @token: value
 * @options:
 *	- userId : owner id for user point. If empty will not creating for user point.
 *	- stars : value of the stars.
 *	- title : tooltip title for each stars.
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
		));

		if (isset($options['userId'])) {
			$options = Set::merge($options, array('data-id' => 's'.$options['userId']));
			unset($options['userId']);
		}

		if (isset($options['stars'])) {
			$options = Set::merge($options, array('stars' => $options['stars']));
		} else {
			$options = Set::merge($options, array('stars' => 5));
		}

		if (isset($options['title'])) {
			$title = array_combine(range(1, count($options['title'])), array_values($options['title']));
		} else {
			$title = array(1 => 'bad', 'good enough', 'good', 'awesome', 'amazing');
		}

		$titleCount = count($title);
		if ($titleCount != $options['stars']) {
			return __d('givrate', 'Star title is not the same as the value star');
		}

		$stars = null;
		for ($i = 1; $i <= $options['stars']; $i++) {
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
$(window).load(Givrate.Ratings.list);
EOF;
		}

		$stars = $this->Html->div('stars', $this->Html->tag('ul', $stars, array('class' => 'rating')));
		$this->Js->buffer($script);
		return $stars;
	}

}
