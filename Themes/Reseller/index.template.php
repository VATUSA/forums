<?php
/**
 * Simple Machines Forum (SMF)
 *
 * @package SMF
 * @author Simple Machines
 * @copyright 2011 Simple Machines
 * @license http://www.simplemachines.org/about/smf/license.php BSD
 *
 * @version 2.0
 */

/*	This template is, perhaps, the most important template in the theme. It
	contains the main template layer that displays the header and footer of
	the forum, namely with main_above and main_below. It also contains the
	menu sub template, which appropriately displays the menu; the init sub
	template, which is there to set the theme up; (init can be missing.) and
	the linktree sub template, which sorts out the link tree.

	The init sub template should load any data and set any hardcoded options.

	The main_above sub template is what is shown above the main content, and
	should contain anything that should be shown up there.

	The main_below sub template, conversely, is shown after the main content.
	It should probably contain the copyright statement and some other things.

	The linktree sub template should display the link tree, using the data
	in the $context['linktree'] variable.

	The menu sub template should display all the relevant buttons the user
	wants and or needs.

	For more information on the templating system, please see the site at:
	http://www.simplemachines.org/
*/

// Initialize the template... mainly little settings.
function template_init()
{
	global $context, $settings, $options, $txt;

	/* Use images from default theme when using templates from the default theme?
		if this is 'always', images from the default theme will be used.
		if this is 'defaults', images from the default theme will only be used with default templates.
		if this is 'never' or isn't set at all, images from the default theme will not be used. */
	$settings['use_default_images'] = 'never';

	/* What document type definition is being used? (for font size and other issues.)
		'xhtml' for an XHTML 1.0 document type definition.
		'html' for an HTML 4.01 document type definition. */
	$settings['doctype'] = 'xhtml';

	/* The version this template/theme is for.
		This should probably be the version of SMF it was created for. */
	$settings['theme_version'] = '2.0';

	/* Set a setting that tells the theme that it can render the tabs. */
	$settings['use_tabs'] = true;

	/* Use plain buttons - as opposed to text buttons? */
	$settings['use_buttons'] = true;

	/* Show sticky and lock status separate from topic icons? */
	$settings['separate_sticky_lock'] = true;

	/* Does this theme use the strict doctype? */
	$settings['strict_doctype'] = false;

	/* Does this theme use post previews on the message index? */
	$settings['message_index_preview'] = false;

	/* Set the following variable to true if this theme requires the optional theme strings file to be loaded. */
	$settings['require_theme_strings'] = true;
}

// The main sub template above the content.
function template_html_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	// Show right to left and the character set for ease of translating.
	echo '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"', $context['right_to_left'] ? ' dir="rtl"' : '', '>
