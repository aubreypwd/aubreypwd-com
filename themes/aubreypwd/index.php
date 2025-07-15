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

			/* Critcal & Mobile CSS */

			/*! modern-normalize v3.0.1 | MIT License | https://github.com/sindresorhus/modern-normalize */
			progress,sub,sup{vertical-align:baseline}*,::after,::before{box-sizing:border-box}html{font-family:system-ui,'Segoe UI',Roboto,Helvetica,Arial,sans-serif,'Apple Color Emoji','Segoe UI Emoji';line-height:1.15;-webkit-text-size-adjust:100%;tab-size:4}body{margin:0}b,strong{font-weight:bolder}code,kbd,pre,samp{font-family:ui-monospace,SFMono-Regular,Consolas,'Liberation Mono',Menlo,monospace;font-size:1em}small{font-size:80%}sub,sup{font-size:75%;line-height:0;position:relative}sub{bottom:-.25em}sup{top:-.5em}table{border-color:currentcolor}button,input,optgroup,select,textarea{font-family:inherit;font-size:100%;line-height:1.15;margin:0}[type=button],[type=reset],[type=submit],button{-webkit-appearance:button}legend{padding:0}::-webkit-inner-spin-button,::-webkit-outer-spin-button{height:auto}[type=search]{-webkit-appearance:textfield;outline-offset:-2px}::-webkit-search-decoration{-webkit-appearance:none}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}summary{display:list-item}

			/* Variables */
			:root {

				/* Colors */
				--color-charchol: #222222;
				--color-jet: #2D2D2D;
				--color-white: #ffffff;
				--color-black: #000000;
				--color-white-ash: #919191;
				--color-electric-blue: #198CFF;
				--color-electric-purple: #ED52ED;
				--color-semi-space: #131313;

				/* Spacing */
				--padding-main-x: 15px;
				--padding-main-y: 15px;
			}

			html {
				background-color: #1A1A1A;
			}

			body {

				background-color: var(--color-charchol);
				color: var(--color-white);
				font-family: Arial, sans-serif;
				font-size: 14px;
				line-height: 20px;

				a:link {
					color: var(--color-electric-blue);
				}

				a:visited {
					color: var(--color-electric-purple);
				}

				/* Header & Footer */
				> header,
				> footer {

					> nav {

						white-space: nowrap;
						overflow: auto;
						scrollbar-width: none;
						-ms-scroll-style: none;
						border-bottom: 1px solid var(--color-jet);

						&::-webkit-scrollbar {
							display: none;
						}

						ul {

							display: flex;
							list-style: none;
							padding: 0;
							margin: 0;

							li {

								margin: 0 var(--padding-main-y) 0 0;
								padding: var(--padding-main-y) var(--padding-main-x);
								border-top: 2px solid transparent;
								border-bottom: 2px solid transparent;

								a:link,
								a:visited {

									color: var(--color-white);
									text-decoration: none;
									font-weight: bold;
								}
							}
						}
					}
				}

				/* Just the Header */
				> header {

					> nav {

						li {
							border-bottom: 2px solid transparent;
						}

						li.current-menu-item {
							border-bottom: 2px solid var(--color-electric-blue);
						}
					}

					.profile {

						border-top: 1px solid var(--color-semi-space);
						border-bottom: 1px solid var(--color-jet);
						display: flex;
						align-items: center;
						align-content: center;
						justify-content: center;
						padding: var(--padding-main-y) var(--padding-main-x);
						gap: calc( var(--padding-main-x) / 2 );

						img {
							border-radius: 10px;
						}

						.information h1,
						.information p {
							padding: 0 0 0 var(--padding-main-y);
						}

						.information h1 {
							font-size: 15px;
							margin: 0;
						}

						.information p {
							color: var(--color-white-ash);
						}
					}
				}

				/* Just the Footer */
				> footer {

					border-top: 1px solid var(--color-semi-space);
					position: relative;
					z-index: 9;

					> nav {

						border-bottom: 1px solid var(--color-semi-space);
						background-color: var(--color-jet);

						li {
							border-top: 2px solid transparent;
						}

						li.current-menu-item {
							border-top: 2px solid var(--color-electric-blue);
						}
					}

					.copyright {

						padding: var(--padding-main-y) var(--padding-main-x);
						text-align: center;
						color: var(--color-white-ash);
					}
				}

				/* Only on the main blog page */
				&.blog {

					article {
						border-bottom: 1px solid var(--color-jet);
					}
				}

				&.single-post,
				&.blog {

					> header {

						ul li:first-child {
							border-bottom: 2px solid var(--color-electric-blue);
						}
					}
				}

				main {

					border-top: 1px solid var(--color-semi-space);
					border-bottom: 1px solid var(--color-jet);
					position: relative;

					&::before {

						background: linear-gradient(to bottom, rgba(26,26,26,1) 0%,rgba(0,0,0,0) 100%);
						content: " ";
						display: block;
						height: 120px;
						width: 100%;
						position: absolute;
						top: 0;
						left: 0;
						right: 0;
						z-index: 8;
					}

					> .content {

						position: relative;
						z-index: 9;

						/* Main Post & Pages */
						article {

							padding: var(--padding-main-y) var(--padding-main-x);

							*:last-child {
								margin-bottom: 0;
							}

							> header {

								a:link,
								a:visited {
									color: var(--color-white);
									text-decoration: none;
								}

								h1 {
									margin-top: 0;
								}
							}

							> footer {
								padding-top: var(--padding-main-y);
							}

							img,
							picture {
								max-width: 100%;
								height: auto;
								border-radius: 5px;
							}
						}

						.date {
							color: var(--color-white-ash);
						}

						.article-link {

							display: flex;
							justify-content: space-between;
							align-items: center;
							padding: calc( var(--padding-main-y) ) var(--padding-main-x);
							border-top: 1px solid var(--color-semi-space);
							border-bottom: 1px solid var(--color-jet);
						}

						.article-link:last-child {
							border-bottom: none;
						}
					}
				}
			}
		</style>

		<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" media="(min-width: 840px)">

		<!-- Fetch gravatar early -->
		<link rel="preload" as="image" type="image/webp" fetchpriority="high" href="<?php echo esc_attr( my_gravatar( 93 ) ); ?>">

		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
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

				<div class="information">
					<h1>Aubrey Portwood</h1>
					<p><?php bloginfo( 'description' ); ?></p>
				</div>
			</div>
		</header>

		<main>
			<div class="content">

				<?php if ( is_home() ) : ?>
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

									<footer>
										<p class="date">
											<?php the_date(); ?>
										</p>
									</footer>
								</article>

							<?php else : ?>
								<div class="article-link">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									<span class="date"><?php the_date(); ?></span>
								</div>
							<?php endif; ?>
						<?php endwhile; ?>
					<?php endif; ?>
				<?php else : ?>
					<article>
						<header>
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
				<small><?php esc_html_e( 'Copyright 2025 &mdash; Aubrey Portwood', 'aubreypwd' ); ?></small>
			</div>
		</footer>
	</body>

	<?php wp_footer(); ?>
</html>
