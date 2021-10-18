<?php global $vigilance, $wp_the_query;

$custom_field_alt = $custom_field_link = $custom_field_height = $custom_field_status = $custom_field_url = '';

$img_link = $vigilance->sideimgLink();
$img_height = $vigilance->sideimgHeight();
$img_alt = '' !== $vigilance->sideimgAlt() ? $vigilance->sideimgAlt() : get_bloginfo( 'name' );
$img_src = '';
$queried_id = $wp_the_query->get_queried_object_id();
wp_reset_query();

if ( is_singular() ) {
	$custom_field_url = wp_filter_post_kses(get_post_meta($queried_id, 'sideimg-url', true));
	$custom_field_alt = stripslashes(wp_filter_post_kses(get_post_meta($queried_id, 'sideimg-alt', true)));
	$custom_field_link = wp_filter_post_kses(get_post_meta($queried_id, 'sideimg-link', true));
	$custom_field_height = wp_filter_post_kses(get_post_meta($queried_id, 'sideimg-height', true));
	$custom_field_status = get_post_meta($queried_id, 'sideimg-status', true);
}

if (
	( $custom_field_status !== 'hidden' && $custom_field_url !== '' ) ||
	( $vigilance->sideimgState() == 'specific' )
) {
	$img_link = $custom_field_link;
	$img_src = is_singular() && '' !== $custom_field_url ? $custom_field_url : '';
	$img_height = '' !== $custom_field_height ? $custom_field_height : $vigilance->sideimgHeight();
	$img_alt = '' !== $custom_field_alt ? $custom_field_alt : get_the_title( $queried_id );

} elseif ( $vigilance->sideimgState() == 'static' && $vigilance->sideimgUrl() !== '' ) {
	$img_src = get_stylesheet_directory_uri() . '/images/sidebar/' . $vigilance->sideimgUrl();

} elseif ( $vigilance->sideimgState() != 'custom' ) {
	$img_src = get_stylesheet_directory_uri() . '/images/sidebar/rotate.php';
}

if ( $vigilance->sideimgState() == 'custom' ) :

	?>
	<div id="sidebar-image">
		<?php echo $vigilance->sideimgCustom(); ?>
	</div><!--end sidebar-image-->
	<?php

elseif ( ! empty( $img_src ) ) :
	?>
	<div id="sidebar-image">
		<?php if ( ! empty( $img_link ) ) : ?>
			<a href="<?php echo esc_attr( $img_link ); ?>">
		<?php endif; ?>

			<img src="<?php echo esc_attr( $img_src ); ?>" width="300" <?php
				if ( ! empty( $img_height ) ) :
					?> height="<?php echo esc_attr( $img_height ); ?>" <?php
				endif;
			?> alt="<?php echo esc_attr( $img_alt ); ?>" />

		<?php if ( ! empty( $img_link ) ) : ?>
			</a>
		<?php endif; ?>
	</div>
<?php endif;