<?php
/**
 * The template for displaying all archive reviews
 *
 */

get_header(); ?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
				<h1 class="main_title">
					<?php
						if( is_tax() ) {
					    global $wp_query;
					    $term = $wp_query->get_queried_object();
					    $title = $term->name;
							echo $title . " Reviews";
						}
					?>
				</h1>
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h2><?php echo get_the_title(); ?></h2>
					<div class="entry-content">
            <a href="<?php echo get_the_permalink(); ?>"><?php echo get_my_excerpt(50, get_the_id() ); ?></a>
					</div> <!-- .entry-content -->
				</article> <!-- .et_pb_post -->
			<?php endwhile; ?>
			</div> <!-- #left-area -->
			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->
<?php get_footer(); ?>
