<?php

if(is_admin())
{
    new Funl_Lead_Form();
}

/**
 * Paulund_Wp_List_Table class will create the page to load the table
 */
class Funl_Lead_Form
{
    /**
     * Constructor will create the menu item
     */
    public function __construct()
    {
		add_action('admin_menu', array($this, 'my_menu_pages'));
    }

    /**
     * Menu item will allow us to load the page to display the table
     */
	public function my_menu_pages(){
	
		add_submenu_page('edit.php?post_type=funlhtmllandingpages','Landing Page','Page Lead data','manage_options','funlleadform', array($this, 'funlleadform'));
	
	}
	 /**
     * Display the funl form data
     *
     * @return Void
     */
	public function funlleadform(){
	
		$funlLeadFormTable = new Funl_Lead_Form_Table();
        $funlLeadFormTable->prepare_items();
        ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                <h2>Page Lead data</h2>
                <?php $funlLeadFormTable->display(); ?>
            </div>
        <?php
	}

}

// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class Funl_Lead_Form_Table extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        
		global $wpdb;
        
        $table_name  = $wpdb->prefix.'funl_forms';
        $columns     = $this->get_columns();
        $hidden      = $this->get_hidden_columns();
        $data        = $this->table_data();
        $perPage     = 10;
        $currentPage = $this->get_pagenum();
        $count_forms = wp_count_posts('funl_forms');
        $totalItems  = count($data);


        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        $this->_column_headers = array($columns, $hidden );
        $this->items = $data;
		
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
		$columns = array(
            'form_id' => 'S.no', 
			'form_post_id'=> 'Page Title',            
			'ip_address' => 'IP Address',
            'email' => 'E-mail',
			'custom_data' => 'Custom Data',
            'form-date' => 'Submission Date'
        );

        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array('title' => array('title', false));
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        global $wpdb;

        $data         = array();
        $table_name   = $wpdb->prefix.'funl_forms';
        $page         = $this->get_pagenum();
        $page         = $page - 1;
        $start        = $page * 10;

        $args = array(
            'post_type'=> 'funlhtmllandingpages',
            'order'    => 'ASC',
            'posts_per_page' => 10,
            'offset' => $start
        );

        $the_query = new WP_Query( $args );
		$id_arr = array();
        while ( $the_query->have_posts() ) : $the_query->the_post();
            $form_post_id = get_the_id();
			array_push($id_arr, $form_post_id);
		endwhile;
		
		if( !empty( $id_arr ) ) {
			$totalItems   = $wpdb->get_results("SELECT * FROM $table_name WHERE form_post_id IN (".implode(",",$id_arr).")" , OBJECT );
			foreach ( $totalItems as $result ) {
				$email = '';
				$custom_data = '';
				$form_value = $result->form_value;
				
				$data_arr = unserialize($form_value);
				if(is_array($data_arr)){
					foreach ($data_arr as $key => $val) {						
												
						if (filter_var($val, FILTER_VALIDATE_EMAIL) && $email == '') {
							$email = $val;
						} else {
								$custom_data .= '<b>'.$key.'</b>' . ' : ' . $val . '<br/>';
						}
					}
				}				
				  
				$meta = get_post_meta( $result->form_post_id, 'funlhtmllandingpages_meta_box', true );
				if(isset($meta['html_permalink'])) {
					$perma_link = get_site_url()."/".$meta['html_permalink'];
				}		
					 			
				$form_values['form_post_id']  = '<a target="_blank" href="'.$perma_link.'">'.get_the_title( $result->form_post_id ).'</a>';
				$form_values['form_id'] = $result->form_id;
				$form_values['ip_address'] = long2ip($result->ip_address);
				$form_values['email'] = $email;
				$form_values['custom_data'] = $custom_data;
				$form_values['form-date'] = $result->form_date;
				$data[] = $form_values;
				
			}
		}
		return $data;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'form_post_id':
            case 'form_id':
			case 'ip_address':
            case 'email':
			case 'custom_data':
            case 'form-date':
                return $item[ $column_name ];

            default:
                return print_r( $item, true ) ;
        }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'title';
        $order = 'asc';

        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = sanitize_sql_orderby( $_GET['orderby'] );
        }

        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = sanitize_sql_orderby( $_GET['order'] );
        }


        $result = strcmp( $a[$orderby], $b[$orderby] );

        if($order === 'asc')
        {
            return $result;
        }

        return $result;
    }
}
?>