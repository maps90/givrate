<?php
if (empty($related)) return;

$rating_html = '';
foreach ($related as $rating) {
	$rating_html .= $this->element('rate-action', compact('rating'));
}
$result = array(
	'rating' => $rating_html,
);
echo json_encode($result);
