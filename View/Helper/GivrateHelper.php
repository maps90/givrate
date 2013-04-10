<?php
App::uses('PointUtil', 'Givrate.Utility');

class GivrateHelper extends AppHelper {

	public $helpers = array('Html', 'Form', 'Js', 'Session');

	protected $_Rating = null;
	protected $_RateCalculate = null;

	public function beforeRender() {
		$params = $this->_View->params;
		if (isset($params['isAjax']) && $params['isAjax'] === true) {
			return;
		}
		if (isset($params['admin']) && $params['admin'] === true) {
			return;
		}
		$this->_Rating = ClassRegistry::init('Givrate.Rating');
		$this->_RateCalculate = ClassRegistry::init('Givrate.RateCalculate');
		$this->Point = new PointUtil;

		$this->Html->css('/givrate/css/style', null, array('inline' => false));
		$this->Html->script(array(
			'/givrate/js/givrate'), array('inline' => false
		));
	}

/*
 * Givrate::displayPoint
 *
 * Display point Rating or Vote
 * @token: array
 * @status: value 'rating' / 'vote'
 * @options:
 *	- ratelink: display rating action link. Default false.
 *	- votelink: display vote action link. Default false.
 *	- type: value of your type point. Default 'default'
 *  - display: display of your point type. Default 'rating'.
 *
 * Array @options can be combined with the @options Givrate::star and Givrate::vote
 */
	public function displayPoint($token, $status, $options = array()) {
		if (empty($token['id']) || empty($status)) {
			return __d('givrate', 'Empty Token or Type.');
		}
		$options = Set::merge(array(
			'ratelink' => false,
			'votelink' => false,
		), $options);

		if (isset($options['type'])) {
			$type = $options['type'];
		} else {
			$type = 'default';
		}
		unset($options['type']);

		if (isset($options['display'])) {
			$display = $options['display'];
		}
		unset($options['display']);

		$result = $this->_RateCalculate->getPoint($token['token'], $type, $status, array('recursive' => -1));
		switch($display) {
			case 'vote':
				$field = 'point';
				if ($options['votelink'] == true) {
					$link = $this->vote($token, $options);
				} else {
					$link = '';
				}
				$point = empty($result['RateCalculate'][$field]) ? 0 : $result['RateCalculate'][$field];
			break;
			case 'rating':
			default:
				$field = 'avg';
				if ($options['ratelink'] == true) {
					$link = $this->star($token, $options);
				} else {
					$link = '';
				}
				$point = empty($result['RateCalculate'][$field]) ? 0 : number_format($result['RateCalculate'][$field], 1);
			break;
		}
		unset($options['ratelink']);
		unset($options['votelink']);

		$point = empty($result['RateCalculate'][$field]) ? 0 : $point;
		$point = $this->Html->div('avg', $point . $link);
		return $point;
	}

/*
 * Givrate::star
 *
 * @token: array
 * @options:
 *	- userId : owner id for user point. If empty will not creating for user point.
 *	- stars : value of the stars.
 *	- title : tooltip title for each stars.
 */
	public function star($token, $options = array()) {
		if (empty($token['id'])) {
			return __d('givrate', 'Empty token!');
		}

		$id = $token['foreign_key'];
		$recursive = array('recursive' => -1);
		$js = 'javascript:void(0);';
		$options = Set::merge(array(
			'class' => 'rating',
			'link' => true,
			'value' => 0,
			'stars' => 5,
			'type' => 'default',
			'userId' => '',
		), $options);

		if (!empty($options['type'])) {
			$options = Set::merge(array('type' => $options['type']), $options);
		}

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
				'class' => 'rate-link-'.$id,
				'data-token' => $token['token'],
				'data-rating' => 's'.$i,
				'status' => 'rating',
				'escape' => false,
			));
			if ($title != array()) {
				$options = Set::merge($options, array('title' => $title[$i]));
			}
			$link = $this->Html->link('&nbsp;', $js, $options);
			$stars .= $this->Html->tag('li', $link, array('class' => 'star' . $i));
$script =<<<EOF
$('body').on('click', '.rate-link-$id', Givrate.Ratings.star);
$(window).load(Givrate.Ratings.list($id));
EOF;
		}

		$authId = $this->Session->read('Auth.User.id');
		$checking = $this->_Rating->checking($token['token'], $authId, 'rating', $recursive);
		if (!empty($checking)) {
			$result = $this->_RateCalculate->getPoint($token['token'], 'rating', $recursive);
			$currentStars = $this->Point->currentStars($result['RateCalculate']['avg'], $result['RateCalculate']['point'], $result['RateCalculate']['count']);
			$maxWidth = 18 * $options['stars'];
$script =<<<EOF
$('div.stars').css('max-width', $maxWidth);
$('ul.rating').css({'width': $currentStars + 'px', 'background-position' : '0px 72px'});
$('.stars .rating li a').css({'display': 'none'});
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
		if (empty($token['id'])) {
			return __d('givrate', 'Empty Token!');
		}
		$id = $token['foreign_key'];
		$js = 'javascript:void(0);';
		$options = Set::merge(array(
			'class' => 'voted-' . $id,
			'data-token' => $token['token'],
			'vote' => '1',
			'data-type' => 'vote',
			'title' => 'vote',
			'img' => '',
			'width' => '',
			'height' => '',
			'alt' => '',
			'escape' => false,
		), $options);

		if (!empty($options['status'])) {
			$options = Set::merge(array('data-status' => $options['status']), $options);
		} else {
			$options['data-status'] = 'default';
		}

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

		} else {
			$value = 'vote';
		}

		if (isset($options['text'])) {
			$value = $options['text'];
		}
		unset($options['img']);
		unset($options['height']);
		unset($options['width']);
		unset($options['alt']);
		unset($options['text']);
		if (isset($options[0])) {
			unset($options[0]);
		}

		$link = $this->Html->link($value, $js, $options);
$script =<<<EOF
$('body').on('click', '.voted-$id', Givrate.Ratings.vote);
EOF;
		$this->Js->buffer($script);
		return $this->Html->div('vote', $this->Html->tag('span', $link));
	}

}
