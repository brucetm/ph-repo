<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package BuddyBoss_Theme
 */
global $wp_query;

get_header();


$view = get_option( 'bb_theme_learndash_grid_list', 'grid' );

?>
	<div id="primary" class="content-area">
		<div class="flex align-items-center bb-courses-header">
		<h4 class="bb-title"><?php echo LearnDash_Custom_Label::get_label( 'courses' ); ?></h4>
		<script> 
            document.title = 'projects-playhacker!'; 
         </script> 
		</div>
		<?php echo do_shortcode('[searchandfilter id="29869"]');?>
		<main id="main" class="site-main">
			
			<div id="learndash-content" class="learndash-course-list">
				<div id="bb-courses-directory-form" class="bb-courses-directory">
					<?php $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; ?>
					<div class="flex align-items-center bb-courses-header">
					<input type="hidden" name="current_page" value="<?php echo esc_attr( $paged ); ?>" >
				    </div>
						<nav class="courses-type-navs main-navs bp-navs dir-navs bp-subnavs">
						<ul class="component-navigation courses-nav">
							<?php
							$navs = array(
								'all' => __( 'All', 'buddyboss-theme' ) . ' ' . LearnDash_Custom_Label::get_label( 'courses' ) . '<span class="count">' . buddyboss_theme()->learndash_helper()->get_all_courses_count() . '</span>',
							);

							if ( is_user_logged_in() ) {
								$navs['my-courses'] = __( 'My', 'buddyboss-theme' ) . ' ' . LearnDash_Custom_Label::get_label( 'courses' ) . '<span class="count">' . buddyboss_theme()->learndash_helper()->get_my_courses_count() . '</span>';
							}

							$navs = apply_filters( 'BuddyBossTheme/Learndash/Archive/Navs', $navs );

							if ( ! empty( $navs ) ) {
								$current_nav = isset( $_GET['type'] ) && isset( $navs[ $_GET['type'] ] ) ? $_GET['type'] : 'all';
								$base_url    = get_post_type_archive_link( 'sfwd-courses' );
								foreach ( $navs as $nav => $text ) {
									$selected_class = $nav == $current_nav ? 'selected' : '';
									$url            = 'all' != $nav ? add_query_arg( array( 'type' => $nav ), $base_url ) : $base_url;
									printf( "<li id='courses-{$nav}' class='{$selected_class}'><a href='%s'>%s</a></li>", $url, $text );
								}
							} else {
								$current_nav = 'all';
							}
							?>
						</ul>
					</nav>
					
					<div class="ld-secondary-header">
						<div class="bb-secondary-list-tabs flex align-items-center" id="subnav" aria-label="Members directory secondary navigation" role="navigation">
							<div class="grid-filters push-right" data-view="ld-course">
								<a href="#" class="layout-view layout-grid-view bp-tooltip <?php echo ( 'grid' === $view ) ? esc_attr( 'active' ) : ''; ?>" data-view="grid" data-bp-tooltip-pos="up" data-bp-tooltip="<?php _e( 'Grid View', 'buddyboss-theme' ); ?>">
									<i class="dashicons dashicons-screenoptions" aria-hidden="true"></i>
								</a>

								<a href="#" class="layout-view layout-list-view bp-tooltip <?php echo ( 'list' === $view ) ? esc_attr( 'active' ) : ''; ?>" data-view="list" data-bp-tooltip-pos="up" data-bp-tooltip="<?php _e( 'List View', 'buddyboss-theme' ); ?>">
									<i class="dashicons dashicons-menu" aria-hidden="true"></i>
								</a>
							</div>

					</div>

					<div class="grid-view bb-grid">

						<div id="course-dir-list" class="course-dir-list bs-dir-list">
							<?php
							if ( have_posts() ) {
								?>
								<ul class="bb-card-list bb-course-items list-view bb-list <?php echo ( 'list' === $view ) ? '' : esc_attr( 'hide' ); ?>" aria-live="assertive" aria-relevant="all">
									<?php
									/* Start the Loop */
									while ( have_posts() ) :
										the_post();

										/*
										 * Include the Post-Format-specific template for the content.
										 * If you want to override this in a child theme, then include a file
										 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
										 */
										get_template_part( 'learndash/ld30/template-course-item' );

									endwhile;
									?>
								</ul>

								<ul class="bb-card-list bb-course-items grid-view bb-grid <?php echo ( 'grid' === $view ) ? '' : esc_attr( 'hide' ); ?>" aria-live="assertive" aria-relevant="all">
									<?php
									/* Start the Loop */
									while ( have_posts() ) :
										the_post();

										/*
										 * Include the Post-Format-specific template for the content.
										 * If you want to override this in a child theme, then include a file
										 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
										 */
										get_template_part( 'learndash/ld30/template-course-item' );

									endwhile;
									?>
								</ul>

								<div class="bb-lms-pagination">
								<?php
									global $wp_query;
									$big        = 999999999; // need an unlikely integer
									$translated = __( 'Page', 'buddyboss-theme' ); // Supply translatable string

									echo paginate_links(
										array(
											'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
											'format'  => '?paged=%#%',
											'current' => max( 1, get_query_var( 'paged' ) ),
											'total'   => $wp_query->max_num_pages,
											'before_page_number' => '<span class="screen-reader-text">' . $translated . ' </span>',
										)
									);
								?>
									</div>
									<?php
							} else {
								?>
								<aside class="bp-feedback bp-template-notice ld-feedback info">
									<span class="bp-icon" aria-hidden="true"></span>
									<p><?php _e( 'Sorry, no courses were found.', 'buddyboss-theme' ); ?></p>
								</aside>
								<?php
							}
							?>
						</div>
					</div>
				</div>

			</div>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php get_sidebar( 'learndash' ); ?>

<?php
get_footer();
