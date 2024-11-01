<?php

add_action('rest_api_init', 'lead_gen_landing_page_register_funl_api');

if ( ! function_exists( 'lead_gen_landing_page_funl_data_entry' ) ) {
	/** first create a function for processing your request*/
	function lead_gen_landing_page_funl_data_entry($request){
		$request_body = $request->get_body_params();
	
		if(!empty($request_body)) {
			global $wpdb;
			
			/*
			Check whethet page id is in table or not
			*/
			$sitepoint = array(
							"custom-post-type" => "funlhtmllandingpages"
						);
			$posts = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = %s AND id = %d", sanitize_text_field($sitepoint["custom-post-type"]), sanitize_text_field($request_body["post_id"]) ) );
			if($posts) {
				/*
				Insert lead form data in database
				*/
				/*Getting Ip Address*/
				$ipaddress = '';
				if (getenv('HTTP_CLIENT_IP'))
					$ipaddress = getenv('HTTP_CLIENT_IP');
				else if(getenv('HTTP_X_FORWARDED_FOR'))
					$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
				else if(getenv('HTTP_X_FORWARDED'))
					$ipaddress = getenv('HTTP_X_FORWARDED');
				else if(getenv('HTTP_FORWARDED_FOR'))
					$ipaddress = getenv('HTTP_FORWARDED_FOR');
				else if(getenv('HTTP_FORWARDED'))
					$ipaddress = getenv('HTTP_FORWARDED');
				else if(getenv('REMOTE_ADDR'))
					$ipaddress = getenv('REMOTE_ADDR');
				else
					$ipaddress = 'UNKNOWN';

				$table_name = $wpdb->prefix.'funl_forms';
			
				$form_post_id = sanitize_text_field( $request_body['post_id'] );
				$form_value   = sanitize_text_field( $request_body['form_data'] );
				$form_date    = current_time('Y-m-d H:i:s');
				$formdata = $formdata1 = array();
				
				parse_str($form_value, $formdata1);
				
				if($formdata1 !== '') {
					foreach($formdata1 as $k => $val){
						if(is_array($val))
								$val = $val[0];
							
						if($k !== 'leadDataReceiver' && $k !== 'slpformemailcctextinput1' && $k !== 'pageUrl' && $k !== 'redirectUrl' && 
						$k !== 'thankuMsg' && $k !== 'webhookUrl' && $k !== 'leadMail' && $k !== 'fromName' && $k !== 'leadMailContent' && $k !== 'leadMailSubject' && $k !== 'leadMailFile' && $k !== 'leadmailchimpkey' && $k !== 'leadmailchimplist' && $k !== 'leadmailchimpgroup' && $k !== 'leadmailchimpdoubleoptin' && $k !== 'leadcakemailkey' && $k !== 'leadcakemaillist' && $k !== 'leadcakemailgroup' && $k !== 'leadcakemaildoubleoptin' && $k !== 'leadcakemailpublickey' && $k !== 'googlesheetid' && $k !== 'funlgstk')
							$formdata[$k] = $val;
					}
				}
				
				$insert_id = $wpdb->insert( $table_name, array(
					'form_post_id' => $form_post_id,
					'form_value'   => serialize($formdata),
					'ip_address' => ip2long($ipaddress),
					'form_date'    => $form_date
				) );
				$response = new WP_REST_Response(array('message'=>'Successful','error'=>$wpdb->last_error));
				$response->set_status(200);
				return $response;
			} else {
				$response = new WP_REST_Response(array('message'=>'Post Not Found'));
				$response->set_status(200);
				return $response;
			}
	} else {
		return new WP_Error('invalid_request', 'Something went wrong', array('status'=>403));
	}
	}
}


if ( ! function_exists( 'lead_gen_landing_page_register_funl_api' ) ) {
	/** then create a register route callback function */
	function lead_gen_landing_page_register_funl_api(){
	register_rest_route('staticpage', 'api', array(
			'methods'=>'POST',
			'callback'=>'lead_gen_landing_page_funl_data_entry'
	));
	}
}


?>