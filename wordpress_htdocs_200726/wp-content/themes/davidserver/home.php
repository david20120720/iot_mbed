<!--홈페이지에 접속했을때 hpme.php 화일이 있으며, index.php가 아닌, home.php을 열게된다. 즉 우선순위는 home.php 가 높다 : david
-->

<?php get_header('home'); ?>


<div class="container">
	<div class="container-box">
        <div class="main-content">
          <?php echo do_shortcode("[PFG id=60]"); ?>
	    </div>
	</div>

</div>

<div class="mainbox-container">
	<div class="mainbox">
		<div class="subbox1">
			<div>CALENDAR</div><br>
			<?php dynamic_sidebar('bottom-sidebar-a'); ?>
		</div>
		<div class="subbox2">
			<div>RECENT POST</div><br>
			<?php dynamic_sidebar('bottom-sidebar-b'); ?>
		</div>
        <div class="subbox3">
			<div>CONTACTS</div><br>
			<?php dynamic_sidebar('bottom-sidebar-c'); ?>
		</div>

	</div>
</div>

<?php get_footer(); ?>

