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
		return array(
			array('config.add', array('taw_admin_exempt', '0')),
			array('config.add', array('taw_all_forums', '1')),
			array('config.add', array('taw_author_exempt', '0')),
			array('config.add', array('taw_interval', '0')),
			array('config.add', array('taw_interval_type', 'd')),
			array('config.add', array('taw_last_post', '1')),
			array('config.add', array('taw_lock', '0')),
			array('config.add', array('taw_message_bottom', '0')),
			array('config.add', array('taw_message_top', '1')),
			array('config.add', array('taw_mod_exempt', '0')),
			array('config.add', array('taw_quickreply', '0')),
			array('config.add', array('taw_show_locked', '0')),

			array('config_text.add', array('taw_forums', '0')), // Use config text here as config field may be too small

			// Add the ACP module
			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'TOPIC_AGE_WARNING')),

			array('module.add', array(
				'acp', 'TOPIC_AGE_WARNING', array(
					'module_basename'	=> '\david63\topicagewarning\acp\topicagewarning_module',
					'modes'				=> array('main'),
				),
			)),
		);
	}
}
