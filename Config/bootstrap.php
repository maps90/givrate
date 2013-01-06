<?php

Configure::load('Givrate.givrate');

Croogo::hookRoutes('Givrate');
Croogo::hookHelper('*', 'Givrate.Givrate');
