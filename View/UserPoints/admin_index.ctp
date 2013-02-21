<?php

$this->extend('/Common/admin_index');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__('Givrate'))
	->addCrumb(__('UserPoints'), $this->here);
?>

<div class="row-fluid">
	<div class="span12">
		<table class="table table-striped">
		<?php
			$tableHeaders = $this->Html->tableHeaders(array(
				$this->Paginator->sort('id'),
				__d('givrate', 'Users'),
				$this->Paginator->sort('raters'),
				$this->Paginator->sort('points'),
				$this->Paginator->sort('avg'),
				$this->Paginator->sort('type'),
				$this->Paginator->sort('point_date'),
			));
			echo $tableHeaders;

			$rows = array();
			foreach ($userPoints as $userPoint) {
				$rows[] = array(
					$userPoint['UserPoint']['id'],
					$this->Html->link($userPoint['User']['name'],
						array('controller' => 'users', 'action' => 'view', $userPoint['UserPoint']['user_id'])
					),
					$userPoint['UserPoint']['raters'],
					$userPoint['UserPoint']['points'],
					number_format($userPoint['UserPoint']['avg'], 1) . '%',
					$userPoint['UserPoint']['type'],
					$userPoint['UserPoint']['point_date'],
				);
			}

			echo $this->Html->tableCells($rows);
			echo $tableHeaders;
		?>
		</table>
	</div>
</div>
