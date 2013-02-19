<?php

Croogo::hookRoutes('Givrate');
Croogo::hookHelper('*', 'Givrate.Givrate');


CroogoNav::add('extensions.children.givrate', array(
	'title' => 'Givrate',
	'url' => '#',
	'children' => array(
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
