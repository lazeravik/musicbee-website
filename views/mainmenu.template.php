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

	require_once $_SERVER['DOCUMENT_ROOT'] . '/functions.php'; ?>
<?php

	//var_dump($_SESSION['memberinfo']);
?>
<!-- Navigation Menu starts -->
<nav class="mainmenu" id="main_menu">
	<div class="menu_wrapper">
		<ul class="menu_position menu_left">
			<?php
				//var_dump($mb['main_menu']);
				foreach ($mb['main_menu'] as $key => $menu_item) {
					if (!isset($menu_item['restriction'])) {
						echo "<li><a href=\"" . $menu_item['href'] . " \">" . $menu_item['title'] . "</a>";
						if (count($menu_item['sub_menu']) > 0) {
							echo "<ul class=\"nav_dropdown_sub primary_submenu\">";
							foreach ($menu_item['sub_menu'] as $sub_item_key => $sub_item) {
								if (isset($sub_item['restriction'])) {
									if ($sub_item['restriction'] == 'admin' && $mb['user']['is_admin']) {
										echo "<li>";
										if (!empty($sub_item['href'])) echo "<a href=\"" . $sub_item['href'] . " \">";
										if (!empty($sub_item['icon']) && empty($no_menu_icon)) echo $sub_item['icon'];
										echo $sub_item['title'];
										if (!empty($sub_item['href'])) echo "</a>";
										echo "</li>";
									}
								} else {
									echo "<li>";
									if (!empty($sub_item['href'])) echo "<a href=\"" . $sub_item['href'] . " \">";
									if (!empty($sub_item['icon']) && empty($no_menu_icon)) echo $sub_item['icon'];
									echo $sub_item['title'];
									if (!empty($sub_item['href'])) echo "</a>";
									echo "</li>";
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
						if (count($logged_menu_item['sub_menu']) > 0) {
							echo '<ul class="nav_dropdown_sub dropdown_right primary_submenu" >';
							foreach ($logged_menu_item['sub_menu'] as $sub_item_key => $sub_item) {
								if (isset($sub_item['restriction'])) {
									if ($sub_item['restriction'] == 'admin' && $mb['user']['is_admin']) {
										echo '<li>';
										if (!empty($sub_item['href']))
											echo "<a href=\"" . $sub_item['href'] . " \">";
										if (!empty($sub_item['icon']) && empty($no_menu_icon))
											echo $sub_item['icon'];
										echo $sub_item['title'];
										if (!empty($sub_item['href']))
											echo "</a>";
										echo '</li>';
									}
								} else {
									echo '<li>';
									if (!empty($sub_item['href']))
										echo "<a href=\"" . $sub_item['href'] . " \">";
									if (!empty($sub_item['icon']) && empty($no_menu_icon))
										echo $sub_item['icon'];
									echo $sub_item['title'];
									if (!empty($sub_item['href']))
										echo "</a>";
									echo "</li>";
								}
							}
							echo "</ul>";
						}
						echo "</li>";
					}
				}
				?>
				<li>
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
					<a href="<?php echo $link['login']; ?>"><i class="fa fa-user"></i>&nbsp; <?php echo $lang['17']; ?></a>
				</li>
			<?php endif; ?>

		</ul>
	</div>
</nav>
<noscript>
	<div class="noscript_wrap"><p class="show_info info_red"><?php echo $lang['no_js']; ?></p></div>
</noscript>
<!-- Navigation menu ends-->
