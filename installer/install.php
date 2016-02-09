<?php 
# MySQL Workbench Synchronization
# Generated: 2015-12-02 10:34
# Model: New Model
# Version: 1.0
# Project: Name of the project
# Author: Avik

//creates the database if not exists, But in this case we already have the database predefined
//CREATE SCHEMA IF NOT EXISTS `".SITE_DB_NAME."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ; 
//Website related Setting
// define('DB_HOST', 'localhost');
// define('SITE_DB_NAME', 'getmu0_". SITE_DB_NAME . "'); //Website Database Name
// define('SITE_DB_USER', 'root'); //username
// define('SITE_DB_PASS', ''); //password



define('DB_HOST', 'localhost');
define('SITE_DB_NAME', 'getmu0_MusicBee'); //Website Database Name
define('SITE_DB_USER', 'getmu0_AvikB'); //username
define('SITE_DB_PASS', '[mbAvikB01]'); //password


//create the connection
try {
  $dbcon = new PDO('mysql:host='. DB_HOST .';dbname='. SITE_DB_NAME . ';charset=utf8', SITE_DB_USER, SITE_DB_PASS);
} catch (Exception $e) {
  var_dump($e);
}


  
try {
  $sql = "

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE TABLE IF NOT EXISTS `". SITE_DB_NAME ."`.`member` (
  `ID_MEMBER` INT(11) NOT NULL COMMENT 'this will be the same as forum ID MEMEBER',
  `membername` VARCHAR(80) NOT NULL,
  `addon_added` VARCHAR(45) NOT NULL DEFAULT 0,
  `rank` VARCHAR(45) NOT NULL DEFAULT 10,
  `total_likeReceived` VARCHAR(45) NULL DEFAULT 0,
  `total_addon_submitted` VARCHAR(45) NULL DEFAULT 0,
  `total_addon_issue` VARCHAR(45) NULL DEFAULT 0,
  `total_addon_neededApproval` VARCHAR(45) NULL DEFAULT 0,
  `total_addon_rejected` VARCHAR(45) NULL DEFAULT 0,
  `submitPermission` TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'can user submit an addon without admin/mod permission?',
  PRIMARY KEY (`ID_MEMBER`),
  UNIQUE INDEX `forum_uid_UNIQUE` (`ID_MEMBER` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `". SITE_DB_NAME ."`.`addons` (
  `ID_ADDON` INT(11) NOT NULL AUTO_INCREMENT,
  `ID_AUTHOR` INT(11) NOT NULL,
  `COLOR_ID` INT(11) NULL DEFAULT 1,
  `tags` VARCHAR(45) NOT NULL COMMENT 'eg. metro, modern, dark',
  `supported_mbversion` VARCHAR(80) NOT NULL COMMENT 'eg. MusicBee2,MusicBee3',
  `addon_title` VARCHAR(80) NOT NULL COMMENT 'eg. Windows 10 Style Skin',
  `addon_type` INT(11) NOT NULL COMMENT 'eg. skins(1), plugins(2), theater_mode(3),\nvisualiser(4)',
  `addon_version` VARCHAR(45) NOT NULL DEFAULT '1.0',
  `publish_date` VARCHAR(45) NOT NULL,
  `update_date` VARCHAR(45) NULL DEFAULT NULL,
  `short_description` MEDIUMTEXT NOT NULL,
  `download_links` VARCHAR(200) NOT NULL COMMENT 'instead of relational database approach, using serialize arrays are better since every addons will have unique number of unique download links.',
  `image_links` VARCHAR(600) NOT NULL,
  `thumbnail` VARCHAR(80) NOT NULL,
  `cover_image` VARCHAR(45) NULL DEFAULT NULL,
  `important_note` VARCHAR(80) NULL DEFAULT NULL,
  `support_forum` VARCHAR(80) NULL DEFAULT NULL,
  `readme_content` LONGTEXT NULL DEFAULT NULL,
  `readme_content_html` LONGTEXT NULL DEFAULT NULL,
  `is_beta` TINYINT(1) NOT NULL DEFAULT 0,
  `status` INT(11) NOT NULL DEFAULT 0 COMMENT '0=need approval\n1=approved\n2=rejected\n3=deleted',
  `lastStatus_moderatedBy` INT(11) NULL DEFAULT NULL COMMENT 'last modertator to moderate the status',
  PRIMARY KEY (`ID_ADDON`),
  UNIQUE INDEX `ID_ADDON_UNIQUE` (`ID_ADDON` ASC),
  INDEX `COLOR_ID_idx` (`COLOR_ID` ASC),
  INDEX `author_fk_id_idx` (`ID_AUTHOR` ASC),
  CONSTRAINT `COLOR_ID`
    FOREIGN KEY (`COLOR_ID`)
    REFERENCES `". SITE_DB_NAME ."`.`theme_color` (`ID_COLOR`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `author_fk_id`
    FOREIGN KEY (`ID_AUTHOR`)
    REFERENCES `". SITE_DB_NAME ."`.`member` (`ID_MEMBER`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `". SITE_DB_NAME ."`.`current_version` (
  `ID_VERSION` INT(11) NOT NULL,
  `appname` VARCHAR(45) NOT NULL,
  `version` VARCHAR(45) NOT NULL,
  `beta` TINYINT(1) NOT NULL DEFAULT 0,
  `release_date` VARCHAR(45) NOT NULL,
  `message` MEDIUMTEXT NULL DEFAULT NULL,
  `supported_os` VARCHAR(45) NOT NULL,
  `DownloadLink` VARCHAR(80) NOT NULL,
  `MirrorLink1` VARCHAR(80) NULL DEFAULT NULL,
  `MirrorLink2` VARCHAR(80) NULL DEFAULT NULL,
  `PortableLink` VARCHAR(80) NOT NULL,
  `available` TINYINT(1) NULL DEFAULT 1,
  PRIMARY KEY (`ID_VERSION`),
  UNIQUE INDEX `ID_VERSION_UNIQUE` (`ID_VERSION` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `". SITE_DB_NAME ."`.`theme_color` (
  `ID_COLOR` INT(11) NOT NULL,
  `colorname` VARCHAR(45) NOT NULL,
  `colorvalue` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`ID_COLOR`),
  UNIQUE INDEX `ID_COLOR_UNIQUE` (`ID_COLOR` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `". SITE_DB_NAME ."`.`likes` (
  `ID_LIKES` INT(11) NOT NULL AUTO_INCREMENT,
  `ID_MEMBER` INT(11) NOT NULL,
  `ID_ADDON` INT(11) NOT NULL,
  PRIMARY KEY (`ID_LIKES`),
  INDEX `memberid_idx` (`ID_MEMBER` ASC),
  INDEX `addonid_idx` (`ID_ADDON` ASC),
  CONSTRAINT `memberid`
    FOREIGN KEY (`ID_MEMBER`)
    REFERENCES `". SITE_DB_NAME ."`.`member` (`ID_MEMBER`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `addonid`
    FOREIGN KEY (`ID_ADDON`)
    REFERENCES `". SITE_DB_NAME ."`.`addons` (`ID_ADDON`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE TABLE IF NOT EXISTS `". SITE_DB_NAME ."`.`allversions` (
  `ID_ALLVERSIONS` INT(11) NOT NULL AUTO_INCREMENT,
  `appname` VARCHAR(45) NOT NULL,
  `version` VARCHAR(45) NOT NULL,
  `release_date` VARCHAR(45) NOT NULL,
  `supported_os` VARCHAR(45) NOT NULL,
  `release_note` MEDIUMTEXT NULL DEFAULT NULL,
  `release_note_html` MEDIUMTEXT NULL DEFAULT NULL,
  `major` TINYINT(1) NOT NULL DEFAULT 0,
  `dashboard_availablity` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID_ALLVERSIONS`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

";
    $statement = $dbcon->prepare($sql);
    $statement->execute();
  } catch (Exception $e) {
    echo $e;
  }






 ?>