<?php
$ver = '1.1';

$base_url_host = parse_url(site_url(), PHP_URL_HOST);
$skin_url      = str_replace(WP_PLUGIN_DIR, plugins_url(), $skinpath);

wp_enqueue_style('flagallery-wp-videoplayer-skin', plugins_url('/css/wp-videoplayer.css', __FILE__), array('mediaelement'), $ver);
wp_enqueue_script('flagallery-wp-videoplayer-skin', plugins_url('/js/wp-videoplayer.js', __FILE__), array(
    'jquery',
    'wp-util',
    'backbone',
    'mediaelement',
), $ver, true
);

unset($settings['customCSS']);

$content = array();

foreach($videos as $item){
    $default_cover  = wp_mime_type_icon($item->mime_type);
    $cover = get_post_meta($item->ID, 'thumbnail', true);

    $nocover = false;
    if(!$cover){
        $cover = wp_mime_type_icon($item->mime_type);
        $nocover = true;
    }
    $meta = wp_get_attachment_metadata($item->ID);
    $height    = $settings['width'] / 16 * 9;
    $content[] = array(
        'id'          => $item->ID,
        'src'         => wp_get_attachment_url($item->ID),
        'title'       => $item->post_title,
        'description' => str_replace(array("\r\n", "\r", "\n"), '', wpautop($item->post_content)),
        'meta'        => array('length_formatted' => $meta['length_formatted']),
        'dimensions'  => array(
            'original' => array('width' => $meta['width'], 'height' => $meta['height']),
            'resized'  => array('width' => intval($settings['width']), 'height' => intval($height)),
        ),
        'image'       => $cover,
        'icon'       => $nocover,
    );
}


if( !empty($content)){
    $json_array = array(
        'type'         => 'video',
        'tracklist'    => true,
        'tracknumbers' => ('1' == $settings['tracknumbers']),
        'images'       => true,
        'artists'      => true,
        'tracks'       => $content,
    );
    ?>
    <!--[if lt IE 9]>
    <script>document.createElement('video');</script><![endif]-->
    <div class="gmedia-wp-playlist wp-video-playlist wp-playlist-light" style="width:<?php echo intval($settings['width']) . 'px'; ?>; max-width:100%;">
        <video controls="controls" preload="none" width="640" height="480"></video>
        <div class="wp-playlist-next"></div>
        <div class="wp-playlist-prev"></div>
        <noscript>
            <ol>
                <?php foreach($content as $item){ ?>
                    <li><a href='<?php echo $item['src']; ?>'><?php echo $item['title']; ?></a></li>
                <?php } ?>
            </ol>
        </noscript>
        <script type="application/json"><?php echo json_encode($json_array); ?></script>
    </div>
    <?php
}