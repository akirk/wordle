<?php
/*
Plugin Name: Wordle Plugin
Description: Add a Wordle to the site under /wordle/
*/

// Hook into WordPress
add_action( 'init', 'wordle_rewrite_rule' );

// Add rewrite rule to show Wordle when /wordle/ is accessed
function wordle_rewrite_rule() {
	add_rewrite_rule( '^wordle/?$', 'index.php?wordle=true', 'top' );
}

// Add query var for custom endpoint
function wordle_query_vars( $query_vars ) {
	$query_vars[] = 'wordle';
	return $query_vars;
}
add_filter( 'query_vars', 'wordle_query_vars' );

add_filter(
	'my_apps_plugins',
	function ( $apps ) {
		$apps['wordle'] = array(
			'name'     => 'Wordle',
			'icon_url' => plugins_url( 'logo.png', __FILE__ ),
			'url'      => home_url( '/wordle/' ),
		);
		return $apps;
	}
);

function wordle_get_target_word() {
	$date = date( 'Y-m-d' );
	$transient_key = 'wordle_target_word' . $date;
	$data = get_transient( $transient_key );
	if ( ! $data ) {
		// Let's not spill the beans on the host name.
		$host = implode( '', array_reverse( str_split( 'semi' . str_rot13( 'gla' ) ) ) );

		$url = 'https://www.' . $host . '.com/svc/wordle/v2/' . $date . '.json';
		$response = wp_remote_get( $url );

		if ( ! is_wp_error( $response ) ) {
			$data = json_decode( wp_remote_retrieve_body( $response ) );
		}
		if ( $data ) {
			set_transient( $transient_key, $data, DAY_IN_SECONDS );
		} else {
			set_transient( $transient_key, 'no_result', MINUTE_IN_SECONDS );
			return false;
		}
	}
	if ( $data === 'no_result' ) {
		return false;
	}
	return $data;
}

		// Load Wordle content if query var is present
function wordle_custom_content() {
	$wordle = get_query_var( 'wordle' );


	if ( $wordle ) {
		$wordle = wordle_get_target_word();
		?><!DOCTYPE html>
<html lang="en">

<head>
		<?php wp_head(); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?php echo esc_attr( plugins_url( 'styles.css', __FILE__ ) . '?' . filemtime( __DIR__ . '/styles.css' ) ); ?>">
	<?php if ( $wordle ) : ?>
		<meta name="wordle-target" content="<?php echo esc_attr( $wordle->solution ); ?>">
		<meta name="wordle-meta" content="<?php echo esc_attr( number_format( $wordle->days_since_launch ) ); ?>">
	<?php endif; ?>
	<script src="<?php echo esc_attr( plugins_url( 'letter-logic.js', __FILE__ ) . '?' . filemtime( __DIR__ . '/letter-logic.js' ) ); ?>"></script>
	<script src="<?php echo esc_attr( plugins_url( 'script.js', __FILE__ ) . '?' . filemtime( __DIR__ . '/script.js' ) ); ?>" defer></script>
	<title>Wordle Clone</title>
</head>

<body>
		<?php
		readfile( __DIR__ . '/index.html' );
		wp_footer();

		exit;
	}
}
		add_action( 'template_redirect', 'wordle_custom_content' );
