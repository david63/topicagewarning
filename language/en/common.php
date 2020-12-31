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

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'TOPIC_AGE_WARNING'			=> 'This topic is %s old and the forum Administrator has chosen not to allow any further replies. Instead, please begin a new topic or search for another related topic that may be more suitable.',
	'TOPIC_AGE_WARNING_LOCK'	=> 'This topic is %s old and the forum Administrator has chosen for old topics to be locked when a reply is attempted. Please begin a new topic or use the search feature to find a similar but newer topic.',

	'TAW_DAY'	=> array(
		1	=> '%1$s Day',
		2	=> '%1$s Days',
	),

	'TAW_WEEK'	=> array(
		1	=> '%1$s Week',
		2	=> '%1$s Weeks',
	),

	'TAW_MONTH'	=> array(
		1	=> '%1$s Month',
		2	=> '%1$s Months',
	),

	'TAW_YEAR'	=> array(
		1	=> '%1$s Year',
		2	=> '%1$s Years',
	),
));
