<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://funl.co
 * @since      1.0.0
 *
 * @package    Funl_Html_Landing_Pages
 * @subpackage Funl_Html_Landing_Pages/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Funl_Html_Landing_Pages
 * @subpackage Funl_Html_Landing_Pages/admin
 * @author     Vineet Kharwar <vineet.kharwar@gaboli.com>
 */
class Funl_Html_Landing_Pages_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $funl_html_landing_pages    The ID of this plugin.
	 */
	private $funl_html_landing_pages;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The plugin_path of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_path    The current path of this plugin.
	 */
	private $plugin_path;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $funl_html_landing_pages       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $funl_html_landing_pages, $version,$plugin_path ) {

		$this->funl_html_landing_pages = $funl_html_landing_pages;
		$this->version = $version;
		$this->plugin_path = $plugin_path;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Funl_Html_Landing_Pages_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Funl_Html_Landing_Pages_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->funl_html_landing_pages, plugin_dir_url( __FILE__ ) . 'css/funl-html-landing-pages-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Funl_Html_Landing_Pages_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Funl_Html_Landing_Pages_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/html'));
		wp_localize_script('jquery', 'cm_settings', $cm_settings);
		
		wp_enqueue_script('wp-theme-plugin-editor');
		wp_enqueue_style('wp-codemirror');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function create_funlhtmllandingpages_post_type() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Funl_Html_Landing_Pages_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Funl_Html_Landing_Pages_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		register_post_type('funlhtmllandingpages',
                       array(
                           'labels'      => array(
                               'name'          => __('Landing Page'),
                               'singular_name' => __('Landing Pages'),
                           ),
                           'public'      	=> true,
                           'has_archive'	=> true,
                           'rewrite'    	=> ['slug' => 'funlhtmllandingpages'],
                           'show_in_menu'   => true,
                           'supports'   	=> ['title','author'],
                           'menu_icon'      => plugin_dir_url( __FILE__ ) . 'assets/img/funl-icon-white.png'
                       	)
    	);
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function funlhtmllandingpages_meta_box() {
		add_meta_box(
			'funlhtmllandingpages_meta_box', // $id
			'Page Settings', // $title
			array( $this, 'funlhtmllandingpages_meta_box_show'), // $callback
			'funlhtmllandingpages', // $screen
			'normal', // $context
			'high' // $priority
		);
	}

	public function funlhtmllandingpages_meta_box_show() {
		global $post;  
		$meta = get_post_meta( $post->ID, 'funlhtmllandingpages_meta_box', true ); ?>

		<input type="hidden" name="funlhtmllandingpages_meta_nonce" value="<?php echo wp_create_nonce( basename(__FILE__) ); ?>">

		<!-- funlhtmllandingpages fields -->

		<p>
			<span>
				<strong>
					<?php echo esc_url( get_site_url()."/" ); ?>
				</strong>
			</span>
			<input type="text" placeholder="enter-permalink-here" name="funlhtmllandingpages_meta_box[html_permalink]" id="funlhtmllandingpages_meta_box[html_permalink]" class="regular-text" value="<?php echo ( esc_attr( isset($meta['html_permalink'])?$meta['html_permalink']:"" ) ); ?>">
			
			<?php 
			
			if(isset($meta['html_permalink']) && !empty($meta['html_permalink'])) {
				$perma_link = get_site_url()."/".$meta['html_permalink'];
				if($meta['html_permalink']) ?>
					 <a href="<?php echo esc_url($perma_link) ?>" target='_blank'>View page</a> 
					 <?php
			}
			
			?>
			<br>
			<hr>
		</p>

		<p>
			<strong><label for="funlhtmllandingpages_meta_box[html_code]">HTML Page Code</label></strong>
		<br>

		<textarea name="funlhtmllandingpages_meta_box[html_code]" id="funlhtmllandingpages_meta_box[html_code]" rows="30" cols="90" style="width:100%;">

			<?php if(	isset(	$meta[	'html_code'	]	)	) echo esc_html( htmlspecialchars(	$meta[	'html_code'	], ENT_QUOTES	) ); ?>
			<?php if(	!	isset(	$meta[	'html_code'	]	)	) {	?>
			   <!DOCTYPE html>
			   <html>
			   <head>
			   <title>Page Title</title>
			   <style type="text/css"></style>
			   </head>
			   <body>
			   		<h1>Landing Page By FUNL.CO</h1>
			   </body>
			   </html>
			<?php } ?>
		</textarea>

		<script>
			function colorcoding() {
				wp.codeEditor.initialize(document.getElementById("funlhtmllandingpages_meta_box[html_code]"), cm_settings);
			}
		  
			colorcoding();
	  
		</script>

		</p>

		<br>
		<p>
		<ul>
			<?php 
			if(isset($meta['html_permalink']) && !empty($meta['html_permalink'])) {
				$perma_link = get_site_url()."/".$meta['html_permalink'];
				if( $meta['html_permalink'] ) ?>
				
					<li><strong>Link to Landing Page: </strong> <a href="<?php echo esc_url($perma_link) ?>" target='_blank'><?php echo esc_url($perma_link) ?></a></li>
				
				<?php
			}
			
			?>
		</ul>
		</p>

		<?php 
	}

	public function funlhtmllandingpages_meta_box_save( $post_id, $post, $update ) {   
		// verify nonce
		if ( !isset($_POST['funlhtmllandingpages_meta_nonce']) || !wp_verify_nonce( $_POST['funlhtmllandingpages_meta_nonce'], basename(__FILE__) ) ) {
			return $post_id; 
		}
		// check autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		// check permissions
		if ( 'page' === $_POST['funlhtmllandingpages'] ) {
			if ( !current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			} elseif ( !current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}  
		}
		
	  	$user_id = get_current_user_id();

		$old = get_post_meta( $post_id, 'funlhtmllandingpages_meta_box', true );
		$new = wp_unslash( $_POST['funlhtmllandingpages_meta_box'] );
	   
		$new_slug = sanitize_url( $_POST['funlhtmllandingpages_meta_box']['html_permalink'] );

		if ( $new && $new !== $old ) {
			update_post_meta( $post_id, 'funlhtmllandingpages_meta_box', $new );
		} elseif ( '' === $new && $old ) {
			delete_post_meta( $post_id, 'funlhtmllandingpages_meta_box', $old );
		}
	}

	public function funlhtmllandingpages_before_delete( $post_id ){

		global $post_type;  
		
		if( $post_type != 'funlhtmllandingpages' )
		{
			return;
		}
		
		wp_delete_post( $post_id, true );
		delete_post_meta($post_id, 'funlhtmllandingpages_meta_box' );
		
	}

	public function funlhtmllandingpages_template($single) {

	    global $wp_query, $post;

	    /* Checks for single template by post type */
	    if ( $post->post_type == 'funlhtmllandingpages' ) {
	        if ( file_exists( $this->plugin_path . 'admin/pages/single-funlhtmllandingpages.php' ) ) {
	            return $this->plugin_path . 'admin/pages/single-funlhtmllandingpages.php';
	        }
	    }

	    return $single;

	}

	public function getStaticPosts()
	{
		global $wpdb;

		$sql = "SELECT {$wpdb->postmeta}.post_id, {$wpdb->postmeta}.meta_key, {$wpdb->postmeta}.meta_value 
				FROM {$wpdb->postmeta} 
				WHERE ({$wpdb->postmeta}.meta_key = %s)";		

		$rows = $wpdb->get_results( $wpdb->prepare( $sql, 'funlhtmllandingpages_meta_box' ) );		

		$posts = array();
		$post = array();

		foreach ( $rows as $k => $row )
		{
			if ( !array_key_exists( $row->post_id, $posts ) )
			{
				$posts[ $row->post_id ] = array();
			}

			$post =  get_post($row->post_id); 

			if( isset( $post->post_type ) && $post->post_type == 'funlhtmllandingpages' && isset( $post->post_status ) && $post->post_status == 'publish' ) {
				$meta_value = unserialize( $row->meta_value );
				
				if( isset( $meta_value[ 'html_permalink' ] ) && !empty( $meta_value[ 'html_permalink' ] ) ) {
					$posts[ $meta_value[ 'html_permalink' ] ][ 'id' ] = $row->post_id;
					
					$posts[ $meta_value[ 'html_permalink' ] ][ 'html_code' ] = isset( $meta_value[ 'html_code' ] )? $meta_value[ 'html_code' ]: '';
				}
			}
			
		}
		
		return $posts;
	}

	public function parseRequest()
	{
		$posts = $this->getStaticPosts();

		if ( !is_array( $posts ) )
		{
			return false;
		}

		// get current url
		$current = sanitize_text_field( $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ] );

		// calculate the path
		$pattern = '/^www\./';
		$home_url = preg_replace( $pattern, '', str_replace( array( 'https://', 'http://' ), '', get_home_url() ) );
		$current = preg_replace( $pattern, '', $current );
		$part = substr( $current, strlen( $home_url ) );
		$part = rtrim( $part, '/' );

		if ( substr( $part, 0, 1 ) === '/' )
		{
			$part = substr( $part, 1 );
		}

		if ( strpos( $part, '?' ) !== false )
		{
			$part = explode( '?', $part );
			$part = $part[ 0 ];
		}

		$part = trim( $part, '/' );

		if ( $part == '' )
		{

			return false;
		}

		foreach( $posts as $key => $value )
		{
			if( strcasecmp( $part, $key ) === 0 )
			{
				return $value;
			}
		}

		return false;
	}

	public function funlhtmllandingpages_permalink_handler( $post ) 
	{
		global $post;

		$requested_page = $this->parseRequest();

		if( $requested_page == false )
		{
			return $post;
		}

		$html = $requested_page[ 'html_code' ] ;
		
		status_header( '200' );

		header( 'Access-Control-Allow-Origin: *' );
	    
	    //get_header();
	    print stripslashes($html);
	    //get_footer();

	    die();
     
	}

	public function funlhtmllandingpages_admin_init() {
	    register_setting( 'funlhtmllandingpages-options', 'funlhtmllandingpages_opt_allow_wp-admin' );
	    register_setting( 'funlhtmllandingpages-options', 'funlhtmllandingpages_opt_filter_params' );
	}
	
	/**
	 * Landing Pages update messages.
	 *
	 * See /wp-admin/edit-form-advanced.php
	 *
	 * @param array $messages Existing post update messages.
	 *
	 * @return array Amended post update messages with new CPT update messages.
	 */

	public function rw_post_updated_messages( $messages ) {

		$post             = get_post();
		$post_type        = get_post_type( $post );
		$post_type_object = get_post_type_object( $post_type );
		
		$messages['funlhtmllandingpages'] = array(
			0  => '', // Unused. Messages start at index 1.
			1  => __( 'Landing Page updated.' ),
			2  => __( 'Custom field updated.' ),
			3  => __( 'Custom field deleted.'),
			4  => __( 'Landing Page updated.' ),
			/* translators: %s: date and time of the revision */
			5  => isset( $_GET['revision'] ) ? sprintf( __( 'Landing Page restored to revision from %s' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6  => __( 'Landing Page published.' ),
			7  => __( 'Landing Page saved.' ),
			8  => __( 'Landing Page submitted.' ),
			9  => sprintf(
				__( 'Landing Page scheduled for: <strong>%1$s</strong>.' ),
				// translators: Publish box date format, see http://php.net/date
				date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Landing Page draft updated.' )
		);

		return $messages;
	}
	
}
