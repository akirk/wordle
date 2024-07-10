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
	$transient_key = 'wordle_target_word';
	$word = get_transient( $transient_key );
	$word = 'gaunt';
	if ( ! $word ) {
		// Let's not spill the beans on the host name.
		$host = implode( '', array_reverse( str_split( 'semi' . str_rot13( 'gla' ) ) ) );

		$url = 'https://www.' . $host . '.com/svc/wordle/v2/' . date( 'Y-m-d' ) . '.json';
		$response = wp_remote_get( $url );

		if ( ! is_wp_error( $response ) ) {
			$data = json_decode( wp_remote_retrieve_body( $response ) );
			if ( $data ) {
				$word = $data->solution;
			}
		}
		if ( $word ) {
			set_transient( $transient_key, $word, DAY_IN_SECONDS );
		} else {
			set_transient( $transient_key, 'no_result', MINUTE_IN_SECONDS );
			return false;
		}
	}
	if ( $word === 'no_result' ) {
		return false;
	}
	return $word;
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
	<link rel="stylesheet" href="<?php echo esc_attr( plugins_url( 'styles.css', __FILE__ ) ); ?>">
	<?php if ( $wordle ) : ?>
		<meta name="wordle-target" content="<?php echo esc_attr( $wordle ); ?>">
	<?php endif; ?>
	<script src="<?php echo esc_attr( plugins_url( 'script.js', __FILE__ ) ); ?>" defer></script>
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
