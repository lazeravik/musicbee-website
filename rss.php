<?php 
header("Content-Type: application/xml; charset=UTF-8");
require_once $_SERVER['DOCUMENT_ROOT'].'/functions.php';

$feed='<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" 
xml:base="'.$siteUrl.'"
xmlns:atom="http://www.w3.org/2005/Atom"
xmlns:sy="http://purl.org/rss/1.0/modules/syndication/">
<channel>
	<title>MusicBee Updates</title>
	<language>en-US</language>
	<link>'.$siteUrl.'</link>
	<description>Get MusicBee release updates</description>
	<atom:link href="'.$link['rss'] .'.php" rel="self" type="application/rss+xml" />
	<sy:updatePeriod>hourly</sy:updatePeriod>
	<sy:updateFrequency>1</sy:updateFrequency>
	<copyright>Copyright (C) '.date("Y").' '.$siteUrl.'</copyright>
	<item>
		<title> MusicBee v.'.$release['stable']['version'].' released</title>
		<description>A new version of MusicBee is released, click to go to download page.</description>
		<link>'.$link['download'].'</link>
		<pubDate>'.$release['stable']['date'].'</pubDate>
		<guid isPermaLink="false">#'.md5($release['stable']['version']).'</guid>
	</item>
</channel>
</rss>';

echo $feed;
?>