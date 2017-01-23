<?php
/**
 * Copyright (c) 2017 AvikB, some rights reserved.
 *  Copyright under Creative Commons Attribution-ShareAlike 3.0 Unported,
 *  for details visit: https://creativecommons.org/licenses/by-sa/3.0/
 *
 * @Contributors:
 * Created by AvikB for noncommercial MusicBee project.
 *  Spelling mistakes and fixes from community members.
 *
 */

use App\Lib\ForumHook;
$context = ForumHook::getHookContext();

$menu =  array(
    'dashboard'       => array(
        'title'       => __("Dashboard"),
        'href'        => $link['addon']['dashboard'],
        'restriction' => 'login',
        'sub_menu'    => array(),
    ),
    'member-panel'    => array(
        'title'       => '<img src="'.$context['user']['avatar'].'" class="user_avatar">',

        'href'        => $link['forum'].'?action=profile',
        'restriction' => 'login',
        'sub_menu'    => array(
            'user-profile' => array(
                'title' => ''.sprintf(__('Hey, %1$s'), $context['user']['name']).'',
                'href'        => $link['forum'].'?action=profile',
            ),
            'line1'        => array('title' => "<hr class=\"line\"/>",),
            'admin-panel'  => array(
                'title'       => __("Web Admin"),
                'href'        => $link['addon']['dashboard'].'#admin_setting',
                'icon'        => "<i class=\"fa fa-desktop\"></i>",
                'restriction' => 'admin',
            ),
            'forum-admin'  => array(
                'title'       => __("Forum Admin"),
                'href'        => $link['admin']['forum-panel'],
                'icon'        => "<i class=\"fa fa-comments\"></i>",
                'restriction' => 'admin',
            ),
            'line2'        => array('title' => "<hr class=\"line\"/>",),
            'sign-out'     => array(
                'title' => __("Sign Out"),
                'href'  => $link['logout'],
                'icon'  => "<i class=\"fa fa-sign-out\"></i>",
            ),
        ),
    ),
    'download'        => array(
        'title'    => __("Download"),
        'href'     => $link['download'],
        'sub_menu' => array(),
    ),
    'add-ons'         => array(
        'title'    => __("Add-ons"),
        'href'     => $link['addon']['home'],
        'sub_menu' => array(
            '1' => array(
                'title' => __("Skins"),
                'href'  => $link['addon']['home']."s/?type=1",
                'icon'  => "<i class=\"fa fa-paint-brush\"></i>",
                'desc'  => __("Make MusicBee look the way you want"),
                'id'    => 1,
            ),
            '2' => array(
                'title' => __("Plugins"),
                'href'  => $link['addon']['home']."s/?type=2",
                'icon'  => "<i class=\"fa fa-plug\"></i>",
                'desc'  => __("Add features/functionality to MusicBee"),
                'id'    => 2,
            ),
            '3' => array(
                'title' => __("Visualizer"),
                'href'  => $link['addon']['home']."s/?type=3",
                'icon'  => "<i class=\"fa fa-bar-chart\"></i>",
                'desc'  => __("Get colorful visualizers for an eye pleasing experience"),
                'id'    => 3,
            ),
            '4' => array(
                'title' => __("Theater Mode"),
                'href'  => $link['addon']['home']."s/?type=4",
                'icon'  => "<i class=\"fa fa-arrows-alt\"></i>",
                'desc'  => __("Get a full theater mode experience for MusicBee"),
                'id'    => 4,
            ),
            '5' => array(
                'title' => __("Misc"),
                'href'  => $link['addon']['home']."s/?type=5",
                'icon'  => "<i class=\"fa fa-ellipsis-h\"></i>",
                'desc'  => __("Other useful add-ons for enhancing your MusicBee experience"),
                'id'    => 5,
            ),
        ),
    ),
    'forum'           => array(
        'title'    => __("Forum"),
        'href'     => $link['forum'],
        'sub_menu' => array(),
    ),
    'help'            => array(
        'title'    => __("Help"),
        'href'     => $link['faq'],
        'sub_menu' => array(
            'faq' => array(
                'title' => __("FAQ & Help"),
                'href'  => $link['faq'],
                'icon'  => "<i class=\"fa fa-question\"></i>",
            ),
            'api' => array(
                'title' => __("Developer API"),
                'href'  => $link['api'],
                'icon'  => "<i class=\"fa fa-code\"></i>",
            ),
            'line2'        => array('title' => "<hr class=\"line\"/>",),
            'release-note' => array(
                'title' => __("Release Notes"),
                'href'  => $link['release-note'],
                'icon'  => "<i class=\"fa fa-sticky-note-o\"></i>",
            ),
            'press' => array(
                'title' => __("Press & Media"),
                'href'  => $link['press'],
                'icon'  => "<i class=\"fa fa-bullhorn\"></i>",
            ),
            'line3'        => array('title' => "<hr class=\"line\"/>",),
            'bug' => array(
                'title' => __("Report a bug"),
                'href'  => $link['bugreport'],
                'icon'  => "<i class=\"fa fa-bug\"></i>",
                'hide'  => true,
            ),
            'wiki' => array(
                'title' => __("MusicBee Wiki"),
                'href'  => !empty($link['wiki'])?: null,
                'icon'  => "<i class=\"fa fa-wikipedia-w\"></i>",
                'target'=> '_blank',
                'hide'  => true,
            ),
        ),
    ),
);
