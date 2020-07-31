	<?php
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	// hide the update setting notice once users updated (1 = process not done, 2 = process done)
	$old_setting_update_status = get_option('pfg_old_settings_updated', 1);
	if($old_setting_update_status == 1 && !isset($_POST['update_settings_action'])){
	?>
	<div style="text-align:center; border: solid 2px red; padding: 50px; margin:20px;">
		<h1 style="color:red;"><strong>!!! IMPORTANT NOTICE !!!</strong></h1>
		<h1 style="color:blue;">Update Plugin Settings from Old Version to Latest Version</h1>
		<p>Due to security reasons, we have updated the plugin settings code according to WordPress guidelines.</p>
		<p>Users those using the plugin version 1.0.7 or Old once, need to hit the below button to update previously saved setting according to the latest plugin.</p>
		<p>It will prevent you to lose your previously created galleries and settings.</p>
		<p>This process will be run one time to update all your galleries setting and recover them accordingly to the new plugin code.</p>
		<h2 style="color:red;"><strong>If you have already updated the setting please ignore this process.<strong></h2>
		<form action="" method="post">
			<input type="hidden" value="pfg-update" name="update_settings_action" id="update_settings_action">
			<input type="submit" value="Update Plugin Settings" name="update_settings" id="update_settings" class="button button-primary button-hero">
			<?php wp_nonce_field( 'pfg_update_old_settings', 'pfg_update_old_settings_nonce' ); ?>
		</form>
		<h3>OR</h3>
		<p><strong>If you are a new user and using plugin version 1.1.1 or the latest, then hit the below button to hide the notice.</p>
		<form action="" method="post">
			<input type="hidden" value="pfg-hide-notice" name="pfg_hide_notice" id="pfg_hide_notice">
			<input type="submit" value="I Am New User Hide This Notice For Me" name="hide_notice" id="hide_notice" class="button button-primary button-hero">
			<?php wp_nonce_field( 'pfg_hide_notice', 'pfg_hide_notice_nonce' ); ?>
		</form>
		
	</div>
	
	<?php 
	} else {
		echo '<h1 style="color:blue;">Thanks! You have already updated all old gallery settings for the latest plugin version.</h1>';
	}
	
	if(isset($_POST['pfg_hide_notice'])) {
		if (!isset( $_POST['pfg_hide_notice_nonce'] ) || ! wp_verify_nonce( $_POST['pfg_hide_notice_nonce'], 'pfg_hide_notice' ) ) {
		   print 'Sorry, your nonce did not verify.';
		   exit;
		} else {
			// hide notice ((1 = show, 2 = hide))
			update_option('pfg_hide_notice', 2);
		}
	}
	if(isset($_POST['update_settings_action'])) {
		
		if (!isset( $_POST['pfg_update_old_settings_nonce'] ) || ! wp_verify_nonce( $_POST['pfg_update_old_settings_nonce'], 'pfg_update_old_settings' ) ) {
		   print 'Sorry, your nonce did not verify.';
		   exit;
		} else {
			//echo "nonce verified<br />";
			
			// load old plugin settings
			$pfg_args = array('post_type' => 'awl_filter_gallery');
			global $pfg_galleries;
			$pfg_galleries = new WP_Query( $pfg_args );
			//echo "1<br />";
			while ( $pfg_galleries->have_posts() ) : $pfg_galleries->the_post();
				//echo "2 in while <br />";
				
				$gallery_id = get_the_ID();
				//echo $gallery_id."<br />";
				
				$pf_gallery_settings = unserialize(base64_decode(get_post_meta( $gallery_id, 'awl_filter_gallery'.$gallery_id, true)));
				
				//echo "<pre>";
				//echo "Old Setting<hr />";
				//print_r($pf_gallery_settings);
				//echo "</pre>";
				
				$gal_size 					= sanitize_text_field($pf_gallery_settings['gal_size']);
				$col_large_desktops			= sanitize_text_field($pf_gallery_settings['col_large_desktops']);
				$col_desktops 				= sanitize_text_field($pf_gallery_settings['col_desktops']);
				$col_tablets 				= sanitize_text_field($pf_gallery_settings['col_tablets']);
				$col_phones 				= sanitize_text_field($pf_gallery_settings['col_phones']);
				$image_hover_effect_four	= sanitize_text_field($pf_gallery_settings['image_hover_effect_four']);
				$title_thumb				= sanitize_text_field($pf_gallery_settings['title_thumb']);
				$image_numbering			= sanitize_text_field($pf_gallery_settings['image_numbering']);
				$no_spacing					= sanitize_text_field($pf_gallery_settings['no_spacing']);
				$gray_scale					= sanitize_text_field($pf_gallery_settings['gray_scale']);
				$url_target					= sanitize_text_field($pf_gallery_settings['url_target']);
				$filter_bg					= sanitize_text_field($pf_gallery_settings['filter_bg']);
				$filter_title_color			= sanitize_text_field($pf_gallery_settings['filter_title_color']);
				$light_box					= sanitize_text_field($pf_gallery_settings['light-box']);
				if(isset($pf_gallery_settings['custom-css'] )) {
						$custom_css = $pf_gallery_settings['custom-css'];
					} else {
						$custom_css = '';
					}
				$i = 0;
				$image_ids_val = $pf_gallery_settings['image-ids'];
				$filters = array();
				$filter_image = array();
				foreach($image_ids_val as $image_id) {
					$image_ids[]	= sanitize_text_field($pf_gallery_settings['image-ids'][$i]);
					$image_titles[]	= sanitize_text_field($pf_gallery_settings['image-title'][$i]);
					$image_type[]	= sanitize_text_field($pf_gallery_settings['slide-type'][$image_id]);
					$image_desc[]	= sanitize_text_field($pf_gallery_settings['image-desc'][$i]);
					$image_link[]	= sanitize_text_field($pf_gallery_settings['image-link'][$i]);
					if(isset($pf_gallery_settings['filters'])) {
						$filters = $pf_gallery_settings['filters']; //array
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
					'filters'  							=> $filters,
					'filter-image'  					=> $filter_image,
					'gal_size'							=> $gal_size,
					'col_large_desktops'				=> $col_large_desktops,
					'col_desktops' 			   			=> $col_desktops,
					'col_tablets'						=> $col_tablets,
					'col_phones' 						=> $col_phones,
					'image_hover_effect_four'			=> $image_hover_effect_four,
					'title_thumb'						=> $title_thumb,
					'image_numbering'					=> $image_numbering,
					'no_spacing'						=> $no_spacing,
					'gray_scale'						=> $gray_scale,
					'url_target'						=> $url_target,
					'filter_bg'							=> $filter_bg,
					'filter_title_color'				=> $filter_title_color,
					'light-box'							=> $light_box,
					'custom-css'						=> $custom_css,
				);
				$awl_portfolio_shortcode_setting = "awl_filter_gallery".$gallery_id;
				
				//echo "<pre>";
				//echo "New Setting<hr />";
				//print_r($portfolio_post_setting);
				//echo "</pre>";
				
				update_post_meta($gallery_id, $awl_portfolio_shortcode_setting, $portfolio_post_setting);
				
				// add a update flag to run this process one time
				update_option('pfg_old_settings_updated', 2);
				
				//echo "3 end of while <br />";
				
				//unset
				unset($pf_gallery_settings);
				unset($image_ids_val);
				unset($image_ids);
				unset($image_titles);
				unset($image_type);
				unset($image_desc);
				unset($image_link);
				unset($filters);
				unset($filter_image);
				unset($single_image_update);
				unset($portfolio_post_setting);
			endwhile;
		}
	}
?>