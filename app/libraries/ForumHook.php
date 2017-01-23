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


namespace App\Lib;

class ForumHook
{
    private static function getHook()
    {
        global $context;
        require_once '../forum/SSI.php';
        return $context;
    }

    public static function getHookContext()
    {
        global $link;

        $hookContext = self::getHook();
        $avatar = !empty($hookContext['user']['avatar']['href'])? : $link['img-dir'].'usersmall.jpg';
        $context = [
            'user'           => array(
                'id'              => $hookContext['user']['id'],
                'is_logged'       => $hookContext['user']['is_logged'],
                'is_guest'        => $hookContext['user']['is_guest'],
                'is_admin'        => $hookContext['user']['is_admin'],
                'is_mod'          => $hookContext['user']['is_mod'],
                'is_elite'        => false,
                'is_newbie'       => false,
                'rank_name'       => null,
                'need_approval'   => true,
                'can_mod'         => $hookContext['user']['can_mod'],
                'username'        => $hookContext['user']['name'],
                'email'           => $hookContext['user']['email'],
                'name'            => $hookContext['user']['name'],
                'messages'        => $hookContext['user']['messages'],
                'unread_messages' => $hookContext['user']['unread_messages'],
                'avatar'          => $avatar,
            ),
        ];

        return $context;
    }
}
