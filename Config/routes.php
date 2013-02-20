<?php

CroogoRouter::connect('/rate/:action',
	array(
		'plugin' => 'givrate',
		'controller' => 'ratings',
		)
	);
