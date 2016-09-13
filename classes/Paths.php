<?php
$secure = (isSecure())?'https://':'http://';

//All links defined here. MODIFY IT WHEN FOLDER/SITE STRUCTURE CHANGES!
$link                         = array();
$link['root']                 = GetRootDir();
$link['url']                  = $secure.$_SERVER['HTTP_HOST']."/";
$link['favicon']              = $link['url']."favicon.ico";
$link['download']             = $link['url'].'downloads/';
$link['rss']                  = $link['url'].'rss/';
$link['home']                 = $link['url'];
$link['forum']                = $link['url'].'forum/';
$link['admin']['forum-panel'] = $link['forum'].'?action=admin';
$link['login']                = $link['forum'].'?action=login';
$link['support']              = $link['url'].'support/';
$link['addon']['home']        = $link['url'].'addons/';
$link['addon']['dashboard']   = $link['url'].'dashboard/';
$link['help']                 = $link['url'].'help/';
$link['faq']                  = $link['help'].'faq/';
$link['release-note']         = $link['help'].'release-note/';
$link['press']                = $link['help'].'press/';
$link['api']                  = $link['help'].'api/';
$link['bugreport']            = $link['url'].'bug/';
$link['redirect']             = $link['url'].'out/';
$link['404']                  = $link['root']."pages/error/404.php";
$link['kb']                   = $link['url'].'kb/';
$link['credit']               = $link['help'].'credit/';
$link['logout']               = $link['url'].'logout/';

/**
 * Check if the server has SSL or not
 * 
 * @return boolean
 */
function isSecure() {
	return
		(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
		|| $_SERVER['SERVER_PORT'] == 443;
}

function GetRootDir()
{
	return __DIR__ ."/../";
}

function GetStyleDir()
{
	global $link;
	return $link['url'].'styles/';
}