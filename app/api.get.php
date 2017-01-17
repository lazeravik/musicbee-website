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

/**
 * @author Avik B
 * @version 1.0
 */

/**
 * All data are JSON formatted. Currently JSON is the only format we serve, maybe XML in future... :S
 * For result list Maximum limit is 20, default is 5
 *
 *****************************************************************************************************
 * API Links:
 *****************************************************************************************************
 *
 * MusicBee beta, stable and patch release info
 *      http://getmusicbee.com/api/1.0/?type=json&action=release-info
 *
 * ADDON Data
 *      http://getmusicbee.com/api/1.0/?type=json&action=addon-info&id=1
 *
 * ADDON List by UserID
 *      http://getmusicbee.com/api/1.0/?type=json&action=addon-list&authorid=1&limit=10
 *
 * ADDON Search by Term
 *      http://getmusicbee.com/api/1.0/?type=json&action=addon-search&search=windows&page=1&limit=10
 *
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/app/functions.php';
require_once $link['root'] . 'classes/Addon.php';
require_once $link['root'] . 'classes/Search.php';
require_once $link['root'] . 'classes/Member.php';

const default_result_limit = 5;
const max_result_limit = 20;


if (isset($params[3])) {
    //remove the ? sign from the string and convert the url paramenter into an array
    parse_str(str_replace("?", "", $params[3]), $url_params);

    if (isset($url_params['type']) && isset($url_params['action'])) {
        //if the request is for JSON then intialize json parsing
        if ($url_params['type'] == "json") {
            //switch between actions
            switch ($url_params['action']) {
                case 'release-info':
                    releaseInfo();
                    break;

                case 'addon-info':
                    addonInfo();
                    break;

                case 'addon-list':
                    addonList();
                    break;

                case 'addon-search':
                    addonSearchJson();
                    break;

                default:
                    # code...
                    break;
            }
        } elseif ($url_params['type'] == "html") {
            //switch between actions
            switch ($url_params['action']) {
                case 'addon-search-autocomplete':
                    addonSearchAutoComplete();
                    break;
                case 'user-search-autocomplete':
                    userSearchAutoComplete();
                    break;

                default:
                    # code...
                    break;
            }
        }
    }
}
/**
 * Generate MusicBee beta, stable and patch release info
 */
function releaseInfo()
{
    global $mb;
    printJson(json_encode($mb['musicbee_download']));
}

/**
 * Search addons by search term, filter between addon type
 */

function addonSearchJson()
{
    printJson(json_encode(addonSearch()));
}

function userSearchAutoComplete()
{
    global $url_params, $link, $memberContext;

    $resultLimit = Format::clamp(isset($url_params['limit'])?$url_params['limit']:20, 30, 0);
    $searchData = Member::SearchUsernames($url_params['search'], $resultLimit);

    if (!$searchData) {
        return;
    }

    foreach ($searchData as $member) {
        loadMemberData($member['ID_MEMBER']);
        loadMemberContext($member['ID_MEMBER']);
        $avatar_url = ($memberContext[$member['ID_MEMBER']]['avatar']['href'])? $memberContext[$member['ID_MEMBER']]['avatar']['href']: GetImageDir()."usersmall.jpg";
        $username = $member['membername'];
        $uid = md5($username);
        $email = $memberContext[$member['ID_MEMBER']]['email'];

        $html = <<<HTML
<li id="$uid">
	<ul>
		<input type="hidden" name="user_id" value="{$member['ID_MEMBER']}">
		<li>
			<img src="$avatar_url">
		</li>
		<li>
			<p class="title">$username</p>
			<p>$email</p>
		</li>
		<li>
			<button class="select_popup btn btn_blue" onclick="set_user('$uid', event); event.preventDefault();"><i class="fa fa-check"></i></button>
		</li>
	</ul>
</li>
HTML;
        echo $html;
        //var_dump($result);
    }

    exit();
}

function addonSearchAutoComplete()
{
    global $lang;
    $searchData = addonSearch();


    foreach ($searchData['addon_data']['result'] as $result) {
        $thumb = Format::imgurResizer($result['thumbnail'], "s");
        $title = $result['addon_title'];
        $author = $lang['addon_15'].' '.$result['membername'];
        $url = addonUrlGenerator($result);
        $uid = md5($title.$author);

        $html = <<<HTML
<li id="$uid">
	<ul>
		<input type="hidden" name="addon_id" value="{$result['ID_ADDON']}">
		<li>
			<img src="$thumb">
		</li>
		<li>
			<p class="title"><a href="$url">$title</a></p>
			<p>$author</p>
		</li>
		<li>
			<button class="select_popup btn btn_blue" onclick="set_item('$uid', event); event.preventDefault();"><i class="fa fa-check"></i></button>
		</li>
	</ul>
</li>
HTML;
        echo $html;
        //var_dump($result);
    }

    exit();
}

function addonSearch()
{
    global $url_params, $mb, $link;

    $limit = (isset($url_params['limit']))?checkResultLimit($url_params['limit']): $mb['view_range']['addon_view_range'];

    if (isset($url_params['search'])) {
        if (isset($url_params['cat'])) {
            if ($url_params['cat'] == 'all') {
                $url_params['cat'] = null;
            }
        } else {
            $url_params['cat'] = null;
        }

        if (isset($url_params['page'])) {
            if (is_int($url_params['page']) || ctype_digit($url_params['page'])) {
                $offset = ($url_params['page'] - 1) * $limit;
                $data['current_page'] = $url_params['page'];
            } else {
                $offset = 0;
                $data['current_page'] = 1;
            }
        } else {
            $offset = 0;
            $data['current_page'] = 1;
        }

        $searchinput['query'] = (!empty($url_params['search']))? htmlspecialchars(trim($url_params['search']), ENT_QUOTES, "UTF-8"): '';

        $search = new Search();

        //get all the addon filtered by category/query and other
        $data['addon_data'] = $search->searchAddons($searchinput['query'], $url_params['cat'], '1', null, $offset, $limit);

        //Calculate total number of page required
        $data['total_page'] = ceil($data['addon_data']['row_count'] / $limit);

        $data['page_url'] = $link['addon']['home'] . "s/?q=" . urlencode($searchinput['query']) . "&type=" . $url_params['cat'];
        $data['prev_page_url'] = ($data['current_page']==1)? null : $data['page_url'].'&p='.($data['current_page']-1);
        $data['next_page_url'] = ($data['total_page']==$data['current_page'])? null : $data['page_url'].'&p='.($data['current_page']+1);


        return $data;
    }
}

/**
 * Generate a List of Addon submitted by a member, you will need memberid/userid
 */
function addonList()
{
    global $url_params;

    if (isset($url_params['authorid'])) {
        $addon = new Addon();
        $limit = (isset($url_params['limit']))? checkResultLimit($url_params['limit']) : default_result_limit;

        $data = $addon->getAddonListByMember($url_params['authorid'], $limit);

        printJson(json_encode($data));
    }
}

/**
 * Get ALL the info about an addon, you will need addon id
 */
function addonInfo()
{
    global $url_params;

    if (isset($url_params['id'])) {
        $addon = new Addon();
        $addon_data = $addon->getAddonData($url_params['id']);

        printJson(json_encode($addon_data));
    }
}

function checkResultLimit($limit)
{
    if (ctype_digit($limit)) {
        if ($limit > max_result_limit) {
            return max_result_limit;
        }
    }

    return default_result_limit;
}

/**
 * Prints the data to a human eye, all in JSON
 * @param $encodedData
 */
function printJson($encodedData)
{
    header('Content-Type: application/json');
    print_r($encodedData);
}
