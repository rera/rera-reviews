<?php
/*
Template Name: Reviews Form
*/

  $error = array();
  $success = false;

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_POST['review_author']))
      $error[] = "Please add your name";

    if (empty($_POST['review_content']))
      $error[] = "Please add your review";

    if (empty($_POST['review_email']))
      $error[] = "Please add your email address";

    if ( empty($error) ) {
      $new = array(
        'post_title'	=>	$_POST['review_author'],
        'post_content'	=>	$_POST['review_content'],
        'post_status'	=>	'draft',
        'post_type'	=>	'review'
      );

  		// save the post
  		$pid = wp_insert_post($new);

      if (!empty($pid)) {
    		// add custom fields
    		add_post_meta($pid, 'rating', $_POST['review_rating'], true);
    		add_post_meta($pid, 'email', $_POST['review_email'], true);

        $success = true;
      }

      // insert the post
      do_action('wp_insert_post', 'wp_insert_post');
		}

  }

?>

<?php get_header(); ?>

<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h1 class="main_title"><?php the_title(); ?></h1>
					<div class="entry-content">
            <div class="form-results">
                 <?php
                    if (!empty($error)) {
                      echo '<p class="error"><strong>Your review was NOT submitted. The following error(s) returned:</strong><br/><ul><li>' . implode("</li><li>", $error) . '</li></ul></p>';
                    } elseif ($success) {
                      echo '<p class="success"><strong>Your review was submitted successfully!<br/><br/></p>';
                    }
                ?>
            </div>

  					<?php the_content(); ?>

            <form method="post" action="" class="wpcf7-form">
              <p>
                Name<br>
                <input type="text" name="review_author" value="<?php echo $_POST['review_author'] ?: ''; ?>" size="40">
              </p>
              <p>
                Email<br>
                <input type="email" name="review_email" value="<?php echo $_POST['review_email'] ?: ''; ?>" size="40">
              </p>
              <p>
                Rating<br>
                <label><input type="radio" name="review_rating" value="1"<?php echo $_POST['review_rating'] == 1 ? ' checked': ''; ?>> 1</label>&nbsp;
                <label><input type="radio" name="review_rating" value="2"<?php echo $_POST['review_rating'] == 2 ? ' checked': ''; ?>> 2</label>&nbsp;
                <label><input type="radio" name="review_rating" value="3"<?php echo $_POST['review_rating'] == 3 ? ' checked': ''; ?>> 3</label>&nbsp;
                <label><input type="radio" name="review_rating" value="4"<?php echo $_POST['review_rating'] == 4 ? ' checked': ''; ?>> 4</label>&nbsp;
                <label><input type="radio" name="review_rating" value="5"<?php echo $_POST['review_rating'] == 5 || empty($_POST['review_rating']) ? ' checked': ''; ?>> 5</label>&nbsp;
              </p>
              <p>
                Review<br>
                <textarea name="review_content" cols="40" rows="10"><?php echo $_POST['review_content'] ?: ''; ?></textarea>
              </p>
              <p>
                <input type="submit" value="Send" class="wpcf7-form-control wpcf7-submit">
              </p>
            </form>

        		<!-- END OF FORM -->
					</div> <!-- .entry-content -->
				</article> <!-- .et_pb_post -->
			<?php endwhile; ?>
			</div> <!-- #left-area -->
			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->
<?php get_footer(); ?>
