<?php
/**
 *
 * @package Topic Age Warning Extension
 * @copyright (c) 2015 david63
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace david63\topicagewarning\acp;

class topicagewarning_module
{
	public $u_action;

	public function main($id, $mode)
	{
		global $phpbb_container, $user;

		$this->tpl_name   = 'topic_age_warning';
		$this->page_title = $user->lang('TOPIC_AGE_WARNING');

		// Get an instance of the admin controller
		$admin_controller = $phpbb_container->get('david63.topicagewarning.admin.controller');

		// Make the $u_action url available in the admin controller
		$admin_controller->set_page_url($this->u_action);

		$admin_controller->display_options();
	}
}
