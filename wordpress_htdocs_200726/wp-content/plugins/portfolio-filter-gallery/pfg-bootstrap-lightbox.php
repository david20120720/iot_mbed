<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Blue Imp Light Box Load File
 */
wp_enqueue_style('awl-bootstrap-lightbox-css', PFG_PLUGIN_URL .'lightbox/bootstrap/css/ekko-lightbox.css');
wp_enqueue_script('awl-bootstrap-lightbox-js', PFG_PLUGIN_URL .'lightbox/bootstrap/js/ekko-lightbox.js', array('jquery'), '' , true);

$allimages = array(  'p' => $pf_gallery_id, 'post_type' => 'awl_filter_gallery', 'orderby' => 'ASC');
$loop = new WP_Query( $allimages );
while ( $loop->have_posts() ) : $loop->the_post();
	$post_id = get_the_ID();
	$all_category = get_option('awl_portfolio_filter_gallery_categories');

	// collect all selected filters assigned on images
	$all_selected_filters = array();
	foreach ($filters as $filters_key => $filters_value) {
		if(is_array($filters_value)) {
			$all_selected_filters = array_merge($all_selected_filters, $filters_value);
		}
	} ?>
	<div class="portfolio-wraper">
		<?php 
		if($hide_filters == 0 ) { 
		
		if($filter_position == 'center') {
		$filter_align = 'text-center';
		}
		if($filter_position == 'right') {
			$filter_align = 'text-right';
		}
		if($filter_position == 'left') {
			$filter_align = '';
		}
		?>
		<div class="col-lg-12 col-md-12 col-xs-12 <?php echo $filter_align ?>">
				<ul id="all-simplefilter" class="filtr-control-<?php echo $pf_gallery_id; ?> simplefilter simplefilter_<?php echo $pf_gallery_id; ?>">
					<li id="all" class="active filtr-controls-<?php echo $pf_gallery_id; ?>" data-filter="all"><?php _e($all_txt, 'portfolio-filter-gallery'); ?></li>
				</ul>
				<ul id="f-simplefilter" class="filtr-control-<?php echo $pf_gallery_id; ?> simplefilter simplefilter_<?php echo $pf_gallery_id; ?>">
					<?php
					if(is_array($all_selected_filters) && count($all_selected_filters)) {
						$all_selected_filters = array_unique($all_selected_filters ); // remove same key
						foreach ($all_selected_filters as $filter_key) {
							?>
							<li data-filter="<?php echo $filter_key ?>" class="filtr-controls-<?php echo $pf_gallery_id; ?>"><?php  _e($all_category[$filter_key], 'portfolio-filter-gallery'); ?></li>
							<?php
						}
					} ?>
				</ul>
			</div>
		<?php } ?>
		
		<!-- Shuffle & Sort Controls -->
		<?php if($search_box) { ?>
		<div class="col-md-12 filter-wrap <?php echo $filter_align ?>">
			<input type="text" class="filtr-controls-<?php echo $pf_gallery_id; ?> filtr-search filtr_search_<?php echo $pf_gallery_id; ?>" name="filtr-search" placeholder="<?php if($search_txt) echo ucwords($search_txt); else echo 'Search Images'; ?>" data-search></li>
		</div>
		<?php } ?>
		
		<div class="row loading-wrapper text-center">
			<img src="<?php echo PFG_PLUGIN_URL ?>/img/loading-icon.gif" width="60">
		</div>
		<div class="filter_gallery_<?php echo $pf_gallery_id; ?> row filtr-container filters-div" style="opacity:0">
			<?php
			
			if(isset($pf_gallery_settings['image-ids']) && count($pf_gallery_settings['image-ids']) > 0) {
				$count = 0;
				if($thumbnail_order == "DESC") {
					$pf_gallery_settings['image-ids'] = array_reverse($pf_gallery_settings['image-ids']);
				}
				if($thumbnail_order == "RANDOM") {
					shuffle($pf_gallery_settings['image-ids']);
				}			
				$no = 1;
				foreach($pf_gallery_settings['image-ids'] as $attachment_id) {
					//$attachment_id;
					$image_link_url =  $pf_gallery_settings['image-link'][$count];
					$image_type = $pf_gallery_settings['slide-type'][$count];
					
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
					
					//set thumbnail size
					if($gal_thumb_size == "thumbnail") { $thumbnail_url = $thumbnail[0]; }
					if($gal_thumb_size == "medium") { $thumbnail_url = $medium[0]; }
					if($gal_thumb_size == "large") { $thumbnail_url = $large[0]; }
					if($gal_thumb_size == "full") { $thumbnail_url = $full[0]; }
					
					// seach attachment id in to $filters and get all filter ids
					//$pfg_filters = $pf_gallery_settings['filters'];
					foreach ($filters as $pfg_filters_key => $pfg_filters_values) {
					}
					if (array_key_exists($attachment_id, $filters)) {
						$filter_key_array = $filters[$attachment_id];
						$prefix = $filter_keys = '';
						
						if(count($filter_key_array) > 1) {
							foreach ($filter_key_array as $filter_key => $filter_value) {
								$filter_keys .= $prefix . $filter_value;
								$prefix = ', ';
							}
						} else {
							$filter_keys = $filter_key_array[0];						
						}
					}
					if(!isset($filter_keys)) {
						$filter_keys = 1;
					} if($image_link_url) { 
						if($image_type == 'image') { 
							?>
							<a href="<?php echo esc_url($image_link_url); ?>" title="<?php echo esc_html($title); ?>" target="<?php echo esc_html($url_target); ?>">
								<div data-category="<?php echo esc_html($filter_keys); ?>" data-sort="<?php echo esc_html($title); ?>" class="animateonload filtr-item filtr_item_<?php echo esc_html($pf_gallery_id); ?> single_one <?php echo esc_html($col_large_desktops); ?> <?php echo esc_html($col_desktops); ?> <?php echo esc_html($col_tablets); ?> <?php echo esc_html($col_phones); ?>">
									<img class="<?php if($thumb_border == "yes") { ?> thumbnail <?php } ?> thumbnail_<?php echo esc_html($pf_gallery_id); ?> pfg-img pfg_img_<?php echo esc_html($pf_gallery_id); ?> img-responsive <?php echo esc_html($image_hover_effect); ?>" src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ); ?>">
									<?php if($image_numbering) { ?>
										<div class="item-position item_position_<?php echo esc_html($pf_gallery_id); ?>"><?php echo esc_html($no); ?></div>
									<?php } ?>
									<?php if($title_thumb == "show") {?>
									<span class="item-desc item_desc_<?php echo esc_html($pf_gallery_id); ?>"><?php esc_html_e($title, 'portfolio-filter-gallery'); ?></span>
									<?php } ?>
								</div>
							</a>
							<?php 
						}// image with link
						if($image_type == 'video') {
							?>
							<a class="video-box" href="<?php echo esc_url($image_link_url); ?>" title="<?php echo esc_html($title); ?>" target="<?php echo esc_html($url_target); ?>">
								<div data-category="<?php echo esc_html($filter_keys); ?>" data-sort="<?php echo esc_html($title); ?>" class="filtr-item filtr_item_<?php echo esc_html($pf_gallery_id); ?> single_one <?php echo esc_html($col_large_desktops); ?> <?php echo $col_desktops; ?> <?php echo esc_html($col_tablets); ?> <?php echo esc_html($col_phones); ?>">
									<span class="snipv12">
										<img class="<?php if($thumb_border == "yes") { ?> thumbnail <?php } ?> thumbnail_<?php echo esc_html($pf_gallery_id); ?> pfg-img pfg_img_<?php echo esc_html($pf_gallery_id); ?> img-responsive <?php echo esc_html($image_hover_effect); ?>" src="<?php echo esc_html($thumbnail_url); ?>" alt="<?php echo get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ); ?>">
										<?php if (!strpos($image_link_url, 'vimeo')) { 
										?>
										<i class=""><img src="<?php echo PFG_PLUGIN_URL ?>/img/p-youtube.png"></i>
										<?php
										} else {
										?>
										<i class="fa fa-youtube-play"></i>
										<?php
										} 
										?>
									</span>
								</div>
							</a>
							<?php 	
						}  // image with video
					
					} else { ?>
						<a href="<?php echo $full[0]; ?>" data-toggle="lightbox" data-gallery="multiimages" data-title="<?php esc_html_e($title, 'portfolio-filter-gallery'); ?>">
							<div data-category="<?php echo esc_html($filter_keys); ?>" data-sort="<?php echo  esc_html($title); ?>" class="animateonload filtr-item filtr_item_<?php echo  esc_html($pf_gallery_id); ?> single_one <?php echo  esc_html($col_large_desktops); ?> <?php echo $col_desktops; ?> <?php echo  esc_html($col_tablets); ?> <?php echo  esc_html($col_phones); ?>">
								<img class="<?php if($thumb_border == "yes") { ?> thumbnail <?php } ?> thumbnail_<?php echo  esc_html($pf_gallery_id); ?> pfg-img pfg_img_<?php echo  esc_html($pf_gallery_id); ?> img-responsive <?php echo  esc_html($image_hover_effect); ?>" src="<?php echo  esc_url($thumbnail_url); ?>" alt="<?php echo get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ); ?>">
								<?php if($image_numbering) { ?>
									<div class="item-position item_position_<?php echo esc_html($pf_gallery_id); ?>"><?php echo esc_html($no); ?></div>
								<?php } ?>
								<?php if($title_thumb == "show") {?>
								<span class="item-desc item_desc_<?php echo esc_html($pf_gallery_id); ?>"><?php esc_html_e($title, 'portfolio-filter-gallery'); ?></span>
								<?php } ?>
							</div>
						</a>
						
					<?php } 
					$no++;
					$count++;
					
				}// end of attachment foreach
			} else {
				_e('Sorry! No image gallery found ', 'portfolio-filter-gallery');
				echo ":[PFG id=$post_id]";
			} // end of if esle of images avaialble check into imager ?>
		</div>
	</div>

