<?php
/**
 *
 * @package Topic Age Warning Extension
 * @copyright (c) 2015 david63
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace david63\topicagewarning\acp;

class topicagewarning_info
{
	public function module()
	{
		return [
			'filename'	=> '\david63\topicagewarning\acp\userranks_module',
			'title' 	=> 'TOPIC_AGE_WARNING',
			'modes' 	=> [
				'main' => ['title' => 'TAW_SETTINGS', 'auth' => 'ext_david63/topicagewarning && acl_a_board', 'cat' => ['TOPIC_AGE_WARNING']],
			],
		];
	}
}
