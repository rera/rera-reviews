<?php
/**
 * The template for displaying all single reviews
 *
 */

get_header(); ?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h1 class="main_title"><?php the_title(); ?></h1>
					<div class="entry-content">
            <?php the_content(); ?>
            <br/><br/>
            <h3>Related Reviews</h3>
            <?php
              // Get terms for post
              $terms = get_the_terms( get_the_id() , 'collection' );

              if ( $terms != null ) {
                $collections = array("<ul>");
                foreach( $terms as $term ) {
                  $collections[] = "<li><a href='" . get_site_url() . "\/collection/" . $term->slug . "'>" . $term->name . " Reviews</a></li>";
                  unset($term);
                }
                $collections[] = "</ul>";

                echo implode("\n", $collections);
              }
            ?>

					</div> <!-- .entry-content -->
				</article> <!-- .et_pb_post -->
			<?php endwhile; ?>
			</div> <!-- #left-area -->
			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->
<?php get_footer(); ?>
