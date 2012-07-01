<div class="rateCalculates view">
<h2><?php  echo __('Rate Calculate');?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($rateCalculate['RateCalculate']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Model'); ?></dt>
		<dd>
			<?php echo h($rateCalculate['RateCalculate']['model']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Foreign Key'); ?></dt>
		<dd>
			<?php echo h($rateCalculate['RateCalculate']['foreign_key']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Count'); ?></dt>
		<dd>
			<?php echo h($rateCalculate['RateCalculate']['count']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Sum'); ?></dt>
		<dd>
			<?php echo h($rateCalculate['RateCalculate']['sum']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Avg'); ?></dt>
		<dd>
			<?php echo h($rateCalculate['RateCalculate']['avg']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($rateCalculate['RateCalculate']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($rateCalculate['RateCalculate']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Rate Calculate'), array('action' => 'edit', $rateCalculate['RateCalculate']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Rate Calculate'), array('action' => 'delete', $rateCalculate['RateCalculate']['id']), null, __('Are you sure you want to delete # %s?', $rateCalculate['RateCalculate']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Rate Calculates'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Rate Calculate'), array('action' => 'add')); ?> </li>
	</ul>
</div>
