<?php
defined('BASEPATH') OR exit('No direct script access allowed');

echo display_html_content(element('headercontent', element('group', $view)));

if (element('board_list', $view)) {
	foreach (element('board_list', $view) as $key => $board) {
		$config = array(
			'skin' => 'mobile',
			'brd_id' => element('brd_id', $board),
			'limit' => 5,
			'length' => 25,
			'is_gallery' => '',
			'image_width' => '',
			'image_height' => '',
			'cache_minute' => 1,
		);
		echo $this->board->latest($config);
	}
}

echo display_html_content(element('footercontent', element('group', $view)));