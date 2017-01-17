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
require_once $link['root'] . 'classes/Addon.php';
require_once $link['root'] . 'classes/Search.php';
$addon = new Addon();


if (isset($_GET['id'])) {
	if (is_int($_GET['id']) || ctype_digit ($_GET['id']))
	{

		$params = array_filter($params);
		array_shift($params); //"addons" from the array as we already know the page name
		$addon_data = $addon->getAddonData($_GET['id']);
		$type_blob = (array_key_exists($addon_data['category'], $mb['main_menu']['add-ons']['sub_menu'])) ? $mb['main_menu']['add-ons']['sub_menu'][$addon_data['category']]['title'] : $addon_data['category'];

		if($addon_data == null)
		{
			header("HTTP/1.0 404 Not Found");
			include $link['404'];
			exit();
		}

		//checks the url parameter and try to match the addon title, if the addon id matches but not the title, then do a 301 redirect
		if (count ($params) <= 3 && (urldecode($params[2]) != Format::slug ($addon_data['addon_title']) || urldecode($params[0]) != Format::slug($type_blob)))
		{
			header ("Location: " . $link['addon']['home']. Format::slug($type_blob) .'/'. $addon_data['ID_ADDON'] . "/" . Format::slug ($addon_data['addon_title']) . "/", 301);
		}

		$from_author = $addon->getAddonListByMember ($addon_data['ID_AUTHOR'], 5);
		// var_dump($addon_data);

		include_once $link['root'] . 'views/addons.selected.template.php';
		exit();

	}
	elseif ($_GET['id'] == "s")
	{
		//remove the ? sign from the string and convert the url paramenter into an array
		parse_str (str_replace ("?", "", $params[3]), $url_params);

		//Just to be safe, we will check if the url contains type and order parameter otherwise initialize the default value for them
		$url_params['type'] = isset($url_params['type']) ? htmlspecialchars(Format::htmlSafeOutput($url_params['type']), ENT_QUOTES, "UTF-8")  : "all";

		//get the addon type,result order,if any search query from the get request
		$data['type'] = Format::unslugTxt ($url_params['type']);

		$data['is_overview'] = isset($url_params['overview'])? true : false;

		if(isset($url_params['q']))
		{
			if(!empty($url_params['q']))
			{
				$searchinput['query'] = htmlspecialchars(trim($url_params['q']), ENT_QUOTES, "UTF-8");
			}
			else
			{
				$searchinput['query'] = "";
			}
		}
		else
		{
			$searchinput['query'] = "";
		}

		$addon_type = Format::slug ($data['type']);
		$data['current_type'] = ($url_params['type']=="all")? null : $url_params['type'];


		if (isset($url_params['p']))
		{
			if(is_int($url_params['p']) || ctype_digit($url_params['p']))
			{
				$offset = ($url_params['p'] - 1) * $mb['view_range']['addon_view_range'];
				$current_page = $url_params['p'];
			}
			else
			{
				$offset = 0;
				$current_page = 1;
			}
		}
		else
		{
			$offset = 0;
			$current_page = 1;
		}
		$search = new Search();
		//get all the addon filtered by category/query and other
		$data['addon_data'] = $search->searchAddons($searchinput['query'],$data['current_type'],'1', null, $offset, $mb['view_range']['addon_view_range']);
		//Calculate total number of page required
		$page_total = ceil ($data['addon_data']['row_count'] / $mb['view_range']['addon_view_range']);

		//Current page URL without page number parameter
		$generated_url = $link['addon']['home'] . "s/?q=" . urlencode ($searchinput['query']) . "&type=" . $addon_type;

		//Previous and Next page link for search engine optimization
		$generated_url_prev = ($current_page==1)? null : $generated_url.'&p='.($current_page-1);
		$generated_url_next = ($page_total==$current_page)? null : $generated_url.'&p='.($current_page+1);


		if($data['is_overview'])
		{
			$data['addon_data_new'] = $search->searchAddons($searchinput['query'], $data['current_type'], '1', null, 0, 5, "publish_date DESC");
			$data['addon_data_updated'] = $search->searchAddons($searchinput['query'], $data['current_type'], '1', null, 0, 5, "update_date DESC");
			$data['addon_data_like'] = $search->searchAddons($searchinput['query'], $data['current_type'], '1', null, 0, 8, "downloadCount DESC, likesCount DESC", true);

			$data['top_members'] = $addon->getTopMembers();
		}

		if(isset($mb['main_menu']['add-ons']['sub_menu'][$addon_type]['desc']))
		{
			$meta_description = $mb['main_menu']['add-ons']['sub_menu'][$addon_type]['desc'];
		}
		else
		{
			$meta_description = strip_tags($lang['addon_45']);
		}


		include_once $link['root'] . 'views/addons.search.template.php';
		exit();
	}
	else
	{
		header ("HTTP/1.0 404 Not Found");
		include_once $link['404'];
		exit();
	}
}
else
{
	header ("Location: " . $link['addon']['home'] . "s/?type=all&overview");
	exit();
}


/**
 * Generate list of top members who have submitted addons
 *
 * @param $data
 *
 * @return null|string
 */
