<?php
/**
 * Copyright (c) AvikB, some rights reserved.
 * Copyright under Creative Commons Attribution-ShareAlike 3.0 Unported,
 *  for details visit: https://creativecommons.org/licenses/by-sa/3.0/
 *
 * @Contributors:
 * Created by AvikB for noncommercial MusicBee project.
 * Spelling mistakes and fixes from phred and other community memebers.
 */


class Search
{
	public function searchAddons($searchquery_input, $cat_input = null, $status_input = 1) {
		global $connection, $main_menu;
		
		
		//Create arrays for SQL value binding
		if($cat_input == null) {
			$cat_array = array_keys($main_menu['add-ons']['sub_menu']);
		} elseif (!is_array($cat_input)) {
			$cat_array = Format::createSqlArrayParam($cat_input);
		} else {
			$cat_array = $cat_input;
		}
		
		if (!is_array($searchquery_input)) {
			$search_array = preg_filter('/^(.*?)$/', '$0*', Format::createSqlArrayParam($searchquery_input));
		} else {
			$search_array = preg_filter('/^(.*?)$/', '$0*', $searchquery_input);
		}
		
		if (!is_array($status_input)) {
			$status_array = Format::createSqlArrayParam($status_input);
		} else {
			$status_array = $status_input;
		}
		
		$addon_tbl = SITE_ADDON;
		$member_tbl = SITE_MEMBER_TBL;
		
		//Sanitize the array and convert them into '?' sign
		$search = Format::safeSqlSearchArray($search_array);
		$cat = Format::safeSqlArray($cat_array);
		$status = Format::safeSqlArray($status_array);

		
		//Create another single array of all sanitized array, we will pass it into execute() method
		$bindedVal = array_merge($search_array,$search_array,$search_array,$search_array,$cat_array,$status_array);
		
		$sql = "SELECT
				  	ID_ADDON as addon_id, 
				  	ID_AUTHOR as author_id,
				  	membername as author_name, 
				  	addon_title, 
				  	addon_type, 
				  	thumbnail, 
				  	is_beta, 
				  	status, 
				  	tags, 
				  	short_description as description
				  FROM
					{$addon_tbl}
				  		LEFT JOIN
				  	{$member_tbl}
				  		on
					{$addon_tbl}.ID_AUTHOR = {$member_tbl}.ID_MEMBER
				  WHERE
				  MATCH(membername) AGAINST ({$search} IN BOOLEAN MODE)
				  OR 
				  MATCH(tags) AGAINST ({$search} IN BOOLEAN MODE)
				  OR 
				  MATCH(addon_title) AGAINST ({$search} IN BOOLEAN MODE)
				  OR 
				  MATCH(short_description) AGAINST ({$search} IN BOOLEAN MODE)
				  AND
				  addon_type IN ({$cat})
				  AND
				  status IN ({$status})
				  ";
					
		
		if (databaseConnection ()) {
			try {
				$statement = $connection->prepare ($sql);
				$statement->execute ($bindedVal);
				return $statement->fetchAll (PDO::FETCH_ASSOC);
			} catch (Exception $e) { }
		}
	}
}