<?php
/**
 * Reviews lets you collect customer reviews, place on your site with shortcodes, and approve before asking for third-party reviews (such as Google or Yelp).
 *
 * @package Rera_Reviews
 *
 * @wordpress-plugin
 * Plugin Name: Reviews
 * Version:     0.1.0
 * Plugin URI: http://davidcabrera.me/plugins/reviews
 * Description: Reviews lets you collect customer reviews, place on your site with shortcodes, and approve before asking for third-party reviews (such as Google or Yelp).
 * Author: David Cabrera
 * Author URI: http://davidcabrera.me
 * License:		GPL-2.0+
 * License URI:	http://www.gnu.org/licenses/gpl-2.0.txt
*/

// If this file is called directly, then abort execution.
if ( ! defined( 'WPINC' ) ) {
	die( "Aren't you supposed to come here via WP-Admin?" );
}

/**
 * Holds the filesystem directory path.
 */
define( 'RERA_REVIEWS_DIR', dirname( __FILE__ ) );

/**
 * Set the global variables for Better Search path and URL
 */
$rera_reviews_path = plugin_dir_path( __FILE__ );
$rera_reviews_url = plugins_url() . '/' . plugin_basename( dirname( __FILE__ ) );
$rera_reviews_plugin_url = plugin_dir_url( __FILE__ );

include $rera_reviews_path . "pagetemplater.php";


/**
 * Create custom post type.
 *
 */
