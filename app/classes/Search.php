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

namespace App\Lib;

class Search
{
    public static function createSearchTermFromDelimiter($string)
    {
        return explode(',', trim($string));
    }

    public static function createSearchTermFromSpaces($string)
    {
        return explode(' ', trim($string));
    }

    public static function createSqlPlaceholderFullText($val)
    {
        return join(' ', array_fill(0, count($val), '?'));
    }

    public static function createSqlPlaceholder($val)
    {
        return join(',', array_fill(0, count($val), '?'));
    }

    /**
     * @param string $searchquery
     * @param null $cat_input
     * @param int $status_input
     * @param null $authorid
     * @param int $offset
     * @param int $range
     * @param string $orderby
     * @param int $skip_count
     *
     * @return mixed
     */
    public function searchAddons(
        $searchquery,
        $cat_input = null,
        $status_input = 1,
        $authorid = null,
        $offset = 0,
        $range = 20,
        $orderby = "ID_ADDON DESC",
        $skip_count = null
    ) {
        global $connection, $mb;
        
     //validate the input data
        if (!(is_int($offset) && is_int($range))) {
            return false;
        }
        
     //Create arrays for SQL value binding
        if ($cat_input == null) {
            $cat_array = array_keys($mb['main_menu']['add-ons']['sub_menu']);
        } elseif (!is_array($cat_input)) {
            $cat_array = self::createSearchTermFromDelimiter($cat_input);
        } else {
            $cat_array = $cat_input;
        }
        
        if (!is_array($status_input)) {
            $status_array = self::createSearchTermFromDelimiter($status_input);
        } else {
            $status_array = $status_input;
        }

     //sanitize input
        $cat = self::createSqlPlaceholder($cat_array);
        $status = self::createSqlPlaceholder($status_array);

        if (!is_array($searchquery)) {
            $searchquery = trim($searchquery);

         //create an array from spaces between wrods
            $search_array = self::createSearchTermFromSpaces($searchquery);

         //replace spaces and create a compact string, and then create a single array
            $search_sort_term = array(str_replace(' ', '', $searchquery));

         //add % sign on both end and do not replace spaces this time. and then create an array from spaces in between
            $search_sort_term_unmod = preg_filter(
                ['/^/','/$/',],
                ['%$0','$0%',],
                array(self::createSearchTermFromSpaces($searchquery)[0])
            );

         //Create another array which * sign after all array keys
            $search_array_fulltext = preg_filter('/$/', '$0*', $search_array);

         //create another array which has % sign before and at the end of all array keys
            $search_array_like = preg_filter(['/^/','/$/',], ['%$0','$0%',], $search_array);

            $cat_status_array = array_merge($cat_array, $status_array);

         //If placeholder input has only 1 word in it then use mysql %LIKE% for searching
            if (count($search_array) == 1) {
             //create another array like "?,?,?,?...", this will prevent sql injection
                $placeholder = self::createSqlPlaceholder($search_array);

             //If search query is blank then get all
                if (empty($searchquery)) {
                 //Now Merge all the arrays together and
                    $bindedVal = $cat_status_array;
                } else {
                    if ($authorid == null) {
                        $bindedVal = array_merge(
                            $cat_status_array,
                            $search_array_like,
                            $search_array_like,
                            $search_array_like,
                            $search_array_fulltext,
                            $search_sort_term,
                            $search_sort_term_unmod
                        );
                    } else {
                        $bindedVal = array_merge(
                            $cat_status_array,
                            $search_array_like,
                            $search_array_like,
                            $search_array_fulltext,
                            $search_sort_term,
                            $search_sort_term_unmod
                        );

                    }
                }
            } else {
                //Unlike privously where we created array like "?,?,?,...",
                // this time for FULLTEXT placeholder we are omitting
                //commas and instead will use spaces
                $placeholder = self::createSqlPlaceholderFullText($search_array);

                if ($authorid == null) {
                 //Create another single array of all sanitized array, we will pass it into execute() method
                    $bindedVal = array_merge(
                        $cat_status_array,
                        $search_array_fulltext,
                        $search_array_fulltext,
                        $search_sort_term_unmod,
                        $search_array_fulltext,
                        $search_array_fulltext,
                        $search_sort_term,
                        $search_sort_term_unmod
                    );
                } else {
                    $bindedVal = array_merge(
                        $cat_status_array,
                        $search_array_fulltext,
                        $search_sort_term_unmod,
                        $search_array_fulltext,
                        $search_array_fulltext,
                        $search_sort_term,
                        $search_sort_term_unmod
                    );
                }
            }
        }

        $search_sql     = $this->generateQuery(
            "result",
            $range,
            $offset,
            $placeholder,
            $search_array,
            $authorid,
            $status,
            $cat,
            $searchquery,
            $orderby
        );
        $row_count_sql  = $this->generateQuery(
            "count",
            $range,
            $offset,
            $placeholder,
            $search_array,
            $authorid,
            $status,
            $cat,
            $searchquery,
            $orderby
        );

        if (databaseConnection()) {
            try {
             //Get the result data
                $search_statement = $connection->prepare($search_sql);
                $search_statement->execute($bindedVal);
                $data['result'] = $search_statement->fetchAll(PDO::FETCH_ASSOC);


                if ($skip_count == null) {
                 //Get the total row count for pagination
                    $count_statement = $connection->prepare($row_count_sql);
                    $count_statement->execute($bindedVal);
                    $data['row_count'] = count($count_statement->fetchAll(PDO::FETCH_ASSOC));
                }

             //return showQuery($search_sql, $bindedVal);
                return $data;
            } catch (Exception $e) {
                var_dump($e);
            }
        }
        return null;
    }


