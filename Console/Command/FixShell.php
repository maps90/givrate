<?php

/**
 * Fix shell for Rating & Rate Calculates
 */

App::uses('AppShell', 'Console/Command');

class FixShell extends AppShell {

	public $uses = array('Rating','RateCalculate');

	public function getOptionParser() {
		return parent::getOptionParser()
			->description('Givrate Fixing')
			->addSubCommand('owner_ratecals', array(
				'help' => 'Fix empty user_id on rate_calculate'
			));
	}

/**
 * Fix empty user_id on rate_calculates table
 */
	public function owner_ratecals() {
		$db = $this->RateCalculate->getDataSource();
		$rateCals = $this->RateCalculate->find('all', array(
			'recursive' => -1,
			'conditions' => array(
				'RateCalculate.user_id' => NULL
			),
		));
		if (empty($rateCals)) {
			$this->out('All fixed');
			return false;
		}
		$processed = 0;
		$failed = 0;
		$db->begin();
		foreach ($rateCals as $rateCal) {
			$rating = $this->Rating->find('first', array(
				'recursive' => -1,
				'conditions' => array(
					'Rating.foreign_key' => $rateCal['RateCalculate']['foreign_key'],
				)
			));
			if (!empty($rating['Rating']['owner_id'])) {
				$this->RateCalculate->id = $rateCal['RateCalculate']['id'];
				$this->RateCalculate->saveField('user_id', $rating['Rating']['owner_id']);
				$this->out(sprintf('Recover Rate Calculate id: %d', $rateCal['RateCalculate']['id']));
				$processed++;
			} else {
				$this->out(sprintf('Empty owner_id on Rating.id: %d', $rating['Rating']['id']));
				$failed++;
			}
		}
		$db->commit();
		$this->out(sprintf('Total recover: %d', $processed));
		$this->out(sprintf('Total failed: %d', $failed));
	}
}
