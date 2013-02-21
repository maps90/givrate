<?php

Croogo::hookRoutes('Givrate');
Croogo::hookHelper('*', 'Givrate.Givrate');


CroogoNav::add('extensions.children.givrate', array(
	'title' => 'Givrate',
	'url' => '#',
	'children' => array(
		'user_points' => array(
			'title' => __d('givrate', 'User Points'),
			'url' => array(
				'plugin' => 'givrate',
				'admin' => true,
				'controller' => 'user_points',
				'action' => 'index'
			)
		),
		'rate_calculates' => array(
			'title' => __d('givrate', 'Rating Calculate'),
			'url' => array(
				'plugin' => 'givrate',
				'admin' => true,
				'controller' => 'rate_calculates',
				'action' => 'index'
			)
		),
		'settings' => array(
			'title' => __d('givrate', 'Givrate settings'),
			'url' => array(
				'plugin' => 'settings',
				'admin' => true,
				'controller' => 'settings',
				'action' => 'prefix',
				'Givrate'
			)
		)
	)
));
