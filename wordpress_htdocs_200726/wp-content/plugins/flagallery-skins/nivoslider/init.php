<?php
$ver = '1.1';

$base_url_host = parse_url(site_url(), PHP_URL_HOST);

wp_enqueue_style('flagallery-nivoslider-skin', plugins_url('/css/nivoslider.css', __FILE__), array('flag-nivo-slider'), $ver);
wp_enqueue_script('flagallery-nivoslider-skin', plugins_url('/js/nivoslider.js', __FILE__), array('jquery', 'flag-nivo-slider'), $ver, true);

$settings['boxCols'] = 8;
$settings['boxRows'] = 4;
$settings['prevText'] = 'Prev';
$settings['nextText'] = 'Next';

unset($settings['customCSS']);

?>
<style type="text/css" scoped="scoped">@import url('<?php echo plugins_url(FLAGFOLDER . '/assets/nivoslider/themes/' . $settings['theme'] . '/' . $settings['theme'] . '.css'); ?>') all;</style>
<div class="slider-wrapper theme-<?php echo $settings['theme']; ?>" style="position: relative;">
    <div class="ribbon"></div>
    <div class="flagallery_nivoSlider" data-settings="<?php echo esc_attr(json_encode($settings)); ?>">
        <?php

        $link_target = '';
        if(!empty($settings['linkTarget'])){
            $link_target = ' target="_blank"';
        }
        foreach($pictures as $picture){
            $link = get_post_meta($picture->ID, 'link', true);
            $url = wp_get_attachment_url($picture->ID);
            if($link){
                echo '<a href="' . $link . '"' . $link_target . '><img alt="' . esc_attr(strip_tags($picture->post_title)) . '" src="' . $url . '"/></a>';
            } else{
                echo '<img alt="' . esc_attr(strip_tags($picture->post_title)) . '" src="' . $url . '"/>';
            }
        }
        ?>
    </div>
</div>
