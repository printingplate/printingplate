<?php

class Super_Theme {

  /**
   * Constructor, uses hooks to integrate functionalities into WordPress
   */
  public function __construct() {

    # Activate thumbnail support
    add_theme_support( 'post-thumbnails' );

    # Add image sizes
    $this->add_image_sizes();

    # Remove st00pid emoji scripts
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );

     # Enqueue stylesheets
    add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_styles' ) );

    # Enqueue javascripts
    add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts' ) );

     # Disable for security reasons
    add_filter( 'xmlrpc_enabled', '__return_false' );

    # Redirect login attempts for user "admin"
    add_action( 'wp_login_failed', array( &$this, 'redirect_unwanted_login' ), 1 );

    # Add dynamic menu's
    # add_action( 'init', array( &$this, 'register_menus' ) );

    # Add shortcodes
    # add_action( 'init', array( &$this, 'register_shortcodes') );

    # # Add sidebars
    # add_action( 'widgets_init', array( &$this, 'register_sidebars' ) );

   

    # Activate ACF options page
    // if( function_exists('acf_add_options_page') )
    //     acf_add_options_page();


    # Activate page excerpts
    // add_action( 'init', array( &$this, 'add_page_excerpts' ) );

    # Set content width for correct inline image sizes
    // global $content_width;
    // $content_width = 700;

    # Customize login screen
    // add_action( 'login_enqueue_scripts', array( &$this, 'custom_login_logo' ) );
    // add_filter( 'login_headerurl', array( &$this, 'custom_login_logo_url' ) );
    // add_filter( 'login_headertitle', array( &$this, 'custom_login_logo_url_title' ) );

    # Force login
    // add_action( 'template_redirect', array( &$this, 'check_user_allowed' ) );

  }


  /**
   * Force login for when in dev/staging mode
   */
  public function check_user_allowed() {

    $allowed_ips = array( '92.111.254.99' );

    if( ! is_user_logged_in() && ! in_array( $_SERVER['REMOTE_ADDR'], $allowed_ips ) {

      $redirect_url = trailingslashit( get_site_url() ) . 'wp-login.php?reauth=1&redirect_to='.urlencode( home_url( $_SERVER["REQUEST_URI"] ) );

      if (function_exists('status_header'))
        status_header( 301 );

      header("HTTP/1.1 301 Temporary Redirect");
      header("Location:" . $redirect_url);
      exit();

    }

  }

  /**
   * Register image sizes
   */
  public function add_image_sizes() {

    // add_image_size( 'image-250x250', 250, 250, true );

  }


  /**
   * Register the dynamic menu's for our theme
   */
  public function register_menus() {

    // register_nav_menus( array(
    //  'main_nav' => __( 'Main menu', 'super-theme' )
    // ));
  }

  /**
   * Register sidebars
   */
  public function register_sidebars() {

    // register_sidebar( array(
    //  'name' => __( 'Super sidebar', 'super-theme' ),
    //  'id' => 'super-sidebar',
    //  'before_widget' => '<div class="widget %2$s">',
    //  'after_widget' => '</div>',
    //  'before_title' => "<h3>",
    //  'after_title' => "</h3>"
    // ));

  }

  public function register_shortcodes() {

    // add_shortcode( 'highlight', function( $atts, $content ) {

    //  if( ! empty( $atts ) )
    //    extract( $atts );

    //  return '<p class="intro highlight">'.$content.'</p>';
    // });

  }


  /**
   * Add our stylesheets includes to the page header
   */
  public function enqueue_styles() {

    # Enqueue main CSS file
    wp_register_style( 'super-styles', $this->theme_url( 'assets/css/screen.min.css' ), null, null, 'screen' );
    wp_enqueue_style( 'super-styles' );

  }

  /**
   *  Add our javascript files includes to the page header and footer
   */
  public function enqueue_scripts() {
    
    wp_register_script( 'super-scripts', $this->theme_url( 'assets/js/script.js' ), null, null, true );
    
    # Set admin ajax url var in JS
    //wp_localize_script( 'super-scripts', 'load_posts_ajax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );

    wp_enqueue_script( 'main' );

  }


  /**
   * Method to easily get the right URL when wanting to reference the theme folder URI.
   * For wonderful convenience!
   */
  public static function theme_url( $url ) {
    return trailingslashit( get_template_directory_uri() ) . $url;
  }


  /**
   * Detect bot trying to log in as admin and redirect it to itself
   */
  public function redirect_unwanted_login( $username ) {

    if( $username == 'admin' ) # Bye
      wp_redirect( $_SERVER['REMOTE_ADDR'], 301 );

  }


  /**
   * Get the image URL of given size for given post
   */
  public static function get_feature_image_url( $post_id, $size ) {
    $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $size );
    return $image[0];
  }


  /**
   * Activate support for page excerpts
   */
  public function add_page_excerpts() {
    add_post_type_support( 'page', 'excerpt' );
  }


  /**
   * Custom logo for login screen
   */
  public function custom_login_logo() {
    echo '<style type="text/css">';
    echo '.login h1 a {';
    echo 'width:100%;';
    echo 'background-image: url('.$this->theme_url( 'assets/images/logo.svg' ).');';
    echo 'background-size: 100%;';
    echo 'padding-bottom: 30px;}';
    echo '</style>';
  }


  /**
   * Change logo link login screen
   */
  function custom_login_logo_url() {
    return home_url();
  }


  /**
   * Change logo title attr login screen
   */
  function custom_login_logo_url_title() {
    return get_bloginfo( 'blogname' );
  }
  

}

new Super_Theme;

function super_theme_url( $path ) {
  return Super_Theme::theme_url( $path );
}

function super_get_featured_image_url( $post_id, $size ) {
  return Super_Theme::get_feature_image_url( $post_id, $size );
}
