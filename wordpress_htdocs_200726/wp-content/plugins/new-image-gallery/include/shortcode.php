<?php
/**
 * Slider Responsive Premium Shortcode
 *
 * @access    public
 * @since     3.0
 *
 * @return    Create Fontend Slider Output
 */
add_shortcode('IMG-Gal', 'awl_image_gallery_shortcode');
function awl_image_gallery_shortcode($post_id) {
	ob_start();
	//js
	wp_enqueue_script('awl-ig-bootstrap-js', IG_PLUGIN_URL .'assets/js/bootstrap.min.js');
	wp_enqueue_script('awl-imagesloaded-pkgd-js', IG_PLUGIN_URL .'assets/js/imagesloaded.pkgd.js');
	wp_enqueue_script('awl-ig-isotope-js', IG_PLUGIN_URL .'assets/js/isotope.pkgd.min.js');
		
	// awp custom bootstrap css
	wp_enqueue_style('awl-bootstrap-css', IG_PLUGIN_URL .'assets/css/ig-bootstrap.css');
	
	$gallery_settings = unserialize(base64_decode(get_post_meta( $post_id['id'], 'awl_ig_settings_'.$post_id['id'], true)));
	//print_r($gallery_settings);
	
	$image_gallery_id = $post_id['id'];
	
	//columns settings
	$gal_thumb_size = $gallery_settings['gal_thumb_size'];
	$col_large_desktops = $gallery_settings['col_large_desktops'];
	$col_desktops = $gallery_settings['col_desktops'];
	$col_tablets = $gallery_settings['col_tablets'];
	$col_phones = $gallery_settings['col_phones'];
	
	// ligtbox style
	if(isset($gallery_settings['light-box'])) $light_box = $gallery_settings['light-box']; else $light_box = 1;
	
	//hover effect
	if(isset($gallery_settings['image_hover_effect_type'])) $image_hover_effect_type = $gallery_settings['image_hover_effect_type']; else $image_hover_effect_type = "no";
	if($image_hover_effect_type == "no") {
		$image_hover_effect = "";
	} else {
		// hover csss
		wp_enqueue_style('ggp-hover-css', IG_PLUGIN_URL .'assets/css/hover.css');
	}
	if($image_hover_effect_type == "sg")
		if(isset($gallery_settings['image_hover_effect_four'])) $image_hover_effect = $gallery_settings['image_hover_effect_four']; else $image_hover_effect = "hvr-box-shadow-outset";
	
	if(isset($gallery_settings['no_spacing'])) $no_spacing = $gallery_settings['no_spacing']; else $no_spacing = 1;
	if(isset($gallery_settings['thumbnail_order'])) $thumbnail_order = $gallery_settings['thumbnail_order']; else $thumbnail_order = "ASC";
	if(isset($gallery_settings['url_target'])) $url_target = $gallery_settings['url_target']; else $url_target = "_new";
	if(isset($gallery_settings['custom-css'])) $custom_css = $gallery_settings['custom-css']; else $custom_css = "";
	if(isset($gallery_settings['img_title'])) $img_title = $gallery_settings['img_title']; else $img_title = 0;
	?>
	<!-- CSS Part Start From Here-->
	<style>
	.all-images {
		padding-top: 10px !important;
		padding-bottom: 15px !important;
	}
	#image_gallery_<?php echo $image_gallery_id; ?> .thumbnail {
		width: 100% !important;
		height: auto !important;
		border-radius: 0px;
		/*background: transparent url('<?php echo IG_PLUGIN_URL.'assets/img/loading.gif'; ?>') center no-repeat !important;*/
	}
	<?php if($no_spacing) { ?>
	#image_gallery_<?php echo $image_gallery_id; ?> .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
		padding-right: 0px !important;
		padding-left: 0px !important;
	}
	#image_gallery_<?php echo $image_gallery_id; ?> .thumbnail {
		padding: 0px !important;
		margin-bottom: 0px !important;
		border: 0px !important;
	}
	<?php } ?>
	.item-title {
		background-color: rgba(0, 0, 0, 0.5);
		bottom: 45px;
		color: #FFFFFF;
		display: block;
		font-weight: 300;
		left: 2rem;
		padding: 8px;
		position: absolute;
		right: 2rem;
		text-align: center;
		text-transform: capitalize;
	}
	<?php echo $custom_css; ?>
	</style>
	<?php
	// load without lightbox gallery output
	if($light_box == 0) {
		require('nig-no-lightbox.php');
	}
	
	// load bootstrap 3 lightbox gallery output
	if($light_box == 6) {
		require('nig-bootstrap-lightbox.php');
	}
	return ob_get_clean();
}
?>