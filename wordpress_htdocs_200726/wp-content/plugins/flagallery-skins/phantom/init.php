<?php
$ver = '1.1';

/**
 * @var $data
 **/
$base_url_host = parse_url(site_url(), PHP_URL_HOST);
unset($settings['customCSS']);

wp_enqueue_style('flagallery-phantom-skin', plugins_url('/css/phantom.css', __FILE__), array('magnific-popup'), $ver);
wp_enqueue_script('flagallery-phantom-skin', plugins_url('/js/phantom.js', __FILE__), array(
    'jquery',
    'magnific-popup',
), $ver, true
);

$thumbsWrapper_class = (int) $settings['thumbScale']? ' flagPhantom_ThumbScale' : '';
if('label' == $settings['thumbsInfo']){
    if((int) $settings['labelOnHover']){
        $thumbsWrapper_class .= ' flagPhantom_LabelHover';
    } else{
        $thumbsWrapper_class .= ' flagPhantom_LabelInside';
    }
} elseif('label_bottom' == $settings['thumbsInfo']){
    $thumbsWrapper_class .= ' flagPhantom_LabelBottom';
} elseif('tooltip' == $settings['thumbsInfo']){
    $thumbsWrapper_class .= ' flagPhantom_LabelTooltip';
} elseif('none' == $settings['thumbsInfo']){
    $thumbsWrapper_class .= ' flagPhantom_LabelNone';
}

?>
    <div class="flagPhantom_Container noLightbox" style="opacity:0">
        <?php
        $div_r = $settings['thumbWidth'] / $settings['thumbWidth'];
        foreach($data as $gallery){
            $path  = $gallery['gallery']['path'];
            $items = $gallery['data'];
            if($gallery['gallery']['title']){
                echo "<h3>{$gallery['gallery']['title']}</h3>";
            }
            if($gallery['gallery']['galdesc']){
                echo '<div class="flagPhantom_galleryDescription">' . apply_filters('the_content', stripslashes($gallery['gallery']['galdesc'])) . '</div>';
            }
            ?>
            <div class="flagPhantom_thumbsWrapper<?php echo $thumbsWrapper_class; ?>">
                <?php
                foreach($items as $item){
                    $image = $path . '/webview/' . $item['filename'];
                    $thumb = $path . '/thumbs/thumbs_' . $item['filename'];

                    $description = str_replace(array(
                                                   "\r\n",
                                                   "\r",
                                                   "\n",
                                               ), '', wpautop(stripslashes($item['description']))
                    );

                    $title = $alttext = stripslashes($item['alttext']);

                    $link_target = '';
                    if($item['link']){
                        $url_host = parse_url($item['link'], PHP_URL_HOST);
                        if($url_host == $base_url_host || empty($url_host)){
                            $link_target = '_self';
                        } else{
                            $link_target = '_blank';
                        }
                        $title = "<a href='{$item['link']}' target='{$link_target}'>" . ($title? $title : $item['filename']) . '</a>';
                    }

                    $download_link = $path . '/' . $item['filename'];

                    $meta_data = $item['meta_data'];
                    $width     = $meta_data['webview'][0];
                    $height    = $meta_data['webview'][1];
                    $thumb_r   = (int)$height ? $width / $height : 1;
                    $ratio = $thumb_r > $div_r? $thumb_r : $div_r;
                    $item_data = array(
                        'id'    => $item['pid'],
                        'ratio' => $ratio,
                        'title' => $alttext,
                    );

                    $item_data['views'] = $item['hitcounter'];
                    $item_data['likes'] = $item['total_votes'];

                    if($item['link']){
                        $item_data['link']   = $item['link'];
                        $item_data['target'] = $link_target;
                    }

                    $item_data_html = '';
                    foreach($item_data as $key => $val){
                        $val            = esc_attr($val);
                        $item_data_html .= " data-{$key}=\"{$val}\"";
                    }
                    ?>
                <div class="flagPhantom_ThumbContainer flagPhantom_ThumbLoader"<?php echo $item_data_html; ?>>
                    <a href="<?php echo ( !empty($settings['thumb2link']) && $item['link'])? $item['link'] : $image; ?>" class="flagPhantom_Thumb"><img src="<?php echo $thumb; ?>" data-src="<?php echo $image; ?>" alt="<?php esc_attr_e($alttext); ?>"/></a>
                    <?php
                    if(in_array($settings['thumbsInfo'], array('label', 'label_bottom'))){ ?>
                        <div class="flagPhantom_ThumbLabel">
                            <span class="flagPhantom_ThumbLabel_title"><?php echo $title; ?></span></div>
                        <?php
                    } ?>
                    <div style="display:none;" class="flagPhantom_Details">
                        <?php
                        if( !(int) $settings['show_title']){
                            $title = '';
                        }
                        if($title || $description){ ?>
                            <div class="flagPhantom_description">
                                <div class="flagPhantom_title"><?php echo $title; ?></div>
                                <div class="flagPhantom_text"><?php echo $description; ?></div>
                            </div>
                        <?php } ?>
                    </div>
                    </div><?php
                }
                ?>
            </div>
            <?php
        }
        ?>
    </div>
