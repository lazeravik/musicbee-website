<!DOCTYPE html>
<html>
<head>
	<title><?php echo ucfirst($data['type']); ?> for MusicBee | Add-ons</title>
	<meta name="description" content="<?php echo $meta_description; ?>">
        <!-- keyword meta tag is obsolete, google does not use it, but some
        search engine still use it, so for legacy support it is included -->
        <meta name="keywords" content="musicbee, music, player, unltimate, best, customizable, skin, free, plugin, download">

        <!--include common meta tags and stylesheets -->
        <?php include $siteRoot . 'includes/meta&styles.php'; ?>
        <!--Use OWL carousel for image screenshot slider -->
        <link rel="stylesheet" href="<?php echo $siteUrl; ?>styles/magnific-popup.css">
        <!-- Used for slider animation -->
        <link rel="stylesheet" type="text/css" href="<?php echo $siteUrl; ?>styles/MusicBeeAddons.css">
        <!--roboto is messed up when clearfont is disabled this makes sure that it looks great -->
        <?php include $siteRoot . 'includes/font.helper.php'; ?>
        <?php if (!empty($addon_type)): ?>
        	<style>
        		#<?php echo Slug($addon_type); ?>_active_page{
        			box-shadow: inset 0px -3px 0px #FFC107;
        			color: #FFC107;
        			background: rgba(0, 0, 0, 0.1);
        		}
        	</style>
        <?php endif; ?>
    </head>
    <body>
    	<div id="indexBackground">
    		<div id="wrapper">
    			<!--IMPORTANT-->
    			<!-- INCLUDE MAIN MENU FOR BASIC NAVIGATION -->
    			<?php
    			include($mainmenu);
    			?>
    			<!-- BODY CONTENT -->
    			<div id="main">
    				<div id="main_panel">
    					<!-- addon page INFO SECTION STARTS -->
    					<div id="bg_image" class="general_info">
    						<!-- AddOn page navigation top menu -->
    						<div class="secondery_nav addon_secondery_nav secondery_nav_color" id="secondery_nav">
    							<div class="secondery_nav_wrap">
    								<ul class="left">
    									<li><a href="<?php echo $link['addon']['home'] . "s/?q=&type=all&order=latest"; ?>" id="all_active_page"><?php echo $lang['18']; ?></a></li>
    									<?Php
    									foreach ($main_menu['add-ons']['sub_menu'] as $key => $menu_addon) {
    										echo "<li><a href=\"" . $menu_addon['href'] . " \"  id=\"" . Slug($menu_addon['title']) . "_active_page\">" . $menu_addon['title'] . "</a></li>";
    									}
    									?>
    									<div id="clear"></div>
    								</ul>
    								<div id="clear"></div>
    							</div>
    						</div>
    						<div class="mb_addon_bg">
    							<div id="overlay_bg" class="search_box_outer_wrap">
    								<div class="sub_content">
    									<div class="search_box">
    										<div class="search_box_wrap">
    											<form method="GET" action="<?php echo $link['addon']['home']; ?>s/">
    												<p><label for="order">Search: </label></p>
    												<div class="search_left">
    													<input type="text" class="srch big_search" id="big_search" name="q" placeholder="Search <?php echo $data['type']; ?>" value="<?php echo htmlspecialchars($url_params['q'], ENT_QUOTES, "UTF-8"); ?>"/>
                                                        <input type="hidden" name="search" value="true" />
    												</div>
    												<div class="search_right">
    													<button class="yellow_btn_big search_btn" ><h3>Search</h3></button>
    												</div>
    												<div class="search_filter_wrap">
    													<div class="search_filter_cat">
    														<div class="search_cat_input">
    															<p><label for="type">Category: </label></p>
    															<select name="type" id="type">
    																<?php
    																echo "<option value=\"all\" ";
    																if ($data['type'] == "All")
    																	echo "selected";
    																echo ">All</option>";
    																foreach ($main_menu['add-ons']['sub_menu'] as $key => $menu_addon) {
    																	echo "<option value=\"" . Slug($menu_addon['title']) . "\"";
    																	if ($addon_type == Slug($menu_addon['title']))
    																		echo "selected";
    																	echo ">" . $menu_addon['title'] . "</option>";
    																}
    																?>
    															</select>
    															<div id="clear"></div>	
    														</div>
    													</div>
    													<div class="search_filter_cat">
    														<div class="search_cat_input search_cat_input_last">
    															<p><label for="order">Order By: </label></p>
    															<select name="order" id="order">
    																<option value="latest">Latest</option>
    																<option value="oldest">Oldest</option>
    																<!-- <option value="likes">Likes</option> -->
    															</select>
    															<div id="clear"></div>	
    														</div>
    													</div>
    													<div id="clear"></div>
    												</div>
    												<div id="clear"></div>
    											</form>
    										</div>
    									</div>

    								</div>
    							</div>
    						</div>

    						<div class="addon_similar addon_cat_header">
    							<hr class="line" />
                                <div class="addon_similar_wrap">
                                    <?php 
                                    if (!empty($url_params['q'])) {
                                        echo "<h2>".$lang['269']."<i class=\"search_term\">".htmlspecialchars($url_params['q'], ENT_QUOTES, "UTF-8")."</i>"."<i class=\"search_term\">".$data['type']."</i></h2>";
                                    } else {
                                        echo "<h2>".$data['type']."</h2>";
                                        if (isset($main_menu['add-ons']['sub_menu'][$addon_type]['desc'])) {
                                            echo "<h4>" . $main_menu['add-ons']['sub_menu'][$addon_type]['desc'] . "</h4>";
                                        }
                                    }
                                    ?>

                                </div>       
                            </div>
                            <div class="addon_similar addon_cat_header">
                             <div class="addon_similar_wrap">
                                <?php if ($data['addon'] != null): ?>
                                   <ul class="addon_list_box">
                                      <?php foreach ($data['addon'] as $key => $addon_data):
                                      $addon_author = $addon->memberInfo($addon_data['ID_AUTHOR'])[0];
                                      $addon_link = $link['addon']['home'] . $addon_data['ID_ADDON'] . '/' . Slug($addon_data['addon_title']);
                                      ?>
                                      <li id ="<?php echo $addon_data['ID_ADDON']; ?>">
                                         <div class="addon_list_box_wrapper">
                                            <a href="<?php echo $addon_link; ?>">
                                               <div class="thumb_more" style="background-image:url(<?php echo $addon_data['thumbnail']; ?>)"></div>
                                               <div class="love"><i class="fa fa-heart"></i><p class="love_count"><?php echo $addon->getRating($addon_data['ID_ADDON']); ?></p></div>
                                           </a>
                                           <div class="addon_list_box_info">
                                               <a href="<?php echo $addon_link; ?>">
                                                  <p class="title"><?php echo $addon_data['addon_title']; ?></p>
                                              </a>
                                              <p class="author">by <?php echo $addon_author['membername']; ?></p>
                                          </div>
                                      </div>
                                  </li>
                              <?php endforeach; ?>
                              <div id="clear"></div>
                          </ul>
                          <ul class="pagination">
                            <?php for ($i=1; $i < $page_total+1; $i++): ?>
                               <li><a href="<?php echo $generated_url ?>&p=<?php echo $i; ?>"><p><?php echo $i; ?></p></a></li>
                           <?php endfor; ?>
                       </ul>
                   <?php else: ?>
                    <div class="no_result">
                       <h2>There is nothing yet! <i class="fa fa-frown-o"></i></h2>
                       <p>Request for <?php echo $data['type']; ?> in forum, or you can create and submit one yourself</p>
                   </div>
               <?php endif; ?>
           </div>
       </div>
   </div>

</div>
</div>
</div>
</div>
<!--IMPORTANT-->
<!-- INCLUDE THE FOOTER AT THE END -->
<?php
include($footer);
?>

<script type="text/javascript" src="<?php echo $siteUrl; ?>scripts/jquery-2.1.4.min.js"></script>
<script src="<?php echo $siteUrl; ?>scripts/jquery.sticky.min.js"></script>
<script type="text/javascript">
   $('#type').on('change', function (event) {
      $('#big_search').attr('placeholder', 'Search ' + $("#type option:selected").text());
  });

   $("#secondery_nav").sticky({topSpacing: 0});
</script>
</body>
</html>