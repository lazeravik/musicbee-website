<?php
/**
 * Copyright (c) AvikB, some rights reserved.
 * Copyright under Creative Commons Attribution-ShareAlike 3.0 Unported,
 *  for details visit: https://creativecommons.org/licenses/by-sa/3.0/
 *
 * @Contributors:
 * Created by AvikB for noncommercial MusicBee project.
 * Spelling mistakes and fixes from phred and other community memebers.
 */

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php';
require_once $siteRoot . 'classes/Addon.php';
require_once $siteRoot . 'classes/Format.php';
$addon = new Addon(); 


if (isset($_GET['id'])) {
	if (ctype_digit($_GET['id'])) {
		$data = $addon->getAddonInfo($_GET['id'])[0];

		//if no addon data is found on the database then it is likely to be deleted, throw a 404 error
		if (null == $data) {
			header("HTTP/1.0 404 Not Found");
			include_once $status['404'];
			exit();
		}

		var_dump($data);

		//checks the url parameter and try to match the addon title, if the addon id matches but not the title, then do a 301 redirect
		if (count($params) <= 4 || $params[3] != Slug($data['addon_title'])) {
			header("Location: " . $siteUrl . "addons/" . $data['ID_ADDON'] . "/" . Slug($data['addon_title']) . "", 301);
		}

		//Since the addon is found and everything is ok, create screenshot link array
		$screenshots = explode(",", $data['image_links']);

        //Create an array of supported musicbee version for this array
		foreach (explode(",", $data['supported_mbversion']) as $mbVer) {
			$mbVerArray[] = $addon->getMbVersions($mbVer)[0]['appname'];
		}

        //get addon specific info like likes, title, description etc.
		$meta_description = "Download MusicBee skins, plugins, theater mode, visualizer and more..";
		$addon_type = UnslugTxt($data['addon_type']);
		$from_author = $addon->getAddonListbyMember($data['ID_AUTHOR'], 5);
		$addon_like = $addon->getRating($data['ID_ADDON']);
		$addon_already_liked = $addon->is_rated($data['ID_ADDON'],$user_info['id']);

		include_once $siteRoot . 'includes/addons.selected.template.php';
		exit();

	} elseif ($_GET['id'] == "s") {
		$meta_description = "blah";

    	//Addon Pagination function!
    	//remove the ? sign from the string and convert the url paramenter into an array
		parse_str(str_replace("?", "", $params[3]),$url_params); 

    	//get the addon type,result order,if any search query from the get request
		$data['type'] = UnslugTxt(htmlspecialchars($url_params['type'], ENT_QUOTES, "UTF-8"));
		$data['order'] = htmlspecialchars($url_params['order'], ENT_QUOTES, "UTF-8");
		$data['query'] = (isset($url_params['q']))?htmlspecialchars($url_params['q'], ENT_QUOTES, "UTF-8"):null;
		$addon_type = Slug($data['type']);

		$generated_url = $link['addon']['home']."s/?q=".urlencode($data['query'])."&type=".$addon_type."&order=".$data['order'];

		//If the url already has a page number parameter then load only for that page
		if (isset($url_params['p'])) {
			$data['addon'] = $addon->getAddonFiltered($url_params['type'], $url_params['order'], $url_params['p'], $data['query']);
		} 
		//else load only for page 1 as default
		else {
			$data['addon'] = $addon->getAddonFiltered($url_params['type'], $url_params['order'], "1", $data['query']);
		}
		//var_dump($data['addon']);
		$addon_total = $addon->getAddonCount($url_params['type'], $data['query']);
		$page_total = ceil($addon_total/$addon_view_range);

		include_once $siteRoot . 'includes/addons.search.template.php';
		exit();
	} else {
		header("HTTP/1.0 404 Not Found");
		include_once $status['404'];
		exit();
	}
} else {
	header("Location: " . $link['addon']['home'] . "s/?q=&type=all&order=latest");
	exit();
}

/**
 * Generate addon result view
 * @param  array $data  gets the data from Addon class using getAddonFiltered() method
 * @param  class $addon class instance
 * @return string        necessary html for generating the addon list view 
 */
function addon_result_view_generator($data, $addon)
{
	global $link, $lang;

	if ($data != null) {
		$result_view = '<ul class="addon_list_box">';
		foreach ($data as $key => $addon_data){
			$addon_link = $link['addon']['home'] . $addon_data['ID_ADDON'] . '/' . Slug($addon_data['addon_title']);

			$result_view .='<li id ="'.$addon_data['ID_ADDON'].'">
			<div class="addon_list_box_wrapper">
				<a href="'.$addon_link.'">
					<div class="thumb_more" style="background-image:url('.$addon_data['thumbnail'].')"></div>
					<div class="love"><i class="fa fa-heart"></i><p class="love_count">'. $addon->getRating($addon_data['ID_ADDON']).'</p></div>
				</a>
				<div class="addon_list_box_info">
					<a href="'.$addon_link.'"><p class="title">'.$addon_data['addon_title'].'</p></a>
					<p class="author">by '.$addon_data['membername'].'</p>
				</div>
			</div>
		</li>'; }
		$result_view .='<div id="clear"></div></ul>';
	} else {
		$result_view = '<div class="no_result"><h2>'.$lang['270'].'</h2><p>'.$lang['271'].'</p></div>';
	}

	return $result_view;
}

/**
 * Generate Pagination for addon result view
 * @param  int    $page_total    	total number of pagination breadcrumb required
 * @param  string $generated_url 	url with required param only, no page param included
 * @return string                   necessary html for generating the pagination bread crumb  
 */
function addon_result_pagination_generator($page_total, $generated_url)
{
	if ($page_total > 0) {
		$pagination_view = '<ul class="pagination">';
		for ($i=1; $i < $page_total+1; $i++){
			$pagination_view .= '<li><a href="'.$generated_url.'&p='.$i.'"><p>'.$i.'</p></a></li>';
		}
		$pagination_view .= '</ul>';
	} else {
		$pagination_view = "";
	}

	return $pagination_view;
}