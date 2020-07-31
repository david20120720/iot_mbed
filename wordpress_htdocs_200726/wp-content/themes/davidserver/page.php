
<?php get_header(); ?>

<div class="container">
	<div class="container-box">
		<div class="main-content">
			<?php
			if(have_posts()) {
				while(have_posts()) :the_post();
					the_content();

				endwhile;
			
			}
			?>
		</div>

	</div>
</div>


<?php get_footer(); ?>
