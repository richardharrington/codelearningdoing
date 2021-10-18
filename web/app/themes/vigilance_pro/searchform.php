<h2 class="widgettitle"><?php _e( 'Search', 'vigilance' ); ?></h2>
<form method="get" id="search-form" action="<?php echo home_url( '/' ); ?>/">
	<div>
		<label for="s"><?php _e( 'Search', 'vigilance' ); ?></label>
		<input type="text" value="<?php esc_attr_e( 'type and press enter', 'vigilance' ); ?>" name="s" id="s" onfocus="if (this.value == '<?php echo esc_js( __( 'type and press enter', 'vigilance' ) ); ?>' ) {this.value = '';}" onblur="if (this.value == '' ) {this.value = '<?php echo esc_js( __( 'type and press enter', 'vigilance' ) ); ?>';}" />
		<input type="hidden" value="<?php _e( 'Search', 'vigilance' ); ?>" />
	</div>
</form>