<head>
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/reseller.css" />
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/index', $context['theme_variant'], '.css?fin20" />';

	// Some browsers need an extra stylesheet due to bugs/compatibility issues.
	foreach (array('ie7', 'ie6', 'webkit') as $cssfix)
		if ($context['browser']['is_' . $cssfix])
			echo '
	<link rel="stylesheet" type="text/css" href="', $settings['default_theme_url'], '/css/', $cssfix, '.css" />';

	// RTL languages require an additional stylesheet.
	if ($context['right_to_left'])
		echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/rtl.css" />';

	// Here comes the JavaScript bits!
	echo '
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>	
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/bootstrap.min.js"></script>
	<script type="text/javascript">
	$(document).ready(function(){
		$("input[type=button]").attr("class", "btn btn-default btn-sm");
		$(".button_submit").attr("class", "btn btn-primary btn-sm");
		$("#advanced_search input[type=\'text\'], #search_term_input input[type=\'text\']").removeAttr("size"); 
		$(".table_grid").attr("class", "table table-striped");
		$("img[alt=\'', $txt['new'], '\'], img.new_posts").replaceWith("<span class=\'label label-warning\'>', $txt['new'], '</span>");
		$("#profile_success").removeAttr("id").removeClass("windowbg").addClass("alert alert-success"); 
		$("#profile_error").removeAttr("id").removeClass("windowbg").addClass("alert alert-danger"); 
	});
	</script>
	<script type="text/javascript" src="', $settings['default_theme_url'], '/scripts/script.js?fin20"></script>
	<script type="text/javascript" src="', $settings['theme_url'], '/scripts/theme.js?fin20"></script>
	<script type="text/javascript"><!-- // --><![CDATA[
		var smf_theme_url = "', $settings['theme_url'], '";
		var smf_default_theme_url = "', $settings['default_theme_url'], '";
		var smf_images_url = "', $settings['images_url'], '";
		var smf_scripturl = "', $scripturl, '";
		var smf_iso_case_folding = ', $context['server']['iso_case_folding'] ? 'true' : 'false', ';
		var smf_charset = "', $context['character_set'], '";', $context['show_pm_popup'] ? '
		var fPmPopup = function ()
		{
			if (confirm("' . $txt['show_personal_messages'] . '"))
				window.open(smf_prepareScriptUrl(smf_scripturl) + "action=pm");
		}
		addLoadEvent(fPmPopup);' : '', '
		var ajax_notification_text = "', $txt['ajax_in_progress'], '";
		var ajax_notification_cancel_text = "', $txt['modify_cancel'], '";
	// ]]></script>';

	echo '
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta http-equiv="Content-Type" content="text/html; charset=', $context['character_set'], '" />
	<meta name="description" content="', $context['page_title_html_safe'], '" />', !empty($context['meta_keywords']) ? '
	<meta name="keywords" content="' . $context['meta_keywords'] . '" />' : '', '
	<title>', $context['page_title_html_safe'], '</title>';

	// Please don't index these Mr Robot.
	if (!empty($context['robot_no_index']))
		echo '
	<meta name="robots" content="noindex" />';

	// Present a canonical url for search engines to prevent duplicate content in their indices.
	if (!empty($context['canonical_url']))
		echo '
	<link rel="canonical" href="', $context['canonical_url'], '" />';

	// Show all the relative links, such as help, search, contents, and the like.
	echo '
	<link rel="help" href="', $scripturl, '?action=help" />
	<link rel="search" href="', $scripturl, '?action=search" />
	<link rel="contents" href="', $scripturl, '" />';

	// If RSS feeds are enabled, advertise the presence of one.
	if (!empty($modSettings['xmlnews_enable']) && (!empty($modSettings['allow_guestAccess']) || $context['user']['is_logged']))
		echo '
	<link rel="alternate" type="application/rss+xml" title="', $context['forum_name_html_safe'], ' - ', $txt['rss'], '" href="', $scripturl, '?type=rss;action=.xml" />';

	// If we're viewing a topic, these should be the previous and next topics, respectively.
	if (!empty($context['current_topic']))
		echo '
	<link rel="prev" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=prev" />
	<link rel="next" href="', $scripturl, '?topic=', $context['current_topic'], '.0;prev_next=next" />';

	// If we're in a board, or a topic for that matter, the index will be the board's index.
	if (!empty($context['current_board']))
		echo '
	<link rel="index" href="', $scripturl, '?board=', $context['current_board'], '.0" />';

	// Output any remaining HTML headers. (from mods, maybe?)
	echo $context['html_headers'];

	echo '
</head>
<body>';
}

function template_body_above()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo'
<nav class="navbar navbar-default navbar-static-top" role="navigation">
	<div class="container">
		<div class="row">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand visible-xs" href="', $scripturl, '">', $context['forum_name'] ,'</a>
			</div>
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">';
				
					// Show the menu here, according to the menu sub template.
					template_menu();
					
				echo'
				</ul>
			</div>
		</div>
	</div>
