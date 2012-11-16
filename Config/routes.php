<?php

CroogoRouter::connect('/rate/*',
	array(
		'plugin' => 'givrate',
		'controller' => 'ratings',
		'action' => 'submit'
		)
	);
