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
			}

			html,
			body {

				color: var(--color-white);
				font-family: Arial, sans-serif;
				font-size: 14px;

				a:link {
					color: var(--color-electric-blue);
				}

				a:visited {
					color: var(--color-electric-purple);
				}

				> header,
				> footer {

					> nav {

						white-space: nowrap;
						overflow: auto;
						scrollbar-width: none;
						-ms-scroll-style: none;
						border-bottom: 1px solid var(--color-jet);

						::-webkit-scrollbar {
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

				> header {

					> nav {

						li {
							border-bottom: 2px solid transparent;
						}

						li.current-menu-item {
							border-bottom: 2px solid var(--color-electric-blue);
						}
					}
				}

				> footer {

					border-top: 1px solid var(--color-semi-space);
					background-color: var(--color-jet);
					position: relative;
					z-index: 9;

					> nav {

						border-bottom: 1px solid var(--color-semi-space);

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
						font-size: 12px;
						background: var(--color-charchol);
					}
				}

				&.home {

					> header {
						ul li:first-child {
							border-bottom: 2px solid var(--color-electric-blue);
						}
					}
				}

				.profile {

					border-top: 1px solid var(--color-semi-space);
					border-bottom: 1px solid var(--color-jet);
					display: flex;
					align-items: center;
					padding: var(--padding-main-y) var(--padding-main-x);

					img {
						border-radius: 10px;
					}

					p {
						padding: 0 0 0 var(--padding-main-y);
						color: var(--color-white-ash);
						font-size: 13px;
					}
				}

				main {

					border-top: 1px solid var(--color-semi-space);
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
						padding-bottom: var(--padding-main-y);

						article {

							padding: var(--padding-main-y) var(--padding-main-x);
							border-bottom: 1px solid var(--color-jet);

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
						}

						.more-posts {

							padding: var(--padding-main-y) var(--padding-main-x);
							font-weight: bold;
							color: var(--color-white-ash);
							border-top: 1px solid var(--color-semi-space);
							/*border-bottom: 1px solid var(--color-jet);*/
							/*background-color: var(--color-jet);*/
							/*text-align: center;*/
						}

						.article-link {

							display: block;
							padding: calc( var(--padding-main-y) / 2 ) var(--padding-main-x);
							/*border-bottom: 1px solid var(--color-jet);*/
							/*border-top: 1px solid var(--color-semi-space);*/
						}
					}
				}
			}
		</style>

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

				<p>
					<?php bloginfo( 'description' ); ?>
				</p>
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
								</article>

								<div class="more-posts"><?php echo __( 'Recent Posts', 'aubreypwd' ); ?></div>

							<?php else : ?>
								<a href="<?php the_permalink(); ?>" class="article-link"><?php the_title(); ?></a>
							<?php endif; ?>
						<?php endwhile; ?>
					<?php endif; ?>
				<?php else : ?>
					Single
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
				<?php esc_html_e( 'Copyright 2025 &mdash; Aubrey Portwood', 'aubreypwd' ); ?>
			</div>
		</footer>
	</body>

	<?php wp_footer(); ?>
</html>
