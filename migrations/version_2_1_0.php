<?php
/**
 *
 * @package Topic Age Warning Extension
 * @copyright (c) 2015 david63
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace david63\topicagewarning\migrations;

class version_2_1_0 extends \phpbb\db\migration\migration
{
	public function update_data()
	{
		return [
			['config.add', ['taw_admin_exempt', '0']],
			['config.add', ['taw_all_forums', '1']],
			['config.add', ['taw_author_exempt', '0']],
			['config.add', ['taw_interval', '0']],
			['config.add', ['taw_interval_type', 'd']],
			['config.add', ['taw_last_post', '1']],
			['config.add', ['taw_lock', '0']],
			['config.add', ['taw_message_bottom', '0']],
			['config.add', ['taw_message_top', '1']],
			['config.add', ['taw_mod_exempt', '0']],
			['config.add', ['taw_quickreply', '0']],
			['config.add', ['taw_show_locked', '0']],

			['config_text.add', ['taw_forums', '0']], // Use config text here as config field may be too small

			// Add the ACP module
			['module.add', ['acp', 'ACP_CAT_DOT_MODS', 'TOPIC_AGE_WARNING']],

			['module.add', [
				'acp', 'TOPIC_AGE_WARNING', [
					'module_basename' => '\david63\topicagewarning\acp\topicagewarning_module',
					'modes' => ['main'],
				],
			]],
		];
	}
}
