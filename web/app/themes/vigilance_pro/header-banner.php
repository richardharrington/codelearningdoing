<?php global $vigilance, $wp_query;

$custom_field_alt = $custom_field_height = $custom_field_status = $custom_field_url = '';

$img_src = '';
$img_height = $vigilance->bannerHeight();
$img_alt = '' !== $vigilance->bannerAlt() ? $vigilance->bannerAlt() : get_bloginfo( 'name' );

if ( is_singular() ) {
	$queried_id = $wp_query->get_queried_object_id();
	$custom_field_height = wp_filter_post_kses(get_post_meta($queried_id, 'img-height', true));
	$custom_field_url = get_post_meta($queried_id, 'img-url', true);
	$custom_field_alt = stripslashes(wp_filter_post_kses(get_post_meta($queried_id, 'img-alt', true)));
	$custom_field_status = get_post_meta($queried_id, 'img-status', true);
}

if (
	is_singular() && ! is_home() &&
	( $custom_field_url !== '' && $custom_field_status !== 'hidden' )
) {
	$img_src = $custom_field_url;
	$img_height = '' !== $custom_field_height ? $custom_field_height : $vigilance->bannerHeight();
	$img_alt = '' !== $custom_field_alt ? $custom_field_alt : get_the_title( $queried_id );

} elseif ( $vigilance->bannerHome() != '' && is_home() ) {
	$img_src = get_stylesheet_directory_uri() . '/images/top-banner/' . $vigilance->bannerHome();

} elseif ( $vigilance->bannerState() == 'static' && $vigilance->bannerUrl() !== '' ) {
	$img_src = get_stylesheet_directory_uri() . '/images/top-banner/' . $vigilance->bannerUrl();

} elseif ( $vigilance->bannerState() == 'rotate' ) {
	$img_src = get_stylesheet_directory_uri() . '/images/top-banner/rotate.php';

} elseif ( $vigilance->bannerState() == 'custom' ) {
}

if ( $vigilance->bannerState() == 'custom' ) : ?>

	<div id="menu">
		<?php echo $vigilance->bannerCustom(); ?>
	</div><!--end menu-->

<?php elseif ( ! empty( $img_src ) ) : ?>

	<div id="menu">
		<img src="<?php echo esc_attr( $img_src ); ?>" width="596" <?php
			if ( ! empty( $img_height ) ) :
				?> height="<?php echo esc_attr( $img_height ); ?>" <?php
			endif;
		?> alt="<?php echo esc_attr( $img_alt ); ?>" />
	</div><!--end menu-->

<?php endif; ?>