</nav>
<header>
	<div class="container">
		<div class="row">
			<div class="col-md-9">
				<a href="', $scripturl, '"><img src="https://www.vatusa.net/img/logo-light.png" style="height: 75px;" alt="' . $context['forum_name'] . '" /></a>
			</div>
			<div class="col-md-3">';
			if ($context['user']['is_logged'])
			{
				if (!empty($context['user']['avatar']))
				echo '
					<img src="', $context['user']['avatar']['href'], '" class="avatar img-circle img-thumbnail" alt="*" />';
				echo'
					<ul class="reset">
						<li class="user">', $context['user']['name'], '</li>
						<li><a href="', $scripturl, '?action=profile">My Profile</a></li>
						<li><a href="', $scripturl, '?action=unread">', $txt['unread_topics_visit'], '</a></li>
						<li><a href="', $scripturl, '?action=unreadreplies">', $txt['unread_replies'], '</a></li>
					</ul>';
			}
			else
			{
			echo'
				<a href="https://login.vatusa.net" class="btn btn-success" style="color: #fff">Login</a>';
			}
			echo'
			</div>
		</div>
	</div>
</header>';

	// Show the navigation tree.
	theme_linktree();
	
echo'
<div class="container">
	<div class="row">';

		// The main content should go here.
		echo '
		<div id="main_content_section">';

		// Custom banners and shoutboxes should be placed here, before the linktree.
}

function template_body_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
		</div>
	</div>
</div>';

		// Show the "Powered by" and "Valid" logos, as well as the copyright. Remember, the copyright must be somewhere!
		echo '
		<footer>
			<div class="container">
				<div class="row">
					<div class="social_icons col-lg-12">';
						if(!empty($settings['facebook_check']))
						echo'
							<a href="', !empty($settings['facebook_text']) ? $settings['facebook_text'] : 'http://www.facebook.com ' ,'"><img src="', $settings['images_url'], '/social_icons/facebook.png" alt="', $txt['rs_facebook'], '" /></a>';
						if(!empty($settings['twitter_check']))
						echo'
							<a href="', !empty($settings['twitter_text']) ? $settings['twitter_text'] : 'http://www.twitter.com' ,'"><img src="', $settings['images_url'], '/social_icons/twitter.png" alt="', $txt['rs_twitter'], '" /></a>';
						if(!empty($settings['youtube_check']))
						echo'
							<a href="', !empty($settings['youtube_text']) ? $settings['youtube_text'] : 'http://www.youtube.com' ,'"><img src="', $settings['images_url'], '/social_icons/youtube.png" alt="', $txt['rs_youtube'], '" /></a>';
						if(!empty($settings['rss_check']))
						echo'
							<a href="', !empty($settings['rss_text']) ? $settings['rss_text'] : $scripturl .'?action=.xml;type=rss' ,'"><img src="', $settings['images_url'], '/social_icons/rss.png" alt="', $txt['rs_rss'], '" /></a>';
						echo'
					</div> 
					<div class="col-lg-12" style="color: #fff;">
						&copy; '. date('Y'). ' VATUSA.  All rights reserved.' ,'
					 </div>';
				echo '
				</div>
			</div>
		</footer>';
}

function template_html_below()
{
	global $context, $settings, $options, $scripturl, $txt, $modSettings;

	echo '
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-112506058-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag("js", new Date());

  gtag("config", "UA-112506058-2");
</script>
</body></html>';
}

