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
 *
 * Display point Rating or Vote
 * @token: value
 * @options:
 *	- ratelink: display rating action link. Default false.
 *	- votelink: display vote action link. Default false.
 *
 * Array @options can be combined with the @options Givrate::star and Givrate::vote
 */
	public function displayPoint($token, $type, $options = array()) {
		if (empty($token) || empty($type)) {
			return __d('givrate', 'Empty Token or Type.');
		}
		$options = Set::merge(array(
			'ratelink' => false,
			'votelink' => false,
		), $options);

		$RateCalculate = ClassRegistry::init('Givrate.RateCalculate');
		$result = $RateCalculate->getPoint($token, $type, array('recursive' => -1));

		switch($type) {
			case 'rating':
				$field = 'avg';
				if ($options['ratelink'] == true) {
					$link = $this->star($token, $options);
				} else {
					$link = '';
				}
				$point = number_format($result['RateCalculate'][$field], 1);
			break;
			case 'vote':
				$field = 'point';
				if ($options['votelink'] == true) {
					$link = $this->vote($token, $options);
				} else {
					$link = '';
				}
				$point = $result['RateCalculate'][$field];
			break;
			default:
				$field = 'avg';
				$link = '';
				$point = number_format($result['RateCalculate'][$field], 1);
			break;
		}
		unset($options['ratelink']);
		unset($options['votelink']);

		$point = empty($result['RateCalculate'][$field]) ? 0 : $point;
		$point = $this->Html->div('span7', $this->Html->div('avg', $this->Html->tag('span', $point)) . $link);
		return $this->Html->div('row-fluid', $point);
	}

/*
 * Givrate::star
 *
 * @token: value
 * @options:
 *	- userId : owner id for user point. If empty will not creating for user point.
 *	- stars : value of the stars.
 *	- title : tooltip title for each stars.
 */
	public function star($token, $options = array()) {
		if (empty($token)) {
			return __d('givrate', 'Empty token!');
		}

		$js = 'javascript:;';
		$options = Set::merge(array(
			'class' => 'rating',
			'link' => true,
			'value' => 0,
			'stars' => 5,
			'userId' => '',
		), $options);

		if (!empty($options['userId'])) {
			$options = Set::merge($options, array('data-id' => 's'.$options['userId']));
			unset($options['userId']);
		}

		if (isset($options['title'])) {
			if ($options['title'] != false) {
				$title = array_combine(range(1, count($options['title'])), array_values($options['title']));

				$titleCount = count($title);
				if ($titleCount != $options['stars']) {
					return __d('givrate', 'Value star title is not the same as the value stars');
				}
			} else {
				$title = array();
			}
		} else {
			$title = array(1 => 'bad', 'good enough', 'good', 'awesome', 'amazing');
		}

		$stars = null;
		for ($i = 1; $i <= $options['stars']; $i++) {
			$link = null;
			$options = Set::merge($options, array(
				'class' => 'rate-link',
				'data-token' => $token,
				'data-rating' => 's'.$i,
				'rtype' => 'rating',
				'escape' => false,
			));
			if ($title != array()) {
				$options = Set::merge($options, array('title' => $title[$i]));
			}
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

/*
 * Givrate::vote
 *
 * @token: value
 * @options:
 *	- img: custom image. Default null
 *	- height: height for image if "img" options is not empty.
 *	- width: width for image if "img" options is not empty.
 *	- alt: alt for image if "img" options is not empty.
 *	- text: Title for vote link if don`t want to use image.
 */
	public function vote($token, $options = array()) {
		if (empty($token)) {
			return __d('givrate', 'Empty Token!');
		}
		$js = 'javascript:;';
		$options = Set::merge(array(
			'class' => 'voted',
			'data-token' => $token,
			'vote' => '1',
			'data-type' => 'vote',
			'img' => '',
			'width' => '',
			'height' => '',
			'alt' => '',
			'escape' => false,
		), $options);

		$options = Set::merge($options, array('data-vote' => 's'.$options['vote']));
		unset($options['vote']);

		if (isset($options['userId'])) {
			$options = Set::merge($options, array('data-id' => 's'.$options['userId']));
			unset($options['userId']);
		}

		if (!empty($options['img'])) {
			$value = $this->Html->image($options['img'], array(
				'width' => $options['width'],
				'height' => $options['height'],
				'alt' => $options['alt']
			));
			unset($options['img']);
			unset($options['height']);
			unset($options['width']);
			unset($options['alt']);
		} else {
			$value = 'vote';
		}

		if (isset($options['title'])) {
			$value = $options['title'];
		}

		$link = $this->Html->link($value, $js, $options);
$script =<<<EOF
$('body').on('click', '.voted', Givrate.Ratings.vote);
EOF;
		$this->Js->buffer($script);
		return $this->Html->div('vote', $this->Html->tag('span', $link));
	}

}
