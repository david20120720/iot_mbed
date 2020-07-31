<?php
$ver = '1.1';

global $flagallery_shortcode_instance;

if(empty($flagallery_shortcode_instance)){
    $flagallery_shortcode_instance = array();
}

$base_url_host = parse_url(site_url(), PHP_URL_HOST);

wp_enqueue_style('flagallery-jq-mplayer-skin', plugins_url('/css/jq-mplayer.css', __FILE__), array(), $ver);
wp_enqueue_script('flagallery-jq-mplayer-skin', plugins_url('/js/jq-mplayer.js', __FILE__), array('jplayer'), $ver, true);

unset($settings['customCSS']);

foreach($music as $item){
    $ext = substr(get_attached_file($item->ID), - 3);

    if( !in_array($ext, array('mp3', 'ogg', 'wav', 'mp4'))){
        if('webm' != ($ext = substr($item->flaguid, - 4))){
            continue;
        }
    }
    if($ext == 'ogg'){
        $ext = 'oga';
    }
    $default_cover = '';

    $_metadata = wp_get_attachment_metadata($item->ID);
    $cover     = get_post_meta($item->ID, 'thumbnail', true);
    $file      = wp_get_attachment_url($item->ID);
    $button    = '';
    if($settings['downloadTrack']){
        $button = $file;
    }
    $content[] = array(
        'id'     => $item->ID,
        $ext     => $file,
        'cover'  => $cover,
        'title'  => stripslashes($item->post_title),
        'text'   => str_replace(array("\r\n", "\r", "\n"), '', wpautop(stripslashes($item->post_content))),
        'button' => $button,
    );
}


if( !empty($content)){
    $jqmp_autoplay_setting = intval($settings['autoplay']);
    if($jqmp_autoplay_setting){
        $flagallery_shortcode_instance['music_autoplay'] = isset($flagallery_shortcode_instance['music_autoplay'])? $flagallery_shortcode_instance['music_autoplay'] + 1 : 0;
        if($flagallery_shortcode_instance['music_autoplay']){
            $settings['autoplay'] = '0';
        }
    }
    $galleryID = wp_rand();
    ?>
    <div id="flag_musicplayer_r<?php echo $galleryID; ?>">
        <script type="text/javascript">
            jQuery(function(){
                var settings = <?php echo json_encode($settings); ?>;
                var content = <?php echo json_encode($content); ?>;
                jQuery('#flag_musicplayer_r<?php echo $galleryID; ?>').data('uid', 'r<?php echo $galleryID; ?>').flagMusicPlayer(content, settings);
            });
        </script>
    </div>
    <?php
}