<?php
endwhile;
wp_reset_query(); ?>
<script>
jQuery('#filter_gallery_<?php echo $pf_gallery_id; ?>').hide();
jQuery('.loading-wrapper').show();
jQuery( window ).load(function() {
	jQuery('.filtr-item').addClass('animateonload');
	jQuery('#filter_gallery_<?php echo $pf_gallery_id; ?>').show();
	jQuery('.loading-wrapper').hide();
	
	jQuery(".loader_img").hide();
	jQuery(".lg_load_more").show();
	jQuery(".filtr-container").css("opacity", 1);
	//Filterizd Default options
	options = {
		animationDuration: 0.5,
		callbacks: {
			onFilteringStart: function() { },
			onFilteringEnd: function() { },
			onShufflingStart: function() { },
			onShufflingEnd: function() { },
			onSortingStart: function() { },
			onSortingEnd: function() { }
		},
		controlsSelector: '.filtr-controls-<?php echo $pf_gallery_id; ?>',
		filter: 'all',
		 filterOutCss: {
		  top:'0px',
			left:'0px',
			opacity: 0.001,
			transform: ''
		  },
		  filterInCss: {
			  top:'0px',
			left:'0px',
			opacity: 1,
			transform: ''
		  },
		layout: 'sameWidth',
		selector: '.filtr-item',
		setupControls: false
	}
	var filterizd = jQuery('.filter_gallery_<?php echo $pf_gallery_id; ?>').filterizr(options);
	//filterizd.filterizr('sort', 'domIndex', 'desc');
	
	<?php 
	if ( $sort_by_title == "asc" ) { ?>
		// Sort by title
		filterizd.filterizr('sort', 'sortData', 'asc');
		<?php
	} if ( $sort_by_title == "desc" ) { ?>
		// Sort by decending order
		filterizd.filterizr('sort', 'sortData', 'desc');
	<?php
	} 
	if ( $hide_filters == 0 ) {
		if ( $sort_filter_order == 1 ) { ?>
			/* Sort li to alphabetically: */
			jQuery(function(){
			  var list, i, switching, b, shouldSwitch;
			  list = document.getElementById("f-simplefilter");
			  switching = true;
			  /* Make a loop that will continue until
			  no switching has been done: */
			  while (switching) {
				// start by saying: no switching is done:
				switching = false;
				b = list.getElementsByTagName("LI");
				// Loop through all list-items:
				for (i = 0; i < (b.length - 1); i++) {
				  // start by saying there should be no switching:
				  shouldSwitch = false;
				  /* check if the next item should
				  switch place with the current item: */
				  if (b[i].innerHTML.toLowerCase() > b[i + 1].innerHTML.toLowerCase()) {
					/* if next item is alphabetically
					lower than current item, mark as a switch
					and break the loop: */
					shouldSwitch = true;
					break;
				  }
				}
				if (shouldSwitch) {
				  /* If a switch has been marked, make the switch
				  and mark the switch as done: */
				  b[i].parentNode.insertBefore(b[i + 1], b[i]);
				  switching = true;
				}
			  }
			});
		/* Sort li to alphabetically: */
		<?php 
		} 
	} ?>
	
	//bootstrap-lightbox-js
	jQuery(document).ready(function (jQuery) {
		// delegate calls to data-toggle="lightbox"
		jQuery(document).delegate('*[data-toggle="lightbox"]:not([data-gallery="navigateTo"])', 'click', function(event) {
			event.preventDefault();
			return jQuery(this).ekkoLightbox({
				onShown: function() {
					/* if (window.console) {
						return console.log('Checking our the events huh?');
					} */
				},
				onNavigate: function(direction, itemIndex) {
					if (window.console) {
						return console.log('Navigating '+direction+'. Current item: '+itemIndex);
					}
				}
			});
		});

		//Programatically call
		jQuery('#open-image').click(function (e) {
			e.preventDefault();
			jQuery(this).ekkoLightbox();
		});
		jQuery('#open-youtube').click(function (e) {
			e.preventDefault();
			jQuery(this).ekkoLightbox();
		});

		// navigateTo
		jQuery(document).delegate('*[data-gallery="navigateTo"]', 'click', function(event) {
			event.preventDefault();
			var lb;
			return jQuery(this).ekkoLightbox({
				onShown: function() {
					lb = this;
					jQuery(lb.modal_content).on('click', '.modal-footer a', function(e) {
						e.preventDefault();
						lb.navigateTo(2);
					});
				}
			});
		});
	});

	// video player
	jQuery(function(){
      jQuery("a.video-box").YouTubePopUp( { autoplay: 0 } ); // Disable autoplay
	});
});   
</script>