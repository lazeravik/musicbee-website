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


use App\Lib\Utility\Template;

$headerTemplate = new Template("header");
$headerTemplate->setData(
    "title",
    __("The requested page does not exist. Make sure you typed the URL correctly")
);
$headerTemplate->setData(
    "description",
    ""
);
$headerTemplate->setData(
    "keywords",
    ""
);
$headerTemplate->setData("isFontHelperDisabled", false);
$headerTemplate->render();

include_once path('template-dir').'mainmenu.template.php';
?>
<div id="main">
    <div id="main_panel">
        <div class="mb_landing align_right">
            <div class="sub_content">
                <div class="hero_text_top">
                    <h1><?php echo __("404"); ?></h1>
                    <h2><?php echo __("Hey there! Are you lost?"); ?></h2>
                    <br/>
                    <p><?php echo __("The requested page does not exist. Make sure you typed the URL correctly"); ?></p>
                    <br/>
                    <br/>
                    <hr class="line"/>
                    <a href="<?php echo $link['url']; ?>" class="btn btn_green"><?php echo __("Go to home"); ?>&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>