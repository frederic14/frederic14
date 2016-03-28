<?php
/**
*
* @version $Id: presentation_form.php,v 1.0.0 01/03/2016  ErnadoO Exp $
* @copyright (c) 2008 ErnadoO
* @modifier par frederic14 pour phpbb 3.1 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_PHPBB', true);
define('IN_PRESENTATION_FORM', true);

$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_posting.' . $phpEx);
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
include($phpbb_root_path . 'includes/message_parser.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup(array('posting', '/presentation_form'));

$submit 		= ( isset($_POST['post']) ) ? TRUE : FALSE;
$preview		= (isset($_POST['preview'])) ? true : false;
$username 		= utf8_normalize_nfc(request_var('username', $user->data['username'], true));
$presentation 	= utf8_normalize_nfc(request_var('presentation', '', true));
$loisir 		= utf8_normalize_nfc(request_var('loisir', array(''), true));
$qualites 		= request_var('qu', '', true);
$defauts 		= request_var('de', '', true);
$info 			= request_var('info', '', true);
$adresse 		= request_var('adresse', '', true);

$forum_id = 22; 	// Forum où inserer le message
$notify = true; // Doit on abonner le membre à son sujet de présentation?

// Si l'utilisateur n'est pas loggé
if ( !$user->data['is_registered'] )
{
	login_box();
}

// Build Navigation Links
$sql = 'SELECT *
	FROM ' . FORUMS_TABLE . "
	WHERE forum_id = $forum_id";
$result = $db->sql_query($sql);
$post_data = $db->sql_fetchrow($result);
generate_forum_nav($post_data);

// Build Forum Rules
generate_forum_rules($post_data);

// HTML, BBCode, Smilies, Images and Flash status
$bbcode_status	= ($config['allow_bbcode'] && $auth->acl_get('f_bbcode', $forum_id)) ? true : false;
$smilies_status	= ($bbcode_status && $config['allow_smilies'] && $auth->acl_get('f_smilies', $forum_id)) ? true : false;
$img_status		= ($bbcode_status && $auth->acl_get('f_img', $forum_id)) ? true : false;
$url_status		= ($config['allow_post_links']) ? true : false;
$flash_status	= ($bbcode_status && $auth->acl_get('f_flash', $forum_id) && $config['allow_post_flash']) ? true : false;
$quote_status	= ($auth->acl_get('f_reply', $forum_id)) ? true : false;

add_form_key('posting');
$template->assign_vars(array(
	'S_POST_ACTION' 	=> append_sid(basename(__FILE__) . '?f=' . $forum_id),
	'S_BBCODE_ALLOWED' 	=> $bbcode_status,
	'BBCODE_STATUS'		=> ($bbcode_status) ? sprintf($user->lang['BBCODE_IS_ON'], '<a href="' . append_sid("{$phpbb_root_path}faq.$phpEx", 'mode=bbcode') . '">', '</a>') : sprintf($user->lang['BBCODE_IS_OFF'], '<a href="' . append_sid("{$phpbb_root_path}faq.$phpEx", 'mode=bbcode') . '">', '</a>'),
	'IMG_STATUS'		=> ($img_status) ? $user->lang['IMAGES_ARE_ON'] : $user->lang['IMAGES_ARE_OFF'],
	'FLASH_STATUS'		=> ($flash_status) ? $user->lang['FLASH_IS_ON'] : $user->lang['FLASH_IS_OFF'],
	'SMILIES_STATUS'	=> ($smilies_status) ? $user->lang['SMILIES_ARE_ON'] : $user->lang['SMILIES_ARE_OFF'],
	'URL_STATUS'		=> ($bbcode_status && $url_status) ? $user->lang['URL_IS_ON'] : $user->lang['URL_IS_OFF'],

	'USERNAME' 		=> $username,
	'PRESENTATION' 	=> $presentation,
	'LOISIRS_1' 	=> $loisirs_1,
	'LOISIRS_2' 	=> $loisirs_2,
	'LOISIRS_3' 	=> $loisirs_3,
	'LOISIRS_4' 	=> $loisirs_4,
	'LOISIRS_5' 	=> $loisirs_5,
	'LOISIRS_6' 	=> $loisirs_6,
	'LOISIRS_7' 	=> $loisirs_7,
	'LOISIRS_8' 	=> $loisirs_8,
	'LOISIRS_9' 	=> $loisirs_9,
	'LOISIRS_10' 	=> $loisirs_10,
	'LOISIRS_11' 	=> $loisirs_11,
	'LOISIRS_12' 	=> $loisirs_12,
	'LOISIRS_13'	=> $loisirs_13,
	'LOISIRS_14' 	=> $loisirs_14,
	'LOISIRS_15' 	=> $loisirs_15,
	'QUALITES' 		=> $qualites,
	'DEFAUTS' 		=> $defauts,
	'INFO' 			=> $info,
	'ADRESSE' 		=> $adresse,

	'S_HIDDEN_FIELDS'	=> $s_fields)
);

if ($submit || $preview)
{
	$message_parser = new parse_message();

	if (empty($presentation))
	{
		$error[] = $user->lang['PRESENTATION_EMPTY'];
	}

	// check form
	if (!check_form_key('posting'))
	{
		$error[] = $user->lang['FORM_INVALID'];
	}

	$message = '[list][b]' . $presentation . '[/b]' . "\n\n";
	$message .= ':arrow: [b]' . $user->lang['LEISURE'] . ':[/b] ' . implode(', ', $loisir) . "\n";
	if ( !empty($qualites) )
	{
		$message .= ':arrow: [b]' . $user->lang['QUALITIES'] . ':[/b] ' . $qualites . "\n";
	}
	if ( !empty($defauts) )
	{
		$message .= ':arrow: [b]' . $user->lang['DEFECTS'] . ':[/b] ' . $defauts . "\n";
	}
	$message .= "\n";
	if ( !empty($info) )
	{
		$message .= ':arrow: [b]' . $user->lang['HOW'] . ':[/b] ' . $info . "\n";
	}
	if ( !empty($adresse) )
	{
		$message .= ':arrow: [b]' . $user->lang['URL'] . ':[/b] ' . $adresse . "\n";
	}

	$message .= "[/list]";

	$message_parser->message = $message;

	$post_data['enable_bbcode']		= (!$bbcode_status || isset($_POST['disable_bbcode'])) ? false : true;
	$post_data['enable_smilies']	= (!$smilies_status || isset($_POST['disable_smilies'])) ? false : true;
	$post_data['enable_urls']		= 1;
	$post_data['enable_sig']		= (!$config['allow_sig']) ? false : ((isset($_POST['attach_sig']) && $user->data['is_registered']) ? true : false);

	if ($submit)
	{
		$status_switch = (($post_data['enable_bbcode']+1) << 8) + (($post_data['enable_smilies']+1) << 4) + (($post_data['enable_urls']+1) << 2) + (($post_data['enable_sig']+1) << 1);
		$status_switch = ($status_switch != $check_value);
	}
	else
	{
		$status_switch = 1;
	}

	// Parse Attachments - before checksum is calculated
	$message_parser->parse_attachments('fileupload', $mode, $forum_id, $submit, $preview, $refresh);

	// Grab md5 'checksum' of new message
	$message_md5 = md5($message_parser->message);

	// Check checksum ... don't re-parse message if the same
	$update_message = ($message_md5 != $post_data['post_checksum'] || $status_switch || strlen($post_data['bbcode_uid']) < BBCODE_UID_LEN) ? true : false;

	// Parse message
	if ($update_message)
	{
		if (sizeof($message_parser->warn_msg))
		{
			$error[] = implode('<br />', $message_parser->warn_msg);
			$message_parser->warn_msg = array();
		}

		$message_parser->parse($post_data['enable_bbcode'], ($config['allow_post_links']) ? $post_data['enable_urls'] : false, true, $img_status, false, $quote_status, $config['allow_post_links']);

		// On a refresh we do not care about message parsing errors
		if (sizeof($message_parser->warn_msg) && $refresh)
		{
			$message_parser->warn_msg = array();
		}
	}
	else
	{
		$message_parser->bbcode_bitfield = $post_data['bbcode_bitfield'];
	}

	if (sizeof($message_parser->warn_msg))
	{
		$error[] = implode('<br />', $message_parser->warn_msg);
	}

	// DNSBL check
	if ($config['check_dnsbl'] && !$refresh)
	{
		if (($dnsbl = $user->check_dnsbl('post')) !== false)
		{
			$error[] = sprintf($user->lang['IP_BLACKLISTED'], $user->ip, $dnsbl[1]);
		}
	}

	// Pas d'erreurs, let's go baby
	if (!$error && $submit)
	{
		// variables to hold the parameters for submit_post
		$poll = $uid = $bitfield = $options = '';
		$post_need_approval = (!$auth->acl_get('f_noapprove', $data['forum_id']) && !$auth->acl_get('m_approve', $data['forum_id'])) ? true : false;

		generate_text_for_storage($username, $uid, $bitfield, $options, false, false, false);
		generate_text_for_storage($message, $uid, $bitfield, $options, true, true, true);

		$data = array(
			'forum_id'			=> $forum_id,
			'icon_id'			=> false,

			'enable_bbcode'		=> (!$bbcode_status || isset($_POST['disable_bbcode'])) ? false : true,
			'enable_smilies'	=> (!$smilies_status || isset($_POST['disable_smilies'])) ? false : true,
			'enable_urls'		=> true,
			'enable_sig'		=> (!$config['allow_sig']) ? false : true,

			'message'			=> $message,
			'message_md5'		=> md5($message),
			'attachment_data'	=> 0,

			'bbcode_bitfield'	=> $bitfield,
			'bbcode_uid'		=> $uid,

			'post_edit_locked'	=> 0,
			'topic_title'		=> $username,
			'notify_set'		=> false,
			'notify'			=> $notify,
			'forum_name'		=> '',
			'enable_indexing'	=> true,
		);

		$redirect_url = submit_post('post', $username, '', POST_NORMAL, $poll, $data);

		// If the post need approval we will wait a lot longer.
		if ($post_need_approval)
		{
			meta_refresh(10, $redirect_url);
			$message = ($mode == 'edit') ? $user->lang['POST_EDITED_MOD'] : $user->lang['POST_STORED_MOD'];
			$message .= (($user->data['user_id'] == ANONYMOUS) ? '' : ' '. $user->lang['POST_APPROVAL_NOTIFY']);
		}
		else
		{
			meta_refresh(3, $redirect_url);
			$message = ($mode == 'edit') ? 'POST_EDITED' : 'POST_STORED';
			$message = $user->lang[$message] . '<br /><br />' . sprintf($user->lang['VIEW_MESSAGE'], '<a href="' . $redirect_url . '">', '</a>');
		}

		$message .= '<br /><br />' . sprintf($user->lang['RETURN_FORUM'], '<a href="' . append_sid("{$phpbb_root_path}viewforum.$phpEx", 'f=' . $data['forum_id']) . '">', '</a>');
		trigger_error($message);

	}
	else
	{
		$template->assign_vars( array(
			'ERROR'	=> (isset($error)) ? implode('<br />', $error) : ''
		));
	}
}
// Preview
if (!sizeof($error) && $preview)
{
	$preview_message = $message_parser->format_display($post_data['enable_bbcode'], $post_data['enable_urls'], $post_data['enable_smilies'], false);

	$preview_signature = ($mode == 'edit') ? $post_data['user_sig'] : $user->data['user_sig'];
	$preview_signature_uid = ($mode == 'edit') ? $post_data['user_sig_bbcode_uid'] : $user->data['user_sig_bbcode_uid'];
	$preview_signature_bitfield = ($mode == 'edit') ? $post_data['user_sig_bbcode_bitfield'] : $user->data['user_sig_bbcode_bitfield'];

	// Signature
	$post_data['enable_sig'] = ($config['allow_sig'] && $user->optionget('attachsig')) ? true: false;
	if ($post_data['enable_sig'] && $config['allow_sig'] && $preview_signature && $auth->acl_get('f_sigs', $forum_id))
	{
		$parse_sig = new parse_message($preview_signature);
		$parse_sig->bbcode_uid = $preview_signature_uid;
		$parse_sig->bbcode_bitfield = $preview_signature_bitfield;

		// Not sure about parameters for bbcode/smilies/urls... in signatures
		$parse_sig->format_display($config['allow_sig_bbcode'], true, $config['allow_sig_smilies']);
		$preview_signature = $parse_sig->message;
		unset($parse_sig);
	}
	else
	{
		$preview_signature = '';
	}

	$preview_subject = censor_text($post_data['post_subject']);

	if (!sizeof($error))
	{
		$template->assign_vars(array(
			'PREVIEW_SUBJECT'		=> $preview_subject,
			'PREVIEW_MESSAGE'		=> $preview_message,
			'PREVIEW_SIGNATURE'		=> $preview_signature,

			'S_DISPLAY_PREVIEW'		=> true)
		);
	}
}
page_header('', false);

$template->set_filenames(array(
	'body' => 'mods/presentation_form/presentation_form_body.html')
);

page_footer();
?>