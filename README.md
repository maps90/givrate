Rating / Vote Plugin for Croogo 1.5
-------------------------------------------------

A simple rating / vote point with tokenable, and can be use for any plugins

Credits to
----------
* Rachman Chavik for TokenableBehavior at cholesterol Repo.

Requirements
------------

- CakePHP > 2.x
- Croogo 1.5


Croogo 1.5
----------

Activate the plugin via the admin beckend, or via CLI:

Console/cake ext activate plugin Givrate

- Create the schema
- Use behaviors Givrate.Tokenable & Givrate.Giveme in target Model


Givrate Settings
----------------

Configure `Givrate.vote_approved`: value check vote number.
* You can use 1 or more number for checking.


Quickstar Guide
---------------

use Givrate::helpers:

$options = array(
	'userId' => $Model['Model']['user_id']
);
`If using userId options. Givrate will be processing point for users`


Example
--------

* Givrate::star (for rating stars)

	$this->Givrate->star($Model['Token']['token'], $options);


* Givrate::displayPoint (for displaying point of rating or vote)

@type : switch `rating` or `vote`

	$this->Givrate->displayPoint($Model['Token']['token'], 'rating');


* Givrate::vote

	$this->Givrate->vote($Model['Token']['token'], $options);


Note: I still consider this plugin as very unstable

Good luck and have fun
-- kahitam --
