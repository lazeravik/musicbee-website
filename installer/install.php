<?php
/**
 * Copyright (c) 2016 AvikB, some rights reserved.
 *  Copyright under Creative Commons Attribution-ShareAlike 3.0 Unported,
 *  for details visit: https://creativecommons.org/licenses/by-sa/3.0/
 *
 * @Contributors:
 * Created by AvikB for noncommercial MusicBee project.
 *  Spelling mistakes and fixes from community members.
 */

$link['url'] = 'http://'.$_SERVER['HTTP_HOST'].'/';

if (isset($_GET['step']) && isset($_POST['submitted'])) {
    if ($_GET['step']==1) {
        if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['database']) && isset($_POST['host']) && isset($_POST['action'])) {
            if (empty($_POST['username']) || empty($_POST['database']) || empty($_POST['host']) || empty($_POST['action'])) {
                die(generateJson("please make sure you fill all the boxes", 0, ""));
            } else {
                if ($_POST['action']=="dbcheck") {
                    if (checkConnection()) {
                        if (saveDatabaseDetails()) {
                            exit(generateJson("✅ Conncetion to the Database is established.<br>Please wait while we set up the database...", 1, "install_tables", true));
                        } else {
                            die(generateJson("✖ Setting can't be saved! Make sure you have write permission", 0, ""));
                        }
                    } else {
                        die(generateJson("✖ please make sure username, password, database is valid!", 0, ""));
                    }
                } elseif ($_POST['action']=="install_tbl") {
                    //ok, everything seems to be fine, it is time to install the tables
                    if (installTables()) {
                        exit(generateJson("✅ Database Tables successfully created.<br>Please wait while we add some initial data...", 1, "install_data", true));
                    } else {
                        die(generateJson("❌ Oops, something went wrong.", 0, ""));
                    }
                } elseif ($_POST['action']=="install_data") {
                    if (installData()) {
                        exit(generateJson("✅ Initial data successfully added", 1, "install_finished", true));
                    } else {
                        die(generateJson("❌ Can not enter any data!", 0, ""));
                    }
                }
            }
        } else {
            die(generateJson("❌ please make sure you fill all the boxes", 0, ""));
        }

        return null;
    } else {
        return null;
    }
}



/**
 * Save database authentication details to setting.php file
 *
 * @return bool
 */
