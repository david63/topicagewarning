<?php
/**
*
* @package Topic Age Warning Extension
* @copyright (c) 2015 david63
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace david63\topicagewarning\controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use phpbb\config\config;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;
use phpbb\language\language;
use david63\topicagewarning\ext;

/**
* Admin controller
*/
class admin_controller implements admin_interface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\request\request */
	protected $request;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\user */
	protected $user;

	/** @var ContainerInterface */
	protected $container;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var string Custom form action */
	protected $u_action;

	/**
	* Constructor for admin controller
	*
	* @param \phpbb\config\config		$config		Config object
	* @param \phpbb\request\request		$request	Request object
	* @param \phpbb\template\template	$template	Template object
	* @param \phpbb\user				$user		User object
	* @param ContainerInterface			$container	Service container interface
	* @param phpbb\language\language	$language
	* @access public
	*/
	public function __construct(config $config, request $request, template $template, user $user, ContainerInterface $container, language $language)
	{
		$this->config		= $config;
		$this->request		= $request;
		$this->template		= $template;
		$this->user			= $user;
		$this->container	= $container;
		$this->language		= $language;
	}

	/**
	* Display the options a user can configure for this extension
	*
	* @return null
	* @access public
	*/
	public function display_options()
	{
		// Add the language files
		$this->language->add_lang('acp_topicagewarning', 'david63/topicagewarning');

		// Create a form key for preventing CSRF attacks
		$form_key = 'topicagewarning';
		add_form_key($form_key);

		// Start initial var setup
		$all_forums			= $this->request->variable('taw_all_forums', 0);
		$selected_forums	= $this->request->variable('taw_forums', array(0));
		$submit				= ($this->request->is_set_post('submit')) ? true : false;

		$this->config_text	= $this->container->get('config_text');

		// Is the form being submitted
		if ($submit)
		{
			// Is the submitted form is valid
			if (!check_form_key($form_key))
			{
				trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			// Ensure that something is selected
			if ($all_forums == 0 && empty($selected_forums))
			{
				trigger_error($this->language->lang('NO_TAW_FORUMS') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			// If no errors, process the form data
			// Set the options the user configured
			$this->set_options();

			// Add option settings change action to the admin log
			$phpbb_log = $this->container->get('log');
			$phpbb_log->add('admin', $this->user->data['user_id'], $this->user->ip, 'TAW_LOG');

			// Option settings have been updated and logged
			// Confirm this to the user and provide link back to previous page
			trigger_error($this->language->lang('CONFIG_UPDATED') . adm_back_link($this->u_action));
		}

		// Build the forum selection list
		$forum_list	= make_forum_select(false, false, true, true, false, false, true);
		$taw_fora	= $this->config_text->get_array(array('taw_forums'));
		$taw_forums = (!empty($taw_fora['taw_forums'])) ? json_decode($taw_fora['taw_forums']) : array();

		$s_forum_options = '';

		foreach ($forum_list as $f_id => $f_row)
		{
			$s_forum_options .= '<option value="' . $f_id . '"' . ((in_array($f_id, $taw_forums)) ? ' selected="selected"' : '') . (($f_row['disabled']) ? ' disabled="disabled" class="disabled-option"' : '') . '>' . $f_row['padding'] . $f_row['forum_name'] . '</option>';
 		}

		// Template vars for header panel
		$this->template->assign_vars(array(
			'HEAD_TITLE'		=> $this->language->lang('TOPIC_AGE_WARNING'),
			'HEAD_DESCRIPTION'	=> $this->language->lang('TOPIC_AGE_WARNING_EXPLAIN'),

			'VERSION_NUMBER'	=> ext::TAW_VERSION,
		));

		// Set output vars for display in the template
		$this->template->assign_vars(array(
			'S_FORUM_OPTIONS'		=> $s_forum_options,

			'TAW_ADMIN_EXEMPT'		=> isset($this->config['taw_admin_exempt']) ? $this->config['taw_admin_exempt'] : '',
			'TAW_ALL_FORUMS'		=> isset($this->config['taw_all_forums']) ? $this->config['taw_all_forums'] : '',
			'TAW_AUTHOR_EXEMPT'		=> isset($this->config['taw_author_exempt']) ? $this->config['taw_author_exempt'] : '',
			'TAW_INTERVAL' 			=> isset($this->config['taw_interval']) ? $this->config['taw_interval'] : '',
			'TAW_INTERVAL_TYPE'		=> $this->get_taw_interval_type(),
			'TAW_LAST_POST'			=> isset($this->config['taw_last_post']) ? $this->config['taw_last_post'] : '',
			'TAW_LOCK'				=> isset($this->config['taw_lock']) ? $this->config['taw_lock'] : '',
			'TAW_MESSAGE_BOTTOM'	=> isset($this->config['taw_message_bottom']) ? $this->config['taw_message_bottom'] : '',
			'TAW_MESSAGE_TOP'		=> isset($this->config['taw_message_top']) ? $this->config['taw_message_top'] : '',
			'TAW_MOD_EXEMPT'		=> isset($this->config['taw_mod_exempt']) ? $this->config['taw_mod_exempt'] : '',
			'TAW_QUICKREPLY' 		=> isset($this->config['taw_quickreply']) ? $this->config['taw_quickreply'] : '',
			'TAW_SHOW_LOCKED' 		=> isset($this->config['taw_show_locked']) ? $this->config['taw_show_locked'] : '',

			'U_ACTION' 				=> $this->u_action,
		));
	}

	/**
	* Set the options a user can configure
	*
	* @return null
	* @access protected
	*/
	protected function set_options()
	{
		$this->config->set('taw_all_forums', $this->request->variable('taw_all_forums', 0));
		$this->config->set('taw_admin_exempt', $this->request->variable('taw_admin_exempt', 0));
		$this->config->set('taw_author_exempt', $this->request->variable('taw_author_exempt', 0));
		$this->config->set('taw_interval', $this->request->variable('taw_interval', 0));
		$this->config->set('taw_interval_type', $this->request->variable('taw_interval_type', ''));
		$this->config->set('taw_last_post', $this->request->variable('taw_last_post', 0));
		$this->config->set('taw_lock', $this->request->variable('taw_lock', 0));
		$this->config->set('taw_message_bottom', $this->request->variable('taw_message_bottom', 0));
		$this->config->set('taw_message_top', $this->request->variable('taw_message_top', 0));
		$this->config->set('taw_mod_exempt', $this->request->variable('taw_mod_exempt', 0));
		$this->config->set('taw_quickreply', $this->request->variable('taw_quickreply', 0));
		$this->config->set('taw_show_locked', $this->request->variable('taw_show_locked', 0));

		$this->config_text->set_array(array('taw_forums' => ($this->request->variable('taw_all_forums', '')) ? '' : json_encode($this->request->variable('taw_forums', array(0)))));
	}

	/**
	* Topic Age Warning interval select
	*
	* @return select
	* @access protected
	*/
	protected function get_taw_interval_type()
	{
		$s_taw_type = '';

		$types = array(
			'd' => $this->language->lang('TAW_DAYS'),
			'm' => $this->language->lang('TAW_MONTHS'),
			'y' => $this->language->lang('TAW_YEARS')
		);

		foreach ($types as $type => $lang)
		{
			$selected	= ($this->config['taw_interval_type'] == $type) ? ' selected="selected"' : '';
			$s_taw_type .= '<option value="' . $type . '"' . $selected . '>' . $this->language->lang($lang);
			$s_taw_type .= '</option>';
		}

		return '<select name="taw_interval_type" id="taw_interval_type">' . $s_taw_type . '</select>';
	}

	/**
	* Set page url
	*
	* @param string $u_action Custom form action
	* @return null
	* @access public
	*/
	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}
}
