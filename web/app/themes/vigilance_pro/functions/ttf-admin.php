<?php

if ( ! class_exists('TTFCore') ) {
	class TTFCore {

		var $domain;
		var $themename = "TTF";
		var $themeurl = "http://thethemefoundry.com/";
		var $shortname = "ttf_themes";
		var $options = array();

		/* PHP4 Compatible Constructor */
		function TTFCore () {
			add_action( 'admin_init', array(&$this, 'printAdminScripts' ) );
			add_action( 'admin_menu', array(&$this, 'addAdminPage' ) );
			add_action( 'widgets_init', array(&$this, 'registerSidebars' ) );
			add_action( 'wp_before_admin_bar_render', array(&$this, 'adminBarRender'), 99);
			add_action( 'wp_footer', array(&$this, 'statsCode'));

			load_theme_textdomain( $this->domain, locate_template( array( 'languages' ) ) );

			$this->setupTheme();
		}

		function setupTheme() {
			$this->addFeedSupport();
			$this->addImageSize();
			$this->addThumbnailSupport();

			do_action( 'setup_theme_' . $this->domain );
		}

		function addFeedSupport() {
			add_theme_support( 'automatic-feed-links');
		}

		function addImageSize() {}

		function addThumbnailSupport() {
			add_theme_support( 'post-thumbnails' );
		}

		/* Add Custom CSS & JS */
		function printAdminScripts () {
			if ( current_user_can( 'edit_theme_options' ) && isset( $_GET['page'] ) && $_GET['page'] == basename(__FILE__) ) {
				wp_enqueue_script( 'media-upload' );
				add_thickbox();
				wp_enqueue_style( 'ttf', get_template_directory_uri().'/functions/stylesheets/admin.css');
				wp_enqueue_script( 'ttf', get_template_directory_uri().'/functions/javascripts/admin.js', array('jquery') );
				wp_enqueue_script( 'farbtastic' );
				wp_enqueue_style( 'farbtastic' );
			}
		}

		function registerSidebars() {}

		function adminBarRender() {
			global $wp_admin_bar;

			if ( ! empty( $wp_admin_bar ) ) {
				$wp_admin_bar->add_menu( array(
					'parent' => 'appearance',
					'title' => __( 'Theme Options' ),
					'href' => admin_url('themes.php?page=ttf-admin.php'),
				) );
			}
		}

		/* Process Input and Add Options Page*/
		function addAdminPage() {
			// global $themename, $shortname, $options;
			if ( current_user_can( 'edit_theme_options' ) && isset( $_GET['page'] ) && $_GET['page'] == basename(__FILE__) ) {
				if ( ! empty( $_REQUEST['save-theme-options-nonce'] ) && wp_verify_nonce( $_REQUEST['save-theme-options-nonce'], 'save-theme-options' ) && isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'save' ) {
					foreach ($this->options as $value) {
						if ( array_key_exists('id', $value) ) {
							if ( isset( $_REQUEST[ $value['id'] ] ) ) {
								if (
									in_array(
										$value['id'],
										array(
											$this->shortname.'_background_color',
											$this->shortname.'_hover_color',
											$this->shortname.'_link_color',
										)
									)
								) {
									$opt_value = preg_match( '/^#([a-zA-Z0-9]){3}$|([a-zA-Z0-9]){6}$/', trim( $_REQUEST[ $value['id'] ] ) ) ? trim( $_REQUEST[ $value['id'] ] ) : '';
									update_option( $value['id'], $opt_value );
								} elseif (
									in_array(
										$value['id'],
										array(
											$this->shortname.'_categories_to_exclude',
											$this->shortname.'_pages_to_exclude',
										)
									)
								) {
									$opt_value = implode(',', array_filter( array_map( 'intval', explode(',', $_REQUEST[ $value['id'] ] ) ) ) );
									update_option( $value['id'], $opt_value );
								} else {
									update_option( $value['id'], stripslashes( $_REQUEST[ $value['id'] ] ) );
								}
							} else {
								delete_option( $value['id'] );
							}
						}
					}
					wp_redirect("themes.php?page=".basename(__FILE__)."&saved=true");
					exit;
				} else if ( ! empty( $_REQUEST['reset-theme-options-nonce'] ) && wp_verify_nonce( $_REQUEST['reset-theme-options-nonce'], 'reset-theme-options' ) && isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'reset' ) {
					foreach ($this->options as $value) {
						if ( array_key_exists('id', $value) ) {
							delete_option( $value['id'] );
						}
					}
					wp_redirect("themes.php?page=".basename(__FILE__)."&reset=true");
					exit;
				}
			}

			add_theme_page(
				__( 'Theme Options' ),
				__( 'Theme Options' ),
				'edit_theme_options',
				basename(__FILE__),
				array(&$this, 'adminPage' )
			);
		}

		/* Output of the Admin Page */
		function adminPage () {
			// global $themename, $shortname, $options;
			$up_dir = wp_upload_dir();
			if ( ! empty( $_REQUEST['saved'] ) ) {
				echo '<div id="message" class="updated fade"><p><strong>' . sprintf( __( '%s settings saved!', $this->domain ), $this->themename ) . '</strong></p></div>';
			}

			if ( ! empty( $_REQUEST['reset'] ) ) {
				echo '<div id="message" class="updated fade"><p><strong>' . sprintf( __( '%s settings reset.', $this->domain ), $this->themename ) . '</strong></p></div>';
			}
			?>

		<div id="ttf-options" class="wrap">
			<div id="icon-themes" class="icon32"><br></div>
			<h2><?php _e( 'Theme Options', $this->domain ); ?></h2>
			<div id="ttf-options-body">
				<form method="post" action="">
					<?php wp_nonce_field( 'save-theme-options', 'save-theme-options-nonce' );
					for ($i = 0; $i < count($this->options); $i++) :
						switch ($this->options[$i]["type"]) :
							case "subhead":
								if ($i != 0) :
									?>
									<div class="ttf-save-button submit">
										<input type="hidden" name="action" value="save" />
										<input class="button-primary" type="submit" value="<?php esc_attr_e( 'Save changes', $this->domain ); ?>" name="save"/>
									</div><!--end ttf-save-button-->
								</div>
							</div><!-- ttf-option -->
									<?php
								endif;

								?>
								<div class="ttf-option">
									<h3><?php echo $this->options[$i]["name"]; ?> <a href="#"><?php _e( 'Edit', $this->domain ); ?></a></h3>
									<div class="ttf-option-body clear">
										<?php if ( isset( $this->options[$i]["notice"] ) ) : ?>
											<p class="notice"><?php echo $this->options[$i]["notice"]; ?></p>
										<?php endif; ?>
								<?php
							break;

							case "checkbox":
								?>
								<div class="ttf-field check clear">
									<div class="ttf-field-d"><?php echo $this->options[$i]["desc"]; ?></div>
									<input id="<?php echo $this->options[$i]["id"]; ?>" type="checkbox" name="<?php echo $this->options[$i]["id"]; ?>" value="true"<?php echo (get_option($this->options[$i]['id'])) ? ' checked="checked"' : ''; ?> />
									<label for="<?php echo $this->options[$i]["id"]; ?>"><?php echo $this->options[$i]["name"]; ?></label>
								</div><!--end ttf-field check-->
								<?php
							break;

								case "radio":
									?>
									<div class="ttf-field radio clear">
										<div class="ttf-field-d"><?php echo $this->options[$i]["desc"]; ?></div>
											<?php
											$radio_setting = get_option($this->options[$i]['id']);
											$checked = "";
											foreach ($this->options[$i]['options'] as $key => $val) :
												if ($radio_setting != '' &&	$key == get_option($this->options[$i]['id']) ) {
													$checked = ' checked="checked"';
												} else {
													if ($key == $this->options[$i]['std']){
														$checked = 'checked="checked"';
													}
												}
												?>
												<input type="radio" name="<?php echo esc_attr( $this->options[$i]['id'] ); ?>" value="<?php echo esc_attr( $key ); ?>"<?php echo $checked; ?> /><?php echo $val; ?><br />
											<?php endforeach; ?>
										<label for="<?php echo esc_attr( $this->options[$i]["id"] ); ?>"><?php echo $this->options[$i]["name"]; ?></label>
									</div><!--end ttf-field radio-->
									<?php
								break;

								case "upload":
									?>
									<div class="ttf-field logo-upload clear">
										<div class="ttf-field-d"><?php echo $this->options[$i]["desc"]; ?></div>
										<div class="ttf-field-c">
											<label for="<?php echo esc_attr( $this->options[$i]["id"] ); ?>"><?php echo $this->options[$i]["name"]; ?></label>
											<input id="<?php echo esc_attr( $this->options[$i]["id"] ); ?>" type="text" name="<?php echo esc_attr( $this->options[$i]["id"] ); ?>" class="<?php echo esc_attr( $this->options[$i]["class"] ); ?>" value="<?php
												$id = get_option( $this->options[$i]["id"] );
												echo esc_attr( '' != $id ? $id : $this->options[$i]["std"] ); ?>" />
											<input id="<?php echo esc_attr( $this->options[$i]["id"] ); ?>_upload_button" type="button" value="Upload Logo" data-type="image" data-field-id="<?php echo esc_attr( $this->options[$i]["id"] ); ?>" class="button" />
										</div>
									</div><!--end ttf-field text-->
									<?php
								break;

								case "text":
									?>
									<div class="ttf-field text clear">
										<div class="ttf-field-d"><?php echo $this->options[$i]["desc"]; ?></div>
										<label for="<?php echo esc_attr( $this->options[$i]["id"] ); ?>"><?php echo $this->options[$i]["name"]; ?></label>
										<input id="<?php echo esc_attr( $this->options[$i]["id"] ); ?>" type="text" name="<?php echo esc_attr( $this->options[$i]["id"] ); ?>" value="<?php
											$id = get_option( $this->options[$i]["id"] );
											echo esc_attr( stripslashes( '' != $id ? $id : $this->options[$i]["std"] ) ); ?>" />
									</div><!--end ttf-field text-->
									<?php
								break;

								case "colorpicker":
									?>
									<div class="ttf-field colorpicker clear">
										<div class="ttf-field-d"><?php echo $this->options[$i]["desc"]; ?> </div>
										<label for="<?php echo esc_attr( $this->options[$i]["id"] ); ?>"><?php echo $this->options[$i]["name"]; ?> <a href="#<?php echo esc_attr( $this->options[$i]["id"] ); ?>_colorpicker" onclick="toggleColorpicker (this, '<?php echo esc_js( $this->options[$i]["id"] ); ?>', 'open', '<?php _e( 'show color picker', $this->domain ); ?>', '<?php _e( 'hide color picker', $this->domain ); ?>')"><?php _e( 'show color picker', $this->domain ); ?></a></label>
										<div id="<?php echo esc_attr( $this->options[$i]["id"] ); ?>_colorpicker" class="colorpicker_container"></div>
										<input id="<?php echo esc_attr( $this->options[$i]["id"] ); ?>" type="text" name="<?php echo esc_attr( $this->options[$i]["id"] ); ?>" value="<?php
											$id = get_option($this->options[$i]["id"]);
											echo esc_attr( $id ? $id : $this->options[$i]["std"] ); ?>" />
									</div><!--end ttf-field colorpicker-->
									<?php
								break;

								case "select":
									?>
									<div class="ttf-field select clear">
										<div class="ttf-field-d"><?php echo $this->options[$i]["desc"]?></div>
										<label for="<?php echo esc_attr( $this->options[$i]["id"] ); ?>"><?php echo $this->options[$i]["name"]; ?></label>
										<select id="<?php echo esc_attr( $this->options[$i]["id"] ); ?>" name="<?php echo esc_attr( $this->options[$i]["id"] ); ?>">
											<?php
												foreach ( (array) $this->options[$i]["options"] as $key => $val) :
													if ( '' == get_option($this->options[$i]["id"]) || is_null( get_option($this->options[$i]["id"] ) ) ) : ?>
														<option value="<?php echo $key; ?>"<?php echo ($key == $this->options[$i]['std']) ? ' selected="selected"' : ''; ?>><?php echo $val; ?></option>
													<?php else : ?>
														<option value="<?php echo $key; ?>"<?php echo get_option($this->options[$i]["id"]) == $key ? ' selected="selected"' : ''; ?>><?php echo $val; ?></option>
													<?php endif;
												endforeach;
											?>
										</select>
									</div><!--end ttf-field select-->
									<?php
								break;

								case "textarea":
									?>
									<div class="ttf-field textarea clear">
										<div class="ttf-field-d"><?php echo $this->options[$i]["desc"]?></div>
										<label for="<?php echo esc_attr( $this->options[$i]["id"] ); ?>"><?php echo $this->options[$i]["name"]?></label>
										<textarea id="<?php echo esc_attr( $this->options[$i]["id"] ); ?>" name="<?php echo esc_attr( $this->options[$i]["id"] ); ?>" <?php
											echo ( $this->options[$i]["options"] ? ' rows="' . $this->options[$i]["options"]["rows"] . '" cols="' . $this->options[$i]["options"]["cols"] . '"' : '' );
										?>><?php
											echo htmlspecialchars(
												'' != get_option($this->options[$i]['id']) ? get_option($this->options[$i]['id']) : $this->options[$i]['std'],
												ENT_QUOTES
											);
										?></textarea>
									</div><!--end ttf-options-ttf-field textarea-->
									<?php
								break;
							endswitch;
						endfor;
						?>
					<div class="ttf-save-button submit">
						<input class="button-primary" type="submit" value="<?php _e( 'Save changes', $this->domain ); ?>" name="save"/>
					</div><!--end ttf-save-button-->
				</div>
			</div>
			<div class="ttf-saveall-button submit">
				<input class="button-primary" type="submit" value="<?php esc_attr_e( 'Save all changes', $this->domain ); ?>" name="save" />
			</div>
		</form>

		<div class="ttf-reset-button submit">
			<form method="post" action="">
				<?php wp_nonce_field( 'reset-theme-options', 'reset-theme-options-nonce' ); ?>
				<input type="hidden" name="action" value="reset" />
				<input class="ttf-reset" type="submit" value="<?php esc_attr_e( 'Reset all options', $this->domain ); ?>" name="reset" />
			</form>
		</div>

		<script type="text/javascript">
			<?php
			for ($i = 0; $i < count($this->options); $i++) :
				if ($this->options[$i]['type'] == 'colorpicker') :
					?>
					jQuery("#<?php echo esc_js( $this->options[$i]["id"] ); ?>_colorpicker").farbtastic("#<?php echo esc_js( $this->options[$i]["id"] ); ?>");
					<?php
				endif;
			endfor;
			?>
			jQuery( '.colorpicker_container' ).hide();
		</script>
	</div><!--end ttf-options-body-->
</div><!--end wrap-->
<?php
		}

		function statsCode() {
			echo stripslashes(get_option($this->shortname.'_stats_code' ));
		}

	}
}
?>