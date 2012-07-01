<div class="rateCalculates form">
<?php echo $this->Form->create('RateCalculate');?>
	<fieldset>
		<legend><?php echo __('Admin Edit Rate Calculate'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('model');
		echo $this->Form->input('foreign_key');
		echo $this->Form->input('count');
		echo $this->Form->input('sum');
		echo $this->Form->input('avg');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit'));?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('RateCalculate.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('RateCalculate.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Rate Calculates'), array('action' => 'index'));?></li>
	</ul>
</div>
