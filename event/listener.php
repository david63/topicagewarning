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

use david63\topicagewarning\controller\main_controller;

/**
 * Event listener
 */
class listener implements EventSubscriberInterface
{
	/** @var main_controller */
	protected $main_controller;

	/**
	 * Constructor for listener
	 *
	 * @param main_controller 	$main_controller    Main controller
	 *
	 * @access public
	 */
	public function __construct(main_controller $main_controller)
	{
		$this->main_controller = $main_controller;
	}

	/**
	 * Assign functions defined in this class to event listeners in the core
	 *
	 * @return array
	 * @static
	 * @access public
	 */
	public static function getSubscribedEvents()
	{
		return [
			'core.viewtopic_modify_page_title' => 'viewtopic_warning_message',
		];
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
		$this->main_controller->viewtopic_warning_message($event);
	}
}
