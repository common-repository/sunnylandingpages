<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://funl.co
 * @since      1.0.0
 *
 * @package    Funl_Html_Landing_Pages
 * @subpackage Funl_Html_Landing_Pages/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Funl_Html_Landing_Pages
 * @subpackage Funl_Html_Landing_Pages/includes
 * @author     Vineet Kharwar <vineet.kharwar@gaboli.com>
 */
class Funl_Html_Landing_Pages {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Funl_Html_Landing_Pages_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $funl_html_landing_pages    The string used to uniquely identify this plugin.
	 */
	protected $funl_html_landing_pages;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The plugin_path of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_path    The current path of this plugin.
	 */
	private $plugin_path;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'FUNL_HTML_LANDING_PAGES_VERSION' ) ) {
			$this->version = FUNL_HTML_LANDING_PAGES_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->funl_html_landing_pages = 'funl-html-landing-pages';
		$this->plugin_path = plugin_dir_path( $this->dirname_r( __FILE__, 1 ) );


		$this->load_dependencies();
		$this->define_admin_hooks();

	}

	/**
 	* PHP 5 compatible dirname() with count parameter
 	*
 	* @see http://php.net/manual/en/function.dirname.php#113193
	 *
 	* @deprecated with PHP 7
	 * @param string $path
	 * @param int    $levels
	 * @return string
	 */
	public function dirname_r($path, $count=1){
    		if ($count > 1){
       			return dirname(dirname_r($path, --$count));
    		}else{
       			return dirname($path);
   		 }
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Funl_Html_Landing_Pages_Loader. Orchestrates the hooks of the plugin.
	 * - Funl_Html_Landing_Pages_i18n. Defines internationalization functionality.
	 * - Funl_Html_Landing_Pages_Admin. Defines all hooks for the admin area.
	 * - Funl_Html_Landing_Pages_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-funl-html-landing-pages-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-funl-html-landing-pages-admin.php';
		
		/**
		 * The class responsible for defining all actions that occur in the admin area of form lead 
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/lead/class-funl-lead-form.php';

		/**
		 * The class responsible for defining all actions that occur in the form data submitting api
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/api/class-funl-data-api.php';

		$this->loader = new Funl_Html_Landing_Pages_Loader();

	}


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Funl_Html_Landing_Pages_Admin( $this->get_funl_html_landing_pages(), $this->get_version(), $this->get_plugin_path() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'create_funlhtmllandingpages_post_type' );
		$this->loader->add_action( 'add_meta_boxes',  $plugin_admin, 'funlhtmllandingpages_meta_box' );
		$this->loader->add_action( 'save_post',  $plugin_admin, 'funlhtmllandingpages_meta_box_save', 10, 3 );
		$this->loader->add_action( 'before_delete_post',  $plugin_admin, 'funlhtmllandingpages_before_delete' );

		$this->loader->add_filter( 'single_template',  $plugin_admin, 'funlhtmllandingpages_template', 999 );
		$this->loader->add_action( 'parse_request', $plugin_admin, 'funlhtmllandingpages_permalink_handler' );
		
		$this->loader->add_action( 'admin_init', $plugin_admin, 'funlhtmllandingpages_admin_init' );
		$this->loader->add_action( 'post_updated_messages', $plugin_admin, 'rw_post_updated_messages' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_funl_html_landing_pages() {
		return $this->funl_html_landing_pages;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Funl_Html_Landing_Pages_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve the plugin_path of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The plugin_path of the plugin.
	 */
	public function get_plugin_path() {
		return $this->plugin_path;
	}
	
	/**
	 * Retrieve the array from csv .
	 *
	 * @since     1.0.0
	 * @return    array    
	 */
	public function csvstring_to_array($string, $separatorChar = ',', $enclosureChar = '"', $newlineChar = "\n") {
		$array = array();
		$size = strlen($string);
		$columnIndex = 0;
		$rowIndex = 0;
		$fieldValue="";
		$isEnclosured = false;
		for($i=0; $i<$size;$i++) {
			$char = $string[$i];
			$addChar = "";
			if($isEnclosured) {
				if($char==$enclosureChar) {
					if($i+1<$size && $string[$i+1]==$enclosureChar){
						// escaped char
						$addChar=$char;
						$i++; // dont check next char
					}else{
						$isEnclosured = false;
					}
				}else {
					$addChar=$char;
				}
			}else {
				if($char==$enclosureChar) {
					$isEnclosured = true;
				}else {
					if($char==$separatorChar) {
						$array[$rowIndex][$columnIndex] = $fieldValue;
						$fieldValue="";

						$columnIndex++;
					}elseif($char==$newlineChar) {						
						$array[$rowIndex][$columnIndex] = $fieldValue;
						$fieldValue="";
						$columnIndex=0;
						$rowIndex++;
					}else {
						$addChar=$char;
					}
				}
			}
			if($addChar!=""){
				$fieldValue.=$addChar;
			}
		}
		if($fieldValue) { // save last field
			$array[$rowIndex][$columnIndex] = $fieldValue;
		}
		return $array;
	}
	
	/**
	 * Bulk upload post from CSV
	 *
	 * @since     1.0.0
	 * @return    string    
	 */
	public function uploadSection(){

		// Upload file
		if(isset($_POST['but_submit'])){

		  if($_FILES['file']['name'] != ''){
			$uploadedfile = sanitize_text_field( $_FILES['file'] );
			$posts = Funl_Html_Landing_Pages::csvstring_to_array(file_get_contents($_FILES['file']['tmp_name']));
		
			// Change these to whatever you set
			$funlhtmllandingpages = array(
				"custom-post-type" => "funlhtmllandingpages"
			);
			
			foreach ( $posts as $key => $post ) {
				if ($key == 0) //or whatever
					continue;

				// If the post exists, skip this post and go to the next one
				//if ( $post_exists( $post["title"] ) ) {
				//     continue;
				//}

				// Insert the post into the database
				$post["id"] = wp_insert_post( array(
					"post_title" => $post[1],
					"post_content" => $post[2],
					"post_type" => $funlhtmllandingpages["custom-post-type"],
					"post_status" => "draft"
				));

				   $meta['html_permalink'] = '';
				   $meta['html_code'] = $post[2];
				  $meta_with_pageid = str_replace("<body>",'<body><input id="wpostid" name="wpostid" type="hidden" value='.$post["id"].'>',$meta['html_code']);
				  
					$meta['html_code'] = $meta_with_pageid;
				   update_post_meta( $post["id"], 'funlhtmllandingpages_meta_box', $meta );
			}
		  }
		 
		}
		add_action( 'admin_head', array( &$this,'addAdminJS') );
		
		
?><div class='updated'  id='funl_upload_wrap' style='display:none;overflow: hidden;'>
		
			<!-- Form -->
			<div class="csv-upload-wrap">
				<div style="text-align: center;"><img style="max-width: 72px;" src="<?php echo plugin_dir_url( __FILE__ ) ?>img/folder.png" /></div>
				<form method="post" action="" name="myform" enctype="multipart/form-data" class="input-group mb-3 px-2 py-2 rounded-pill bg-white shadow-sm">
					<input id="upload" name="file" type="file" onchange="readURL(this);" class="form-control border-0">
					<label id="upload-label" for="upload" class="font-weight-light text-muted">Choose CSV file</label>
					<div class="input-group-append">
						<input type="submit" name="but_submit" id="but_submit" class="btn btn-light m-0 rounded-pill px-4 button button-primary" value="Upload" disabled>
					</div>          
				</form>				
			</div>
			
			
			
			
			<style>
			.funl-steps-gif {max-width: 500px;margin: 10px auto 30px auto;text-align: center;display: block;}
			.updated, .updated p {font-size: 14px;}.updated ol > li {margin-bottom: 15px;}
			.csv-upload-wrap{background-color: #f1f1f1;padding: 30px 40px;width: 100%;display: inline-block;float: left;margin: 20px 0;box-sizing: border-box;}
			.csv-upload-wrap form{background-color: #fff;margin-top: 50px;}
			.steps-guid-wrap p{padding-top: 70px !important;font-size: 18px;}
			.steps-guid-wrap{display: inline-block;float: left;padding: 20px;border-left: 2px solid #ddd;margin: 20px 0 20px 2%;min-height: 248px;width: 48%;box-sizing: border-box;text-align: center;}
			.input-group {position: relative;display: -ms-flexbox;display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;-ms-flex-align: stretch;align-items: stretch;width: 100%;background-color: #f1f1f1;padding: .5rem;flex: 0 0 50%;max-width: 550px;margin: auto;margin-bottom: 20px;}
			#upload {opacity: 0;flex: 1 1 auto;}
			#upload-label {position: absolute;top: 50%;left: 1rem;font-size: 16px;transform: translateY(-50%);font-weight: 100;}
			.input-group-append{display: flex;}
			.input-group-append .btn{padding: 6px 24px;border: 1px solid #fff;font-size: 16px;color: #fff;font-weight: 600;background-color: #4fc1f8;min-height: auto; line-height: initial;}			
			</style>
	</div><?php }
				public function addAdminJS() { ?>
					<script>
						function readURL(input) {
							if (input.files && input.files[0]) {
								var reader = new FileReader();
								reader.readAsDataURL(input.files[0]);
							}
						}
						var input = document.getElementById( "upload" );
						var infoArea = document.getElementById( "upload-label" );

						input.addEventListener("change", showFileName );
						function showFileName( event ) {
						  var input = event.srcElement;
						  var fileName = input.files[0].name;
						  infoArea.textContent = "File name: " + fileName;
						  document.getElementById( "but_submit" ).removeAttribute("disabled");
						}

						document.addEventListener("DOMContentLoaded", function() {
							fuw = document.getElementById("funl_upload_wrap"); 
							fuw.style.display = 'block';
							el0 = document.getElementById("posts-filter"); 
							el1 = document.getElementById("funl_how_to_section");  
							el0.parentNode.insertBefore(el1, el0.nextSibling);
							el1.style.display = 'block';
						}, false);
					
					</script> <?php
				}
			
}
