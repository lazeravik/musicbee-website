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
	if (ctype_digit ($_GET['id'])) {
		$data = $addon->getAddonInfo ($_GET['id'])[0];

		//if no addon data is found on the database then it is likely to be deleted, throw a 404 error
		if (null == $data) {
			header ("HTTP/1.0 404 Not Found");
			include_once $status['404'];
			exit();
		}

		//var_dump($data);

		//checks the url parameter and try to match the addon title, if the addon id matches but not the title, then do a 301 redirect
		if (count ($params) <= 4 || $params[3] != Format::Slug ($data['addon_title'])) {
			header ("Location: " . $siteUrl . "addons/" . $data['ID_ADDON'] . "/" . Format::Slug ($data['addon_title']) . "", 301);
		}

		//Since the addon is found and everything is ok, create screenshot link array
		$screenshots = explode (",", $data['image_links']);

		//Create an array of supported musicbee version for this array
		foreach (explode (",", $data['supported_mbversion']) as $mbVer) {
			$mbVerArray[] = $addon->getMbVersions ($mbVer)[0]['appname'];
		}

		//get addon specific info like likes, title, description etc.
		$meta_description = "Download MusicBee skins, plugins, theater mode, visualizer and more..";
		$addon_type = Format::UnslugTxt ($data['addon_type']);
		$from_author = $addon->getAddonListByMember ($data['ID_AUTHOR'], 5);
		$addon_like = $addon->getRating ($data['ID_ADDON']);
		$addon_already_liked = $addon->is_rated ($data['ID_ADDON'], $user_info['id']);

		include_once $siteRoot . 'includes/addons.selected.template.php';
		exit();

	} elseif ($_GET['id'] == "s") {

		//Addon Pagination function!
		//remove the ? sign from the string and convert the url paramenter into an array
		parse_str (str_replace ("?", "", $params[3]), $url_params);

		//Just to be safe, we will check if the url contains type and order parameter otherwise initialize the default value for them
		$url_params['type'] = isset($url_params['type']) ? $url_params['type'] : "all";
		$url_params['order'] = isset($url_params['order']) ? $url_params['order'] : "latest";

		//get the addon type,result order,if any search query from the get request
		$data['type'] = Format::UnslugTxt (htmlspecialchars ($url_params['type'], ENT_QUOTES, "UTF-8"));
		$data['order'] = htmlspecialchars ($url_params['order'], ENT_QUOTES, "UTF-8");
		$data['query'] = (isset($url_params['q'])) ? htmlspecialchars ($url_params['q'], ENT_QUOTES, "UTF-8") : null;
		$addon_type = Format::Slug ($data['type']);

		$generated_url = $link['addon']['home'] . "s/?q=" . urlencode ($data['query']) . "&type=" . $addon_type . "&order=" . $data['order'];
		
		//get all the addon filtered by category/query and other
		$data['addon_all'] = $addon->getAddonFiltered ($url_params['type'], $url_params['order'], $data['query']);

		//Offset start and end value for pagination
		$offset_start = (isset($url_params['p'])) ? (($url_params['p'] - 1) * $addon_view_range) : "0";

		//instead of showing the full list at once, we wan't to break it down by chunks and use pagination
		$data['addon'] = ($data['addon_all'] != null) ? array_slice ($data['addon_all'], $offset_start, $addon_view_range) : null;

		//var_dump($data['addon']);
		
		//Calculate total number of page required
		$page_total = ceil (count ($data['addon_all']) / $addon_view_range);

		$meta_description = "blah";
		include_once $siteRoot . 'includes/addons.search.template.php';
		exit();
	} else {
		header ("HTTP/1.0 404 Not Found");
		include_once $status['404'];
		exit();
	}
} else {
	header ("Location: " . $link['addon']['home'] . "s/?q=&type=all&order=latest");
	exit();
}

/**
 * Generate addon result view
 *
 * @param  array $data  gets the data from Addon class using getAddonFiltered() method
 * @param  class $addon class instance
 *
 * @return string        necessary html for generating the addon list view
 */
