<?php
//load settings
$gallery_settings = unserialize(base64_decode(get_post_meta( $post->ID, 'awl_ig_settings_'.$post->ID, true)));
$image_gallery_id = $post->ID;

//toggle button CSS
wp_enqueue_style('nig-admin-bootstrap-css', IG_PLUGIN_URL . 'assets/css/admin-bootstrap.css');
wp_enqueue_style('nig-toogle-button-css', IG_PLUGIN_URL . 'assets/css/toogle-button.css');
wp_enqueue_style('nig-metabox-css', IG_PLUGIN_URL . 'assets/css/metabox.css');
//js
wp_enqueue_script('jquery');
wp_enqueue_script( 'nig-bootstrap-js',  IG_PLUGIN_URL .'assets/js/bootstrap.min.js', array( 'jquery' ), '', true  );


$col_large_desktops = $gallery_settings['col_large_desktops'];
$col_desktops = $gallery_settings['col_desktops'];
$col_tablets = $gallery_settings['col_tablets'];
$col_phones = $gallery_settings['col_phones'];
?>
<style>
.col-1, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-10, .col-11, .col-12, .col, .col-auto, .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm, .col-sm-auto, .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12, .col-md, .col-md-auto, .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg, .col-lg-auto, .col-xl-1, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl, .col-xl-auto {
float: left;
}
#comment-link-box, #edit-slug-box {
	display: none;
}
</style>
	<div class="row">
		<div class="col-lg-12 bhoechie-tab-container">
			<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 bhoechie-tab-menu">
				<div class="list-group">
					<a href="#" class="list-group-item active text-center">
						<span class="dashicons dashicons-editor-table"></span><br/><?php _e('Add Images', IGP_TXTDM); ?>
					</a>
					<a href="#" class="list-group-item text-center">
						<span class="dashicons dashicons-admin-generic"></span><br/><?php _e('Configure', IGP_TXTDM); ?>
					</a>
					<a href="#" class="list-group-item text-center">
						<span class="dashicons dashicons-admin-appearance"></span><br/><?php _e('Animation Effect', IGP_TXTDM); ?>
					</a>
					<a href="#" class="list-group-item text-center">
						<span class="dashicons dashicons-admin-customizer"></span><br/><?php _e('LightBox Settings', IGP_TXTDM); ?>
					</a>
					<a href="#" class="list-group-item text-center">
						<span class="dashicons dashicons-editor-code"></span><br/><?php _e('Custom Css', IGP_TXTDM); ?>
					</a>
					<a href="#" class="list-group-item text-center">
						<span class="dashicons dashicons-cart"></span><br/><?php _e('Upgrade To Pro', IGP_TXTDM); ?>
					</a>
				</div>
			</div>
			<div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 bhoechie-tab">
				<div class="bhoechie-tab-content active">
					<h1><?php _e('Add Images', IGP_TXTDM); ?></h1>
					<hr>
					<div id="slider-gallery">
						<input type="button" id="remove-all-slides" name="remove-all-slides" class="button button-large remove-all-slides" rel="" value="<?php _e('Delete All Images', IGP_TXTDM); ?>">
						<ul id="remove-slides" class="sbox">
						<?php
						$allimagesetting = unserialize(base64_decode(get_post_meta( $post->ID, 'awl_ig_settings_'.$post->ID, true)));
						if(isset($allimagesetting['slide-ids'])) {
							$count = 0;
						foreach($allimagesetting['slide-ids'] as $id) {
							$thumbnail = wp_get_attachment_image_src($id, 'medium', true);
							$attachment = get_post( $id );
							?>
							<li class="slide">
								<img class="new-slide" src="<?php echo $thumbnail[0]; ?>" alt="<?php echo get_the_title($id); ?>" style="height: 150px; width: 98%; border-radius: 8px;">
								<input type="hidden" id="slide-ids[]" name="slide-ids[]" value="<?php echo $id; ?>" />
								<input type="text" name="slide-title[]" id="slide-title[]" style="width: 98%;" placeholder="<?php _e('Image Title', IGP_TXTDM); ?>" value="<?php echo get_the_title($id); ?>">
								<a class="pw-trash-icon" name="remove-slide" id="remove-slide" href="#"><span class="dashicons dashicons-trash"></span></a>
							</li>
						<?php $count++; } // end of foreach
						} //end of if
						?>
						</ul>
					</div>
				</div>
				
				<div class="bhoechie-tab-content">
					<h1><?php _e('profile settings', IGP_TXTDM); ?></h1>
					<hr>
					
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h6><?php _e('Gallery Thumbnail Size', IGP_TXTDM); ?></h6>
							<p><?php _e('Select gallery thumbnails size to display into gallery', IGP_TXTDM); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field p-4">
							<?php if(isset($gallery_settings['gal_thumb_size'])) $gal_thumb_size = $gallery_settings['gal_thumb_size']; else $gal_thumb_size = "thumbnail"; ?>
							<select id="gal_thumb_size" name="gal_thumb_size" style="width:50%">	
								<option value="thumbnail" <?php if($gal_thumb_size == "thumbnail") echo "selected=selected"; ?>><?php _e('Thumbnail – 150 × 150', IGP_TXTDM); ?></option>
								<option value="medium" <?php if($gal_thumb_size == "medium") echo "selected=selected"; ?>><?php _e('Medium – 300 × 169', IGP_TXTDM); ?></option>
								<option value="large" <?php if($gal_thumb_size == "large") echo "selected=selected"; ?>><?php _e('Large – 840 × 473', IGP_TXTDM); ?></option>
								<option value="full" <?php if($gal_thumb_size == "full") echo "selected=selected"; ?>><?php _e('Full Size – 1280 × 720', IGP_TXTDM); ?></option>
							</select>
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h6><?php _e('Columns On Large Desktops', IGP_TXTDM); ?></h6>
							<p><?php _e('Select gallery column layout for large desktop devices', IGP_TXTDM); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field p-4">
							<?php if(isset($gallery_settings['col_large_desktops'])) $col_large_desktops = $gallery_settings['col_large_desktops']; else $col_large_desktops = "col-lg-2"; ?>
							<select id="col_large_desktops" name="col_large_desktops" style="width:40%">	
								<option value="col-lg-12" <?php if($col_large_desktops == "col-lg-12") echo "selected=selected"; ?>><?php _e('1 Column', IGP_TXTDM); ?></option>
								<option value="col-lg-6" <?php if($col_large_desktops == "col-lg-6") echo "selected=selected"; ?>><?php _e('2 Column', IGP_TXTDM); ?></option>
								<option value="col-lg-4" <?php if($col_large_desktops == "col-lg-4") echo "selected=selected"; ?>><?php _e('3 Column', IGP_TXTDM); ?></option>
								<option value="col-lg-3" <?php if($col_large_desktops == "col-lg-3") echo "selected=selected"; ?>><?php _e('4 Column', IGP_TXTDM); ?></option>
								<option value="col-lg-2" <?php if($col_large_desktops == "col-lg-2") echo "selected=selected"; ?>><?php _e('6 Column', IGP_TXTDM); ?></option>
								<option value="col-lg-1" <?php if($col_large_desktops == "col-lg-1") echo "selected=selected"; ?>><?php _e('12 Column', IGP_TXTDM); ?></option>
							</select>
						</div>
					</div>
					
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h6><?php _e('Columns On Desktops', IGP_TXTDM); ?></h6>
							<p><?php _e('Select gallery column layout for desktop devices', IGP_TXTDM); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field p-4">
							<?php if(isset($gallery_settings['col_desktops'])) $col_desktops = $gallery_settings['col_desktops']; else $col_desktops = "col-md-3"; ?>
							<select id="col_desktops" name="col_desktops" style="width:40%">	
								<option value="col-md-12" <?php if($col_desktops == "col-md-12") echo "selected=selected"; ?>><?php _e('1 Column', IGP_TXTDM); ?></option>
								<option value="col-md-6" <?php if($col_desktops == "col-md-6") echo "selected=selected"; ?>><?php _e('2 Column', IGP_TXTDM); ?></option>
								<option value="col-md-4" <?php if($col_desktops == "col-md-4") echo "selected=selected"; ?>><?php _e('3 Column', IGP_TXTDM); ?></option>
								<option value="col-md-3" <?php if($col_desktops == "col-md-3") echo "selected=selected"; ?>><?php _e('4 Column', IGP_TXTDM); ?></option>
								<option value="col-md-2" <?php if($col_desktops == "col-md-2") echo "selected=selected"; ?>><?php _e('6 Column', IGP_TXTDM); ?></option>
								<option value="col-md-1" <?php if($col_desktops == "col-md-1") echo "selected=selected"; ?>><?php _e('12 Column', IGP_TXTDM); ?></option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h6><?php _e('Columns On Tablets', IGP_TXTDM); ?></h6>
							<p><?php _e('Select gallery column layout for tablet devices', IGP_TXTDM); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field p-4">
							<?php if(isset($gallery_settings['col_tablets'])) $col_tablets = $gallery_settings['col_tablets']; else $col_tablets = "col-sm-4"; ?>
							<select id="col_tablets" name="col_tablets" style="width:40%">	
								<option value="col-sm-12" <?php if($col_tablets == "col-sm-12") echo "selected=selected"; ?>><?php _e('1 Column', IGP_TXTDM); ?></option>
								<option value="col-sm-6" <?php if($col_tablets == "col-sm-12") echo "selected=selected"; ?>><?php _e('2 Column', IGP_TXTDM); ?></option>
								<option value="col-sm-4" <?php if($col_tablets == "col-sm-4") echo "selected=selected"; ?>><?php _e('3 Column', IGP_TXTDM); ?></option>
								<option value="col-sm-3" <?php if($col_tablets == "col-sm-3") echo "selected=selected"; ?>><?php _e('4 Column', IGP_TXTDM); ?></option>
								<option value="col-sm-2" <?php if($col_tablets == "col-sm-2") echo "selected=selected"; ?>><?php _e('6 Column', IGP_TXTDM); ?></option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h6><?php _e('Colums On Phones', IGP_TXTDM); ?></h6>
							<p><?php _e('Select gallery column layout for phone devices', IGP_TXTDM); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field p-4">
							<?php if(isset($gallery_settings['col_phones'])) $col_phones = $gallery_settings['col_phones']; else $col_phones = "col-xs-6"; ?>
							<select id="col_phones" name="col_phones" style="width:40%">	
								<option value="col-xs-12" <?php if($col_phones == "col-xs-12") echo "selected=selected"; ?>><?php _e('1 Column', IGP_TXTDM); ?></option>
								<option value="col-xs-6" <?php if($col_phones == "col-xs-6") echo "selected=selected"; ?>><?php _e('2 Column', IGP_TXTDM); ?></option>
								<option value="col-xs-4" <?php if($col_phones == "col-xs-4") echo "selected=selected"; ?>><?php _e('3 Column', IGP_TXTDM); ?></option>
								<option value="col-xs-3" <?php if($col_phones == "col-xs-3") echo "selected=selected"; ?>><?php _e('4 Column', IGP_TXTDM); ?></option>
							</select>
						</div>
					</div>
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h6><?php _e('Hide Thumbnails Title', IGP_TXTDM); ?></h6>
							<p><?php _e('Hide Thumbnails Title Yes / No', IGP_TXTDM); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field p-4 switch-field em_size_field">
							<?php if(isset($gallery_settings['img_title'])) $img_title = $gallery_settings['img_title']; else $img_title = 0; ?>
							<input type="radio" name="img_title" id="img_title1" value="1" <?php if($img_title == 1) echo "checked=checked"; ?>>
							<label for="img_title1"><?php _e('Yes', IGP_TXTDM); ?></label>
							<input type="radio" name="img_title" id="img_title2" value="0" <?php if($img_title == 0) echo "checked=checked"; ?>>
							<label for="img_title2"><?php _e('No', IGP_TXTDM); ?></label>
						</div>
					</div>
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h6><?php _e('Hide Thumbnails Spacing', IGP_TXTDM); ?></h6>
							<p><?php _e('Hide gap / margin / padding / spacing between gallery', IGP_TXTDM); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field p-4 switch-field em_size_field">
							<?php if(isset($gallery_settings['no_spacing'])) $no_spacing = $gallery_settings['no_spacing']; else $no_spacing = 0; ?>
							<input type="radio" name="no_spacing" id="no_spacing1" value="1" <?php if($no_spacing == 1) echo "checked=checked"; ?>>
							<label for="no_spacing1"><?php _e('Yes', IGP_TXTDM); ?></label>
							<input type="radio" name="no_spacing" id="no_spacing2" value="0" <?php if($no_spacing == 0) echo "checked=checked"; ?>>
							<label for="no_spacing2"><?php _e('No', IGP_TXTDM); ?></label>
						</div>
					</div>
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h6><?php _e('Gallery Thumbnail Order', IGP_TXTDM); ?></h6>
							<p><?php _e('Set a image order in which you want to display gallery thumbnails', IGP_TXTDM); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field p-4 switch-field em_size_field">
							<?php if(isset($gallery_settings['thumbnail_order'])) $thumbnail_order = $gallery_settings['thumbnail_order']; else $thumbnail_order = "ASC"; ?>
							<input type="radio" name="thumbnail_order" id="thumbnail_order1" value="ASC" <?php if($thumbnail_order == "ASC") echo "checked=checked"; ?>>
							<label for="thumbnail_order1"><?php _e('Old First', IGP_TXTDM); ?></label>
							<input type="radio" name="thumbnail_order" id="thumbnail_order2" value="DESC" <?php if($thumbnail_order == "DESC") echo "checked=checked"; ?>>
							<label for="thumbnail_order2"><?php _e('New First', IGP_TXTDM); ?></label>
							<input type="radio" name="thumbnail_order" id="thumbnail_order3" value="RANDOM" <?php if($thumbnail_order == "RANDOM") echo "checked=checked"; ?>>
							<label for="thumbnail_order3"><?php _e('Random', IGP_TXTDM); ?></label>
						</div>
					</div>
				</div>	
				
				<div class="bhoechie-tab-content">
					<h1><?php _e('Image Hover Effect Type', IGP_TXTDM); ?></h1>
					<hr>
					
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h6><?php _e('Hover Effect Type', IGP_TXTDM); ?></h6>
							<p><?php _e('Select and Set a image hover effect type for Gallery', IGP_TXTDM); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field p-4 switch-field em_size_field">
							<?php if(isset($gallery_settings['image_hover_effect_type'])) $image_hover_effect_type = $gallery_settings['image_hover_effect_type']; else $image_hover_effect_type = "sg"; ?>
							<input type="radio" name="image_hover_effect_type" id="image_hover_effect_type1" value="no" <?php if($image_hover_effect_type == "no") echo "checked=checked"; ?>>
							<label for="image_hover_effect_type1"><?php _e('None', IGP_TXTDM); ?></label>
							<input type="radio" name="image_hover_effect_type" id="image_hover_effect_type2" value="sg" <?php if($image_hover_effect_type == "sg") echo "checked=checked"; ?>>
							<label for="image_hover_effect_type2"><?php _e('2D Transitions', IGP_TXTDM); ?></label>
						</div>
					</div>
					<div class="he_four">
						<div class="col-md-4">
							<div class="ma_field_discription">
								<h6><?php _e('Image Hover Effects', IGP_TXTDM); ?></h6>
								<p><?php _e('Select and Set a image hover effect type for Gallery', IGP_TXTDM); ?></p> 
							</div>
						</div>
						<div class="col-md-8">
							<div class="ma_field p-4">
								<?php if(isset($gallery_settings['image_hover_effect_four'])) $image_hover_effect_four = $gallery_settings['image_hover_effect_four']; else $image_hover_effect_four = "hvr-glow"; ?>
								<select name="image_hover_effect_four" id="image_hover_effect_four" style="width:40%">	
									<optgroup label="<?php _e('Shadow and Glow Transitions Effects', IGP_TXTDM); ?>" class="sg">
										<option value="hvr-grow-shadow" <?php if($image_hover_effect_four == "hvr-grow-shadow") echo "selected=selected"; ?>><?php _e('Grow Shadow', IGP_TXTDM); ?></option>
										<option value="hvr-float-shadow" <?php if($image_hover_effect_four == "hvr-float-shadow") echo "selected=selected"; ?>><?php _e('Float Shadow', IGP_TXTDM); ?></option>
										<option value="hvr-glow" <?php if($image_hover_effect_four == "hvr-glow") echo "selected=selected"; ?>><?php _e('Glow', IGP_TXTDM); ?></option>
										<option value="hvr-box-shadow-inset" <?php if($image_hover_effect_four == "hvr-box-shadow-inset") echo "selected=selected"; ?>><?php _e('Box Shadow Inset', IGP_TXTDM); ?></option>
										<option value="hvr-box-shadow-outset" <?php if($image_hover_effect_four == "hvr-box-shadow-outset") echo "selected=selected"; ?>><?php _e('Box Shadow Outset', IGP_TXTDM); ?></option>
									</optgroup>
								</select>
							</div>
						</div>
					</div>
				</div>
				
				<div class="bhoechie-tab-content">
					<h1><?php _e('Light Box Style', IGP_TXTDM); ?></h1>
					<hr>
					
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h6><?php _e('Light Box', IGP_TXTDM); ?></h6>
							<p><?php _e('Select a light box style', IGP_TXTDM); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field p-4">
							<?php if(isset($gallery_settings['light-box'])) $light_box = $gallery_settings['light-box']; else $light_box = 1; ?>
							<select name="light-box" id="light-box" style="width:50%">	
								<option value="0" <?php if($light_box == 0) echo "selected=selected"; ?>><?php _e('None', IGP_TXTDM); ?></option>
								<option value="6" <?php if($light_box == 6) echo "selected=selected"; ?>><?php _e('Bootstrap Light Box', IGP_TXTDM); ?></option>
							</select>
						</div>
					</div>
				</div>
				
				<div class="bhoechie-tab-content">
					<h1><?php _e('Custom CSS', IGP_TXTDM); ?></h1>
					<hr>
					
					<div class="col-md-4">
						<div class="ma_field_discription">
							<h6><?php _e('Custom CSS', IGP_TXTDM); ?></h6>
							<p><?php _e('Apply own CSS on image gallery and do not use style tag', IGP_TXTDM); ?></p> 
						</div>
					</div>
					<div class="col-md-8">
						<div class="ma_field p-4">
							<?php if(isset($gallery_settings['custom-css'])) $custom_css = $gallery_settings['custom-css']; else $custom_css = ""; ?>
							<textarea name="custom-css" id="custom-css" style="width: 100%; height: 120px;" placeholder="<?php _e('Type direct CSS code here. Do not use <style>...</style> tag.', IGP_TXTDM); ?>"><?php echo $custom_css; ?></textarea>
						</div>
					</div>
				</div>
				
				<div class="bhoechie-tab-content">
					<h1><?php _e('Upgrade To Pro', IGP_TXTDM); ?></h1>
					<hr>
					<!--Grid-->
					<div class="" style="padding-left: 10px;">
						<p class="ms-title">Upgrade To Premium For Unloack More Features & Settings</p>
					</div>

					<div class="">
						<h1><strong>Offer:</strong> Upgrade To Premium Just In Half Price <strike>$15</strike> <strong>$10</strong></h1>
						<br>
						<a href="https://awplife.com/demo/image-gallery-free-wordpress-plugin/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize">Check Free Plugin Demo</a>
						<a href="https://awplife.com/demo/image-gallery-premium/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize">Check Pro Plugin Demo</a>
						<a href="https://awplife.com/wordpress-plugins/image-gallery-wordpress-plugin/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize">Premium Version Details</a>
						<a href="https://awplife.com/demo/image-gallery-premium-admin-demo/" target="_blank" class="button button-primary button-hero load-customize hide-if-no-customize">Try Pro Version</a>
					</div>

				</div>
				
			</div>
		</div>                                                                            
	</div>    
	
<input type="hidden" name="ig-settings" id="ig-settings" value="ig-save-settings">

<!-- Return to Top -->

<script>


var effect_type = jQuery('input[name="image_hover_effect_type"]:checked').val();
//alert(effect_type);
if(effect_type == "no") {
	jQuery('.he_four').hide();
}
if(effect_type == "sg") {
	jQuery('.he_four').show();
}

//on change effect
jQuery(document).ready(function() {
	jQuery('input[name="image_hover_effect_type"]').change(function(){
		var effect_type = jQuery('input[name="image_hover_effect_type"]:checked').val();
		if(effect_type == "no") {
			jQuery('.he_four').hide();
		}
		if(effect_type == "sg") {
			jQuery('.he_four').show();
		}
	});
});

// tab
	jQuery("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
		e.preventDefault();
		jQuery(this).siblings('a.active').removeClass("active");
		jQuery(this).addClass("active");
		var index = jQuery(this).index();
		jQuery("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
		jQuery("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
	});
	
</script>