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

    require_once $_SERVER['DOCUMENT_ROOT'].'/app/includes/html-purifier/HTMLPurifier.auto.php'; //load html purifier
class Format
{
    public static function htmlSafeOutput($html, $htmlpurifierConfig)
    {
        $config = $htmlpurifierConfig;
        $config->set('HTML.AllowedElements', array(
                         'code', 'p',  'a',  'img',  'div',  'pre',
            'table',  'thead',  'tbody',  'td',  'tr',
            'th',  'h2',  'h1',  'h3',  'h4',  'h5',  'span',
            'ul',  'li',  'ol',  'strong',  'blockquote',  'em',
            's',  'del',  'hr',  'br',  'iframe', 'i'));
        $config->set('HTML.AllowedAttributes', array('a.href','a.target' ,'code.lang-rel', 'img.src',
            '*.class', '*.alt', '*.title', '*.border', 'a.target', 'a.rel',
            'iframe.src', 'iframe.width', 'iframe.height', 'iframe.frameborder'));
        $config->set('HTML.SafeIframe', true);
        
        //allow YouTube and Vimeo
        $config->set(
            'URI.SafeIframeRegexp',
            '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%'
        );
        
        $def = $config->getHTMLDefinition(true);
        $def->addAttribute('code', 'lang-rel', 'Text');
        $purifier = new HTMLPurifier($config);
        return $purifier->purify($html); //purify html from any xss attack
    }

    /**
         * Since PHP does not have any clmap function we can use this custom one :)
         */
    public static function clamp($CurrentVal, $Max, $Min)
    {
        return max($Min, min($Max, $CurrentVal));
    }


    /**
         * unslug text... sort of
         *
         * @param string $string
         * @return string
         */
    public static function unslugTxt($string)
    {
        return ucfirst(str_replace("-", " ", $string));
    }


    /**
         * adds k,m,b after making a long number short for better presentation
         *
         * @param int $input
         * @return string
         */
    public static function numberFormatSuffix($input)
    {
        $suffixes = array('', 'k', 'm', 'g', 't');
        $suffixIndex = 0;

        while (abs($input) >= 1000 && $suffixIndex < sizeof($suffixes)) {
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


    /* Converts any HTML-entities into characters */
    public static function numeric2character($t)
    {
        $convmap = array(0x0, 0x2FFFF, 0, 0xFFFF);
        return mb_decode_numericentity($t, $convmap, 'UTF-8');
    }
        /* Converts any characters into HTML-entities */
    public static function character2numeric($t)
    {
        $convmap = array(0x0, 0x2FFFF, 0, 0xFFFF);
        return mb_encode_numericentity($t, $convmap, 'UTF-8');
    }


    /**
         * Create a web friendly URL slug from a string.
         *
         * Although supported, transliteration is discouraged because
         *     1) most web browsers support UTF-8 characters in URLs
         *     2) transliteration causes a loss of information
         *
         * @param string $str
         * @param array $options
         * @return string
         */
    public static function slug($str, $options = array())
    {
     // Make sure string is in UTF-8 and strip invalid UTF-8 characters
        $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());

        $defaults = array(
        'delimiter' => '-',
        'limit' => null,
        'lowercase' => true,
        'replacements' => array(),
        'transliterate' => false,
        );

     // Merge options
        $options = array_merge($defaults, $options);

        $char_map = array(
      // Latin
        'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
        'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
        'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
        'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
        'ß' => 'ss',
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
        'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
        'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
        'ÿ' => 'y',
      // Latin symbols
        '©' => '(c)',
      // Greek
        'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
        'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
        'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
        'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
        'Ϋ' => 'Y',
        'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
        'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
        'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
        'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
        'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
      // Turkish
        'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
        'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
      // Russian
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
        'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
        'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
        'Я' => 'Ya',
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
        'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
        'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
        'я' => 'ya',
      // Ukrainian
        'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
        'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
      // Czech
        'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
        'Ž' => 'Z',
        'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
        'ž' => 'z',
      // Polish
        'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
        'Ż' => 'Z',
        'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
        'ż' => 'z',
      // Latvian
        'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
        'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
        'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
        'š' => 's', 'ū' => 'u', 'ž' => 'z'
        );

     // Make custom replacements
        $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);

     // Transliterate characters to ASCII
        if ($options['transliterate']) {
            $str = str_replace(array_keys($char_map), $char_map, $str);
        }

     // Replace non-alphanumeric characters with our delimiter
        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);

     // Remove duplicate delimiters
        $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);

     // Truncate slug to max. characters
        $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');

     // Remove delimiter from ends
        $str = trim($str, $options['delimiter']);

        return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
    }

    /**
         * Input sanitizer for specialcharacter and html tag escape
         * @param $value
         *
         * @return null|string
         */
    public static function safeInput($value)
    {
        if (!empty($value)) {
            $value = strip_tags($value);
            $value = htmlspecialchars($value);
            $value = str_replace("\\", "", $value);
            $value = str_replace(array(
            "\r\n",
            "\r",
            "\n",
            ), "", $value); //remove new line breaks fom the string
            return stripslashes($value);
        }

        return null;
    }

    public static function removeSpace($value)
    {
        if (!empty($value)) {
            return str_replace(" ", "", $value);
        }

        return null;
    }

    public static function parsePage($pageLocation)
    {
        if (!empty($pageLocation)) {
         //open the Page file
            $fileData   = file($pageLocation);

            $data       = array();
            $data['markdown'] = '';

            for ($i = 0, $n = count($fileData); $i < $n; $i++) {
             //if the line starts and ends with [], then it is page meta info
                if (substr(trim($fileData[$i]), 0, 1).substr(trim($fileData[$i]), -1) == '[]') {
                 //remove [ and ] from the string
                    $fileData[$i] = substr(trim($fileData[$i]), 1, -1);

                    if (strchr($fileData[$i], 'title =') || strchr($fileData[$i], 'title=')) {
                        $data['title'] = trim(str_replace(array('title =','title='), '', $fileData[$i]));
                    } elseif (strchr($fileData[$i], 'description =') || strchr($fileData[$i], 'description=')) {
                        $data['description'] = trim(
                            str_replace(array('description =','description='), '', $fileData[$i])
                        );
                    }

                } else {
                    $data['markdown']   .= $fileData[$i];
                }
            }

            return $data;
        }
        return null;
    }

    public static function imgurResizer($url, $size)
    {
        if (preg_match('/^https?\:\/\/i\.imgur\.com\//', $url)) {
            $ext_pos = strrpos($url, '.'); // find position of the last dot, so where the extension starts
            $thumb = substr($url, 0, $ext_pos).$size.substr($url, $ext_pos);
        } else {
            $thumb = $url;
        }

        return $thumb;
    }
}
