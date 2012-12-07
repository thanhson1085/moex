<?php
	class Yop_Poll_Admin extends Yop_Poll_Plugin {

		protected function init() {
			$this->add_action( 'init', 'admin_loader' );
			$this->add_action( 'init', 'my_yop_poll_button' );
			register_activation_hook( $this->_config->plugin_file, array( $this, 'activate' ) );
			register_deactivation_hook( $this->_config->plugin_file, array( $this, 'deactivate' ) );
			register_uninstall_hook( $this->_config->plugin_file, 'yop_poll_uninstall' );
			$this->add_action('admin_enqueue_scripts', 'load_editor_functions');
			$this->add_action('plugins_loaded', 'db_update');  
		}

		public function db_update() {
			global $wpdb;
			$installed_version	= get_option( "yop_poll_version" );

			//update for version 1.5
			if( version_compare ( $installed_version, '1.5', '<=' ) ) {
				$default_options						= get_option( 'yop_poll_options' );
				if ( ! isset( $default_options['vote_button_label'] ) ) {
					$default_options['vote_button_label']	= 'Vote';
				}
				update_option( "yop_poll_version", $wpdb->yop_poll_version );
				update_option( 'yop_poll_options', $default_options );
			}

			//update for version 1.6
			if( version_compare ( $installed_version, '1.6', '<=' ) ) {
				$default_options									= get_option( 'yop_poll_options' );
				if ( ! isset( $default_options['display_other_answers_values'] ) ) {
					$default_options['display_other_answers_values']	= 'no';
				}
				if ( ! isset( $default_options['percentages_decimals'] ) ) {
					$default_options['percentages_decimals']	= '0';
				}
				if ( ! isset( $default_options['plural_answer_result_votes_number_label'] ) ) {
					$default_options['singular_answer_result_votes_number_label']	= 'vote';
				}
				if ( ! isset( $default_options['plural_answer_result_votes_number_label'] ) ) {
					$default_options['plural_answer_result_votes_number_label']	= 'votes';
				}
				update_option( "yop_poll_version", $wpdb->yop_poll_version );
				update_option( 'yop_poll_options', $default_options );
			}
		}

		public function admin_loader() { 
			$this->add_action( 'plugins_loaded', 'load_translation_file', 1 );
			$this->add_action( 'admin_menu', 'admin_menu', 1 );	
			$this->add_action( 'admin_init', 'yop_poll_options_admin_init', 1 );
			$this->add_action( 'wp_ajax_yop_poll_editor', 'ajax_get_polls_for_editor', 1 );
			$this->add_action( 'wp_ajax_yop_poll_html_editor', 'ajax_get_polls_for_html_editor', 1 );
			$this->add_action( 'wp_ajax_yop_poll_edit_add_new_poll', 'ajax_edit_add_new_poll', 1 );
			$this->add_action( 'wp_ajax_yop_poll_edit_add_new_poll_template', 'ajax_edit_add_new_poll_template', 1 );

			$this->add_action( 'wp_ajax_nopriv_yop_poll_do_vote', 'yop_poll_do_vote', 1 );
			$this->add_action( 'wp_ajax_yop_poll_do_vote', 'yop_poll_do_vote', 1 );

			$this->add_action( 'wp_ajax_nopriv_yop_poll_view_results', 'yop_poll_view_results', 1 );
			$this->add_action( 'wp_ajax_yop_poll_view_results', 'yop_poll_view_results', 1 );

			$this->add_action( 'wp_ajax_yop_poll_back_to_vote', 'yop_poll_back_to_vote', 1 );
			$this->add_action( 'wp_ajax_nopriv_yop_poll_back_to_vote', 'yop_poll_back_to_vote', 1 );

			$this->add_action( 'wp_ajax_yop_poll_load_css', 'yop_poll_load_css', 1 );
			$this->add_action( 'wp_ajax_nopriv_yop_poll_load_css', 'yop_poll_load_css', 1 );

			$this->add_action( 'wp_ajax_yop_poll_load_js', 'yop_poll_load_js', 1 );
			$this->add_action( 'wp_ajax_nopriv_yop_poll_load_js', 'yop_poll_load_js', 1 );
		}

		/**
		* this file is execute on activation
		* it creates the database and add some data to database's tables
		* 
		*/
		public function activate() {
			global $wp_version;
			if( ! version_compare( $wp_version, YOP_POLL_WP_VERSION, '>=' ) ) {
				$error = new WP_Error( 'Wordpress_version_error', sprintf( __( 'You need at least Wordpress version %s to use this plugin', 'yop_poll' ), YOP_POLL_WP_VERSION ), __( 'Error: Wordpress Version Problem', 'yop_poll' ) );

				// die & print error message & code - for admins only!
				if ( isset( $error ) && is_wp_error( $error ) && current_user_can( 'manage_options' ) ) 
					wp_die( $error -> get_error_message(), $error -> get_error_data() );
			}
			else {
				if ( ! extension_loaded('json') ) {
					$error = new WP_Error( 'Wordpress_json_error', __( 'You need the  json php extension for this plugin', 'yop_poll' ), __( 'Error: Wordpress Extension Problem', 'yop_poll' ) );

					// die & print error message & code - for admins only!
					if ( isset( $error ) && is_wp_error( $error ) && current_user_can( 'manage_options' ) ) 
						wp_die( $error -> get_error_message(), $error -> get_error_data() );
				}
				// including upgrade.php for using dbDelta() 
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				// including db_schema class file
				require_once( YOP_POLL_INC . '/' . 'db_schema.php');
				//create tables
				Yop_Poll_DbSchema::create_poll_database_tables();
			}
		}

		/**
		* this file initialize the text domain for translation file
		* 
		*/
		public function load_translation_file() {
			$plugin_path = $this->_config->plugin_dir . '/' . $this->_config->languages_dir;
			load_plugin_textdomain( 'yop_poll', false, $plugin_path );
		}

		public function deactivate() {
			$poll_archive_page	= get_page_by_path('yop-poll-archive', ARRAY_A );
			if ( $poll_archive_page ) {
				$poll_archive_page_id	= $poll_archive_page['ID'];
				wp_delete_post( $poll_archive_page_id, true );
			}
		}

		public function admin_menu() {
			if ( is_admin() && current_user_can( 'edit_posts' ) ) {
				if (function_exists('add_menu_page')) {
					$page = add_menu_page( __( 'Yop Poll', 'yop_poll' ), __( 'Yop Poll', 'yop_poll' ), 'edit_posts', 'yop-polls', array( $this, 'manage_pages' ), "{$this->_config->plugin_url}/images/yop-poll-admin-menu-icon16.png", 28 );
				}
				add_action( "load-$page", array( &$this, 'manage_pages_load' ) );
				if (function_exists('add_submenu_page')) {
					$subpage = add_submenu_page( 'yop-polls', __( 'All Yop Polls', 'yop_poll' ), __( 'All Yop Polls', 'yop_poll' ), 'edit_posts', 'yop-polls', array( &$this, 'manage_pages' ) );
					add_action( "load-$subpage", array( &$this, 'manage_pages_load' ) );
					$subpage = add_submenu_page( 'yop-polls', __( 'Add New Yop Poll', 'yop_poll' ), __( 'Add New Yop Poll', 'yop_poll' ), 'edit_posts', 'yop-polls-add-new', array( &$this, 'manage_pages' ) );
					add_action( "load-$subpage", array( &$this, 'manage_pages_load' ) );
					$subpage = add_submenu_page( 'yop-polls', __( 'Yop Poll Options', 'yop_poll' ), __( 'Yop Poll Options', 'yop_poll' ), 'manage_options', 'yop-polls-options', array( &$this, 'manage_pages' ) );
					add_action( "load-$subpage", array( &$this, 'manage_pages_load' ) );
					$subpage = add_submenu_page( 'yop-polls', __( 'Yop Poll Templates', 'yop_poll' ), __( 'Yop Poll Templates', 'yop_poll' ), 'manage_options', 'yop-polls-templates', array( &$this, 'manage_pages' ) );
					add_action( "load-$subpage", array( &$this, 'manage_pages_load' ) );
					$subpage = add_submenu_page( 'yop-polls', __( 'Yop Poll Logs', 'yop_poll' ), __( 'Yop Poll Logs', 'yop_poll' ), 'manage_options', 'yop-polls-logs', array( &$this, 'manage_pages' ) );
					add_action( "load-$subpage", array( &$this, 'manage_pages_load' ) );
					$subpage = add_submenu_page( 'yop-polls', __( 'Yop Poll Bans', 'yop_poll' ), __( 'Yop Poll Bans', 'yop_poll' ), 'manage_options', 'yop-polls-bans', array( &$this, 'manage_pages' ) );
					add_action( "load-$subpage", array( &$this, 'manage_pages_load' ) );
				}
			}			
		}

		public function manage_pages() {
			global $page, $action;
			switch ( $page ) {
				case 'yop-polls':
					if ( 'custom-fields' == $action ) {
						$this->view_poll_custom_fields();
						break;
					}
					if ( 'results'	== $action ) {
						$this->view_poll_results();
						break;
					}
					elseif ( 'edit' == $action ) {
					}
					else {
						$this->view_all_polls();
						break;
					}
				case 'yop-polls-add-new':
					$this->view_add_edit_new_poll();
					break;
				case 'yop-polls-options' :
					$this->view_yop_poll_options();
					break;
				case 'yop-polls-logs' :
					$this->view_yop_poll_logs();
					break;
				case 'yop-polls-bans' :
					$this->view_yop_poll_bans();
					break;
				case 'yop-polls-templates' :
					if( 'add-new' == $action || 'edit' == $action) {
						$this -> view_add_edit_poll_template();
					}
					else {
						$this->view_yop_poll_templates();
					}
					break;
				default:
					$this->view_all_polls();
			}
		}

		public function manage_pages_load() {
			wp_reset_vars( array( 'page', 'action', 'orderby', 'order' ) );
			global $page, $action, $orderby, $order, $yop_poll_add_new_config;
			$default_options = get_option( 'yop_poll_options', array() );

			wp_enqueue_script('jquery'); 

			wp_enqueue_style( 'yop-poll-admin', "{$this->_config->plugin_url}/css/yop-poll-admin.css", array(), $this->_config->version );
			wp_enqueue_script( 'yop-poll-admin', "{$this->_config->plugin_url}/js/yop-poll-admin.js", array(), $this->_config->version );
			$answers_number			= $this->_config->min_number_of_answers + 1; //total +1
			$customfields_number	= $this->_config->min_number_of_customfields + 1; //total +1

			switch ( $page ) {
				case 'yop-polls':
					if ( 'results' == $action ) {
						wp_enqueue_style( 'yop-poll-admin-results', "{$this->_config->plugin_url}/css/yop-poll-admin-results.css", array(), $this->_config->version );
						wp_enqueue_style( 'yop-poll-timepicker', "{$this->_config->plugin_url}/css/timepicker.css", array(), $this->_config->version );
						wp_enqueue_style( 'yop-poll-jquery-ui', "{$this->_config->plugin_url}/css/jquery-ui.css", array(), $this->_config->version );
						wp_enqueue_script( 'yop-poll-jquery-ui-timepicker', "{$this->_config->plugin_url}/js/jquery-ui-timepicker-addon.js",array( 'jquery-ui-datepicker', 'jquery-ui-slider' ), $this->_config->version);
						wp_enqueue_style( 'yop-poll-admin-custom-fields', "{$this->_config->plugin_url}/css/yop-poll-admin-custom-fields.css", array(), $this->_config->version );
						wp_enqueue_script( 'yop-poll-admin-custom-fields', "{$this->_config->plugin_url}/js/yop-poll-admin-custom-fields.js", array('jquery', 'yop-poll-jquery-ui-timepicker'), $this->_config->version );
						$this->yop_poll_custom_fields_results_operations();
						break;
					}
					if ( 'custom-fields' == $action ) {
						wp_enqueue_style( 'yop-poll-timepicker', "{$this->_config->plugin_url}/css/timepicker.css", array(), $this->_config->version );
						wp_enqueue_style( 'yop-poll-jquery-ui', "{$this->_config->plugin_url}/css/jquery-ui.css", array(), $this->_config->version );
						wp_enqueue_script( 'yop-poll-jquery-ui-timepicker', "{$this->_config->plugin_url}/js/jquery-ui-timepicker-addon.js",array( 'jquery-ui-datepicker', 'jquery-ui-slider' ), $this->_config->version);
						wp_enqueue_style( 'yop-poll-admin-custom-fields', "{$this->_config->plugin_url}/css/yop-poll-admin-custom-fields.css", array(), $this->_config->version );
						wp_enqueue_script( 'yop-poll-admin-custom-fields', "{$this->_config->plugin_url}/js/yop-poll-admin-custom-fields.js", array( 'jquery', 'yop-poll-jquery-ui-timepicker' ), $this->_config->version );
						$this->yop_poll_custom_fields_operations();
						break;
					}
					elseif ( 'edit' == $action ) {
						require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');						
						$poll_id				= ( isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0 );
						$yop_poll_model			= new Yop_Poll_Model( $poll_id );
						$answers				= Yop_Poll_Model::get_poll_answers( $poll_id );
						$answers_number 		= count( $answers ) + 1; //total +1
						$custom_fields			= Yop_Poll_Model::get_poll_customfields( $poll_id );
						$customfields_number 	= count( $custom_fields ) + 1; //total +1
					}
					else {
						$this->view_all_polls_operations(); 
						break;	
					}

				case 'yop-polls-add-new':
					$yop_poll_add_new_config = array(
						'ajax' => array(
							'url' => admin_url('admin-ajax.php', (is_ssl() ? 'https' : 'http')),
							'action' => 'yop_poll_edit_add_new_poll',
							'beforeSendMessage' => __( 'Please wait a moment while we process your request...', 'yop_poll' ),
							'errorMessage' => __( 'An error has occured...', 'yop_poll' )
						),
						'text_answer' => __('Answer', 'yop_poll'),
						'text_customfield' => __('Custom Text Field', 'yop_poll'),
						'text_requiered_customfield' => __('Required', 'yop_poll'),
						'text_remove_answer' => __('Remove', 'yop_poll'),
						'text_remove_customfield' => __('Remove', 'yop_poll'),
						'text_customize_answer' => __('Customize Toggle', 'yop_poll'),
						'plugin_url' => $this->_config->plugin_url,
						'default_number_of_answers' => $answers_number, 
						'default_number_of_customfields' => $customfields_number,  
						'text_poll_bar_style' => array( 
							'use_template_bar_label' => __( 'Use Template Result Bar', 'yop_poll' ), 
							'use_template_bar_yes_label' => __( 'Yes', 'yop_poll' ), 
							'use_template_bar_no_label' => __( 'No', 'yop_poll' ), 
							'poll_bar_style_label' => __( 'Yop Poll Bar Style', 'yop_poll' ), 
							'poll_bar_preview_label' => __( 'Yop Poll Bar Preview', 'yop_poll' ), 
							'poll_bar_style_background_label' => __( 'Background Color', 'yop_poll' ), 
							'poll_bar_style_height_label' => __( 'Height', 'yop_poll' ), 
							'poll_bar_style_border_color_label' => __( 'Border Color', 'yop_poll' ), 
							'poll_bar_style_border_width_label' => __( 'Border Width', 'yop_poll' ), 
							'poll_bar_style_border_style_label' => __( 'Border Style', 'yop_poll' ), 
						),
						'poll_bar_default_options' => array(
							'use_template_bar'	=> isset( $default_options['use_template_bar'] ) ? $default_options['use_template_bar'] : 'yes',
							'height'			=> isset( $default_options['bar_height'] ) ? $default_options['bar_height'] : 10,
							'background_color'	=> isset( $default_options['bar_background'] ) ? $default_options['bar_background'] : 'd8e1eb',
							'border'			=> isset( $default_options['bar_border_style'] ) ? $default_options['bar_border_style'] : 'solid',
							'border_width'		=> isset( $default_options['bar_border_width'] ) ? $default_options['bar_border_width'] : 1,
							'border_color'		=> isset( $default_options['bar_border_color'] ) ? $default_options['bar_border_color'] : 'c8c8c8',
						),
					);
					wp_enqueue_style( 'yop-poll-admin-add-new', "{$this->_config->plugin_url}/css/yop-poll-admin-add-new.css", array(), $this->_config->version );
					wp_enqueue_style( 'yop-poll-timepicker', "{$this->_config->plugin_url}/css/timepicker.css", array(), $this->_config->version );
					wp_enqueue_style( 'yop-poll-jquery-ui', "{$this->_config->plugin_url}/css/jquery-ui.css", array(), $this->_config->version );

					wp_enqueue_script( 'yop-poll-admin-add-new', "{$this->_config->plugin_url}/js/yop-poll-admin-add-new.js", array( 'jquery', 'yop-poll-jquery-ui-timepicker' ), $this->_config->version );
					wp_enqueue_script( 'yop-poll-jquery-ui-timepicker', "{$this->_config->plugin_url}/js/jquery-ui-timepicker-addon.js",array( 'jquery-ui-datepicker', 'jquery-ui-slider' ), $this->_config->version);
					wp_localize_script( 'yop-poll-admin-add-new', 'yop_poll_add_new_config', $yop_poll_add_new_config );
					wp_enqueue_script('link');
					wp_enqueue_script('xfn');
					break;
				case 'yop-polls-logs' :
					$this->view_yop_poll_logs_operations();
					break;
				case 'yop-polls-bans' :
					wp_enqueue_script( 'yop-poll-admin-bans', "{$this->_config->plugin_url}/js/yop-poll-admin-bans.js", array( 'jquery' ), $this->_config->version );
					$this->view_yop_poll_bans_operations();
					break;
				case 'yop-polls-options' :
					wp_enqueue_style( 'yop-poll-admin-options', "{$this->_config->plugin_url}/css/yop-poll-admin-options.css", array(), $this->_config->version );
					wp_enqueue_style( 'yop-poll-timepicker', "{$this->_config->plugin_url}/css/timepicker.css", array(), $this->_config->version );
					wp_enqueue_style( 'yop-poll-jquery-ui', "{$this->_config->plugin_url}/css/jquery-ui.css", array(), $this->_config->version );
					wp_enqueue_script( 'yop-poll-admin-options', "{$this->_config->plugin_url}/js/yop-poll-admin-options.js", array( 'jquery', 'yop-poll-jquery-ui-timepicker' ), $this->_config->version );
					wp_enqueue_script( 'yop-poll-jquery-ui-timepicker', "{$this->_config->plugin_url}/js/jquery-ui-timepicker-addon.js",array( 'jquery-ui-datepicker', 'jquery-ui-slider' ), $this->_config->version);
					wp_enqueue_script('link');
					wp_enqueue_script('xfn');
					break;
				case 'yop-polls-templates' :
					wp_enqueue_script(array('editor', 'thickbox'));
					wp_enqueue_style('thickbox');
					wp_enqueue_style( 'yop-poll-admin-templates', "{$this->_config->plugin_url}/css/yop-poll-admin-templates.css", array(), $this->_config->version );
					wp_enqueue_script( 'yop-poll-admin-templates', "{$this->_config->plugin_url}/js/yop-poll-admin-templates.js", array( 'jquery' ), $this->_config->version );
					$yop_poll_add_new_template_config = array(
						'ajax' => array(
							'url' => admin_url('admin-ajax.php', (is_ssl() ? 'https' : 'http')),
							'action' => 'yop_poll_edit_add_new_poll_template',
							'beforeSendMessage' => __( 'Please wait a moment while we process your request...', 'yop_poll' ),
							'errorMessage' => __( 'An error has occured...', 'yop_poll' )
						)
					);
					wp_localize_script( 'yop-poll-admin-templates', 'yop_poll_add_new_template_config', $yop_poll_add_new_template_config );
					$this->view_yop_poll_templates_operations();
					break;
				default :
					$this->view_all_polls_operations(); 
					break;
			}
		}

		/**
		* Start operations section
		*/	

		public function view_yop_poll_logs_operations() {
			global $page, $action;
			if ( '-1' != $action && isset( $_REQUEST['yoppolllogscheck'] ) ) {
				if ( 'delete' == $action ) {
					check_admin_referer( 'yop-poll-logs' );
					$bulklogs	= (array) $_REQUEST['yoppolllogscheck'];
					require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
					foreach ( $bulklogs as $log_id ) {
						$log_id = (int) $log_id;
						Yop_Poll_Model::delete_poll_log_from_db( $log_id );
					}
					wp_redirect( add_query_arg('deleted', count( $bulklogs ), remove_query_arg( array( '_wp_http_referer', '_wpnonce', 'yoppolllogscheck' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) ) );
					exit;		
				}
			}
			elseif ( '-1' != $action && isset( $_REQUEST['id'] ) ) {
				if ( 'delete' == $action ) {
					check_admin_referer( 'yop-poll-logs-delete' );
					$log_id = (int) $_REQUEST['id'];
					require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
					Yop_Poll_Model::delete_poll_log_from_db( $log_id );
					wp_redirect( add_query_arg('deleted', 1, remove_query_arg( array( '_wpnonce', 'id', 'action' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) ) );
					exit;
				}
			}
			elseif ( ! empty( $_GET['_wp_http_referer'] ) ) {
				wp_safe_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) );
				exit;
			}
		}

		public function yop_poll_custom_fields_operations() {
			global $page, $action;                            
			if ( isset( $_REQUEST['export'] ) ) {
				check_admin_referer( 'yop-poll-custom-fields' );
				if( '' != $_REQUEST['export'] ) {
					$per_page						= ( isset( $_GET['per_page'] ) ? intval( $_GET['per_page'] ) : 100 );
					$page_no						= ( isset( $_REQUEST['page_no'] ) ? (int) $_REQUEST['page_no'] : 1 );
					$poll_id						= ( isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0 );	
					$sdate							= ( isset( $_GET['sdate'] ) ?  $_GET['sdate'] : '' );	
					$edate							= ( isset( $_GET['edate'] ) ? $_GET['edate'] : '' );	
					require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
					$poll_details					= YOP_POLL_MODEL::get_poll_from_database_by_id( $poll_id );
					$poll_custom_fields				= YOP_POLL_MODEL::get_poll_customfields( $poll_id );
					$custom_fields_number			= count( $poll_custom_fields );
					$column_custom_fields_ids		= array();
					$total_custom_fields_logs		= YOP_POLL_MODEL::get_poll_total_customfields_logs( $poll_id, $sdate, $edate );
					$total_custom_fields_logs_pages	= ceil(  $total_custom_fields_logs / $per_page );
					if ( intval( $page_no) > intval( $total_custom_fields_logs_pages ) )
						$page_no = 1;

					if ( 'all' == $_REQUEST['export'] )
						$custom_fields_logs				= YOP_POLL_MODEL::get_poll_customfields_logs( $poll_id, 'vote_id', 'asc', 0, 99999999, $sdate, $edate );
					if ( 'page' == $_REQUEST['export'] )
						$custom_fields_logs				= YOP_POLL_MODEL::get_poll_customfields_logs( $poll_id, 'vote_id', 'asc', ( $page_no - 1 ) * $per_page, $per_page, $sdate, $edate );

					$csv_file_name					= 'custom_fields_export.'.date('YmdHis').'.csv';
					$csv_header_array 				= array( __('#', 'yop_poll' ) );
					foreach ( $poll_custom_fields as $custom_field ) {
						$column_custom_fields_ids[]		= $custom_field['id']; 
						$csv_header_array[]				= ucfirst( $custom_field['custom_field'] ); 
					}
					$csv_header_array[]				= __('Vote Date', 'yop_poll' );

					header('Content-type: application/csv');
					header('Content-Disposition: attachment; filename="' . $csv_file_name . '"');
					ob_start();
					$f = fopen('php://output', 'w') or show_error( __( "Can't open php://output!", 'yop_poll' ) );
					$n = 0;
					if (isset($csv_header_array))
						if ( ! fputcsv( $f, $csv_header_array ) )
							_e("Can't write header!", 'yop_poll');

						if ( count( $custom_fields_logs ) > 0 ) {
						$index	= 1;
						foreach( $custom_fields_logs as $logs) {
							$column_custom_fields_values	= array( $index );
							foreach ( $column_custom_fields_ids as $custom_field_id ) {
								$vote_log_values	= array();
								$vote_logs	= explode( ',', $logs['vote_log'] );
								if( count( $vote_logs ) > 0 ) {
									foreach ( $vote_logs as $vote_log ) {
										$temp							= explode( '-', $vote_log );
										$vote_log_values[ $temp[1] ]	= stripslashes( $temp[0] ); 	
									}
								}
								$column_custom_fields_values[]			= isset( $vote_log_values[ $custom_field_id ] ) ? $vote_log_values[ $custom_field_id ] : '';
							}
							$column_custom_fields_values[]				= $logs['vote_date'];
							if ( !fputcsv($f, $column_custom_fields_values))
								_e("Can't write record!", 'yop_poll');
							$index++;
						}
					}
					fclose($f) or show_error( __( "Can't close php://output!", 'yop_poll' ) );
					$csvStr = ob_get_contents();
					ob_end_clean();

					echo $csvStr;
					exit;
				}
				wp_safe_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce', 'export', 'a' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) );
				exit;
			}
			elseif ( ! empty( $_GET['_wp_http_referer'] ) ) {
				wp_safe_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce', 'a' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) );
				exit;
			}
		}

		public function yop_poll_custom_fields_results_operations() {
			global $page, $action;                            
			if ( isset( $_REQUEST['export'] ) ) {
				check_admin_referer( 'yop-poll-custom-fields' );
				if( __( 'Export', 'yop_poll' ) == $_REQUEST['a'] ) {
					$cf_per_page						= ( isset( $_GET['cf_per_page'] ) ? intval( $_GET['cf_per_page'] ) : 100 );
					$cf_page_no						= ( isset( $_REQUEST['cf_page_no'] ) ? (int) $_REQUEST['cf_page_no'] : 1 );
					$poll_id						= ( isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0 );	
					$cf_sdate							= ( isset( $_GET['cf_sdate'] ) ?  $_GET['cf_sdate'] : '' );	
					$cf_edate							= ( isset( $_GET['cf_edate'] ) ? $_GET['cf_edate'] : '' );	
					require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
					$poll_details					= YOP_POLL_MODEL::get_poll_from_database_by_id( $poll_id );
					$poll_custom_fields				= YOP_POLL_MODEL::get_poll_customfields( $poll_id );
					$custom_fields_number			= count( $poll_custom_fields );
					$column_custom_fields_ids		= array();
					$total_custom_fields_logs		= YOP_POLL_MODEL::get_poll_total_customfields_logs( $poll_id, $cf_sdate, $cf_edate );
					$total_custom_fields_logs_pages	= ceil(  $total_custom_fields_logs / $cf_per_page );
					if ( intval( $cf_page_no) > intval( $total_custom_fields_logs_pages ) )
						$cf_page_no = 1;

					if ( 'all' == $_REQUEST['export'] )
						$custom_fields_logs				= YOP_POLL_MODEL::get_poll_customfields_logs( $poll_id, 'vote_id', 'asc', 0, 99999999, $cf_sdate, $cf_edate );
					if ( 'page' == $_REQUEST['export'] )
						$custom_fields_logs				= YOP_POLL_MODEL::get_poll_customfields_logs( $poll_id, 'vote_id', 'asc', ( $cf_page_no - 1 ) * $cf_per_page, $cf_per_page, $cf_sdate, $cf_edate );

					$csv_file_name					= 'custom_fields_export.'.date('YmdHis').'.csv';
					$csv_header_array 				= array( __('#', 'yop_poll' ) );
					foreach ( $poll_custom_fields as $custom_field ) {
						$column_custom_fields_ids[]		= $custom_field['id']; 
						$csv_header_array[]				= ucfirst( $custom_field['custom_field'] ); 
					}
					$csv_header_array[]				= __('Vote Date', 'yop_poll' );

					header('Content-type: application/csv');
					header('Content-Disposition: attachment; filename="' . $csv_file_name . '"');
					ob_start();
					$f = fopen('php://output', 'w') or show_error( __( "Can't open php://output!", 'yop_poll' ) );
					$n = 0;
					if (isset($csv_header_array))
						if ( ! fputcsv( $f, $csv_header_array ) )
							_e("Can't write header!", 'yop_poll');

						if ( count( $custom_fields_logs ) > 0 ) {
						$index	= 1;
						foreach( $custom_fields_logs as $logs) {
							$column_custom_fields_values	= array( $index );
							foreach ( $column_custom_fields_ids as $custom_field_id ) {
								$vote_log_values	= array();
								$vote_logs	= explode( ',', $logs['vote_log'] );
								if( count( $vote_logs ) > 0 ) {
									foreach ( $vote_logs as $vote_log ) {
										$temp							= explode( '-', $vote_log );
										$vote_log_values[ $temp[1] ]	= stripslashes( $temp[0] ); 	
									}
								}
								$column_custom_fields_values[]			= isset( $vote_log_values[ $custom_field_id ] ) ? $vote_log_values[ $custom_field_id ] : '';
							}
							$column_custom_fields_values[]				= $logs['vote_date'];
							if ( !fputcsv($f, $column_custom_fields_values))
								_e("Can't write record!", 'yop_poll');
							$index++;
						}
					}
					fclose($f) or show_error( __( "Can't close php://output!", 'yop_poll' ) );
					$csvStr = ob_get_contents();
					ob_end_clean();

					echo $csvStr;
					exit;
				}
				wp_safe_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce', 'export', 'a' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) );
				exit;
			}
			elseif ( ! empty( $_GET['_wp_http_referer'] ) ) {
				wp_safe_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce', 'a' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) );
				exit;
			}
		}

		public function view_yop_poll_bans_operations() {
			global $page, $action;
			if ( '-1' != $action && isset( $_REQUEST['yoppollbanscheck'] ) ) {
				if ( 'delete' == $action ) {
					check_admin_referer( 'yop-poll-bans' );
					$bulkbans	= (array) $_REQUEST['yoppollbanscheck'];
					require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
					foreach ( $bulkbans as $ban_id ) {
						$ban_id = (int) $ban_id;
						Yop_Poll_Model::delete_poll_ban_from_db( $ban_id );
					}
					wp_redirect( add_query_arg('deleted', count( $bulkbans ), remove_query_arg( array( '_wp_http_referer', '_wpnonce', 'yoppollbanscheck' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) ) );
					exit;		
				}
			}
			elseif ( '-1' != $action && isset( $_REQUEST['id'] ) ) {
				if ( 'delete' == $action ) {
					check_admin_referer( 'yop-poll-bans-delete' );
					$ban_id = (int) $_REQUEST['id'];
					require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
					Yop_Poll_Model::delete_poll_ban_from_db( $ban_id );
					wp_redirect( add_query_arg('deleted', 1, remove_query_arg( array( '_wpnonce', 'id', 'action' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) ) );
					exit;
				}
			}
			elseif( 'add-ban' == $action ) {
				check_admin_referer( 'yop-poll-add-ban' );
				require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
				$bans	= Yop_Poll_Model::add_bans( $_REQUEST );
				if( $bans['error'] != '' ) {
					wp_redirect( add_query_arg('bans-error', urlencode($bans['error']), remove_query_arg( array( '_wpnonce', 'id', 'action' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) ) );
					exit;
				}
				else {
					wp_redirect( add_query_arg('bans-added', urlencode((int)$bans['success']), remove_query_arg( array( '_wpnonce', 'id', 'action' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) ) );
					exit;	
				}	
			}
			elseif ( ! empty( $_GET['_wp_http_referer'] ) ) {
				wp_safe_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) );
				exit;
			}
		}

		public function view_yop_poll_templates_operations() {
			global $page, $action, $yop_poll_add_new_config;
			if ( '-1' != $action && isset( $_REQUEST['templatecheck'] ) ) {
				if ( 'delete' == $action ) {
					check_admin_referer( 'yop-poll-templates' );
					$bulktemplates	= (array) $_REQUEST['templatecheck'];
					require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
					foreach ( $bulktemplates as $template_id ) {
						$template_id = (int) $template_id;
						Yop_Poll_Model::delete_poll_template_from_db( $template_id );
					}
					wp_redirect( add_query_arg('deleted', count( $bulktemplates ), remove_query_arg( array( '_wp_http_referer', '_wpnonce', 'templatecheck' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) ) );
					exit;		
				}
				if ( 'clone' == $action ) {
					check_admin_referer( 'yop-poll-templates' );
					$bulktemplates = (array) $_REQUEST['templatecheck'];
					require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
					foreach ( $bulktemplates as $template_id ) {
						$template_id = (int) $template_id;
						Yop_Poll_Model::clone_poll_template( $template_id );
					}
					wp_redirect( add_query_arg('cloned', count( $bulktemplates ), remove_query_arg( array( '_wp_http_referer', '_wpnonce', 'templatecheck' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) ) );
					exit;
				}
			}
			elseif ( '-1' != $action && isset( $_REQUEST['id'] ) ) {
				if ( 'delete' == $action ) {
					check_admin_referer( 'yop-poll-templates' );
					$template_id = (int) $_REQUEST['id'];
					require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
					Yop_Poll_Model::delete_poll_template_from_db( $template_id );
					wp_redirect( add_query_arg('deleted', 1, remove_query_arg( array( '_wpnonce', 'id', 'action' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) ) );
					exit;
				}
				if ( 'clone' == $action ) {
					check_admin_referer( 'yop-poll-templates' );
					$template_id = (int) $_REQUEST['id'];
					require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
					Yop_Poll_Model::clone_poll_template( $template_id );
					wp_redirect( add_query_arg('cloned', 1, remove_query_arg( array( '_wpnonce', 'id', 'action' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) ) );
					exit;
				}
			}
			elseif ( ! empty( $_GET['_wp_http_referer'] ) ) {
				wp_safe_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) );
				exit;
			}
		}

		public function view_all_polls_operations() {
			global $page, $action, $yop_poll_add_new_config;
			if ( '-1' != $action && isset( $_REQUEST['yoppollcheck'] ) ) {
				if ( 'delete' == $action ) {
					check_admin_referer( 'yop-poll-view' );
					$bulkyoppolls = (array) $_REQUEST['yoppollcheck'];
					require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
					foreach ( $bulkyoppolls as $yoppoll_id ) {
						$yoppoll_id = (int) $yoppoll_id;
						Yop_Poll_Model::delete_poll_from_db( $yoppoll_id );
					}
					wp_redirect( add_query_arg('deleted', count( $bulkyoppolls ), remove_query_arg( array( '_wp_http_referer', '_wpnonce', 'yoppollcheck' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) ) );
					exit;		
				}
				if ( 'clone' == $action ) {
					check_admin_referer( 'yop-poll-view' );
					$bulkyoppolls = (array) $_REQUEST['yoppollcheck'];
					require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
					foreach ( $bulkyoppolls as $yoppoll_id ) {
						$yoppoll_id = (int) $yoppoll_id;
						Yop_Poll_Model::clone_poll( $yoppoll_id );
					}
					wp_redirect( add_query_arg('cloned', count( $bulkyoppolls ), remove_query_arg( array( '_wp_http_referer', '_wpnonce', 'yoppollcheck' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) ) );
					exit;
				}
				if ( 'reset_votes' == $action ) {
					check_admin_referer( 'yop-poll-view' );
					$bulkyoppolls = (array) $_REQUEST['yoppollcheck'];
					require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
					foreach ( $bulkyoppolls as $yoppoll_id ) {
						$yoppoll_id = (int) $yoppoll_id;
						Yop_Poll_Model::reset_votes_for_poll( $yoppoll_id );
					}
					wp_redirect( add_query_arg('reseted_votes', count( $bulkyoppolls ), remove_query_arg( array( '_wp_http_referer', '_wpnonce', 'yoppollcheck' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) ) );
					exit;
				}
				if ( 'delete_logs' == $action ) {
					check_admin_referer( 'yop-poll-view' );
					$bulkyoppolls = (array) $_REQUEST['yoppollcheck'];
					require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
					foreach ( $bulkyoppolls as $yoppoll_id ) {
						$yoppoll_id = (int) $yoppoll_id;
						Yop_Poll_Model::delete_all_poll_logs( $yoppoll_id );
					}
					wp_redirect( add_query_arg('deleted_logs', count( $bulkyoppolls ), remove_query_arg( array( '_wp_http_referer', '_wpnonce', 'yoppollcheck' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) ) );
					exit;
				}
			}
			elseif ( '-1' != $action && isset( $_REQUEST['id'] ) ) {
				if ( 'delete' == $action ) {
					check_admin_referer( 'yop-poll-delete' );
					$yoppoll_id = (int) $_REQUEST['id'];
					require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
					Yop_Poll_Model::delete_poll_from_db( $yoppoll_id );
					wp_redirect( add_query_arg('deleted', 1, remove_query_arg( array( '_wpnonce', 'id', 'action' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) ) );
					exit;
				}

				if ( 'clone' == $action ) {
					check_admin_referer( 'yop-poll-clone' );
					$yoppoll_id = (int) $_REQUEST['id'];
					require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
					Yop_Poll_Model::clone_poll( $yoppoll_id );
					wp_redirect( add_query_arg('cloned', 1, remove_query_arg( array( '_wpnonce', 'id', 'action' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) ) );
					exit;
				}

				if ( 'reset_votes' == $action ) {
					check_admin_referer( 'yop-poll-reset-votes' );
					$yoppoll_id = (int) $_REQUEST['id'];
					require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
					Yop_Poll_Model::reset_votes_for_poll( $yoppoll_id );
					wp_redirect( add_query_arg('reseted_votes', 1, remove_query_arg( array( '_wpnonce', 'id', 'action' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) ) );
					exit;
				}

				if ( 'delete_logs' == $action ) {
					check_admin_referer( 'yop-poll-delete-logs' );
					$yoppoll_id = (int) $_REQUEST['id'];
					require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
					Yop_Poll_Model::delete_all_poll_logs( $yoppoll_id );
					wp_redirect( add_query_arg('deleted_logs', 1, remove_query_arg( array( '_wpnonce', 'id', 'action' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) ) );
					exit;
				}
			}
			elseif ( ! empty( $_GET['_wp_http_referer'] ) ) {
				wp_safe_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) );
				exit;
			}

		}

		/**
		* End operations section
		*/

		/**
		* Start Views section
		*/
		public function view_all_polls() {
			global $page, $action, $orderby, $order;
			$orderby	= ( empty( $orderby ) ) ? 'name' : $orderby;
			$order_direction	= array(
				'id'			=> 'asc', 
				'name'			=> 'asc', 
				'question'		=> 'asc', 
				'start_date'	=> 'asc', 
				'end_date'		=> 'asc', 
				'total_votes'	=> 'asc', 
				'total_voters'	=> 'asc', 
			);
			$order_direction[ $orderby ] = ( 'desc' == $order ) ? 'asc' : 'desc';

			$order_direction_reverse	= array(
				'id'			=> 'desc', 
				'name'			=> 'desc', 
				'question'		=> 'desc', 
				'start_date'	=> 'desc', 
				'end_date'		=> 'desc', 
				'total_votes'	=> 'desc', 
				'total_voters'	=> 'desc', 
			);
			$order_direction_reverse[ $orderby ]	= ( 'desc' == $order ) ? 'desc' : 'asc';

			$order_sortable	= array(
				'id'			=> 'sortable', 
				'name'			=> 'sortable', 
				'question'		=> 'sortable', 
				'start_date'	=> 'sortable', 
				'end_date'		=> 'sortable', 
				'total_votes'	=> 'sortable', 
				'total_voters'	=> 'sortable', 
			);
			$order_sortable[ $orderby ]	= 'sorted';
			require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
			$filter = array( 'field' => NULL, 'value' => NULL, 'operator' => '=' );
			if( isset ( $_REQUEST['filters'] ) ) {
				switch ( $_REQUEST['filters'] ) {
					case 'started':
						$filter = array( 'field' => 'start_date', 'value' => YOP_POLL_MODEL::get_mysql_curent_date(), 'operator' => '<='  );
						break;
					case 'not_started':
						$filter = array( 'field' => 'start_date', 'value' => YOP_POLL_MODEL::get_mysql_curent_date(), 'operator' => '>=' );
						break;
					case 'never_expire':
						$filter = array( 'field' => 'end_date', 'value' => '9999-12-31 23:59:59', 'operator' => '=' );
						break;
					case 'expired':
						$filter = array( 'field' => 'end_date', 'value' => YOP_POLL_MODEL::get_mysql_curent_date(), 'operator' => '<=' );
						break;
				}
			}
			$search = array( 'fields' => array('name', 'question'), 'value' => isset( $_REQUEST['s'] ) ? $_REQUEST['s'] : '' );
			$yop_polls = Yop_Poll_Model::get_yop_polls_filter_search( $orderby, $order, $filter, $search );
		?>
		<div class="wrap">
			<div class="icon32 icon32-yop-poll"><br></div><h2><?php _e( 'Yop Poll', 'yop_poll' ); ?><a class="add-new-h2" href="<?php echo esc_url( add_query_arg( array( 'page' => 'yop-polls-add-new', 'id' => false, 'deleted' => false, 'cloned' => false ) ) ); ?>" ><?php _e( 'Add New', 'yop_poll' ); ?></a> </h2>
			<?php
				if ( isset($_REQUEST['deleted']) ) {
					echo '<div id="message" class="updated"><p>';
					$deleted = (int) $_REQUEST['deleted'];
					printf(_n('%s Poll deleted.', '%s Polls deleted.', $deleted), $deleted);
					echo '</p></div>';
					$_SERVER['REQUEST_URI'] = remove_query_arg(array('deleted'), $_SERVER['REQUEST_URI']);
				}
			?>
			<?php
				if ( isset($_REQUEST['cloned']) ) {
					echo '<div id="message" class="updated"><p>';
					$cloned = (int) $_REQUEST['cloned'];
					printf(_n('%s Poll cloned.', '%s Polls cloned.', $cloned), $cloned);
					echo '</p></div>';
					$_SERVER['REQUEST_URI'] = remove_query_arg(array('cloned'), $_SERVER['REQUEST_URI']);
				}
			?>
			<?php
				if ( isset($_REQUEST['reseted_votes']) ) {
					echo '<div id="message" class="updated"><p>';
					$reseted_votes = (int) $_REQUEST['reseted_votes'];
					printf(_n('Vote reseted for %s Poll.', 'Votes reseted for %s Poll.', $reseted_votes), $reseted_votes);
					echo '</p></div>';
					$_SERVER['REQUEST_URI'] = remove_query_arg(array('reseted_votes'), $_SERVER['REQUEST_URI']);
				}
			?>

			<?php
				if ( isset($_REQUEST['deleted_logs']) ) {
					echo '<div id="message" class="updated"><p>';
					$deleted_logs= (int) $_REQUEST['deleted_logs'];
					printf(_n('Log deleted for %s Poll.', 'Log deleted for %s Polls.', $deleted_logs), $deleted_logs);
					echo '</p></div>';
					$_SERVER['REQUEST_URI'] = remove_query_arg(array('deleted_logs'), $_SERVER['REQUEST_URI']);
				}
			?>

			<form action="" method="get">
				<?php wp_nonce_field( 'yop-poll-view' ); ?>
				<input type="hidden" name="order" value="<?php echo $order ?>" />
				<input type="hidden" name="orderby" value="<?php echo $orderby ?>" />
				<input type="hidden" name="page" value="yop-polls" />
				<p class="search-box">
					<label class="screen-reader-text" for="yop-poll-search-input"><?php _e( 'Search Polls', 'yop_poll' ) ?></label>
					<input id="yop-poll-search-input" type="search" value="<?php if( isset( $_REQUEST['s'] ) ): echo esc_html( stripslashes( $_REQUEST['s'] ) ); endif; ?>" name="s" />
					<input id="search-submit" class="button" type="submit" value="<?php _e( 'Search Polls', 'yop_poll' ); ?>" name="" />
				</p>
				<div class="tablenav top">
					<div class="alignleft actions">
						<select name="action">
							<option selected="selected" value="-1"><?php _e( 'Bulk Actions', 'yop_poll' ); ?></option>
							<option value="delete"><?php _e( 'Delete', 'yop_poll' ); ?></option>
							<option value="clone"><?php _e( 'Clone', 'yop_poll' ); ?></option>
							<option value="reset_votes"><?php _e( 'Reset Votes', 'yop_poll' ); ?></option>
							<option value="delete_logs"><?php _e( 'Delete Logs', 'yop_poll' ); ?></option>
						</select>
						<input type="submit" value="<?php _e( 'Apply', 'yop_poll' ); ?>" class="button-secondary action" id="doaction" name="">
					</div>
					<div class="alignleft actions">
						<select name="filters">
							<option value="0"><?php _e( 'View All Polls', 'yop_poll' ); ?></option>
							<option <?php echo isset( $_REQUEST['filters'] ) ? ( 'never_expire' == $_REQUEST['filters'] ? 'selected="selected"' : '' ) : '' ?> value="never_expire"><?php _e( 'Never Expire', 'yop_poll' ); ?></option>
							<option <?php echo isset( $_REQUEST['filters'] ) ? ( 'expired' == $_REQUEST['filters'] ? 'selected="selected"' : '' ) : '' ?> value="expired"><?php _e( 'Expired', 'yop_poll' ); ?></option>
							<option <?php echo isset( $_REQUEST['filters'] ) ? ( 'started' == $_REQUEST['filters'] ? 'selected="selected"' : '' ) : '' ?>  value="started"><?php _e( 'Started', 'yop_poll' ); ?></option>
							<option <?php echo isset( $_REQUEST['filters'] ) ? ( 'not_started' == $_REQUEST['filters'] ? 'selected="selected"' : '' ) : '' ?>  value="not_started"><?php _e( 'Not Started', 'yop_poll' ); ?></option>
						</select>
						<input type="submit" value="<?php _e( 'Filter', 'yop_poll' ); ?>" class="button-secondary" id="post-query-submit" name="">		
					</div>
					<br class="clear">
				</div>
				<table class="wp-list-table widefat fixed" cellspacing="0">
					<thead>
						<tr>
							<th id="cb" class="manage-column column-cb check-column" style="width: 2%;" scope="col">
								<input type="checkbox">
							</th>
							<th id="id" class="manage-column <?php echo $order_sortable[ 'id' ] ?> <?php echo $order_direction_reverse[ 'id' ] ?>" style="width:5%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'id', 'order' => $order_direction[ 'id' ] ) ) ); ?>">
									<span><?php _e( 'ID', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="name" class="manage-column <?php echo $order_sortable[ 'name' ] ?> <?php echo $order_direction_reverse[ 'name' ] ?>" style="width:30%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'name', 'order' => $order_direction[ 'name' ] ) ) ); ?>">
									<span><?php _e( 'Name', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="total_votes" class="manage-column <?php echo $order_sortable[ 'total_votes' ] ?> <?php echo $order_direction_reverse[ 'total_votes' ] ?>" style="width:6%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'total_votes', 'order' => $order_direction[ 'total_votes' ] ) ) ); ?>">
									<span><?php _e( 'Total Votes', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="total_voters" class="manage-column <?php echo $order_sortable[ 'total_voters' ] ?> <?php echo $order_direction_reverse[ 'total_voters' ] ?>" style="width:7%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'total_voters', 'order' => $order_direction[ 'total_voters' ] ) ) ); ?>">
									<span><?php _e( 'Total Voters', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="question" class="manage-column <?php echo $order_sortable[ 'question' ] ?> <?php echo $order_direction_reverse[ 'question' ] ?>" style="width:30%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'question', 'order' => $order_direction[ 'question' ] ) ) ); ?>">
									<span><?php _e( 'Question', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="start-date" class="manage-column <?php echo $order_sortable[ 'start_date' ] ?> <?php echo $order_direction_reverse[ 'start_date' ] ?>" style="width:10%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'start_date', 'order' => $order_direction[ 'start_date' ] ) ) ); ?>">
									<span><?php _e( 'Start Date', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="end-date" class="manage-column <?php echo $order_sortable[ 'end_date' ] ?> <?php echo $order_direction_reverse[ 'end_date' ] ?>" style="width:10%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'end_date', 'order' => $order_direction[ 'end_date' ] ) ) ); ?>">
									<span><?php _e( 'End Date', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
						</tr>
					</thead>
					<?php 
						if( count( $yop_polls ) > 0 ) { 
							foreach( $yop_polls as $yop_poll ) {
							?>
							<tbody id="the-list">
								<tr valign="middle" class="alternate" id="yop-poll-<?php echo $yop_poll['id']; ?>">
									<th class="check-column" scope="row">
										<input type="checkbox" value="<?php echo $yop_poll['id']; ?>" name="yoppollcheck[]">
									</th>
									<td>
										<strong><a title="<?php echo $yop_poll['id']; ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'edit', 'id' => $yop_poll['id'] ) ) ); ?>" class="row-title"><?php echo $yop_poll['id']; ?></a></strong><br>
									</td>
									<td>
										<strong><a title="<?php echo esc_html( stripslashes( $yop_poll['name'] ) ); ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'edit', 'id' => $yop_poll['id'] ) ) ); ?>" class="row-title"><?php echo esc_html( stripslashes( $yop_poll['name'] ) ); ?></a></strong><br>
										<div class="row-actions">
											<span class="edit"><a href="<?php echo esc_url( add_query_arg( array( 'action' => 'edit', 'id' => $yop_poll['id'] ) ) ); ?>"><?php _e( 'Edit', 'yop_poll' ) ?></a> | </span>
											<span class="edit"><a href="<?php echo esc_url( add_query_arg( array( 'page' => 'yop-polls-logs', 'poll_id' => $yop_poll['id'] ) ) ); ?>"><?php _e( 'Logs', 'yop_poll' ) ?></a> | </span>
											<span class="delete"><a onclick="if ( confirm( '<?php echo __( "You are about to delete this poll",'yop_poll').": \'".esc_html( $yop_poll['name'] )."\' \\n  \'".__("Cancel", 'yop_poll')."\' ". __('to stop', 'yop_poll'). ", \'".__('OK', 'yop_poll')."\' ".__('to delete', 'yop_poll'); ?>' ) ) { return true;}return false;" href="<?php echo wp_nonce_url( add_query_arg( array( 'action' => 'delete', 'id' => $yop_poll['id'] ) ), 'yop-poll-delete' ); ?>" class="submitdelete"><?php _e( 'Delete', 'yop_poll' ) ?></a> | </span>
											<span class="clone"><a onclick="if ( confirm( '<?php echo __( "You are about to clone this poll",'yop_poll').": \'".esc_html( $yop_poll['name'] )."\' \\n  \'".__("Cancel", 'yop_poll')."\' ". __('to stop', 'yop_poll'). ", \'".__('OK', 'yop_poll')."\' ".__('to clone', 'yop_poll'); ?>' ) ) { return true;}return false;" href="<?php echo wp_nonce_url( add_query_arg( array( 'action' => 'clone', 'id' => $yop_poll['id'] ) ), 'yop-poll-clone' ); ?>" class="submitclone"><?php _e( 'Clone', 'yop_poll' ) ?></a> | </span>
											<span class="results"><a href="<?php echo esc_url( add_query_arg( array( 'action' => 'results', 'id' => $yop_poll['id'] ) ) ); ?>"><?php _e( 'Results', 'yop_poll' ) ?></a> | </span>
											<!--<span class="custom-fields"><a href="<?php echo esc_url( add_query_arg( array( 'action' => 'custom-fields', 'id' => $yop_poll['id'] ) ) ); ?>"><?php _e( 'Custom Fields', 'yop_poll' ) ?></a> | </span>-->
											<span class="delete"><a onclick="if ( confirm( '<?php echo __( "You are about to reset votes for this poll",'yop_poll').": \'".esc_html( $yop_poll['name'] )."\' \\n  \'".__("Cancel", 'yop_poll')."\' ". __('to stop', 'yop_poll'). ", \'".__('OK', 'yop_poll')."\' ".__('to reset votes', 'yop_poll'); ?>' ) ?>' ) ) { return true;}return false;" href="<?php echo wp_nonce_url( add_query_arg( array( 'action' => 'reset_votes', 'id' => $yop_poll['id'] ) ), 'yop-poll-reset-votes' ); ?>" class="submitresetvotes"><?php _e( 'Reset Stats', 'yop_poll' ) ?></a> | </span>
											<span class="delete"><a onclick="if ( confirm( '<?php echo __( "You are about to delete logs for this poll",'yop_poll').": \'".esc_html( $yop_poll['name'] )."\' \\n  \'".__("Cancel", 'yop_poll')."\' ". __('to stop', 'yop_poll'). ", \'".__('OK', 'yop_poll')."\' ".__('to delete logs', 'yop_poll'); ?>' ) ) { return true;}return false;" href="<?php echo wp_nonce_url( add_query_arg( array( 'action' => 'delete_logs', 'id' => $yop_poll['id'] ) ), 'yop-poll-delete-logs' ); ?>" class="submitresetvotes"><?php _e( 'Delete Logs', 'yop_poll' ) ?></a></span>
										</div>
									</td>
									<td>
										<?php echo esc_html( stripslashes( $yop_poll['total_votes'] ) ); ?>
									</td>
									<td>
										<?php echo esc_html( stripslashes( $yop_poll['total_voters'] ) ); ?>
									</td>
									<td>
										<?php echo esc_html( stripslashes( $yop_poll['question'] ) ); ?>
									</td>
									<td>
										<?php echo esc_html( stripslashes( $yop_poll['start_date'] ) ); ?>
									</td>
									<td>
										<?php 
											if ( YOP_POLL_MODEL::get_mysql_curent_date() > $yop_poll['end_date'] )
												echo '<font style="color:#CC0000;"><b>';
											echo ( '9999-12-31 23:59:59' == $yop_poll['end_date'] ) ? __( 'Never Expire', 'yop_poll' ) : esc_html( stripslashes( $yop_poll['end_date'] ) ); 
											if ( YOP_POLL_MODEL::get_mysql_curent_date() > $yop_poll['end_date'] )
												echo '</b></font>'
										?>
									</td>
								</tr>
							</tbody>
							<?php 
							}
						} 
						else {
						?>
						<tbody id="the-list">
							<tr valign="middle" class="alternate" id="yop-poll-<?php ?>">
								<th colspan="8">
									<?php _e( 'No poll found!', 'yop_poll' ); ?>
								</th>
							</tr>
						</tbody>
						<?php 
						}
					?>

					<tfoot>
						<tr>
							<th id="cb" class="manage-column column-cb check-column" style="" scope="col">
								<input type="checkbox">
							</th>
							<th id="id" class="manage-column  <?php echo $order_sortable[ 'id' ] ?> <?php echo $order_direction_reverse[ 'id' ] ?>" style="" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'id', 'order' => $order_direction[ 'id' ] ) ) ); ?>">
									<span><?php _e( 'ID', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="name" class="manage-column <?php echo $order_sortable[ 'name' ] ?> <?php echo $order_direction_reverse[ 'name' ] ?>" style="" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'name', 'order' => $order_direction[ 'name' ] ) ) ); ?>">
									<span><?php _e( 'Name', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="total_votes" class="manage-column <?php echo $order_sortable[ 'total_votes' ] ?> <?php echo $order_direction_reverse[ 'total_votes' ] ?>" style="" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'total_votes', 'order' => $order_direction[ 'total_votes' ] ) ) ); ?>">
									<span><?php _e( 'Total Votes', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="total_voters" class="manage-column <?php echo $order_sortable[ 'total_voters' ] ?> <?php echo $order_direction_reverse[ 'total_voters' ] ?>" style="" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'total_voters', 'order' => $order_direction[ 'total_voters' ] ) ) ); ?>">
									<span><?php _e( 'Total Voters', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="question" class="manage-column <?php echo $order_sortable[ 'question' ] ?> <?php echo $order_direction_reverse[ 'question' ] ?>" style="" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'question', 'order' => $order_direction[ 'question' ] ) ) ); ?>">
									<span><?php _e( 'Question', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="start-date" class="manage-column <?php echo $order_sortable[ 'start_date' ] ?> <?php echo $order_direction_reverse[ 'start_date' ] ?>" style="" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'start_date', 'order' => $order_direction[ 'start_date' ] ) ) ); ?>">
									<span><?php _e( 'Start Date', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="end-date" class="manage-column <?php echo $order_sortable[ 'end_date' ] ?> <?php echo $order_direction_reverse[ 'end_date' ] ?>" style="" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'end_date', 'order' => $order_direction[ 'end_date' ] ) ) ); ?>">
									<span><?php _e( 'End Date', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
						</tr>
					</tfoot>
				</table>
			</form>
		</div>
		<?php
		}

		public function view_poll_results() {
			global $page, $action;
			$poll_id						= ( isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0 );	
			require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
			$poll_details					= YOP_POLL_MODEL::get_poll_from_database_by_id( $poll_id );
			$poll_answers					= YOP_POLL_MODEL::get_poll_answers( $poll_id, array( 'default', 'other' ));
			$poll_other_answer				= YOP_POLL_MODEL::get_poll_answers( $poll_id, array( 'other' ));

			//other-answers
			$oa_per_page					= ( isset( $_GET['oa_per_page'] ) ? intval( $_GET['oa_per_page'] ) : 100 );
			$oa_page_no						= ( isset( $_REQUEST['oa_page_no'] ) ? (int) $_REQUEST['oa_page_no'] : 1 );
			$total_logs_other_answers		= count( YOP_POLL_MODEL::get_other_answers_votes( isset( $poll_other_answer[0]['id'] ) ? $poll_other_answer[0]['id'] : 0 ) );
			$total_logs_other_answers_pages	= ceil(  $total_logs_other_answers / $oa_per_page );
			if ( intval( $oa_page_no) > intval( $total_logs_other_answers_pages ) )
				$oa_page_no = 1;
			$logs_other_answers				= YOP_POLL_MODEL::get_other_answers_votes( isset( $poll_other_answer[0]['id'] ) ? $poll_other_answer[0]['id'] : 0, ( $oa_page_no - 1 ) * $oa_per_page, $oa_per_page );

			$oa_args = array(
				'base'         => remove_query_arg( 'oa_page_no', $_SERVER['REQUEST_URI'] ).'%_%',
				'format'       => '&oa_page_no=%#%',
				'total'        => $total_logs_other_answers_pages,
				'current'      => max( 1, $oa_page_no ),
				'prev_next'    => True,
				'prev_text'    => __('&laquo; Previous'),
				'next_text'    => __('Next &raquo;')
			);
			$oa_pagination	= paginate_links( $oa_args );
			//other-answers

			//custom-fields
			$cf_per_page					= ( isset( $_GET['cf_per_page'] ) ? intval( $_GET['cf_per_page'] ) : 100 );
			$cf_page_no						= ( isset( $_REQUEST['cf_page_no'] ) ? (int) $_REQUEST['cf_page_no'] : 1 );
			$cf_sdate						= ( isset( $_GET['cf_sdate'] ) ?  $_GET['cf_sdate'] : '' );	
			$cf_edate						= ( isset( $_GET['cf_edate'] ) ? $_GET['cf_edate'] : '' );
			$poll_custom_fields				= YOP_POLL_MODEL::get_poll_customfields( $poll_id );
			$custom_fields_number			= count( $poll_custom_fields );
			$total_custom_fields_logs		= YOP_POLL_MODEL::get_poll_total_customfields_logs( $poll_id, $cf_sdate, $cf_edate );
			$total_custom_fields_logs_pages	= ceil(  $total_custom_fields_logs / $cf_per_page );
			if ( intval( $cf_page_no) > intval( $total_custom_fields_logs_pages ) )
				$cf_page_no = 1;
			$custom_fields_logs				= YOP_POLL_MODEL::get_poll_customfields_logs( $poll_id, 'vote_id', 'asc', ( $cf_page_no - 1 ) * $cf_per_page, $cf_per_page, $cf_sdate, $cf_edate );

			$column_custom_fields_ids	= array();
			$cf_args = array(
				'base'         => remove_query_arg( 'cf_page_no', $_SERVER['REQUEST_URI'] ).'%_%',
				'format'       => '&cf_page_no=%#%',
				'total'        => $total_custom_fields_logs_pages,
				'current'      => max( 1, $cf_page_no ),
				'prev_next'    => True,
				'prev_text'    => __('&laquo; Previous'),
				'next_text'    => __('Next &raquo;')
			);
			$cf_pagination	= paginate_links( $cf_args );
			//custom-fields
		?>
		<div class="wrap">
			<div class="icon32 icon32-yop-poll"><br></div><h2><?php _e( 'Yop Poll Results', 'yop_poll' ); ?><a class="add-new-h2" href="<?php echo esc_url( add_query_arg( array( 'page' => 'yop-polls' ), remove_query_arg( array( 'action', 'id'), stripslashes( $_SERVER['REQUEST_URI'] ) ) ) ); ?>" ><?php _e( 'All Yop Polls', 'yop_poll' ); ?></a></h2>
			<?php
				if ( $poll_details ) {
				?>
				<h3>Name: <?php echo esc_html( stripslashes( $poll_details['name'] ) )?></h3>
				<h4>Question: <?php echo esc_html( stripslashes( $poll_details['question'] ) )?></h4>
				<table class="wp-list-table widefat fixed" cellspacing="0">
					<thead>
						<tr>
							<th id="" class="column-answer" style="width: 40%;" scope="col"><?php _e( 'Answer', 'yop_poll' ); ?></th>
							<th id="" class="column-votes" style="width: 5%;" scope="col"><?php _e( 'Votes', 'yop_poll' ); ?></th>
							<th id="" class="column-percent" style="width: 5%;" scope="col"><?php _e( 'Percent', 'yop_poll' ); ?></th>
							<th id="" class="column-bar" style="width: 45%;" scope="col"></th>
						</tr>
					</thead>
					<tbody>
						<?php
							if ( count( $poll_answers ) > 0 ) {
								foreach( $poll_answers as $answer ) {
								?>
								<tr>
									<th><?php echo esc_html( stripslashes( $answer['answer'] ) ); ?></th>
									<td><?php echo esc_html( stripslashes( $answer['votes'] ) ); ?></td>
									<td><?php echo esc_html( stripslashes( $answer['procentes'] ) ); ?>%</td>
									<td><span class="yop-poll-admin-result-bar" style="width: <?php echo esc_html( stripslashes( $answer['procentes'] ) ); ?>%;"> </span></td>
								</tr>
								<?php
								}
							}
							else {
							?>
							<th colspan="4"><?php _e( 'No answers defined!', 'yop_poll' ); ?></th>
							<?php
							}
						?>
					</tbody>
				</table><br><br>
				<div style="width: 30%; float: left;">
					<h3><?php _e( 'Poll Other Answers', 'yop_poll' ); ?></h3>
					<form method="get">
						<input type="hidden" name="page" value="yop-polls" />
						<input type="hidden" name="action" value="results" />
						<input type="hidden" name="id" value="<?php echo $poll_id; ?>" />
						<input type="hidden" name="cf_page_no" value="<?php echo $cf_page_no; ?>" />
						<input type="hidden" name="oa_page_no" value="<?php echo $oa_page_no; ?>" />
						<input type="hidden" name="cf_per_page" value="<?php echo $cf_per_page; ?>" />
						<div class="tablenav top">
							<div class="tablenav-pages one-page">
								<label for="yop-poll-oa-items-per-page" class="displaying-num"><?php _e( 'Items Per Page', 'yop_poll' ); ?>:</label><input id="yop-poll-oa-items-per-page" type="text" name="oa_per_page" value="<?php echo $oa_per_page; ?>" /> <input name="a" value="<?php _e( 'Set', 'yop_poll' ); ?>" type="submit" />&nbsp;&nbsp;<span class="displaying-num"><?php echo count( $logs_other_answers ); ?> / <?php echo $total_logs_other_answers; ?> items</span>
								<?php print $oa_pagination; ?>
							</div>
							<br class="clear">
						</div>          						
						<table class="wp-list-table widefat fixed" cellspacing="0">
							<thead>
								<tr>
									<th id="" class="column-answer" style="width: 40%;" scope="col"><?php _e( 'Other Answers', 'yop_poll' ); ?></th>
									<th id="" class="column-votes" style="width: 5%;" scope="col"><?php _e( 'Votes', 'yop_poll' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
									if ( count( $logs_other_answers ) > 0 ) {
										foreach( $logs_other_answers as $answer ) {
										?>
										<tr>
											<td><?php echo esc_html( stripslashes( $answer['other_answer_value'] ) ); ?></td>
											<td><?php echo esc_html( stripslashes( $answer['votes'] ) ); ?></td>
										</tr>
										<?php
										}
									}
									else {
									?>
									<td colspan="2"><?php _e( 'No other answers defined!', 'yop_poll' ); ?></td>
									<?php
									}
								?>
							</tbody>
						</table>
						<div class="tablenav top">
							<div class="tablenav-pages one-page">
								<?php print $oa_pagination; ?>
							</div>
						</div>
						<br class="clear">
					</form>
				</div>
				<div style="width: 69%; float: right;">
					<h3><?php _e( 'Custom Fields', 'yop_poll' ); ?></h3>
					<form method="get">
						<?php wp_nonce_field( 'yop-poll-custom-fields' ); ?>
						<input type="hidden" name="page" value="yop-polls" />
						<input type="hidden" name="action" value="results" />
						<input type="hidden" name="id" value="<?php echo $poll_id; ?>" />
						<input type="hidden" name="oa_page_no" value="<?php echo $oa_page_no; ?>" />
						<input type="hidden" name="cf_page_no" value="<?php echo $cf_page_no; ?>" />
						<input type="hidden" name="oa_per_page" value="<?php echo $oa_per_page; ?>" />

						<div class="tablenav top">
							<div class="alignleft actions">
								<select name="export">
									<option value="page"><?php _e( 'This Page', 'yop_poll' ); ?></option>
									<option value="all"><?php _e( 'All Pages', 'yop_poll' ); ?></option>
								</select>
								<input type="submit" value="<?php _e( 'Export', 'yop_poll' ); ?>" class="button-secondary action" id="doaction" name="a"> &nbsp;&nbsp;&nbsp;
								<label for="yop-poll-custom-field-start-date-input"><?php _e( 'Start Date', 'yop_poll' ); ?>:</label>
								<input id="yop-poll-custom-field-start-date-input" type="text" name="cf_sdate" value="<?php echo $cf_sdate; ?>" />&nbsp;&nbsp;
								<label for="yop-poll-custom-field-end-date-input"><?php _e( 'End Date', 'yop_poll' ); ?>:</label>
								<input id="yop-poll-custom-field-end-date-input" type="text" name="cf_edate" value="<?php echo $cf_edate; ?>" />&nbsp;&nbsp;
								<input value="<?php _e( 'Filter', 'yop_poll' ); ?>" type="submit" name="a" />
							</div>
							<div class="tablenav-pages one-page">
								<label for="yop-poll-items-per-page" class="displaying-num"><?php _e( 'Items Per Page', 'yop_poll' ); ?>:</label><input id="yop-poll-items-per-page" type="text" name="cf_per_page" value="<?php echo $cf_per_page; ?>" /> <input name="a" value="<?php _e( 'Set', 'yop_poll' ); ?>" type="submit" />&nbsp;&nbsp;<span class="displaying-num"><?php echo count( $custom_fields_logs ); ?> / <?php echo $total_custom_fields_logs; ?> items</span>
								<?php print $cf_pagination; ?>
							</div>
							<br class="clear">
						</div>
						<table class="wp-list-table widefat fixed" cellspacing="0">
							<thead>
								<tr>
									<th id="" class="column-answer" style="width:5%" scope="col"><?php _e( '#', 'yop_poll' ); ?></th>
									<?php
										foreach ( $poll_custom_fields as $custom_field ) {
											$column_custom_fields_ids[]	= $custom_field['id']; 
										?>
										<th id="custom_field_<?php echo $custom_field['id']; ?>" class="column-custom-field" style="width:<?php echo intval( 80 / intval($custom_fields_number) ); ?>%" scope="col"><?php echo ucfirst( $custom_field['custom_field'] ); ?></th>
										<?php
										}
									?>
									<th id="" class="column-vote-date" style="width:15%" scope="col"><?php _e( 'Vote Date', 'yop_poll' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php 
									if ( count( $custom_fields_logs ) > 0 ) {
										$index	= ( $cf_page_no - 1 ) * $cf_per_page + 1;
										foreach( $custom_fields_logs as $logs) {
										?>
										<tr>
											<td><?php echo $index; ?></td>
											<?php 
												foreach ( $column_custom_fields_ids as $custom_field_id ) {
													$vote_log_values	= array();
													$vote_logs	= explode( ',', $logs['vote_log'] );
													if( count( $vote_logs ) > 0 ) {
														foreach ( $vote_logs as $vote_log ) {
															$temp							= explode( '-', $vote_log );
															$vote_log_values[ $temp[1] ]	= stripslashes( $temp[0] ); 	
														}
													}
												?>
												<td><?php echo isset( $vote_log_values[ $custom_field_id ] ) ? $vote_log_values[ $custom_field_id ] : ''; ?></td>
												<?php	
												}
											?>
											<td><?php echo $logs['vote_date']; ?></td>
										</tr>
										<?php
											$index++;
										}
									}
								?>
							</tbody>
						</table>
						<div class="tablenav top">
							<div class="tablenav-pages one-page">
								<?php print $cf_pagination; ?>
							</div>
							<br class="clear">
						</div>
					</form>
				</div>
				<div style="clear: both;"></div>
			</div>
			<?php
			}
			else {
			?>
			<h3><?php _e( 'Your poll doesn`t exist!', 'yop_poll' );?></h3>
			<?php 
			}
		}

		public function view_poll_custom_fields() {
			global $page, $action;
			$per_page						= ( isset( $_GET['per_page'] ) ? intval( $_GET['per_page'] ) : 100 );
			$page_no						= ( isset( $_REQUEST['page_no'] ) ? (int) $_REQUEST['page_no'] : 1 );
			$poll_id						= ( isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0 );	
			$sdate							= ( isset( $_GET['sdate'] ) ?  $_GET['sdate'] : '' );	
			$edate							= ( isset( $_GET['edate'] ) ? $_GET['edate'] : '' );	
			require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
			$poll_details					= YOP_POLL_MODEL::get_poll_from_database_by_id( $poll_id );
			$poll_custom_fields				= YOP_POLL_MODEL::get_poll_customfields( $poll_id );
			$custom_fields_number			= count( $poll_custom_fields );
			$total_custom_fields_logs		= YOP_POLL_MODEL::get_poll_total_customfields_logs( $poll_id, $sdate, $edate );
			$total_custom_fields_logs_pages	= ceil(  $total_custom_fields_logs / $per_page );
			if ( intval( $page_no) > intval( $total_custom_fields_logs_pages ) )
				$page_no = 1;
			$custom_fields_logs				= YOP_POLL_MODEL::get_poll_customfields_logs( $poll_id, 'vote_id', 'asc', ( $page_no - 1 ) * $per_page, $per_page, $sdate, $edate );

			$column_custom_fields_ids	= array();
			$args = array(
				'base'         => remove_query_arg( 'page_no', $_SERVER['REQUEST_URI'] ).'%_%',
				'format'       => '&page_no=%#%',
				'total'        => $total_custom_fields_logs_pages,
				'current'      => max( 1, $page_no ),
				'prev_next'    => True,
				'prev_text'    => __('&laquo; Previous'),
				'next_text'    => __('Next &raquo;')
			);
			$pagination	= paginate_links( $args );
			$_SERVER['REQUEST_URI'] = remove_query_arg(array('action'), $_SERVER['REQUEST_URI']);
		?>
		<div class="wrap">
			<div class="icon32 icon32-yop-poll"><br></div><h2><?php _e( 'Custom Fields', 'yop_poll' ); ?><a class="add-new-h2" href="<?php echo esc_url( add_query_arg( array( 'page' => 'yop-polls' ), remove_query_arg( array( 'action', 'id'), stripslashes( $_SERVER['REQUEST_URI'] ) ) ) ); ?>" ><?php _e( 'All Yop Polls', 'yop_poll' ); ?></a></h2>
			<?php
				if ( $poll_details ) {
					if ( $poll_custom_fields ) {
					?>
					<h3>Name: <?php echo esc_html( stripslashes( $poll_details['name'] ) )?></h3>
					<h4>Question: <?php echo esc_html( stripslashes( $poll_details['question'] ) )?></h4>
					<form method="get">
						<?php wp_nonce_field( 'yop-poll-custom-fields' ); ?>
						<input type="hidden" name="page" value="yop-polls" />
						<input type="hidden" name="action" value="custom-fields" />
						<input type="hidden" name="id" value="<?php echo $poll_id; ?>" />
						<input type="hidden" name="page_no" value="<?php echo $page_no; ?>" />
						<table cellspacing="5" align=" center">
							<tbody>
								<tr>
									<th>
										<label for="yop-poll-custom-field-start-date-input"><?php _e( 'Start Date', 'yop_poll' ); ?>:</label>
									</th>
									<td>
										<input id="yop-poll-custom-field-start-date-input" type="text" name="sdate" value="<?php echo $sdate; ?>" />
									</td>
								</tr>	
								<tr>
									<th>
										<label for="yop-poll-custom-field-end-date-input"><?php _e( 'End Date', 'yop_poll' ); ?>:</label>
									</th>
									<td>
										<input id="yop-poll-custom-field-end-date-input" type="text" name="edate" value="<?php echo $edate; ?>" />
									</td>
								</tr>
								<tr>
									<th colspan="2">
										<input value="<?php _e( 'Filter', 'yop_poll' ); ?>" type="submit" name="a" />
									</th>
								</tr>											
							</tbody>
						</table>
						<div class="tablenav top">
							<div class="alignleft actions">
								<select name="export">
									<option selected="selected" value=""><?php _e( 'Do Not Export', 'yop_poll' ); ?></option>
									<option value="page"><?php _e( 'This Page', 'yop_poll' ); ?></option>
									<option value="all"><?php _e( 'All Pages', 'yop_poll' ); ?></option>
								</select>
								<input type="submit" value="<?php _e( 'Export', 'yop_poll' ); ?>" class="button-secondary action" id="doaction" name="a">
							</div>
							<div class="tablenav-pages one-page">
								<label for="yop-poll-items-per-page" class="displaying-num"><?php _e( 'Items Per Page', 'yop_poll' ); ?>:</label><input id="yop-poll-items-per-page" type="text" name="per_page" value="<?php echo $per_page; ?>" /> <input name="a" value="<?php _e( 'Set', 'yop_poll' ); ?>" type="submit" />&nbsp;&nbsp;<span class="displaying-num"><?php echo count( $custom_fields_logs ); ?> / <?php echo $total_custom_fields_logs; ?> items</span>
								<?php print $pagination; ?>
							</div>
							<br class="clear">
						</div>
						<table class="wp-list-table widefat fixed" cellspacing="0">
							<thead>
								<tr>
									<th id="" class="column-answer" style="width:5%" scope="col"><?php _e( '#', 'yop_poll' ); ?></th>
									<?php
										foreach ( $poll_custom_fields as $custom_field ) {
											$column_custom_fields_ids[]	= $custom_field['id']; 
										?>
										<th id="custom_field_<?php echo $custom_field['id']; ?>" class="column-custom-field" style="width:<?php echo intval( 80 / intval($custom_fields_number) ); ?>%" scope="col"><?php echo ucfirst( $custom_field['custom_field'] ); ?></th>
										<?php
										}
									?>
									<th id="" class="column-vote-date" style="width:15%" scope="col"><?php _e( 'Vote Date', 'yop_poll' ); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php 
									if ( count( $custom_fields_logs ) > 0 ) {
										$index	= ( $page_no - 1 ) * $per_page + 1;
										foreach( $custom_fields_logs as $logs) {
										?>
										<tr>
											<td><?php echo $index; ?></td>
											<?php 
												foreach ( $column_custom_fields_ids as $custom_field_id ) {
													$vote_log_values	= array();
													$vote_logs	= explode( ',', $logs['vote_log'] );
													if( count( $vote_logs ) > 0 ) {
														foreach ( $vote_logs as $vote_log ) {
															$temp							= explode( '-', $vote_log );
															$vote_log_values[ $temp[1] ]	= stripslashes( $temp[0] ); 	
														}
													}
												?>
												<td><?php echo isset( $vote_log_values[ $custom_field_id ] ) ? $vote_log_values[ $custom_field_id ] : ''; ?></td>
												<?php	
												}
											?>
											<td><?php echo $logs['vote_date']; ?></td>
										</tr>
										<?php
											$index++;
										}
									}
								?>
							</tbody>
						</table>
						<div class="tablenav top">
							<div class="tablenav-pages one-page">
								<?php print $pagination; ?>
							</div>
							<br class="clear">
						</div>
					</form>
				</div>
				<?php
				}
				else {
				?>
				<h3><?php _e( 'This poll doesn\'t have set custom fields!', 'yop_poll' );?></h3>
				<?php 
				}
			}
			else {
			?>
			<h3><?php _e( 'Your Poll doesn`t exist!', 'yop_poll' );?></h3>
			<?php 
			}
		}

		public function view_yop_poll_templates() {
			global $page, $action, $orderby, $order;
			$orderby	= ( empty( $orderby ) ) ? 'last_modified' : $orderby;
			$order_direction	= array(
				'id'			=> 'asc', 
				'name'			=> 'asc', 
				'last_modified'	=> 'desc'
			);
			$order_direction[ $orderby ] = ( 'desc' == $order ) ? 'asc' : 'desc';

			$order_direction_reverse	= array(
				'id'			=> 'desc', 
				'name'			=> 'desc', 
				'last_modified'	=> 'desc' 
			);
			$order_direction_reverse[ $orderby ]	= ( 'desc' == $order ) ? 'desc' : 'asc';

			$order_sortable	= array(
				'id'			=> 'sortable', 
				'name'			=> 'sortable', 
				'last_modified'	=> 'sortable'
			);
			$order_sortable[ $orderby ]	= 'sorted';
			require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
			$search = array( 'fields' => array('name', 'last_modified'), 'value' => isset( $_REQUEST['s'] ) ? $_REQUEST['s'] : '' );
			$yop_poll_templates	= Yop_Poll_Model::get_yop_poll_templates_search( $orderby, $order, $search );
		?>
		<div class="wrap">
			<div class="icon32 icon32-yop-poll"><br></div><h2><?php _e( 'Yop Poll Templates', 'yop_poll' ); ?><a class="add-new-h2" href="<?php echo esc_url( add_query_arg( array( 'page' => 'yop-polls-templates', 'action' => 'add-new', 'id' => false, 'deleted' => false, 'cloned' => false ) ) ); ?>" ><?php _e( 'Add New', 'yop_poll' ); ?></a> </h2>
			<?php
				if ( isset($_REQUEST['deleted']) ) {
					echo '<div id="message" class="updated"><p>';
					$deleted = (int) $_REQUEST['deleted'];
					printf(_n('%s Poll template deleted.', '%s Poll templates deleted.', $deleted), $deleted);
					echo '</p></div>';
					$_SERVER['REQUEST_URI'] = remove_query_arg(array('deleted'), $_SERVER['REQUEST_URI']);
				}
			?>
			<?php
				if ( isset($_REQUEST['cloned']) ) {
					echo '<div id="message" class="updated"><p>';
					$cloned = (int) $_REQUEST['cloned'];
					printf(_n('%s Poll template cloned.', '%s Poll templates cloned.', $cloned), $cloned);
					echo '</p></div>';
					$_SERVER['REQUEST_URI'] = remove_query_arg(array('cloned'), $_SERVER['REQUEST_URI']);
				}
			?>
			<form action="" method="get">
				<?php wp_nonce_field( 'yop-poll-templates' ); ?>
				<input type="hidden" name="order" value="<?php echo $order ?>" />
				<input type="hidden" name="orderby" value="<?php echo $orderby ?>" />
				<input type="hidden" name="page" value="yop-polls-templates" />
				<p class="search-box">
					<label class="screen-reader-text" for="yop-poll-search-input"><?php _e( 'Search Polls', 'yop_poll' ) ?></label>
					<input id="yop-poll-search-input" type="search" value="<?php if( isset( $_REQUEST['s'] ) ): echo esc_html( stripslashes( $_REQUEST['s'] ) ); endif; ?>" name="s" />
					<input id="search-submit" class="button" type="submit" value="<?php _e( 'Search Polls', 'yop_poll' ); ?>" name="" />
				</p>
				<div class="tablenav top">
					<div class="alignleft actions">
						<select name="action">
							<option selected="selected" value="-1"><?php _e( 'Bulk Actions', 'yop_poll' ); ?></option>
							<option value="delete"><?php _e( 'Delete', 'yop_poll' ); ?></option>
							<option value="clone"><?php _e( 'Clone', 'yop_poll' ); ?></option>
						</select>
						<input type="submit" value="<?php _e( 'Apply', 'yop_poll' ); ?>" class="button-secondary action" id="doaction" name="">
					</div>
					<br class="clear">
				</div>
				<table class="wp-list-table widefat fixed" cellspacing="0">
					<thead>
						<tr>
							<th id="cb" class="manage-column column-cb check-column" scope="col" style="width:2%;">
								<input type="checkbox">
							</th>
							<th id="id" class="manage-column <?php echo $order_sortable[ 'id' ] ?> <?php echo $order_direction_reverse[ 'id' ] ?>" style="width:10%;" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'id', 'order' => $order_direction[ 'id' ] ) ) ); ?>">
									<span><?php _e( 'ID', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="name" class="manage-column <?php echo $order_sortable[ 'name' ] ?> <?php echo $order_direction_reverse[ 'name' ] ?>" style="width:43%;" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'name', 'order' => $order_direction[ 'name' ] ) ) ); ?>">
									<span><?php _e( 'Name', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="last_modified" class="manage-column <?php echo $order_sortable[ 'last_modified' ] ?> <?php echo $order_direction_reverse[ 'last_modified' ] ?>" style="width:45%;" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'last_modified', 'order' => $order_direction[ 'last_modified' ] ) ) ); ?>">
									<span><?php _e( 'Last Modified', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
						</tr>
					</thead>
					<?php 
						if( count( $yop_poll_templates ) > 0 ) { 
							foreach( $yop_poll_templates as $template ) {
							?>
							<tbody id="the-list">
								<tr valign="middle" class="alternate" id="yop-poll-<?php echo $template['id']; ?>">
									<th class="check-column" scope="row">
										<input type="checkbox" value="<?php echo $template['id']; ?>" name="templatecheck[]">
									</th>
									<td>
										<strong><a title="<?php echo $template['id']; ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'edit', 'id' => $template['id'] ) ) ); ?>" class="row-title"><?php echo $template['id']; ?></a></strong><br>
										<div class="row-actions">
											<span class="edit"><a href="<?php echo esc_url( add_query_arg( array( 'action' => 'edit', 'id' => $template['id'] ) ) ); ?>"><?php _e( 'Edit', 'yop_poll' ) ?></a> | </span>
											<span class="delete"><a onclick="if ( confirm( '<?php echo __( "You are about to delete this poll template",'yop_poll').": \'".esc_html( $template['name'] )."\' \\n  \'".__("Cancel", 'yop_poll')."\' ". __('to stop', 'yop_poll'). ", \'".__('OK', 'yop_poll')."\' ".__('to delete', 'yop_poll'); ?>' ) ) { return true;}return false;" href="<?php echo wp_nonce_url( add_query_arg( array( 'action' => 'delete', 'id' => $template['id'] ) ), 'yop-poll-templates' ); ?>" class="submitdelete"><?php _e( 'Delete', 'yop_poll' ) ?></a> | </span>
											<span class="clone"><a onclick="if ( confirm( '<?php echo __( "You are about to clone this poll template",'yop_poll').": \'".esc_html( $template['name'] )."\' \\n  \'".__("Cancel", 'yop_poll')."\' ". __('to stop', 'yop_poll'). ", \'".__('OK', 'yop_poll')."\' ".__('to clone', 'yop_poll'); ?>' ) ) { return true;}return false;" href="<?php echo wp_nonce_url( add_query_arg( array( 'action' => 'clone', 'id' => $template['id'] ) ), 'yop-poll-templates' ); ?>" class="submitdelete"><?php _e( 'Clone', 'yop_poll' ) ?></a></span>
										</div>
									</td>
									<td>
										<strong><a title="<?php echo esc_html( stripslashes( $template['name'] ) ); ?>" href="<?php echo esc_url( add_query_arg( array( 'action' => 'edit', 'id' => $template['id'] ) ) ); ?>" class="row-title"><?php echo esc_html( stripslashes( $template['name'] ) ); ?></a></strong><br>
									</td>
									<td>
										<?php echo esc_html( stripslashes( $template['last_modified'] ) ); ?>
									</td>		
								</tr>
							</tbody>
							<?php 
							}
						} 
						else {
						?>
						<tbody id="the-list">
							<tr valign="middle" class="alternate" id="yop-poll-<?php ?>">
								<td id="empty-set" colspan="4">
									<h3 style="margin-bottom:0px;"><?php _e(" You haven't used our template editor to create any yop poll templates!", 'yop_poll' ); ?> </h3>
									<p style="margin-bottom:20px;"><?php _e( "Please create your poll template first.", 'yop_poll' ); ?></p>
									<a class="button-primary" href="<?php echo esc_url( add_query_arg( array( 'page' => 'yop-polls-templates', 'action' => 'add-new', 'id' => false, 'deleted' => false, 'cloned' => false ) ) ); ?>"><?php _e( "Create a poll template now", 'yop_poll' ); ?></a>
									<br />
									<br />
								</td>
							</tr>
						</tbody>
						<?php 
						}
					?>

					<tfoot>
						<tr>
							<th id="cb" class="manage-column column-cb check-column" style="" scope="col">
								<input type="checkbox">
							</th>
							<th id="id" class="manage-column <?php echo $order_sortable[ 'id' ] ?> <?php echo $order_direction_reverse[ 'id' ] ?>" style="" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'id', 'order' => $order_direction[ 'id' ] ) ) ); ?>">
									<span><?php _e( 'ID', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="name" class="manage-column <?php echo $order_sortable[ 'name' ] ?> <?php echo $order_direction_reverse[ 'name' ] ?>" style="" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'name', 'order' => $order_direction[ 'name' ] ) ) ); ?>">
									<span><?php _e( 'Name', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="question" class="manage-column <?php echo $order_sortable[ 'last_modified' ] ?> <?php echo $order_direction_reverse[ 'last_modified' ] ?>" style="" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'last_modified', 'order' => $order_direction[ 'last_modified' ] ) ) ); ?>">
									<span><?php _e( 'Last Modified', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
						</tr>
					</tfoot>
				</table>
			</form>
		</div>
		<?php
		}

		public function view_yop_poll_logs() {
			global $wpdb, $page, $action, $orderby, $order;
			$per_page	= ( isset( $_GET['per_page'] ) ? intval( $_GET['per_page'] ) : 100 );
			$page_no	= isset( $_REQUEST['page_no'] ) ? (int) $_REQUEST['page_no'] : 1;
			$orderby	= ( empty( $orderby ) ) ? 'name' : $orderby;
			$order_direction	= array(
				'id'			=> 'asc', 
				'name'			=> 'asc', 
				'answer'		=> 'asc', 
				'user_nicename'	=> 'asc', 
				'user_email'	=> 'asc', 
				'ip'			=> 'asc', 
				'vote_date'		=> 'asc', 
			);
			$order_direction[ $orderby ] = ( 'desc' == $order ) ? 'asc' : 'desc';

			$order_direction_reverse	= array(
				'id'			=> 'desc', 
				'name'			=> 'desc', 
				'answer'		=> 'desc', 
				'user_nicename'	=> 'desc', 
				'user_email'	=> 'desc', 
				'ip'			=> 'desc', 
				'vote_date'		=> 'desc', 
			);
			$order_direction_reverse[ $orderby ]	= ( 'desc' == $order ) ? 'desc' : 'asc';

			$order_sortable	= array(
				'id'			=> 'sortable', 
				'name'			=> 'sortable', 
				'answer'		=> 'sortable', 
				'user_nicename'	=> 'sortable', 
				'user_email'	=> 'sortable', 
				'ip'			=> 'sortable', 
				'vote_date'		=> 'sortable', 
			);
			$order_sortable[ $orderby ]	= 'sorted';
			$poll_id	= isset( $_REQUEST['poll_id'] ) ? (int) $_REQUEST['poll_id'] : NULL;
			require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
			$yop_polls 	= Yop_Poll_Model::get_yop_polls_filter_search( 'id', 'asc' );
			$search = array( 'fields' => array($wpdb->yop_polls . '.name', $wpdb->yop_poll_answers . '.answer', $wpdb->yop_poll_logs . '.ip', $wpdb->users . '.user_nicename', $wpdb->users . '.user_email'), 'value' => isset( $_REQUEST['s'] ) ? trim( $_REQUEST['s'] ) : '' );
			$filter = array( 'field' => NULL, 'value' => NULL, 'operator' => '=' );
			$total_logs			= Yop_Poll_Model::get_total_logs_filter_search( $search,  $poll_id );
			$total_logs_pages	= ceil(  $total_logs / $per_page );
			if ( intval( $page_no) > intval( $total_logs_pages ) )
				$page_no = 1;
			$logs				= Yop_Poll_Model::get_logs_filter_search( $orderby, $order, $search,  $poll_id, ($page_no - 1 ) * $per_page, $per_page );

			$args = array(
				'base'         => remove_query_arg( 'page_no', $_SERVER['REQUEST_URI'] ).'%_%',
				'format'       => '&page_no=%#%',
				'total'        => $total_logs_pages,
				'current'      => max( 1, $page_no ),
				'prev_next'    => True,
				'prev_text'    => __('&laquo; Previous'),
				'next_text'    => __('Next &raquo;')
			);
			$pagination	= paginate_links( $args );
			$_SERVER['REQUEST_URI'] = remove_query_arg(array('action'), $_SERVER['REQUEST_URI']);
		?>
		<div class="wrap">
			<div class="icon32 icon32-yop-poll"><br></div><h2><?php _e( 'Yop Poll Logs', 'yop_poll' ); ?></h2>
			<?php
				if ( isset($_REQUEST['deleted']) ) {
					echo '<div id="message" class="updated"><p>';
					$deleted = (int) $_REQUEST['deleted'];
					printf(_n('%s Poll Log deleted.', '%s Poll Logs deleted.', $deleted), $deleted);
					echo '</p></div>';
					$_SERVER['REQUEST_URI'] = remove_query_arg(array('deleted'), $_SERVER['REQUEST_URI']);
				}
			?>
			<form method="get">
				<?php wp_nonce_field( 'yop-poll-logs' ); ?>
				<input type="hidden" name="order" value="<?php echo $order ?>" />
				<input type="hidden" name="orderby" value="<?php echo $orderby ?>" />
				<input type="hidden" name="page" value="yop-polls-logs" />
				<p class="search-box">
					<label class="screen-reader-text" for="yop-poll-search-input"><?php _e( 'Search Poll Logs', 'yop_poll' ) ?></label>
					<input id="yop-poll-search-input" type="search" value="<?php if( isset( $_REQUEST['s'] ) ): echo esc_html( stripslashes( $_REQUEST['s'] ) ); endif; ?>" name="s" />
					<input id="search-submit" class="button" type="submit" value="<?php _e( 'Search Poll Logs', 'yop_poll' ); ?>" name="" />
				</p>
				<div class="tablenav top">
					<div class="alignleft actions">
						<select name="action">
							<option selected="selected" value="-1"><?php _e( 'Bulk Actions', 'yop_poll' ); ?></option>
							<option value="delete"><?php _e( 'Delete', 'yop_poll' ); ?></option>
						</select>
						<input type="submit" value="<?php _e( 'Apply', 'yop_poll' ); ?>" class="button-secondary action" id="doaction" name="">
					</div>
					<div class="alignleft actions">
						<select name="poll_id">
							<option value=""><?php _e( 'All Logs', 'yop_poll' ); ?></option>
							<?php 
								if ( count( $yop_polls ) > 0 ) {
									foreach( $yop_polls as $yop_poll ) {
									?>
									<option <?php echo selected( $poll_id, $yop_poll['id'] ); ?> value="<?php echo $yop_poll['id'] ?>"><?php echo $yop_poll['name'] ?></option>	
									<?php
									}
								}
							?>
						</select>
						<input type="submit" value="<?php _e( 'Filter', 'yop_poll' ); ?>" class="button-secondary" id="post-query-submit" name="">
					</div>
					<div class="tablenav-pages one-page">
						<label for="yop-poll-items-per-page" class="displaying-num"><?php _e( 'Items Per Page', 'yop_poll' ); ?>:</label>
						<input id="yop-poll-items-per-page" type="text" name="per_page" value="<?php echo $per_page; ?>" /> 
						<input name="a" value="<?php _e( 'Set', 'yop_poll' ); ?>" type="submit" />&nbsp;&nbsp;
						<span class="displaying-num"><?php echo count( $logs ); ?> / <?php echo $total_logs; ?> logs</span>
						<?php print $pagination; ?>
					</div>
					<br class="clear">
				</div>
				<table class="wp-list-table widefat fixed" cellspacing="0">
					<thead>
						<tr>
							<th id="cb" class="manage-column column-cb check-column" style="width: 2%;" scope="col">
								<input type="checkbox">
							</th>
							<th id="id" class="manage-column <?php echo $order_sortable[ 'id' ] ?> <?php echo $order_direction_reverse[ 'id' ] ?>" style="width:5%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'id', 'order' => $order_direction[ 'id' ] ) ) ); ?>">
									<span><?php _e( 'ID', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="name" class="manage-column <?php echo $order_sortable[ 'name' ] ?> <?php echo $order_direction_reverse[ 'name' ] ?>" style="width:25%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'name', 'order' => $order_direction[ 'name' ] ) ) ); ?>">
									<span><?php _e( 'Poll Name', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="total_votes" class="manage-column <?php echo $order_sortable[ 'answer' ] ?> <?php echo $order_direction_reverse[ 'answer' ] ?>" style="width:25%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'answer', 'order' => $order_direction[ 'answer' ] ) ) ); ?>">
									<span><?php _e( 'Answer', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="total_voters" class="manage-column <?php echo $order_sortable[ 'user_nicename' ] ?> <?php echo $order_direction_reverse[ 'user_nicename' ] ?>" style="width:15%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'user_nicename', 'order' => $order_direction[ 'user_nicename' ] ) ) ); ?>">
									<span><?php _e( 'User', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="question" class="manage-column <?php echo $order_sortable[ 'user_email' ] ?> <?php echo $order_direction_reverse[ 'user_email' ] ?>" style="width:15%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'user_email', 'order' => $order_direction[ 'user_email' ] ) ) ); ?>">
									<span><?php _e( 'User Email', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="start-date" class="manage-column <?php echo $order_sortable[ 'ip' ] ?> <?php echo $order_direction_reverse[ 'ip' ] ?>" style="width:10%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'ip', 'order' => $order_direction[ 'ip' ] ) ) ); ?>">
									<span><?php _e( 'Ip', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="end-date" class="manage-column <?php echo $order_sortable[ 'vote_date' ] ?> <?php echo $order_direction_reverse[ 'vote_date' ] ?>" style="width:10%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'vote_date', 'order' => $order_direction[ 'vote_date' ] ) ) ); ?>">
									<span><?php _e( 'Vote Date', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th id="cb" class="manage-column column-cb check-column" style="width: 2%;" scope="col">
								<input type="checkbox">
							</th>
							<th id="id" class="manage-column <?php echo $order_sortable[ 'id' ] ?> <?php echo $order_direction_reverse[ 'id' ] ?>" style="width:5%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'id', 'order' => $order_direction[ 'id' ] ) ) ); ?>">
									<span><?php _e( 'ID', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="name" class="manage-column <?php echo $order_sortable[ 'name' ] ?> <?php echo $order_direction_reverse[ 'name' ] ?>" style="width:25%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'name', 'order' => $order_direction[ 'name' ] ) ) ); ?>">
									<span><?php _e( 'Poll Name', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="total_votes" class="manage-column <?php echo $order_sortable[ 'answer' ] ?> <?php echo $order_direction_reverse[ 'answer' ] ?>" style="width:25%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'answer', 'order' => $order_direction[ 'answer' ] ) ) ); ?>">
									<span><?php _e( 'Answer', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="total_voters" class="manage-column <?php echo $order_sortable[ 'user_nicename' ] ?> <?php echo $order_direction_reverse[ 'user_nicename' ] ?>" style="width:15%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'user_nicename', 'order' => $order_direction[ 'user_nicename' ] ) ) ); ?>">
									<span><?php _e( 'User', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="question" class="manage-column <?php echo $order_sortable[ 'user_email' ] ?> <?php echo $order_direction_reverse[ 'user_email' ] ?>" style="width:15%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'user_email', 'order' => $order_direction[ 'user_email' ] ) ) ); ?>">
									<span><?php _e( 'User Email', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="start-date" class="manage-column <?php echo $order_sortable[ 'ip' ] ?> <?php echo $order_direction_reverse[ 'ip' ] ?>" style="width:10%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'ip', 'order' => $order_direction[ 'ip' ] ) ) ); ?>">
									<span><?php _e( 'Ip', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="end-date" class="manage-column <?php echo $order_sortable[ 'vote_date' ] ?> <?php echo $order_direction_reverse[ 'vote_date' ] ?>" style="width:10%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'vote_date', 'order' => $order_direction[ 'vote_date' ] ) ) ); ?>">
									<span><?php _e( 'Vote Date', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
						</tr>
					</tfoot>
					<?php
						if ( count ($logs) > 0 ) {
							foreach( $logs as $log ) {
							?>
							<tbody id="the-list">
								<tr valign="middle" class="alternate" id="yop-poll-log<?php echo $log['id']; ?>">
									<th class="check-column" scope="row">
										<input type="checkbox" value="<?php echo $log['id']; ?>" name="yoppolllogscheck[]">
									</th>
									<td>
										<strong><?php echo $log['id']; ?></strong><br>
									</td>
									<td>
										<strong><?php echo esc_html( stripslashes( $log['name'] ) ); ?></strong><br>
										<div class="row-actions">
											<span class="delete"><a onclick="if ( confirm( '<?php echo __( "You are about to delete this poll log",'yop_poll').": \'".esc_html( $log['id'] )."\' \\n  \'".__("Cancel", 'yop_poll')."\' ". __('to stop', 'yop_poll'). ", \'".__('OK', 'yop_poll')."\' ".__('to delete', 'yop_poll'); ?>'  ) ) { return true;}return false;" href="<?php echo wp_nonce_url( add_query_arg( array( 'action' => 'delete', 'id' => $log['id'] ) ), 'yop-poll-logs-delete' ); ?>" class="submitdelete"><?php _e( 'Delete', 'yop_poll' ) ?></a></span>
										</div>
									</td>
									<td>
										<?php echo esc_html( stripslashes( $log['answer'] ) ); ?>
									</td>
									<td>
										<?php echo esc_html( stripslashes( $log['user_nicename'] ) ); ?>
									</td>
									<td>
										<?php echo esc_html( stripslashes( $log['user_email'] ) ); ?>
									</td>
									<td>
										<?php echo esc_html( stripslashes( $log['ip'] ) ); ?>
									</td>
									<td>
										<?php echo esc_html( stripslashes( $log['vote_date'] ) ); ?>
									</td>
								</tr>
							</tbody>
							<?php
							}
						} 
					?>
				</table>
				<div class="tablenav bottom">
					<div class="tablenav-pages one-page">
						<span class="displaying-num"><?php echo count( $logs ); ?> / <?php echo $total_logs; ?> logs</span>
						<?php print $pagination; ?>
					</div>	
				</div>	
			</form>
		</div>
		<?php
		}

		public function view_yop_poll_bans() {
			global $wpdb, $page, $action, $orderby, $order;
			$per_page	= ( isset( $_GET['per_page'] ) ? intval( $_GET['per_page'] ) : 100 );
			$page_no	= isset( $_REQUEST['page_no'] ) ? (int) $_REQUEST['page_no'] : 1;
			$orderby	= ( empty( $orderby ) ) ? 'name' : $orderby;
			$order_direction	= array(
				'id'			=> 'asc', 
				'name'			=> 'asc',
				'type'			=> 'asc', 
				'value'			=> 'asc', 
			);
			$order_direction[ $orderby ] = ( 'desc' == $order ) ? 'asc' : 'desc';

			$order_direction_reverse	= array(
				'id'			=> 'desc', 
				'name'			=> 'desc', 
				'type'			=> 'desc', 
				'value'			=> 'desc', 
			);
			$order_direction_reverse[ $orderby ]	= ( 'desc' == $order ) ? 'desc' : 'asc';

			$order_sortable	= array(
				'id'			=> 'sortable', 
				'name'			=> 'sortable', 
				'type'			=> 'sortable', 
				'value'			=> 'sortable', 
			);
			$order_sortable[ $orderby ]	= 'sorted';
			$poll_id			= isset( $_REQUEST['poll_id'] ) ? (int) $_REQUEST['poll_id'] : NULL;
			$type				= isset( $_REQUEST['type'] ) ? $_REQUEST['type'] : NULL;
			require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
			$yop_polls 			= Yop_Poll_Model::get_yop_polls_filter_search( 'id', 'asc' );
			$search 			= array( 'fields' => array( $wpdb->yop_poll_bans . '.value' ), 'value' => isset( $_REQUEST['s'] ) ? trim( $_REQUEST['s'] ) : '' );
			$total_bans			= count( Yop_Poll_Model::get_bans_filter_search($orderby, $order, $search, $type,  $poll_id) );
			$total_bans_pages	= ceil(  $total_bans / $per_page );
			if ( intval( $page_no) > intval( $total_bans_pages ) )
				$page_no = 1;
			$bans				= Yop_Poll_Model::get_bans_filter_search($orderby, $order, $search, $type,  $poll_id, ($page_no - 1 ) * $per_page, $per_page);

			$args = array(
				'base'         => remove_query_arg( 'page_no', $_SERVER['REQUEST_URI'] ).'%_%',
				'format'       => '&page_no=%#%',
				'total'        => $total_bans_pages,
				'current'      => max( 1, $page_no ),
				'prev_next'    => True,
				'prev_text'    => __('&laquo; Previous'),
				'next_text'    => __('Next &raquo;')
			);
			$pagination	= paginate_links( $args );
			$_SERVER['REQUEST_URI'] = remove_query_arg(array('action'), $_SERVER['REQUEST_URI']);
		?>
		<div class="wrap">
			<div class="icon32 icon32-yop-poll"><br></div><h2><?php _e( 'Yop Poll Bans', 'yop_poll' ); ?> <a href="javascript:void(0);" class="add-new-h2" id="yop-poll-add-new-ban"><?php _e( 'Add New', 'yop_poll' ); ?></a></h2>
			<?php
				if ( isset($_REQUEST['deleted']) ) {
					echo '<div id="message" class="updated"><p>';
					$deleted = (int) $_REQUEST['deleted'];
					printf(_n('%s Poll Ban deleted!', '%s Poll Bans deleted!', $deleted), $deleted);
					echo '</p></div>';
					$_SERVER['REQUEST_URI'] = remove_query_arg(array('deleted'), $_SERVER['REQUEST_URI']);
				}
			?>
			<?php
				if ( isset($_REQUEST['bans-added']) ) {
					echo '<div id="message" class="updated"><p>';
					$added = (int) $_REQUEST['bans-added'];
					printf(_n('%s Poll Ban added!', '%s Poll Bans added!', $added), $added);
					echo '</p></div>';
					$_SERVER['REQUEST_URI'] = remove_query_arg(array('bans-added'), $_SERVER['REQUEST_URI']);
				}
			?>				
			<?php
				if ( isset($_REQUEST['bans-error']) ) {
					echo '<div id="message" class="error"><p>';
					print $_REQUEST['bans-error'];
					echo '</p></div>';
					$_SERVER['REQUEST_URI'] = remove_query_arg(array('bans-error'), $_SERVER['REQUEST_URI']);
				}
			?>
			<div id='yop-poll-add-ban-div' style="display: none;">
				<p><?php _e( 'Ban IP, Username or Email', 'yop_poll' ); ?></p>
				<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
					<input type="hidden" name="page" value="yop-polls-bans" />
					<input type="hidden" name="action" value="add-ban" />
					<?php wp_nonce_field( 'yop-poll-add-ban' ); ?>
					<table class="form-table">
						<tbody>
							<tr class="form-field form-required">
								<th scope="row"><label for="ban-poll-id"><?php _e( 'Poll', 'yop_poll' ); ?> <span class="description">(required)</span></label></th>
								<td>
									<select id="ban-poll-id" name="ban_poll_id">
										<option value="0"><?php _e( 'Bans For All Polls', 'yop_poll' ); ?></option>
										<?php 
											if ( count( $yop_polls ) > 0 ) {
												foreach( $yop_polls as $yop_poll ) {
												?>
												<option value="<?php echo $yop_poll['id'] ?>"><?php echo $yop_poll['name'] ?></option>	
												<?php
												}
											}
										?>
									</select>
								</td>
							</tr>
							<tr class="form-field form-required">
								<th scope="row"><label for="yop-poll-ban-type"><?php _e( 'Type', 'yop_poll' ); ?> <span class="description">(required)</span></label></th>
								<td>
									<select id="yop-poll-ban-type" name="ban_type">
									<option value=""><?php _e( 'Choose Ban Type', 'yop_poll' ); ?></option>
									<option value="ip"><?php _e( 'IP', 'yop_poll' ); ?></option>
									<option value="username"><?php _e( 'Username', 'yop_poll' ); ?></option>
									<option value="email"><?php _e( 'Email', 'yop_poll' ); ?></option>
								</td>
							</tr>
							<tr class="form-field form-required">
								<th scope="row"><label for="yop-poll-ban-value"><?php _e( 'Value', 'yop_poll' ); ?> <span class="description">(required)</span><br><small><i><?php _e( 'One Value Per Line', 'yop_poll'); ?></i></small></label></th>
								<td><textarea rows="5" cols="20" id="yop-poll-ban-value" name="ban_value"></textarea></td>
							</tr>
						</tbody>
					</table>
					<p class="submit"><input type="submit" value="<?php _e( 'Add Ban', 'yop_poll');?> " class="button-primary"> <input id="yop-poll-add-ban-close" type="button" value="<?php _e( 'Close', 'yop_poll');?> " class="button-primary"></p>
				</form>
			</div>
			<br />
			<form method="get">
				<?php wp_nonce_field( 'yop-poll-bans' ); ?>
				<input type="hidden" name="order" value="<?php echo $order ?>" />
				<input type="hidden" name="orderby" value="<?php echo $orderby ?>" />
				<input type="hidden" name="page" value="yop-polls-bans" />
				<p class="search-box">
					<label class="screen-reader-text" for="yop-poll-search-input"><?php _e( 'earch Poll Bans', 'yop_poll' ) ?></label>
					<input id="yop-poll-search-input" type="search" value="<?php if( isset( $_REQUEST['s'] ) ): echo esc_html( stripslashes( $_REQUEST['s'] ) ); endif; ?>" name="s" />
					<input id="search-submit" class="button" type="submit" value="<?php _e( 'earch Poll Bans', 'yop_poll' ); ?>" name="" />
				</p>
				<div class="tablenav top">
					<div class="alignleft actions">
						<select name="action">
							<option selected="selected" value="-1"><?php _e( 'Bulk Actions', 'yop_poll' ); ?></option>
							<option value="delete"><?php _e( 'Delete', 'yop_poll' ); ?></option>
						</select>
						<input type="submit" value="<?php _e( 'Apply', 'yop_poll' ); ?>" class="button-secondary action" id="doaction" name="">
					</div>
					<div class="alignleft actions">
						<select name="poll_id">
							<option value=""><?php _e( 'All Polls', 'yop_poll' ); ?></option>
							<?php 
								if ( count( $yop_polls ) > 0 ) {
									foreach( $yop_polls as $yop_poll ) {
									?>
									<option <?php echo selected( $poll_id, $yop_poll['id'] ); ?> value="<?php echo $yop_poll['id'] ?>"><?php echo $yop_poll['name'] ?></option>	
									<?php
									}
								}
							?>
						</select>
					</div>
					<div class="alignleft actions">
						<select name="type">
							<option value=""><?php _e( 'All Ban Types', 'yop_poll' ); ?></option>
							<option <?php echo selected('ip', $type); ?> value="ip"><?php _e( 'IP', 'yop_poll' ); ?></option>
							<option <?php echo selected('username', $type); ?> value="username"><?php _e( 'Username', 'yop_poll' ); ?></option>
							<option <?php echo selected('email', $type); ?> value="email"><?php _e( 'Email', 'yop_poll' ); ?></option>
						</select>
						<input type="submit" value="<?php _e( 'Filter', 'yop_poll' ); ?>" class="button-secondary" id="post-query-submit" name="">
					</div>
					<div class="tablenav-pages one-page">
						<label for="yop-poll-items-per-page" class="displaying-num"><?php _e( 'Items Per Page', 'yop_poll' ); ?>:</label>
						<input id="yop-poll-items-per-page" type="text" name="per_page" value="<?php echo $per_page; ?>" /> 
						<input name="a" value="<?php _e( 'Set', 'yop_poll' ); ?>" type="submit" />&nbsp;&nbsp;
						<span class="displaying-num"><?php echo count( $bans ); ?> / <?php echo $total_bans; _e('Bans', 'yop_poll')?> </span>
						<?php print $pagination; ?>
					</div>
					<br class="clear">
				</div>
				<table class="wp-list-table widefat fixed" cellspacing="0">
					<thead>
						<tr>
							<th id="cb" class="manage-column column-cb check-column" style="width: 2%;" scope="col">
								<input type="checkbox">
							</th>
							<th id="id" class="manage-column <?php echo $order_sortable[ 'id' ] ?> <?php echo $order_direction_reverse[ 'id' ] ?>" style="width:5%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'id', 'order' => $order_direction[ 'id' ] ) ) ); ?>">
									<span><?php _e( 'ID', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="name" class="manage-column <?php echo $order_sortable[ 'name' ] ?> <?php echo $order_direction_reverse[ 'name' ] ?>" style="width:25%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'name', 'order' => $order_direction[ 'name' ] ) ) ); ?>">
									<span><?php _e( 'Poll Name', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="type" class="manage-column <?php echo $order_sortable[ 'type' ] ?> <?php echo $order_direction_reverse[ 'type' ] ?>" style="width:25%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'type', 'order' => $order_direction[ 'type' ] ) ) ); ?>">
									<span><?php _e( 'Ban Type', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="value" class="manage-column <?php echo $order_sortable[ 'value' ] ?> <?php echo $order_direction_reverse[ 'value' ] ?>" style="width:15%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'value', 'order' => $order_direction[ 'value' ] ) ) ); ?>">
									<span><?php _e( 'Ban Value', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th id="cb" class="manage-column column-cb check-column" style="width: 2%;" scope="col">
								<input type="checkbox">
							</th>
							<th id="id" class="manage-column <?php echo $order_sortable[ 'id' ] ?> <?php echo $order_direction_reverse[ 'id' ] ?>" style="width:5%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'id', 'order' => $order_direction[ 'id' ] ) ) ); ?>">
									<span><?php _e( 'ID', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="name" class="manage-column <?php echo $order_sortable[ 'name' ] ?> <?php echo $order_direction_reverse[ 'name' ] ?>" style="width:25%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'name', 'order' => $order_direction[ 'name' ] ) ) ); ?>">
									<span><?php _e( 'Poll Name', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="type" class="manage-column <?php echo $order_sortable[ 'type' ] ?> <?php echo $order_direction_reverse[ 'type' ] ?>" style="width:25%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'type', 'order' => $order_direction[ 'type' ] ) ) ); ?>">
									<span><?php _e( 'Ban Type', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
							<th id="value" class="manage-column <?php echo $order_sortable[ 'value' ] ?> <?php echo $order_direction_reverse[ 'value' ] ?>" style="width:15%" scope="col">
								<a href="<?php echo esc_url( add_query_arg( array( 'orderby' => 'value', 'order' => $order_direction[ 'value' ] ) ) ); ?>">
									<span><?php _e( 'Ban Value', 'yop_poll' ); ?></span>
									<span class="sorting-indicator"></span>
								</a>
							</th>
						</tr>
					</tfoot>
					<?php
						if ( count ($bans) > 0 ) {
							foreach( $bans as $ban ) {
							?>
							<tbody id="the-list">
								<tr valign="middle" class="alternate" id="yop-poll-log<?php echo $ban['id']; ?>">
									<th class="check-column" scope="row">
										<input type="checkbox" value="<?php echo $ban['id']; ?>" name="yoppollbanscheck[]">
									</th>
									<td>
										<strong><?php echo $ban['id']; ?></strong><br>
									</td>
									<td>
										<strong><?php echo esc_html( stripslashes( $ban['name'] ) ); ?></strong><br>
										<div class="row-actions">
											<span class="delete"><a onclick="if ( confirm( '<?php echo __( "You are about to remove this poll ban",'yop_poll').": \'".esc_html( $log['id'] )."\' \\n  \'".__("Cancel", 'yop_poll')."\' ". __('to stop', 'yop_poll'). ", \'".__('OK', 'yop_poll')."\' ".__('to remove', 'yop_poll'); ?>' ) ) { return true;}return false;" href="<?php echo wp_nonce_url( add_query_arg( array( 'action' => 'delete', 'id' => $ban['id'] ) ), 'yop-poll-bans-delete' ); ?>" class="submitdelete"><?php _e( 'Remove', 'yop_poll' ) ?></a></span>
										</div>
									</td>
									<td>
										<?php echo esc_html( stripslashes( $ban['type'] ) ); ?>
									</td>
									<td>
										<?php echo esc_html( stripslashes( $ban['value'] ) ); ?>
									</td>
								</tr>
							</tbody>
							<?php
							}
						} 
					?>
				</table>
				<div class="tablenav bottom">
					<div class="tablenav-pages one-page">
						<span class="displaying-num"><?php echo count( $bans ); ?> / <?php echo $total_bans; _e('Bans', 'yop_poll')?> </span>
						<?php print $pagination; ?>
					</div>	
				</div>	
			</form>
		</div>
		<?php
		}

		public function yop_poll_options_admin_init(){
			register_setting( 'yop_poll_options', 'yop_poll_options', array( &$this, 'yop_poll_options_validate' ) );
		}

		public function yop_poll_options_validate( $input ) {
			$default_options		= get_option( 'yop_poll_options', array() );
			$newinput				= $default_options;
			$errors					= '';
			$updated				= '';
			$message_delimiter		= '<br>';
			//allow_other_answers
			if ( isset( $input['allow_other_answers'] ) ) {
				if( in_array( $input['allow_other_answers'], array('yes', 'no') ) ) {
					$newinput['allow_other_answers']			=  trim( $input['allow_other_answers'] );
					$updated									.= __( 'Option "Allow Other Answer" Updated!', 'yop_poll' ).$message_delimiter;
				}
				else {
					$newinput['allow_other_answers']			=  $default_options['allow_other_answers'];
					$errors										.= __( 'Option "Allow Other Answer" Not Updated! Choose "yes" or "no"!', 'yop_poll' ).$message_delimiter;
				}

				//other_answers_label
				if ( 'yes' == $input['allow_other_answers'] ) {
					if( isset( $input['other_answers_label'] ) ) {
						if( '' != trim( $input['other_answers_label'] ) ) { 
							$newinput['other_answers_label']	= trim( $input['other_answers_label'] ) ;
							$updated							.= __( 'Option "Allow Other Answer Label" Updated!', 'yop_poll' ).$message_delimiter;
						}
						else {
							$newinput['other_answers_label']	= $default_options['other_answers_label'] ;
							$errors								.= __( 'Option "Other Answer Label" Not Updated! The field must not be empty!', 'yop_poll' ).$message_delimiter;	
						}
					}
					else {
						$newinput['other_answers_label']		= $default_options['other_answers_label'] ;
						$errors									.= __( 'Option "Allow Other Answer Label" Not Updated! The field must not be empty!', 'yop_poll' ).$message_delimiter;
					}

					if( isset( $input['display_other_answers_values'] ) ) {
						if( in_array( $input['display_other_answers_values'], array('yes', 'no') ) ) { 
							$newinput['display_other_answers_values']	= trim( $input['display_other_answers_values'] ) ;
							$updated							.= __( 'Option "Display Other Answers Values" Updated!', 'yop_poll' ).$message_delimiter;
						}
						else {
							$newinput['display_other_answers_values']	= $default_options['display_other_answers_values'] ;
							$errors								.= __( 'Option "Display Other Answers Values" Not Updated! Choose "yes" or "no"!', 'yop_poll' ).$message_delimiter;	
						}
					}
					else {
						$newinput['display_other_answers_values']		= $default_options['display_other_answers_values'] ;
						$errors									.= __( 'Option "Display Other Answers Values" Not Updated! Choose "yes" or "no"!', 'yop_poll' ).$message_delimiter;
					}
				}
			}
			else {
				$newinput['allow_other_answers']				=  $default_options['allow_other_answers'];
				$errors											.= __( 'Option "Allow Other Answer" Not Updated! Choose "yes" or "no"!', 'yop_poll' ).$message_delimiter;
			}

			//allow_multiple_answers
			if ( isset( $input['allow_multiple_answers'] ) ) {
				if ( in_array( $input['allow_multiple_answers'], array('yes', 'no') ) ) { 
					$newinput['allow_multiple_answers']			= trim( $input['allow_multiple_answers'] );
					$updated									.= __( 'Option "Allow Multiple Answers" Updated!', 'yop_poll' ).$message_delimiter;

					//allow_multiple_answers_number
					if( 'yes' == $input['allow_multiple_answers'] ) {
						if ( isset( $input['allow_multiple_answers_number'] ) ) {
							if ( ctype_digit( $input['allow_multiple_answers_number'] ) ) {
								$newinput['allow_multiple_answers_number']	= trim( $input['allow_multiple_answers_number'] );
								$updated									.= __( 'Option "Number of allowed answers" Updated!', 'yop_poll' ).$message_delimiter;
							}
							else {
								$newinput['allow_multiple_answers_number']	= $default_options['allow_multiple_answers_number'];
								$errors										.= __( 'Option "Number of allowed answers" Not Updated! Please fill in a number!', 'yop_poll' ).$message_delimiter;
							}
						}
						else {
							$newinput['allow_multiple_answers_number']		= $default_options['allow_multiple_answers_number'] ;
							$errors											.= __( 'Option "Number of allowed answers" Not Updated! The field must not be empty!', 'yop_poll' ).$message_delimiter;
						}
					}
				}
				else {
					$newinput['allow_multiple_answers']						= $default_options['allow_multiple_answers'];
					$errors													.= __( 'Option "Allow Multiple Answers" Not Updated! Choose "yes" or "no"!', 'yop_poll' ).$message_delimiter;
				}
			}
			else {
				$newinput['allow_multiple_answers']				=  $default_options['allow_multiple_answers'];
				$errors											.= __( 'Option "Allow Multiple Answers" Not Updated! Choose "yes" or "no"!', 'yop_poll' ).$message_delimiter;
			}

			//display_answers	
			if ( isset( $input['display_answers'] ) ) {
				if ( in_array( $input['display_answers'], array('vertical', 'orizontal', 'tabulated') ) ) {
					$newinput['display_answers']							=  trim( $input['display_answers'] );
					$updated												.= __( 'Option "Display Answers" Updated!', 'yop_poll' ).$message_delimiter;

					if( 'tabulated' == $input['display_answers'] ) {
						//display_answers_tabulated_cols	
						if ( isset( $input['display_answers_tabulated_cols'] ) ) {
							if ( ctype_digit( $input['display_answers_tabulated_cols'] ) ) {
								$newinput['display_answers_tabulated_cols']	= trim( $input['display_answers_tabulated_cols'] );
								$updated									.= __( 'Option "Columns for Tabulated Display Answers" Updated!', 'yop_poll' ).$message_delimiter;
							}
							else {
								$newinput['display_answers_tabulated_cols']	= $default_options['display_answers_tabulated_cols'];
								$errors											.= __( 'Option "Columns for Tabulated Display Answers" Not Updated! Please fill in a number!', 'yop_poll' ).$message_delimiter;
							}
						}
						else {
							$newinput['display_answers_tabulated_cols']		= $default_options['display_answers_tabulated_cols'] ;
							$errors											.= __( 'Option "Columns for Tabulated Display Answers" Not Updated!', 'yop_poll' ).$message_delimiter;
						}
					}
				}
				else {
					$newinput['display_answers']							=  $default_options['display_answers'];
					$errors													.= __( 'Option "Display Answers" Not Updated! you must choose between \'vertical\', \'orizontal\' or \'tabulated\'', 'yop_poll' ).$message_delimiter;
				}
			}
			else {
				$newinput['display_answers']								=  $default_options['display_answers'];
				$errors														.= __( 'Option "Display Answers" Not Updated!', 'yop_poll' ).$message_delimiter;
			}

			//display_results	
			if ( isset( $input['display_results'] ) ) {
				if ( in_array( $input['display_results'], array('vertical', 'orizontal', 'tabulated') ) ) {
					$newinput['display_results']						= trim( $input['display_results'] );
					$updated											.= __( 'Option "Display Results" Updated!', 'yop_poll' ).$message_delimiter;

					if( 'tabulated' == $input['display_results'] ) {
						//display_results_tabulated_cols	
						if ( isset( $input['display_results_tabulated_cols'] ) ) {
							if ( ctype_digit( $input['display_results_tabulated_cols'] ) ) {
								$newinput['display_results_tabulated_cols']	= trim( $input['display_results_tabulated_cols'] );
								$updated									.= __( 'Option "Columns for Tabulated Display Results" Updated!', 'yop_poll' ).$message_delimiter;
							}
							else {
								$newinput['display_results_tabulated_cols']	= $default_options['display_results_tabulated_cols'];
								$errors											.= __( 'Option "Columns for Tabulated Display Results" Not Updated! Please fill in a number!', 'yop_poll' ).$message_delimiter;
							}
						}
						else {
							$newinput['display_results_tabulated_cols']		= $default_options['display_results_tabulated_cols'] ;
							$errors											.= __( 'Option "Columns for Tabulated Display Results" Not Updated!', 'yop_poll' ).$message_delimiter;
						}
					}
				}
				else {
					$newinput['display_results']						= $default_options['display_results'];
					$errors												.= __( 'Option "Display Results" Not Updated! Choose the display layout: \'vertical\', \'orizontal\' or \'tabulated\'', 'yop_poll' ).$message_delimiter;
				}
			}
			else {
				$newinput['display_results']							=  $default_options['display_results'];
				$errors													.= __( 'Option "Display Results" Not Updated!', 'yop_poll' ).$message_delimiter;
			}

			//use_template_bar
			if ( isset( $input['use_template_bar'] ) ) {
				if ( in_array( $input['use_template_bar'], array('yes', 'no') ) ) { 
					$newinput['use_template_bar']			= trim( $input['use_template_bar'] );
					$updated								.= __( 'Option "Use Template Result Bar" Updated!', 'yop_poll' ).$message_delimiter;

					if ('no' == $input['use_template_bar'] ) {
						//bar_background
						if ( isset( $input['bar_background'] ) ) {
							if ( ctype_alnum( $input['bar_background'] ) ) {
								$newinput['bar_background']	= trim( $input['bar_background'] );
								$updated					.= __( 'Option "Result Bar Background Color" Updated!', 'yop_poll' ).$message_delimiter;
							}
							else {
								$newinput['bar_background']	= $default_options['bar_background'];
								$errors						.= __( 'Option "Result Bar Background Color" Not Updated! Fill in an alphanumeric value!', 'yop_poll' ).$message_delimiter;
							}
						}
						else {
							$newinput['bar_background']		= $default_options['bar_background'];
							$errors							.= __( 'Option "Result Bar Background Color" Not Updated!', 'yop_poll' ).$message_delimiter;	
						}

						//bar_height
						if ( isset( $input['bar_height'] ) ) {
							if ( ctype_digit( $input['bar_height'] ) ) {
								$newinput['bar_height']	= trim( $input['bar_height'] );
								$updated				.= __( 'Option "Result Bar Height" Updated!', 'yop_poll' ).$message_delimiter;
							}
							else {
								$newinput['bar_height']	= $default_options['bar_height'];
								$errors					.= __( 'Option "Result Bar Height" Not Updated! Please fill in a number!', 'yop_poll' ).$message_delimiter;
							}
						}
						else {
							$newinput['bar_height']		= $default_options['bar_height'];
							$errors						.= __( 'Option "Result Bar Height" Not Updated!', 'yop_poll' ).$message_delimiter;	
						}

						//bar_border_color
						if ( isset( $input['bar_border_color'] ) ) {
							if ( ctype_alnum( $input['bar_border_color'] ) ) {
								$newinput['bar_border_color']	= trim( $input['bar_border_color'] );
								$updated						.= __( 'Option "Result Bar Border Color" Updated!', 'yop_poll' ).$message_delimiter;
							}
							else {
								$newinput['bar_border_color']	= $default_options['bar_border_color'];
								$errors							.= __( 'Option "Result Bar Border Color" Not Updated! Please fill in a number!', 'yop_poll' ).$message_delimiter;
							}
						}
						else {
							$newinput['bar_border_color']		= $default_options['bar_border_color'];
							$errors								.= __( 'Option "Result Bar Border Color" Not Updated!', 'yop_poll' ).$message_delimiter;	
						}

						//bar_border_width
						if ( isset( $input['bar_border_width'] ) ) {
							if ( ctype_digit( $input['bar_border_width'] ) ) {
								$newinput['bar_border_width']	= trim( $input['bar_border_width'] );
								$updated						.= __( 'Option "Result Bar Border Width" Updated!', 'yop_poll' ).$message_delimiter;
							}
							else {
								$newinput['bar_border_width']	= $default_options['bar_border_width'];
								$errors							.= __( 'Option "Result Bar Border Width" Not Updated! Please fill in a number!', 'yop_poll' ).$message_delimiter;
							}
						}
						else {
							$newinput['bar_border_width']		= $default_options['bar_border_width'];
							$errors								.= __( 'Option "Result Bar Border Width" Not Updated!', 'yop_poll' ).$message_delimiter;	
						}

						//bar_border_style
						if ( isset( $input['bar_border_style'] ) ) {
							if ( ctype_alpha( $input['bar_border_style'] ) ) {
								$newinput['bar_border_style']	= trim( $input['bar_border_style'] );
								$updated						.= __( 'Option "Result Bar Border Style" Updated!', 'yop_poll' ).$message_delimiter;
							}
							else {
								$newinput['bar_border_style']	= $default_options['bar_border_style'];
								$errors							.= __( 'Option "Result Bar Border Style" Not Updated! Fill in an alphanumeric value!', 'yop_poll' ).$message_delimiter;
							}
						}
						else {
							$newinput['bar_border_style']		= $default_options['bar_border_style'];
							$errors								.= __( 'Option "Result Bar Border Style" Not Updated!', 'yop_poll' ).$message_delimiter;	
						}
					}
				}
				else {
					$newinput['use_template_bar']		= $default_options['use_template_bar'];
					$errors									.= __( 'Option "Use Template Result Bar" Not Updated! Choose "yes" or "no"!', 'yop_poll' ).$message_delimiter;
				}
			}
			else {
				$newinput['use_template_bar']	= $default_options['use_template_bar'];
				$errors							.= __( 'Option "Use Template Result Bar" Not Updated!', 'yop_poll' ).$message_delimiter;	
			}

			//sorting_answers	
			if ( isset( $input['sorting_answers'] ) ) {
				if ( in_array( $input['sorting_answers'], array('exact', 'alphabetical', 'random', 'votes') ) ) {
					$newinput['sorting_answers']					= trim( $input['sorting_answers'] );
					$updated										.= __( 'Option "Sort Answers" Updated!', 'yop_poll' ).$message_delimiter;

					//sorting_answers_direction	
					if ( isset( $input['sorting_answers_direction'] ) ) {
						if ( in_array( $input['sorting_answers_direction'], array('asc', 'desc') ) ) {
							$newinput['sorting_answers_direction']	= trim( $input['sorting_answers_direction'] );
							$updated								.= __( 'Option "Sort Answers Direction" Updated!', 'yop_poll' ).$message_delimiter;
						}
						else {
							$newinput['sorting_answers_direction']	= $default_options['sorting_answers_direction'];
							$errors									.= __( 'Option "Sort Answers Direction" Not Updated! Please choose between \'Ascending\' or \'Descending\'', 'yop_poll' ).$message_delimiter;
						}
					}
					else {
						$newinput['sorting_answers_direction']		= $default_options['sorting_answers_direction'];
						$errors										.= __( 'Option "Sort Answers Direction" Not Updated!', 'yop_poll' ).$message_delimiter;
					}
				}
				else {
					$newinput['sorting_answers']					= $default_options['sorting_answers'];
					$errors											.= __( 'Option "Sort Answers" Not Updated! Please choose between: \'exact\', \'alphabetical\', \'random\' or \'votes\'', 'yop_poll' ).$message_delimiter;
				}
			}
			else {
				$newinput['sorting_answers']						= $default_options['sorting_answers'];
				$errors												.= __( 'Option "Sort Answers" Not Updated!', 'yop_poll' ).$message_delimiter;	
			}

			//sorting_results	
			if ( isset( $input['sorting_answers'] ) ) {
				if ( in_array( $input['sorting_results'], array('exact', 'alphabetical', 'random', 'votes') ) ) {
					$newinput['sorting_results']					= trim( $input['sorting_results'] );
					$updated										.= __( 'Option "Sort Results" Updated!', 'yop_poll' ).$message_delimiter;

					//sorting_results_direction	
					if ( isset( $input['sorting_results_direction'] ) ) {
						if ( in_array( $input['sorting_results_direction'], array('asc', 'desc') ) ) {
							$newinput['sorting_results_direction']	= trim( $input['sorting_results_direction'] );
							$updated								.= __( 'Option "Sort Results Direction" Updated!', 'yop_poll' ).$message_delimiter;
						}
						else {
							$newinput['sorting_results_direction']	= $default_options['sorting_results_direction'];
							$errors									.= __( 'Option "Sort Results Direction" Not Updated! Please choose between \'Ascending\' or \'Descending\'', 'yop_poll' ).$message_delimiter;
						}
					}
					else {
						$newinput['sorting_results_direction']		= $default_options['sorting_results_direction'];
						$errors										.= __( 'Option "Sort Results Direction" Not Updated!', 'yop_poll' ).$message_delimiter;
					}
				}
				else {
					$newinput['sorting_results']					= $default_options['sorting_results'];
					$errors											.= __( 'Option "Sort Results" Not Updated! Please choose between: \'exact\', \'alphabetical\', \'random\' or \'votes\'', 'yop_poll' ).$message_delimiter;
				}
			}
			else {
				$newinput['sorting_results']						= $default_options['sorting_results'];
				$errors												.= __( 'Option "Sort Answers" Not Updated!', 'yop_poll' ).$message_delimiter;	
			}

			//start_date	
			if ( isset( $input['start_date'] ) ) {
				if ( '' != trim( $input['start_date'] ) ) {
					$newinput['start_date']	= trim( $input['start_date'] );
					$updated				.= __( 'Option "Poll Start Date" Updated!', 'yop_poll' ).$message_delimiter;
				} 
				else {
					$newinput['start_date']	= $default_options['start_date'];
					$errors					.= __( 'Option "Poll Start Date" Not Updated! The field is empty!', 'yop_poll' ).$message_delimiter;
				}
			} 
			else {
				$newinput['start_date']		= $default_options['start_date'];
				$errors						.= __( 'Option "Poll Start Date" Not Updated!', 'yop_poll' ).$message_delimiter;
			}

			//never_expire
			if ( isset( $input['never_expire'] ) ) {
				if ( 'yes' == $input['never_expire'] ) {
					$newinput['never_expire']	= trim( $input['never_expire'] );
					$updated					.= __( 'Option "Poll End Date" Updated!', 'yop_poll' ).$message_delimiter;
				}
				else {
					$newinput['never_expire']	= $default_options['never_expire'] ;
					$errors						.= __( 'Option "Poll End Date" Not Updated! ', 'yop_poll' ).$message_delimiter;
				}
			}
			else {
				$newinput['never_expire'] 		= 'no';
			}

			//end_date	
			if ( 'yes' != $newinput['never_expire'] ) {
				if ( isset( $input['end_date'] ) ) {
					if ( '' != $input['end_date'] ) {
						$newinput['end_date']	= $input['end_date'];
						$updated				.= __( 'Option "Poll End Date" Updated!', 'yop_poll' ).$message_delimiter;
					}
					else {
						$errors				.= __( 'Option "Poll End Date" Not Updated! The field is empty! ', 'yop_poll' ).$message_delimiter;
					}
				}
				else {
					$errors					.= __( 'Option "Poll End Date" Not Updated! ', 'yop_poll' ).$message_delimiter;
				}
			}

			//view_results
			if ( isset( $input['view_results'] ) ) {
				if ( in_array( $input['view_results'], array('before', 'after', 'after-poll-end-date', 'never', 'custom-date' ) ) ) {
					$newinput['view_results']						= trim( $input['view_results'] );
					$updated										.= __( 'Option "View Results" Updated!', 'yop_poll' ).$message_delimiter;

					if ( 'custom-date' == $newinput['view_results'] ) {
						//view_results_start_date
						if ( isset( $input['view_results_start_date'] ) ) {
							$newinput['view_results_start_date']	= $input['view_results_start_date'];
							$updated								.= __( 'Option "View Results Custom Date" Updated!', 'yop_poll' ).$message_delimiter;
						}
						else {
							$newinput['view_results_start_date']	= $default_options['view_results_start_date'];
							$errors									.= __( 'Option "View Results" Not Updated!', 'yop_poll' ).$message_delimiter;
						}
					}
				}
				else {
					$newinput['view_results']						= $default_options['view_results'];
					$errors											.= __( 'Option "View Results" Not Updated! Please choose between: \'Before\', \'After\', \'After Poll End Date\', \'Never\' or \'Custom Date\'', 'yop_poll' ).$message_delimiter;
				}
			} 
			else {
				$newinput['view_results']								= $default_options['view_results'];
				$errors												.= __( 'Option "View Results" Not Updated!', 'yop_poll' ).$message_delimiter;	
			}

			//view_results_type
			if ( isset( $input['view_results_type'] ) ) {
				if ( in_array( $input['view_results_type'], array('votes-number', 'percentages', 'votes-number-and-percentages' ) ) ) {
					$newinput['view_results_type']	=  trim( $input['view_results_type'] );
					$updated						.= __( 'Option "View Results Type" Updated!', 'yop_poll' ).$message_delimiter;
				}
				else {
					$newinput['view_results_type']	= $default_options['view_results_type'];
					$errors							.= __( 'Option "View Results Type" Not Updated! Please choose between: \'Votes number\', \'Percentages\' or \'Votes number and percentages\' ', 'yop_poll' ).$message_delimiter;
				}
			}
			else {
				$newinput['view_results_type']		= $default_options['view_results_type'];
				$errors								.= __( 'Option "View Results Type" Not Updated!', 'yop_poll' ).$message_delimiter;
			}

			//answer_result_label
			if ( isset( $input['answer_result_label'] ) ) {
				if ( 'votes-number' == $input['view_results_type'] ) {
					if ( stripos( $input['answer_result_label'], '%POLL-ANSWER-RESULT-VOTES%' ) === false ) {
						$newinput['answer_result_label']	= $default_options['answer_result_label'];
						$errors								.= __( 'Option "Poll Answer Result Label" Not Updated! You must use %POLL-ANSWER-RESULT-VOTES%!', 'yop_poll' ).$message_delimiter;	
					}
					else {
						$newinput['answer_result_label']	= trim( $input['answer_result_label'] );
						$updated						.= __( 'Option "Poll Answer Result Label" Updated!', 'yop_poll' ).$message_delimiter;	
					}
				}

				if ( 'percentages' == $input['view_results_type'] ) {
					if ( stripos( $input['answer_result_label'], '%POLL-ANSWER-RESULT-PERCENTAGES%' ) === false ) {
						$newinput['answer_result_label']	= $default_options['answer_result_label'];
						$errors								.= __( 'Option "Poll Answer Result Label" Not Updated! You must use %POLL-ANSWER-RESULT-PERCENTAGES%!', 'yop_poll' ).$message_delimiter;	
					}
					else {
						$newinput['answer_result_label']	= trim( $input['answer_result_label'] );
						$updated						.= __( 'Option "Poll Answer Result Label" Updated!', 'yop_poll' ).$message_delimiter;	
					}
				}

				if ( 'votes-number-and-percentages' == $input['view_results_type'] ) {
					if ( stripos( $input['answer_result_label'], '%POLL-ANSWER-RESULT-PERCENTAGES%' ) === false ) {
						$newinput['answer_result_label']	= $default_options['answer_result_label'];
						$errors								.= __( 'Option "Poll Answer Result Label" Not Updated! You must use %POLL-ANSWER-RESULT-VOTES% and %POLL-ANSWER-RESULT-PERCENTAGES%!', 'yop_poll' ).$message_delimiter;	
					}
					elseif ( stripos( $input['answer_result_label'], '%POLL-ANSWER-RESULT-VOTES%' ) === false ) {
						$newinput['answer_result_label']	= $default_options['answer_result_label'];
						$errors								.= __( 'Option "Poll Answer Result Label" Not Updated! You must use %POLL-ANSWER-RESULT-VOTES% and %POLL-ANSWER-RESULT-PERCENTAGES%!', 'yop_poll' ).$message_delimiter;	
					}
					else {
						$newinput['answer_result_label']	= trim( $input['answer_result_label'] );
						$updated						.= __( 'Option "Poll Answer Result Label" Updated!', 'yop_poll' ).$message_delimiter;	
					}
				}	
			}
			else {
				$newinput['answer_result_label']			= $default_options['answer_result_label'];
				$errors										.= __( 'Option "Poll Answer Result Label" Not Updated!', 'yop_poll' ).$message_delimiter;	
			}
			
			//singular_answer_result_votes_number_label
			if ( isset( $input['singular_answer_result_votes_number_label'] ) ) {
				if ( '' !=  $input['singular_answer_result_votes_number_label'] ) {
					$newinput['singular_answer_result_votes_number_label']			= trim( $input['singular_answer_result_votes_number_label'] );
					$updated								.= __( 'Option "Poll Answer Result Votes Number Singular Label" Updated!', 'yop_poll' ).$message_delimiter;
				}
				else {
					$newinput['singular_answer_result_votes_number_label']			= $default_options['singular_answer_result_votes_number_label'];
					$errors									.= __( 'Option "Poll Answer Result Votes Number Singular Label" Not Updated! The field is empty!', 'yop_poll' ).$message_delimiter;
				}
			}
			else {
				$newinput['singular_answer_result_votes_number_label']				=  $default_options['singular_answer_result_votes_number_label'];
				$errors										.= __( 'Option "Poll Answer Result Votes Number Singular Label" Not Updated!', 'yop_poll' ).$message_delimiter;	
			}
			
			//plural_answer_result_votes_number_label
			if ( isset( $input['plural_answer_result_votes_number_label'] ) ) {
				if ( '' !=  $input['singular_answer_result_votes_number_label'] ) {
					$newinput['plural_answer_result_votes_number_label']			= trim( $input['plural_answer_result_votes_number_label'] );
					$updated								.= __( 'Option "Poll Answer Result Votes Number Plural Label" Updated!', 'yop_poll' ).$message_delimiter;
				}
				else {
					$newinput['plural_answer_result_votes_number_label']			= $default_options['plural_answer_result_votes_number_label'];
					$errors									.= __( 'Option "Poll Answer Result Votes Number Plural Label" Not Updated! The field is empty!', 'yop_poll' ).$message_delimiter;
				}
			}
			else {
				$newinput['plural_answer_result_votes_number_label']				=  $default_options['plural_answer_result_votes_number_label'];
				$errors										.= __( 'Option "Poll Answer Result Votes Number Plural Label" Not Updated!', 'yop_poll' ).$message_delimiter;	
			}

			//vote_button_label
			if ( isset( $input['vote_button_label'] ) ) {
				if ( '' !=  $input['vote_button_label'] ) {
					$newinput['vote_button_label']			= trim( $input['vote_button_label'] );
					$updated								.= __( 'Option "Vote Button Label" Updated!', 'yop_poll' ).$message_delimiter;
				}
				else {
					$newinput['vote_button_label']			= $default_options['vote_button_label'];
					$errors									.= __( 'Option "Vote Button Label" Not Updated! The field is empty!', 'yop_poll' ).$message_delimiter;
				}
			}
			else {
				$newinput['vote_button_label']				=  $default_options['vote_button_label'];
				$errors										.= __( 'Option "Vote Button Label" Not Updated!', 'yop_poll' ).$message_delimiter;	
			}


			//view_results_link
			if ( isset( $input['view_results_link'] ) ) {
				if ( in_array( $input['view_results_link'], array('yes', 'no' ) ) ) {
					$newinput['view_results_link']						=  trim( $input['view_results_link'] );
					$updated											.= __( 'Option "View Results Link" Updated!', 'yop_poll' ).$message_delimiter;

					if ( 'yes' == $input['view_results_link'] ) {
						//view_results_link_label	
						if ( isset( $input['view_results_link_label'] ) ) {
							if ( '' !=  $input['view_results_link_label'] ) {
								$newinput['view_results_link_label']	= trim( $input['view_results_link_label'] );
								$updated								.= __( 'Option "View Results Link Label" Updated!', 'yop_poll' ).$message_delimiter;
							}
							else {
								$newinput['view_results_link_label']	= $default_options['view_results_link_label'];
								$errors									.= __( 'Option "View Results Link Label" Not Updated! The field is empty!', 'yop_poll' ).$message_delimiter;
							}
						}
						else {
							$newinput['view_results_link_label']		=  $default_options['view_results_link_label'];
							$errors										.= __( 'Option "View Results Link Label" Not Updated!', 'yop_poll' ).$message_delimiter;	
						}
					}
				} 
				else {
					$newinput['view_results_link']						=  $default_options['view_results_link'];
					$errors												.= __( 'Option "View Results Link" Not Updated! Please choose between \'yes\' or \'no\'', 'yop_poll' ).$message_delimiter;
				}
			}
			else {
				$newinput['view_results_link']							= $default_options['view_results_link'];
				$errors													.= __( 'Option "View Results Link" Not Updated!', 'yop_poll' ).$message_delimiter;
			}

			//view_back_to_vote_link
			if ( isset( $input['view_back_to_vote_link'] ) ) {
				if ( in_array( $input['view_back_to_vote_link'], array('yes', 'no' ) ) ) {
					$newinput['view_back_to_vote_link']						=  trim( $input['view_back_to_vote_link'] );
					$updated											.= __( 'Option "View Back To Vote Link" Updated!', 'yop_poll' ).$message_delimiter;

					if ( 'yes' == $input['view_back_to_vote_link'] ) {
						//view_results_link_label	
						if ( isset( $input['view_back_to_vote_link_label'] ) ) {
							if ( '' !=  $input['view_back_to_vote_link_label'] ) {
								$newinput['view_back_to_vote_link_label']	= trim( $input['view_back_to_vote_link_label'] );
								$updated								.= __( 'Option "View Back to Vote Link Label" Updated!', 'yop_poll' ).$message_delimiter;
							}
							else {
								$newinput['view_back_to_vote_link_label']	= $default_options['view_back_to_vote_link_label'];
								$errors									.= __( 'Option "View Back to Vote Link Label" Not Updated! The field is empty!', 'yop_poll' ).$message_delimiter;
							}
						}
						else {
							$newinput['view_back_to_vote_link_label']		=  $default_options['view_back_to_vote_link_label'];
							$errors										.= __( 'Option "View Back to Vote Link Label" Not Updated!', 'yop_poll' ).$message_delimiter;	
						}
					}
				} 
				else {
					$newinput['view_back_to_vote_link']						=  $default_options['view_back_to_vote_link'];
					$errors												.= __( 'Option "View Back to Vote Link" Not Updated! Please choose between \'yes\' or \'no\'', 'yop_poll' ).$message_delimiter;
				}
			}
			else {
				$newinput['view_back_to_vote_link']							= $default_options['view_back_to_vote_link'];
				$errors													.= __( 'Option "View Back to Vote Link" Not Updated!', 'yop_poll' ).$message_delimiter;
			}

			//view_total_votes
			if ( isset( $input['view_total_votes'] ) ) {
				if ( in_array( $input['view_total_votes'], array('yes', 'no' ) ) ) {
					$newinput['view_total_votes']					= trim( $input['view_total_votes'] );
					$updated										.= __( 'Option "View Total Votes" Updated!', 'yop_poll' ).$message_delimiter;

					//view_total_votes
					if( 'yes' == $input['view_total_votes'] ) {
						if ( isset( $input['view_total_votes_label'] ) ) {
							if ( stripos( $input['view_total_votes_label'], '%POLL-TOTAL-VOTES%' ) === false ) {
								$newinput['view_total_votes_label']	= $default_options['view_total_votes_label'];
								$errors 							.= __('You must use %POLL-TOTAL-VOTES% to define your Total Votes label!', 'yop_poll' ).$message_delimiter;	
							}
							else {
								$newinput['view_total_votes_label']	= trim( $input['view_total_votes_label'] );
								$updated							.= __( 'Option "View Total Votes Label" Updated!', 'yop_poll' ).$message_delimiter;
							}
						}
						else {
							$newinput['view_total_votes_label']	= $default_options['view_total_votes_label'];
							$errors 								.= __('Option "Total Votes Label" Not Updated!', 'yop_poll' ).$message_delimiter;
						}
					}
				}
				else {
					$newinput['view_total_votes']					= $default_options['view_total_votes'];
					$errors											.= __( 'Option "View Total Votes" Not Updated! Please choose between \'yes\' or \'no\'', 'yop_poll' ).$message_delimiter;
				}
			}
			else {
				$newinput['view_total_votes']						= $default_options['view_total_votes'];
				$errors												.= __( 'Option "View Total Votes" Not Updated!', 'yop_poll' ).$message_delimiter;	
			}

			//view_total_voters
			if ( isset( $input['view_total_voters'] ) ) {
				if ( in_array( $input['view_total_voters'], array('yes', 'no' ) ) ) {
					$newinput['view_total_voters']					= trim( $input['view_total_voters'] );
					$updated										.= __( 'Option "View Total Voters" Updated!', 'yop_poll' ).$message_delimiter;

					//view_total_voters
					if( 'yes' == $input['view_total_voters'] ) {
						if ( isset( $input['view_total_voters_label'] ) ) {
							if ( stripos( $input['view_total_voters_label'], '%POLL-TOTAL-VOTERS%' ) === false ) {
								$newinput['view_total_voters_label']	= $default_options['view_total_voters_label'];
								$errors 							.= __('You must use %POLL-TOTAL-VOTERS% to define your Total Voters label!', 'yop_poll' ).$message_delimiter;
							}
							else {
								$newinput['view_total_voters_label']	= trim( $input['view_total_voters_label'] );
								$updated							.= __( 'Option "View Total Voters Label" Updated!', 'yop_poll' ).$message_delimiter;	
							}
						}
						else {
							$newinput['view_total_voters_label']	= $default_options['view_total_voters_label'];
							$errors 								.= __('Option "Total Voters Label" Not Updated!', 'yop_poll' ).$message_delimiter;
						}
					}
				}
				else {
					$newinput['view_total_voters']					= $default_options['view_total_voters'];
					$errors											.= __( 'Option "View Total Voters" Not Updated! Please choose between \'yes\' or \'no\'', 'yop_poll' ).$message_delimiter;
				}
			}
			else {
				$newinput['view_total_voters']						= $default_options['view_total_voters'];
				$errors												.= __( 'Option "View Total Voters" Not Updated!', 'yop_poll' ).$message_delimiter;	
			}

			//vote_permisions
			if ( isset( $input['vote_permisions'] ) ) {
				if ( in_array( $input['vote_permisions'], array('quest-only', 'registered-only', 'guest-registered' ) ) ) {
					$newinput['vote_permisions']	= trim( $input['vote_permisions'] );
					$updated						.= __( 'Option "Vote Permisions" Updated!', 'yop_poll' ).$message_delimiter;
				}
				else {
					$newinput['vote_permisions']	= $default_options['vote_permisions'];
					$errors							.= __( 'Option "Vote Permisions" Not Updated! Please choose between \'Quest Only\', \'Registered Only\', \'Guest & Registered Users\'', 'yop_poll' ).$message_delimiter;
				}
			}
			else {
				$newinput['vote_permisions']	= $default_options['vote_permisions'];
				$errors							.= __( 'Option "Vote Permisions" Not Updated!', 'yop_poll' ).$message_delimiter;	
			}

			//blocking_voters
			if ( isset( $input['blocking_voters'] ) ) {
				if ( in_array( $input['blocking_voters'], array('dont-block', 'cookie', 'ip', 'username', 'cookie-ip' ) ) ) {
					$newinput['blocking_voters']							=  trim( $input['blocking_voters'] );
					$updated												.= __( 'Option "Blocking Voters" Updated!', 'yop_poll' ).$message_delimiter;

					if ( 'dont-block' != $newinput['blocking_voters'] ) {
						//blocking_voters_interval_value
						if ( isset( $input['blocking_voters_interval_value'] ) ) {
							if ( ctype_digit( $input['blocking_voters_interval_value'] ) ) {
								$newinput['blocking_voters_interval_value']	=  trim( $input['blocking_voters_interval_value'] );
								$updated									.= __( 'Option "Blocking Voters Interval Value" Updated!', 'yop_poll' ).$message_delimiter;
							}
							else {
								$newinput['blocking_voters_interval_value']	= $default_options['blocking_voters_interval_value'];
								$errors										.= __( 'Option "Blocking Voters Interval Value" Not Updated! Please fill in a number!', 'yop_poll' ).$message_delimiter;
							}
						}
						else {
							$newinput['blocking_voters_interval_value']		= $default_options['blocking_voters_interval_value'];
							$errors											.= __( 'Option "Blocking Voters Interval Value" Not Updated!', 'yop_poll' ).$message_delimiter;
						}

						//blocking_voters_interval_unit
						if ( isset( $input['blocking_voters_interval_unit'] ) ) {
							if ( in_array( $input['blocking_voters_interval_unit'], array('seconds', 'minutes', 'hours', 'days' ) ) ) {
								$newinput['blocking_voters_interval_unit']	=  trim( $input['blocking_voters_interval_unit'] );
								$updated									.= __( 'Option "Blocking Voters Interval Unit" Updated!', 'yop_poll' ).$message_delimiter;
							}
							else {
								$newinput['blocking_voters_interval_unit']	=  $default_options['blocking_voters_interval_unit'];
								$errors											.= __( 'Option "Blocking Voters Interval Unit" Not Updated! Please choose between \'Seconds\', \'Minutes\', \'Hours\' or \'Days\'', 'yop_poll' ).$message_delimiter;
							}
						}
						else {
							$newinput['blocking_voters_interval_unit']		= $default_options['blocking_voters_interval_unit'];
							$errors											.= __( 'Option "Blocking Voters Interval Unit" Not Updated!', 'yop_poll' ).$message_delimiter;
						}	
					}
				}
				else {
					$newinput['blocking_voters']							= $default_options['blocking_voters'];
					$errors													.= __( 'Option "Blocking Voters" Not Updated! Please choose between: \'Don`t Block\', \'Cookie\', \'Ip\', \'Username\', \'Cookie and Ip\'', 'yop_poll' ).$message_delimiter;
				}
			}
			else {
				$newinput['blocking_voters']								= $default_options['blocking_voters'];
				$errors														.= __( 'Option "Blocking Voters" Not Updated!', 'yop_poll' ).$message_delimiter;
			}
			
			//percentages_decimals
			if ( isset( $input['percentages_decimals'] ) ) {
				if ( ctype_digit( $input['percentages_decimals'] ) ) {
					$newinput['percentages_decimals']						=  trim( $input['percentages_decimals'] );
					$updated											.= __( 'Option "Percentages Decimals" Updated!', 'yop_poll' ).$message_delimiter;
				} 
				else {
					$newinput['percentages_decimals']						=  $default_options['percentages_decimals'];
					$errors												.= __( 'Option "Percentages Decimals" Not Updated! Please fill in a number!', 'yop_poll' ).$message_delimiter;
				}
			}
			else {
				$newinput['percentages_decimals']							= $default_options['percentages_decimals'];
				$errors													.= __( 'Option "Percentages Decimals" Not Updated!', 'yop_poll' ).$message_delimiter;
			}

			//view_poll_archive_link
			if ( isset( $input['view_poll_archive_link'] ) ) {
				if ( in_array( $input['view_poll_archive_link'], array('yes', 'no' ) ) ) {
					$newinput['view_poll_archive_link']						=  trim( $input['view_poll_archive_link'] );
					$updated											.= __( 'Option "View Poll Archive Link" Updated!', 'yop_poll' ).$message_delimiter;

					if ( 'yes' == $input['view_poll_archive_link'] ) {
						//view_results_link_label	
						if ( isset( $input['view_poll_archive_link_label'] ) ) {
							if ( '' !=  $input['view_poll_archive_link_label'] ) {
								$newinput['view_poll_archive_link_label']	= trim( $input['view_poll_archive_link_label'] );
								$updated								.= __( 'Option "View Poll Archive Link Label" Updated!', 'yop_poll' ).$message_delimiter;
							}
							else {
								$newinput['view_poll_archive_link_label']	= $default_options['view_poll_archive_link_label'];
								$errors									.= __( 'Option "View Poll Archive Link Label" Not Updated! The field is empty!', 'yop_poll' ).$message_delimiter;
							}
						}
						else {
							$newinput['view_poll_archive_link_label']		=  $default_options['view_poll_archive_link_label'];
							$errors										.= __( 'Option "View Poll Archive Link Label" Not Updated!', 'yop_poll' ).$message_delimiter;	
						}

						if ( isset( $input['poll_archive_url'] ) ) {
							if ( '' !=  $input['poll_archive_url'] ) {
								$newinput['poll_archive_url']			= trim( $input['poll_archive_url'] );
								$updated								.= __( 'Option "Poll Archive URL" Updated!', 'yop_poll' ).$message_delimiter;
							}
							else {
								$newinput['poll_archive_url']			= $default_options['poll_archive_url'];
								$errors									.= __( 'Option "Poll Archive URL" Not Updated! The field is empty!', 'yop_poll' ).$message_delimiter;
							}
						}
						else {
							$newinput['poll_archive_url']				=  $default_options['poll_archive_url'];
							$errors										.= __( 'Option "Poll Archive URL" Not Updated!', 'yop_poll' ).$message_delimiter;	
						}
					}
				} 
				else {
					$newinput['view_poll_archive_link']						=  $default_options['view_poll_archive_link'];
					$errors												.= __( 'Option "View Poll Archive Link" Not Updated! Please choose between \'yes\' or \'no\'', 'yop_poll' ).$message_delimiter;
				}
			}
			else {
				$newinput['view_poll_archive_link']							= $default_options['view_poll_archive_link'];
				$errors													.= __( 'Option "View Poll Archive Link" Not Updated!', 'yop_poll' ).$message_delimiter;
			}

			//show_in_archive
			if ( isset( $input['show_in_archive'] ) ) {
				if ( in_array( $input['show_in_archive'], array('yes', 'no' ) ) ) {
					$newinput['show_in_archive']						=  trim( $input['show_in_archive'] );
					$updated											.= __( 'Option "Show Poll in Arhive', 'yop_poll' ).$message_delimiter;

					if ( 'yes' == $input['show_in_archive'] ) {
						//archive_order	
						if ( isset( $input['archive_order'] ) ) {
							if ( ctype_digit( $input['archive_order'] ) ) {
								$newinput['archive_order']				= trim( $input['archive_order'] );
								$updated								.= __( 'Option "Archive Order" Updated!', 'yop_poll' ).$message_delimiter;
							}
							else {
								$newinput['archive_order']				= $default_options['archive_order'];
								$errors									.= __( 'Option "Archive Order" Not Updated! Please fill in a number!', 'yop_poll' ).$message_delimiter;
							}
						}
						else {
							$newinput['view_poll_archive_link_label']		=  $default_options['view_poll_archive_link_label'];
							$errors										.= __( 'Option "View Poll Archive Link Label" Not Updated!', 'yop_poll' ).$message_delimiter;	
						}
					}
				} 
				else {
					$newinput['show_in_archive']						=  $default_options['show_in_archive'];
					$errors												.= __( 'Option "Show Poll in Archive" Not Updated! Please choose between \'yes\' or \'no\'', 'yop_poll' ).$message_delimiter;
				}
			}
			else {
				$newinput['show_in_archive']							= $default_options['show_in_archive'];
				$errors													.= __( 'Option "Show Poll in Archive" Not Updated!', 'yop_poll' ).$message_delimiter;
			}

			//archive_polls_per_page
			if ( isset( $input['archive_polls_per_page'] ) ) {
				if ( ctype_digit( $input['archive_polls_per_page'] ) ) {
					$newinput['archive_polls_per_page']						=  trim( $input['archive_polls_per_page'] );
					$updated											.= __( 'Option "Archive Polls Per Page', 'yop_poll' ).$message_delimiter;
				} 
				else {
					$newinput['archive_polls_per_page']						=  $default_options['archive_polls_per_page'];
					$errors												.= __( 'Option "Archive Polls Per Page" Not Updated! Please fill in a number!', 'yop_poll' ).$message_delimiter;
				}
			}
			else {
				$newinput['archive_polls_per_page']							= $default_options['archive_polls_per_page'];
				$errors													.= __( 'Option "Archive Polls Per Page" Not Updated!', 'yop_poll' ).$message_delimiter;
			}

			if( '' != $errors )
				add_settings_error( 'general', 'yop-poll-errors', $errors, 'error' );
			if( '' != $updated )
				add_settings_error( 'general', 'yop-poll-updates', $updated, 'updated' );

			return $newinput;
		}

		public function view_yop_poll_options() {
			require_once( ABSPATH . '/wp-admin/options-head.php' );
			global $page;
			$default_options	= get_option( 'yop_poll_options', array() );
		?>
		<div class="wrap">
			<div class="icon32 icon32-yop-poll"><br></div>
			<h2><?php _e( 'Yop Poll Options', 'yop_poll' ); ?></h2>
			<div id="message"></div>
			<br/>

			<form action="options.php" method="post">
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<?php settings_fields('yop_poll_options'); ?>
						<div class="meta-box-sortables ui-sortable" id="normal-sortables">
							<div class="postbox" id="yop-poll-advanced-options-div">
								<div title="Click to toggle" class="handlediv"><br /></div>
								<h3 class="hndle"><span><?php _e( 'Answers options', 'yop_poll' ); ?></span></h3>
								<div class="inside">
									<table cellspacing="0" class="links-table">
									<tbody>
									<tr>
										<th>
											<?php _e( 'Allow other answers ', 'yop_poll' ); ?>:
										</th>
										<td>
											<label for="yop-poll-allow-other-answers-no"><input id="yop-poll-allow-other-answers-no" <?php echo 'no' == $default_options['allow_other_answers'] ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[allow_other_answers]" value="no" /> <?php _e( 'No', 'yop_poll' ); ?></label>
											<label for="yop-poll-allow-other-answers-yes"><input id="yop-poll-allow-other-answers-yes" <?php echo 'yes' == $default_options['allow_other_answers']  ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[allow_other_answers]" value="yes" /> <?php _e( 'Yes', 'yop_poll' ); ?></label>
										</td>
									</tr>
									<tr id="yop-poll-other-answers-label-div" style="<?php echo 'no' == $default_options['allow_other_answers'] ? 'display: none;' : '';  ?>">
										<th>
											<?php _e( 'Other Answer Label', 'yop_poll' ); ?>:
										</th>
										<td>
											<input id="yop-poll-other-answers-label" type="text" name="yop_poll_options[other_answers_label]" value="<?php echo isset( $other_answer[0]['answer'] ) ? esc_html( stripslashes(  $other_answer[0]['answer'] ) ) : $default_options['other_answers_label'] ?>" />	
											<input type="hidden" name="yop_poll_options[other_answers_id]" value="<?php echo isset( $other_answer[0]['id'] ) ? $other_answer[0]['id'] : '' ?>" />															
										</td>
									</tr>
									<tr id="yop-poll-display-other-answers-values-div" style="<?php echo 'no' == $default_options['allow_other_answers'] ? 'display: none;' : '';  ?>">
										<th>
											<?php _e( 'Display Other Answers Values', 'yop_poll' ); ?>:
										</th>
										<td>
											<label for="yop-poll-display-other-answers-values-no"><input id="yop-poll-display-other-answers-values-no" <?php echo 'no' == $default_options['display_other_answers_values'] ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[display_other_answers_values]" value="no" /> <?php _e( 'No', 'yop_poll' ); ?></label>
											<label for="yop-poll-display-other-answers-values-yes"><input id="yop-poll-display-other-answers-values-yes" <?php echo 'yes' == $default_options['display_other_answers_values']  ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[display_other_answers_values]" value="yes" /> <?php _e( 'Yes', 'yop_poll' ); ?></label>															
										</td>
									</tr>
									<tr>
										<th>
											<?php _e( 'Allow Multiple Answers ', 'yop_poll' ); ?>:
										</th>
										<td>
											<label for="yop-poll-allow-multiple-answers-no"><input id="yop-poll-allow-multiple-answers-no"  <?php echo $default_options['allow_multiple_answers'] == 'no' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[allow_multiple_answers]" value="no" /> <?php _e( 'No', 'yop_poll' ); ?></label>
											<label for="yop-poll-allow-multiple-answers-yes"><input id="yop-poll-allow-multiple-answers-yes" <?php echo $default_options['allow_multiple_answers'] == 'yes' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[allow_multiple_answers]" value="yes" /> <?php _e( 'Yes', 'yop_poll' ); ?></label>

										</td>
									</tr>
									<tr id="yop-poll-allow-multiple-answers-div" style="<?php echo $default_options['allow_multiple_answers'] == 'no' ? 'display: none;' : '';  ?>">
									<th>
										<?php _e( 'Number of allowed answers', 'yop_poll' ); ?>:
									</th>
									<td>
								<input id="yop-poll-allow-multiple-answers-number" type="text" name="yop_poll_options[allow_multiple_answers_number]" value="<?php echo $default_options['allow_multiple_answers_number']; ?>" />															</div>
								</td>
								</tr>
								</tbody>
								</table>
							</div>
						</div>
						<div class="postbox" id="yop-poll-advanced-options-div">
							<div title="Click to toggle" class="handlediv"><br /></div>
							<h3 class="hndle"><span><?php _e( 'Display Options', 'yop_poll' ); ?></span></h3>
							<div class="inside">
								<table cellspacing="0"class="links-table">
								<tbody>
									<tr>
										<th>
											<?php _e( 'Display Answers ', 'yop_poll' ); ?>:
										</th>
										<td>
											<label for="yop-poll-display-answers-vertical"><input id="yop-poll-display-answers-vertical" <?php echo $default_options['display_answers'] == 'vertical' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[display_answers]" value="vertical" /> <?php _e( 'Vertical', 'yop_poll' ); ?></label>
											<label for="yop-poll-display-answers-orizontal"><input id="yop-poll-display-answers-orizontal" <?php echo $default_options['display_answers'] == 'orizontal' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[display_answers]" value="orizontal" /> <?php _e( 'Orizontal', 'yop_poll' ); ?></label>
											<label for="yop-poll-display-answers-tabulated"><input id="yop-poll-display-answers-tabulated" <?php echo $default_options['display_answers'] == 'tabulated' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[display_answers]" value="tabulated" /> <?php _e( 'Tabulated', 'yop_poll' ); ?></label> 
										</td>
									</tr>
									<tr id="yop-poll-display-answers-tabulated-div" style="<?php echo $default_options['display_answers'] != 'tabulated' ? 'display: none;' : '';  ?>">
										<th>
											<?php _e( 'Columns', 'yop_poll' ); ?>:
										</th>
										<td>
											<input id="yop-poll-display-answers-tabulated-cols" type="text" name="yop_poll_options[display_answers_tabulated_cols]" value="<?php echo $default_options['display_answers_tabulated_cols']; ?>" /> 
										</td>
									</tr>
									<tr>
										<th>
											<?php _e( 'Display Results ', 'yop_poll' ); ?>:
										</th>
										<td>
											<label for="yop-poll-display-results-vertical"><input id="yop-poll-display-results-vertical" <?php echo $default_options['display_results'] == 'vertical' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[display_results]" value="vertical" > <?php _e( 'Vertical', 'yop_poll' ); ?></label>
											<label for="yop-poll-display-results-orizontal"><input id="yop-poll-display-results-orizontal" <?php echo $default_options['display_results'] == 'orizontal' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[display_results]" value="orizontal" > <?php _e( 'Orizontal', 'yop_poll' ); ?></label>
											<label for="yop-poll-display-results-tabulated"><input id="yop-poll-display-results-tabulated" <?php echo $default_options['display_results'] == 'tabulated' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[display_results]" value="tabulated" > <?php _e( 'Tabulated', 'yop_poll' ); ?></label> 
										</td>
									</tr>
									<tr id="yop-poll-display-results-tabulated-div" style="<?php echo $default_options['display_results'] != 'tabulated' ? 'display: none;' : '';  ?>">
										<th>
											<?php _e( 'Columns', 'yop_poll' ); ?>:
										</th>
										<td>
											<input id="yop-poll-display-results-tabulated-cols" type="text" name="yop_poll_options[display_results_tabulated_cols]" value="<?php echo $default_options['display_results_tabulated_cols']; ?>" /> 
										</td>
									</tr>
								</tbody>
								</table>
							</div>
						</div>
						<div class="postbox" id="yop-poll-advanced-options-div">
							<div title="Click to toggle" class="handlediv"><br /></div>
							<h3 class="hndle"><span><?php _e( 'Poll Bar Style', 'yop_poll' ); ?></span></h3>
							<div class="inside">
								<table cellspacing="0" class="links-table">
									<tbody>
										<tr>
											<th>
												<?php _e( 'Use Template Result Bar', 'yop_poll' ); ?>:
											</th>
											<td>
												<label for="yop-poll-use-template-bar-no"><input id="yop-poll-use-template-bar-no" <?php echo 'no' == $default_options['use_template_bar'] ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[use_template_bar]" value="no" /> <?php _e( 'No', 'yop_poll' ); ?></label>
												<label for="yop-poll-use-template-bar-yes"><input id="yop-poll-use-template-bar-yes" <?php echo 'yes' == $default_options['use_template_bar']  ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[use_template_bar]" value="yes" /> <?php _e( 'Yes', 'yop_poll' ); ?></label>
											</td>
										</tr>
										<tr class="yop-poll-custom-result-bar-table" style="<?php echo $default_options['use_template_bar'] == 'yes' ? 'display: none;' : '';  ?>">
											<th>
												<label for="yop-poll-bar-background"><?php echo $yop_poll_add_new_config['text_poll_bar_style']['poll_bar_style_background_label']; ?></label>
											</th>
											<td>
												#<input class="yop-small-input" id="yop-poll-bar-background" value="<?php echo $default_options['bar_background']; ?>" onblur="yop_poll_update_bar_style('#yop-poll-bar-preview', 'background-color', '#' + this.value)" type="text" name="yop_poll_options[bar_background]" />
											</td>
										</tr>
										<tr class="yop-poll-custom-result-bar-table" style="<?php echo $default_options['use_template_bar'] == 'yes' ? 'display: none;' : '';  ?>">
											<th>
												<label for="yop-poll-bar-height"><?php echo $yop_poll_add_new_config['text_poll_bar_style']['poll_bar_style_height_label']; ?></label>
											</th>
											<td>
												<input class="yop-small-input" id="yop-poll-bar-height" value="<?php echo $default_options['bar_height']; ?>" onblur="yop_poll_update_bar_style('#yop-poll-bar-preview', 'height', this.value + 'px')" type="text" name="yop_poll_options[bar_height]" /> px
											</td>
										</tr>
										<tr class="yop-poll-custom-result-bar-table" style="<?php echo $default_options['use_template_bar'] == 'yes' ? 'display: none;' : '';  ?>">
											<th>
												<label for="yop-poll-bar-border-color"><?php echo $yop_poll_add_new_config['text_poll_bar_style']['poll_bar_style_border_color_label']; ?></label>
											</th>
											<td>
												#<input class="yop-small-input" id="yop-poll-bar-border-color" value="<?php echo $default_options['bar_border_color']; ?>" onblur="yop_poll_update_bar_style( '#yop-poll-bar-preview', 'border-color', '#' + this.value )" type="text" name="yop_poll_options[bar_border_color]" />
											</td>
										</tr>
										<tr class="yop-poll-custom-result-bar-table" style="<?php echo $default_options['use_template_bar'] == 'yes' ? 'display: none;' : '';  ?>">
											<th>
												<label for="yop-poll-bar-border-width"><?php echo $yop_poll_add_new_config['text_poll_bar_style']['poll_bar_style_border_width_label']; ?></label>
											</th>
											<td>
												<input class="yop-small-input" id="yop-poll-bar-border-width" value="<?php echo $default_options['bar_border_width']; ?>" onblur="yop_poll_update_bar_style('#yop-poll-bar-preview', 'border-width', this.value + 'px')" type="text" name="yop_poll_options[bar_border_width]" /> px
											</td>
										</tr>
										<tr class="yop-poll-custom-result-bar-table" style="<?php echo $default_options['use_template_bar'] == 'yes' ? 'display: none;' : '';  ?>">
											<th>
												<label for="yop-poll-bar-border-style"><?php echo $yop_poll_add_new_config['text_poll_bar_style']['poll_bar_style_border_style_label']; ?></label>
											</th>
											<td>
												<select id="yop-poll-bar-border-style" onchange="yop_poll_update_bar_style('#yop-poll-bar-preview', 'border-style', this.value)" name="yop_poll_options[bar_border_style]">
													<option <?php print 'solid' == $default_options['bar_border_style'] ? 'selected="selected"' : ''; ?> value="solid">Solid</option>
													<option <?php print 'dashed' == $default_options['bar_border_style'] ? 'selected="selected"' : ''; ?> value="dashed">Dashed</option>
													<option <?php print 'dotted' == $default_options['bar_border_style'] ? 'selected="selected"' : ''; ?> value="dotted">Dotted</option>
												</select>
											</td>
										</tr>
										<tr class="yop-poll-custom-result-bar-table" style="<?php echo $default_options['use_template_bar'] == 'yes' ? 'display: none;' : '';  ?>"> 
											<th>
												<label><?php echo $yop_poll_add_new_config['text_poll_bar_style']['poll_bar_preview_label']; ?></label>  
											</th>
											<td>
												<div id="yop-poll-bar-preview"; style="width: 100px; height: <?php echo $default_options['bar_height']; ?>px; background-color:#<?php echo $default_options['bar_background']; ?>; border-style: <?php echo $default_options['bar_border_style']; ?>; border-width: <?php echo $default_options['bar_border_width']; ?>px; border-color: #<?php echo $default_options['bar_border_color']; ?>;"></div>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="postbox" id="yop-poll-advanced-options-div">
							<div title="Click to toggle" class="handlediv"><br /></div>
							<h3 class="hndle"><span><?php _e( 'Sorting Answers &amp; Results', 'yop_poll' ); ?></span></h3>
							<div class="inside">
								<table cellspacing="0" class="links-table">
									<tbody>
										<tr>
											<th><?php _e( 'Sort Answers', 'yop_poll' ); ?>:</th>
											<td valign="top">
												<label for="yop_poll_sorting_answers_exact"><input id="yop_poll_sorting_answers_exact" <?php echo $default_options['sorting_answers'] == 'exact' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_answers]" value="exact" > <?php _e( 'Exact Order', 'yop_poll' ); ?></label>
												<label for="yop_poll_sorting_answers_alphabetical"><input id="yop_poll_sorting_answers_alphabetical" <?php echo $default_options['sorting_answers'] == 'alphabetical' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_answers]" value="alphabetical" > <?php _e( 'Alphabetical Order', 'yop_poll' ); ?></label>
												<label for="yop_poll_sorting_answers_random"><input id="yop_poll_sorting_answers_random" <?php echo $default_options['sorting_answers'] == 'random' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_answers]" value="random" > <?php _e( 'Random Order', 'yop_poll' ); ?></label>
												<label for="yop_poll_sorting_answers_votes"><input id="yop_poll_sorting_answers_votes" <?php echo $default_options['sorting_answers'] == 'votes' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_answers]" value="votes" > <?php _e( 'Number of Votes', 'yop_poll' ); ?></label> 
											</td>	
										</tr>
										<tr>
											<th>
												<?php _e( 'Sort Answers Rule', 'yop_poll' ); ?>:
											</th>
											<td>
												<label for="yop_poll_sorting_answers_asc"><input id="yop_poll_sorting_answers_asc" <?php echo $default_options['sorting_answers_direction'] == 'asc' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_answers_direction]" value="asc" > <?php _e( 'Ascending', 'yop_poll' ); ?></label>
												<label for="yop_poll_sorting_answers_desc"><input id="yop_poll_sorting_answers_desc" <?php echo $default_options['sorting_answers_direction'] == 'desc' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_answers_direction]" value="desc" > <?php _e( 'Descending', 'yop_poll' ); ?> </label>
											</td>
										</tr>
										<tr>
											<th><?php _e( 'Sorting Results', 'yop_poll' ); ?>:</th>
											<td valign="top">
												<label for="yop_poll_sorting_results_exact"><input id="yop_poll_sorting_results_exact" <?php echo $default_options['sorting_results'] == 'exact' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_results]" value="exact" > <?php _e( 'Exact Order', 'yop_poll' ); ?></label>
												<label for="yop_poll_sorting_results_alphabetical"><input id="yop_poll_sorting_results_alphabetical" <?php echo $default_options['sorting_results'] == 'alphabetical' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_results]" value="alphabetical" > <?php _e( 'Alphabetical Order', 'yop_poll' ); ?></label>
												<label for="yop_poll_sorting_results_random"><input id="yop_poll_sorting_results_random" <?php echo $default_options['sorting_results'] == 'random' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_results]" value="random" > <?php _e( 'Random Order', 'yop_poll' ); ?></label>
												<label for="yop_poll_sorting_results_votes"><input id="yop_poll_sorting_results_votes" <?php echo $default_options['sorting_results'] == 'votes' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_results]" value="votes" > <?php _e( 'Number of Votes', 'yop_poll' ); ?></label> 
											</td>	
										</tr>
										<tr>
											<th>
												<?php _e( 'Sorting Results Rule', 'yop_poll' ); ?>:
											</th>
											<td>
												<label for="yop_poll_sorting_results_asc"><input id="yop_poll_sorting_results_asc" <?php echo $default_options['sorting_results_direction'] == 'asc' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_results_direction]" value="asc" > <?php _e( 'Ascending', 'yop_poll' ); ?></label>
												<label for="yop_poll_sorting_results_desc"><input id="yop_poll_sorting_results_desc" <?php echo $default_options['sorting_results_direction'] == 'desc' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_results_direction]" value="desc" > <?php _e( 'Descending', 'yop_poll' ); ?></label> 
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="postbox" id="yop-poll-advanced-options-div">
							<div title="Click to toggle" class="handlediv"><br /></div>
							<h3 class="hndle"><span><?php _e( 'Poll Start/End Date', 'yop_poll' ); ?></h3>
							<div class="inside">
								<table cellspacing="0" class="links-table">
									<tbody>
										<tr>
											<th>
												<label for="yop-poll-start-date-input"><?php _e( 'Start Date', 'yop_poll' ); ?>:</label>
											</th>
											<td>
												<input id="yop-poll-start-date-input" type="text" name="yop_poll_options[start_date]" value="<?php echo '' == $default_options['start_date'] ? current_time('mysql', 1) : $default_options['start_date']; ?>" />
											</td>
										</tr>	
										<tr>
											<th>
												<label for="yop-poll-end-date-input"><?php _e( 'End Date ', 'yop_poll' ); ?>:</label>
											</th>
											<td>
												<input style="<?php echo 'yes' == $default_options['never_expire'] ? 'display: none;' : '';  ?>" <?php echo 'yes' == $default_options['never_expire'] ? 'disabled="disabled"' : '';  ?> id="yop-poll-end-date-input" type="text" name="yop_poll_options[end_date]" value="<?php echo '' == $default_options['end_date'] ? '' : $default_options['end_date']; ?>" />
												<label for="yop-poll-never-expire"><input type="checkbox" <?php echo $default_options['never_expire'] == 'yes' ? 'checked="checked"' : '';  ?>  id="yop-poll-never-expire" name="yop_poll_options[never_expire]" value="yes" /> Do NOT Expire This Poll</label> 
											</td>
										</tr>											
									</tbody>
								</table>
							</div>
						</div>
						<div class="postbox" id="yop-poll-advanced-options-div">
							<div title="Click to toggle" class="handlediv"><br /></div>
							<h3 class="hndle"><span><?php _e( 'View Results Options', 'yop_poll' ); ?></span></h3>
							<div class="inside">
								<table cellspacing="0" class="links-table">
									<tbody>
										<tr>
											<th>
												<?php _e( 'View Results', 'yop_poll' ); ?>:
											</th>
											<td>
												<label for="yop-poll-view-results-before-vote"><input class="yop-poll-view-results-hide-custom" <?php echo 'before' == $default_options['view_results'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-results-before-vote" type="radio" value="before" name="yop_poll_options[view_results]" /> <?php _e( 'Before Vote' , 'yop_poll'); ?></label>
												<label for="yop-poll-view-results-after-vote"><input class="yop-poll-view-results-hide-custom" <?php echo 'after' == $default_options['view_results'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-results-after-vote" type="radio" value="after" name="yop_poll_options[view_results]" /> <?php _e( 'After Vote' , 'yop_poll'); ?></label>
												<label for="yop-poll-view-results-after-poll-end-date"><input class="yop-poll-view-results-hide-custom" <?php echo 'after-poll-end-date' == $default_options['view_results'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-results-after-poll-end-date" type="radio" value="after-poll-end-date" name="yop_poll_options[view_results]" /> <?php _e( 'After Poll End Date' , 'yop_poll'); ?></label>
												<label for="yop-poll-view-results-never"><input class="yop-poll-view-results-hide-custom" <?php echo 'never' == $default_options['view_results'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-results-never" type="radio" value="never" name="yop_poll_options[view_results]" /> <?php _e( 'Never' , 'yop_poll'); ?></label>
												<label for="yop-poll-view-results-custom"><input class="yop-poll-view-results-show-custom" <?php echo 'custom-date' == $default_options['view_results'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-results-custom" type="radio" value="custom-date" name="yop_poll_options[view_results]" /> <?php _e( 'Custom Date' , 'yop_poll'); ?></label>
												<div id="yop-poll-display-view-results-div" style="<?php echo 'custom-date' != $default_options['view_results'] ? 'display: none;' : '';  ?>">
													<label for="yop-poll-view-results-start-date"><?php _e( 'Results display date (after this date the user can see the results)', 'yop_poll' ); ?>:</label> <input id="yop-poll-view-results-start-date" type="text" name="yop_poll_options[view_results_start_date]" value="<?php echo $default_options['view_results_start_date']; ?>" > 
												</div>
											</td>
										</tr>
										<tr>
											<th>
												<?php _e( 'Results Display', 'yop_poll' ); ?>:
											</th>
											<td>
												<label for="yop-poll-view-results-votes-number"><input id="yop-poll-view-results-votes-number" <?php echo 'votes-number' == $default_options['view_results_type'] ? 'checked="checked"' : '';  ?> type="radio" value="votes-number" name="yop_poll_options[view_results_type]" /> <?php _e( 'By Votes Number' , 'yop_poll'); ?></label>
												<label for="yop-poll-view-results-percentages"><input id="yop-poll-view-results-percentages" <?php echo 'percentages' == $default_options['view_results_type'] ? 'checked="checked"' : '';  ?> type="radio" value="percentages" name="yop_poll_options[view_results_type]" /> <?php _e( 'Percentages' , 'yop_poll'); ?></label>
												<label for="yop-poll-view-results-votes-number-and-percentages"><input id="yop-poll-view-results-votes-number-and-percentages" <?php echo 'votes-number-and-percentages' == $default_options['view_results_type'] ? 'checked="checked"' : '';  ?> type="radio" value="votes-number-and-percentages" name="yop_poll_options[view_results_type]" /> <?php _e( 'by Votes Number and Percentages' , 'yop_poll'); ?></label>
											</td>
										</tr>
										<tr>
											<th>
												<?php _e( 'Poll Answer Result Label', 'yop_poll'); ?>:
											</th>
											<td>
												<input id="yop-poll-answer-result-label" type="text" name="yop_poll_options[answer_result_label]" value="<?php echo esc_html( stripslashes( $default_options['answer_result_label'] ) ); ?>" /> <small><i>Use %POLL-ANSWER-RESULT-PERCENTAGES% for showing answer percentages and  %POLL-ANSWER-RESULT-VOTES% for showing answer number of votes</i></small>
											</td>	
										</tr>
										<tr>
											<th>
												<?php _e( 'Poll Answer Result Votes Number Label', 'yop_poll' ); ?>:
											</th>
											<td>
												<?php _e( 'Singular', 'yop_poll' ); ?>
												<input id="yop-poll-singular-answer-result-votes-number-label" type="text" name="yop_poll_options[singular_answer_result_votes_number_label]" value="<?php echo esc_html( stripslashes( $default_options['singular_answer_result_votes_number_label'] ) ); ?>" />
												<?php _e( 'Plural', 'yop_poll' ); ?>
												<input id="yop-poll-plural-answer-result-votes-number-label" type="text" name="yop_poll_options[plural_answer_result_votes_number_label]" value="<?php echo esc_html( stripslashes( $default_options['plural_answer_result_votes_number_label'] ) ); ?>" />
												
											</td>
										</tr>
										<tr>
											<th>
												<?php _e( 'Vote Button Label', 'yop_poll' ); ?>:
											</th>
											<td>
												<input id="yop-poll-vote-button-label" type="text" name="yop_poll_options[vote_button_label]" value="<?php echo esc_html( stripslashes( $default_options['vote_button_label'] ) ); ?>" />
											</td>
										</tr>
										<tr>
											<th>
												<?php _e( 'View Results Link', 'yop_poll' ); ?>:
											</th>
											<td>
												<label for="yop-poll-view-results-link-yes"><input <?php echo 'yes' == $default_options['view_results_link'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-results-link-yes" type="radio" value="yes" name="yop_poll_options[view_results_link]" /> <?php _e( 'Yes' , 'yop_poll'); ?></label>
												<label for="yop-poll-view-results-link-no"><input <?php echo 'no' == $default_options['view_results_link'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-results-link-no" type="radio" value="no" name="yop_poll_options[view_results_link]" /> <?php _e( 'No' , 'yop_poll'); ?></label>
											</td>
										</tr>
										<tr id="yop-poll-view-results-link-div" style="<?php echo 'yes' != $default_options['view_results_link'] ? 'display: none;' : '';  ?>">
											<th>
												<?php _e( 'View Results Link Label', 'yop_poll' ); ?>:
											</th>
											<td>
												<input id="yop-poll-view-results-link-label" type="text" name="yop_poll_options[view_results_link_label]" value="<?php echo esc_html( stripslashes( $default_options['view_results_link_label'] ) ); ?>" />
											</td>
										</tr>
										<tr>
											<th>
												<?php _e( 'View Back To Vote Link ', 'yop_poll' ); ?>:
											</th>
											<td>
												<label for="yop-poll-view-back-to-vote-link-yes"><input <?php echo 'yes' == $default_options['view_back_to_vote_link'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-back-to-vote-link-yes" type="radio" value="yes" name="yop_poll_options[view_back_to_vote_link]" /> <?php _e( 'Yes' , 'yop_poll'); ?></label>
												<label for="yop-poll-view-back-to-vote-link-no"><input <?php echo 'no' == $default_options['view_back_to_vote_link'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-back-to-vote-link-no" type="radio" value="no" name="yop_poll_options[view_back_to_vote_link]" /><?php _e( 'No' , 'yop_poll'); ?></label>
											</td>
										</tr>
										<tr id="yop-poll-view-back-to-vote-link-div" style="<?php echo 'yes' != $default_options['view_back_to_vote_link'] ? 'display: none;' : '';  ?>">
											<th>
												<?php _e( 'View Back To Vote Link Label', 'yop_poll' ); ?>:
											</th>
											<td>
												<input id="yop-poll-view-back-to-vote-link-label" type="text" name="yop_poll_options[view_back_to_vote_link_label]" value="<?php echo esc_html( stripslashes( $default_options['view_back_to_vote_link_label'] ) ); ?>" />
											</td>
										</tr>
										<tr>
											<th>
												<?php _e( 'View Total Votes ', 'yop_poll' ); ?>:
											</th>
											<td>
												<label for="yop-poll-view-total-votes-yes"><input <?php echo 'yes' == $default_options['view_total_votes'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-total-votes-yes" type="radio" value="yes" name="yop_poll_options[view_total_votes]" /> <?php _e( 'Yes' , 'yop_poll'); ?></label>
												<label for="yop-poll-view-total-votes-no"><input <?php echo 'no' == $default_options['view_total_votes'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-total-votes-no" type="radio" value="no" name="yop_poll_options[view_total_votes]" /> <?php _e( 'No' , 'yop_poll'); ?></label>
											</td>
										</tr>
										<tr id="yop-poll-view-total-votes-div" style="<?php echo 'yes' != $default_options['view_total_votes'] ? 'display: none;' : '';  ?>">
											<th>
												<?php _e( 'View Total Votes Label', 'yop_poll' ); ?>:
											</th>
											<td>
												<input id="yop-poll-view-total-votes-label" type="text" name="yop_poll_options[view_total_votes_label]" value="<?php echo esc_html( stripslashes( $default_options['view_total_votes_label'] ) ); ?>" />
											</td>
										</tr>
										<tr>
											<th>
												<?php _e( 'View Total Voters ', 'yop_poll' ); ?>:
											</th>
											<td>
												<label for="yop-poll-view-total-voters-yes"><input <?php echo 'yes' == $default_options['view_total_voters'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-total-voters-yes" type="radio" value="yes" name="yop_poll_options[view_total_voters]" /> <?php _e( 'Yes' , 'yop_poll'); ?></label>
												<label for="yop-poll-view-total-voters-no"><input <?php echo 'no' == $default_options['view_total_voters'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-total-voters-no" type="radio" value="no" name="yop_poll_options[view_total_voters]" /> <?php _e( 'No' , 'yop_poll'); ?></label>
											</td>
										</tr>
										<tr id="yop-poll-view-total-voters-div" style="<?php echo 'yes' != $default_options['view_total_voters'] ? 'display: none;' : '';  ?>">
											<th>
												<?php _e( 'View Total Voters Label', 'yop_poll' ); ?>:
											</th>
											<td>
												<input id="yop-poll-view-total-voters-label" type="text" name="yop_poll_options[view_total_voters_label]" value="<?php echo esc_html( stripslashes( $default_options['view_total_voters_label'] ) ); ?>" />
											</td>
										</tr>
										<tr>
											<th>
												<label for="yop-poll-page-url"><?php _e( 'Poll Page Url ', 'yop_poll' ); ?>:</label>
											</th>
											<td>
												<input id="yop-poll-page-url" type="text" name="yop_poll_options[poll_page_url]" value="<?php echo esc_html( stripslashes( $default_options['poll_page_url'] ) ); ?>" />
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="postbox" id="yop-poll-advanced-options-div">
							<div title="Click to toggle" class="handlediv"><br /></div>
							<h3 class="hndle"><span><?php _e( 'Other Options', 'yop_poll' ); ?></span></h3>
							<div class="inside">
								<table cellspacing="0" class="links-table">
									<tbody>
										<tr>
											<th>
												<?php _e( 'Vote Permisions ', 'yop_poll' ); ?>:
											</th>
											<td>
												<label for="yop-poll-vote-permisions-quest-only"><input id="yop-poll-vote-permisions-quest-only" <?php echo 'quest-only' == $default_options['vote_permisions'] ? 'checked="checked"' : '';  ?> type="radio" value="quest-only" name="yop_poll_options[vote_permisions]" /> <?php _e( 'Guest Only' , 'yop_poll'); ?></label>
												<label for="yop-poll-vote-permisions-registered-only"><input id="yop-poll-vote-permisions-registered-only" <?php echo 'registered-only' == $default_options['vote_permisions'] ? 'checked="checked"' : '';  ?> type="radio" value="registered-only" name="yop_poll_options[vote_permisions]" /> <?php _e( 'Registered Users Only' , 'yop_poll'); ?></label>
												<label for="yop-poll-vote-permisions-guest-registered"><input id="yop-poll-vote-permisions-guest-registered" <?php echo 'guest-registered' == $default_options['vote_permisions'] ? 'checked="checked"' : '';  ?> type="radio" value="guest-registered" name="yop_poll_options[vote_permisions]" /> <?php _e( 'Guest &amp; Registered Users' , 'yop_poll'); ?></label>
											</td>
										</tr>
										<tr>
											<th>
												<?php _e( 'Blocking Voters ', 'yop_poll' ); ?>:
											</th>
											<td>
												<label for="yop-poll-blocking-voters-dont-block"><input class="yop-poll-blocking-voters-hide-interval" <?php echo 'dont-block' == $default_options['blocking_voters'] ? 'checked="checked"' : '';  ?> id="yop-poll-blocking-voters-dont-block" type="radio" value="dont-block" name="yop_poll_options[blocking_voters]" /> <?php _e( 'Dont`t Block' , 'yop_poll'); ?></label>
												<label for="yop-poll-blocking-voters-cookie"><input class="yop-poll-blocking-voters-show-interval" <?php echo 'cookie' == $default_options['blocking_voters'] ? 'checked="checked"' : '';  ?> id="yop-poll-blocking-voters-cookie" type="radio" value="cookie" name="yop_poll_options[blocking_voters]" /> <?php _e( 'By Cookie' , 'yop_poll'); ?></label>
												<label for="yop-poll-blocking-voters-ip"><input class="yop-poll-blocking-voters-show-interval" <?php echo 'ip' == $default_options['blocking_voters'] ? 'checked="checked"' : '';  ?> id="yop-poll-blocking-voters-ip" type="radio" value="ip" name="yop_poll_options[blocking_voters]" /> <?php _e( 'By Ip' , 'yop_poll'); ?></label>
												<label for="yop-poll-blocking-voters-username"><input class="yop-poll-blocking-voters-show-interval" <?php echo 'username' == $default_options['blocking_voters'] ? 'checked="checked"' : '';  ?> id="yop-poll-blocking-voters-username" type="radio" value="username" name="yop_poll_options[blocking_voters]" /> <?php _e( 'By Username' , 'yop_poll'); ?></label>
												<label for="yop-poll-blocking-voters-cookie-ip"><input class="yop-poll-blocking-voters-show-interval" <?php echo 'cookie-ip' == $default_options['blocking_voters'] ? 'checked="checked"' : '';  ?> id="yop-poll-blocking-voters-cookie-ip" type="radio" value="cookie-ip" name="yop_poll_options[blocking_voters]" /> <?php _e( 'By Cookie &amp; Ip' , 'yop_poll'); ?></label>
											</td>
										</tr>
										<tr id="yop-poll-blocking-voters-interval-div" style="<?php echo 'dont-block' == $default_options['blocking_voters'] ? 'display: none;' : '';  ?>">
											<th>
												<?php _e( 'Blocking voters interval', 'yop_poll' ); ?>:
											</th>
											<td>
												<input type="text" name="yop_poll_options[blocking_voters_interval_value]" id="yop-poll-blocking-voters-interval-value" value="<?php echo $default_options['blocking_voters_interval_value'];  ?>" />
												<select id="yop-poll-blocking-voters-interval-unit" name="yop_poll_options[blocking_voters_interval_unit]">
													<option <?php echo 'seconds' == $default_options['blocking_voters_interval_unit'] ? 'selected="selected"' : '';  ?> value="seconds"><?php _e( 'Seconds', 'yop_poll' ); ?></option>
													<option <?php echo 'minutes' == $default_options['blocking_voters_interval_unit'] ? 'selected="selected"' : '';  ?> value="minutes"><?php _e( 'Minutes', 'yop_poll' ); ?></option>
													<option <?php echo 'hours' == $default_options['blocking_voters_interval_unit'] ? 'selected="selected"' : '';  ?> value="hours"><?php _e( 'Hours', 'yop_poll' ); ?></option>
													<option <?php echo 'days' == $default_options['blocking_voters_interval_unit'] ? 'selected="selected"' : '';  ?> value="days"><?php _e( 'Days', 'yop_poll' ); ?></option>
												</select> 
											</td>
										</tr>
										<tr>
											<th>
												<?php _e( 'Percentages Decimals', 'yop_poll' ); ?>:
											</th>
											<td>
												<input id="yop-poll-percentages-decimals" type="text" name="yop_poll_options[percentages_decimals]" value="<?php echo esc_html( stripslashes( $default_options['percentages_decimals'] ) ); ?>" />
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="postbox" id="yop-poll-advanced-options-div">
							<div title="Click to toggle" class="handlediv"><br /></div>
							<h3 class="hndle"><span><?php _e( 'Archive Options', 'yop_poll' ); ?></span></h3>
							<div class="inside">
								<table cellspacing="0" class="links-table">
									<tbody>
									<tr>
										<th>
											<?php _e( 'View Poll Archive Link ', 'yop_poll' ); ?>:
										</th>
										<td>
											<input <?php checked( 'yes', $default_options['view_poll_archive_link'] ); ?> id="yop-poll-view-poll-archive-link-yes" type="radio" value="yes" name="yop_poll_options[view_poll_archive_link]" /><label for="yop-poll-view-poll-archive-link-yes"><?php _e( 'Yes' , 'yop_poll'); ?></label>
											<input <?php checked( 'no', $default_options['view_poll_archive_link'] ); ?> id="yop-poll-view-poll-archive-link-no" type="radio" value="no" name="yop_poll_options[view_poll_archive_link]" /><label for="yop-poll-view-poll-archive-link-no"><?php _e( 'No' , 'yop_poll'); ?></label>
										</td>
									</tr>
									<tr id="yop-poll-view-poll-archive-link-div" style="<?php echo 'yes' != $default_options['view_poll_archive_link'] ? 'display: none;' : '';  ?>">
										<th>
											<?php _e( 'View Poll Archive Link Label', 'yop_poll' ); ?>:
										</th>
										<td>
											<input id="yop-poll-view-poll-archive-link-label" type="text" name="yop_poll_options[view_poll_archive_link_label]" value="<?php echo esc_html( stripslashes( $default_options['view_poll_archive_link_label'] ) ); ?>" />
										</td>
									</tr>
									<tr id="yop-poll-view-poll-archive-link-div" style="<?php echo 'yes' != $default_options['view_poll_archive_link'] ? 'display: none;' : '';  ?>">
										<th>
											<?php _e( 'Poll Archive Url', 'yop_poll' ); ?>:
										</th>
										<td>
											<input id="yop-poll-poll-archive-url" type="text" name="yop_poll_options[poll_archive_url]" value="<?php echo esc_html( stripslashes( $default_options['poll_archive_url'] ) ); ?>" />
										</td>
									</tr>
									<tr>
										<th>
											<?php _e( 'Show Poll In Archive ', 'yop_poll' ); ?>:
										</th>
										<td>
											<label for="yop-poll-show-in-archive-yes"><input <?php checked( 'yes', $default_options['show_in_archive'] ); ?> id="yop-poll-show-in-archive-yes" type="radio" value="yes" name="yop_poll_options[show_in_archive]" /> <?php _e( 'Yes' , 'yop_poll'); ?></label>
											<label for="yop-poll-show-in-archive-no"><input <?php checked( 'no', $default_options['show_in_archive'] ); ?> id="yop-poll-show-in-archive-no" type="radio" value="no" name="yop_poll_options[show_in_archive]" /> <?php _e( 'No' , 'yop_poll'); ?></label>
										</td>
									</tr>
									<tr id="yop-poll-show-in-archive-div" style="<?php echo 'yes' != $default_options['show_in_archive'] ? 'display: none;' : '';  ?>">
										<th>
											<?php _e( 'Archive Order', 'yop_poll' ); ?>:
										</th>
										<td>
											<input id="yop-poll-show-in-archive-order" type="text" name="yop_poll_options[archive_order]" value="<?php echo $default_options['archive_order']; ?>" />
										</td>
									</tr>
									<tr>
										<th>
											<?php _e( 'Archive Polls Per Page', 'yop_poll' ); ?>:
										</th>
										<td>
											<input id="yop-poll-archive-polls-per-page" type="text" name="yop_poll_options[archive_polls_per_page]" value="<?php echo $default_options['archive_polls_per_page']; ?>" />
										</td>
									</tr>
								</table>	
							</div>
						</div>
					</div>
					<input name="Submit" class="button-primary" type="submit" value="<?php _e('Save Changes'); ?>" />
				</div>

				<div class="postbox-container" id="postbox-container-1">
					<div class="meta-box-sortables ui-sortable" id="side-sortables">
						<div class="postbox " id="linksubmitdiv">
							<div title="Click to toggle" class="handlediv">
								<br />
							</div>
							<h3 class="hndle"><span><?php _e( 'Save Changes', 'yop_poll'); ?></span></h3>
							<div class="inside">
								<div id="submitlink" class="submitbox">
									<div id="major-publishing-actions">


										<div id="publishing-action">
											<input name="Submit" class="button-primary" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
										</div>
										<div class="clear"></div>
									</div>
									<div class="clear"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		</form>
		</div>

		<?php
		}

		public function view_add_edit_new_poll( ) {
			global $yop_poll_add_new_config, $action;
			require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
			$yop_poll_model	= new Yop_Poll_Model( );
			$page_name		= __( 'Add New Yop Poll', 'yop_poll' );
			$action_type	= 'add-new';
			$poll_id		= '';
			$default_options		= get_option( 'yop_poll_options', array() );
			if ( 'edit' == $action ) {
				$poll_id			= ( isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0 );
				$yop_poll_model			= new Yop_Poll_Model( $poll_id );
				$answers				= Yop_Poll_Model::get_poll_answers( $poll_id );
				$other_answer			= Yop_Poll_Model::get_poll_answers( $poll_id, array('other') );
				$custom_fields			= Yop_Poll_Model::get_poll_customfields( $poll_id );
				$page_name				= __( 'Edit Poll', 'yop_poll' );
				$action_type			= 'edit';
				$poll_default_options	= get_yop_poll_meta( $poll_id, 'options', true );
				foreach( $default_options as $option_name => $option_value ) {
					if( isset( $poll_default_options[ $option_name ]) ) {
						$default_options[ $option_name ] = $poll_default_options[ $option_name ];
					}
				}
			}
			$current_poll			= $yop_poll_model -> get_current_poll();
			$answers_number			= $yop_poll_add_new_config['default_number_of_answers'];
			$customfields_number	= $yop_poll_add_new_config['default_number_of_customfields'];
		?>
		<div class="wrap">
			<div class="icon32 icon32-yop-poll"><br></div><h2><?php print $page_name; ?><?php if( 'edit' == $action ): ?><a class="add-new-h2" href="<?php echo esc_url( add_query_arg( array( 'page' => 'yop-polls-add-new', 'action' => false, 'id' => false ) ) ); ?>" ><?php _e( 'Add New', 'yop_poll' ); ?></a><?php endif; ?></h2>
			<div id="message"></div>
			<form method="post" name="yop_poll_edit_add_new_form" id="yop-poll-edit-add-new-form">
			<?php wp_nonce_field( 'yop-poll-edit-add-new' ); ?>
			<span <?php if ( 'edit' != $action ) { ?>style="display:none;"<?php } ?>>
				Shortcode: <input id="yop_poll_shortcode" type="text" value='[yop_poll id="<?php echo $current_poll['id']; ?>"]' readonly="readonly">
			</span>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="stuffbox" id="yop-poll-namediv">
							<h3><label for="yop-poll-name"><?php _e( 'Poll Name', 'yop_poll' ); ?></label></h3>
							<div class="inside">
								<input type="text" id="yop-poll-name" value="<?php echo esc_html( stripslashes( $current_poll['name'] ) ); ?>" tabindex="1" name="yop_poll_name" size="30" />
								<p><?php _e( 'Example: Test Poll', 'yop_poll' ); ?></p>
							</div>
						</div>
						<div class="stuffbox" id="yop-poll-questiondiv">
							<h3><label for="yop-poll-question"><?php _e( 'Question', 'yop_poll' ); ?></label></h3>
							<div class="inside">
								<input type="text" id="yop-poll-question" value="<?php echo esc_html( stripslashes( $current_poll['question'] ) ); ?>" tabindex="1" name="yop_poll_question" size="30" />
								<p><?php _e( 'Example: How is my plugin?', 'yop_poll' ); ?></p>
							</div>
						</div>
						<div class="stuffbox" id="yop-poll-answersdiv">
							<h3><span><?php _e( 'Answers', 'yop_poll' ); ?></span></h3>
							<div class="inside">
								<table cellspacing="0" class="links-table" id='yop-poll-answer-table'>
									<tbody>
										<?php 
											for( $answer_id = 1; $answer_id < $answers_number; $answer_id++ ) { 
												if( isset( $answers[ $answer_id - 1 ] ) ) {
													$answer_options = get_yop_poll_answer_meta( $answers[ $answer_id - 1 ]['id'], 'options' );
												}
											?>
											<tr class="yop_poll_tr_answer" id="yop_poll_tr_answer<?php echo $answer_id ?>">
												<th scope="row"><label class="yop_poll_answer_label" for="yop-poll-answer<?php echo $answer_id ?>"><?php echo $yop_poll_add_new_config['text_answer']; ?> <?php echo $answer_id ?></label></th>
												<td>
													<input type="hidden" value="<?php echo isset( $answers[ $answer_id - 1 ]['id'] )? $answers[ $answer_id - 1 ]['id'] : ''; ?>" name="yop_poll_answer_ids[answer<?php echo $answer_id ?>]" />
													<input type="text" value="<?php echo isset( $answers[ $answer_id - 1 ]['answer'] )? esc_html( stripslashes( $answers[ $answer_id - 1 ]['answer'] ) ) : ''; ?>" id="yop-poll-answer<?php echo $answer_id ?>" name="yop_poll_answer[answer<?php echo $answer_id ?>]" /></td>
												<td align="right"><input type="button" value="<?php echo $yop_poll_add_new_config['text_customize_answer']; ?>" onclick="yop_poll_toogle_customize_answer('#yop-poll-answer-table', <?php echo $answer_id ?>); return false;" class="button" /> <input onclick="yop_poll_remove_answer('#yop-poll-answer-table', <?php echo $answer_id ?>); return false;" type="button" value="<?php echo $yop_poll_add_new_config['text_remove_answer'];?>" class="button" /></td>
											</tr>
											<tr class="yop_poll_tr_customize_answer" id="yop_poll_tr_customize_answer<?php echo $answer_id ?>" style="display:none;"> 
												<td colspan="3">
													<table cellspacing="0" width="100%"><tbody>
															<tr>
																<th>
																	<?php echo $yop_poll_add_new_config['text_poll_bar_style']['use_template_bar_label']; ?>:
																</th>
																<td>
																	<input onclick="jQuery('#yop-poll-answer-use-template-bar-table-<?php echo $answer_id ?>').show();" id="yop-poll-answer-use-template-bar-no-<?php echo $answer_id ?>" <?php echo checked( 'no', isset ( $answer_options[0]['use_template_bar'] ) ? $answer_options[0]['use_template_bar'] : $default_options['use_template_bar'] ); ?> type="radio" name="yop_poll_answer_options[answer<?php echo $answer_id ?>][use_template_bar]" value="no" /> <label for="yop-poll-answer-use-template-bar-no-<?php echo $answer_id ?>"><?php _e( 'No', 'yop_poll' ); ?></label>&nbsp;|&nbsp;
																	<input onclick="jQuery('#yop-poll-answer-use-template-bar-table-<?php echo $answer_id ?>').hide();" id="yop-poll-answer-use-template-bar-yes-<?php echo $answer_id ?>" <?php echo checked( 'yes', isset ( $answer_options[0]['use_template_bar'] ) ? $answer_options[0]['use_template_bar'] : $default_options['use_template_bar'] ); ?> type="radio" name="yop_poll_answer_options[answer<?php echo $answer_id ?>][use_template_bar]" value="yes" /> <label for="yop-poll-answer-use-template-bar-yes-<?php echo $answer_id ?>"><?php _e( 'Yes', 'yop_poll' ); ?></label>
																</td>
															</tr>
														</tbody>
													</table>
													<table cellspacing="0" width="100%" id="yop-poll-answer-use-template-bar-table-<?php echo $answer_id ?>" style="<?php echo ( 'yes' == ( isset ( $answer_options[0]['use_template_bar'] ) ? $answer_options[0]['use_template_bar'] : $default_options['use_template_bar'] ) ? 'display: none;' : '' );  ?>">
														<tbody> 
															<tr> 
																<th>
																	<label><?php echo $yop_poll_add_new_config['text_poll_bar_style']['poll_bar_style_label']; ?></label>  
																</th> 
																<td>
																	<table cellspacing="0" style="margin-left:0px;" style="width:100%"><tbody>
																			<tr>
																				<th>
																					<label for="yop-poll-answer-option-bar-background-answer<?php echo $answer_id ?>"><?php echo $yop_poll_add_new_config['text_poll_bar_style']['poll_bar_style_background_label']; ?></label>
																				</th>
																				<td>
																					#<input id="yop-poll-answer-option-bar-background-answer<?php echo $answer_id ?>" value="<?php echo isset ( $answer_options[0]['bar_background'] ) ? $answer_options[0]['bar_background'] : $default_options['bar_background']; ?>" onblur="yop_poll_update_bar_style('#yop-poll-bar-preview<?php echo $answer_id ?>', 'background-color', '#' + this.value)" type="text" name="yop_poll_answer_options[answer<?php echo $answer_id ?>][bar_background]" />
																				</td>
																			</tr>
																			<tr>
																				<th>
																					<label for="yop-poll-answer-option-bar-height-answer<?php echo $answer_id ?>"><?php echo $yop_poll_add_new_config['text_poll_bar_style']['poll_bar_style_height_label']; ?></label>
																				</th>
																				<td>
																					<input id="yop-poll-answer-option-bar-height-answer<?php echo $answer_id ?>" value="<?php echo isset ( $answer_options[0]['bar_height'] ) ? $answer_options[0]['bar_height'] : $default_options['bar_height']; ?>" onblur="yop_poll_update_bar_style('#yop-poll-bar-preview<?php echo $answer_id ?>', 'height', this.value + 'px')" type="text" name="yop_poll_answer_options[answer<?php echo $answer_id ?>][bar_height]" /> px
																				</td>
																			</tr>
																			<tr>
																				<th>
																					<label for="yop-poll-answer-option-bar-border-color-answer<?php echo $answer_id ?>"><?php echo $yop_poll_add_new_config['text_poll_bar_style']['poll_bar_style_border_color_label']; ?></label>
																				</th>
																				<td>
																					#<input id="yop-poll-answer-option-bar-border-color-answer<?php echo $answer_id ?>" value="<?php echo isset ( $answer_options[0]['bar_border_color'] ) ? $answer_options[0]['bar_border_color'] : $default_options['bar_border_color']; ?>" onblur="yop_poll_update_bar_style( '#yop-poll-bar-preview<?php echo $answer_id ?>', 'border-color', '#' + this.value )" type="text" name="yop_poll_answer_options[answer<?php echo $answer_id ?>][bar_border_color]" />
																				</td>
																			</tr>
																			<tr>
																				<th>
																					<label for="yop-poll-answer-option-bar-border-width-answer<?php echo $answer_id ?>"><?php echo $yop_poll_add_new_config['text_poll_bar_style']['poll_bar_style_border_width_label']; ?></label>
																				</th>
																				<td>
																					<input id="yop-poll-answer-option-bar-border-width-answer<?php echo $answer_id ?>" value="<?php echo isset ( $answer_options[0]['bar_border_width'] ) ? $answer_options[0]['bar_border_width'] : $default_options['bar_border_width']; ?>" onblur="yop_poll_update_bar_style('#yop-poll-bar-preview<?php echo $answer_id ?>', 'border-width', this.value + 'px')" type="text" name="yop_poll_answer_options[answer<?php echo $answer_id ?>][bar_border_width]" /> px
																				</td>
																			</tr>
																			<tr>
																				<th>
																					<label for="yop-poll-answer-option-bar_border-style-answer<?php echo $answer_id ?>"><?php echo $yop_poll_add_new_config['text_poll_bar_style']['poll_bar_style_border_style_label']; ?></label>
																				</th>
																				<td>
																					<select id="yop-poll-answer-option-bar_border-style-answer<?php echo $answer_id ?>" onchange="yop_poll_update_bar_style('#yop-poll-bar-preview<?php echo $answer_id ?>', 'border-style', this.value)" name="yop_poll_answer_options[answer<?php echo $answer_id ?>][bar_border_style]">
																						<option <?php print ( 'solid' == ( isset ( $answer_options[0]['bar_border_style'] ) ? $answer_options[0]['bar_border_style'] : $default_options['bar_border_style'] ) ) ? 'selected="selected"' : ''; ?> value="solid">Solid</option>
																						<option <?php print ( 'dashed' == ( isset ( $answer_options[0]['bar_border_style'] ) ? $answer_options[0]['bar_border_style'] : $default_options['bar_border_style'] ) ) ? 'selected="selected"' : ''; ?> value="dashed">Dashed</option>
																						<option <?php print ( 'dotted' == ( isset ( $answer_options[0]['bar_border_style'] ) ? $answer_options[0]['bar_border_style'] : $default_options['bar_border_style'] ) ) ? 'selected="selected"' : ''; ?> value="dotted">Dotted</option>
																					</select>
																				</td>
																			</tr>
																		</tbody></table>
																</td>
															</tr> 
															<tr> 
																<th>
																	<label><?php echo $yop_poll_add_new_config['text_poll_bar_style']['poll_bar_preview_label']; ?></label>  
																</th>
																<td>
																	<div id="yop-poll-bar-preview<?php echo $answer_id ?>"; style="width: 100px; height: <?php echo isset ( $answer_options[0]['bar_height'] ) ? $answer_options[0]['bar_height'] : $default_options['bar_height']; ?>px; background-color:#<?php echo isset ( $answer_options[0]['bar_background'] ) ? $answer_options[0]['bar_background'] : $default_options['bar_background']; ?>; border-style: <?php echo isset ( $answer_options[0]['bar_border_style'] ) ? $answer_options[0]['bar_border_style'] : $default_options['bar_border_style']; ?>; border-width: <?php echo isset ( $answer_options[0]['bar_border_width'] ) ? $answer_options[0]['bar_border_width'] : $default_options['bar_border_width']; ?>px; border-color: #<?php echo isset ( $answer_options[0]['bar_border_color'] ) ? $answer_options[0]['bar_border_color'] : $default_options['bar_border_color']; ?>;"></div>
																</td>
															</tr> 
														</tbody> 
													</table> 
												</td>
											</tr>
											<?php } ?>
									</tbody>
								</table>
								<p id="yop-poll-add-answer-holder" style="display: block;">
									<button id="yop-poll-add-answer-button" class="button"><?php _e( 'Add New Answer', 'yop_poll' ) ?></button>
									<button id="yop-poll-answers-advanced-options-button" class="button"><?php _e( 'Answers Advanced Options', 'yop_poll' ); ?></button>
								</p>

								<table cellspacing="0" id="yop-poll-answers-advanced-options-div" style="display:none;" class="links-table">
								<tbody>
								<tr>
									<th>
										<?php _e( 'Allow other answers ', 'yop_poll' ); ?>:
									</th>
									<td>
										<label for="yop-poll-allow-other-answers-no"><input id="yop-poll-allow-other-answers-no" <?php echo 'no' == $default_options['allow_other_answers'] ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[allow_other_answers]" value="no" /> <?php _e( 'No', 'yop_poll' ); ?></label>
										<label for="yop-poll-allow-other-answers-yes"><input id="yop-poll-allow-other-answers-yes" <?php echo 'yes' == $default_options['allow_other_answers']  ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[allow_other_answers]" value="yes" /> <?php _e( 'Yes', 'yop_poll' ); ?></label>
									</td>
								</tr>
								<tr id="yop-poll-other-answers-label-div" style="<?php echo 'no' == $default_options['allow_other_answers'] ? 'display: none;' : '';  ?>">
									<th>
										<?php _e( 'Other Answer Label', 'yop_poll' ); ?>:
									</th>
									<td>
										<input id="yop-poll-other-answers-label" type="text" name="yop_poll_options[other_answers_label]" value="<?php echo isset( $other_answer[0]['answer'] ) ? esc_html( stripslashes( $other_answer[0]['answer'] ) ) : $default_options['other_answers_label'] ?>" />	
										<input type="hidden" name="yop_poll_options[other_answers_id]" value="<?php echo isset( $other_answer[0]['id'] ) ? $other_answer[0]['id'] : '' ?>" />															
									</td>
								</tr>
								<tr id="yop-poll-display-other-answers-values-div" style="<?php echo 'no' == $default_options['allow_other_answers'] ? 'display: none;' : '';  ?>">
									<th>
										<?php _e( 'Display Other Answers Values', 'yop_poll' ); ?>:
									</th>
									<td>
										<label for="yop-poll-display-other-answers-values-no"><input id="yop-poll-display-other-answers-values-no" <?php echo 'no' == $default_options['display_other_answers_values'] ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[display_other_answers_values]" value="no" /> <?php _e( 'No', 'yop_poll' ); ?></label>
										<label for="yop-poll-display-other-answers-values-yes"><input id="yop-poll-display-other-answers-values-yes" <?php echo 'yes' == $default_options['display_other_answers_values']  ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[display_other_answers_values]" value="yes" /> <?php _e( 'Yes', 'yop_poll' ); ?></label>															
									</td>
								</tr>
								<tr>
									<th>
										<?php _e( 'Allow Multiple Answers ', 'yop_poll' ); ?>:
									</th>
									<td>
										<label for="yop-poll-allow-multiple-answers-no"><input id="yop-poll-allow-multiple-answers-no"  <?php echo $default_options['allow_multiple_answers'] == 'no' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[allow_multiple_answers]" value="no" /> <?php _e( 'No', 'yop_poll' ); ?></label>
										<label for="yop-poll-allow-multiple-answers-yes"><input id="yop-poll-allow-multiple-answers-yes" <?php echo $default_options['allow_multiple_answers'] == 'yes' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[allow_multiple_answers]" value="yes" /> <?php _e( 'Yes', 'yop_poll' ); ?></label>

									</td>
								</tr>
								<tr id="yop-poll-allow-multiple-answers-div" style="<?php echo $default_options['allow_multiple_answers'] == 'no' ? 'display: none;' : '';  ?>">
								<th>
									<?php _e( 'Number of allowed answers', 'yop_poll' ); ?>:
								</th>
								<td>
							<input id="yop-poll-allow-multiple-answers-number" type="text" name="yop_poll_options[allow_multiple_answers_number]" value="<?php echo $default_options['allow_multiple_answers_number']; ?>" />															</div>
							</td>
							</tr>
							<tr>
								<th>
									<?php _e( 'Display Answers ', 'yop_poll' ); ?>:
								</th>
								<td>
									<label for="yop-poll-display-answers-vertical"><input id="yop-poll-display-answers-vertical" <?php echo $default_options['display_answers'] == 'vertical' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[display_answers]" value="vertical" /> <?php _e( 'Vertical', 'yop_poll' ); ?></label>
									<label for="yop-poll-display-answers-orizontal"><input id="yop-poll-display-answers-orizontal" <?php echo $default_options['display_answers'] == 'orizontal' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[display_answers]" value="orizontal" /> <?php _e( 'Orizontal', 'yop_poll' ); ?></label>
									<label for="yop-poll-display-answers-tabulated"><input id="yop-poll-display-answers-tabulated" <?php echo $default_options['display_answers'] == 'tabulated' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[display_answers]" value="tabulated" /> <?php _e( 'Tabulated', 'yop_poll' ); ?></label> 
								</td>
							</tr>
							<tr id="yop-poll-display-answers-tabulated-div" style="<?php echo $default_options['display_answers'] != 'tabulated' ? 'display: none;' : '';  ?>">
								<th>
									<?php _e( 'Columns', 'yop_poll' ); ?>:
								</th>
								<td>
									<input id="yop-poll-display-answers-tabulated-cols" type="text" name="yop_poll_options[display_answers_tabulated_cols]" value="<?php echo $default_options['display_answers_tabulated_cols']; ?>" /> 
								</td>
							</tr>
							<tr>
								<th>
									<?php _e( 'Display Results ', 'yop_poll' ); ?>:
								</th>
								<td>
									<label for="yop-poll-display-results-vertical"><input id="yop-poll-display-results-vertical" <?php echo $default_options['display_results'] == 'vertical' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[display_results]" value="vertical" > <?php _e( 'Vertical', 'yop_poll' ); ?></label>
									<label for="yop-poll-display-results-orizontal"><input id="yop-poll-display-results-orizontal" <?php echo $default_options['display_results'] == 'orizontal' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[display_results]" value="orizontal" > <?php _e( 'Orizontal', 'yop_poll' ); ?></label>
									<label for="yop-poll-display-results-tabulated"><input id="yop-poll-display-results-tabulated" <?php echo $default_options['display_results'] == 'tabulated' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[display_results]" value="tabulated" > <?php _e( 'Tabulated', 'yop_poll' ); ?></label> 
								</td>
							</tr>
							<tr id="yop-poll-display-results-tabulated-div" style="<?php echo $default_options['display_results'] != 'tabulated' ? 'display: none;' : '';  ?>">
								<th>
									<?php _e( 'Columns', 'yop_poll' ); ?>:
								</th>
								<td>
									<input id="yop-poll-display-results-tabulated-cols" type="text" name="yop_poll_options[display_results_tabulated_cols]" value="<?php echo $default_options['display_results_tabulated_cols']; ?>" /> 
								</td>
							</tr>
							<tr>
								<th>
									<?php _e( 'Use Template Result Bar', 'yop_poll' ); ?>:
								</th>
								<td>
									<label for="yop-poll-use-template-bar-no"><input id="yop-poll-use-template-bar-no" <?php echo 'no' == $default_options['use_template_bar'] ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[use_template_bar]" value="no" /> <?php _e( 'No', 'yop_poll' ); ?></label>
									<label for="yop-poll-use-template-bar-yes"><input id="yop-poll-use-template-bar-yes" <?php echo 'yes' == $default_options['use_template_bar']  ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[use_template_bar]" value="yes" /> <?php _e( 'Yes', 'yop_poll' ); ?></label>
								</td>
							</tr>
							<tr class="yop-poll-custom-result-bar-table" style="<?php echo $default_options['use_template_bar'] == 'yes' ? 'display: none;' : '';  ?>">
								<th>
									<label for="yop-poll-bar-background"><?php echo $yop_poll_add_new_config['text_poll_bar_style']['poll_bar_style_background_label']; ?></label>
								</th>
								<td>
									#<input class="yop-small-input" id="yop-poll-bar-background" value="<?php echo $default_options['bar_background']; ?>" onblur="yop_poll_update_bar_style('#yop-poll-bar-preview', 'background-color', '#' + this.value)" type="text" name="yop_poll_options[bar_background]" />
								</td>
							</tr>
							<tr class="yop-poll-custom-result-bar-table" style="<?php echo $default_options['use_template_bar'] == 'yes' ? 'display: none;' : '';  ?>">
								<th>
									<label for="yop-poll-bar-height"><?php echo $yop_poll_add_new_config['text_poll_bar_style']['poll_bar_style_height_label']; ?></label>
								</th>
								<td>
									<input class="yop-small-input" id="yop-poll-bar-height" value="<?php echo $default_options['bar_height']; ?>" onblur="yop_poll_update_bar_style('#yop-poll-bar-preview', 'height', this.value + 'px')" type="text" name="yop_poll_options[bar_height]" /> px
								</td>
							</tr>
							<tr class="yop-poll-custom-result-bar-table" style="<?php echo $default_options['use_template_bar'] == 'yes' ? 'display: none;' : '';  ?>">
								<th>
									<label for="yop-poll-bar-border-color"><?php echo $yop_poll_add_new_config['text_poll_bar_style']['poll_bar_style_border_color_label']; ?></label>
								</th>
								<td>
									#<input class="yop-small-input" id="yop-poll-bar-border-color" value="<?php echo $default_options['bar_border_color']; ?>" onblur="yop_poll_update_bar_style( '#yop-poll-bar-preview', 'border-color', '#' + this.value )" type="text" name="yop_poll_options[bar_border_color]" />
								</td>
							</tr>
							<tr class="yop-poll-custom-result-bar-table" style="<?php echo $default_options['use_template_bar'] == 'yes' ? 'display: none;' : '';  ?>">
								<th>
									<label for="yop-poll-bar-border-width"><?php echo $yop_poll_add_new_config['text_poll_bar_style']['poll_bar_style_border_width_label']; ?></label>
								</th>
								<td>
									<input class="yop-small-input" id="yop-poll-bar-border-width" value="<?php echo $default_options['bar_border_width']; ?>" onblur="yop_poll_update_bar_style('#yop-poll-bar-preview', 'border-width', this.value + 'px')" type="text" name="yop_poll_options[bar_border_width]" /> px
								</td>
							</tr>
							<tr class="yop-poll-custom-result-bar-table" style="<?php echo $default_options['use_template_bar'] == 'yes' ? 'display: none;' : '';  ?>">
								<th>
									<label for="yop-poll-bar-border-style"><?php echo $yop_poll_add_new_config['text_poll_bar_style']['poll_bar_style_border_style_label']; ?></label>
								</th>
								<td>
									<select id="yop-poll-bar-border-style" onchange="yop_poll_update_bar_style('#yop-poll-bar-preview', 'border-style', this.value)" name="yop_poll_options[bar_border_style]">
										<option <?php print 'solid' == $default_options['bar_border_style'] ? 'selected="selected"' : ''; ?> value="solid">Solid</option>
										<option <?php print 'dashed' == $default_options['bar_border_style'] ? 'selected="selected"' : ''; ?> value="dashed">Dashed</option>
										<option <?php print 'dotted' == $default_options['bar_border_style'] ? 'selected="selected"' : ''; ?> value="dotted">Dotted</option>
									</select>
								</td>
							</tr>
							<tr class="yop-poll-custom-result-bar-table" style="<?php echo $default_options['use_template_bar'] == 'yes' ? 'display: none;' : '';  ?>"> 
								<th>
									<label><?php echo $yop_poll_add_new_config['text_poll_bar_style']['poll_bar_preview_label']; ?></label>  
								</th>
								<td>
									<div id="yop-poll-bar-preview"; style="width: 100px; height: <?php echo $default_options['bar_height']; ?>px; background-color:#<?php echo $default_options['bar_background']; ?>; border-style: <?php echo $default_options['bar_border_style']; ?>; border-width: <?php echo $default_options['bar_border_width']; ?>px; border-color: #<?php echo $default_options['bar_border_color']; ?>;"></div>
								</td>
							</tr>
							<tr>
								<th><?php _e( 'Sort Answers', 'yop_poll' ); ?>:</th>
								<td valign="top">
									<label for="yop_poll_sorting_answers_exact"><input id="yop_poll_sorting_answers_exact" <?php echo $default_options['sorting_answers'] == 'exact' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_answers]" value="exact" > <?php _e( 'Exact Order', 'yop_poll' ); ?></label>
									<label for="yop_poll_sorting_answers_alphabetical"><input id="yop_poll_sorting_answers_alphabetical" <?php echo $default_options['sorting_answers'] == 'alphabetical' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_answers]" value="alphabetical" > <?php _e( 'Alphabetical Order', 'yop_poll' ); ?></label>
									<label for="yop_poll_sorting_answers_random"><input id="yop_poll_sorting_answers_random" <?php echo $default_options['sorting_answers'] == 'random' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_answers]" value="random" > <?php _e( 'Random Order', 'yop_poll' ); ?></label>
									<label for="yop_poll_sorting_answers_votes"><input id="yop_poll_sorting_answers_votes" <?php echo $default_options['sorting_answers'] == 'votes' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_answers]" value="votes" > <?php _e( 'Number of Votes', 'yop_poll' ); ?></label> 
								</td>	
							</tr>
							<tr>
								<th>
									<?php _e( 'Sort Answers Rule', 'yop_poll' ); ?>:
								</th>
								<td>
									<label for="yop_poll_sorting_answers_asc"><input id="yop_poll_sorting_answers_asc" <?php echo $default_options['sorting_answers_direction'] == 'asc' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_answers_direction]" value="asc" > <?php _e( 'Ascending', 'yop_poll' ); ?></label>
									<label for="yop_poll_sorting_answers_desc"><input id="yop_poll_sorting_answers_desc" <?php echo $default_options['sorting_answers_direction'] == 'desc' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_answers_direction]" value="desc" > <?php _e( 'Descending', 'yop_poll' ); ?> </label>
								</td>
							</tr>
							<tr>
								<th><?php _e( 'Sorting Results in', 'yop_poll' ); ?>:</th>
								<td valign="top">
									<label for="yop_poll_sorting_results_exact"><input id="yop_poll_sorting_results_exact" <?php echo $default_options['sorting_results'] == 'exact' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_results]" value="exact" > <?php _e( 'Exact Order', 'yop_poll' ); ?></label>
									<label for="yop_poll_sorting_results_alphabetical"><input id="yop_poll_sorting_results_alphabetical" <?php echo $default_options['sorting_results'] == 'alphabetical' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_results]" value="alphabetical" > <?php _e( 'Alphabetical Order', 'yop_poll' ); ?></label>
									<label for="yop_poll_sorting_results_random"><input id="yop_poll_sorting_results_random" <?php echo $default_options['sorting_results'] == 'random' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_results]" value="random" > <?php _e( 'Random Order', 'yop_poll' ); ?></label>
									<label for="yop_poll_sorting_results_votes"><input id="yop_poll_sorting_results_votes" <?php echo $default_options['sorting_results'] == 'votes' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_results]" value="votes" > <?php _e( 'Number of Votes', 'yop_poll' ); ?></label> 
								</td>	
							</tr>
							<tr>
								<th>
									<?php _e( 'Sorting Results Rule', 'yop_poll' ); ?>:
								</th>
								<td>
									<label for="yop_poll_sorting_results_asc"><input id="yop_poll_sorting_results_asc" <?php echo $default_options['sorting_results_direction'] == 'asc' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_results_direction]" value="asc" > <?php _e( 'Ascending', 'yop_poll' ); ?></label>
									<label for="yop_poll_sorting_results_desc"><input id="yop_poll_sorting_results_desc" <?php echo $default_options['sorting_results_direction'] == 'desc' ? 'checked="checked"' : '';  ?> type="radio" name="yop_poll_options[sorting_results_direction]" value="desc" > <?php _e( 'Descending', 'yop_poll' ); ?></label> 
								</td>
							</tr>
							</tbody>
							</table>

						</div>
					</div>
					<div class="stuffbox" id="yop-poll-customfieldsdiv">
						<h3><span><?php _e( 'Custom Text Fields', 'yop_poll' ); ?></span></h3>
						<div class="inside">
							<table cellspacing="0" class="links-table" id='yop-poll-customfields-table'>
								<tbody>	
									<?php 
										for( $custom_field_id = 1; $custom_field_id < $customfields_number; $custom_field_id++ ) {
											if( isset ( $custom_fields[ $custom_field_id - 1 ]['id'] ) ) {
											?>
											<tr class="yop_poll_tr_customfields" id="yop_poll_tr_customfield<?php echo $custom_field_id; ?>">
												<th scope="row"><label class="yop_poll_customfield_label" for="yop_poll_customfield<?php echo $custom_field_id; ?>"><?php echo $yop_poll_add_new_config['text_customfield'] ?> <?php echo $custom_field_id ?></label>
												</th>
												<td>
													<input type="hidden" value="<?php echo isset( $custom_fields[ $custom_field_id - 1 ]['id'] )? $custom_fields[ $custom_field_id - 1 ]['id'] : ''; ?>" name="yop_poll_customfield_ids[customfield<?php echo $custom_field_id ?>]" />
													<input type="text" value="<?php echo isset( $custom_fields[ $custom_field_id - 1 ]['custom_field'] )? $custom_fields[ $custom_field_id - 1 ]['custom_field'] : ''; ?>" id="yop-poll-customfield<?php echo $custom_field_id ?>" name="yop_poll_customfield[customfield<?php echo $custom_field_id ?>]" /> 
													<input value="yes" <?php if ( isset ($custom_fields[ $custom_field_id - 1 ]['required'] ) ) echo ('yes' == $custom_fields[ $custom_field_id - 1 ]['required'] )? 'checked="checked"' : ''; ?> id="yop-poll-customfield-required-<?php echo $custom_field_id ?>" type="checkbox" name="yop_poll_customfield_required[customfield<?php echo $custom_field_id ?>]" /> <label for="yop-poll-customfield-required-<?php echo $custom_field_id ?>"><?php echo $yop_poll_add_new_config['text_requiered_customfield'] ?></label>
												</td>
												<td align="right"><input onclick="yop_poll_remove_customfield( '#yop-poll-customfields-table', <?php echo $custom_field_id ?> ); return false;" type="button" value="<?php echo $yop_poll_add_new_config['text_remove_customfield']; ?>" class="button" /></td>
											</tr>
											<?php
											}
										} 
									?>
								</tbody>
							</table> 									
							<p id="yop-poll-add-customfield-holder" style="display: block;">
								<button id="yop-poll-add-customfield-button" class="button"><?php _e( 'Add New Custom Field', 'yop_poll' ) ?></button>
							</p>
						</div>
					</div>
					<div class="meta-box-sortables ui-sortable" id="normal-sortables">
						<div class="postbox" id="yop-poll-advanced-options-div">
							<div title="Click to toggle" class="handlediv"><br /></div>
							<h3 class="hndle"><span><?php _e( 'Poll Start/End Date', 'yop_poll' ); ?></h3>
							<div class="inside">
								<table cellspacing="0" class="links-table">
									<tbody>
										<tr>
											<th>
												<label for="yop-poll-start-date-input"><?php _e( 'Start Date', 'yop_poll' ); ?>:</label>
											</th>
											<td>
												<input id="yop-poll-start-date-input" type="text" name="yop_poll_options[start_date]" value="<?php echo '' == $default_options['start_date'] ? current_time('mysql', 1) : $default_options['start_date']; ?>" />
											</td>
										</tr>	
										<tr>
											<th>
												<label for="yop-poll-end-date-input"><?php _e( 'End Date ', 'yop_poll' ); ?>:</label>
											</th>
											<td>
												<input style="<?php echo 'yes' == $default_options['never_expire'] ? 'display: none;' : '';  ?>" <?php echo 'yes' == $default_options['never_expire'] ? 'disabled="disabled"' : '';  ?> id="yop-poll-end-date-input" type="text" name="yop_poll_options[end_date]" value="<?php echo '' == $default_options['end_date'] ? '' : $default_options['end_date']; ?>" />
												<label for="yop-poll-never-expire"><input type="checkbox" <?php echo $default_options['never_expire'] == 'yes' ? 'checked="checked"' : '';  ?>  id="yop-poll-never-expire" name="yop_poll_options[never_expire]" value="yes" /> Do NOT Expire This Poll</label> 
											</td>
										</tr>											
									</tbody>
								</table>
							</div>
						</div>
						<div class="postbox" id="yop-poll-advanced-options-div">
							<div title="Click to toggle" class="handlediv"><br /></div>
							<h3 class="hndle"><span><?php _e( 'View Results Options', 'yop_poll' ); ?></span></h3>
							<div class="inside">
								<table cellspacing="0" class="links-table">
									<tbody>
										<tr>
											<th>
												<?php _e( 'View Results', 'yop_poll' ); ?>:
											</th>
											<td>
												<label for="yop-poll-view-results-before-vote"><input class="yop-poll-view-results-hide-custom" <?php echo 'before' == $default_options['view_results'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-results-before-vote" type="radio" value="before" name="yop_poll_options[view_results]" /> <?php _e( 'Before Vote' , 'yop_poll'); ?></label>
												<label for="yop-poll-view-results-after-vote"><input class="yop-poll-view-results-hide-custom" <?php echo 'after' == $default_options['view_results'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-results-after-vote" type="radio" value="after" name="yop_poll_options[view_results]" /> <?php _e( 'After Vote' , 'yop_poll'); ?></label>
												<label for="yop-poll-view-results-after-poll-end-date"><input class="yop-poll-view-results-hide-custom" <?php echo 'after-poll-end-date' == $default_options['view_results'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-results-after-poll-end-date" type="radio" value="after-poll-end-date" name="yop_poll_options[view_results]" /> <?php _e( 'After Poll End Date' , 'yop_poll'); ?></label>
												<label for="yop-poll-view-results-never"><input class="yop-poll-view-results-hide-custom" <?php echo 'never' == $default_options['view_results'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-results-never" type="radio" value="never" name="yop_poll_options[view_results]" /> <?php _e( 'Never' , 'yop_poll'); ?></label>
												<label for="yop-poll-view-results-custom"><input class="yop-poll-view-results-show-custom" <?php echo 'custom-date' == $default_options['view_results'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-results-custom" type="radio" value="custom-date" name="yop_poll_options[view_results]" /> <?php _e( 'Custom Date' , 'yop_poll'); ?></label>
												<div id="yop-poll-display-view-results-div" style="<?php echo 'custom-date' != $default_options['view_results'] ? 'display: none;' : '';  ?>">
													<label for="yop-poll-view-results-start-date"><?php _e( 'Results display date (after this date the user can see the results)', 'yop_poll' ); ?>:</label> <input id="yop-poll-view-results-start-date" type="text" name="yop_poll_options[view_results_start_date]" value="<?php echo $default_options['view_results_start_date']; ?>" > 
												</div>
											</td>
										</tr>
										<tr>
											<th>
												<?php _e( 'Results Display', 'yop_poll' ); ?>:
											</th>
											<td>
												<label for="yop-poll-view-results-votes-number"><input id="yop-poll-view-results-votes-number" <?php echo 'votes-number' == $default_options['view_results_type'] ? 'checked="checked"' : '';  ?> type="radio" value="votes-number" name="yop_poll_options[view_results_type]" /> <?php _e( 'By Votes Number' , 'yop_poll'); ?></label>
												<label for="yop-poll-view-results-percentages"><input id="yop-poll-view-results-percentages" <?php echo 'percentages' == $default_options['view_results_type'] ? 'checked="checked"' : '';  ?> type="radio" value="percentages" name="yop_poll_options[view_results_type]" /> <?php _e( 'Percentages' , 'yop_poll'); ?></label>
												<label for="yop-poll-view-results-votes-number-and-percentages"><input id="yop-poll-view-results-votes-number-and-percentages" <?php echo 'votes-number-and-percentages' == $default_options['view_results_type'] ? 'checked="checked"' : '';  ?> type="radio" value="votes-number-and-percentages" name="yop_poll_options[view_results_type]" /> <?php _e( 'by Votes Number and Percentages' , 'yop_poll'); ?></label>
											</td>
										</tr>
										<tr>
											<th>
												<?php _e( 'Poll Answer Result Label', 'yop_poll'); ?>:
											</th>
											<td>
												<input id="yop-poll-answer-result-label" type="text" name="yop_poll_options[answer_result_label]" value="<?php echo esc_html( stripslashes( $default_options['answer_result_label'] ) ); ?>" /> <small><i>Use %POLL-ANSWER-RESULT-PERCENTAGES% for showing answer percentages and  %POLL-ANSWER-RESULT-VOTES% for showing answer number of votes</i></small>
											</td>	
										</tr>
										<tr>
											<th>
												<?php _e( 'Poll Answer Result Votes Number Label', 'yop_poll' ); ?>:
											</th>
											<td>
												<?php _e( 'Singular', 'yop_poll' ); ?>
												<input id="yop-poll-singular-answer-result-votes-number-label" type="text" name="yop_poll_options[singular_answer_result_votes_number_label]" value="<?php echo esc_html( stripslashes( $default_options['singular_answer_result_votes_number_label'] ) ); ?>" />
												<?php _e( 'Plural', 'yop_poll' ); ?>
												<input id="yop-poll-plural-answer-result-votes-number-label" type="text" name="yop_poll_options[plural_answer_result_votes_number_label]" value="<?php echo esc_html( stripslashes( $default_options['plural_answer_result_votes_number_label'] ) ); ?>" />
												
											</td>
										</tr>
										<tr>
											<th>
												<?php _e( 'Vote Button Label', 'yop_poll' ); ?>:
											</th>
											<td>
												<input id="yop-poll-vote-button-label" type="text" name="yop_poll_options[vote_button_label]" value="<?php echo esc_html( stripslashes( $default_options['vote_button_label'] ) ); ?>" />
											</td>
										</tr>
										<tr>
											<th>
												<?php _e( 'View Results Link', 'yop_poll' ); ?>:
											</th>
											<td>
												<label for="yop-poll-view-results-link-yes"><input <?php echo 'yes' == $default_options['view_results_link'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-results-link-yes" type="radio" value="yes" name="yop_poll_options[view_results_link]" /> <?php _e( 'Yes' , 'yop_poll'); ?></label>
												<label for="yop-poll-view-results-link-no"><input <?php echo 'no' == $default_options['view_results_link'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-results-link-no" type="radio" value="no" name="yop_poll_options[view_results_link]" /> <?php _e( 'No' , 'yop_poll'); ?></label>
											</td>
										</tr>
										<tr id="yop-poll-view-results-link-div" style="<?php echo 'yes' != $default_options['view_results_link'] ? 'display: none;' : '';  ?>">
											<th>
												<?php _e( 'View Results Link Label', 'yop_poll' ); ?>:
											</th>
											<td>
												<input id="yop-poll-view-results-link-label" type="text" name="yop_poll_options[view_results_link_label]" value="<?php echo esc_html( stripslashes( $default_options['view_results_link_label'] ) ); ?>" />
											</td>
										</tr>
										<tr>
											<th>
												<?php _e( 'View Back To Vote Link ', 'yop_poll' ); ?>:
											</th>
											<td>
												<label for="yop-poll-view-back-to-vote-link-yes"><input <?php echo 'yes' == $default_options['view_back_to_vote_link'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-back-to-vote-link-yes" type="radio" value="yes" name="yop_poll_options[view_back_to_vote_link]" /> <?php _e( 'Yes' , 'yop_poll'); ?></label>
												<label for="yop-poll-view-back-to-vote-link-no"><input <?php echo 'no' == $default_options['view_back_to_vote_link'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-back-to-vote-link-no" type="radio" value="no" name="yop_poll_options[view_back_to_vote_link]" /><?php _e( 'No' , 'yop_poll'); ?></label>
											</td>
										</tr>
										<tr id="yop-poll-view-back-to-vote-link-div" style="<?php echo 'yes' != $default_options['view_back_to_vote_link'] ? 'display: none;' : '';  ?>">
											<th>
												<?php _e( 'View Back To Vote Link Label', 'yop_poll' ); ?>:
											</th>
											<td>
												<input id="yop-poll-view-back-to-vote-link-label" type="text" name="yop_poll_options[view_back_to_vote_link_label]" value="<?php echo esc_html( stripslashes( $default_options['view_back_to_vote_link_label'] ) ); ?>" />
											</td>
										</tr>
										<tr>
											<th>
												<?php _e( 'View Total Votes ', 'yop_poll' ); ?>:
											</th>
											<td>
												<label for="yop-poll-view-total-votes-yes"><input <?php echo 'yes' == $default_options['view_total_votes'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-total-votes-yes" type="radio" value="yes" name="yop_poll_options[view_total_votes]" /> <?php _e( 'Yes' , 'yop_poll'); ?></label>
												<label for="yop-poll-view-total-votes-no"><input <?php echo 'no' == $default_options['view_total_votes'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-total-votes-no" type="radio" value="no" name="yop_poll_options[view_total_votes]" /> <?php _e( 'No' , 'yop_poll'); ?></label>
											</td>
										</tr>
										<tr id="yop-poll-view-total-votes-div" style="<?php echo 'yes' != $default_options['view_total_votes'] ? 'display: none;' : '';  ?>">
											<th>
												<?php _e( 'View Total Votes Label', 'yop_poll' ); ?>:
											</th>
											<td>
												<input id="yop-poll-view-total-votes-label" type="text" name="yop_poll_options[view_total_votes_label]" value="<?php echo esc_html( stripslashes( $default_options['view_total_votes_label'] ) ); ?>" />
											</td>
										</tr>
										<tr>
											<th>
												<?php _e( 'View Total Voters ', 'yop_poll' ); ?>:
											</th>
											<td>
												<label for="yop-poll-view-total-voters-yes"><input <?php echo 'yes' == $default_options['view_total_voters'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-total-voters-yes" type="radio" value="yes" name="yop_poll_options[view_total_voters]" /> <?php _e( 'Yes' , 'yop_poll'); ?></label>
												<label for="yop-poll-view-total-voters-no"><input <?php echo 'no' == $default_options['view_total_voters'] ? 'checked="checked"' : '';  ?> id="yop-poll-view-total-voters-no" type="radio" value="no" name="yop_poll_options[view_total_voters]" /> <?php _e( 'No' , 'yop_poll'); ?></label>
											</td>
										</tr>
										<tr id="yop-poll-view-total-voters-div" style="<?php echo 'yes' != $default_options['view_total_voters'] ? 'display: none;' : '';  ?>">
											<th>
												<?php _e( 'View Total Voters Label', 'yop_poll' ); ?>:
											</th>
											<td>
												<input id="yop-poll-view-total-voters-label" type="text" name="yop_poll_options[view_total_voters_label]" value="<?php echo esc_html( stripslashes( $default_options['view_total_voters_label'] ) ); ?>" />
											</td>
										</tr>
										<tr>
											<th>
												<label for="yop-poll-page-url"><?php _e( 'Poll Page Url ', 'yop_poll' ); ?>:</label>
											</th>
											<td>
												<input id="yop-poll-page-url" type="text" name="yop_poll_options[poll_page_url]" value="<?php echo esc_html( stripslashes( $default_options['poll_page_url'] ) ); ?>" />
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="postbox" id="yop-poll-advanced-options-div">
							<div title="Click to toggle" class="handlediv"><br /></div>
							<h3 class="hndle"><span><?php _e( 'Other Options', 'yop_poll' ); ?></span></h3>
							<div class="inside">
								<table cellspacing="0" class="links-table">
									<tbody>
										<tr>
											<th>
												<?php _e( 'Vote Permisions ', 'yop_poll' ); ?>:
											</th>
											<td>
												<label for="yop-poll-vote-permisions-quest-only"><input id="yop-poll-vote-permisions-quest-only" <?php echo 'quest-only' == $default_options['vote_permisions'] ? 'checked="checked"' : '';  ?> type="radio" value="quest-only" name="yop_poll_options[vote_permisions]" /> <?php _e( 'Guest Only' , 'yop_poll'); ?></label>
												<label for="yop-poll-vote-permisions-registered-only"><input id="yop-poll-vote-permisions-registered-only" <?php echo 'registered-only' == $default_options['vote_permisions'] ? 'checked="checked"' : '';  ?> type="radio" value="registered-only" name="yop_poll_options[vote_permisions]" /> <?php _e( 'Registered Users Only' , 'yop_poll'); ?></label>
												<label for="yop-poll-vote-permisions-guest-registered"><input id="yop-poll-vote-permisions-guest-registered" <?php echo 'guest-registered' == $default_options['vote_permisions'] ? 'checked="checked"' : '';  ?> type="radio" value="guest-registered" name="yop_poll_options[vote_permisions]" /> <?php _e( 'Guest &amp; Registered Users' , 'yop_poll'); ?></label>
											</td>
										</tr>
										<tr>
											<th>
												<?php _e( 'Blocking Voters ', 'yop_poll' ); ?>:
											</th>
											<td>
												<label for="yop-poll-blocking-voters-dont-block"><input class="yop-poll-blocking-voters-hide-interval" <?php echo 'dont-block' == $default_options['blocking_voters'] ? 'checked="checked"' : '';  ?> id="yop-poll-blocking-voters-dont-block" type="radio" value="dont-block" name="yop_poll_options[blocking_voters]" /> <?php _e( 'Dont`t Block' , 'yop_poll'); ?></label>
												<label for="yop-poll-blocking-voters-cookie"><input class="yop-poll-blocking-voters-show-interval" <?php echo 'cookie' == $default_options['blocking_voters'] ? 'checked="checked"' : '';  ?> id="yop-poll-blocking-voters-cookie" type="radio" value="cookie" name="yop_poll_options[blocking_voters]" /> <?php _e( 'By Cookie' , 'yop_poll'); ?></label>
												<label for="yop-poll-blocking-voters-ip"><input class="yop-poll-blocking-voters-show-interval" <?php echo 'ip' == $default_options['blocking_voters'] ? 'checked="checked"' : '';  ?> id="yop-poll-blocking-voters-ip" type="radio" value="ip" name="yop_poll_options[blocking_voters]" /> <?php _e( 'By Ip' , 'yop_poll'); ?></label>
												<label for="yop-poll-blocking-voters-username"><input class="yop-poll-blocking-voters-show-interval" <?php echo 'username' == $default_options['blocking_voters'] ? 'checked="checked"' : '';  ?> id="yop-poll-blocking-voters-username" type="radio" value="username" name="yop_poll_options[blocking_voters]" /> <?php _e( 'By Username' , 'yop_poll'); ?></label>
												<label for="yop-poll-blocking-voters-cookie-ip"><input class="yop-poll-blocking-voters-show-interval" <?php echo 'cookie-ip' == $default_options['blocking_voters'] ? 'checked="checked"' : '';  ?> id="yop-poll-blocking-voters-cookie-ip" type="radio" value="cookie-ip" name="yop_poll_options[blocking_voters]" /> <?php _e( 'By Cookie &amp; Ip' , 'yop_poll'); ?></label>
											</td>
										</tr>
										<tr id="yop-poll-blocking-voters-interval-div" style="<?php echo 'dont-block' == $default_options['blocking_voters'] ? 'display: none;' : '';  ?>">
											<th>
												<?php _e( 'Blocking voters interval', 'yop_poll' ); ?>:
											</th>
											<td>
												<input type="text" name="yop_poll_options[blocking_voters_interval_value]" id="yop-poll-blocking-voters-interval-value" value="<?php echo $default_options['blocking_voters_interval_value'];  ?>" />
												<select id="yop-poll-blocking-voters-interval-unit" name="yop_poll_options[blocking_voters_interval_unit]">
													<option <?php echo 'seconds' == $default_options['blocking_voters_interval_unit'] ? 'selected="selected"' : '';  ?> value="seconds"><?php _e( 'Seconds', 'yop_poll' ); ?></option>
													<option <?php echo 'minutes' == $default_options['blocking_voters_interval_unit'] ? 'selected="selected"' : '';  ?> value="minutes"><?php _e( 'Minutes', 'yop_poll' ); ?></option>
													<option <?php echo 'hours' == $default_options['blocking_voters_interval_unit'] ? 'selected="selected"' : '';  ?> value="hours"><?php _e( 'Hours', 'yop_poll' ); ?></option>
													<option <?php echo 'days' == $default_options['blocking_voters_interval_unit'] ? 'selected="selected"' : '';  ?> value="days"><?php _e( 'Days', 'yop_poll' ); ?></option>
												</select> 
											</td>
										</tr>
										<tr>
											<th><?php _e( 'Poll Template ', 'yop_poll' ); ?>:</th>
											<td>
												<?php 
													$templates	= YOP_POLL_MODEL::get_yop_poll_templates_search( 'id', 'asc' );
												?>
												<select class="yop-poll-template" id="yop-poll-template" name="yop_poll_options[template]">
													<option value=""><?php _e( '--SELECT Template--',  'yop_poll' ); ?></option>
													<?php 
														if ( count( $templates ) > 0 ) {
															foreach( $templates as $template ) {
															?>
															<option <?php if($default_options['template'] == $template['id'] ) echo 'selected="selected"' ?> value="<?php echo $template['id']; ?>"><?php echo esc_html( stripslashes( $template['name'] ) ) ?></option>
															<?php
															} 
														}
													?>
												</select>
											</td>
										</tr>
										<tr>
											<th>
												<?php _e( 'Percentages Decimals', 'yop_poll' ); ?>:
											</th>
											<td>
												<input id="yop-poll-percentages-decimals" type="text" name="yop_poll_options[percentages_decimals]" value="<?php echo esc_html( stripslashes( $default_options['percentages_decimals'] ) ); ?>" />
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div class="postbox" id="yop-poll-advanced-options-div">
							<div title="Click to toggle" class="handlediv"><br /></div>
							<h3 class="hndle"><span><?php _e( 'Archive Options', 'yop_poll' ); ?></span></h3>
							<div class="inside">
								<table cellspacing="0" class="links-table">
									<tbody>
									<tr>
										<th>
											<?php _e( 'View Poll Archive Link ', 'yop_poll' ); ?>:
										</th>
										<td>
											<input <?php checked( 'yes', $default_options['view_poll_archive_link'] ); ?> id="yop-poll-view-poll-archive-link-yes" type="radio" value="yes" name="yop_poll_options[view_poll_archive_link]" /><label for="yop-poll-view-poll-archive-link-yes"><?php _e( 'Yes' , 'yop_poll'); ?></label>
											<input <?php checked( 'no', $default_options['view_poll_archive_link'] ); ?> id="yop-poll-view-poll-archive-link-no" type="radio" value="no" name="yop_poll_options[view_poll_archive_link]" /><label for="yop-poll-view-poll-archive-link-no"><?php _e( 'No' , 'yop_poll'); ?></label>
										</td>
									</tr>
									<tr id="yop-poll-view-poll-archive-link-div" style="<?php echo 'yes' != $default_options['view_poll_archive_link'] ? 'display: none;' : '';  ?>">
										<th>
											<?php _e( 'View Poll Archive Link Label', 'yop_poll' ); ?>:
										</th>
										<td>
											<input id="yop-poll-view-poll-archive-link-label" type="text" name="yop_poll_options[view_poll_archive_link_label]" value="<?php echo esc_html( stripslashes( $default_options['view_poll_archive_link_label'] ) ); ?>" />
										</td>
									</tr>
									<tr id="yop-poll-view-poll-archive-link-div" style="<?php echo 'yes' != $default_options['view_poll_archive_link'] ? 'display: none;' : '';  ?>">
										<th>
											<?php _e( 'Poll Archive Url', 'yop_poll' ); ?>:
										</th>
										<td>
											<input id="yop-poll-poll-archive-url" type="text" name="yop_poll_options[poll_archive_url]" value="<?php echo esc_html( stripslashes( $default_options['poll_archive_url'] ) ); ?>" />
										</td>
									</tr>
									<tr>
										<th>
											<?php _e( 'Show Poll In Archive ', 'yop_poll' ); ?>:
										</th>
										<td>
											<label for="yop-poll-show-in-archive-yes"><input <?php checked( 'yes', $default_options['show_in_archive'] ); ?> id="yop-poll-show-in-archive-yes" type="radio" value="yes" name="yop_poll_options[show_in_archive]" /> <?php _e( 'Yes' , 'yop_poll'); ?></label>
											<label for="yop-poll-show-in-archive-no"><input <?php checked( 'no', $default_options['show_in_archive'] ); ?> id="yop-poll-show-in-archive-no" type="radio" value="no" name="yop_poll_options[show_in_archive]" /> <?php _e( 'No' , 'yop_poll'); ?></label>
										</td>
									</tr>
									<tr id="yop-poll-show-in-archive-div" style="<?php echo 'yes' != $default_options['show_in_archive'] ? 'display: none;' : '';  ?>">
										<th>
											<?php _e( 'Archive Order', 'yop_poll' ); ?>:
										</th>
										<td>
											<input id="yop-poll-show-in-archive-order" type="text" name="yop_poll_options[archive_order]" value="<?php echo esc_html( stripslashes( $default_options['archive_order'] ) ); ?>" />
										</td>
									</tr>
								</table>	
							</div>
						</div>
					</div>
					<input type="hidden" value="<?php echo $poll_id ?>" name="yop_poll_id" id="yop-poll-edit-add-new-form-poll-id" />
					<input type="hidden" value="<?php echo $action_type ?>" name="action_type" id="yop-poll-edit-add-new-form-action-type" />
					<input type="button" accesskey="p" class="button-primary" value="<?php _e( 'Save Poll', 'yop_poll' ); ?>"  id="yop-poll-edit-add-new-form-submit" />
				</div>
				<div class="postbox-container" id="postbox-container-1">
					<div class="meta-box-sortables ui-sortable" id="side-sortables">
						<div class="postbox " id="linksubmitdiv">
							<div title="Click to toggle" class="handlediv">
								<br />
							</div>
							<h3 class="hndle"><span><?php _e( 'Save', 'yop_poll' ); ?></span></h3>
							<div class="inside">
								<div id="submitlink" class="submitbox">
									<div id="major-publishing-actions">
										<div id="publishing-action">
											<input type="button" accesskey="p" class="button-primary" value="<?php _e( 'Save Poll', 'yop_poll' ); ?>" id="yop-poll-edit-add-new-form-submit1" />
										</div>
										<div class="clear"></div>
									</div>
									<div class="clear"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<br class="clear">
		</div>
		</form>
		</div>
		<?php
		}

		public function view_add_edit_poll_template( ) {
			global $action;
			$page_name			= __( 'Add New Poll Template', 'yop_poll' );
			$action_type		= 'add-new';
			$template_id		= '';
			if ( 'edit' == $action ) {
				$template_id			= ( isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0 );
				$page_name				= __( 'Edit Poll Template', 'yop_poll' );
				$action_type			= 'edit';
			}
			require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
			$current_template	= YOP_POLL_MODEL::get_poll_template_from_database_by_id( $template_id );
		?>
		<div class="wrap">
			<div class="icon32 icon32-yop-poll"><br></div><h2><?php print $page_name; ?><?php if( 'edit' == $action ): ?><a class="add-new-h2" href="<?php echo esc_url( add_query_arg( array( 'page' => 'yop-polls-templates', 'action' => 'add-new', 'id' => false ) ) ); ?>" ><?php _e( 'Add New', 'yop_poll' ); ?></a><?php endif; ?></h2>
			<div id="message"></div>
			<form method="post" name="yop_poll_edit_add_new_template_form" id="yop-poll-edit-add-new-template-form">
				<?php wp_nonce_field( 'yop-poll-edit-add-new-template' ); ?>
				<div id="poststuff">
					<div id="post-body" class="metabox-holder columns-2">
						<div id="post-body-content">
							<div class="stuffbox" id="yop-poll-template-namediv">
								<h3><label for="yop-poll-template-name"><?php _e( 'Template Name', 'yop_poll' ); ?></label></h3>
								<div class="inside">
									<input type="text" id="yop-poll-template-name" value="<?php echo esc_html( stripslashes( $current_template['name'] ) ); ?>" tabindex="1" name="yop_poll_template_name" size="30" />
									<p><?php _e( 'Example: Test Poll Template', 'yop_poll' ); ?></p>
								</div>
							</div>
							<div class="stuffbox" id="yop-poll-before-vote-template-div">
								<h3><label for="yop-poll-before_vote-template-input"><?php _e( 'Template Before Vote', 'yop_poll' ); ?></label></h3>
								<div class="inside">
									<?php wp_editor( stripslashes( $current_template['before_vote_template'] ), 'yop-poll-before-vote-template-input', array( 'textarea_name' => 'yop_poll_before_vote_template', 'teeny' => true, 'media_buttons' => false )); ?>
								</div>
							</div>
							<div class="stuffbox" id="yop-poll-after-vote-template-div">
								<h3><label for="yop-poll-after-vote-template-input"><?php _e( 'Template After Vote', 'yop_poll' ); ?></label></h3>
								<div class="inside">
									<?php wp_editor( stripslashes( $current_template['after_vote_template'] ), 'yop-poll-after-vote-template-input', array( 'textarea_name' => 'yop_poll_after_vote_template', 'teeny' => true, 'media_buttons' => false )); ?>
								</div>
							</div>
							<div class="postbox" id="yop-poll-template-before-start-date-div">
								<div title="Click to toggle" class="handlediv" id="yop-poll-template-before-start-date-handler"><br></div>
								<h3><label for="yop-poll-template-before-start-date-input"><?php _e( 'Template Before Start Date', 'yop_poll' ); ?></label></h3>
								<div class="inside">
									<?php wp_editor( stripslashes( $current_template['before_start_date_template'] ), 'yop-poll-template-before-start-date-input', array( 'textarea_name' => 'yop_poll_template_before_start_date', 'teeny' => true, 'media_buttons' => false )); ?>
								</div>
							</div>							
							<div class="postbox" id="yop-poll-template-after-end-date-div">
								<div title="Click to toggle" class="handlediv" id="yop-poll-template-after-end-date-handler"><br></div>
								<h3><label for="yop-poll-template-after-end-date-input"><?php _e( 'Template After End Date', 'yop_poll' ); ?></label></h3>
								<div class="inside">
									<?php wp_editor( stripslashes( $current_template['after_end_date_template'] ), 'yop-poll-template-after-end-date-input', array( 'textarea_name' => 'yop_poll_template_after_end_date', 'teeny' => true, 'media_buttons' => false )); ?>
								</div>
							</div>							
							<div class="postbox" id="yop-poll-template-css-div">
								<div title="Click to toggle" class="handlediv" id="yop-poll-template-css-handler"><br></div>
								<h3><label for="yop-poll-template-css-input"><?php _e( 'Css', 'yop_poll' ); ?></label></h3>
								<div class="inside">
									<?php wp_editor( stripslashes( $current_template['css'] ), 'yop-poll-template-css-input', array( 'textarea_name' => 'yop_poll_template_css', 'teeny' => true, 'media_buttons' => false )); ?>
								</div>
							</div>							
							<div class="postbox" id="yop-poll-template-js-div">
								<div title="Click to toggle" class="handlediv" id="yop-poll-template-js-handler"><br></div>
								<h3><label for="yop-poll-template-js-input"><?php _e( 'JavaScript', 'yop_poll' ); ?></label></h3>
								<div class="inside">
									<?php wp_editor( stripslashes( $current_template['js'] ), 'yop-poll-template-js-input', array( 'textarea_name' => 'yop_poll_template_js', 'teeny' => true, 'media_buttons' => false )); ?>
								</div>
							</div>

							<input type="hidden" value="<?php echo $current_template['id']; ?>" name="template_id" id="yop-poll-edit-add-new-template-form-template-id" />
							<input type="hidden" value="<?php echo $action_type ?>" name="action_type" id="yop-poll-edit-add-new-template-form-action-type" />
							<input type="button" class="button-primary" value="<?php _e('Save', 'yop_poll' ) ?>" id="yop-poll-edit-add-new-template-form-submit" />
						</div>
					</div>
					<br class="clear">
				</div>
			</form>
		</div>
		<?php
		}
		/**
		* End Views section
		*/
		//
		/**
		* Start Ajax section
		*/
		function ajax_edit_add_new_poll() {
			if ( is_admin() ) {
				global $wpdb;
				check_ajax_referer('yop-poll-edit-add-new');
				require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
				$yop_poll_model = new Yop_Poll_Model();
				if ( 'add-new' == $_REQUEST['action_type'] ) {
					$yop_poll_id = $yop_poll_model->add_poll_to_database( $_REQUEST, $this->_config );
					if( $yop_poll_id ) {
						_e( 'Poll successfully added!', 'yop_poll' );
					}
					else {
						echo $yop_poll_model->error;
					}
				}
				if ( 'edit' == $_REQUEST['action_type'] ) {
					if( ctype_digit( $_REQUEST['yop_poll_id'] )) {
						$yop_poll_id = $yop_poll_model->edit_poll_in_database( $_REQUEST, $this->_config );
						if ( $yop_poll_id) 
							_e( 'Poll successfully Edited!', 'yop_poll' );
						else
							echo $yop_poll_model->error;	
					}
					else
						_e( 'We\'re unable to update your poll!', 'yop_poll' );	
				}
				unset( $yop_poll_model );
			}
			die();
		}

		function ajax_edit_add_new_poll_template() {
			if ( is_admin() ) {
				global $wpdb;
				check_ajax_referer('yop-poll-edit-add-new-template');
				require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
				$yop_poll_model = new Yop_Poll_Model();
				if( 'add-new' == $_REQUEST['action_type'] ) {
					$yop_poll_template_id = $yop_poll_model->add_poll_template_to_database( $_REQUEST, $this->_config );
					if( $yop_poll_template_id ) {
						_e( 'Poll template successfully added!', 'yop_poll' );
					}
					else {
						echo $yop_poll_model->error;
					}	
				}
				if ( 'edit' == $_REQUEST['action_type'] ) {
					if( ctype_digit( $_REQUEST['template_id'] )) {
						$yop_poll_template_id = $yop_poll_model->edit_poll_template_in_database( $_REQUEST, $this->_config );
						if( $yop_poll_template_id ) {
							_e( 'Poll Template successfully Edited!', 'yop_poll' );
						}
						else {
							echo $yop_poll_model->error;
						}
					}
					else
						_e( 'We\'re unable to update your poll template!', 'yop_poll' );	
				}
				unset( $yop_poll_model );
			}
			die();
		}

		public function yop_poll_do_vote() {
			$error		= '';
			$message	= '';
			if ( is_admin() ) {
				$poll_id = isset( $_REQUEST['poll_id'] ) ? $_REQUEST['poll_id'] : NULL;
				if ( $poll_id ) {
					require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
					$yop_poll_model		= new YOP_POLL_MODEL( $poll_id );
					$poll_html			= $yop_poll_model->register_vote( $_REQUEST );
					if( $poll_html ) {
						$message		= $poll_html;		
					}
					else {
						$error			= $yop_poll_model->error;
					}
					unset($yop_poll_model);
				}
				else {
					$error 				= __( 'Invalid Request! Try later!', 'yop_poll' ); 
				}
			}  
			print '[ajax-response]'.json_encode( array( 'error' => $error, 'message' => $message ) ).'[/ajax-response]';
			die();
		}

		public function yop_poll_view_results() {
			$error		= '';
			$message	= '';
			if ( is_admin() ) {
				$poll_id = isset( $_REQUEST['poll_id'] ) ? $_REQUEST['poll_id'] : NULL;
				if ( $poll_id ) {
					require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
					$yop_poll_model			= new YOP_POLL_MODEL( $poll_id );
					$yop_poll_model->vote	= true;
					$poll_html				= do_shortcode( $yop_poll_model->return_poll_html() );
					if( $poll_html ) {
						$message			= $poll_html;		
					}
					else {
						$error				= $yop_poll_model->error;
					}
					unset($yop_poll_model);
				}
				else {
					$error 				= __( 'Invalid Request! Try later!', 'yop_poll' ); 
				}
			}
			//print json_encode( array( 'error' => $error, 'message' => $message ) );
			print '[ajax-response]'.json_encode( array( 'error' => $error, 'message' => $message ) ).'[/ajax-response]';
			die();
		}

		public function yop_poll_back_to_vote() {
			$error		= '';
			$message	= '';
			if ( is_admin() ) {
				$poll_id = isset( $_REQUEST['poll_id'] ) ? $_REQUEST['poll_id'] : NULL;
				if ( $poll_id ) {
					require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
					$yop_poll_model					= new YOP_POLL_MODEL( $poll_id );
					$poll_html						= do_shortcode( $yop_poll_model->return_poll_html() );
					if( $poll_html ) {
						$message					= $poll_html;		
					}
					else {
						$error						= $yop_poll_model->error;
					}
					unset($yop_poll_model);
				}
				else {
					$error 							= __( 'Invalid Request! Try later!', 'yop_poll' ); 
				}
			}
			//print json_encode( array( 'error' => $error, 'message' => $message ) );
			print '[ajax-response]'.json_encode( array( 'error' => $error, 'message' => $message ) ).'[/ajax-response]';
			die();
		}

		public function yop_poll_load_css() {
			$expires_offset = 31536000;
			header('Content-Type: text/css');
			header('Expires: ' . gmdate( "D, d M Y H:i:s", time() + $expires_offset ) . ' GMT');
			header("Cache-Control: public, max-age=$expires_offset");
			check_ajax_referer('yop-poll-public-css');
			if ( is_admin() ) {
				$poll_id = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : NULL;
				if ( $poll_id ) {
					require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
					$yop_poll_model		= new YOP_POLL_MODEL( $poll_id );
					$poll_css			= $yop_poll_model->return_poll_css();
					print $poll_css;
					unset($yop_poll_model);
				}
			}
			die();
		}

		public function yop_poll_load_js() {
			check_ajax_referer('yop-poll-public-js');
			if ( is_admin() ) {
				$poll_id = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : NULL;
				if ( $poll_id ) {
					require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
					$yop_poll_model		= new YOP_POLL_MODEL( $poll_id );
					$poll_css			= $yop_poll_model->return_poll_js();
					print $poll_css;
					unset($yop_poll_model);
				}
			}
			die();
		}

		public function ajax_get_polls_for_editor() {
			check_ajax_referer('yop-poll-editor');
			if ( is_admin() ) {
				require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
				$yop_polls	= Yop_Poll_Model::get_yop_polls_filter_search( 'id', 'asc' );
			?>
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<title><?php _e('Insert Poll', 'yop_poll'); ?></title>
					<script type="text/javascript" src="<?php echo get_option( 'siteurl' ) ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
					<script type="text/javascript">
						function insertYopPollTinyMce( poll_id ) {
							if ( isNaN( poll_id ) ) {
								alert( '<?php _e( 'Error: Invalid Yop Poll!\n\nPlease choose the poll again:\n\n', 'yop_poll' ) ?>' );
							}
							else {
								if ( poll_id != null && poll_id != '' ) {
									tinyMCEPopup.editor.execCommand('mceInsertContent', false, '[yop_poll id="'+poll_id+'"]' );
								}
								else {
									tinyMCEPopup.editor.execCommand('mceInsertContent', false, '[yop_poll]' );
								}
								tinyMCEPopup.close();
							}
						}
					</script>
				</head>
				<body>
					<p>
						<label for="yop-poll-id-dialog">
							<span><?php _e( 'Poll to Display', 'yop_poll' ); ?>:</span>
							<select class="widefat" name="yop_poll_id" id="yop-poll-id-dialog">
								<option value="-3"><?php _e( 'Display Random Poll', 'yop_poll' ); ?></option>
								<option value="-2"><?php _e( 'Display Latest Poll', 'yop_poll' ); ?></option>
								<option value="-1"><?php _e( 'Display Current Active Poll', 'yop_poll' ); ?></option>
								<?php 
									if( count( $yop_polls ) > 0 ) {
										foreach( $yop_polls as $yop_poll ) {
										?>
										<option value="<?php echo $yop_poll['id']; ?>"><?php echo esc_html( stripslashes( $yop_poll['name'] ) ); ?></option>
										<?php
										}
									}
								?>
							</select>
						</label>
						<center><input type="button" class="button-primary" value="<?php _e( 'Insert Poll', 'yop_poll' ); ?>" onclick=" insertYopPollTinyMce( document.getElementById('yop-poll-id-dialog').value );" /></center>
						<br />
						<center><input type="button" class="button-primary" value="<?php _e( 'Close', 'yop_poll' ); ?>" onclick="tinyMCEPopup.close();"/></center>
					</p>
				</body>
			</html>
			<?php	
			}
			die();	
		}

		public function ajax_get_polls_for_html_editor() {
			check_ajax_referer('yop-poll-html-editor');
			if ( is_admin() ) {
				require_once( $this->_config->plugin_inc_dir.'/yop_poll_model.php');
				$yop_polls	= Yop_Poll_Model::get_yop_polls_filter_search( 'id', 'asc' );
			?>		
			<p style="text-align:center;">
				<label for="yop-poll-id-html-dialog">
					<span><?php _e( 'Poll to Display', 'yop_poll' ); ?>:</span>
					<select class="widefat" name="yop_poll_id" id="yop-poll-id-html-dialog">
						<option value="-3"><?php _e( 'Display Random Poll', 'yop_poll' ); ?></option>
						<option value="-2"><?php _e( 'Display Latest Poll', 'yop_poll' ); ?></option>
						<option value="-1"><?php _e( 'Display Current Active Poll', 'yop_poll' ); ?></option>
						<?php 
							if( count( $yop_polls ) > 0 ) {
								foreach( $yop_polls as $yop_poll ) {
								?>
								<option value="<?php echo $yop_poll['id']; ?>"><?php echo esc_html( stripslashes( $yop_poll['name'] ) ); ?></option>
								<?php
								}
							}
						?>
					</select>
				</label>
				<br />
				<br />
				<input type="button" class="" value="<?php _e( 'Insert Poll', 'yop_poll' ); ?>" onclick=" insertYopPoll( edCanvas, document.getElementById('yop-poll-id-html-dialog').value );" />
				<br />
				<br />
				<input type="button" class="" value="<?php _e( 'Close', 'yop_poll' ); ?>" onclick="tb_remove();"/>
			</p>

			<?php	
			}
			die();	
		}

		/**
		* End Ajax section
		*/

		/* start timymce */
		function load_editor_functions( $hook ) {
			global $post;

			if ( $hook == 'post-new.php' || $hook == 'post.php' || $hook == 'page-new.php' || $hook == 'page.php' ) {
				$yop_poll_editor_config	= array(
					'dialog_url'					=> wp_nonce_url( admin_url('admin-ajax.php', (is_ssl() ? 'https' : 'http')).'?action=yop_poll_editor', 'yop-poll-editor' ),
					'dialog_html_url'				=> wp_nonce_url( admin_url('admin-ajax.php', (is_ssl() ? 'https' : 'http')).'?action=yop_poll_html_editor', 'yop-poll-html-editor' ),
					'name'							=> __( 'Yop Poll', 'yop_poll' ),
					'title'							=> __( 'Insert Poll', 'yop_poll' ),
					'prompt_insert_poll_id'			=> __( 'Please insert the poll ID:\n\n', 'yop_poll' ),
					'prompt_insert_again_poll_id'	=> __( 'Error: Poll Id must be numeric!\n\nPlease insert the poll ID Again:\n\n', 'yop_poll' )
				);
				wp_enqueue_style( 'thickbox' );
				wp_enqueue_script( 'thickbox' );
				wp_enqueue_script( 'yop-poll-editor-functions', "{$this->_config->plugin_url}/tinymce/yop-poll-editor-functions.js", 'jquery', $this->_config->version, true );
				wp_localize_script( 'yop-poll-editor-functions', 'yop_poll_editor_config', $yop_poll_editor_config );
			}
		}

		function register_button( $buttons ) {
			array_push( $buttons, "separator", "yoppoll" );
			return $buttons;
		}

		function add_plugin( $plugin_array ) {
			$plugin_array['yoppoll'] = "{$this->_config->plugin_url}/tinymce/yop-poll-editor.js";
			return $plugin_array;
		}

		function my_yop_poll_button() { 
			if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
				return;
			}

			if ( get_user_option('rich_editing') == 'true' ) {
				add_filter( 'mce_external_plugins', array( &$this, 'add_plugin' ) );
				add_filter( 'mce_buttons', array( &$this, 'register_button' ) );
			}   
		}
		/* end tinymce */
}