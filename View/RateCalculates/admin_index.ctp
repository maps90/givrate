<?php

$this->extend('/Common/admin_index');

$this->Html
	->addCrumb('', '/admin', array('icon' => 'home'))
	->addCrumb(__('Givrate'))
	->addCrumb(__('RateCalculates'), $this->here);
?>

<div class="row-fluid">
	<div class="span12">
		<table class="table table-striped">
		<?php
			$tableHeaders = $this->Html->tableHeaders(array(
				$this->Paginator->sort('id'),
				$this->Paginator->sort('model'),
				__d('givrate', 'Foreign Key'),
				$this->Paginator->sort('count'),
				$this->Paginator->sort('point'),
				$this->Paginator->sort('avg'),
				$this->Paginator->sort('type'),
				$this->Paginator->sort('created'),
			));
			echo $tableHeaders;

			$rows = array();
			foreach ($rateCalculates as $rate) {
				$rows[] = array(
					$rate['RateCalculate']['id'],
					$rate['RateCalculate']['model'],
					$rate['RateCalculate']['foreign_key'],
					$rate['RateCalculate']['count'],
					$rate['RateCalculate']['point'],
					number_format($rate['RateCalculate']['avg'], 1) . '%',
					$rate['RateCalculate']['type'],
					$rate['RateCalculate']['created'],
				);
			}

			echo $this->Html->tableCells($rows);
			echo $tableHeaders;
		?>
		</table>
	</div>
</div>