function top_member_result_generator($data)
{
	global $memberContext, $link, $lang;
	if($data != null){

		$result = '<ul class="sidebar_result">';
		foreach($data as $member) {

			loadMemberData($member['ID_MEMBER']);
			loadMemberContext($member['ID_MEMBER']);
			$avatar_url = ($memberContext[$member['ID_MEMBER']]['avatar']['href'])? $memberContext[$member['ID_MEMBER']]['avatar']['href']: GetImageDir()."usersmall.jpg";

			if($member['rank'] == 1)
			{
				$rank = '<p class="rank admin" title="'.$lang['addon_43'].'"><i class="fa fa-shield"></i>&nbsp;&nbsp;'. Member::rankName($member['rank']) .'</p>';
			}
			elseif($member['rank'] == 2)
			{
				$rank = '<p class="rank mod" title="'.$lang['addon_43'].'"><i class="fa fa-shield"></i>&nbsp;&nbsp;'. Member::rankName($member['rank']) .'</p>';
			}
			elseif($member['rank'] == 5)
			{
				$rank = '<p class="rank elite" title="'.$lang['addon_43'].'"><i class="fa fa-shield"></i>&nbsp;&nbsp;'. Member::rankName($member['rank']) .'</p>';
			}
			else
			{
				$rank = '<p class="rank noob" title="'.$lang['addon_43'].'"><i class="fa fa-shield"></i>&nbsp;&nbsp;'. Member::rankName($member['rank']) .'</p>';
			}

			$result .= '<li><img class="avatar" src="'.$avatar_url.'"/>
							<div class="info">
							<a href ="'.addon_author_url_generator($member['membername']).'">
								<p class="membername">'.$member['membername'].'<i title="'.$lang['addon_44'].'">'.Format::numberFormatSuffix($member['addonUploads']).'</i></p></a>
								'.$rank.'
							</div>
						</li>';
		}
		$result .= '</ul>';

		//var_dump($data);
		return $result;
	}

	return null;
}

/**
 * Generate addon result view
 *
 * @param  array $data  gets the data from Addon class using getAddonFiltered() method
 *
 * @return string        necessary html for generating the addon list view
 */
function addon_result_view_generator($data)
{
	global $lang;

	if ($data != null) {
		$result_view = '<ul class="addon_list_box">';
		foreach ($data as $key => $addon_data) {
			$addon_link = addonUrlGenerator($addon_data);
			$addon_beta_markup = ($addon_data['is_beta']==1)?'<p class="small_info beta">'.$lang['addon_38'].'</p>': '';

			$result_view .= '<li id ="' . $addon_data['ID_ADDON'] . '">
			<div class="addon_list_box_wrapper">
				<a href="' . $addon_link . '">
					<div class="thumb_more" style=\'background-image:url("' . htmlspecialchars($addon_data['thumbnail'], ENT_QUOTES, "UTF-8") . '")\'></div>
					<div class="love"><i class="fa fa-heart"></i><p class="love_count">' . Format::numberFormatSuffix($addon_data['likesCount']) . '</p></div>
					'.$addon_beta_markup.'
				</a>
				<div class="addon_list_box_info">
					<a href="' . $addon_link . '"><p class="title">' . $addon_data['addon_title']. '</p></a>
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
 * @param  int    $current_page
 * @param  string $generated_url url with required param only, no page param included
 *
 * @return string necessary html for generating the pagination bread crumb
 */
function addon_result_pagination_generator($page_total, $current_page, $generated_url) {
	if ($page_total > 0) {
		$pagination_view = '<ul class="pagination">';
		for ($i = 1; $i < $page_total + 1; $i++) {
			if ($current_page == $i) {
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

/**
 * Generate url for getting addon by certain member(author)
 *
 * @param $name     member name
 *
 * @return string
 */
function addon_author_url_generator($name) {
	global $link;

	return $link['addon']['home'] . "s/?q=" . urlencode ($name) . "&type=all";
}

function addon_secondery_nav_generator($addon_type) {
	global $link, $lang, $mb, $url_params, $searchinput;
	$data = '<ul class="left">
	<li class="expand"><a href="javascript:void(0)" onclick="expand_second_menu()"><i class="fa fa-bars"></i></a></li>';

	if (Format::slug ($addon_type) == "all" && empty($searchinput['query'])) {
		$data .= '<li><a href="' . $link['addon']['home'] . 's/?type=all&overview" class="active_menu_link">' . $lang['all'] . '</a></li>';
	} else {
		$data .= '<li><a href="' . $link['addon']['home'] . 's/?type=all&overview" >' . $lang['all'] . '</a></li>';
	}

	foreach ($mb['main_menu']['add-ons']['sub_menu'] as $key => $menu_addon) {
		if ($addon_type == $menu_addon['id'] && empty($searchinput['query'])) {
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
	if(!empty($searchinput['query'])){
		$data .= '<li><a href="" class="active_menu_link">'.$lang['search'].'</a></li>';
	}

	$placeholder = (array_key_exists($url_params['type'], $mb['main_menu']['add-ons']['sub_menu']))? $mb['main_menu']['add-ons']['sub_menu'][$url_params['type']]['title'] : '';

	$data .= '
</ul>
<ul class="right">
	<li class="input_wrap">
		<form method="GET" action="' . $link['addon']['home'] . 's/">
			<input type="search" spellcheck="false" autocomplete="off" autocorrect="off" autocapitalize="off" class="search small_search no_icon" placeholder="'.$lang['search'].' '.$placeholder.'" name="q" value="' . $searchinput['query']  . '"/>
			<button type="submit" class="search_btn_inline"><i class="fa fa-search"></i></button>
			<input type="hidden" name="type" value="'.$url_params['type'].'" />
		</form>
	</li>
</ul>';
	return $data;
}
