<?php
/*
Plugin Name: Wordle Plugin
Description: Add a Wordle to the site under /wordle/
Tested up to: 7.1
*/

use WpApp\WpApp;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

$wordle_app = new WpApp(
	__DIR__ . '/templates',
	'wordle',
	array(
		'app_name'     => 'Wordle',
		'my_apps_icon' => plugins_url( 'logo.png', __FILE__ ),
	)
);
$wordle_app->route( 'dictionary' );
$wordle_app->add_menu_item( 'dictionary', 'Dictionary', home_url( '/wordle/dictionary/' ) );
$wordle_app->init();

register_activation_hook(
	__FILE__,
	function () use ( $wordle_app ) {
		$wordle_app->router()->flush_rules();
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
