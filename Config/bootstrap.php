<?php

Croogo::hookRoutes('Givrate');
Croogo::hookHelper('*', 'Givrate.Givrate');
Croogo::hookComponent('*', 'Givrate.Ratings');