<?php
/* Dynamic CSS */
$mfp_id  = "#mfp_gm_{$galleryID}";
$mfp_css = '';
if(isset($settings['lightboxControlsColor'])){
    $mfp_css .= "
{$mfp_id} .mfp-arrow-right:after,
{$mfp_id} .mfp-arrow-right .mfp-a {border-left-color:{$settings['lightboxControlsColor']};}
{$mfp_id} .mfp-arrow-left:after,
{$mfp_id} .mfp-arrow-left .mfp-a {border-right-color:{$settings['lightboxControlsColor']};}
{$mfp_id} .mfp-close,
{$mfp_id} .mfp-likes,
{$mfp_id} .mfp-share {color:{$settings['lightboxControlsColor']};}
{$mfp_id} .mfp-preloader {background-color:{$settings['lightboxControlsColor']};}";
}
if(isset($settings['lightboxTitleColor'])){
    $mfp_css .= "
{$mfp_id} .flagPhantom_title,
{$mfp_id} .mfp-counter {color:{$settings['lightboxTitleColor']};}";
}
if(isset($settings['lightboxTextColor'])){
    $mfp_css .= "
{$mfp_id} .flagPhantom_text {color:{$settings['lightboxTextColor']};}";
}
if(isset($settings['lightboxBGColor'])){
    $mfp_css .= "
{$mfp_id}_bg.mfp-bg {background-color:{$settings['lightboxBGColor']};}";
}
if(isset($settings['lightboxBGAlpha'])){
    $alpha   = $settings['lightboxBGAlpha'] / 100;
    $mfp_css .= "
{$mfp_id}_bg.mfp-bg {opacity:{$alpha};}
{$mfp_id}_bg.mfp-zoom-in.mfp-bg {opacity:0}
{$mfp_id}_bg.mfp-zoom-in.mfp-ready.mfp-bg {opacity:{$alpha};}
{$mfp_id}_bg.mfp-zoom-in.mfp-removing.mfp-bg {opacity:0}";
}
if($mfp_css){
    $settings['mfp_css'] = $mfp_css;
}