// Show a linktree. This is that thing that shows "My Community | General Category | General Discussion"..
function theme_linktree($force_show = false)
{
	global $context, $settings, $options, $shown_linktree;

	// If linktree is empty, just return - also allow an override.
	if (empty($context['linktree']) || (!empty($context['dont_default_linktree']) && !$force_show))
		return;

	echo '
	<div class="navigate_section">
		<div class="container">
			<div class="row">
				<ol class="breadcrumb"><li><a href="https://www.vatusa.net">VATUSA</a></li>';

			// Each tree item has a URL and name. Some may have extra_before and extra_after.
			foreach ($context['linktree'] as $link_num => $tree)
			{
				echo '
					<li', ($link_num == count($context['linktree']) - 1) ? ' class="active"' : '', '>';

				// Show something before the link?

				// Show the link, including a URL if it should have one.
				echo $settings['linktree_link'] && isset($tree['url']) ? '
						<a href="' . $tree['url'] . '"><span>' . $tree['name'] . '</span></a>' : '<span>' . $tree['name'] . '</span>';

				// Show something after the link...?

				echo '
					</li>';
			}
			echo '
				</ol>
			</div>
		</div>
	</div>';

	$shown_linktree = true;
}

// Show the menu up top. Something like [home] [help] [profile] [logout]...
function template_menu()
{
	global $context, $settings, $options, $scripturl, $txt;

	foreach ($context['menu_buttons'] as $act => $button)
	{ 
		if (isset($button['sub_buttons'])) {
			echo '
				<li id="button_', $act, '" class="', $button['sub_buttons'] ? 'dropdown ' : '', '', $button['active_button'] ? 'active ' : '', '">
					<a ', $button['sub_buttons'] ? 'class="dropdown-toggle" ' : '', 'href="', $button['sub_buttons'] ? '#' : $button['href'], '"', isset($button['target']) ? ' target="' . $button['target'] . '"' : '', '', $button['sub_buttons'] ? ' data-toggle="dropdown"' : '', '>
						', $button['title'], '
						', $button['sub_buttons'] ? '<span class="caret"></span>' : '' ,'
					</a>';
		}
		if (isset($button['sub_buttons']))
		{
			echo '
					<ul class="dropdown-menu" role="menu">';

			foreach ($button['sub_buttons'] as $childbutton)
			{
				echo '
						<li>
							<a href="', $childbutton['href'], '"', isset($childbutton['target']) ? ' target="' . $childbutton['target'] . '"' : '', '>
								', $childbutton['title'] , '
							</a>
						</li>';
			}
				echo '
					</ul>';
		}
		echo '
				</li>';
	}
}

// Generate a strip of buttons.
function template_button_strip($button_strip, $direction = 'top', $strip_options = array())
{
	global $settings, $context, $txt, $scripturl;

	if (!is_array($strip_options))
		$strip_options = array();

	// List the buttons in reverse order for RTL languages.
	if ($context['right_to_left'])
		$button_strip = array_reverse($button_strip, true);

	// Create the buttons...
	$buttons = array();
	foreach ($button_strip as $key => $value)
	{
		if (!isset($value['test']) || !empty($context[$value['test']]))
			$buttons[] = '
				<li><a' . (isset($value['id']) ? ' id="button_strip_' . $value['id'] . '"' : '') . ' class="button_strip_' . $key . (isset($value['active']) ? ' active' : '') . '" href="' . $value['url'] . '"' . (isset($value['custom']) ? ' ' . $value['custom'] : '') . '><span>' . $txt[$value['text']] . '</span></a></li>';
	}

	// No buttons? No button strip either.
	if (empty($buttons))
		return;

	// Make the last one, as easy as possible.
	$buttons[count($buttons) - 1] = str_replace('<span>', '<span class="last">', $buttons[count($buttons) - 1]);

	$text = "Actions";
	if ($strip_options['id'] == "moderationbuttons_strip") { $text = "Moderation Actions"; }

	echo '
		<div class="btn-group', !empty($direction) ? ' navbar-' . $direction : '', '"', (empty($buttons) ? ' style="display: none;"' : ''), (!empty($strip_options['id']) ? ' id="' . $strip_options['id'] . '"': ''), '>
			<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				 ' . $text . ' <i class="caret"></i>
			  </button>
			<ul class="dropdown-menu" role="menu">',
				implode('', $buttons), '
			</ul>
		</div>';
}

?>
