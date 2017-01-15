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

header("Content-Type: application/opensearchdescription+xml; charset=UTF-8");
require_once $_SERVER['DOCUMENT_ROOT'].'/app/functions.php';

$feed='<?xml version="1.0" encoding="UTF-8" ?>
 <OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
 <ShortName>'.$lang['addon_opensearch_title'].'</ShortName>
 <Description>Search for MusicBee add-ons</Description>
 <Image height="16" width="16" type="image/x-icon">'.$link['favicon'].'</Image>
 <Url type="text/html" template="'.$link['addon']['home'].'s/?q={searchTerms}"/>
 <SyndicationRight>open</SyndicationRight>
 <AdultContent>false</AdultContent>
 <Language>'.$language['meta'].'</Language>
 <OutputEncoding>'.$mb['charset'].'</OutputEncoding>
<InputEncoding>'.$mb['charset'].'</InputEncoding>
 </OpenSearchDescription>
';

echo $feed;
