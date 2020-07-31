<?php
$ver = '1.0';
global $wp;
$settings['galleryID'] = $galleryID;
$settings['post_url'] = add_query_arg($_SERVER['QUERY_STRING'], '', home_url($wp->request));
$settings['module_url'] = plugins_url('/', __FILE__);

wp_enqueue_script('flagallery-paginator-skin', plugins_url('/js/paginator.js', __FILE__), array(), $ver, true);

?>
<script type="text/javascript">
	(function(){
		this['<?php echo "FlaGallery_".esc_attr($galleryID); ?>']={'settings':<?php echo json_encode($settings);?>, 'data':<?php echo json_encode($data);?>, };
	})();
</script>