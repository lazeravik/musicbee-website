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

	require_once $_SERVER['DOCUMENT_ROOT'].'/includes/html-purifier/HTMLPurifier.auto.php'; //load html purifier
	class Format
	{
		public static function htmlSafeOutput($html)
		{
			$config = HTMLPurifier_Config::createDefault();
			$config->set('HTML.Allowed', 'code[class|lang-rel],p,pre,table,thead,tbody,td,tr,th,h2,h1,h3,h4,h5,span,ul,li,ol,strong,blockquote,em,a[href|title],img[src],s,del,hr');
			$def = $config->getHTMLDefinition(true);
			$def->addAttribute('code', 'lang-rel', 'Text');
			$purifier = new HTMLPurifier($config);
			return $purifier->purify($html); //purify html from any xss attack
		}

		/**
		 * generate slug url
		 *
		 * @param string $string
		 * @return string
		 */
		public static function Slug($string)
		{
			return strtolower(trim(preg_replace('~[^0-9a-z]+~i','-',
					                               html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i','$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
		}


		/**
		 * unslug text... sort of
		 *
		 * @param string $string
		 * @return string
		 */
		public static function UnslugTxt($string)
		{
			return ucfirst(str_replace("-", " ", $string));
		}


		/**
		 * adds k,m,b after making a long number short for better presentation
		 *
		 * @param int $input
		 * @return string
		 */
		public static function number_format_suffix($input) {
			$suffixes = array('', 'k', 'm', 'g', 't');
			$suffixIndex = 0;

			while(abs($input) >= 1000 && $suffixIndex < sizeof($suffixes))
			{
				$suffixIndex++;
				$input /= 1000;
			}

			return (
			$input > 0
				// precision of 3 decimal places
				? floor($input * 10) / 10
				: ceil($input * 10) / 10
			)
			. $suffixes[$suffixIndex];
		}
		
		/**
		 * @param array $val
		 *
		 * @return string
		 */
		public static function safeSqlArray($val) {
			return join(',', array_fill(0, count($val), '?'));
		}

		public static function safeSqlSearchArray($val) {
			return join(' ', array_fill(0, count($val), '?'));
		}

		public static function createSqlArrayParam($string) {
			return explode(',',$string);
		}

		/* Converts any HTML-entities into characters */
		public static function my_numeric2character($t)
		{
			$convmap = array(0x0, 0x2FFFF, 0, 0xFFFF);
			return mb_decode_numericentity($t, $convmap, 'UTF-8');
		}
		/* Converts any characters into HTML-entities */
		public static function my_character2numeric($t)
		{
			$convmap = array(0x0, 0x2FFFF, 0, 0xFFFF);
			return mb_encode_numericentity($t, $convmap, 'UTF-8');
		}

	}
