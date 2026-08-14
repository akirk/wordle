<?php
$target_words = json_decode( file_get_contents( dirname( __DIR__ ) . '/targetWords.json' ), true );
$all_words    = json_decode( file_get_contents( dirname( __DIR__ ) . '/dictionary.json' ), true );
$plugin_file  = dirname( __DIR__ ) . '/wordle.php';

if ( ! is_array( $target_words ) ) {
	$target_words = array();
}

if ( ! is_array( $all_words ) ) {
	$all_words = array();
}
?>
<!DOCTYPE html>
<html <?php wp_app_language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo esc_html( wp_app_title( 'Wordle Dictionary' ) ); ?></title>
	<link rel="stylesheet" href="<?php echo esc_url( plugins_url( 'styles.css', $plugin_file ) . '?' . filemtime( dirname( __DIR__ ) . '/styles.css' ) ); ?>">
	<?php wp_app_head(); ?>
</head>
<body class="wordle-dictionary-page">
	<?php wp_app_body_open(); ?>
	<main class="dictionary-shell">
		<nav class="app-nav" aria-label="<?php echo esc_attr__( 'Wordle navigation', 'wordle' ); ?>">
			<a href="<?php echo esc_url( home_url( '/wordle/' ) ); ?>"><?php esc_html_e( 'Game', 'wordle' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/wordle/dictionary/' ) ); ?>" aria-current="page"><?php esc_html_e( 'Dictionary', 'wordle' ); ?></a>
		</nav>

		<section class="dictionary-panel">
			<div>
				<h1><?php esc_html_e( 'Wordle Dictionary', 'wordle' ); ?></h1>
				<p><?php esc_html_e( 'Filter the word list by known letters, excluded letters, and wildcard patterns.', 'wordle' ); ?></p>
			</div>

			<form class="dictionary-controls" data-dictionary-controls>
				<label>
					<span><?php esc_html_e( 'Word pattern', 'wordle' ); ?></span>
					<input type="text" name="pattern" maxlength="5" inputmode="text" autocomplete="off" placeholder="s.a.e" aria-describedby="pattern-help">
					<small id="pattern-help"><?php esc_html_e( 'Use . or ? for unknown letters.', 'wordle' ); ?></small>
				</label>

				<label>
					<span><?php esc_html_e( 'Must include', 'wordle' ); ?></span>
					<input type="text" name="include" maxlength="26" inputmode="text" autocomplete="off" placeholder="tr">
				</label>

				<label>
					<span><?php esc_html_e( 'Exclude', 'wordle' ); ?></span>
					<input type="text" name="exclude" maxlength="26" inputmode="text" autocomplete="off" placeholder="cloud">
				</label>

				<label>
					<span><?php esc_html_e( 'List', 'wordle' ); ?></span>
					<select name="source">
						<option value="answers"><?php esc_html_e( 'Likely answers', 'wordle' ); ?></option>
						<option value="valid"><?php esc_html_e( 'All valid guesses', 'wordle' ); ?></option>
					</select>
				</label>
			</form>
		</section>

		<section class="dictionary-results" aria-live="polite">
			<div class="dictionary-summary">
				<strong data-result-count>0</strong>
				<span><?php esc_html_e( 'matches', 'wordle' ); ?></span>
			</div>
			<div class="word-list" data-word-list></div>
		</section>
	</main>

	<script>
		window.wordleDictionaryData = <?php echo wp_json_encode( array( 'answers' => array_values( $target_words ), 'valid' => array_values( $all_words ) ) ); ?>;
	</script>
	<script src="<?php echo esc_url( plugins_url( 'dictionary.js', $plugin_file ) . '?' . filemtime( dirname( __DIR__ ) . '/dictionary.js' ) ); ?>" defer></script>
	<?php wp_app_body_close(); ?>
</body>
</html>
