<div class="rateCalculates index">
	<h2><?php echo __('Rate Calculates');?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>
			<th><?php echo $this->Paginator->sort('model');?></th>
			<th><?php echo $this->Paginator->sort('foreign_key');?></th>
			<th><?php echo $this->Paginator->sort('count');?></th>
			<th><?php echo $this->Paginator->sort('sum');?></th>
			<th><?php echo $this->Paginator->sort('avg');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			<th><?php echo $this->Paginator->sort('modified');?></th>
			<th class="actions"><?php echo __('Actions');?></th>
	</tr>
	<?php
	foreach ($rateCalculates as $rateCalculate): ?>
	<tr>
		<td><?php echo h($rateCalculate['RateCalculate']['id']); ?>&nbsp;</td>
		<td><?php echo h($rateCalculate['RateCalculate']['model']); ?>&nbsp;</td>
		<td><?php echo h($rateCalculate['RateCalculate']['foreign_key']); ?>&nbsp;</td>
		<td><?php echo h($rateCalculate['RateCalculate']['count']); ?>&nbsp;</td>
		<td><?php echo h($rateCalculate['RateCalculate']['sum']); ?>&nbsp;</td>
		<td><?php echo h($rateCalculate['RateCalculate']['avg']); ?>&nbsp;</td>
		<td><?php echo h($rateCalculate['RateCalculate']['created']); ?>&nbsp;</td>
		<td><?php echo h($rateCalculate['RateCalculate']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $rateCalculate['RateCalculate']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $rateCalculate['RateCalculate']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $rateCalculate['RateCalculate']['id']), null, __('Are you sure you want to delete # %s?', $rateCalculate['RateCalculate']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Rate Calculate'), array('action' => 'add')); ?></li>
	</ul>
</div>
