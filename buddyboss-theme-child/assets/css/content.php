<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package BuddyBoss_Theme
 */
?>

<?php
global $post;
?>

<article id="post-<?php the_ID(); ?> d" <?php post_class(); ?>>

	<?php if ( !is_single() || is_related_posts() ) { ?>
		<div class="post-inner-wrap">
	<?php } ?>

	<?php
	if ( ( !is_single() || is_related_posts() ) && function_exists( 'buddyboss_theme_entry_header' ) ) {
		buddyboss_theme_entry_header( $post );
	}
	?>
	<div class="entry-content-wrap">

		<?php 
		$featured_img_style = buddyboss_theme_get_option( 'blog_featured_img' );

		if ( !empty( $featured_img_style ) && $featured_img_style == "full-fi-invert" ) {

			if ( is_single() && ! is_related_posts() ) {

				if ( has_post_thumbnail() ) {
                    $featured_img_url = get_the_post_thumbnail_url(get_the_ID(),'full');
					 /* Bruce's modified hero image code May 12'20 ****************/
					?>
                    <div class="hero-banner">
                    <style>
                        .hero-banner::before {
                            background-image: url('<?php echo $featured_img_url; ?>');
                        }
                    </style>
                    <?php /* Bruce's hero image code ****************/			
				}
			} ?>

			<?php
			if ( has_post_format( 'link' ) && ( !is_singular() || is_related_posts() ) ) {
				echo '<span class="post-format-icon"><i class="bb-icon-link-1"></i></span>';
			}

			if ( has_post_format( 'quote' ) && ( !is_singular() || is_related_posts() ) ) {
				echo '<span class="post-format-icon white"><i class="bb-icon-quote"></i></span>';
			}
			?>

            <?php if (!is_singular('lesson') && !is_singular('llms_assignment') ) : ?>

            <!--/Bruce added:-->
            <div class="entry-header-wrap">

            <header class="entry-header"><?php

				//This is the full blog post:
				if ( is_singular() && ! is_related_posts() ) :

					/* BEGIN Think post title system May 10'20 ************************************/

					if ( function_exists('yoast_breadcrumb') ) {
						yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
					}

					$post_id = get_the_id();
					$post_title = get_the_title();
					$post_categories = get_the_category($post_id);
					$first_category = $post_categories[0]->cat_name;
					$description = get_field( 'content_short_description', $post_id );

                    echo '<h1 class="entry-title playhacker-title">' . $first_category . ': ' . $post_title . '</h1>';
					echo '<p class="article-description">' . $description . '</p>';

					get_template_part( 'template-parts/entry-meta' );

					echo '<div class="article_bookmark">';
						echo the_favorites_button();
					echo '</div>';			
						
					/* END Think post title system ***************************************/

				else :
					$prefix = "";
					if( has_post_format( 'link' ) ){
						$prefix = __( '[Link]', 'buddyboss-theme' );
						$prefix .= " ";//whitespace
					}
					the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $prefix, '</a></h2>' );
				endif;

				if( has_post_format( 'link' ) && function_exists( 'buddyboss_theme_get_first_url_content' ) && ( $first_url = buddyboss_theme_get_first_url_content( $post->post_content ) ) != "" ) : ?>
					<p class="post-main-link"><?php echo $first_url;?></p>
				<?php endif; ?>

				</header><!-- .entry-header -->

            <?php endif; ?>

			<?php if ( !is_singular() || is_related_posts() ) { ?>
				<div class="entry-content">
				
				<!-- Bruce bookmark: here shows in post grid below title, 
				but not single post or related posts -->

					<?php
					if( empty($post->post_excerpt) ) {
						the_excerpt();
					} else {
						echo bb_get_excerpt($post->post_excerpt, 150);
					}
					?>
				</div>
			<?php } ?>

			<?php if ( ( isset($post->post_type) && $post->post_type === 'post' ) || ( ! has_post_format( 'quote' ) && is_singular( 'post' ) ) ) : ?>
				
				<!-- Bruce wrapped this so it doesn't show on full blog posts, because he added it into title area instead:  -->
				<?php if ( !is_singular() ) {
					get_template_part( 'template-parts/entry-meta' );
				} ?>
               
			<?php endif; ?>

		<?php } else { ?>

			<?php
			if ( has_post_format( 'link' ) && ( !is_singular() || is_related_posts() ) ) {
				echo '<span class="post-format-icon"><i class="bb-icon-link-1"></i></span>';
			}

			if ( has_post_format( 'quote' ) && ( !is_singular() || is_related_posts() ) ) {
				echo '<span class="post-format-icon white"><i class="bb-icon-quote"></i></span>';
			}
			?>

			<header class="entry-header">
				<?php
				if ( is_singular() && ! is_related_posts() ) :
					the_title( '<h1 class="entry-title">', '</h1>' );
				else :
					$prefix = "";
					if( has_post_format( 'link' ) ){
						$prefix = __( '[Link]', 'buddyboss-theme' );
						$prefix .= " ";//whitespace
					}
					the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $prefix, '</a></h2>' );
				endif;
				?>

				<?php if( has_post_format( 'link' ) && function_exists( 'buddyboss_theme_get_first_url_content' ) && ( $first_url = buddyboss_theme_get_first_url_content( $post->post_content ) ) != "" ):?>
				<p class="post-main-link"><?php echo $first_url;?></p>
				<?php endif; ?>
			</header><!-- .entry-header -->

			<?php if ( !is_singular() || is_related_posts() ) { ?>
				<div class="entry-content">
					<?php
					if( empty($post->post_excerpt) ) {
						the_excerpt();
					} else {
						echo bb_get_excerpt($post->post_excerpt, 150);
					}
					?>
				</div>
			<?php } ?>

			<?php if ( ( isset($post->post_type) && $post->post_type === 'post' ) || ( ! has_post_format( 'quote' ) && is_singular( 'post' ) ) ) : ?>
				<?php get_template_part( 'template-parts/entry-meta' ); ?>

                <!-- Bruce added bookmark here: is this post grid?  What is this? Seems to do nothing here -->

			<?php endif; ?>

			<?php if ( is_single() && ! is_related_posts() ) { ?>
				<?php if ( has_post_thumbnail() ) { ?>
					<figure class="entry-media entry-img bb-vw-container1">
						<?php the_post_thumbnail( 'full' ); ?>
					</figure>
				<?php } ?>
			<?php } ?>

		<?php } ?>

		<?php if ( is_singular() && ! is_related_posts() ) { ?>

            <!--/Bruce added:-->
            </div><!--/entry-header-wrap-->
            </div><!--/hero-->

			<div class="entry-content">
			<?php
				the_content( sprintf(
				wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
				__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'buddyboss-theme' ), array(
					'span' => array(
						'class' => array(),
					),
				)
				), get_the_title()
				) );
			?>
			</div><!-- .entry-content -->
		<?php } ?>
	</div>

	<?php if ( !is_single() || is_related_posts() ) { ?>
		</div><!--Close '.post-inner-wrap'-->
	<?php } ?>

</article><!-- #post-<?php the_ID(); ?> -->

<?php if( is_single() && ( has_category() || has_tag() ) ) { ?>
	<div class="post-meta-wrapper">
		<?php if  ( has_category() ) : ?>
			<div class="cat-links">
				<i class="bb-icon-folder"></i>
				<?php _e( 'Categories: ', 'buddyboss-theme' ); ?>
				<span><?php the_category( __( ', ', 'buddyboss-theme' ) ); ?></span>
			</div>
		<?php endif; ?>

		<?php if  ( has_tag() ) : ?>
			<div class="tag-links">
				<i class="bb-icon-tag"></i>
				<?php _e( 'Tagged: ', 'buddyboss-theme' ); ?>
				<?php the_tags( '<span>', __( ', ', 'buddyboss-theme' ),'</span>' ); ?>
			</div>
		<?php endif; ?>
	</div>
<?php } ?>

<?php
get_template_part( 'template-parts/content-subscribe' );
get_template_part( 'template-parts/author-box' );
get_template_part( 'template-parts/related-posts' );