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

require_once $_SERVER['DOCUMENT_ROOT'].'/functions.php';
if (count($params) > 1):?>
<style>
#<?php echo htmlspecialchars($params[1]);?>_active_page {
    background: rgba(255, 255, 255, 0.1) !important;
    color: #FFA500 !important;
    text-shadow: 1px 1px 1px rgba(80, 80, 80, 0.3) !important;
}
</style>
<?php endif; ?>
<?php 


?>
<!-- Navigation Menu starts -->
<div id="header">
	<div id="tabs">
		<ul>  
		<?php if (!$context['user']['is_guest']): ?>
			<li>
				<a href="<?php echo $scripturl; ?>?action=pm" 
				class="secondery_nav_menu_button"  
				title="Messages: <?php echo $context['user']['unread_messages'], ' ', $context['user']['unread_messages'] == 1 ? $txt['newmessages0'] : $txt['newmessages1'],', total ',$context['user']['messages']; ?>">
					<?php if ($context['user']['unread_messages'] > 0): ?>
					<span class="message_new"><?php echo $context['user']['unread_messages']; ?></span>
					<?php else: ?>
					<span class="message_no"><i class="fa fa-bell-o"></i></span>
					<?php endif; ?>
				</a>
			</li>	
		<?php else: ?>
			<li>
				<a href="<?php echo $link['login']; ?>"><i class="fa fa-user"></i>&nbsp; <?php echo $lang['17']; ?></a>
			</li>
		<?php endif; ?>
		<?php 
		//var_dump($main_menu);
			foreach ($main_menu as $key => $menu_item) {
				if ($context['user']['is_guest'] && isset($menu_item['restriction'])) {

				} else {
					echo "<li><a href=\"".$menu_item['href']." \">".$menu_item['title']."</a>";
					if (count($menu_item['sub_menu']) > 0) {
						echo "<ul class=\"nav_dropdown_sub\">";
							foreach ($menu_item['sub_menu'] as $sub_item_key => $sub_item) {
								if (isset($sub_item['restriction'])) {
									if ($sub_item['restriction'] == 'admin' && $context['user']['is_admin']) {
										echo "<li>";
										if (!empty($sub_item['href'])) echo "<a href=\"".$sub_item['href']." \">";
										if (!empty($sub_item['icon']) && empty($no_menu_icon)) echo $sub_item['icon'];
										echo $sub_item['title'];
										if (!empty($sub_item['href'])) echo "</a>";
										echo "</li>";
									}
								} else {
									echo "<li>";
									if (!empty($sub_item['href'])) echo "<a href=\"".$sub_item['href']." \">";
									if (!empty($sub_item['icon']) && empty($no_menu_icon)) echo $sub_item['icon'];
									echo $sub_item['title'];
									if (!empty($sub_item['href'])) echo "</a>";
									echo "</li>";
								}
							}
						echo "</ul>";
					}

				}


				echo"</li>";
			}
		?>
			<div id="clear"></div>
		</ul>
		<div id="clear"></div>
	</div>
</div>
<noscript>
	<div class="noscript_wrap"><p class="show_info info_red">Your browser does not support javascript(or disabled), please use a browser with javascript or enable it.<br/>We need javascript to function properly, otherwise some things won't work properly.</p></div>
</noscript>
<!-- Navigation menu ends-->
