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
		'userId' => $model['Model']['user_id']
	);
If using userId options. Givrate will be processing point for users


Example
--------

* Givrate::star (for rating stars)

	$this->Givrate->star($model['Token'], array(
		'userId' => $model['Model']['user_id']
	));




* Givrate::displayPoint (for displaying point of rating or vote)

$status: switch `rating` or `vote`.

$options:

ratelink : display rating action link. Default false

votelink : display vote action link.  Default false

	$this->Givrate->displayPoint($model['Token'], 'rating', array(
		'userId' => $model['Model']['user_id'],
		'ratelink' => true
	));



* Givrate::vote

	$this->Givrate->vote($model['Token'], array(
		'userId' => $model['Model']['user_id']
	));


Givrate Components
-------------------
You can use Givrate::Components if you want to make custom ratings / vote from your controller.
Put this in your controller:

Public $components = array(
	'Givrate.Givrate'
);


Manually Generate Token by TokenShell
-------------------------------------
You can use TokenShell for generate tokens according to your model / foreignKey.

Usage:
```bash
./Console/cake givrate.token generate modelName plugin pluginName (id) (length)
```
Optional:<br>
**id** : generate token according to foreignKey ID<br>
**length** : length token. Default is 5


Note:
-----
I still consider this plugin as very unstable

Good luck and have fun
-- kahitam --
