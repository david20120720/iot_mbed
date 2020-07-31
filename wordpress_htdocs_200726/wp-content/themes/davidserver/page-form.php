<?php
/*
Template Name: FORM
Template Post Type: page
*/
?>


<form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>">
  <input type="hidden" name="action" value="user_form">
  <?php wp_nonce_field( 'user_form' ); ?>
	<p>temperature : <input type="text" name="temp"></p> 
	<p>humidity : <input type="text" name="humi"></p> 
	<p><input type="submit" /></p> 
</form>



