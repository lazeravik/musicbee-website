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

		$addon_tbl = SITE_ADDON;
		$member_tbl = SITE_MEMBER_TBL;
		
		//Create arrays for SQL value binding
		if($cat_input == null) {
			$cat_array = array_keys($main_menu['add-ons']['sub_menu']);
		} elseif (!is_array($cat_input)) {
			$cat_array = Format::createSqlArrayParam($cat_input);
		} else {
			$cat_array = $cat_input;
		}
		
		if (!is_array($status_input)) {
			$status_array = Format::createSqlArrayParam($status_input);
		} else {
			$status_array = $status_input;
		}

		//sanitize input
		$cat = Format::safeSqlArray($cat_array);
		$status = Format::safeSqlArray($status_array);

		if (!is_array($searchquery_input)) {

			//create an array from spaces between wrods
			$search_array = Format::safeSqlSearchArray($searchquery_input);

			//replace spaces and create a compact string, and then create a single array
			$search_sort_term = array(str_replace(' ', '', $searchquery_input));

			//add % sign on both end and do not replace spaces this time. and then create an array from spaces in between
			$search_sort_term_unmod = preg_filter(['/^/','/$/'], ['%$0','$0%'], array(Format::safeSqlSearchArray($searchquery_input)[0]));

			//Create another array which * sign after all array keys
			$search_array_fulltext = preg_filter('/$/', '$0*', $search_array);

			//If search input has only 1 word in it then use mysql %LIKE% for searching
			if(count($search_array)==1){
				//create another array like "?,?,?,?...", this will prevent sql injection
				$search = Format::safeSqlArray($search_array);

				//create another array which has % sign before and at the end of all array keys
				$search_array_like = preg_filter(['/^/','/$/'], ['%$0','$0%'], $search_array);

				//If search query is blank/* then get all
				if($searchquery_input=="") {
					//Now Merge all the arrays together and
					$bindedVal = array_merge ($cat_array, $status_array);
				} else {
					$bindedVal = array_merge ($cat_array, $status_array, $search_array_like, $search_array_like, $search_array_like, $search_array_fulltext, $search_sort_term, $search_sort_term_unmod);
				}
			} else {

				//Unlike privously where we created array like "?,?,?,...", this time for FULLTEXT search we are omitting
				//commas and instead will use spaces
				$search = Format::safeSqlArrayFullText($search_array);

				//Create another single array of all sanitized array, we will pass it into execute() method
				$bindedVal = array_merge($cat_array,
				                         $status_array,
				                         $search_array_fulltext,
				                         $search_array_fulltext,
				                         $search_array_fulltext,
				                         $search_array_fulltext,
				                         $search_sort_term,
				                         $search_sort_term_unmod);
			}
		}
		


		$sql = "SELECT
					ID_ADDON,
				  	ID_AUTHOR,
				  	membername,
				  	addon_title,
				  	addon_type,
				  	thumbnail,
				  	is_beta,
				  	status,
				  	tags,
				  	short_description as description
				  FROM {$addon_tbl} LEFT JOIN {$member_tbl}
				  	on {$addon_tbl}.ID_AUTHOR = {$member_tbl}.ID_MEMBER
				  WHERE
				  	addon_type IN ({$cat})
				  	AND
				  	status IN ({$status})";

		if($searchquery_input=="") {
			$sql .= "ORDER BY ID_ADDON DESC";
		} else {
			if (count ($search_array) == 1) {
				$sql .= "AND (membername LIKE {$search}
				     OR
				     tags LIKE {$search}
				     OR
				     REPLACE(addon_title,' ','') LIKE {$search}
				     OR
				     MATCH(short_description) AGAINST ({$search} IN BOOLEAN MODE)) ";
			} else {
				$sql .= "AND (MATCH(membername) AGAINST ({$search} IN BOOLEAN MODE)
				     OR
				     MATCH(tags) AGAINST ({$search} IN BOOLEAN MODE)
				     OR
				     MATCH(addon_title) AGAINST ({$search} IN BOOLEAN MODE)
				     OR
				     MATCH(short_description) AGAINST ({$search} IN BOOLEAN MODE)) ";
			}

			$sql .= "ORDER BY (CASE
              WHEN REPLACE(addon_title,' ','') LIKE ? THEN 0
              WHEN addon_title LIKE ? THEN 1
              ELSE 2
              END), addon_title ASC";
		}


		if (databaseConnection ()) {
			try {
				$statement = $connection->prepare ($sql);
				$statement->execute ($bindedVal);
				return $statement->fetchAll (PDO::FETCH_ASSOC);
			} catch (Exception $e) { }
		}
	}
}