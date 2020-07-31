<?php
$ver = '1.5';

$base_url_host = parse_url(site_url(), PHP_URL_HOST);
$iSlide        = $settings['initial_slide'] = isset($_GET['flag' . $galleryID . '_slide'])? (int) $_GET['flag' . $galleryID . '_slide'] : 0;
$settings['gallID'] = $galleryID;
unset($settings['customCSS']);

wp_enqueue_style('flagallery-photomania-skin', plugins_url('/css/photomania.css', __FILE__), array('swiper'), $ver);
wp_enqueue_script('flagallery-photomania-skin', plugins_url('/js/photomania.js', __FILE__), array('jquery', 'swiper', 'mousetrap'), $ver, true);

$content = array(
        'data'  => array()
);
foreach ( $data as $gallery ) {
    $path  = $gallery['gallery']['path'];
    $items = $gallery['data'];
    foreach($items as $item){
        $image = $path . '/webview/' . $item['filename'];
        $thumb = $path . '/thumbs/thumbs_' . $item['filename'];
        $description = str_replace(array("\r\n", "\r", "\n"), '', wpautop(stripslashes($item['description'])));
        $title = $alttext = stripslashes($item['alttext']);
        $download_link = $path . '/' . $item['filename'];

        $meta_data = $item['meta_data'];

        $meta     = array_merge($meta_data, array(
            'views'  => $item['hitcounter'],
            'likes'  => $item['total_votes']
        ));

        $content['data'][ $item['pid'] ] = array(
            'id'     => $item['pid'],
            'image'  => $image,
            'thumb'  => $thumb,
            'file'   => $item['filename'],
            'meta'   => $meta,
            'title'  => $title
        );

        if( !empty($settings['show_description'])){
            $content['data'][ $item['pid'] ]['description'] = $description;
        }

        if( !empty($settings['show_link_button'])){
            $content['data'][ $item['pid'] ]['link'] = $item['link'];
            $link_target                          = '';
            if($item['link']){
                $url_host = parse_url($item['link'], PHP_URL_HOST);
                if($url_host == $base_url_host || empty($url_host)){
                    $link_target = '_self';
                } else{
                    $link_target = '_blank';
                }
            }
            $content['data'][ $item['pid'] ]['link_target'] = $link_target;
        }

        if( !empty($settings['show_download_button'])){
            $content['data'][ $item['pid'] ]['download'] = $download_link;
        }
    }
}

if ( empty( $content['data'] ) ) {
    return;
}

$slides        = array();
$slides_thumbs = array();
$i             = 0;
foreach ( $content['data'] as $item_id => $item ) {
    $i ++;
    if ( ! empty( $item['meta']['webview'][0] ) && ! empty( $item['meta']['webview'][1] ) ) {
        $ratio = $item['meta']['webview'][0] / $item['meta']['webview'][1];
    } else {
        $ratio = 1.5;
    }
    $content['data'][ $item_id ]['ratio'] = $ratio;
    if ( 1 <= $ratio ) {
        $orientation = 'flagpm_photo_landscape';
    } else {
        $orientation = 'flagpm_photo_portrait';
    }
    $img_src   = '';
    $thumb_src = '';
    if ( 1 === $i ) {
        $img_src   = 'src="' . $item['image'] . '"';
        $thumb_src = 'src="' . $item['thumb'] . '"';
    }
    $img_class     = '';
    $img_preloader = '';
    $img_class .= ' swiper-lazy';
    $img_preloader = '<div class="swiper-lazy-preloader swiper-lazy-preloader-black"></div>';
    $slides[]        = '
		<div class="swiper-slide" data-hash="flagallery' . $item['id'] . '" data-photo-id="' . $item['id'] . '"><span class="flagpm_va"></span>' . '<img ' . $img_src . ' data-src="' . $item['image'] . '" alt="' . esc_attr( $item['title'] ) . '" class="flagpm_the_photo' . $img_class . '">' . $img_preloader . '</div>';
    $slides_thumbs[] = '
		<div class="swiper-slide flagpm_photo" data-photo-id="' . $item['id'] . '">' . '<img ' . $thumb_src . ' data-src="' . $item['thumb'] . '" alt="' . esc_attr( $item['title'] ) . '" class="flagpm_photo swiper-lazy ' . $orientation . '">' . '<span class="swiper-lazy-preloader swiper-lazy-preloader-black"></span>' . '</div>';
}
$content['data'] = array_values( $content['data'] );

