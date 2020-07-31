<?php
/**
 * Bootstrap Light Box Load File
 */

wp_enqueue_style('awl-bootstrap-lightbox-css', IG_PLUGIN_URL .'include/lightbox/bootstrap/css/ekko-lightbox.css');
wp_enqueue_script('awl-bootstrap-lightbox-js', IG_PLUGIN_URL .'include/lightbox/bootstrap/js/ekko-lightbox.js');
 
$allslides = array(  'p' => $image_gallery_id, 'post_type' => 'image_gallery', 'orderby' => 'ASC');
$loop = new WP_Query( $allslides );
while ( $loop->have_posts() ) : $loop->the_post();

	$post_id = get_the_ID();
	$gallery_settings = unserialize(base64_decode(get_post_meta( $post_id, 'awl_ig_settings_'.$post_id, true)));
	count($gallery_settings['slide-ids']);
	// start the image gallery contents
	?>
	<div id="image_gallery_<?php echo $image_gallery_id; ?>" class="row all-images-<?php echo $image_gallery_id; ?>">
		<?php
		if(isset($gallery_settings['slide-ids']) && count($gallery_settings['slide-ids']) > 0) {
			if($thumbnail_order == "DESC") {
				$gallery_settings['slide-ids'] = array_reverse($gallery_settings['slide-ids']);
			}
			if($thumbnail_order == "RANDOM") {
				shuffle($gallery_settings['slide-ids']);
			}
			
			foreach($gallery_settings['slide-ids'] as $attachment_id) {
				$thumb = wp_get_attachment_image_src($attachment_id, 'thumb', true);
				$thumbnail = wp_get_attachment_image_src($attachment_id, 'thumbnail', true);
				$medium = wp_get_attachment_image_src($attachment_id, 'medium', true);
				$large = wp_get_attachment_image_src($attachment_id, 'large', true);
				$full = wp_get_attachment_image_src($attachment_id, 'full', true);
				$postthumbnail = wp_get_attachment_image_src($attachment_id, 'post-thumbnail', true);
				$attachment_details = get_post( $attachment_id );
				$href = get_permalink( $attachment_details->ID );
				$src = $attachment_details->guid;
				$title = $attachment_details->post_title;
				$description = $attachment_details->post_content;
				if(isset($slidetext) == 'true') {
					if($slidetextopt == 'title') $text = $title;
				} else {
					$text = $title;
				}
				
				//set thumbnail size
				if($gal_thumb_size == "thumbnail") { $thumbnail_url = $thumbnail[0]; }
				if($gal_thumb_size == "medium") { $thumbnail_url = $medium[0]; }
				if($gal_thumb_size == "large") { $thumbnail_url = $large[0]; }
				if($gal_thumb_size == "full") { $thumbnail_url = $full[0]; }
					?>
					<div class="single-image-<?php echo $image_gallery_id; ?> <?php echo $col_large_desktops; ?> <?php echo $col_desktops; ?> <?php echo $col_tablets; ?> <?php echo $col_phones; ?>">
						<a href="<?php echo $full[0]; ?>" data-toggle="lightbox-<?php echo $image_gallery_id; ?>" data-gallery="gallery-<?php echo $image_gallery_id; ?>" data-title="<?php echo $title; ?>">
							<img class="thumbnail <?php echo $image_hover_effect; ?>" src="<?php echo $thumbnail_url; ?>" alt="<?php echo $title; ?>">
							<?php if($img_title == 0) { ?>
							<span class="item-title"><?php echo $title; ?></span>
							<?php } ?>
						</a>
					</div>
					<?php
			}// end of attachment foreach
		} else {
			_e('Sorry! No image gallery found.', IGP_TXTDM);
			echo ": [IMG-Gal id=$post_id]";
		} // end of if else of images available check into gallery
		?>
	</div>
<?php
endwhile;
wp_reset_query();
?>
<script>
jQuery(document).ready(function () {
	// Method 1 - Initialize Isotope, then trigger layout after each image loads.
	var $grid = jQuery('.all-images-<?php echo $image_gallery_id; ?>').isotope({
		// options...
		itemSelector: '.single-image-<?php echo $image_gallery_id; ?>',
	});
	// layout Isotope after each image loads
	$grid.imagesLoaded().progress( function() {
		$grid.isotope('layout');
	});
	
	/* 
	//Method 2 - initialize Isotope after all images have been loaded
	var $grid = jQuery('.all-images-<?php echo $image_gallery_id; ?>').imagesLoaded( function() {
		// init Isotope after all images have loaded
		$grid.isotope({
			// options...
			itemSelector: '.single-image-<?php echo $image_gallery_id; ?>',
		});
	});*/
});
jQuery(document).ready(function (jQuery) {
	jQuery(document).on('click', '[data-toggle="lightbox-<?php echo $image_gallery_id; ?>"]', function(event) {
		event.preventDefault();
		jQuery(this).ekkoLightbox();
	});
});
</script>