function saveDatabaseDetails()
{
    $settingArray = file(dirname(__FILE__) . '/app/setting.php');

    $settingArray[9] = "define('DB_HOST', '{$_POST['host']}');\n";
    $settingArray[10] = "define('SITE_DB_NAME', '{$_POST['database']}');\n";
    $settingArray[11] = "define('SITE_DB_USER', '{$_POST['username']}');\n";
    $settingArray[12] = "define('SITE_DB_PASS', '{$_POST['password']}');\n";
    $settingArray[13] = "define('SITE_DB_PREFIX', '{$_POST['prefix']}');\n\n";

    $settingString = '';
    for ($i = 0, $n = count($settingArray); $i < $n; $i++) {
        $settingString .= rtrim($settingArray[$i]) . "\n";
    }

    try {
        //open the setting file stream
        $fp = fopen(dirname(__FILE__) . '/app/setting.php', 'r+');

        //write the content to the file
        fwrite($fp, $settingString);

        //close the file stream
        fclose($fp);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

$connection = null;
function checkConnection()
{
    global $connection;

    //if connection already exists
    if ($connection != null) {
        return true;
    } else {
        try {
            $connection = new PDO('mysql:host='. $_POST['host'] .';dbname='. $_POST['database'] . ';charset=utf8', $_POST['username'], $_POST['password']);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}

/**
 * @param string                             $message message to display, html is allowed
 * @param int                                $status  status code, 0=error, 1=success
 * @param $callback         callback function
 * @param bool                               $append  if the message appeend or clear
 * @return string
 */
function generateJson($message = '', $status = 1, $callback, $append = false)
{
    $generatedJson = '{"status":"'.$status.'", "message":"'.$message.'", "callback": "'.$callback.'", "append": "'.$append.'" }';
    return $generatedJson;
}

function installTables()
{
    global $connection;

    $db = $_POST['database'];
    $prefix = $_POST['prefix'];
    $sql = <<<SQL
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

USE {$db} ;

CREATE TABLE IF NOT EXISTS {$prefix}member (
  `ID_MEMBER` INT NOT NULL COMMENT 'this will be the same as forum ID MEMEBER',
  `membername` VARCHAR(80) NOT NULL,
  `rank` VARCHAR(45) NOT NULL DEFAULT 10,
  PRIMARY KEY (`ID_MEMBER`),
  UNIQUE INDEX `forum_uid_UNIQUE` (`ID_MEMBER` ASC),
  FULLTEXT INDEX `member_fulltext` (`membername` ASC))
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS {$prefix}addons (
  `ID_ADDON` INT NOT NULL AUTO_INCREMENT,
  `ID_AUTHOR` INT NOT NULL,
  `category` INT NOT NULL,
  `supported_mbversion` VARCHAR(80) NOT NULL COMMENT 'eg. MusicBee2,MusicBee3',
  `addon_title` VARCHAR(80) NOT NULL COMMENT 'eg. Windows 10 Style Skin',
  `addon_version` VARCHAR(45) NULL DEFAULT '1.0',
  `publish_date` VARCHAR(45) NOT NULL,
  `update_date` VARCHAR(45) NULL,
  `tags` VARCHAR(100) NULL COMMENT 'eg. metro, modern, dark',
  `short_description` MEDIUMTEXT NOT NULL,
  `download_links` VARCHAR(2083) NOT NULL COMMENT 'instead of relational database approach, using serialize arrays are better since every addons will have unique number of unique download links.',
  `image_links` VARCHAR(5083) NOT NULL,
  `thumbnail` VARCHAR(2083) NOT NULL,
  `important_note` MEDIUMTEXT NULL,
  `support_forum` VARCHAR(80) NULL,
  `readme_content` LONGTEXT NULL,
  `readme_content_html` LONGTEXT NULL,
  `is_beta` TINYINT(1) NOT NULL DEFAULT 0,
  `status` INT NOT NULL DEFAULT 0 COMMENT '0=need approval\n1=approved\n2=rejected\n3=deleted',
  `lastStatus_moderatedBy` INT NULL COMMENT 'last modertator to moderate the status',
  PRIMARY KEY (`ID_ADDON`),
  UNIQUE INDEX `ID_ADDON_UNIQUE` (`ID_ADDON` ASC),
  INDEX `author_fk_id_idx` (`ID_AUTHOR` ASC),
  FULLTEXT INDEX `addons_fulltext_title` (`addon_title` ASC),
  FULLTEXT INDEX `addons_fulltext_tags` (`tags` ASC),
  FULLTEXT INDEX `addons_fulltext_shortdesc` (`short_description` ASC),
  CONSTRAINT `{$prefix}author_fk_id`
    FOREIGN KEY (`ID_AUTHOR`)
    REFERENCES {$prefix}member (`ID_MEMBER`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS {$prefix}current_version (
  `ID_VERSION` INT NOT NULL,
  `appname` VARCHAR(45) NOT NULL,
  `version` VARCHAR(45) NOT NULL,
  `beta` TINYINT(1) NOT NULL DEFAULT 0,
  `release_date` VARCHAR(45) NOT NULL,
  `message` MEDIUMTEXT NULL,
  `supported_os` VARCHAR(45) NOT NULL,
  `DownloadLink` VARCHAR(2083) NOT NULL,
  `MirrorLink1` VARCHAR(2083) NULL,
  `MirrorLink2` VARCHAR(2083) NULL,
  `PortableLink` VARCHAR(2083) NOT NULL,
  `available` TINYINT(1) NULL DEFAULT 1,
  PRIMARY KEY (`ID_VERSION`),
  UNIQUE INDEX `ID_VERSION_UNIQUE` (`ID_VERSION` ASC))
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS {$prefix}likes (
  `ID_LIKES` INT NOT NULL AUTO_INCREMENT,
  `ID_MEMBER` INT NOT NULL,
  `ID_ADDON` INT NOT NULL,
  PRIMARY KEY (`ID_LIKES`),
  INDEX `memberid_idx` (`ID_MEMBER` ASC),
  INDEX `id_addon` (`ID_ADDON` ASC),
  CONSTRAINT `{$prefix}memberid`
    FOREIGN KEY (`ID_MEMBER`)
    REFERENCES {$prefix}member (`ID_MEMBER`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `{$prefix}fk_addons_likes1`
    FOREIGN KEY (`ID_ADDON`)
    REFERENCES {$prefix}addons (`ID_ADDON`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS {$prefix}allversions (
  `ID_ALLVERSIONS` INT NOT NULL AUTO_INCREMENT,
  `appname` VARCHAR(45) NOT NULL,
  `version` VARCHAR(45) NOT NULL,
  `release_date` VARCHAR(45) NOT NULL,
  `supported_os` VARCHAR(45) NOT NULL,
  `release_note` MEDIUMTEXT NULL,
  `release_note_html` MEDIUMTEXT NULL,
  `major` TINYINT(1) NOT NULL DEFAULT 0,
  `dashboard_availablity` TINYINT(1) NOT NULL DEFAULT 0,
  `beta` TINYINT(1) NULL DEFAULT 0,
  PRIMARY KEY (`ID_ALLVERSIONS`))
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS {$prefix}download_stat (
  `STAT_ID` INT NOT NULL AUTO_INCREMENT,
  `ip_address` VARCHAR(45) NULL,
  `is_registered` TINYINT(1) NOT NULL DEFAULT 0,
  `stat_type` VARCHAR(45) NULL,
  `ID` INT NULL,
  PRIMARY KEY (`STAT_ID`),
  UNIQUE INDEX `STAT_ID_UNIQUE` (`STAT_ID` ASC),
  INDEX `fk_download_stat_addons14_idx` (`ID` ASC),
  CONSTRAINT `{$prefix}fk_download_stat_addons14`
    FOREIGN KEY (`ID`)
    REFERENCES {$prefix}addons (`ID_ADDON`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS {$prefix}settings (
  `variable` VARCHAR(255) NOT NULL,
  `value` TEXT NOT NULL,
  PRIMARY KEY (`variable`))
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS {$prefix}help (
  `variable` VARCHAR(255) NOT NULL,
  `data_type` VARCHAR(255) NOT NULL,
  `data` LONGTEXT NOT NULL)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
SQL;

    if (checkConnection()) {
        try {
            $statement = $connection->prepare($sql);
            $statement->execute();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    return false;
}


function installData()
{
    global $connection;

    $db = $_POST['database'];
    $prefix = $_POST['prefix'];
    $sql = <<<SQL

USE {$db};

INSERT INTO {$prefix}settings (`variable`, `value`) VALUES ('showPgaeLoadTime', '0');
INSERT INTO {$prefix}settings (`variable`, `value`) VALUES ('maxSubmitWithOutApproval', '10');
INSERT INTO {$prefix}settings (`variable`, `value`) VALUES ('imgurUploadOn', '1');
INSERT INTO {$prefix}settings (`variable`, `value`) VALUES ('imgurClientID', '7f0764588452050');
INSERT INTO {$prefix}settings (`variable`, `value`) VALUES ('imgurClientSecret', '0061179a2f436175dc84e83deabc23aa12ef255a');
INSERT INTO {$prefix}settings (`variable`, `value`) VALUES ('addonSubmissionOn', '1');
INSERT INTO {$prefix}settings (`variable`, `value`) VALUES ('paypalDonationLink', 'http://paypal.com');
INSERT INTO {$prefix}settings (`variable`, `value`) VALUES ('twitterLink', 'http://twitter.com/musicbeeplayer');
INSERT INTO {$prefix}settings (`variable`, `value`) VALUES ('wikiaLink', 'http://musicbee.wikia.com');
INSERT INTO {$prefix}settings (`variable`, `value`) VALUES ('wishlistLink', '');
INSERT INTO {$prefix}settings (`variable`, `value`) VALUES ('websiteBugLink', '');
INSERT INTO {$prefix}settings (`variable`, `value`) VALUES ('musicbeeBugLink', '');
INSERT INTO {$prefix}settings (`variable`, `value`) VALUES ('eliteRequirement', '8');
INSERT INTO {$prefix}settings (`variable`, `value`) VALUES ('selfApprovalRequirement', '3');
INSERT INTO {$prefix}settings (`variable`, `value`) VALUES ('maximumAddonSubmissionPerDay', '20');

INSERT INTO {$prefix}help (`variable`, `data_type`, `data`) VALUES ('help_links', 'json', '');
INSERT INTO {$prefix}help (`variable`, `data_type`, `data`) VALUES ('help_api_link', 'link', 'http://musicbee.wikia.com/index.php?action=render&title=FAQ');
INSERT INTO {$prefix}help (`variable`, `data_type`, `data`) VALUES ('api_md', 'markdown', '');
INSERT INTO {$prefix}help (`variable`, `data_type`, `data`) VALUES ('api_html', 'html', '');
INSERT INTO {$prefix}help (`variable`, `data_type`, `data`) VALUES ('press_md', 'markdown', '');
INSERT INTO {$prefix}help (`variable`, `data_type`, `data`) VALUES ('press_html', 'html', '');

SQL;

    if (checkConnection()) {
        try {
            $statement = $connection->prepare($sql);
            $statement->execute();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    return false;
}

?>

<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>MusicBee Website Installation Setup</title>
	<link rel="stylesheet" href="<?php echo $link['url']; ?>app/styles/dist/mb_main.css?1.0">

	<script src="<?php echo $link['url']; ?>app/scripts/jquery-2.1.4.min.js"></script>
</head>
<body>
<div class="top_infobar " id="top_jump">
	<div class="infobar_wrapper">
		<div class="infobar_inner_wrapper">
			<h2>Website Installation</h2>
		</div>
	</div>
</div>

<div class="main_content_wrapper col_2_1">
	<div class="sub_content_wrapper">

    <?php if (isset($_GET['step'])) : ?>
    <?php if ($_GET['step']==1) : ?>
		<div class="box_content">
			<span class="show_info custom">
				<h3>Step 1</h3>
				<p class="description">Provide database name and credentials</p>
			</span>
		</div>
		<form action="" method="post" id="install" data-autosubmit>
			<div class="box_content" id="api">
				<ul class="form">
					<li>
						<label for="host">
							<p>Server Name</p>
							<p class="description">This is nearly always localhost - so if you don't know, try localhost.</p>
						</label>
						<input type="text" id="host" name="host" value="localhost"/>
					</li>
					<li>
						<label for="username">
							<p>Username</p>
							<p class="description">Username for connecting to the database</p>
						</label>
						<input type="text" id="username" name="username"/>
					</li>
					<li>
						<label for="password">
							<p>Password</p>
							<p class="description">Put the password you need to connect to your database.</p>
						</label>
						<input type="password" id="password" name="password"/>
					</li>
					<li>
						<label for="database">
							<p>Database Name</p>
							<p class="description">Fill in the name of the database you want to use to store the data.<br/>Please create the database from PhpMyAdmin first. The installer won't create it.</p>
						</label>
						<input type="text" id="database" name="database" value="musicbee" />
					</li>
					<li>
						<label for="prefix">
							<p>Table Prefix</p>
							<p class="description">The prefix for every table in the database. Do not use the same prefix in multiple install!
								This value allows for multiple installations in one database.</p>
						</label>
						<input type="text" id="prefix" name="prefix" value="mb_" />
					</li>
				</ul>
			</div>

			<div class="box_content">
				<p class="show_info info_silver custom" id="result"></p>
				<ul class="form">
					<button id="submit" class="btn btn_blue" type="submit" onclick="next()">Continue</button>
					<input type="hidden" name="submitted" value="true">
					<input type="hidden" name="databaseChecked" id="databaseChecked" value="false">
					<input type="hidden" name="action" id="action" value="dbcheck">
				</ul>
			</div>
			<script>
				function next() {
					$('#result').html('');
					$("#submit").html("please wait!");
					$('form[data-autosubmit][id=install]').autosubmit();
				}

				install_tables = function(){
					$("#action").attr("value","install_tbl");
					$('#submit').click();
				};

				install_data = function(){
					$("#action").attr("value","install_data").delay(100);
					$('#submit').click();
				};

				install_finished = function(){
					window.location = "?step=2";
				};
			</script>
    <?php elseif ($_GET['step']==2) : ?>
				<div class="box_content">
					<span class="show_info custom">
						<h3>Congratualtion</h3>
						<p class="description">Yup, that's it. All Done.</p>
					</span>
				</div>
				<div class="box_content">
					<ul class="list markdownView">
						<h3>Everything is set! Now Add a MusicBee release from admin panel.</h3>
						<p>
							First thing first, before doing anything else go to <code>dashboard > admin > Downloads & Releases</code>, and add a new musicbee release.
						</p>
						<p>
							Add/update links from website settings, modify API page, press & media page as well.
						</p>
						<hr>
						<p class="show_info info_red">Now that installation is complete, please delete <code>install.php</code> file from the directory.</p>
						<a class="btn btn_blue" href="<?php echo $link['url']; ?>">Go to Home Page</a>
						<a class="btn btn_blue" href="<?php echo $link['url']; ?>dashboard/#mbrelease_submit/stable">Add a MusicBee release</a>
					</ul>

				</div>
    <?php 
endif; ?>

    <?php else: ?>

				<div class="box_content">
					<span class="show_info custom">
						<h3>Requirements</h3>
						<p class="description">Please make sure the following conditions are met beofre proceeding any further</p>
					</span>
				</div>
				<div class="box_content">
					<div class="markdownView box">
						<h3>Hi there! Welcome to MusicBee Website installation Guide.</h3>
						<p>
							Before you continue any further, make sure you have the following checked.
						</p>
						<hr>
						<p>✅ &nbsp;&nbsp;&nbsp;Forum is Installed and working properly</p>
						<p>✅ &nbsp;&nbsp;&nbsp;Create a new Database for the site (use PhpMyAdmin)</p>
						<p>✅ &nbsp;&nbsp;&nbsp;Get username and password for connecting to the database</p>
						<hr>
						<p>✅ &nbsp;&nbsp;&nbsp;Check PHP version. <b>Minimum PHP 5.4 is required</b></p>
					</div>
					<div class="space tiny"></div>
				</div>
				<div class="box_content">

        <?php if (phpversion() > 5.3) : ?>
						<ul class="form">
							<button class="btn btn_blue" type="submit" onclick="next()">Continue</button>
						</ul>
        <?php else: ?>
						<p class="show_info info_red custom">You will need atleast PHP 5.4 to continue.</p>
        <?php 
endif; ?>
				</div>
				<script>
					function next() {
						window.location = "?step=1";
					}
				</script>
    <?php 
endif; ?>


	</div>


</div>
<script>
	(function ($) {
		$.fn.autosubmit = function () {
			this.submit(function (event) {
				event.preventDefault();
				event.stopImmediatePropagation(); //This will stop the form submit twice
				var form = $(this);
				$.ajax({
					type: form.attr('method'),
					url: form.attr('action'),
					data: form.serialize()
				}).done(function (data) {
					generateResult(data);
				}).fail(function (jqXHR, textStatus, errorThrown) {
					$("#submit").html("Continue");
				}).always(function () {

				});
			});
		};
		return false;
	})(jQuery);

	var obj;
	function generateResult(data){
		obj = validateJSON(data);
		if (obj !== false) {
			if(obj.append === true) {
				$('#result').append(obj.message+'<br>');
			} else {
				$('#result').html(obj.message);
			}

			if(obj.callback.length > 0)
				callback(obj.callback);
		} else {
			$('#result').html("Something Went Wrong! The json response is not valid.<br/>"+data);
		}
	}

	function validateJSON(jsonString) {
		try {
			var json = jQuery.parseJSON(jsonString);

			// Handle non-exception-throwing cases:
			// Neither JSON.parse(false) or JSON.parse(1234) throw errors, hence the type-checking,
			// but... JSON.parse(null) returns 'null', and typeof null === "object",
			// so we must check for that, too.
			if (json && typeof json === "object" && json !== null) {
				return json;
			}
		}
		catch (e) {}

		return false;
	}

	//create and call the function by it's name
	function callback(function_name) {
		/*jslint evil: true */
		new Function("return " + function_name + "()")();
	}


</script>
</body>
</html>