$photo_show_class = '';
if ( ! empty( $settings['gallery_maximized'] ) ) {
    $photo_show_class .= ' flagpm_maximized';
}
if ( ! empty( $settings['gallery_focus'] ) ) {
    $photo_show_class .= ' flagpm_focus';
}
if ( ! empty( $settings['gallery_focus_maximized'] ) ) {
    $photo_show_class .= ' flagpm_focus_maximized';
}
if ( empty( $settings['keyboard_help'] ) ) {
    $photo_show_class .= ' flagpm_diskeys';
}
$photo_show_class .= ' flagpm_preload';
?>

    <div class="flagpm_photo_show flagpm_w960 flagpm_w640 flagpm_w480<?php echo $photo_show_class; ?>">

        <div class="flagpm_photo_wrap has_prev_photo has_next_photo">
            <div class="swiper-container swiper-big-images">
                <div class="flagpm_photo_arrow_next flagpm_photo_arrow flagpm_next">
                    <div title="Next" class="flagpm_arrow"></div>
                </div>
                <div class="flagpm_photo_arrow_previous flagpm_photo_arrow flagpm_prev">
                    <div title="Previous" class="flagpm_arrow"></div>
                </div>
                <div class="swiper-wrapper">
                    <?php
                    echo implode( '', $slides );
                    ?>
                </div>
            </div>
        </div>

        <div class="flagpm_photo_header">
            <div class="flagpm_wrapper flagpm_clearfix">
                <div class="flagpm_focus_actions">
                    <?php if ( ! empty( $settings['show_share_button'] ) ) { ?>
                        <ul class="flagpm_focus_share">
                            <li style="list-style:none;" class="flagpm_share_wrapper">
                                <a class="flagpm_button flagpm_share"><?php _e( 'Share', 'flash-album-gallery' ); ?></a>
                                <ul class="flagpm_sharelizers flagpm_clearfix">
                                    <li style="list-style:none;"><a class="flagpm_button flagpm_facebook flagpm_sharelizer"><?php _e( 'Facebook', 'flash-album-gallery' ); ?></a></li>
                                    <li style="list-style:none;"><a class="flagpm_button flagpm_twitter flagpm_sharelizer"><?php _e( 'Twitter', 'flash-album-gallery' ); ?></a></li>
                                    <li style="list-style:none;"><a class="flagpm_button flagpm_pinterest flagpm_sharelizer"><?php _e( 'Pinterest', 'flash-album-gallery' ); ?></a></li>
                                    <li style="list-style:none;"><a class="flagpm_button flagpm_stumbleupon flagpm_sharelizer"><?php _e( 'StumbleUpon', 'flash-album-gallery' ); ?></a></li>
                                </ul>
                            </li>
                        </ul>
                    <?php } ?>
                    <?php if ( ! empty( $settings['show_like_button'] ) ) { ?>
                        <ul class="flagpm_focus_like_fave flagpm_clearfix">
                            <li style="list-style:none;"><a class="flagpm_button flagpm_like"><?php _e( 'Like', 'flash-album-gallery' ); ?></a></li>
                        </ul>
                    <?php } ?>
                    <ul class="flagpm_focus_arrows flagpm_clearfix">
                        <li style="list-style:none;"><a class="flagpm_button flagpm_photo_arrow_previous flagpm_prev"><?php _e( 'Previous', 'flash-album-gallery' ); ?></a></li>
                        <li style="list-style:none;"><a class="flagpm_button flagpm_photo_arrow_next flagpm_next"><?php _e( 'Next', 'flash-album-gallery' ); ?></a></li>
                    </ul>
                </div>
                <div class="flagpm_name_wrap flagpm_clearfix flagpm_no_avatar">
                    <div class="flagpm_title_author">
                        <div class="flagpm_title"><?php echo $content['data'][ $iSlide ]['title']; ?></div>
                    </div>
                </div>
                <div class="flagpm_actions flagpm_clearfix">
                    <div class="flagpm_carousel flagpm_has_previous flagpm_has_next">
                        <div class="flagpm_previous_button"></div>
                        <div class="flagpm_photo_carousel">
                            <div class="swiper-container swiper-small-images">
                                <div class="swiper-wrapper">
                                    <?php echo implode( '', $slides_thumbs ); ?>
                                </div>
                            </div>
                        </div>
                        <div class="flagpm_next_button"></div>
                    </div>
                    <?php
                    $show_share_button = ! empty( $settings['show_share_button'] );
                    $show_like_button  = ! empty( $settings['show_like_button'] );
                    if ( $show_share_button || $show_like_button ) { ?>
                        <div class="flagpm_big_button_wrap<?php echo ( ! $show_share_button || ! $show_like_button ) ? ' flagpm_one_button' : ''; ?>">
                            <?php if ( $show_share_button ) { ?>
                                <div class="flagpm_share_wrapper">
                                    <a class="flagpm_button flagpm_share"><?php _e( 'Share', 'flash-album-gallery' ); ?></a>

                                    <div class="flagpm_sharelizers_wrap">
                                        <ul class="flagpm_sharelizers">
                                            <li style="list-style:none;"><a class="flagpm_button flagpm_facebook flagpm_sharelizer"><?php _e( 'Facebook', 'flash-album-gallery' ); ?></a></li>
                                            <li style="list-style:none;"><a class="flagpm_button flagpm_twitter flagpm_sharelizer"><?php _e( 'Twitter', 'flash-album-gallery' ); ?></a></li>
                                            <li style="list-style:none;"><a class="flagpm_button flagpm_pinterest flagpm_sharelizer"><?php _e( 'Pinterest', 'flash-album-gallery' ); ?></a></li>
                                            <li style="list-style:none;"><a class="flagpm_button flagpm_stumbleupon flagpm_sharelizer"><?php _e( 'StumbleUpon', 'flash-album-gallery' ); ?></a></li>
                                        </ul>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ( $show_like_button ) { ?>
                                <a class="flagpm_button flagpm_like"><?php _e( 'Like', 'flash-album-gallery' ); ?></a>
                            <?php } ?>
                        </div>
                    <?php } ?>
                    <?php if ( ! empty( $settings['show_download_button'] ) ) { ?>
                        <div class="flagpm_big_button_wrap">
                            <a class="flagpm_big_button flagpm_download_button" href="<?php echo $content['data'][ $iSlide ]['download']; ?>" download="<?php esc_attr_e( $content['data'][ $iSlide ]['file'] ); ?>">
                                <span class="flagpm_icon"></span>
                                <span class="flagpm_label"><?php echo $settings['download_button_text']; ?></span>
                            </a>
                        </div>
                    <?php } ?>
                    <?php if ( ! empty( $settings['show_link_button'] ) ) {
                        if ( empty( $content['data'][ $iSlide ]['link'] ) ) {
                            $link_class = ' flagpm_inactive';
                            $link_href  = '';
                        } else {
                            $link_class = '';
                            $link_href  = "href='{$content['data'][$iSlide]['link']}' target='{$content['data'][$iSlide]['link_target']}'";
                        }
                        ?>
                        <div class="flagpm_big_button_wrap">
                            <a class="flagpm_big_button flagpm_link_button<?php echo $link_class; ?>" <?php echo $link_href; ?>>
                                <span class="flagpm_icon"></span>
                                <span class="flagpm_label"><?php echo $settings['link_button_text']; ?></span>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="flagpm_focus_close_full">
            <span><a class="flagpm_button flagpm_close"><?php _e( 'Close', 'flash-album-gallery' ); ?></a></span>
            <span><a class="flagpm_button flagpm_full"><?php _e( 'Full', 'flash-album-gallery' ); ?></a></span>
        </div>
        <div class="flagpm_photo_details">
            <div class="flagpm_description_wrap<?php echo empty( $content['data'][ $iSlide ]['description'] ) ? ' empty-item-description' : ''; ?>">
                <?php if ( ! empty( $settings['show_description'] ) ) { ?>
                    <?php if ( ! empty( $settings['description_title'] ) ) { ?>
                        <div class="details_title"><?php echo $settings['description_title']; ?></div>
                    <?php } ?>
                    <div class="flagpm_description_text_wrap">
                        <div class="flagpm_slide_description"><?php echo $content['data'][ $iSlide ]['description']; ?></div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="flagpm_focus_footer">
            <div class="flagpm_focus_keyboard">
                <div class="flagpm_focus_keyboard_title"><?php _e( 'Keyboard Shortcuts', 'flash-album-gallery' ); ?> <a class="flagpm_focus_keyboard_dismiss"><?php _e( 'Dismiss', 'flash-album-gallery' ); ?></a></div>
                <ul>
                    <li style="list-style:none;"><a data-key="p" class="flagpm_key">S</a><span class="flagpm_label"><?php _e( 'Slideshow', 'flash-album-gallery' ); ?></span></li>
                    <li style="list-style:none;"><a data-key="m" class="flagpm_key">M</a><span class="flagpm_label"><?php _e( 'Maximize', 'flash-album-gallery' ); ?></span></li>
                    <li style="list-style:none;"><a data-key="left" class="flagpm_key">&nbsp;</a><span class="flagpm_label"><?php _e( 'Previous', 'flash-album-gallery' ); ?></span></li>
                    <li style="list-style:none;"><a data-key="right" class="flagpm_key">&nbsp;</a><span class="flagpm_label"><?php _e( 'Next', 'flash-album-gallery' ); ?></span></li>
                    <li style="list-style:none;"><a data-key="escape" class="flagpm_key flagpm_esc">esc</a><span class="flagpm_label"><?php _e( 'Close', 'flash-album-gallery' ); ?></span></li>
                </ul>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        jQuery(function($) {
            var settings = <?php echo json_encode($settings); ?>;
            var content = <?php echo json_encode($content); ?>;
            var container = $('#FlaGallery_<?php echo $galleryID; ?>');
            container.photomania(settings, content);
            window.FlaGallery_<?php echo $galleryID; ?> = container.data('photomania');
        });
    </script>
<?php

$cssid     = "#FlaGallery_{$galleryID}";
$color_css = '';
if ( isset( $settings['link_color'] ) ) {
    $color_css .= "
{$cssid} .flagpm_photo_details .flagpm_description_wrap a,
{$cssid} .flagpm_big_button_wrap .flagpm_button.flagpm_like.flagpm_liked,
{$cssid} .flagpm_big_button_wrap .flagpm_button.flagpm_like.flagpm_liked:hover {color:{$settings['link_color']};}
{$cssid} .flagpm_photo_show .flagpm_big_button {background-color:{$settings['link_color']};}";
}
if ( isset( $settings['link_color_hover'] ) ) {
    $color_css .= "
{$cssid} .swiper-small-images div.flagpm_photo.swiper-slide-active {border-color:{$settings['link_color_hover']};}
{$cssid} .flagpm_photo_header .flagpm_name_wrap .flagpm_title_author a:hover,
{$cssid} .flagpm_photo_details .flagpm_description_wrap a:hover {color:{$settings['link_color_hover']};}
{$cssid} .flagpm_photo_show .flagpm_big_button:hover,
{$cssid} .flagpm_focus_actions ul .flagpm_button.like.flagpm_liked {background-color:{$settings['link_color_hover']};}";
}

$flagpm_css = "
{$cssid} .flagpm_preload {opacity:0;}";
if ( 'fit' == $settings['scale_mode'] ) {
    $flagpm_css .= "
{$cssid} .swiper-big-images img.flagpm_the_photo { max-height:100%; max-width:100%; display:inline; width:auto; height:auto !important; object-fit:unset; vertical-align:middle; border:none; }";
} else {
    $flagpm_css .= "
{$cssid} .swiper-big-images img.flagpm_the_photo { max-height:100%; max-width:100%; display:inline; width:100%; height:100% !important; object-fit:cover; vertical-align:middle; border:none; }";
}
$flagpm_css .= "
{$cssid} .flagpm_focus .swiper-big-images img.flagpm_the_photo { width:auto; height:auto; object-fit:unset; }
{$cssid} .swiper-small-images img.flagpm_photo { max-width:none; max-height:none; }
{$cssid} .swiper-small-images img.flagpm_photo.flagpm_photo_landscape { width:auto; height:100% !important; }
{$cssid} .swiper-small-images img.flagpm_photo.flagpm_photo_portrait { width:100%; height:auto !important; }
{$cssid} .flagpm_gallery_sources_list p { margin:7px 0; padding:0; font-size:inherit; }";
$customCSS = $flagpm_css . $color_css . $customCSS;
