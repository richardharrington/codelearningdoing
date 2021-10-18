<?php
/*
Template Name: Archives
*/
?>
<?php get_header(); ?>
	<?php query_posts( 'showposts=25' ); ?>
	<?php if (have_posts()) : ?>
		<h1 class="pagetitle"><?php bloginfo( 'title' ); ?> <?php _e( 'Archives', 'vigilance' ); ?></h1>
		<div class="entry">
			<h2><?php _e( 'Recent Posts', 'vigilance' ); ?></h2>
		</div><!--end-entry-->
		<img class="archive-comment" src="<?php echo get_template_directory_uri(); ?>/images/comments-bubble-archive.gif" width="17" height="14" alt="<?php _e( 'comment', 'vigilance' ); ?>"/>
		<div class="entries">
			<ul>
			<?php while (have_posts()) : the_post(); ?>
				<li><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><span class="comments-number"><?php comments_number( '0', '1', '%', '' ); ?></span><span class="archdate"><?php the_time(__ ( 'n.j.y', 'vigilance' )); ?></span><?php the_title(); ?></a></li>
			<?php endwhile; ?>
			</ul>
		</div><!--end entries-->
	<?php endif; ?>
	<div class="entry">
		<h2><?php _e( 'Monthly Archives', 'vigilance' ); ?></h2>
		<ul>
			<?php wp_get_archives( 'type=monthly&show_post_count=1' ); ?>
		</ul>
	</div><!--end-entry-->
</div><!--end content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>