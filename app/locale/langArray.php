<?php

function lang($lang_key = null){
    global $lang;

    if($lang_key != null){
        if(isset($lang[$lang_key])){
            return $lang[$lang_key];
        } else {
            return null;
        }
    }

    return $lang;
}

$lang["musicbee"] = __("MusicBee");
$lang["login"] = __("Login");
$lang["register"] = __("Register");
$lang['download'] = __('Download');
$lang['addons'] = __('Add-ons');
$lang['forum'] = __('Forum');
$lang['help'] = __('Help');
$lang['web_admin'] = __('Web Admin');
$lang['forum_admin'] = __('Forum Admin');
$lang['dashboard'] = __('Dashboard');
$lang['sign_out'] = __('Sign Out');
$lang['login'] = __('Login');
$lang['hey_username'] = __('Hey, %1$s');
$lang['all'] = __('All');
$lang['skins'] = __('Skins');
$lang['plugins'] = __('Plugins');
$lang['visualizer'] = __('Visualizer');
$lang['equalizer'] = __('Equalizer');
$lang['theater_mode'] = __('Theater Mode');
$lang['misc'] = __('Misc');
$lang['register'] = __('Register');
$lang['releasenote'] = __("Release Note");

$lang['skin_description'] =         __('Make MusicBee look the way you want');
$lang['plugin_description'] =       __('Add features/functionality to MusicBee');
$lang['visualizer_description'] =   __('Get colorful visualizers for an eye pleasing experience');
$lang['theater_description'] =      __('Get a full theater mode experience for MusicBee');
$lang['misc_description'] =         __('Other useful add-ons for enhancing your MusicBee experience');


$lang["unsupported_browser"] = __("Your browser does not support javascript (or it is disabled). Please use a browser with javascript or enable it.<br/>We need javascript to function properly otherwise some things won't work.");
$lang["security_warning"] = __("<b>SECURITY WARNING!</b><br>Please delete <code>install.php</code> file from the directory");
$lang['get_mb'] = __("Get %s");
$lang['get_latest_mb'] = __('Get MusicBee');
$lang['more'] = __('More');
$lang['subscribe_rss'] = __('Subscribe to RSS');
$lang['dev_api'] = __('Developer API');
$lang['get_notified_new_release'] = __('Want to get notified of new releases?');
$lang['report_bug'] = __('Report a bug');
$lang['addons_for_mb'] = __('Add-ons for MusicBee');
$lang['support_mb_with_donation'] = __('Support MusicBee with a voluntary donation');
$lang['paypal_donation'] = __('Donate with Paypal');
$lang['add_new_wishlist'] = __('Add a new feature to the Wishlist');
$lang['site_built_with_love'] = __('Site built with <i class="fa fa-heart" style="color: #F44336;"></i> for the community');
$lang['musicbee_copyright'] = sprintf(__('Copyright &copy; Steven Mayall 2008-%s, All Rights Reserved.'), date ('Y'));
$lang['press'] = __('Press & Media');
$lang['twitter'] = __('Twitter');
$lang['faq'] = __('FAQ & Help');
$lang['mb_wiki'] = __('MusicBee Wiki');
$lang['version_number'] = __('Version %s');
$lang['released_on_date'] = __('Released on %s');
$lang['for_os'] = __('For %s');

$lang['download_page_title'] = __("Downloads");
$lang['download_page_desc'] = __("Get MusicBee Installer executable or Portable and start enjoying MusicBee");

$lang['help_page_title'] = __("MusicBee help and FAQ");
$lang['help_page_desc'] = __("Do you need help with MusicBee? Perhaps you're just getting started or having trouble figuring out a feature. This is the right place for getting all your answers");

$lang['wikia_content_get_err'] = __("ERROR: Can not get content from WIKIA");

$lang['credit_page_title'] = __("Website Credits & Licenses");
$lang['credit_page_desc']  = __("See the people involved in MusicBee website");

$lang['api_page_title'] = __("MusicBee API for developers");
$lang['api_page_desc']  = __("Get the MusicBee API kit and create plugins or integrating 3rd party services into MusicBee! Available in C#, C++");


$lang['releasenote_page_title'] = __("MusicBee - Release Notes");
$lang['releasenote_page_desc']  = __("Release Notes/Change Logs for MusicBee, See the changes made throughout MusicBee's journey");
