<?php
/**
 *
 * @package Topic Age Warning Extension
 * @copyright (c) 2015 david63
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

/**
 * DO NOT CHANGE
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

/**
 * DEVELOPERS PLEASE NOTE
 *
 * All language files should use UTF-8 as their encoding and the files must not contain a BOM.
 *
 * Placeholders can now contain order information, e.g. instead of
 * 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
 * translators to re-order the output of data while ensuring it remains correct
 *
 * You do not need this where single placeholders are used, e.g. 'Message %d' is fine
 * equally where a string contains only two placeholders which are used to wrap text
 * in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
 *
 * Some characters you may want to copy&paste:
 * ’ » “ ” …
 *
 */

$lang = array_merge($lang, [
	'NO_TAW_FORUMS' => 'No forums selected',

	'TAW_ADMIN_EXEMPT'				=> 'Exempt Administrators from Topic Age Warning',
	'TAW_ADMIN_EXEMPT_EXPLAIN' 		=> 'If this is set to <strong>Yes</strong> then an Administrator will be able to reply to a topic, even after the warning interval has passed.',
	'TAW_AUTHOR_EXEMPT' 			=> 'Exempt author from Topic Age Warning',
	'TAW_AUTHOR_EXEMPT_EXPLAIN'		=> 'If this is set to <strong>Yes</strong> then the author of the topic will be able to reply to that topic, even after the warning interval has passed.',
	'TAW_DAYS' 						=> 'Days',
	'TAW_INTERVAL' 					=> 'Topic Age Warning interval',
	'TAW_INTERVAL_EXPLAIN' 			=> 'Number of days, months or years after which a user will recieve the topic age warning message when attempting to reply to a topic.<br><strong>Note:</strong> If this is set to 0 then no warning will be ouput.',
	'TAW_LAST_POST' 				=> 'Use last reply time',
	'TAW_LAST_POST_EXPLAIN' 		=> 'If this is set to <strong>Yes</strong> then the old topics will be determined using the time of the last reply to the topic. If set to <strong>No</strong> then old topics will be determined using the time of the topic’s creation.',
	'TAW_LOCK' 						=> 'Lock old topic',
	'TAW_LOCK_EXPLAIN' 				=> 'If this is set to <strong>Yes</strong> then topics to which a user attempts to reply to, after the set interval, will be automatically locked.',
	'TAW_LOOK_UP_FORUMS' 			=> 'Select forum(s)',
	'TAW_LOOK_UP_FORUMS_EXPLAIN'	=> 'You are able to select more than one forum.<br><br>Selecting “All forums” will clear any selected forums.<br><br>You will need to set All forums to “No” if you want to select individual forums.',
	'TAW_MESSAGE_BOTTOM' 			=> 'Display message at the bottom',
	'TAW_MESSAGE_BOTTOM_EXPLAIN' 	=> 'Display the topic locked warning message at the bottom of the viewtopic list.',
	'TAW_MESSAGE_TOP' 				=> 'Display message at the top',
	'TAW_MESSAGE_TOP_EXPLAIN' 		=> 'Display the topic locked warning message at the top of the viewtopic list.',
	'TAW_MOD_EXEMPT' 				=> 'Exempt Moderators from Topic Age Warning',
	'TAW_MOD_EXEMPT_EXPLAIN' 		=> 'If this is set to <strong>Yes</strong> then a Moderrator will be able to reply to a topic, even after the warning interval has passed.',
	'TAW_MONTHS' 					=> 'Months',
	'TAW_SHOW_LOCKED' 				=> 'Show locked',
	'TAW_SHOW_LOCKED_EXPLAIN' 		=> 'Show the topic locked button in the topic.<br>Setting this to “No” will not display any buttons.',
	'TAW_QUICKREPLY' 				=> 'Allow quick reply (where enabled) in old topics',
	'TAW_QUICKREPLY_EXPLAIN' 		=> 'If this is set to <strong>Yes</strong> then quick reply (where enabled) will still be usable in old topics.',
	'TAW_YEARS' 					=> 'Years',
	'TOPIC_AGE_WARNING_EXPLAIN' 	=> 'Set the options here for the Topic Age Warning extension.',
]);
