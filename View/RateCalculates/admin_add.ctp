<div class="rateCalculates form">
<?php echo $this->Form->create('RateCalculate');?>
	<fieldset>
		<legend><?php echo __('Admin Add Rate Calculate'); ?></legend>
	<?php
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

		<li><?php echo $this->Html->link(__('List Rate Calculates'), array('action' => 'index'));?></li>
	</ul>
</div>
