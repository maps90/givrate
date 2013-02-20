<?php
if (empty($modelName)) {
	$modelName = Inflector::singularize($this->name);
}

$Rating = ClassRegistry::init('Givrate.Rating');
$isRated = $Rating->isRated($modelName['Token']['token'], $user_id);
?>

<div class='rating-el'>
<?php
if (!empty($isRated)) {
//	echo $this->Html->tag('span', 'Rated', array('class' => 'rated'));
} else {
	echo $this->Givrate->star($modelName[$modelName]['id'], $modelName['Token']['token']);
}
?>
</div>
