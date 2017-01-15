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

include_once $_SERVER['DOCUMENT_ROOT'].'/app/functions.php'; ?>

<meta charset="<?php echo $mb['charset']; ?>">
<meta name="language" content="<?php echo $language['name']; ?>">

<?php if($mb['website']['is_test']): ?>
<meta name="robots" content="noindex">
<?php endif; ?>

<!-- responsive mobile deivicew support -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Load external stylesheets and scripts -->
<link rel="stylesheet" type="text/css" href="<?php echo GetStyleDir(); ?>dist/mb_main.css?<?php echo $mb['website']['ver']; ?>">
<link rel="stylesheet" href="<?php echo GetStyleDir(); ?>font-awesome.min.css?4.5.0">
<link rel="alternate" href="<?php echo $link['url']; ?>rss.xml" title="MusicBee updates" type="application/rss+xml"/>
<link rel="help" href="<?php echo $link['faq']; ?>"/>

<link rel="apple-touch-icon" href="<?php echo $link['url']; ?>img/mb_icon_touch.png?<?php echo $mb['website']['ver']; ?>"/>
<link rel="shortcut icon" href="<?php echo $link['favicon']; ?>?<?php echo $mb['website']['ver']; ?>"/>

<!-- Microsoft Tile support -->
<meta content="<?php echo $lang['mb']; ?>" name="application-name"/>
<meta content="#ffa13f" name="msapplication-TileColor"/>

<meta property="og:locale" content="<?php echo $language['meta']; ?>"/>
<meta property="og:type" content="website"/>
