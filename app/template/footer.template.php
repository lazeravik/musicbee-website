<!-- Footer Conetnt Begins -->
<footer class="footer_section">
	<div class="widget">
		<div id="widgetDownload" class="widgetCommon">
			<h4><?php echo $lang['get_latest_mb']; ?></h4>
			<ul>
				<li>
					<a href="<?php echo $link['download']; ?>" class="btn btn_blacknwhite">
						<i class="fa fa-download"></i>&nbsp; <?php echo sprintf($lang['get_mb'], $mb['musicbee_download']['stable']['appname']); ?>
					</a>
				</li>
				<li><?php echo sprintf($lang['version_number'], $mb['musicbee_download']['stable']['version']); ?></li>
				<li><?php echo sprintf($lang['for_os'], $mb['musicbee_download']['stable']['supported_os']);?></li>
				<li><?php echo sprintf($lang['released_on_date'], $mb['musicbee_download']['stable']['release_date']);?></li>

				<li class="line"></li>

				<li><?php echo $lang['get_notified_new_release']; ?></li>

				<li>
					<a href="<?php echo $link['rss']; ?>" class="btn btn_yellow" target="_blank">
						<i class="fa fa-rss"></i>&nbsp; <?php echo $lang['subscribe_rss']; ?>
					</a>
					<?php if(!empty($setting['twitterLink'])): ?>
						<a href="<?php echo $setting['twitterLink']; ?>" target="_blank" class="btn btn_blue">
							<i class="fa fa-twitter"></i>&nbsp; <?php echo $lang['twitter']; ?>
						</a>
					<?php endif; ?>
				</li>
			</ul>
		</div>
		<div id="widgetCustomize" class="widgetCommon">
			<h4><?php echo $lang['addons_for_mb']; ?></h4>
			<ul class="footer_list_menu">
				<?php
				foreach($mb['main_menu']['add-ons']['sub_menu'] as $key => $menu_addon) {
					echo "<li><a href=\"".$menu_addon['href']." \">";
					if(!empty($menu_addon['icon']) && empty($no_menu_icon)) {
						echo $menu_addon['icon'].'&nbsp;&nbsp;';
					}
					echo $menu_addon['title']."</a></li>";
				}
				?>
			</ul>
		</div>
		<div id="widgetCommunity" class="widgetCommon">
			<h4><?php echo $lang['more']; ?></h4>
			<ul class="footer_list_menu">
				<li>
					<a href="<?php echo $link['api']; ?>"><i class="fa fa-code"></i>&nbsp;&nbsp;<?php echo $lang['dev_api']; ?></a>
				</li>

				<li>
					<a href="<?php echo $link['bugreport']; ?>"><i class="fa fa-bug"></i>&nbsp;&nbsp;<?php echo $lang['report_bug']; ?></a>
				</li>
				<?php if(!empty($setting['wishlistLink'])): ?>
					<li>
						<a href="<?php echo $setting['wishlistLink']; ?>"><i class="fa fa-heartbeat"></i>&nbsp;&nbsp;<?php echo $lang['add_new_wishlist']; ?></a>
					</li>
				<?php endif; ?>

				<li>
					<a href="<?php echo $link['press']; ?>"><i class="fa fa-bullhorn"></i>&nbsp;&nbsp;<?php echo $lang['press']; ?></a>
				</li>
				<?php if(!empty($setting['paypalDonationLink'])): ?>
					<ul class="footer_donation">
						<li><?php echo $lang['support_mb_with_donation']; ?></li>
						<li>
							<a href="<?php echo $setting['paypalDonationLink']; ?>" target="_blank" class="btn btn_blue">
								<i class="fa fa-paypal"></i>&nbsp; <?php echo $lang['paypal_donation']; ?>
							</a>
						</li>
					</ul>
				<?php endif; ?>
			</ul>
		</div>
	</div>
	<div class="footer_credit_wrap">
		<ul class="footer_credit">
			<li>
				<p><a href="<?php echo $link['credit']; ?>" class="credit_link"><?php echo $lang['site_built_with_love']; ?></a> &nbsp;&nbsp;|&nbsp;&nbsp; v<?php echo $mb['website']['ver']; ?></p>
				<p id="copyright"><?php echo $lang['musicbee_copyright']; ?></p>
				<?php
				$endScriptTime = microtime(true);
				$totalScriptTime = $endScriptTime - $startScriptTime;
				if($setting['showPgaeLoadTime']) {
					echo '<p>Page generated in '.number_format($totalScriptTime, 4).' seconds</p>';
				}
				?>
			</li>
			<li class="right">
				<select class="lang_selector" id="lang_selector">
					<?php
					foreach($lang_filelist as $key => $lang_file) {
						echo '<option value="'.$lang_file['meta'].'" ',($language['meta']==$lang_file['meta'])?'selected':'','>'.Format::unslugTxt($lang_file['name']).' ('.$lang_file['meta'].')</option>';

					}
					?>
				</select>
			</li>
		</ul>
	</div>
</footer>
<script>
	document.getElementById('lang_selector').onchange = function () {
		var lang_req = '?lang='+this.getElementsByTagName('option')[this.selectedIndex].value;
		window.location.href = '<?php echo $link['redirect']; ?>'+lang_req + window.location.hash;
	}
</script>
<?php if(empty($jquery_preloaded)): ?>
	<script src="<?php echo $link['js-dir']; ?>jquery/jquery.js"></script>
<?php endif; ?>
<script src="<?php echo $link['js-dir']; ?>menu.navigation.js"></script>


<!-- Footer Ends-->
