<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_shortcode('PFG', 'awl_filter_gallery_shortcode');
function awl_filter_gallery_shortcode($post_id) {
	ob_start();
	
	//$pf_gallery_settings = unserialize(base64_decode(get_post_meta( $post_id['id'], 'awl_filter_gallery'.$post_id['id'], true)));
	$pf_gallery_settings = get_post_meta( $post_id['id'], 'awl_filter_gallery'.$post_id['id'], true);
	$pf_gallery_id = $post_id['id'];
	
	if(isset($pf_gallery_settings['bootstrap_disable'])) $bootstrap_disable = $pf_gallery_settings['bootstrap_disable']; else $bootstrap_disable = "no";


	//js
	wp_enqueue_script('jquery');
	wp_enqueue_script('imagesloaded');
	if($bootstrap_disable == 'no') {
		wp_enqueue_script('awl-ig-bootstrap-js', PFG_PLUGIN_URL .'js/bootstrap.min.js', array('jquery'), '' , true);
	}
	wp_enqueue_script('awl-ig-controls-js', PFG_PLUGIN_URL .'js/controls.js', array('jquery'), '', false);
	wp_enqueue_script('awl-ig-filterizr-js', PFG_PLUGIN_URL .'js/jquery.filterizr.js', array('jquery'), '', false);
	wp_enqueue_script('awl-pfg-YouTubePopUp.jquery-js', PFG_PLUGIN_URL .'js/YouTubePopUp.jquery.js', array('jquery'), '', false);
	
	// css
	wp_enqueue_style('awl-filter-css', PFG_PLUGIN_URL .'css/filter-gallery.css');
	wp_enqueue_style('awl-bootstrap-css', PFG_PLUGIN_URL .'css/bootstrap.css');
	wp_enqueue_style('awl-YouTubePopUp-css', PFG_PLUGIN_URL .'css/YouTubePopUp.css');
	wp_enqueue_style('awl-font-awesome-css', PFG_PLUGIN_URL .'css/font-awesome.min.css');
	

	//columns settings
	if(isset($pf_gallery_settings['gal_size'])) $gal_thumb_size = $pf_gallery_settings['gal_size']; else $gal_thumb_size = "full";
	if(isset($pf_gallery_settings['col_large_desktops'])) $col_large_desktops = $pf_gallery_settings['col_large_desktops']; else $col_large_desktops = "col-lg-3";
	if(isset($pf_gallery_settings['col_desktops'])) $col_desktops = $pf_gallery_settings['col_desktops']; else $col_desktops = "col-lg-3";
	if(isset($pf_gallery_settings['col_tablets'])) $col_tablets = $pf_gallery_settings['col_tablets']; else $col_tablets = "col-sm-4";
	if(isset($pf_gallery_settings['col_phones'])) $col_phones = $pf_gallery_settings['col_phones']; else $col_phones = "col-xs-6";
	//image setting
	if(isset($pf_gallery_settings['gallery_direction'])) $gallery_direction = $pf_gallery_settings['gallery_direction']; else $gallery_direction = "ltr";
	if(isset($pf_gallery_settings['image_numbering'])) $image_numbering = $pf_gallery_settings['image_numbering']; else $image_numbering = "0";
	if(isset($pf_gallery_settings['title_thumb'])) $title_thumb = $pf_gallery_settings['title_thumb']; else $title_thumb = "show";
	
	//filter sorting controls
	//$filter_bg = $pf_gallery_settings['filter_bg'];
	if(isset($pf_gallery_settings['custom-css'])) $custom_css = $pf_gallery_settings['custom-css']; else $custom_css = "";
	if(isset($pf_gallery_settings['filter_bg'])) $filter_bg = $pf_gallery_settings['filter_bg']; else $filter_bg = "red";
	//filter setting for data-filters
	
	if(isset($pf_gallery_settings['filters'])) $filters = $pf_gallery_settings['filters']; else  $filters = array();
	// ligtbox style
	if(isset($pf_gallery_settings['light-box'])) $light_box = $pf_gallery_settings['light-box']; else $light_box = 1;
	if(isset($pf_gallery_settings['hide_filters'])) $hide_filters = $pf_gallery_settings['hide_filters']; else $hide_filters = 0;
	if(isset($pf_gallery_settings['all_txt'])) $all_txt = $pf_gallery_settings['all_txt']; else $all_txt = 'All';
	if(isset($pf_gallery_settings['sort_filter_order'])) $sort_filter_order = $pf_gallery_settings['sort_filter_order']; else $sort_filter_order = 0;
	if(isset($pf_gallery_settings['filter_position'])) $filter_position = $pf_gallery_settings['filter_position']; else $filter_position = "center";
	if(isset($pf_gallery_settings['search_box'])) $search_box = $pf_gallery_settings['search_box']; else $search_box = "";
	if(isset($pf_gallery_settings['search_txt'])) $search_txt = $pf_gallery_settings['search_txt']; else $search_txt = "";
	
	//hover effect
	if(isset($pf_gallery_settings['image_hover_effect_four'])) $image_hover_effect = $pf_gallery_settings['image_hover_effect_four']; else $image_hover_effect = "hvr-box-shadow-outset";
	if($image_hover_effect != "none") {
		// hover csss
		wp_enqueue_style('ggp-hover-css', PFG_PLUGIN_URL .'css/hover.css');
	}
	
	if(isset($pf_gallery_settings['thumb_border'])) $thumb_border = $pf_gallery_settings['thumb_border']; else $thumb_border = "yes";
	if(isset($pf_gallery_settings['no_spacing'])) $no_spacing = $pf_gallery_settings['no_spacing']; else $no_spacing = 1;
	if(isset($pf_gallery_settings['gray_scale'])) $gray_scale = $pf_gallery_settings['gray_scale']; else $gray_scale = 0;
	if(isset($pf_gallery_settings['thumbnail_order'])) $thumbnail_order = $pf_gallery_settings['thumbnail_order']; else $thumbnail_order = "ASC";
	if(isset($pf_gallery_settings['sort_by_title'])) $sort_by_title = $pf_gallery_settings['sort_by_title']; else $sort_by_title = "no";
	if(isset($pf_gallery_settings['url_target'])) $url_target = $pf_gallery_settings['url_target']; else $url_target = "_new";
	if(isset($pf_gallery_settings['filter_title_color'])) $filter_title_color = $pf_gallery_settings['filter_title_color']; else $filter_title_color = "#fff";
	
	?>
	<!-- CSS Part Start From Here-->
	<style>
		
		.portfolio-wraper {
			<?php if($gallery_direction == 'rtl') { ?>
				direction:rtl;
			<?php } ?>
			margin-bottom:20px;
		}
		
		li#all {
			margin: 0;
		}
		
		.simplefilter li {
			margin-top: 5px;
		}
		
		.filtr-search {
			margin-top:10px !important;
			width:200px !important;
			margin-bottom: 20px;
		}
		#all-simplefilter {
			display:inline;
			padding-left: 0px !important;
		}
		#f-simplefilter {
			display:inline;
			padding-left: 0px !important;
		}
		
		@keyframes .YouTubePopUp-Content  {
			from {
				transform: translate3d(0, 100%, 0);
				visibility: visible;
			}

			to {
				transform: translate3d(0, 0, 0);
			}
		}

		.YouTubePopUp-Content  {
			animation-name: .YouTubePopUp-Content ;
		}

		.YouTubePopUp-Close { 
			background:url(<?php echo PFG_PLUGIN_URL ?>/img/close-icon-white.png) no-repeat;
			background-size:50px 50px;
			-webkit-background-size:70px 50px;
			-moz-background-size:70px 50px;
			-o-background-size:70px 50px;
		}
		ul.simplefilter {
			margin-left: 0 !important;
		}
		
		#filter_gallery_<?php echo esc_html( $pf_gallery_id ); ?> .pfg_img_<?php echo esc_html( $pf_gallery_id ); ?> {
			width: 100% !important;
		}
		
		#filter_gallery_<?php echo esc_html( $pf_gallery_id ); ?> .thumbnail_<?php echo esc_html( $pf_gallery_id ); ?> {
			width:100% !important;
			height:auto;
			margin-bottom: 0px !important;
			
		}
		.filtr-item {
			padding-right:7px !important;
			padding-left:7px !important;
			padding-top:7px !important;
			padding-bottom:7px !important;
		}
		<?php if($no_spacing) { ?>
		#filter_gallery_<?php echo esc_html( $pf_gallery_id ); ?> .col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
			padding-right: 0px !important;
			padding-left: 0px !important;
				
		}
		#filter_gallery_<?php echo esc_html( $pf_gallery_id ); ?> .thumbnail_<?php echo esc_html( $pf_gallery_id ); ?> {
			padding: 0px !important;
			margin-bottom: 0px !important;
			border: 0px !important;
			border-radius: 0px !important;
		}
		.filtr_item_<?php echo esc_html( $pf_gallery_id ); ?> {
			padding: 0rem !important;			
		}
		.item_desc_<?php echo esc_html( $pf_gallery_id ); ?> {
			bottom: 12px  !important;
			left: 0rem !important;
			right: 0rem !important;
		}
		.item_position_<?php echo esc_html( $pf_gallery_id ); ?> {
			left: 2rem !important;
			top: 2rem !important;
		}
		<?php } ?>
		<?php if($gray_scale){ ?>
		.filtr_item_<?php echo esc_html( $pf_gallery_id ); ?> img {
			filter: grayscale(70%);
		}
		.filtr_item_<?php echo esc_html( $pf_gallery_id ); ?> img:hover {
		   filter: none;
		}
		<?php } ?>
		.simplefilter_<?php echo esc_html( $pf_gallery_id ); ?> li {
			background-color: #ccc !important;
			color: <?php echo esc_html($filter_title_color); ?> !important;
		}
		
		.simplefilter_<?php echo esc_html( $pf_gallery_id ); ?> li:hover {
			background-color: <?php echo esc_html($filter_bg); ?> !important;
		}
		
		.simplefilter_<?php echo esc_html( $pf_gallery_id ); ?> li.active {
			background-color: <?php echo esc_html($filter_bg); ?> !important;
		}
		
		<?php echo $custom_css; ?>
	</style>
	<?php
	
	// load without lightbox gallery output
	if($light_box == 0) {
		require('filters-gallery-output.php');
	}
	// load blue imp lightbox gallery output
	
	if($light_box == 5) {
		require('pfg-bootstrap-lightbox.php');
	}
	if($light_box == 4) {
		require('pfg-ld-lightbox.php');
	}
	return ob_get_clean();
}
?>