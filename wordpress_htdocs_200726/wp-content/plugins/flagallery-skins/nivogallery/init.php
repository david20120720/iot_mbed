<?php
$ver = '1.1';

$base_url_host = parse_url(site_url(), PHP_URL_HOST);

wp_enqueue_style('flagallery-nivoslider-skin', plugins_url('/css/nivoslider.css', __FILE__), array('flag-nivo-slider'), $ver);
wp_enqueue_script('flagallery-nivoslider-skin', plugins_url('/js/nivoslider.js', __FILE__), array(
    'jquery',
    'flag-nivo-slider',
), $ver, true
);

$settings['boxCols']  = 8;
$settings['boxRows']  = 4;
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
        if( !empty($settings['linkTarget'])){
            $link_target = ' target="_blank"';
        }
        foreach($data as $gallery){
            $path  = $gallery['gallery']['path'];
            $items = $gallery['data'];
            //if($gallery['gallery']['title']){
            //    echo "<h3>{$gallery['gallery']['title']}</h3>";
            //}
            //if($gallery['gallery']['galdesc']){
            //    echo '<div class="flagNivoSlider_galleryDescription">' . apply_filters('the_content', stripslashes($gallery['gallery']['galdesc'])) . '</div>';
            //}

            foreach($items as $item){
                $link = $item['link'];
                $url  = $path . '/webview/' . $item['filename'];
                if($link){
                    echo '<a href="' . $link . '"' . $link_target . '><img alt="' . esc_attr(strip_tags(stripslashes($item['alttext']))) . '" src="' . $url . '"/></a>';
                } else{
                    echo '<img alt="' . esc_attr(strip_tags(stripslashes($item['alttext']))) . '" src="' . $url . '"/>';
                }
            }
        }
        ?>
    </div>
</div>
