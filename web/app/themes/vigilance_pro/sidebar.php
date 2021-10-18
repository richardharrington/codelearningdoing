<?php global $vigilance; ?>
<div id="sidebar">
	<?php if ( ( $vigilance->sideimgState() != '' ) && ( $vigilance->sideimgState() != 'hide' ) ) :
		get_template_part( 'sidebar-imagebox' );
	endif; ?>
	<?php if ( $vigilance->feedState() != 'disabled' ) :
		get_template_part( 'sidebar-feedbox' );
	endif; ?>

	<ul>
		<?php if ( ! dynamic_sidebar( 'wide_sidebar' ) ) : ?>
			<li class="widget widget_recent_entries">
				<h2 class="widgettitle"><?php _e( 'Recent Articles', 'vigilance' ); ?></h2>
				<?php $side_posts = new WP_Query( 'numberposts=10' ); ?>
				<?php if ( $side_posts->have_posts() ) : ?>
					<ul>
						<?php while( $side_posts->have_posts() ) : $side_posts->the_post(); ?>
							<li><a href= "<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
						<?php endwhile; ?>
					</ul>
				<?php endif; ?>
			</li>
		<?php endif; ?>
	</ul>

	<?php if ( is_active_sidebar( 'left_sidebar' ) ) : ?>
		<ul class="thin-sidebar spad">
		<?php dynamic_sidebar( 'left_sidebar' ); ?>
		</ul>
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'right_sidebar' ) ) : ?>
		<ul class="thin-sidebar">
		<?php dynamic_sidebar( 'right_sidebar' ); ?>
		</ul>
	<?php endif; ?>
</div><!--end sidebar-->