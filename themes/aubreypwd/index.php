<?php // phpcs:disable

namespace aubreypwd\theme;

?><!DOCTYPE html>
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

		<script type="speculationrules">
			{
				"prerender": [
					{
						"source": "document",
						"where": {
							"and": [
								{
									"selector_matches": "a[href]"
								},
								{
									"not": {
										"selector_matches": "a[href^=\"/wp-\"]"
									}
								}
							]
						},
						"eagerness": "moderate"
					}
				]
			}
		</script>

		<!-- Preconnect -->
		<link rel="preconnect" href="<?php echo esc_attr( my_avatar( 177 ) ); ?>">
		<link rel="preconnect" href="https://ik.imagekit.io">
		<link rel="preconnect" href="https://www.youtube-nocookie.com" fetchpriority="low">

		<title><?php bloginfo( 'name' ); ?>, <?php the_title(); ?></title>

		<!-- Meta -->
		<meta name="description" content="<?php bloginfo( 'description' ); ?>">
		<meta name="author" content="Aubrey Portwood">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="<?php bloginfo( 'charset' ); ?>">

		<!-- Preload -->
		<link rel="preload" as="image" type="image/avif" fetchpriority="high" href="<?php echo esc_attr( my_avatar( 177 ) ); ?>">

		<!-- Fonts -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&display=swap" rel="stylesheet">

		<!-- Critical & Mobile CSS -->
		<style>
			<?php echo file_get_contents( sprintf( '%s/critical.css', __DIR__ ) ); ?>
		</style>

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

				<div class="information">
					<h1>Aubrey Portwood</h1>
					<p><?php bloginfo( 'description' ); ?></p>
				</div>

				<!-- Gravatar, should be preloaded -->
				<img
					src="<?php echo esc_attr( my_avatar( 177 ) ); ?>"
					alt="<?php echo esc_attr__( 'Aubrey Portwood', 'aubreypwd' ); ?>"
					fetchpriority="high"
					referrerpolicy="no-referrer">

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

										<?php if ( is_sticky() ) : ?>
											<span class="sticky"><?php esc_html_e( 'Pinned', 'aubreypwd' ); ?></span>
										<?php endif; ?>

										<a href="<?php the_permalink(); ?>">
											<h1><?php the_title(); ?></h1>
										</a>
									</header>

									<?php the_content(); ?>

									<footer>
										<p class="date">
											<?php echo get_the_date(); ?>
										</p>
									</footer>
								</article>

							<?php else : ?>

								<!-- Show the other posts as links to their single page -->
								<div class="article-link">
									<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( get_the_excerpt() ); ?>"><?php the_title(); ?></a>
									<span class="date"><?php echo get_the_date(); ?></span>
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

		<!-- Mozarts Ghost! -->
		<a href="https://www.youtube-nocookie.com/embed/aRFBc73tWNw?si=MEmwxPRaB5_4x-HT&start=64&autoplay=1&loop=1&end=94" target="_blank" class="mozarts-ghost">&#960;</span>

		<script>
				console.log(`
					 _   _      _ _
					| | | | ___| | | ___
					| |_| |/ _ \\ | |/ _ \\
					|  _  |  __/ | | (_) |
					|_| |_|\\___|_|_|\\___/

					My name is Aubrey Portwood, everywhere online @aubreypwd.

					I'm an ex-Senior WordPress Developer that has since turned to working on
					web performance. I've worked with large WordPress agencies, companies like
					Microsoft, Starbucks, and Kroger and built high-scaled WordPress plugins.
					I have over 18 years experience working in WordPress. Over 20 years working
					on the web.

					Check me out at:

					https://github.com/aubreypwd
					https://x.com/aubreypwd
					https://linkedin.com/in/aubreypwd

					Maybe you're curious about how my site works?

					It runs ClassicPress, with all the normal WordPress bells-N-whistles like
					Gutenberg, etc turned off and I write most of my content in Markdown. This site
					implements a lot of performance features such as AVIF images (CDN), view transitions,
					inlined critical CSS, etc. It was ground-zero for when I started learning more about
					web performance!

					You can learn more by visiting: https://github.com/aubreypwd/aubreypwd-com

					It also has a handfull of easter eggs that I hope will grow over time. :)
					Oh, and this site was "human-coded," never vibe coded.

					Thanks for visiting!
				`);
		</script>
	</body>
</html>
