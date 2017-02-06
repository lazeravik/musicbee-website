<?php
/**
 * @author: AvikB
 * @version: 1.0
 * @description: 
 */

 $gotoUrl = "http://".$_SERVER['HTTP_HOST'].'/app/';
 header("Location: ".$gotoUrl, true, 303);