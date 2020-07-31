<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
Plugin Name: Portfolio Filter Gallery
Plugin URI: http://awplife.com/
Description: Portfolio Filter Gallery For WordPress.
Version: 1.2.2
Author: A WP Life
Author URI: http://awplife.com/
License: GPLv2 or later
Text Domain: portfolio-filter-gallery
Domain Path: /languages
**/

if ( ! class_exists( 'Awl_Portfolio_Filter_Gallery' ) ) {

	class Awl_Portfolio_Filter_Gallery {
		
		public function __construct() {
			$this->_constants();
			$this->_hooks();
		}		
		
		protected function _constants() {
			//Plugin Version
			define( 'PFG_PLUGIN_VER', '1.2.2' );
			
			//Plugin Name
			define( 'PFG_PLUGIN_NAME', __( 'Portfolio Filter Gallery', 'portfolio-filter-gallery' ) );

			//Plugin Slug
			define( 'PFG_PLUGIN_SLUG', 'awl_filter_gallery' );

			//Plugin Directory Path
			define( 'PFG_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

			//Plugin Directory URL
			define( 'PFG_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

			define( 'PFG_SECURE_KEY', md5( NONCE_KEY ) );
			
		} // end of constructor function 
		
		protected function _hooks() {
			
			//Load text domain
			add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			
			//add gallery menu item, change menu filter for multisite
			add_action( 'admin_menu', array( $this, 'pfg_menu' ), 101 );
			
			//Create Portfolio Filter Gallery Custom Post
			add_action( 'init', array( $this, 'Portfolio_Filter_Gallery' ));
			
			//Add meta box to custom post
			add_action( 'add_meta_boxes', array( $this, 'admin_add_meta_box' ) );
			 
			//loaded during admin init 
			add_action( 'admin_init', array( $this, 'admin_add_meta_box' ) );
			
			add_action('wp_ajax_pfg_gallery_js', array(&$this, '_ajax_pfg_gallery'));
		
			add_action('save_post', array(&$this, '_pfg_save_settings'));

			//Shortcode Compatibility in Text Widgets
			add_filter('widget_text', 'do_shortcode');
			
			// add pfg cpt shortcode column - manage_{$post_type}_posts_columns
			add_filter( 'manage_awl_filter_gallery_posts_columns', array(&$this, 'set_filter_gallery_shortcode_column_name') );
			
			// add pfg cpt shortcode column data - manage_{$post_type}_posts_custom_column
			add_action( 'manage_awl_filter_gallery_posts_custom_column' , array(&$this, 'custom_filter_gallery_shodrcode_data'), 10, 2 );

			add_action( 'wp_enqueue_scripts', array(&$this, 'enqueue_scripts_in_header') );
			
			// only for admin dashboard pfg ajax JS
			add_action( 'admin_enqueue_scripts', array(&$this, 'awplife_pfg_admin_scripts'));
			
			// ajax call back, its required localize ajax object
			add_action('wp_ajax_pfg_delete_filter', array(&$this, 'awplife_pfg_delete_filter'));
			add_action('wp_ajax_pfg_delete_all_filter', array(&$this, 'awplife_pfg_delete_all_filters'));
		
		}// end of hook function
		
		
		public function awplife_pfg_admin_scripts() {
			wp_enqueue_script( 'ajax-script', PFG_PLUGIN_URL. 'js/pfg-ajax-script.js', array('jquery'));
			wp_localize_script( 'ajax-script', 'pfg_ajax_object', 
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ), 
					'ajaxnonce' => wp_create_nonce('pfg_ajax_nonce_action_name'),
				)
			);
		}

		// delete filter by ajax
		public function awplife_pfg_delete_filter() {
			if (isset( $_POST['security'] ) && wp_verify_nonce( $_POST['security'], 'pfg_ajax_nonce_action_name' ) ) {

				$id = sanitize_text_field($_POST['filter_id']);
				$all_category = get_option('awl_portfolio_filter_gallery_categories');
				//print_r($all_category);
				if(is_array($all_category)) {
					unset($all_category[$id]);
					$all_category = array_filter( $all_category );
				}
				update_option( 'awl_portfolio_filter_gallery_categories', $all_category);
			}
		}
		
		// delete all selected filter by ajax 
		public function awplife_pfg_delete_all_filters() {
			if (isset( $_POST['security'] ) && wp_verify_nonce( $_POST['security'], 'pfg_ajax_nonce_action_name' ) ) {
				
				$ids = isset( $_POST['filter_ids'] ) ? (array) $_POST['filter_ids'] : array();
				$ids = array_map( 'sanitize_text_field', $ids );
				$all_category = get_option('awl_portfolio_filter_gallery_categories');
				if(is_array($all_category)) {
					foreach($ids as $id) {
						unset($all_category[$id]);
						$all_category_updarted = array_filter( $all_category );
					}
				}
				update_option( 'awl_portfolio_filter_gallery_categories', $all_category);
			}
		}
		
		public function enqueue_scripts_in_header() {
			wp_enqueue_script('jquery');
		}
		// end of hook function
		
		// Filter gallery cpt shortcode column before date columns
		public function set_filter_gallery_shortcode_column_name($defaults) {
			$new = array();
			$shortcode = $columns['_filter_gallery_shortcode'];  // save the tags column
			unset($defaults['tags']);	// remove it from the columns list

			foreach($defaults as $key=>$value) {
				if($key=='date') {  // when we find the date column
				   $new['_filter_gallery_shortcode'] = __( 'Shortcode', 'portfolio-filter-gallery' );  // put the tags column before it
				}    
				$new[$key] = $value;
			}
			return $new;  
		}
		
		// Filter gallery cpt shortcode column data
		public function custom_filter_gallery_shodrcode_data( $column, $post_id ) {
			switch ( $column ) {
				case '_filter_gallery_shortcode' :
					echo "<input type='text' class='button button-primary' id='filter-gallery-shortcode-$post_id' value='[PFG id=$post_id]' style='font-weight:bold; background-color:#32373C; color:#FFFFFF; text-align:center;' />";
					echo "<input type='button' class='button button-primary' onclick='return FilterCopyShortcode$post_id();' readonly value='Copy' style='margin-left:4px;' />";
					echo "<span id='copy-msg-$post_id' class='button button-primary' style='display:none; background-color:#32CD32; color:#FFFFFF; margin-left:4px; border-radius: 4px;'>copied</span>";
					echo "<script>
						function FilterCopyShortcode$post_id() {
							var copyText = document.getElementById('filter-gallery-shortcode-$post_id');
							copyText.select();
							document.execCommand('copy');
							
							//fade in and out copied message
							jQuery('#copy-msg-$post_id').fadeIn('1000', 'linear');
							jQuery('#copy-msg-$post_id').fadeOut(2500,'swing');
						}
						</script>
					";
				break;
			}
		}
		
		public function load_textdomain() {
			load_plugin_textdomain( 'portfolio-filter-gallery', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}
		
		public function pfg_menu() {
			// hide the update setting menu once the porcess done ((1 = show, 2 = hide))
			$old_setting_update_status = get_option('pfg_old_settings_updated', 1); // porcess check
			$pfg_hide_notice = get_option('pfg_hide_notice', 1); // hide notice
			if($old_setting_update_status == 1 && $pfg_hide_notice == 1) {
				$update_old_settings_menu = add_submenu_page( 'edit.php?post_type='.PFG_PLUGIN_SLUG, __( 'Update Old Settings', 'portfolio-filter-gallery' ), __( 'Update Old Settings', 'portfolio-filter-gallery' ), 'administrator', 'pfg-update-plugin', array( $this, 'update_old_settings_page') );
			}
			$filter_menu = add_submenu_page( 'edit.php?post_type='.PFG_PLUGIN_SLUG, __( 'Filters', 'portfolio-filter-gallery' ), __( 'Filters', 'portfolio-filter-gallery' ), 'administrator', 'pfg-filter-page', array( $this, 'awl_filter_page') );
			$doc_menu    = add_submenu_page( 'edit.php?post_type='.PFG_PLUGIN_SLUG, __( 'Docs', 'portfolio-filter-gallery' ), __( 'Docs', 'portfolio-filter-gallery' ), 'administrator', 'sr-doc-page', array( $this, 'pfg_doc_page') );
			$theme_menu    = add_submenu_page( 'edit.php?post_type='.PFG_PLUGIN_SLUG, __( 'Our Theme', 'portfolio-filter-gallery' ), __( 'Our Theme', 'portfolio-filter-gallery' ), 'administrator', 'sr-theme-page', array( $this, 'pfg_theme_page') );
		}
		
		public function Portfolio_Filter_Gallery() {
			$labels = array(
				'name'                => _x( 'Portfolio Filter Gallery', 'Post Type General Name', 'portfolio-filter-gallery' ),
				'singular_name'       => _x( 'Portfolio Filter Gallery', 'Post Type Singular Name', 'portfolio-filter-gallery' ),
				'menu_name'           => __( 'Portfolio Gallery', 'portfolio-filter-gallery' ),
				'name_admin_bar'      => __( 'Portfolio Filter', 'portfolio-filter-gallery' ),
				'parent_item_colon'   => __( 'Parent Item:', 'portfolio-filter-gallery' ),
				'all_items'           => __( 'All Gallery', 'portfolio-filter-gallery' ),
				'add_new_item'        => __( 'Add New Gallery', 'portfolio-filter-gallery' ),
				'add_new'             => __( 'Add New Gallery', 'portfolio-filter-gallery' ),
				'new_item'            => __( 'New Portfolio Filter Gallery', 'portfolio-filter-gallery' ),
				'edit_item'           => __( 'Edit Portfolio Filter Gallery', 'portfolio-filter-gallery' ),
				'update_item'         => __( 'Update Portfolio Filter Gallery', 'portfolio-filter-gallery' ),
				'search_items'        => __( 'Search Portfolio Filter Gallery', 'portfolio-filter-gallery' ),
				'not_found'           => __( 'Portfolio Filter Gallery Not found', 'portfolio-filter-gallery' ),
				'not_found_in_trash'  => __( 'Portfolio Filter Gallery Not found in Trash', 'portfolio-filter-gallery' ),
			);
			$args = array(
				'label'               => __( 'Portfolio Filter Gallery', 'portfolio-filter-gallery' ),
				'description'         => __( 'Custom Post Type For Portfolio Filter Gallery', 'portfolio-filter-gallery' ),
				'labels'              => $labels,
				'supports'            => array('title'),
				'taxonomies'          => array(),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 65,
				'menu_icon'           => 'dashicons-screenoptions',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
			);
			register_post_type( 'awl_filter_gallery', $args );
		} // end of post type function
		
		public function admin_add_meta_box() {
			add_meta_box( __('Add Portfolio Filter Gallery', 'portfolio-filter-gallery'), __('Add Portfolio Filter Gallery', 'portfolio-filter-gallery'), array(&$this, 'pfg_image_upload'), 'awl_filter_gallery', 'normal', 'default' );
			add_meta_box( __('Recommended Free Plugin from A WP Life', 'portfolio-filter-gallery'), __('Recommended Free Plugin from A WP Life', 'portfolio-filter-gallery'), array(&$this, 'pfg_rec_free_plugin'), 'awl_filter_gallery', 'normal', 'default' );
			add_meta_box( __('Upgrade Portfolio Gallery Pro', 'portfolio-filter-gallery'), __('Upgrade Portfolio Gallery Pro', 'portfolio-filter-gallery'), array(&$this, 'pfg_upgrade_pro'), 'awl_filter_gallery', 'side', 'default' );
			add_meta_box( __('Rate Our Plugin', 'portfolio-filter-gallery'), __('Rate Our Plugin', 'portfolio-filter-gallery'), array(&$this, 'pfg_rate_plugin'), 'awl_filter_gallery', 'side', 'default' );
			add_meta_box( __('pfg-shortcode', 'portfolio-filter-gallery'), __('Copy Shortcode', 'portfolio-filter-gallery'), array(&$this, 'PFG_Shortcode'), 'awl_filter_gallery', 'side', 'default' );
		}
		// meta upgrade pro
		public function pfg_upgrade_pro() { ?>
			<img src="<?php echo PFG_PLUGIN_URL ?>img/portfolio-upgrade.png"/ width="250" height="280">
			<a href="http://awplife.com/demo/portfolio-filter-gallery-premium/" target="_new" class="button button-primary" style="background: #496481; text-shadow: none;"><span class="dashicons dashicons-search" style="line-height:1.4;" ></span> <?php _e('Live Demo', 'portfolio-filter-gallery'); ?></a>
			<a href="http://awplife.com/account/signup/portfolio-filter-gallery" target="_new" class="button button-primary" style="background: #496481; text-shadow: none;"><span class="dashicons dashicons-unlock" style="line-height:1.4;" ></span> <?php _e('Upgrade To Pro', 'portfolio-filter-gallery'); ?></a>
		<?php }
		// meta rate us
		Public function pfg_rate_plugin() { ?>
		<div style="text-align:center">
			<p><?php _e('If you like our plugin then please', 'portfolio-filter-gallery'); ?> <b><?php _e('Rate us', 'portfolio-filter-gallery'); ?></b> <?php _e('on WordPress', 'portfolio-filter-gallery'); ?></p>
		</div>
		<div style="text-align:center">
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
		</div>
		<br>
		<div style="text-align:center">
			<a href="https://wordpress.org/support/plugin/portfolio-filter-gallery/reviews/?filter=5" target="_new" class="button button-primary button-large" style="background: #496481; text-shadow: none;"><span class="dashicons dashicons-heart" style="line-height:1.4;" ></span> <?php _e('Please Rate Us', 'portfolio-filter-gallery'); ?></a>
		</div>	
		<?php }
			
		public function pfg_image_upload($post) {
			wp_enqueue_script('jquery');
			wp_enqueue_script('awl-bootstrap-js', PFG_PLUGIN_URL . 'js/bootstrap.min.js');
			wp_enqueue_script('media-upload');
			wp_enqueue_script('awl-pfg-uploader.js', PFG_PLUGIN_URL . 'js/awl-pfg-uploader.js', array('jquery'));
			wp_enqueue_style('awl-pfg-uploader-css', PFG_PLUGIN_URL . 'css/awl-pfg-uploader.css');
			wp_enqueue_style('awl-bootstrap-css', PFG_PLUGIN_URL . 'css/bootstrap.min.css');
			wp_enqueue_script( 'awl-pfg-color-picker-js', plugins_url('js/pfg-color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
			wp_enqueue_media();
			wp_enqueue_style( 'wp-color-picker' );
			
			
			require_once('filter-gallery-settings.php');	
		}// end of upload multiple image
		
		public function PFG_Shortcode($post) { ?>
			<div class="pw-shortcode">
				<input type="text" name="shortcode" id="shortcode" value="<?php echo "[PFG id=".$post->ID."]"; ?>" readonly style="height: 60px; text-align: center; font-size: 20px; width: 100%; border: 2px dotted;">
				<p id="pw-copt-code"><?php _e('Shortcode copied to clipboard!', 'portfolio-filter-gallery'); ?></p>
				<p><?php _e('Copy & Embed shortcode into any Page/ Post / Text Widget to display your image gallery on site.', 'portfolio-filter-gallery'); ?><br></p>
			</div>
			<span onclick="copyToClipboard('#shortcode')" class="pw-copy dashicons dashicons-clipboard"></span>
			<style>
			.pw-copy {
				position: absolute;
				top: 9px;
				right: 24px;
				font-size: 26px;
				cursor: pointer;
			}
			</style>
			<script>
			jQuery( "#pw-copt-code" ).hide();
			function copyToClipboard(element) {
			  var $temp = jQuery("<input>");
			  jQuery("body").append($temp);
			  $temp.val(jQuery(element).val()).select();
			  document.execCommand("copy");
			  $temp.remove();
			  jQuery( "#shortcode" ).select();
			  jQuery( "#pw-copt-code" ).fadeIn();
			}
			</script>
			<?php
		}// end of gallery generation
		
		public function _pfg_ajax_callback_function($id) {
			//wp_get_attachment_image_src ( int $attachment_id, string|array $size = 'thumbnail', bool $icon = false );
			//thumb, thumbnail, medium, large, post-thumbnail
			$thumbnail = wp_get_attachment_image_src($id, 'medium', true);
			$attachment = get_post( $id ); // $id = attachment id
			$all_category = get_option('awl_portfolio_filter_gallery_categories');
			?>
			<li class="item image">
				<img class="new-image" src="<?php echo $thumbnail[0]; ?>" alt="<?php echo get_the_title($id); ?>" style="height: 150px; width: 98%; border-radius: 8px;">
				<input type="hidden" id="image-ids[]" name="image-ids[]" value="<?php echo $id; ?>" />
				
				<select id="slide-type[]" name="slide-type[]" class="form-control" style="width: 98% !important;" placeholder="Image Title" value="<?php echo $image_type; ?>" >
					<option value="image" <?php if($image_type == "image") echo "selected=selected"; ?>> <?php _e('Image', 'portfolio-filter-gallery'); ?> </option>
					<option value="video" <?php if($image_type == "video") echo "selected=selected"; ?>> <?php _e('Video', 'portfolio-filter-gallery'); ?> </option>
				</select>
				
				<input type="text" name="image-title[]" id="image-title[]" style="width: 98%;" placeholder="Image Title" value="<?php echo get_the_title($id); ?>">
				<textarea name="image-desc[]" id="image-desc[]" style="width: 98%; display:none;" placeholder="Type discription here.."></textarea>
				<input type="text" name="image-link[]" id="image-link[]" style="width: 98%;" placeholder="Video URL / Link URL">
				<?php
				if(isset($filters[$id])) {
					$selected_filters_array = $filters[$id];
				} else {
					$selected_filters_array = array();
				}
				?>
				<select class="pfg-filters form-control" name="filters[<?php echo $id; ?>][]" multiple="multiple" id="filters" style="width: 98%;">
					<?php
					foreach ($all_category as $key => $value) {
						if($key != 0) {
							?><strong><option value="<?php echo $key; ?>"><?php echo ucwords(esc_html($value)); ?></option></strong><?php
						}
					}
					?>
				</select>
				<?php foreach ($selected_filters_array as $key => $value) { ?>
				<input type="hidden" name="filter-image[<?php echo $value; ?>][]" id="filter-image[]" style="width: 98%;" value="<?php echo $id; ?>" >
				<?php } ?>
				<a class="pw-trash-icon" name="remove-image" id="remove-image" href="#"><span class="dashicons dashicons-trash"></span></a>
			</li>
			<?php
		}
		
		public function _ajax_pfg_gallery() {
			echo $this->_pfg_ajax_callback_function($_POST['PFGimageId']);
			die;
		}
		
		public function _pfg_save_settings($post_id) {
			if(isset($_POST['pfg_save_nonce'])) {
				if (!isset( $_POST['pfg_save_nonce'] ) || ! wp_verify_nonce( $_POST['pfg_save_nonce'], 'pfg_save_settings' ) ) {
				   print 'Sorry, your nonce did not verify.';
				   exit;
				
				} else {
				
					$gal_size 					= sanitize_text_field($_POST['gal_size']);
					$col_large_desktops			= sanitize_text_field($_POST['col_large_desktops']);
					$col_desktops 				= sanitize_text_field($_POST['col_desktops']);
					$col_tablets 				= sanitize_text_field($_POST['col_tablets']);
					$col_phones 				= sanitize_text_field($_POST['col_phones']);
					$gallery_direction 			= sanitize_text_field($_POST['gallery_direction']);
					$image_hover_effect_type	= sanitize_text_field($_POST['image_hover_effect_type']);
					$image_hover_effect_four	= sanitize_text_field($_POST['image_hover_effect_four']);
					$title_thumb				= sanitize_text_field($_POST['title_thumb']);
					$image_numbering			= sanitize_text_field($_POST['image_numbering']);
					$thumb_border				= sanitize_text_field($_POST['thumb_border']);
					$no_spacing					= sanitize_text_field($_POST['no_spacing']);
					$gray_scale					= sanitize_text_field($_POST['gray_scale']);
					$sort_by_title				= sanitize_text_field($_POST['sort_by_title']);
					$url_target					= sanitize_text_field($_POST['url_target']);
					$filter_bg					= sanitize_text_field($_POST['filter_bg']);
					$filter_title_color			= sanitize_text_field($_POST['filter_title_color']);
					$light_box					= sanitize_text_field($_POST['light-box']);
					$hide_filters				= sanitize_text_field($_POST['hide_filters']);
					$all_txt					= sanitize_text_field($_POST['all_txt']);
					$sort_filter_order			= sanitize_text_field($_POST['sort_filter_order']);
					$filter_position			= sanitize_text_field($_POST['filter_position']);
					$search_box					= sanitize_text_field($_POST['search_box']);
					$search_txt					= sanitize_text_field($_POST['search_txt']);
					$bootstrap_disable			= sanitize_text_field($_POST['bootstrap_disable']);
					if(isset($_POST['custom-css'] )) {
						$custom_css = $_POST['custom-css'];
					} else {
						$custom_css = '';
					}
					
					$i = 0;
					$image_ids = array();
					$image_titles = array();
					$image_type = array();
					$image_desc = array();
					$image_link = array();
					$filters_new = array();
					$filter_image = array();
					 
					$image_ids_val = isset( $_POST['image-ids'] ) ? (array) $_POST['image-ids'] : array();
					$image_ids_val = array_map( 'sanitize_text_field', $image_ids_val );
					
					$filters = isset( $_POST['filters'] ) ? (array) $_POST['filters'] : array();

					foreach($image_ids_val as $image_id) {
						
						$image_ids[]				= sanitize_text_field($_POST['image-ids'][$i]);
						$image_titles[]				= sanitize_text_field($_POST['image-title'][$i]);
						$image_type[]				= sanitize_text_field($_POST['slide-type'][$i]);
						$image_desc[]				= sanitize_text_field($_POST['image-desc'][$i]);
						$image_link[]				= sanitize_text_field($_POST['image-link'][$i]);
						
						if(isset($filters[$image_id])) {
							$filters_new[$image_id] = array_map( 'sanitize_text_field', $filters[$image_id]);
						}
						$single_image_update = array(
							'ID'           => $image_id,
							'post_title'   => $image_titles[$i],
						);
						
						wp_update_post( $single_image_update );
						$i++;
					}
				
					$portfolio_post_setting = array (
						'image-ids'  						=> $image_ids,
						'image_title'  						=> $image_titles,
						'slide-type'  						=> $image_type,
						'image_desc'  						=> $image_desc,
						'image-link'  						=> $image_link,
						'filters'  							=> $filters_new,
						'filter-image'  					=> $filter_image,
						'gal_size'							=> $gal_size,
						'col_large_desktops'				=> $col_large_desktops,
						'col_desktops' 			   			=> $col_desktops,
						'col_tablets'						=> $col_tablets,
						'col_phones' 						=> $col_phones,
						'gallery_direction' 				=> $gallery_direction,
						'image_hover_effect_type'			=> $image_hover_effect_type,
						'image_hover_effect_four'			=> $image_hover_effect_four,
						'title_thumb'						=> $title_thumb,
						'image_numbering'					=> $image_numbering,
						'thumb_border'						=> $thumb_border,
						'no_spacing'						=> $no_spacing,
						'gray_scale'						=> $gray_scale,
						'sort_by_title'						=> $sort_by_title,
						'url_target'						=> $url_target,
						'filter_bg'							=> $filter_bg,
						'filter_title_color'				=> $filter_title_color,
						'light-box'							=> $light_box,
						'hide_filters'						=> $hide_filters,
						'all_txt'							=> $all_txt,
						'sort_filter_order'					=> $sort_filter_order,
						'filter_position'					=> $filter_position,
						'search_box'						=> $search_box,
						'search_txt'						=> $search_txt,
						'bootstrap_disable'					=> $bootstrap_disable,
						'custom-css'						=> $custom_css,
						
					);		
					$awl_portfolio_shortcode_setting = "awl_filter_gallery".$post_id;
					update_post_meta($post_id, $awl_portfolio_shortcode_setting, $portfolio_post_setting);
					
				}
				
			
			}
		}// end save setting
		
		//filter/category page
		public function awl_filter_page() {
			require_once('filters.php');
		}
		
		//filter/category page
		public function update_old_settings_page() {
			require_once('update-old-settings.php');
		}
		
		//Doc page
		public function pfg_doc_page() {
			require_once('docs.php');
		}
			
		public function pfg_rec_free_plugin() {
			require_once('featured-plugins/featured-plugins.php');
		}
		
		public function pfg_theme_page() {
			require_once('our-theme/awp-theme.php');
		}
		
		
		
	}
	$pfg_portfolio_gallery_object = new Awl_Portfolio_Filter_Gallery();		
	require_once('filter-gallery-shortcode.php');
	
}
?>