<?php

CroogoRouter::connect('/give-rate/:alias/:rate/:rating/:user/*',
	array(
		'plugin' => 'givrate',
		'controller' => 'ratings',
		'action' => 'submit'
		)
	);
