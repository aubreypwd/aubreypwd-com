<?php

namespace aubreypwd\theme;

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

	<head>
		<title><?php bloginfo( 'name' ); ?></title>

		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width,initial-scale=1.0">

		<style>

			/* Critcal CSS */

			/*! modern-normalize v3.0.1 | MIT License | https://github.com/sindresorhus/modern-normalize */
			progress,sub,sup{vertical-align:baseline}*,::after,::before{box-sizing:border-box}html{font-family:system-ui,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji';line-height:1.15;-webkit-text-size-adjust:100%;tab-size:4}body{margin:0}b,strong{font-weight:bolder}code,kbd,pre,samp{font-family:ui-monospace,SFMono-Regular,Consolas,'Liberation Mono',Menlo,monospace;font-size:1em}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative}sub{bottom:-.25em}sup{top:-.5em}table{border-color:currentcolor}button,input,optgroup,select,textarea{font-family:inherit;font-size:100%;line-height:1.15;margin:0}[type=button],[type=reset],[type=submit],button{-webkit-appearance:button}legend{padding:0}::-webkit-inner-spin-button,::-webkit-outer-spin-button{height:auto}[type=search]{-webkit-appearance:textfield;outline-offset:-2px}::-webkit-search-decoration{-webkit-appearance:none}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}summary{display:list-item}

			html,
			body {
				background-color: #111111;
				color: #ffffff;
			}

		</style>

		<!-- Fetch gravatar early -->
		<link rel="preload" as="image" type="image/webp" fetchpriority="high" href="<?php echo esc_attr( my_gravatar( 93 ) ); ?>">

		<?php wp_head(); ?>
	</head>

	<body>
		<header>
			<?php

			wp_nav_menu(
				[
					'theme_location' => 'aubreypwd/header',
					'container'      => 'nav',
				]
			);

			?>

			<div class="profile">

				<img src="<?php echo esc_attr( my_gravatar( 93 ) ); ?>" alt="<?php esc_attr_e( 'Aubrey Portwood', 'aubreypwd' ); ?>">

				<p>
					<?php bloginfo( 'description' ); ?>
				</p>
			</div>
		</header>

		<main>
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>

					<?php if ( is_latest_post() ) : ?>
						<article>
							<header>
								<a href="<?php the_permalink(); ?>">
									<h1><?php the_title(); ?></h1>
								</a>
							</header>

							<?php the_content(); ?>
						</article>
					<?php else : ?>
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					<?php endif; ?>
				<?php endwhile; ?>
			<?php endif; ?>
		</main>

		<footer>
			<?php

			wp_nav_menu(
				[
					'theme_location' => 'aubreypwd/footer',
					'container'      => 'nav',
				]
			);

			?>

			<div class="copyright">
				<?php esc_html_e( 'Copyright 2025 &mdash; Aubrey Portwood', 'aubreypwd' ); ?>
			</div>
		</footer>
	</body>

	<?php wp_footer(); ?>
</html>
