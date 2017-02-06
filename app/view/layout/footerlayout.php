<?php
function getFooterHtml() {
    global $lang;
    $setting = setting();
    $link = path();
    $menu = menu();
    $release = getStableReleasedata();

    $mbName        = sprintf($lang['get_mb'], $release['name']);
    $mbVersion     = sprintf($lang['version_number'], $release['version']);
    $mbSupportedOs = sprintf($lang['for_os'], $release['supported_os']);
    $mbReleaseDate = sprintf($lang['released_on_date'], $release['release_date']);

$footerhtml = <<<HTML
<footer class="footer_section">
    <div class="widget">
        <div id="widgetDownload" class="widgetCommon">
            <h4>{$lang['get_latest_mb']}</h4>
            <ul>
                <li>
                    <a data-gettext-id="footer-download-btn" href="{$link['download']}" class="btn btn_blacknwhite">
                        <i class="fa fa-download"></i>{$mbName}
                    </a>
                </li>
                <li>{$mbVersion}</li>
                <li>{$mbSupportedOs}</li>
                <li>{$mbReleaseDate}</li>
                <li class="line"></li>
                <li>{$lang['get_notified_new_release']}</li>
                <li>
                    <a href="{$link['rss']}" class="btn btn_yellow" target="_blank">
                        <i class="fa fa-rss"></i>{$lang['subscribe_rss']}
                    </a>
                </li>
            </ul>
        </div>
        <div id="widgetCustomize" class="widgetCommon">
            <h4>{$lang['addons_for_mb']}</h4>
            <ul class="footer_list_menu">
HTML;

foreach($menu['add-ons']['sub_menu'] as $key => $menu_addon)
{
    $footerhtml .= "<li><a href=\"".$menu_addon['href']." \">";

    if(!empty($menu_addon['icon']) && empty($no_menu_icon)) {
        $footerhtml .= $menu_addon['icon'].'';
    }
        $footerhtml .=  $menu_addon['title']."</a></li>";
}

$footerhtml .= <<<HTML
     </ul>
        </div>
        <div id="widgetCommunity" class="widgetCommon">
            <h4>{$lang['more']}</h4>
            <ul class="footer_list_menu">
                <li>
                    <a href="{$link['api']}"><i class="fa fa-code"></i>{$lang['dev_api']}</a>
                </li>

                <li>
                    <a href="{$link['bugreport']}"><i class="fa fa-bug"></i>{$lang['report_bug']}</a>
                </li>
HTML;
if(!empty($setting['wishlistLink'])) {
    $footerhtml .= <<<HTML
        <li><a href="{$setting['wishlistLink']}"><i class="fa fa-heartbeat"></i>{$lang['add_new_wishlist']}</a></li>
HTML;
}
$footerhtml .= <<<HTML
                <li>
                    <a href="{$link['press']}"><i class="fa fa-bullhorn"></i>{$lang['press']}</a>
                </li>
HTML;

if(!empty($setting['paypalDonationLink'])) {
$footerhtml .= <<<HTML
                <ul class="footer_donation" >
                    <li >{$lang['support_mb_with_donation']}</li >
                    <li >
                        <a href = "{$setting['paypalDonationLink']}" target = "_blank" class="btn btn_blue" >
                            <i class="fa fa-paypal" ></i >{$lang['paypal_donation']}
                        </a >
                    </li >
                </ul >
HTML;
}
$footerhtml .= <<<HTML
            </ul>
        </div>
    </div>
    <div class="footer_credit_wrap">
        <ul class="footer_credit">
            <li>
                <p><a href="{$link['credit']}" class="credit_link">{$lang['site_built_with_love']}</a> | v{$setting['version']}</p>
                <p id="copyright">{$lang['musicbee_copyright']}</p>
            </li>
        </ul>
    </div>
</footer>
HTML;
    return $footerhtml;
}