<!-- Footer Conetnt Begins -->
<footer class="footer_section">
	<div class="widget">
		<div id="widgetDownload"  class="widgetCommon">
			<h4><?php echo $lang['footer_179']; ?></h4>
			<ul>
				<li>
					<a href="<?php echo $link['download']; ?>" class="btn btn_blacknwhite">
						<i class="fa fa-download"></i> <?php echo $lang['footer_180']; ?> <?php echo $mb['musicbee_download']['stable']['appname']; ?>
					</a>
				</li>
				<li><?php echo $lang['footer_181']; ?> <?php echo $mb['musicbee_download']['stable']['version']; ?></li>
				<li><?php echo $lang['home_8']; ?> <?php echo $mb['musicbee_download']['stable']['supported_os']; ?></li>
				<li><?php echo $lang['footer_182']; ?> <?php echo $mb['musicbee_download']['stable']['release_date']; ?></li>
				<br/>
				<li class="line"></li>
				<br/>
				<li><?php echo $lang['footer_186']; ?></li>
				<li>
					<a href="<?php echo $link['rss']; ?>" class="btn btn_yellow" target="_blank">
						<i class="fa fa-rss"></i> <?php echo $lang['footer_184']; ?>
					</a>
					<?php if(!empty($setting['twitterLink'])): ?>
						<a href="<?php echo $setting['twitterLink']; ?>" target="_blank" class="btn btn_blue" >
						<i class="fa fa-twitter"></i>&nbsp; <?php echo $lang['footer_232']; ?>
						</a>
					<?php endif; ?>
				</li>


			</ul>
		</div>      
		<div id="widgetCustomize" class="widgetCommon">
			<h4><?php echo $lang['footer_188']; ?></h4>
			<ul class="footer_list_menu">
				<?php
				foreach ($mb['main_menu']['add-ons']['sub_menu'] as $key => $menu_addon) {
					echo "<li><a href=\"".$menu_addon['href']." \">";
					if (!empty($menu_addon['icon']) && empty($no_menu_icon)) echo $menu_addon['icon'];
					echo $menu_addon['title']."</a></li>";
				}
				?>
			</ul>
		</div>      
		<div id="widgetCommunity"  class="widgetCommon">
			<h4><?php echo $lang['footer_183']; ?></h4>
			<ul class="footer_list_menu">
				<li><a href="<?php echo $link['devapi']; ?>"><?php echo $lang['footer_185']; ?></a></li>
				<li><a href="<?php echo $link['bugreport']; ?>"><?php echo $lang['footer_187']; ?></a></li>

				<?php if(!empty($setting['wishlistLink'])): ?>
					<li><a href="<?php echo $setting['wishlistLink']; ?>"><?php echo $lang['footer_191']; ?></a></li>
				<?php endif; ?>

				<li><a href="<?php echo $link['press']; ?>"><?php echo $lang['footer_231']; ?></a></li>
				<?php if(!empty($setting['paypalDonationLink'])): ?>
				<ul class="footer_donation">
					<li><?php echo $lang['footer_189']; ?></li>
					<li>
						<a href="<?php echo $setting['paypalDonationLink']; ?>" target="_blank" class="btn btn_blue" >
							<i class="fa fa-paypal"></i>&nbsp; <?php echo $lang['footer_190']; ?>
						</a>
					</li>
				</ul>
				<?php endif; ?>
			</ul>
		</div>
		<div id="clear"></div>
	</div>
	<div class="footer_credit_wrap">
		<div class="footer_credit">
			<p><?php echo $lang['footer_192']; ?></p>
			<p id="copyright"><?php echo $lang['footer_193']; ?></p>
			<?php
			$endScriptTime=microtime(TRUE);
			$totalScriptTime=$endScriptTime-$startScriptTime;
			if($setting['showPgaeLoadTime']) {
				echo '<p>Page generated in '.number_format($totalScriptTime, 4).' seconds</p>';
			}
			?>
		</div>
	</div>
</footer>
<!-- Footer Ends-->