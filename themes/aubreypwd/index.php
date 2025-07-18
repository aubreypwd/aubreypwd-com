<?php namespace aubreypwd\theme; ?><!DOCTYPE html>
<html <?php language_attributes(); ?>>

	<!--

	June 16th 2025:

	I wanted a blog—fast, simple, and fundemental.
	For years, work got in the way. I would never do my blog the way I wanted.
	I stepped away from a job in April and finally built it.

	It was easier than I thought, learning a new value made it possible:

		| Stay close to the fundementals

	No framework. No AI. No vibe coding. No pipeline. Just seven files, a few
	hundred lines of code, and two days of focused work uploaded via FTP.

	WordPress and the Web don't have to be a bloated mess. Because performance
	isn’t in boilerplate or React frontends. We forgot the fundamentals, I did.

	This blog is my proof: simplicity still wins.

	It’s the leanest WordPress blog I’ve ever made.

	And I’m proud of it.

		| Code is Poetry

	-->

	<head>

		<link rel="preconnect" href="<?php echo esc_attr( my_avatar( 177 ) ); ?>">
		<link rel="preconnect" href="https://ik.imagekit.io">

		<title><?php bloginfo( 'name' ); ?>, <?php the_title(); ?></title>

		<!-- Meta -->
		<meta name="description" content="<?php bloginfo( 'description' ); ?>">
		<meta name="author" content="Aubrey Portwood">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="<?php bloginfo( 'charset' ); ?>">

		<link rel="preload" as="image" type="image/webp" fetchpriority="high" href="<?php echo esc_attr( my_avatar( 177 ) ); ?>">

		<!-- Critical & Mobile CSS -->
		<style><?php echo file_get_contents( sprintf( '%s/critical.css', __DIR__ ) ); ?></style>

		<!-- Non-critical CSS -->
		<link rel="stylesheet" href="<?php echo sprintf( '%s/%s', get_stylesheet_directory_uri(), 'mid.css' ); ?>" media="(min-width: 600px)">
		<link rel="stylesheet" href="<?php echo sprintf( '%s/%s', get_stylesheet_directory_uri(), 'max.css' ); ?>" media="(min-width: 900px)" fetchpriority="low">

		<link rel="icon" href="<?php echo my_avatar( 32 ); ?>" type="image/x-icon" fetchpriority="low">

		<!-- All the shit WordPress puts on the page -->
		<?php wp_head(); ?>

	</head>

	<body <?php body_class(); ?>>

		<header role="banner">

			<?php

			wp_nav_menu(
				[
					'theme_location' => 'aubreypwd/header',
					'container'      => 'nav',
				]
			);

			?>

			<div class="profile">

				<!-- Gravatar, should be preloaded -->
				<img
					src="<?php echo esc_attr( my_avatar( 177 ) ); ?>"
					alt="<?php echo esc_attr__( 'Aubrey Portwood', 'aubreypwd' ); ?>"
					fetchpriority="high"
					referrerpolicy="no-referrer">

				<div class="information">

					<h1>Aubrey Portwood <span>&mdash; <?php bloginfo( 'name' ); ?></span></h1>
					<p><?php bloginfo( 'description' ); ?></p>
				</div>
			</div>

		</header>

		<main
			role="main"
			aria-label="<?php echo esc_attr__( 'Main content', 'aubreypwd' ); ?>">

			<div class="content">

				<?php if ( is_home() ) : ?>

					<!-- Blog page -->
					<?php if ( have_posts() ) : ?>
						<?php while ( have_posts() ) : the_post(); ?>

							<!-- Show latest post in full -->
							<?php if ( is_latest_post() ) : ?>

								<article>
									<header>
										<a href="<?php the_permalink(); ?>">
											<h1><?php the_title(); ?></h1>
										</a>
									</header>

									<?php the_content(); ?>

									<footer>
										<p class="date">
											<?php the_date(); ?>
										</p>
									</footer>
								</article>

							<?php else : ?>

								<!-- Show the other posts as links to their single page -->
								<div class="article-link">
									<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( get_the_excerpt() ); ?>"><?php the_title(); ?></a>
									<span class="date"><?php the_date(); ?></span>
								</div>
							<?php endif; ?>

						<?php endwhile; ?>
					<?php endif; ?>

				<?php else : ?>

					<!-- When not on the blog page, assume page or post single -->
					<article>

						<header>

							<a href="<?php the_permalink(); ?>">
								<h1><?php the_title(); ?></h1>
							</a>
						</header>

						<?php the_content(); ?>

						<?php if ( ! is_page() ) : ?>

							<footer>
								<p class="date">
									<?php echo get_the_date(); ?>
								</p>
							</footer>
						<?php endif; ?>
					</article>

				<?php endif; ?>
			</div>

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
				<?php esc_html_e( 'Copyright &copy; 2025 &nbsp; Aubrey Portwood', 'aubreypwd' ); ?>
			</div>
		</footer>

		<!-- All the shit WordPress puts in the footer -->
		<?php wp_footer(); ?>
	</body>
</html>
