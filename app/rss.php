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

header("Content-Type: application/xml; charset=UTF-8");
require_once $_SERVER['DOCUMENT_ROOT'].'/app/functions.php';

$feed='<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0"
xml:base="'.$link['url'].'"
xmlns:atom="http://www.w3.org/2005/Atom"
xmlns:sy="http://purl.org/rss/1.0/modules/syndication/">
<channel>
	<title>MusicBee Updates</title>
	<language>en-US</language>
	<link>'.$link['url'].'</link>
	<description>Get MusicBee release updates</description>
	<atom:link href="'.$link['rss'] .'.php" rel="self" type="application/rss+xml" />
	<sy:updatePeriod>hourly</sy:updatePeriod>
	<sy:updateFrequency>1</sy:updateFrequency>
	<copyright>Copyright (C) '.date("Y").' '.$link['url'].'</copyright>
	<item>
		<title> MusicBee v.'.$mb['musicbee_download']['stable']['version'].' released</title>
		<description>A new version of MusicBee is released, click to go to download page.</description>
		<link>'.$link['download'].'</link>
		<pubDate>'.$mb['musicbee_download']['stable']['release_date'].'</pubDate>
		<guid isPermaLink="false">#'.md5($mb['musicbee_download']['stable']['version']).'</guid>
	</item>
</channel>
</rss>';

echo $feed;
