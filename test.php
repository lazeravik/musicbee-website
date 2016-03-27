<?php
include 'functions.php';
include 'classes/Search.php';


if(isset($_GET['search'])){
	$search = new Search();
	$search_result = $search->searchAddons($_GET['search'], array_keys($main_menu['add-ons']['sub_menu']), $_GET['status']);
} else {
	$search_result = "search something";
}


$search_array = preg_filter('/$/', '$0*', Format::safeSqlSearchArray($_GET['search']));

var_dump($search_array);
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
	<form method="get" action="">
		<input type="search" name="search">
		<input type="hidden" name="status" value="1">
	</form>
<br/>
<?php var_dump($search_result); ?>

</body>
</html>