function rera_reviews_cpt_init() {
	$labels = array(
		'name'                => _x( 'Reviews', 'Post Type General Name', 'rera_reviews_domain' ),
		'singular_name'       => _x( 'Review', 'Post Type Singular Name', 'rera_reviews_domain' ),
		'menu_name'           => __( 'Reviews', 'rera_reviews_domain' ),
		'name_admin_bar'      => __( 'Reviews', 'rera_reviews_domain' ),
		'parent_item_colon'   => __( 'Parent Review:', 'rera_reviews_domain' ),
		'all_items'           => __( 'All Reviews', 'rera_reviews_domain' ),
		'add_new_item'        => __( 'Add New Review', 'rera_reviews_domain' ),
		'add_new'             => __( 'Add New', 'rera_reviews_domain' ),
		'new_item'            => __( 'New Review', 'rera_reviews_domain' ),
		'edit_item'           => __( 'Edit Review', 'rera_reviews_domain' ),
		'update_item'         => __( 'Update Review', 'rera_reviews_domain' ),
		'view_item'           => __( 'View Review', 'rera_reviews_domain' ),
		'search_items'        => __( 'Search Review', 'rera_reviews_domain' ),
		'not_found'           => __( 'Not found', 'rera_reviews_domain' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'rera_reviews_domain' ),
	);
	$args = array(
		'label'               => __( 'review', 'rera_reviews_domain' ),
		'description'         => __( 'Customer reviews', 'rera_reviews_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor' ),
		'taxonomies'          => array( 'collection' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 2,
		'menu_icon'           => 'dashicons-awards',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
	);
	register_post_type( 'review', $args );

}
add_action( 'init', 'rera_reviews_cpt_init', 0 );


/**
 * Create custom taxonomy.
 *
 */
function rera_reviews_taxonomy_init() {

	$labels = array(
		'name'                       => _x( 'Collections', 'Taxonomy General Name', 'rera_reviews_domain' ),
		'singular_name'              => _x( 'Collection', 'Taxonomy Singular Name', 'rera_reviews_domain' ),
		'menu_name'                  => __( 'Collections', 'rera_reviews_domain' ),
		'all_items'                  => __( 'All Collections', 'rera_reviews_domain' ),
		'parent_item'                => __( 'Parent Collection', 'rera_reviews_domain' ),
		'parent_item_colon'          => __( 'Parent Collection:', 'rera_reviews_domain' ),
		'new_item_name'              => __( 'New Collection Name', 'rera_reviews_domain' ),
		'add_new_item'               => __( 'Add New Collection', 'rera_reviews_domain' ),
		'edit_item'                  => __( 'Edit Collection', 'rera_reviews_domain' ),
		'update_item'                => __( 'Update Collection', 'rera_reviews_domain' ),
		'view_item'                  => __( 'View Collection', 'rera_reviews_domain' ),
		'separate_items_with_commas' => __( 'Separate collections with commas', 'rera_reviews_domain' ),
		'add_or_remove_items'        => __( 'Add or remove collections', 'rera_reviews_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'rera_reviews_domain' ),
		'popular_items'              => __( 'Popular Collections', 'rera_reviews_domain' ),
		'search_items'               => __( 'Search Collections', 'rera_reviews_domain' ),
		'not_found'                  => __( 'Not Found', 'rera_reviews_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'collection', array( 'review' ), $args );

}
add_action( 'init', 'rera_reviews_taxonomy_init', 0 );


/**
 * Create review display shortcode.
 *
 */
function rera_reviews_shortcode_init( $atts ) {
	// Attributes
	extract( shortcode_atts(
		array(
			'collection' => 'all',
			'cycle' => 'true',
			'mosiac' => 'false',
		), $atts )
	);

	// Code
	$id = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
	$results = array(
		"<ul class='reviews $collection" .
		( $cycle == "true" ? " cycle-slideshow" : "" ) .
		( $mosaic == "true" ? " mosaic" : "" ) . "' " .
		( $cycle == "true" ? "data-cycle-slides='li'" : "" ) .
		" id='$id'>"
	);

	query_posts( array( 'post_type' => 'review', 'collection' => $collection ) );
	if ( have_posts() ) : while ( have_posts() ) : the_post();
		$results[] = "<li><p><a href='" . get_the_permalink() . "'>" . get_my_excerpt(55, get_the_id()) . "<span class='author'>" . get_the_title() . "</span></a></p></li>";
		// $results[] = "<li><p><a href='" . get_site_url() . "/collection/$collection'>" . get_the_content() . "<span class='author'>" . get_the_title() . "</span></a></p></li>";
	endwhile; endif; wp_reset_query();
	$results[] = "</ul>";

	return implode("\n", $results);
}
add_shortcode( 'reviews', 'rera_reviews_shortcode_init' );


/**
 * Template pages for reviews.
 *
 */
function rera_reviews_template_includes( $template_path ) {
    if ( get_post_type() == 'review' ) {
        if ( is_single() ) {
            if ( $theme_file = locate_template( array ( 'single-review.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-review.php';
            }
        }
      elseif ( is_archive() ) {
          if ( $theme_file = locate_template( array ( 'archive-review.php' ) ) ) {
              $template_path = $theme_file;
          } else { $template_path = plugin_dir_path( __FILE__ ) . '/archive-review.php';
          }
      }
      }
    return $template_path;
}
add_filter( 'template_include', 'rera_reviews_template_includes', 1 );


/**
 * Enqueue scripts and styles.
 *
 */
function rera_reviews_enqueue() {
	global $rera_reviews_plugin_url;
	wp_enqueue_style( 'reviews', $rera_reviews_plugin_url . '/rera-reviews.css' );
	wp_enqueue_script( 'cycle2', $rera_reviews_plugin_url . '/jquery.cycle2.min.js', array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'rera_reviews_enqueue' );


/**
 * Enqueue scripts and styles.
 *
 */
function rera_reviews_custom_title( $input ) {
    global $post_type;
    if( is_admin() && 'Enter title here' == $input && 'review' == $post_type )
        return 'Enter Author Name';
    return $input;
}
add_filter('gettext','rera_reviews_custom_title');


/**
 * Build custom meta box for reviews cpt.
 *
 */
function review_details_get_meta( $value ) {
	global $post;

	$field = get_post_meta( $post->ID, $value, true );
	if ( ! empty( $field ) ) {
		return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
	} else {
		return false;
	}
}

function review_details_add_meta_box() {
	add_meta_box(
		'review_details-review-details',
		__( 'Review Details', 'review_details' ),
		'html',
		'review',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes', 'review_details_add_meta_box' );

function html( $post) {
	wp_nonce_field( '_nonce', 'nonce' ); ?>
	<table class="form-table">
		<tr><th scope="row"><label for="email"><?php _e( 'Email', 'review_details' ); ?></label></th>
			<td><input type="text" name="email" id="email" style="width:100%;" value="<?php echo review_details_get_meta( 'email' ); ?>"></td>
		</tr>
		<tr><td scope="row" colspan="2"></td>
		</tr>
		<tr><th scope="row"><label for="rating"><?php _e( 'Rating', 'review_details' ); ?></label></th>
			<td>
				<input type="radio" name="rating" id="rating_0" value="1" <?php echo ( review_details_get_meta( 'rating' ) === '1' ) ? 'checked' : ''; ?>>
				<label for="rating_0">1</label>
				&nbsp;&nbsp;
				<input type="radio" name="rating" id="rating_1" value="2" <?php echo ( review_details_get_meta( 'rating' ) === '2' ) ? 'checked' : ''; ?>>
				<label for="rating_1">2</label>
				&nbsp;&nbsp;
				<input type="radio" name="rating" id="rating_2" value="3" <?php echo ( review_details_get_meta( 'rating' ) === '3' ) ? 'checked' : ''; ?>>
				<label for="rating_2">3</label>
				&nbsp;&nbsp;
				<input type="radio" name="rating" id="rating_3" value="4" <?php echo ( review_details_get_meta( 'rating' ) === '4' ) ? 'checked' : ''; ?>>
				<label for="rating_3">4</label>
				&nbsp;&nbsp;
				<input type="radio" name="rating" id="rating_4" value="5" <?php echo ( review_details_get_meta( 'rating' ) === '5' ) ? 'checked' : ''; ?>>
				<label for="rating_4">5</label>
			</td>
		</tr>
	</table>
	<?php
}

function save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], '_nonce' ) ) return;
	if ( ! current_user_can( 'edit_post' ) ) return;

	if ( isset( $_POST['email'] ) )
		update_post_meta( $post_id, 'email', esc_attr( $_POST['email'] ) );
	if ( isset( $_POST['rating'] ) )
		update_post_meta( $post_id, 'rating', esc_attr( $_POST['rating'] ) );
}
add_action( 'save_post', 'save' );


function my_excerpt($excerpt_length = 55, $id = false, $echo = true) {
    $text = '';

	  if($id) {
	  	$the_post = & get_post( $my_id = $id );
	  	$text = ($the_post->post_excerpt) ? $the_post->post_excerpt : $the_post->post_content;
	  } else {
	  	global $post;
	  	$text = ($post->post_excerpt) ? $post->post_excerpt : get_the_content('');
    }

		$text = strip_shortcodes( $text );
		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]&gt;', $text);
	  $text = strip_tags($text);

		$excerpt_more = ' ' . '[...]';
		$words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
		if ( count($words) > $excerpt_length ) {
			array_pop($words);
			$text = implode(' ', $words);
			$text = $text . $excerpt_more;
		} else {
			$text = implode(' ', $words);
		}
	if($echo)
  echo apply_filters('the_content', $text);
	else
	return $text;
}

function get_my_excerpt($excerpt_length = 55, $id = false, $echo = false) {
 return my_excerpt($excerpt_length, $id, $echo);
}


/**
 * Default options.
 *
 * @return array Array of default options
 */
function rera_reviews_default_options() {

	$ga_url = parse_url( get_option( 'home' ), PHP_URL_HOST );

	$rera_reviews_settings = array (
		'test' => '',
	);
	return apply_filters( 'rera_reviews_default_options', $rera_reviews_settings );
}


/**
 * Function to read options from the database and add any new ones.
 *
 * @return array Options from the database
 */
function rera_reviews_read_options() {
	$rera_reviews_settings_changed = false;

	$defaults = rera_reviews_default_options();

	$rera_reviews_settings = array_map( 'stripslashes', (array) get_option( 'rera_reviews_settings' ) );
	unset( $rera_reviews_settings[0] ); // produced by the (array) casting when there's nothing in the DB

	// If there are any new options added to the Default Options array, let's add them
	foreach ( $defaults as $k=>$v ) {
		if ( ! isset( $rera_reviews_settings[ $k ] ) ) {
			$rera_reviews_settings[ $k ] = $v;
		}
		$rera_reviews_settings_changed = true;
	}

	if ( true == $rera_reviews_settings_changed ) {
		update_option( 'rera_reviews_settings', $rera_reviews_settings );
	}

	return apply_filters( 'rera_reviews_read_options', $rera_reviews_settings );
}


/**
 *  Admin option
 *
 */
if ( is_admin() || strstr( $_SERVER['PHP_SELF'], 'wp-admin/' ) ) {

	/**
	 *  Load the admin pages if we're in the Admin.
	 *
	 */
	require_once( RERA_REVIEWS_DIR . "/admin.inc.php" );

	/**
	 * Adding WordPress plugin action links.
	 *
	 * @param array $links
	 * @return array
	 */
	function rera_reviews_plugin_actions_links( $links ) {

		return array_merge(
			array(
				// 'settings' => '<a href="' . admin_url( 'options-general.php?page=rera_reviews_options' ) . '">Settings</a>'
			),
			$links
		);

	}
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'rera_reviews_plugin_actions_links' );

	/**
	 * Add meta links on Plugins page.
	 *
	 * @param array $links
	 * @param string $file
	 * @return array
	 */
	function rera_reviews_plugin_actions( $links, $file ) {
		static $plugin;
		if ( ! $plugin ) {
			$plugin = plugin_basename( __FILE__ );
		}

		// create link
		if ( $file == $plugin ) {
			$links[] = '<a href="http://davidcabrera.me">Support</a>';
		}
		return $links;
	}
	add_filter( 'plugin_row_meta', 'rera_reviews_plugin_actions', 10, 2 ); // only 2.8 and higher

} // End admin.inc

?>