    public function generateQuery(
        $gen_type,
        $range,
        $offset,
        $placeholder,
        $search_array,
        $authorid,
        $status,
        $cat,
        $searchquery,
        $orderby
    ) {
        global $db_info;
        $search_sql='';

        if ($gen_type == "result") {
            $search_sql = "SELECT
					{$db_info['addon_tbl']}.ID_ADDON,
				  	ID_AUTHOR,
				  	membername,
				  	addon_title,
				  	category,
				  	thumbnail,
				  	is_beta,
				  	status,
				  	tags,
				  	short_description,
				  	COUNT(distinct {$db_info['likes_tbl']}.ID_LIKES)as likesCount,
				  	COUNT(distinct {$db_info['download_stat_tbl']}.STAT_ID)as downloadCount
				  FROM {$db_info['addon_tbl']}
				  	LEFT JOIN {$db_info['member_tbl']}
				  		on {$db_info['addon_tbl']}.ID_AUTHOR = {$db_info['member_tbl']}.ID_MEMBER
				  	LEFT JOIN {$db_info['likes_tbl']}
				  		on {$db_info['addon_tbl']}.ID_ADDON = {$db_info['likes_tbl']}.ID_ADDON
				  	LEFT JOIN {$db_info['download_stat_tbl']}
				  		on {$db_info['addon_tbl']}.ID_ADDON = {$db_info['download_stat_tbl']}.ID
				  WHERE
				  	category IN ({$cat})
				  	AND
				  	status IN ({$status})
				  	";
        } elseif ($gen_type == "count") {
            $search_sql = "SELECT COUNT(*)
				  FROM {$db_info['addon_tbl']}
				  	LEFT JOIN {$db_info['member_tbl']}
				  		on {$db_info['addon_tbl']}.ID_AUTHOR = {$db_info['member_tbl']}.ID_MEMBER
				  WHERE
				  	category IN ({$cat})
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
            if (count($search_array) == 1) {
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
					 GROUP BY {$db_info['addon_tbl']}.ID_ADDON
					 ORDER BY (CASE
					 		WHEN REPLACE(addon_title,' ','') LIKE ? THEN 0
					 		WHEN addon_title LIKE ? THEN 1
					 		ELSE 2
					 	END), addon_title ASC";
        } else {
            $search_sql .= "GROUP BY {$db_info['addon_tbl']}.ID_ADDON ";
            /**
             * If the order by contains the term "date" then we want to convert the date string to Date format
             * otherwise pass it as it is
             */
            if (strpos($orderby, "date")) {
                $orderby_array = explode(" ", $orderby);
                $orderby_first = $orderby_array[0];
                $orderby_second = $orderby_array[1];
    
                if ($orderby_first == "publish_date") {
                    $search_sql .= "ORDER BY STR_TO_DATE({$orderby_first}, '%M %d,%Y') {$orderby_second}";
                } elseif ($orderby_first == "update_date") {
                    $search_sql .= "ORDER BY STR_TO_DATE({$orderby_first}, '%M %d,%Y, %h:%i %p') {$orderby_second}";
                }
            } else {
                $search_sql .= "ORDER BY {$orderby}";
            }
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