function addon_result_view_generator($data, $addon) {
	global $link, $lang;

	if ($data != null) {
		$result_view = '<ul class="addon_list_box">';
		foreach ($data as $key => $addon_data) {
			$addon_link = $link['addon']['home'] . $addon_data['ID_ADDON'] . '/' . Format::Slug ($addon_data['addon_title']);
			$addon_beta_markup = ($addon_data['is_beta']==1)?'<p class="small_info beta">'.$lang['addon_36'].'</p>': '';

			$result_view .= '<li id ="' . $addon_data['ID_ADDON'] . '">
			<div class="addon_list_box_wrapper">
				<a href="' . $addon_link . '">
					<div class="thumb_more" style="background-image:url(' . $addon_data['thumbnail'] . ')"></div>
					<div class="love"><i class="fa fa-heart"></i><p class="love_count">' . $addon->getRating ($addon_data['ID_ADDON']) . '</p></div>
					'.$addon_beta_markup.'
				</a>
				<div class="addon_list_box_info">
					<a href="' . $addon_link . '"><p class="title">' . $addon_data['addon_title'] . '</p></a>
					<p class="author"><a href="' . addon_author_url_generator ($addon_data['membername']) . '"> ' . $lang['addon_15'] . ' <b>' . $addon_data['membername'] . '</b></a></p>
				</div>
			</div>
		</li>';
		}
		$result_view .= '<div id="clear"></div></ul>';
	} else {
		$result_view = '<div class="no_result"><h2>' . $lang['addon_32'] . '</h2><p>' . $lang['addon_33'] . '</p></div>';
	}

	return $result_view;
}

/**
 * Generate Pagination for addon result view
 *
 * @param  int    $page_total    total number of pagination breadcrumb required
 * @param  string $generated_url url with required param only, no page param included
 *
 * @return string                   necessary html for generating the pagination bread crumb
 */
function addon_result_pagination_generator($page_total, $generated_url) {
	global $url_params;
	if ($page_total > 0) {
		$pagination_view = '<ul class="pagination">';
		for ($i = 1; $i < $page_total + 1; $i++) {
			$page_num = isset($url_params['p']) ? $url_params['p'] : "1";
			if ($page_num == $i) {
				$pagination_view .= '<li><a class="btn btn_blue active" href="' . $generated_url . '&p=' . $i . '"><p>' . $i . '</p></a></li>';
			} else {
				$pagination_view .= '<li><a class="btn btn_blue" href="' . $generated_url . '&p=' . $i . '"><p>' . $i . '</p></a></li>';
			}
		}
		$pagination_view .= '</ul>';
	} else {
		$pagination_view = "";
	}

	return $pagination_view;
}

function addon_author_url_generator($name) {
	global $link;

	return $link['addon']['home'] . "s/?q=" . urlencode ("author:" . $name) . "&type=all&order=latest";
}

function addon_secondery_nav_generator($addon_type) {
	global $link, $lang, $main_menu, $url_params;
	$data = '<ul class="left">
	<li class="expand"><a href="javascript:void(0)" onclick="expand_second_menu()"><i class="fa fa-bars"></i></a></li>';

	if (Format::Slug ($addon_type) == "all") {
		$data .= '<li><a href="' . $link['addon']['home'] . 's/?q=&type=all&order=latest" class="active_menu_link">' . $lang['18'] . '</a></li>';
	} else {
		$data .= '<li><a href="' . $link['addon']['home'] . 's/?q=&type=all&order=latest" >' . $lang['18'] . '</a></li>';
	}

	foreach ($main_menu['add-ons']['sub_menu'] as $key => $menu_addon) {
		if (Format::Slug ($addon_type) == Format::Slug ($menu_addon['title'])) {
			$data .= "
			<li>
				<a href=\"" . $menu_addon['href'] . " \"  class=\"active_menu_link\">" . $menu_addon['title'] . "</a>
			</li>";
		} else {
			$data .= "
			<li>
				<a href=\"" . $menu_addon['href'] . " \" >" . $menu_addon['title'] . "</a>
			</li>";
		}
	}
	$data .= '
</ul>
<ul class="right">
	<li>
		<form method="GET" action="' . $link['addon']['home'] . 's/">
			<input type="search" spellcheck="false" autocomplete="off" autocorrect="off" autocapitalize="off" class="search small_search" placeholder="Search for Add-ons" name="q" value="' . htmlspecialchars ($url_params['q'], ENT_QUOTES, "UTF-8") . '"/>
			<input type="hidden" name="type" value="all" />
			<input type="hidden" name="order" value="latest" />
		</form>
	</li>
</ul>';

	return $data;
}