$cssid = "#FlaGallery_{$galleryID}";
$dcss  = '';
if(isset($settings['thumbWidth']) || isset($settings['thumbHeight']) || isset($settings['thumbWidthMobile']) || isset($settings['thumbHeightMobile'])){
    $fsize1 = min($settings['thumbHeight'] / 2, $settings['thumbWidth'] / 2);
    $fsize2 = min($settings['thumbHeightMobile'] / 2, $settings['thumbWidthMobile'] / 2);
    $dcss   .= "
{$cssid} .flagPhantom_ThumbContainer {width:{$settings['thumbWidth']}px; height:{$settings['thumbHeight']}px;}
{$cssid} .flagPhantom_MobileView .flagPhantom_ThumbContainer {width:{$settings['thumbWidthMobile']}px; height:{$settings['thumbHeightMobile']}px;}";
}
if(isset($settings['thumbsAlign'])){
    $margin = 'margin-left:auto;margin-right:auto;';
    if('left' == $settings['thumbsAlign']){
        $margin = 'margin-left:0;';
    } elseif('right' == $settings['thumbsAlign']){
        $margin = 'margin-right:0;';
    }
    $dcss .= "
{$cssid} .flagPhantom_Container {{$margin}}";
}
if(isset($settings['thumbsSpacing'])){
    $dcss .= "
{$cssid} .flagPhantom_ThumbContainer {margin:{$settings['thumbsSpacing']}px 0 0 {$settings['thumbsSpacing']}px;}";
}
if(isset($settings['thumbPadding'])){
    $dcss .= "
{$cssid} .flagPhantom_ThumbContainer {padding:{$settings['thumbPadding']}px;}
{$cssid} .flagPhantom_LabelBottom .flagPhantom_ThumbContainer {padding-bottom:36px;}";
}
if(isset($settings['thumbBG'])){
    if('' == $settings['thumbBG']){
        $dcss .= "
{$cssid} .flagPhantom_ThumbContainer {background-color:transparent;}";
    } else{
        $dcss .= "
{$cssid} .flagPhantom_ThumbContainer,
{$cssid} .flagPhantom_LabelBottom .flagPhantom_ThumbLabel {background-color:{$settings['thumbBG']};}";
    }
}
if(isset($settings['thumbAlpha'])){
    $alpha = $settings['thumbAlpha'] / 100;
    $dcss  .= "
{$cssid} .flagPhantom_ThumbContainer .flagPhantom_Thumb {opacity:{$alpha};}";
}
if(isset($settings['thumbAlphaHover'])){
    $alpha = $settings['thumbAlphaHover'] / 100;
    $dcss  .= "
{$cssid} .flagPhantom_ThumbContainer:hover .flagPhantom_Thumb {opacity:{$alpha};}";
}
if(isset($settings['thumbBorderSize']) || isset($settings['thumbBorderColor'])){
    if((int) $settings['thumbBorderSize']){
        $dcss .= "
{$cssid} .flagPhantom_ThumbContainer {border:{$settings['thumbBorderSize']}px solid {$settings['thumbBorderColor']};}";
    } else{
        $dcss .= "
{$cssid} .flagPhantom_ThumbContainer {border:none;}";
    }
}
if(isset($settings['thumbBorderSize'])){
    if((int) $settings['thumbBorderSize'] == 0){
        $dcss .= "
{$cssid} .flagPhantom_ThumbContainer {box-shadow:none;}";
    } else{
        $dcss .= "
{$cssid} .flagPhantom_ThumbContainer {box-shadow:0 0 5px -2px {$settings['thumbBorderColor']};}";
    }
}
if(isset($settings['label8TextColor'])){
    $dcss .= "
{$cssid} .flagPhantom_ThumbLabel {color:{$settings['label8TextColor']};}";
}
if(isset($settings['label8LinkColor'])){
    $dcss .= "
{$cssid} .flagPhantom_ThumbLabel a {color:{$settings['label8LinkColor']};}";
}
if(isset($settings['labelTextColor'])){
    $dcss .= "
{$cssid} .flagPhantom_LabelInside .flagPhantom_ThumbLabel,
{$cssid} .flagPhantom_LabelHover .flagPhantom_ThumbLabel {color:{$settings['labelTextColor']};}";
}
if(isset($settings['labelLinkColor'])){
    $dcss .= "
{$cssid} .flagPhantom_LabelInside .flagPhantom_ThumbLabel a,
{$cssid} .flagPhantom_LabelHover .flagPhantom_ThumbLabel a,
{$cssid} .flagPhantom_LabelInside .flagPhantom_ThumbLabel a:hover,
{$cssid} .flagPhantom_LabelHover .flagPhantom_ThumbLabel a:hover {color:{$settings['labelLinkColor']};}";
}
if((int) $settings['bgAlpha'] > 0){
    $rgb   = implode(',', flagGallery::hex2rgb($settings['bgColor']));
    $alpha = $settings['bgAlpha'] / 100;
    $dcss  .= "
{$cssid} .flagPhantom_Container {background-color:rgba({$rgb},{$alpha});}";
}
if($dcss){
    $customCSS = $dcss . $customCSS;
}
?>

    <script type="text/javascript">
        jQuery(function(){
            var settings = <?php echo json_encode($settings); ?>;
            jQuery('#FlaGallery_<?php echo $galleryID; ?>').flagPhantom([settings]);
        });
    </script><?php
