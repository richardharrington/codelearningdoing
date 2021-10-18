<?php
function load_vigilance_child_extend() {
	global $vigilance_child;
	locate_template( array( 'functions/vigilance-child-extend.php' ), true );
		if ( class_exists( 'MyChildTheme' ) ) {
			$vigilance_child = new MyChildTheme;
		}
}

add_action( 'after_setup_theme', 'load_vigilance_child_extend');