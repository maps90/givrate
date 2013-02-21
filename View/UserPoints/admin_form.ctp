<?php

$this->extend('/Common/admin_edit');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb('Givrate')
	->addCrumb(__d('givrate', 'UserPoints'), array(
		'plugin' => 'givrate', 'controller' => 'user_points', 'action' => 'index'
	));
?>

<?php
$readonly = array('readonly' => 'readonly');
echo $this->Form->create('Album', array('url' => $this->here));
?>

<div class="row-fluid">
	<div class="span8">

		<ul class="nav nav-tabs">
			<?php
				echo $this->Croogo->adminTab(__('User Point'), '#userpoint-main');
				echo $this->Layout->adminTabs();
			?>
		</ul>

		<div class="tab-content">
			<div id="userpoint-main" class="tab-pane">
			<?php
				$this->Form->inputDefaults(array(
					'label' => false,
					'class' => 'span10',
				));
				echo $this->Form->input('UserPoint.id');
				echo $this->Form->input('UserPoint.user_id', $readonly);
				echo $this->Form->input('UserPoint.raters', $readonly);
				echo $this->Form->input('UserPoint.points', array(
					'placeholder' => __d('givrate', 'Point')
				));
				echo $this->Form->input('UserPoint.avg', $readonly);
				echo $this->Form->input('UserPoint.type', $readonly);
				echo $this->Form->input('UserPoint.point_date', $readonly);
			?>
			</div>

			<?php echo $this->Layout->adminTabs(); ?>
		</div>
	</div>

	<div class="span4">
	<?php
		echo $this->Html->beginBox(__('Publishing')) .
			$this->Form->button(__('Save')) .
			$this->Html->link(__('Cancel'), $this->request->referer(), array(
				'button' => 'danger',
			));
		echo $this->Html->endBox();
	?>
	</div>
</div>
<?php echo $this->Form->end(); ?>
