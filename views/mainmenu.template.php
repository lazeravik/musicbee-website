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

require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php'; ?>
<?php

function printMenuItems($menuArray){
	echo "<li>";
	if (!empty($menuArray['href'])) {
		if(array_key_exists('target',$menuArray)) {
			$target = $menuArray['target'];
		} else {
			$target = '';
		}
		echo '<a href="' . $menuArray['href'] . '" target="'.$target.'">';
	}

	if (!empty($menuArray['icon']) && empty($no_menu_icon))
		echo $menuArray['icon'].'&nbsp;&nbsp;';

	echo $menuArray['title'];

	if (!empty($menuArray['href']))
		echo "</a>";

	echo "</li>";
}

?>
<!-- Navigation Menu starts -->
<nav class="mainmenu" id="main_menu">
	<div class="menu_wrapper">
		<ul class="menu_position menu_left">
			<li class="expand">
				<a href="javascript:void(0)" ><i class="fa fa-bars"></i></a>
			</li>
			<li class="logo">
				<a href="<?php echo $link['url']; ?>" ><img src="<?php echo $link['url']; ?>img/musicbee.png">MusicBee</a>
			</li>
			<?php
			foreach ($mb['main_menu'] as $key => $menu_item)
			{
				if (!isset($menu_item['restriction']))
				{
					echo '<li><a href="' . $menu_item['href'] . '">'. $menu_item['title'] . '</a>';
					if (count($menu_item['sub_menu']) > 0)
					{
						echo '<ul class="nav_dropdown_sub primary_submenu">';
						foreach ($menu_item['sub_menu'] as $sub_item_key => $sub_item)
						{
							if (isset($sub_item['restriction']))
							{
								if ($sub_item['restriction'] == 'admin' && $mb['user']['is_admin'])
								{
									printMenuItems($sub_item);
								}
							}
							else
							{
								printMenuItems($sub_item);
							}
						}
						echo "</ul>";
					}
				}
				echo "</li>";
			}
			?>
		</ul>
		<ul class="menu_position menu_right">
			<?php if (!$mb['user']['is_guest']): ?>
				<?php
				foreach ($mb['main_menu'] as $key => $logged_menu_item) {
					if (isset($logged_menu_item['restriction'])) {
						echo '<li class="'. $key .'">
								<a href="' . $logged_menu_item['href'] .'">' . $logged_menu_item['title'] .'</a>';
						if (count($logged_menu_item['sub_menu']) > 0)
						{
							echo '<ul class="nav_dropdown_sub dropdown_right primary_submenu" >';
							foreach ($logged_menu_item['sub_menu'] as $sub_item_key => $sub_item)
							{
								if (isset($sub_item['restriction']))
								{
									if ($sub_item['restriction'] == 'admin' && $mb['user']['is_admin'])
									{
										printMenuItems($sub_item);
									}
								}
								else
								{
									printMenuItems($sub_item);
								}
							}
							echo "</ul>";
						}
						echo "</li>";
					}
				}
				?>
				<li class="message_count">
					<a href="<?php echo $link['forum']; ?>?action=pm"
					   class="secondery_nav_menu_button"
					   title="Messages: <?php echo $mb['user']['unread_messages'], ' ', $mb['user']['unread_messages'] == 1 ? $txt['newmessages0'] : $txt['newmessages1'], ', total ', $mb['user']['messages']; ?>">
						<?php if ($mb['user']['unread_messages'] > 0): ?>
							<span class="message_new"><?php echo $mb['user']['unread_messages']; ?></span>
						<?php else: ?>
							<span class="message_no"><i class="fa fa-bell-o"></i></span>
						<?php endif; ?>
					</a>
				</li>
			<?php else: ?>
				<li>
					<a href="<?php echo $link['login']; ?>"><i class="fa fa-user"></i>&nbsp; <?php echo $lang['login']; ?></a>
				</li>
			<?php endif; ?>

		</ul>
	</div>
</nav>
<noscript>
	<p class="show_info info_red"><?php echo $lang['no_js']; ?></p>
</noscript>
<?php
if(file_exists($link['root'].'install.php') && $mb['website']['show_warning']){
	echo '<p class="show_info info_red"><b>SECURITY WARNING!</b><br> please delete <code>install.php</code> file from the directory.</p>';
}

?>
<!-- Navigation menu ends-->
