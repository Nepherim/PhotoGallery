<?php if (!defined('PmWiki')) exit();
/*
 * PmWiki PhotoGallery skin
 * Version 0.2.0  (21-Dec-2008)
 * @requires PmWiki 2.2
 *
 * Examples at: http://pmwiki.com/Cookbook/PhotoGallery and http://skins.solidgone.com/PhotoGallery
 * Copyright (c) 2007 David Gilbert
 * Dual licensed under the MIT and GPL licenses:
 *    http://www.opensource.org/licenses/mit-license.php
 *    http://www.gnu.org/licenses/gpl.html
 */
global $FmtPV;
$FmtPV['$SkinName'] = '"PhotoGallery"';
$FmtPV['$SkinVersion'] = '"0.2.0"';

## Override pmwiki styles otherwise they will override styles declared in css
global $HTMLStylesFmt;
$HTMLStylesFmt['pmwiki'] = '';

## Default color scheme
global $SkinColor, $ValidSkinColors;
if ( !is_array($ValidSkinColors) ) $ValidSkinColors = array();
array_push($ValidSkinColors, 'red', 'blue', 'orange', 'yellow', 'green', 'lightblue');
if ( isset($_GET['color']) && in_array($_GET['color'], $ValidSkinColors) ) {
	$SkinColor = $_GET['color'];
} elseif ( !in_array($SkinColor, $ValidSkinColors) ) {
	$SkinColor = 'blue';
}

## Add a custom page storage location
global $PageStorePath, $WikiLibDirs;
$PageStorePath = dirname(__FILE__)."/wikilib.d/{\$FullName}";
$where = count($WikiLibDirs);
if ($where>1) $where--;
array_splice($WikiLibDirs, $where, 0, array(new PageStore($PageStorePath)));

## Define a link stye for new page links
global $LinkPageCreateFmt;
SDV($LinkPageCreateFmt, "<a class='createlinktext' href='\$PageUrl?action=edit'>\$LinkText</a>");

## Move any (:noleft:) or SetTmplDisplay('PageLeftFmt', 0); directives to variables for access in jScript.
$FmtPV['$TabsBar'] = "\$GLOBALS['TmplDisplay']['PageTabsFmt']";
Markup('notabs', 'directives',  '/\\(:notabs:\\)/ei', "SetTmplDisplay('PageTabsFmt',0)");

Markup('album', 'inline', "/\\(:album\\s*(.*?):\\)/se", "Keep(album(PSS('$1')))");
function album ($args) {
	$o = ParseArgs($args);
	global $pagename;
	return
		'<div class="album">'
		.(empty($o['thumb']) ? '' : '<div class="thumb">' .MakeLink($pagename,PSS($o['link']), '<img src="' .$o['thumb'] .'" />') .'</div>')
		.'<div class="albumdesc">' .'<h3>' .MakeLink($pagename,PSS($o['link']), $o['title']) .'</h3><p>' .html_entity_decode($o['desc']) .'</p></div>'
		.'</div>';
}
