<?php

//define('LOCALE_DIR', $_SERVER["DOCUMENT_ROOT"].'/app/locale');
//define('DEFAULT_LOCALE', 'en_US');

require_once('./libraries/gettext/gettext.inc.php');

$supported_locales = [
  "en" => [
          "en_US",
  ],

];
$encoding = 'UTF-8';

$locale = (isset($_GET['lang']))? $_GET['lang'] : 'en_US';

// gettext setup
T_setlocale(LC_MESSAGES, $locale);
// Set the text domain as 'messages'

T_bindtextdomain($locale, $_SERVER['DOCUMENT_ROOT'].'/app/locale');
T_bind_textdomain_codeset($locale, $encoding);
T_textdomain($locale);


?>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
<h1><?php echo __("Hello world!"); ?></h1>
</body>
</html>
