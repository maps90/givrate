<?php

$this->extend('/Common/admin_index');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__('Givrate'))
	->addCrumb(__('UserVotes'), $this->here);
?>

<?php
$script =<<<EOF
$('#UserVoteDate').datepicker({
	format: "yyyy-mm-dd",
});
EOF;
$this->Js->buffer($script);
?>

<div class="row-fluid">
	<div class="span12">
		<table class="table table-striped">
		<?php
			$tableHeaders = $this->Html->tableHeaders(array(
				$this->Paginator->sort('id'),
				__d('givrate', 'Users'),
				$this->Paginator->sort('foreign_key'),
				$this->Paginator->sort('count'),
				$this->Paginator->sort('vote_date')
			));
			echo $tableHeaders;

			$rows = array();
			foreach ($userVotes as $userVote) {
				$rows[] = array(
					$userVote['UserVote']['id'],
					$this->Html->link($userVote['User']['name'],
						array('plugin' => 'users', 'controller' => 'users', 'action' => 'view', $userVote['UserVote']['user_id'])
					),
					$userVote['UserVote']['foreign_key'],
					$userVote['UserVote']['count'],
					$userVote['UserVote']['vote_date']
				);
			}

			echo $this->Html->tableCells($rows);
			echo $tableHeaders;
		?>
		</table>
	</div>
</div>
