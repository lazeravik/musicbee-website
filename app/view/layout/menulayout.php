<?php


function getMenuHtml()
{
    global $lang;

    $link = path();
    $menuhtml = "";
    $menuhtml .= <<<HTML
<nav class="mainmenu" id="main_menu">
    <div class="menu_wrapper">
        <ul class="menu_position menu_left">
            <li class="expand">
                <a href="javascript:void(0)" ><i class="fa fa-bars"></i></a>
            </li>
            <li class="logo">
                <a href="{$link['url']}" ><img src="{$link['img-dir']}musicbee.png" />{$lang["musicbee"]}</a>
            </li>
HTML;

    foreach (menu() as $key => $menu_item) {
        if (!isset($menu_item['restriction'])) {
            $menuhtml .= '<li>
                                    <a href="' . $menu_item['href'] . '">' . $menu_item['title'] . '</a>';
            if (count($menu_item['sub_menu']) > 0) {
                $menuhtml .= '<ul class="nav_dropdown_sub primary_submenu">';
                foreach ($menu_item['sub_menu'] as $sub_item_key => $sub_item) {
                    if (isset($sub_item['restriction'])) {
                        if ($sub_item['restriction'] == 'admin' && forumhook()['user']['is_admin']) {
                            $menuhtml .= printMenuItems($sub_item);
                        }
                    } else {
                        $menuhtml .= printMenuItems($sub_item);
                    }
                }
                $menuhtml .= "</ul>";
            }
            $menuhtml .= "</li>";
        }
    }
    $menuhtml .= <<<HTML
</ul>
<ul class="menu_position menu_right">
HTML;

    if (!forumhook()['user']['is_guest']) {
        foreach (menu() as $key => $logged_menu_item) {
            if (isset($logged_menu_item['restriction'])) {
                $menuhtml .= '<li class="' . $key . '">
								<a href="' . $logged_menu_item['href'] . '">' . $logged_menu_item['title'] . '</a>';
                if (count($logged_menu_item['sub_menu']) > 0) {
                    $menuhtml .= '<ul class="nav_dropdown_sub dropdown_right primary_submenu" >';
                    foreach ($logged_menu_item['sub_menu'] as $sub_item_key => $sub_item) {
                        if (isset($sub_item['restriction'])) {
                            if ($sub_item['restriction'] == 'admin' && forumhook()['user']['is_admin']) {
                                $menuhtml .= printMenuItems($sub_item);
                            }
                        } else {
                            $menuhtml .= printMenuItems($sub_item);
                        }
                    }
                    $menuhtml .= "</ul>";
                }
                $menuhtml .= "</li>";
            }
        }
        $unreadmsg = forumhook()['user']['unread_messages'];
        $menuhtml .= <<<HTML
     <li class="message_count">
					<a href="{$link['forum']}?action=pm"
					   class="secondery_nav_menu_button"
					   title="Messages: {$unreadmsg}">
HTML;

        if (forumhook()['user']['unread_messages'] > 0) {
            $menuhtml .= <<<HTML
    <span class="message_new"><i class="fa fa-envelope-open-o"></i>{$unreadmsg}</span>
HTML;
        } else {
            $menuhtml .= <<<HTML
    <span class="message_no"><i class="fa fa-envelope-o"></i></span>
HTML;
        }

        $menuhtml .= "</a></li>";
    } else {
        $menuhtml .= <<<HTML
     <li>
         <a href="{$link['login']}"><i class="fa fa-user"></i> {$lang["login"]}</a>
     </li>
     <li>
         <a href="{$link['register']}">{$lang["register"]}</a>
     </li>
HTML;
    }
    $menuhtml .= <<<HTML
</ul>
</div>
</nav>
<noscript>
    <p class="show_info info_red">{$lang["unsupported_browser"]}</p>
</noscript>
HTML;

//    if (file_exists($link['root'] . 'installer/install.php') && $mb['website']['show_warning']) {
//        $menuhtml .= <<<HTML
//<p class="show_info info_red">{$lang["security_warning"]}</p>
//HTML;
//    }

    return $menuhtml;
}



function printMenuItems($menuArray){
    $html = "";

    if(empty($menuArray['hide']) && !empty($menuArray['href'])) {
        $html .= "<li>";
        if (!empty($menuArray['href'])) {
            if (array_key_exists('target', $menuArray)) {
                $target = $menuArray['target'];
            } else {
                $target = '';
            }
            $html .= '<a href="' . $menuArray['href'] . '" target="' . $target . '">';
        }


        $html .= (!empty($menuArray['icon']) && empty($no_menu_icon)) ? $menuArray['icon'] : "";
        $html .= $menuArray['title'];


        if (!empty($menuArray['href'])) {
            $html .= "</a>";
        }

        $html .= "</li>";
    }
    return $html;
}