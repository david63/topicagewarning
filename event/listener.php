<?php
/**
*
* @package Topic Age Warning Extension
* @copyright (c) 2015 david63
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace david63\topicagewarning\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use phpbb\config\config;
use phpbb\template\template;
use phpbb\auth\auth;
use phpbb\user;
use phpbb\db\driver\driver_interface;
use phpbb\language\language;
use phpbb\config\db_text;
use david63\topicagewarning\core\functions;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\config\db_text */
	protected $config_text;

	/** @var \david63\topicagewarning\core\functions */
	protected $functions;

	/** @var string phpBB tables */
	protected $tables;

	/** @var string Custom form action */
	protected $u_action;

	/**
	* Constructor for listener
	*
	* @param \phpbb\config\config						$config			Config object
	* @param \phpbb\template\template					$template		Template object
	* @param \phpbb\auth\auth 							$auth			Auth object
	* @param \phpbb\user								$user			User object
	* @param \phpbb\db\driver\driver_interface			$db				The db connection
	* @param \phpbb\language\language					$language		Language object
	* @param \phpbb\config\db_text      				$config_text	Config text object
	* @param \david63\topicagewarning\core\functions	functions		Functions for the extension
	* @param array										$tables			phpBB db tables
	*
	* @access public
	*/
	public function __construct(config $config, template $template, auth $auth, user $user, driver_interface $db, language $language, db_text $config_text, functions $functions, $tables)
	{
		$this->config		= $config;
		$this->template		= $template;
		$this->auth			= $auth;
		$this->user			= $user;
		$this->db			= $db;
		$this->language		= $language;
		$this->config_text 	= $config_text;
		$this->functions	= $functions;
		$this->tables		= $tables;
	}

	/**
	* Assign functions defined in this class to event listeners in the core
	*
	* @return array
	* @static
	* @access public
	*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.viewtopic_modify_page_title'	=> 'viewtopic_warning_message',
		);
	}

	/**
	* Process the Topic Age Warning Message
	*
	* @param object $event The event object
	* @return null
	* @access public
	*/
	public function viewtopic_warning_message($event)
	{
		$topic_data = $event['topic_data'];
		$taw_fora	= $this->config_text->get_array(array('taw_forums'));
		$taw_forums	= json_decode($taw_fora['taw_forums']);

		// Let's just make sure we need to do some processing
		if (($this->config['taw_all_forums'] || in_array($topic_data['forum_id'], $taw_forums)) && $topic_data['topic_status'] != ITEM_LOCKED && $this->config['taw_interval'] > 0)
		{
			$this->language->add_lang('common', $this->functions->get_ext_namespace());

			$time			= time();
			$this->day		= 60 * 60 * 24;				// aka 86,400 seconds. Broken into seconds * minutes * hours = 1 day.
			$this->week		= $this->day * 7;			// 7 days in a week
			$this->month	= $this->day * 30.436875;	//30.436875 = average length of a month in days (according to wikipedia)
			$this->year		= $this->month * 12;		// 12 months in a year

			$forum_id			= $topic_data['forum_id'];
			$check_time			= $topic_data[($this->config['taw_last_post']) ? 'topic_last_post_time' : 'topic_time'];
			$current_interval	= $time - $check_time;
			$author_exempt		= $this->validate_access($topic_data['topic_poster'], $this->user->data['user_id']);
			$last_post			= $this->config['taw_last_post'];
			$interval			= 0;

			// Get the time/date difference
			$this->pretty_interval = $this->compare_dates($check_time, $time);

			// Get the interval type
			switch ($this->config['taw_interval_type'])
			{
				case 'y': // year
					$interval = $this->config['taw_interval'] * $this->year;
				break;

				case 'm': // month
					$interval = $this->config['taw_interval'] * $this->month;
				break;

				default: // day
					$interval = $this->config['taw_interval'] * $this->day;
				break;
			}

			// Do we need to output the message?
			if (!$author_exempt && $interval && ($current_interval > $interval))
			{
				$langkey = 'TOPIC_AGE_WARNING';

				// If they want the topic to be locked, lock it.
				if ($this->config['taw_lock'])
				{
					$this->db->sql_query(
						'UPDATE ' . $this->tables['topics'] . '
						SET topic_status = ' . ITEM_LOCKED . '
						WHERE topic_id = '  . (int) $topic_data['topic_id'] . '
						AND topic_moved_id = 0'
					);

					$langkey .= '_LOCK';
				}

				if ($this->config['taw_show_locked'])
				{
					$this->template->assign_vars(array(
						'S_IS_LOCKED'			=> true,
						'U_POST_REPLY_TOPIC'	=> '',
					));
				}

				// Set output vars for display in the template
				$this->template->assign_vars(array(
					'S_DISPLAY_REPLY_INFO'	=> $this->config['taw_show_locked'],
					'S_MESSAGE_BOTTOM'		=> $this->config['taw_message_bottom'],
					'S_MESSAGE_TOP'			=> $this->config['taw_message_top'],
					'S_QUICK_REPLY'			=> $this->config['taw_quickreply'],
					'S_TOPIC_AGE_WARNING'	=> true,

					'TOPIC_AGE_WARNING'		=> $this->language->lang($langkey, $this->pretty_interval),
				));
			}
		}
	}

	/**
	* Check if the user has access to post
	*
	* @return access
	* @access protected
	*/
	protected function validate_access($topic_poster, $user_id)
	{
		$access = false;

		if ($this->config['taw_author_exempt'] && $topic_poster === $user_id)
		{
			$access = true;
		}

		if ($this->config['taw_admin_exempt'] && $this->auth->acl_get('a_'))
		{
			$access = true;
		}

		if ($this->config['taw_mod_exempt'] && $this->auth->acl_get('m_'))
		{
			$access = true;
		}

		return $access;
	}

	/**
	* Calculte the time difference
	*
	* @return time diff
	* @access protected
	*
	* METHOD STOLEN FROM http://php.net/manual/en/ref.datetime.php (imkingdavid)
	* Modified for language plurals in phpBB 3.1 (david63)
	*/
	protected function compare_dates($date1, $date2)
	{
		$blocks = array(
			array('name' => 'TAW_YEAR', 'amount' => $this->year),
			array('name' => 'TAW_MONTH', 'amount' => $this->month),
			array('name' => 'TAW_WEEK', 'amount' => $this->week),
			array('name' => 'TAW_DAY', 'amount' => $this->day),
		);

		$diff			= abs($date1 - $date2);
		$levels			= 2; // How specific to be; 1 = "1 year"; 2 = "1 year & 2 months"; 3 = "1 year, 2 months & 3 weeks"; etc.
		$current_level	= 1; // Starting point
		$result			= [];

		foreach ($blocks as $block)
		{
			if ($current_level > $levels)
			{
				break;
			}
			else if ($diff / $block['amount'] >= 1)
			{
				$amount		= floor($diff / $block['amount']);
				$result[]	= $this->language->lang($block['name'], $amount);
				$diff		-= $amount * $block['amount'];
				$current_level++;
			}
		}
		return strtolower(implode(' ' . $this->language->lang('AND') . ' ', $result));
	}
}
