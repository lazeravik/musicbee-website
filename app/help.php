<?php
/**
 * Copyright (c) 2016 AvikB, some rights reserved.
 *  Copyright under Creative Commons Attribution-ShareAlike 3.0 Unported,
 *  for details visit: https://creativecommons.org/licenses/by-sa/3.0/
 *
 * @Contributors:
 * Created by AvikB for noncommercial MusicBee project.
 *  Spelling mistakes and fixes from community members.
 *
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/app/functions.php';

if(isset($_GET['press'])) {
	include $link['root'] . 'views/press.template.php';
	exit();
} elseif (isset($_GET['release-note'])) {
	include $link['root'] . 'views/releasenote.template.php';
	exit();
} elseif (isset($_GET['api'])) {
	include $link['root'] . 'views/api.template.php';
	exit();
} elseif (isset($_GET['credit'])) {
	include $link['root'] . 'views/credit.template.php';
	exit();
}elseif(isset($_GET['faq'])) {
	include $link['root'].'views/help.template.php';
	exit();
}else {
	header('Location: '.$link['faq'], true, 301);
	exit();
}


function secondery_nav_generator($active=''){
	global $link, $setting, $lang, $mb;


	echo '
		<div class="secondery_nav" id="secondery_nav">
			<div id="nav" class="secondery_nav_wrap">
				<ul class="left">
					<li class="expand">
						<a href="javascript:void(0)" onclick="expand_second_menu()"><i class="fa fa-bars"></i></a>
					</li>';

	foreach ($mb['main_menu']['help']['sub_menu'] as $item) {
		if(isset($item['href']) && !isset($item['hide'])) {
			echo '<li><a href="' . $item['href'] . '" ', (strpos($item['href'],$_SERVER['REDIRECT_URL']))? 'class="active_menu_link"' : '','>' . $item['icon'].'&nbsp;&nbsp;'.$item['title'] . '</a></li>';
		}
	}

	echo '</ul><ul class="right>" ';

	if($active == 'release-note'){
		$releasenotedata = getVersionInfo(0, "byAllReleases");
		echo'<li class="input_wrap">
				<select name="release_note_jump" id="release_note_jump" onfocus="">
					<option value="top_jump">'. $lang['jumpto_release'].'</option>';
		if(count($releasenotedata) > 0) {
			foreach($releasenotedata as $key => $value) {
				echo '<option>'.str_replace(".", "-", $value['version']).'</option>';
			}
		}
		echo '	</select>
			</li>';
	}
	echo '</ul></div></div>';

}
