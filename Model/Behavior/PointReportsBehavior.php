<?php

App::uses('ModelBehavior', 'Model');

class PointReportsBehavior extends ModelBehavior {

/**
 * make range condition
 */
	public function makeRangeCondition(Model $model, $data, $field = null) {
		$range = array();
		foreach ($data as $field => $value) {
			$range[] = $value;
		}
		return $range;
	}
}
