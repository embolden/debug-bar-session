<?php
/*
 Plugin Name: Debug Bar Session
 Description: Display $_SESSION information in Debug Bar
 Author: Matt Keehner
 Version: 0.0.1
 */

add_filter( 'debug_bar_panels', 'dbs_init' );
add_action( 'wp_enqueue_scripts', 'dbs_scripts_styles' );
add_action( 'admin_enqueue_scripts', 'dbs_scripts_styles' );
add_action( 'wp_ajax_dbs_remove_session_item', 'dbs_remove_session_item' );
add_action( 'wp_ajax_nopriv_dbs_remove_session_item', 'dbs_remove_session_item' );
add_action( 'wp_ajax_dbs_remove_all_session', 'dbs_remove_all_session' );
add_action( 'wp_ajax_nopriv_dbs_remove_all_session', 'dbs_remove_all_session' );

/**
 * [dbs_init description]
 * @param  [type] $panels [description]
 * @return [type]         [description]
 */
function dbs_init( $panels ) {
	/**
	 * [Debug_Bar_Session description]
	 */
	class Debug_Bar_Session extends Debug_Bar_Panel {
		/**
		 * [init description]
		 * @return [type] [description]
		 */
		public function init() {
			$this->title( 'Sessions' );
		}

	/**
	 * [render description]
	 * @return [type] [description]
	 */
		public function render() {
			ob_start();
			?>
			<ul id="dbs-list">
				<?php foreach( $_SESSION as $key => $value ) : ?>
				<li>
					<p><strong><?php echo $key; ?></strong> - <span><?php echo $value; ?></span> <span data-dbs-key="<?php echo $key; ?>" class="dbs-remove">x</span></p>
				</li>
				<?php endforeach; ?>
			</ul>
			<button type="button" class="button" id="clear-all-session">Clear All</button>
			<?php
			ob_end_flush();
		}
	}

	$panels[] = new Debug_Bar_Session();

	return $panels;
}

/**
 * [dbs_scripts_styles description]
 * @return [type] [description]
 */
function dbs_scripts_styles() {
	$ajax_helper = [
		'nonce' => wp_create_nonce( 'dbs-ajax-nonce' ),
	];

	wp_enqueue_style( 'debug-bar-session-style', plugins_url( 'debug-bar-session-style.css', __FILE__) );
	wp_enqueue_script( 'debug-bar-session-script', plugins_url( 'debug-bar-session-script.js', __FILE__ ), array( 'jquery' ) );
	wp_localize_script( 'debug-bar-session-script', 'ajax_helper', $ajax_helper );
}

/**
 * [dbs_remove_session_item description]
 * @return [type] [description]
 */
function dbs_remove_session_item() {
	if( ! isset( $_REQUEST['nonce'] ) || ! check_ajax_referer( 'dbs-ajax-nonce', 'nonce', false ) ) {
		wp_send_json_error( array(
			'message' => 'Your access token is invalid.'
		) );
	}

	if( empty( $_REQUEST['key'] ) ) {
		wp_send_json_error( array(
			'message' => $_REQUEST['key'] . ' is required.',
		) );
	}

	session_unset( $_REQUEST['key'] );

	wp_send_json_success([
		'removed' => $_REQUEST['key'],
	]);
}

/**
 * [dbs_remove_all_session description]
 * @return [type] [description]
 */
function dbs_remove_all_session() {
	if( ! isset( $_REQUEST['nonce'] ) || ! check_ajax_referer( 'dbs-ajax-nonce', 'nonce', false ) ) {
		wp_send_json_error( array(
			'message' => 'Your access token is invalid.'
		) );
	}

	session_unset();

	wp_send_json_success();
}
