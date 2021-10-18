<?php
function vigilance_custom_comment ( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;

	$user_capabilities = null;	
	if ( ! empty( $comment->user_id ) ) {
		$comment_user = get_user_by( 'id', $comment->user_id );
		$user_capabilities = $comment_user->wp_capabilities;
	}

	$admin_comment = (
		is_array( $user_capabilities ) &&
		array_key_exists( 'administrator', $user_capabilities ) &&
		$user_capabilities['administrator']
	) ? '<span class="asterisk">*</span>' : '';

	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>" >
		<div class="c-grav"><?php echo get_avatar( $comment, '60' ); ?></div>
		<div class="c-body">
			<div class="c-head">
				<?php comment_author_link(); ?> <span class="c-permalink"><a href="<?php echo get_permalink(); ?>#comment-<?php comment_ID(); ?>"><?php _e( 'permalink', 'vigilance' ); ?></a></span><?php echo $admin_comment; ?>
			</div>
			<div class="c-date">
				<?php comment_date( 'F j, Y' ); ?>
			</div>
			<?php if ($comment->comment_approved == '0' ) : ?>
				<p><?php _e( '<em><strong>Please Note:</strong> Your comment is awaiting moderation.</em>', 'vigilance' ); ?></p>
			<?php endif; ?>
			<?php comment_text(); ?>
			<?php comment_type(( '' ),( 'Trackback' ),( 'Pingback' )); ?>
			<?php comment_reply_link(array( 'before' => '<div class="reply">', 'after' => '</div>', 'depth' => $depth, 'max_depth' => $args['max_depth'])); ?>
			<?php edit_comment_link( 'edit','<p>','</p>' ); ?>
		</div><!--end c-body-->
		<?php
}

// Template for pingbacks/trackbacks
function vigilance_list_pings($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	?>
	<li id="comment-<?php comment_ID(); ?>"><?php comment_author_link(); ?>
	<?php
}