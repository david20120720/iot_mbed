<!-- 메뉴에서 포스트 관련 메뉴를 선택하면 아래 포스트가 열리게 된다 
즉 index.php 화일에 아래 포스트코드 디스플레이 코드가 있으므로, index.php가 열리는것이다.  
사이드바도 아래 코드와 같이 출력이 된다
그래서 화면이 좌우측으로 나뉘어 오른쪽은 사이드바  왼쪽은 포스트가 열리게 된다 : david*/
-->


<?php get_header(); ?>

<div class="container">
	<div class="container-box">
		<div class="main-content">
		<?php
			if(have_posts()) {
				while(have_posts()) :the_post(); 
					?><h5><?php
					_e("Category:",'davidserver');
					the_category('&gt;','multiple');
					?></h5>
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?>
					<?php the_post_thumbnail('small-thumbnail'); ?>
					</a></h3>
					<?php echo get_the_excerpt(); ?>
					<a href="<?php the_permalink(); ?>">View page</a>
					<hr><?php
				endwhile;
			}
		?>
		</div>
		<div class="sidebar">
			<?php get_sidebar(); ?>
		</div>
	</div>
</div>

<?php get_footer(); ?>
