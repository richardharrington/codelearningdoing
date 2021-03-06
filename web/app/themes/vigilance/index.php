<?php get_header(); ?>
	<?php if (have_posts()) : ?>
	<?php while (have_posts()) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<div class="post-header">
				<h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php esc_attr( sprintf( __( 'Permanent Link to %s', 'vigilance' ), the_title_attribute( 'echo=false' ) ) ); ?>"><?php the_title(); ?></a></h2>
				<div class="date"><span><?php the_time(__( 'Y', 'vigilance' )); ?></span> <?php the_time( __( 'F j', 'vigilance' ) ); ?></div>
				<div class="comments"><?php comments_popup_link( __( 'Leave a comment', 'vigilance' ),  __( '1 Comment', 'vigilance' ), _n ( '% Comment', '% Comments', get_comments_number (),'vigilance' )); ?></div>
			</div><!--end post header-->
			<div class="meta clear">
				<div class="tags"><?php the_tags(__( 'tags: ', ', ', '', 'vigilance' )); ?></div>
				<div class="author"><?php printf( __( 'by %s', 'vigilance' ), get_the_author()); ?></div>
			</div><!--end meta-->
			<div class="entry clear">
				<?php if ( has_post_thumbnail() ) {
					the_post_thumbnail( array(250,9999), array( 'class' => 'alignleft' ) );
				} ?>
				<?php the_content(__( 'read more...', 'vigilance' )); ?>
				<?php edit_post_link( __( 'Edit this', 'vigilance' ), '<p>', '</p>' ); ?>
				<?php wp_link_pages(); ?>
			</div><!--end entry-->
			<div class="post-footer">
				<p><?php printf( __( 'from &rarr; %s', 'vigilance' ), get_the_category_list( ', ' ) ); ?></p>
			</div><!--end post footer-->
		</div><!--end post-->
	<?php endwhile; /* rewind or continue if all posts have been fetched */ ?>
		<div class="navigation index">
			<div class="alignleft"><?php next_posts_link( __( '&laquo; Older Entries', 'vigilance' )); ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Newer Entries &raquo;', 'vigilance' )); ?></div>
		</div><!--end navigation-->
	<?php endif; ?>
</div><!--end content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>