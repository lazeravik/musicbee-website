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
	/**
	 * @param string $searchquery
	 * @param null   $cat_input
	 * @param int    $status_input
	 * @param null   $authorid
	 * @param int    $offset
	 * @param int    $range
	 * @param string $orderby
	 *
	 * @return mixed
	 */
	public function searchAddons($searchquery, $cat_input = null, $status_input = 1, $authorid = null, $offset=0, $range=20, $orderby = "ID_ADDON DESC") {
		global $connection, $mb;
		
		//Create arrays for SQL value binding
		if ($cat_input == null) {
			$cat_array = array_keys ($mb['main_menu']['add-ons']['sub_menu']);
		} elseif (!is_array ($cat_input)) {
			$cat_array = Format::createSqlArrayParam ($cat_input);
		} else {
			$cat_array = $cat_input;
		}
		
		if (!is_array ($status_input)) {
			$status_array = Format::createSqlArrayParam ($status_input);
		} else {
			$status_array = $status_input;
		}

		//sanitize input
		$cat = Format::safeSqlArray ($cat_array);
		$status = Format::safeSqlArray ($status_array);

		if (!is_array ($searchquery)) {
			$searchquery = trim ($searchquery);

			//create an array from spaces between wrods
			$search_array = Format::safeSqlSearchArray ($searchquery);

			//replace spaces and create a compact string, and then create a single array
			$search_sort_term = array(str_replace (' ', '', $searchquery));

			//add % sign on both end and do not replace spaces this time. and then create an array from spaces in between
			$search_sort_term_unmod = preg_filter (['/^/','/$/',], ['%$0','$0%',], array(Format::safeSqlSearchArray ($searchquery)[0]));

			//Create another array which * sign after all array keys
			$search_array_fulltext = preg_filter ('/$/', '$0*', $search_array);

			//create another array which has % sign before and at the end of all array keys
			$search_array_like = preg_filter (['/^/','/$/',], ['%$0','$0%',], $search_array);

			$cat_status_array = array_merge ($cat_array, $status_array);

			//If placeholder input has only 1 word in it then use mysql %LIKE% for searching
			if (count ($search_array) == 1) {
				//create another array like "?,?,?,?...", this will prevent sql injection
				$placeholder = Format::safeSqlArray ($search_array);

				//If search query is blank then get all
				if (empty($searchquery)) {
					//Now Merge all the arrays together and
					$bindedVal = $cat_status_array;
				} else {
					if ($authorid == null) {
						$bindedVal = array_merge ($cat_status_array, $search_array_like, $search_array_like, $search_array_like, $search_array_fulltext, $search_sort_term, $search_sort_term_unmod);
					} else {
						$bindedVal = array_merge ($cat_status_array, $search_array_like, $search_array_like, $search_array_fulltext, $search_sort_term, $search_sort_term_unmod);

					}
				}
			} else {

				//Unlike privously where we created array like "?,?,?,...", this time for FULLTEXT placeholder we are omitting
				//commas and instead will use spaces
				$placeholder = Format::safeSqlArrayFullText ($search_array);

				if ($authorid == null) {
					//Create another single array of all sanitized array, we will pass it into execute() method
					$bindedVal = array_merge ($cat_status_array, $search_array_fulltext, $search_array_fulltext, $search_sort_term_unmod, $search_array_fulltext, $search_array_fulltext, $search_sort_term, $search_sort_term_unmod);
				} else {
					$bindedVal = array_merge ($cat_status_array, $search_array_fulltext, $search_sort_term_unmod, $search_array_fulltext, $search_array_fulltext, $search_sort_term, $search_sort_term_unmod);
				}
			}
		}

		$search_sql     = $this->generateQuery("result", $range, $offset, $placeholder, $search_array, $authorid, $status, $cat, $searchquery,$orderby);
		$row_count_sql  = $this->generateQuery("count", $range, $offset, $placeholder, $search_array, $authorid, $status, $cat, $searchquery,$orderby);

		if (databaseConnection ()) {
			try {
				//var_dump(showQuery($search_sql, $bindedVal));
				//var_dump($bindedVal);

				//Get the result data
				$search_statement = $connection->prepare ($search_sql);
				$search_statement->execute ($bindedVal);

				//Get the total row count for pagination
				$count_statement = $connection->prepare($row_count_sql);
				$count_statement->execute ($bindedVal);

				$data['result'] = $search_statement->fetchAll (PDO::FETCH_ASSOC);
				$data['row_count'] = count($count_statement->fetchAll (PDO::FETCH_ASSOC));

				//return showQuery($search_sql, $bindedVal);
				return $data;
			} catch (Exception $e) {
			}
		}
	}


	public function generateQuery($gen_type,$range, $offset, $placeholder, $search_array, $authorid, $status, $cat, $searchquery,$orderby) {
		$addon_tbl = SITE_ADDON;
		$member_tbl = SITE_MEMBER_TBL;
		$likes_tbl = SITE_ADDON_LIKE;
		$download_stat = SITE_DOWNLOAD_STAT;

		if($gen_type == "result") {
			$search_sql = "SELECT
					{$addon_tbl}.ID_ADDON,
				  	ID_AUTHOR,
				  	membername,
				  	addon_title,
				  	addon_type,
				  	thumbnail,
				  	is_beta,
				  	status,
				  	tags,
				  	short_description,
				  	COUNT(distinct {$likes_tbl}.ID_LIKES)as likesCount,
				  	COUNT(distinct {$download_stat}.STAT_ID)as downloadCount
				  FROM {$addon_tbl}
				  	LEFT JOIN {$member_tbl}
				  		on {$addon_tbl}.ID_AUTHOR = {$member_tbl}.ID_MEMBER
				  	LEFT JOIN {$likes_tbl}
				  		on {$addon_tbl}.ID_ADDON = {$likes_tbl}.ID_ADDON
				  	LEFT JOIN {$download_stat}
				  		on {$addon_tbl}.ID_ADDON = {$download_stat}.ID
				  WHERE
				  	addon_type IN ({$cat})
				  	AND
				  	status IN ({$status})
				  	";
		} elseif ($gen_type == "count") {
			$search_sql = "SELECT COUNT(*)
				  FROM {$addon_tbl}
				  	LEFT JOIN {$member_tbl}
				  		on {$addon_tbl}.ID_AUTHOR = {$member_tbl}.ID_MEMBER
				  WHERE
				  	addon_type IN ({$cat})
				  	AND
				  	status IN ({$status})
				  	";
		}

		if ($authorid != null) {
			$search_sql .="AND
					ID_AUTHOR = {$authorid}
					";
		}

		if (!empty($searchquery)) {
			//If the search input contains only one word
			if (count ($search_array) == 1) {
				$search_sql .= "AND (
						 ";
				if ($authorid == null) {
					$search_sql .= "membername LIKE {$placeholder} OR ";
				}

				$search_sql .= "
						tags LIKE {$placeholder}
						OR
						REPLACE(addon_title,' ','') LIKE {$placeholder} ";
			} else {

				$search_sql .= "AND (
						 ";
				if ($authorid == null) {
					$search_sql .= "MATCH(membername) AGAINST ({$placeholder} IN BOOLEAN MODE) OR ";
				}

				$search_sql .= "MATCH(tags) AGAINST ({$placeholder} IN BOOLEAN MODE)
						 OR (REPLACE(addon_title,' ','') LIKE ?
						 OR MATCH(addon_title) AGAINST ({$placeholder} IN BOOLEAN MODE))
						 ";
			}

			$search_sql .= "OR MATCH(short_description) AGAINST ({$placeholder} IN BOOLEAN MODE)
					 )
					 GROUP BY {$addon_tbl}.ID_ADDON
					 ORDER BY (CASE
					 		WHEN REPLACE(addon_title,' ','') LIKE ? THEN 0
					 		WHEN addon_title LIKE ? THEN 1
					 		ELSE 2
					 	END), addon_title ASC";
		} else {
			$search_sql .= "GROUP BY {$addon_tbl}.ID_ADDON
					ORDER BY {$orderby}";
		}

		//store search SQL query without any limit for page count
		$total_count_sql = $search_sql;

		$search_sql .= " LIMIT {$offset} , {$range}";

		if ($gen_type == "count") {
			return $total_count_sql;
		} else {
			return $search_sql;
		}
	}
}