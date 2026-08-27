<?php
$wordle = wordle_get_target_word();
$plugin_file = dirname( __DIR__ ) . '/wordle.php';
?>
<!DOCTYPE html>
<html <?php wp_app_language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo esc_html( wp_app_title( 'Wordle' ) ); ?></title>
	<link rel="stylesheet" href="<?php echo esc_url( plugins_url( 'styles.css', $plugin_file ) . '?' . filemtime( dirname( __DIR__ ) . '/styles.css' ) ); ?>">
	<?php if ( $wordle ) : ?>
		<meta name="wordle-target" content="<?php echo esc_attr( $wordle->solution ); ?>">
		<meta name="wordle-meta" content="<?php echo esc_attr( number_format( $wordle->days_since_launch ) ); ?>">
	<?php endif; ?>
	<script src="<?php echo esc_url( plugins_url( 'letter-logic.js', $plugin_file ) . '?' . filemtime( dirname( __DIR__ ) . '/letter-logic.js' ) ); ?>"></script>
	<script src="<?php echo esc_url( plugins_url( 'script.js', $plugin_file ) . '?' . filemtime( dirname( __DIR__ ) . '/script.js' ) ); ?>" defer></script>
	<?php wp_app_head(); ?>
</head>
<body class="wordle-game-page">
	<?php wp_app_body_open(); ?>
	<nav class="app-nav" aria-label="<?php echo esc_attr__( 'Wordle navigation', 'wordle' ); ?>">
		<a href="<?php echo esc_url( home_url( '/wordle/' ) ); ?>" aria-current="page"><?php esc_html_e( 'Game', 'wordle' ); ?></a>
		<a href="<?php echo esc_url( home_url( '/wordle/dictionary/' ) ); ?>"><?php esc_html_e( 'Dictionary', 'wordle' ); ?></a>
	</nav>
	<?php require dirname( __DIR__ ) . '/index.html'; ?>
	<?php wp_app_body_close(); ?>
</body>
</html>
