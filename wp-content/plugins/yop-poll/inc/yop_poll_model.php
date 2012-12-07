<?php
	class Yop_Poll_Model {

		var $error							= NULL;
		var $poll							= array(
			'id'							=> NULL,
			'name'							=> NULL,
			'question'						=> NULL,
			'start_date'					=> NULL,
			'end_date'						=> NULL,
			'total_votes'					=> 0,
			'total_voters'					=> 0,
			'status'						=> 'open',
			'date_added'					=> NULL,
			'last_modified'					=> NULL
		);

		var $poll_options					= NULL;
		var $answers						= NULL;
		var $custom_fields					= NULL;

		var $template						= array(
			'id'							=> NULL,
			'name'							=> NULL,
			'before_vote_template'			=> NULL,
			'after_vote_template'			=> NULL,
			'before_start_date_template'	=> NULL,
			'after_end_date_template'		=> NULL,
			'css'							=> NULL,
			'js'							=> NULL,
			'status'						=> 'active',
			'date_added'					=> NULL,
			'last_modified'					=> NULL,
			'show_in_archive'				=> NULL,
			'archive_order'					=> NULL,
		);
		var $vote							= false;

		public function __construct( $poll_id = -99 ) {
			//do not load id= -99
			$poll = NULL;
			//Current Active Poll id = -1
			if ( -1 == $poll_id ) {
				$poll	= self::get_current_active_poll();
			}
			//Latest Poll id = -2
			elseif ( -2 == $poll_id) {
				$polls	= self::get_yop_polls_filter_search( 'date_added', 'desc' );	
				$poll	= $polls[0];
			}
			//Random Poll id = -3
			elseif ( -3 == $poll_id ) {
				$polls	= self::get_yop_polls_filter_search( 'rand()', '' );	
				$poll	= $polls[0];
			}
			//normal poll
			elseif ( $poll_id > 0 ) {
				$poll	= self::get_poll_from_database_by_id( $poll_id );
			}
			if ( $poll ) {
				$this->poll					= $poll;
				$this->poll_options			= get_yop_poll_meta( $this->poll['id'], 'options', true );
				$default_options			= get_option( 'yop_poll_options', false );
				if ( count( $default_options ) > 0 ) {
					foreach ( $default_options as $option_name => $option_value ) {
						if( ! isset( $this->poll_options [ $option_name ] ) )
							$this->poll_options	[ $option_name ] = $option_value;	
					}
				}
			}
		}

		public function verify_request_data ( $request = array(), $config = null ) {
			if ( isset( $request['yop_poll_name'] ) && '' == trim( $request['yop_poll_name'] ) ) {
				$this->error = __( 'Poll name needed!', 'yop_poll' );
				return false;
			}
			elseif (isset( $request['yop_poll_question'] ) && '' == trim( $request['yop_poll_question'] ) ) {
				$this->error = __( 'Poll question needed!', 'yop_poll' );
				return false;
			}
			elseif ( isset( $request['yop_poll_answer'] ) && count ( $request['yop_poll_answer'] ) < $config->min_number_of_answers ) {
				$this->error = __( 'At least '.$config->min_number_of_answers.' answers needed!', 'yop_poll' );
				return false;
			}
			else {
				if ( isset( $request['yop_poll_answer'] ) ) {
					$index = 1;
					foreach ( $request['yop_poll_answer'] as $answer_id => $answer_value ) {
						if ( '' == trim( $answer_value ) ) {
							$this->error = __( 'Answer ' . $index . ' should not be empty!', 'yop_poll' );
							return false;	
						}
						$index++;
					}
				}
				else {
					$this->error = __( 'Answers not found!', 'yop_poll' );
					return false;
				}

				if ( isset( $request['yop_poll_customfield'] ) ) {
					$index = 1;
					if ( count( $request['yop_poll_customfield'] > 0 ) ) {
						foreach ( $request['yop_poll_customfield'] as $customfield_id => $customfield_value ) {
							if ( '' == trim( $customfield_value ) ) {
								$this->error = __( 'Custom Field ' . $index . ' should not be empty!', 'yop_poll' );
								return false;
							}
							$index++;
						}
					}
				}

				if ( isset( $request['yop_poll_options']['start_date'] ) ) {
					if ( '' == $request['yop_poll_options']['start_date'] ) {
						$this->error = __( 'Start Date needed!', 'yop_poll' );
						return false;
					}	
				}
				else {
					$this->error = __( 'Start Date not found!', 'yop_poll' );
					return false;	
				}

				if ( ! isset( $request['yop_poll_options']['never_expire'] ) ) {
					if ( isset( $request['yop_poll_options']['end_date'] ) ) { 
						if ( '' == $request['yop_poll_options']['end_date'] ) {
							$this->error = __( 'End Date needed!', 'yop_poll' );
							return false;
						}	
					}
					if ( isset( $request['yop_poll_options']['start_date'] ) ) {
						if ( isset( $request['yop_poll_options']['end_date'] ) ) {
							if ( $request['yop_poll_options']['start_date'] > $request['yop_poll_options']['end_date']) {
								$this->error = __( 'Invalid entry! Start Date is after the  End Date! ', 'yop_poll' );
								return false;
							}
						}
					} 
				} 

				//answer_result_label
				if ( isset( $request['yop_poll_options']['answer_result_label'] ) ) {
					if ( 'votes-number' == $request['yop_poll_options']['view_results_type'] ) {
						if ( stripos( $request['yop_poll_options']['answer_result_label'], '%POLL-ANSWER-RESULT-VOTES%' ) === false ) {
							$this->error = __( 'Option "Poll Answer Result Label" Not Updated! You must use %POLL-ANSWER-RESULT-VOTES%!', 'yop_poll' );
							return false;	
						}
					}

					if ( 'percentages' == $request['yop_poll_options']['view_results_type'] ) {
						if ( stripos( $request['yop_poll_options']['answer_result_label'], '%POLL-ANSWER-RESULT-PERCENTAGES%' ) === false ) {
							$this->error = __( 'Option "Poll Answer Result Label" Not Updated! You must use %POLL-ANSWER-RESULT-PERCENTAGES%!', 'yop_poll' );
							return false;	
						}
					}

					if ( 'votes-number-and-percentages' == $request['yop_poll_options']['view_results_type'] ) {
						if ( stripos( $request['yop_poll_options']['answer_result_label'], '%POLL-ANSWER-RESULT-PERCENTAGES%' ) === false ) {
							$this->error = __( 'Option "Poll Answer Result Label" Not Updated! You must use %POLL-ANSWER-RESULT-VOTES% and %POLL-ANSWER-RESULT-PERCENTAGES%!', 'yop_poll' );	
							return false;
						}
						elseif ( stripos( $request['yop_poll_options']['answer_result_label'], '%POLL-ANSWER-RESULT-VOTES%' ) === false ) {
							$this->error = __( 'Option "Poll Answer Result Label" Not Updated! You must use %POLL-ANSWER-RESULT-VOTES% and %POLL-ANSWER-RESULT-PERCENTAGES%!', 'yop_poll' );	
							return false;
						}
					}	
				}
				else {
					$this->error = __( 'Option "Poll Answer Result Label" Not Updated!', 'yop_poll' );	
					return false;
				}

				//view_results_link
				if ( isset( $request['yop_poll_options']['view_results_link'] ) ) {
					if ( in_array( $request['yop_poll_options']['view_results_link'], array('yes', 'no' ) ) ) {
						if ( 'yes' == $request['yop_poll_options']['view_results_link'] ) {
							//view_results_link_label	
							if ( isset( $request['yop_poll_options']['view_results_link_label'] ) ) {
								if ( '' ==  $request['yop_poll_options']['view_results_link_label'] ) {
									$this->error = __( 'Option "View Results Link Label" is empty!', 'yop_poll' );
									return false;
								}
							}
							else {
								$this->error = __( 'Option "View Results Link Label" not found!', 'yop_poll' );
								return false;	
							}
						}
					} 
					else {
						$this->error = __( 'Option "View Results Link" is invalid! You must choose between \'yes\' or \'no\'', 'yop_poll' );
						return false;
					}
				}
				else {
					$this->error = __( 'Option "View Results Link" not found!', 'yop_poll' );
					return false;
				}

				//view_back_to_vote_link
				if ( isset( $request['yop_poll_options']['view_back_to_vote_link'] ) ) {
					if ( in_array( $request['yop_poll_options']['view_back_to_vote_link'], array('yes', 'no' ) ) ) {
						if ( 'yes' == $request['yop_poll_options']['view_back_to_vote_link'] ) {
							//view_back_to_vote_link_label	
							if ( isset( $request['yop_poll_options']['view_back_to_vote_link_label'] ) ) {
								if ( '' ==  $request['yop_poll_options']['view_back_to_vote_link_label'] ) {
									$this->error = __( 'Option "View Back to Vote Link Label" is empty!', 'yop_poll' );
									return false;
								}
							}
							else {
								$this->error = __( 'Option "View Back to Vote Link Label" not found!', 'yop_poll' );
								return false;	
							}
						}
					} 
					else {
						$this->error = __( 'Option "View Back to Vote Link" is invalid! You must choose between \'yes\' or \'no\'', 'yop_poll' );
						return false;
					}
				}
				else {
					$this->error = __( 'Option "View Back to Vote Link" not found!', 'yop_poll' );
					return false;
				}

				//view_total_votes
				if ( isset( $request['yop_poll_options']['view_total_votes'] ) ) {
					if ( in_array( $request['yop_poll_options']['view_total_votes'], array('yes', 'no' ) ) ) {
						//view_total_votes
						if( 'yes' == $request['yop_poll_options']['view_total_votes'] ) {
							if ( isset( $request['yop_poll_options']['view_total_votes_label'] ) ) {
								if ( stripos( $request['yop_poll_options']['view_total_votes_label'], '%POLL-TOTAL-VOTES%' ) === false ) {
									$this->error = __('You must use %POLL-TOTAL-VOTES% to define your Total Votes label!', 'yop_poll' );	
									return false;
								}
							}
							else {
								$this->error = __('Option "Total Votes Label" not found!', 'yop_poll' );
								return false;
							}
						}
					}
					else {
						$this->error = __( 'Option "View Total Votes" is invalid! Please choose between \'yes\' or \'no\'', 'yop_poll' );
						return false;
					}
				}
				else {
					$this->error = __( 'Option "View Total Votes" not found!', 'yop_poll' );
					return false;	
				}

				//view_total_voters
				if ( isset( $request['yop_poll_options']['view_total_voters'] ) ) {
					if ( in_array( $request['yop_poll_options']['view_total_voters'], array('yes', 'no' ) ) ) {
						//view_total_votes
						if( 'yes' == $request['yop_poll_options']['view_total_voters'] ) {
							if ( isset( $request['yop_poll_options']['view_total_voters_label'] ) ) {
								if ( stripos( $request['yop_poll_options']['view_total_voters_label'], '%POLL-TOTAL-VOTERS%' ) === false ) {
									$this->error = __('You must use %POLL-TOTAL-VOTERS% to define your Total Voters label!', 'yop_poll' );	
									return false;
								}
							}
							else {
								$this->error = __('Option "Total Voters Label" not found!', 'yop_poll' );
								return false;
							}
						}
					}
					else {
						$this->error = __( 'Option "View Total Voters" is invalid! You must choose between \'yes\' or \'no\'', 'yop_poll' );
						return false;
					}
				}
				else {
					$this->error = __( 'Option "View Total Voters" not found!', 'yop_poll' );
					return false;	
				}

				//view_poll_archive_link
				if ( isset( $request['yop_poll_options']['view_poll_archive_link'] ) ) {
					if ( in_array( $request['yop_poll_options']['view_poll_archive_link'], array('yes', 'no' ) ) ) {
						if ( 'yes' == $request['yop_poll_options']['view_poll_archive_link'] ) {
							//view_poll_archive_link_label	
							if ( isset( $request['yop_poll_options']['view_poll_archive_link_label'] ) ) {
								if ( '' ==  $request['yop_poll_options']['view_poll_archive_link_label'] ) {
									$this->error = __( 'Option "View Poll Archive Link Label" is empty!', 'yop_poll' );
									return false;
								}
							}
							else {
								$this->error = __( 'Option "View Poll Archive Link Label" not found!', 'yop_poll' );
								return false;	
							}

							//poll_archive_url	
							if ( isset( $request['yop_poll_options']['poll_archive_url'] ) ) {
								if ( '' ==  $request['yop_poll_options']['poll_archive_url'] ) {
									$this->error = __( 'Option "Poll Archive URL" is empty!', 'yop_poll' );
									return false;
								}
							}
							else {
								$this->error = __( 'Option "Poll Archive URL" not found!', 'yop_poll' );
								return false;	
							}
						}
					} 
					else {
						$this->error = __( 'Option "View Poll Archive Link" Is Invalid! You must choose between \'yes\' or \'no\'', 'yop_poll' );
						return false;
					}
				}
				else {
					$this->error = __( 'Option "View Poll Archive Link" not found!', 'yop_poll' );
					return false;
				}

				//show_in_archive
				if ( isset( $request['yop_poll_options']['show_in_archive'] ) ) {
					if ( in_array( $request['yop_poll_options']['show_in_archive'], array('yes', 'no' ) ) ) {
						if ( 'yes' == $request['yop_poll_options']['show_in_archive'] ) {
							//archive_order	
							if ( isset( $request['yop_poll_options']['archive_order'] ) ) {
								if ( '' ==  $request['yop_poll_options']['archive_order'] ) {
									$this->error = __( 'Option "Archive Order" is empty!', 'yop_poll' );
									return false;
								}
							}
							else {
								$this->error = __( 'Option "Archive Order" not found!', 'yop_poll' );
								return false;	
							}
						}
					} 
					else {
						$this->error = __( 'Option "Show in Archive" is invalid! You must choose between \'yes\' or \'no\'', 'yop_poll' );
						return false;
					}
				}
				else {
					$this->error = __( 'Option "Show in Archive" not found!', 'yop_poll' );
					return false;
				}

				if( isset( $request['yop_poll_options']['template'] ) ) {
					$template	= self::get_poll_template_from_database_by_id( $request['yop_poll_options']['template'] );
					if ( ! $template ) {
						$this->error = __( 'Template not found!', 'yop_poll' );
						return false;
					}
				}
				else {
					$this->error = __( 'You must choose a template for your poll!', 'yop_poll' );
					return false;
				}

				return true;	
			}
			return false;	
		}

		public function verify_template_request_data( $request = array(), $config = null ) {
			if ( isset( $request['yop_poll_template_name'] ) && '' == trim( $request['yop_poll_template_name'] ) ) {
				$this->error = __( 'Poll Template name needed!', 'yop_poll' );
				return false;
			}
			elseif (isset( $request['yop_pol_template'] ) && '' == trim( $request['yop_pol_template'] ) ) {
				$this->error = __( 'Poll template needed!', 'yop_poll' );
				return false;
			}
			else {
				return true;
			}
			return false;
		}

		public function make_poll_template_from_request_data ( $request = array(), $config = null ) {
			$this->template['id']							= isset( $request['template_id'] ) ? trim( $request['template_id'] ) : null;
			$this->template['name']							= isset( $request['yop_poll_template_name'] ) ? trim( $request['yop_poll_template_name'] ) : null;
			$this->template['before_vote_template']			= isset( $request['yop_poll_before_vote_template'] ) ? trim( $request['yop_poll_before_vote_template'] ) : null;
			$this->template['after_vote_template']			= isset( $request['yop_poll_after_vote_template'] ) ? trim( $request['yop_poll_after_vote_template'] ) : null;
			$this->template['before_start_date_template']	= isset( $request['yop_poll_template_before_start_date'] ) ? trim( $request['yop_poll_template_before_start_date'] ) : null;
			$this->template['after_end_date_template']		= isset( $request['yop_poll_template_after_end_date'] ) ? trim( $request['yop_poll_template_after_end_date'] ) : null;
			$this->template['css']							= isset( $request['yop_poll_template_css'] ) ? trim( $request['yop_poll_template_css'] ) : null;
			$this->template['js']							= isset( $request['yop_poll_template_css'] ) ? trim( $request['yop_poll_template_js'] ) : null;
		}

		public function add_poll_template_to_database ( $request = array(), $config = null ) {
			if( $this->verify_template_request_data( $request, $config )) {
				$this->make_poll_template_from_request_data( $request, $config );
				$result = self::get_poll_template_from_database_by_name( $this->template['name'] );
				if( !isset( $result['id'] ) ) {
					$this->template['id']	= self::insert_poll_template_to_database( $this->template );
					return $this->template['id'];
				}
				else {
					$this->error = __( 'This template name allready exist! Please choose another name!', 'yop_poll' );
					return false;
				}
			}
			else {
				return false;
			}
		}

		public static function add_bans( $request ) {
			global $wpdb;
			$success	= NULL;
			$error		= NULL;

			if ( ! isset( $request['ban_poll_id'] ) ) {
				$error	= __( 'You must choose a yop poll! ');
			}
			elseif ( ! ctype_digit( $request['ban_poll_id'] ) ) {
				$error	= __( 'Invalid Yop Poll! Please try again! ');
			}
			elseif( ! in_array( $request['ban_type'], array( 'ip', 'username', 'email') ) ) {
				$error 	= __( 'You must choose a ban type!', 'yop_poll');
			}
			elseif ( '' == trim( $request['ban_value'] ) ) {
				$error 	= __( 'You must choose a ban value!', 'yop_poll');	
			}
			else {
				$ban_textarea	= nl2br( $request['ban_value'] );
				$values			= explode( '<br />', $ban_textarea );
				if( count( $values ) > 0 ) {
					foreach( $values as $value ) {
						if ( '' != trim( $value )) {
							$ban		= array(
								'poll_id' 	=> trim($request['ban_poll_id']),
								'type' 		=> trim($request['ban_type']),
								'value' 	=> trim($value)
							);
							$exist	= $wpdb->get_var(
								$wpdb->prepare(
									"
									SELECT id 
									FROM ".$wpdb->yop_poll_bans."
									WHERE poll_id in( 0, %d) AND
									(type = %s and value = %s ) 
									LIMIT 0,1
									",
									$ban['poll_id'],
									$ban['type'],
									$ban['value']
								)
							);

							if ( ! $exist ) {
								$ban = self::insert_ban_to_database( $ban );
								if ( $ban )
									$success++;
							}
						}
					}
				}
			}
			return array('success' => $success, 'error' => $error );
		}

		public function edit_poll_template_in_database ( $request = array(), $config = null ) {
			if( $this->verify_template_request_data( $request, $config )) {
				$this->make_poll_template_from_request_data( $request, $config );
				$result = self::get_poll_template_from_database_by_id( $this->template['id'] );
				if( isset( $result['id'] ) ) {
					$result = self::get_poll_template_from_database_by_name( $this->template['name'], array( $this->template['id'] ) );
					if ( !isset($result['id']) ) {
						self::update_poll_template_in_database( $this->template );
						return $this->template['id'];
					}
					else {
						$this->error = __( 'This template name already exists! Please choose another name!', 'yop_poll' );
						return false;
					}
				}
				else {
					$this->error = __( 'This poll template doesn`t exist!', 'yop_poll' );
					return false;
				}
			}
			else {
				return false;
			}	
		}

		public function make_poll_from_request_data ( $request = array(), $config = null ) {
			$this->poll['id']			= isset( $request['yop_poll_id'] ) ? trim( $request['yop_poll_id'] ) : null;
			$this->poll['name']			= isset( $request['yop_poll_name'] ) ? trim( $request['yop_poll_name'] ) : null;
			$this->poll['question']		= isset( $request['yop_poll_question'] ) ? trim( $request['yop_poll_question'] ) : null;
			$this->poll['start_date']	= isset( $request['yop_poll_options']['start_date'] ) ? trim( $request['yop_poll_options']['start_date'] ) : null;
			if( ! isset( $request['yop_poll_options']['never_expire'] ) ) {
				$this->poll['end_date'] = isset( $request['yop_poll_options']['end_date'] ) ? trim( $request['yop_poll_options']['end_date'] ) : null;
			}
			else {
				$this->poll['end_date']	= '9999-12-31 23:59:59';	
			}
			$this->poll['show_in_archive']	= isset( $request['yop_poll_options']['show_in_archive'] ) ? trim( $request['yop_poll_options']['show_in_archive'] ) : null;
			$this->poll['archive_order']	= isset( $request['yop_poll_options']['archive_order'] ) ? trim( $request['yop_poll_options']['archive_order'] ) : null;
		}

		public function make_answers_from_request_data ( $request = array(), $config = null ) {
			$this->answers = NULL;
			$answer = array(
				'id'		=> NULL,
				'poll_id'	=> $this->poll['id'],
				'answer'	=> NULL,
				'votes'		=> 0,
				'status'	=> 'active',
				'type'		=> 'default'
			);
			if ( count( $request['yop_poll_answer'] ) > 0 ) {
				foreach ( $request['yop_poll_answer'] as $answer_id => $answer_value ) {
					$answer['answer']	= $answer_value;	
					$answer['id']		= isset( $request['yop_poll_answer_ids'][ $answer_id ] ) ? $request['yop_poll_answer_ids'][ $answer_id ] : NULL;	
					$answer['name']		= $answer_id;	
					$this->answers[]	= $answer;
				}
			}
			if ( isset ( $request['yop_poll_options']['allow_other_answers'] ) ) {
				if ( 'yes' == $request['yop_poll_options']['allow_other_answers'] ) {
					$answer['answer']	= isset( $request['yop_poll_options']['other_answers_label'] ) ? $request['yop_poll_options']['other_answers_label'] : 'Other';
					$other_answer		= self::get_poll_answers( $this->poll['id'], array( 'other') );
					$answer['id']		= isset( $other_answer[0]['id'] ) ? $other_answer[0]['id'] : NULL;
					$answer['type']		= 'other';
					$this->answers[]	= $answer;
				}
			}	
		}

		public function make_custom_fields_from_request_data ( $request = array(), $config = null ) {
			$this->custom_fields = NULL;
			if( isset( $request['yop_poll_customfield'] ) ) {
				if ( count( $request['yop_poll_customfield'] ) > 0 ) {
					$custom_field = array(
						'id'			=> NULL,
						'poll_id'		=> $this->poll['id'],
						'custom_field'	=> NULL,
						'required'		=> NULL,
						'status'		=> 'active'
					);
					foreach ( $request['yop_poll_customfield'] as $customfield_id => $customfield_value ) {
						$custom_field['custom_field'] = $customfield_value;	
						$custom_field['id'] = isset( $request['yop_poll_customfield_ids'][ $customfield_id ] ) ? $request['yop_poll_customfield_ids'][ $customfield_id ] : NULL;	
						$custom_field['required'] = isset( $request['yop_poll_customfield_required'][ $customfield_id ] ) ? 'yes' : 'no';	
						$this->custom_fields[]	= $custom_field;
					}
				}	
			}
		}

		public function add_poll_to_database ( $request = array(), $config = null ) {
			if( $this->verify_request_data( $request, $config ) ) {
				$this->make_poll_from_request_data( $request, $config );
				$result = self::get_poll_from_database_by_name( $this->poll['name'] );
				if ( !isset( $result['id'] ) ) {
					//inserting poll to db
					$this->poll['id']	= self::insert_poll_to_database( $this->poll );

					//inserting poll options to db
					$poll_options		= array();
					$default_options	= get_option( 'yop_poll_options', false );


					if ( isset( $request['yop_poll_options'] ) ) {
						foreach ($request['yop_poll_options'] as $option_name => $option_value ) {
							if ( $default_options ) {
								if ( isset( $default_options[ $option_name ] ) ) {
									if ( $default_options[ $option_name ] != $option_value ) {
										$poll_options[ $option_name ] = $option_value;
									}
								}
							} 
							else
								$poll_options[ $option_name ] = $option_value;	
						}
					} 
					//this is for checkbox options
					if( ! isset( $request['yop_poll_options']['never_expire'] ) ) {
						$poll_options['never_expire']	= 'no';
					}

					//if ( count( $poll_options ) > 0 )
						update_yop_poll_meta( $this->poll['id'], 'options', $poll_options );

					//inserting answers to db
					foreach( $default_options as $option_name => $option_value ) {
						if( isset( $poll_options[ $option_name ]) ) {
							$default_options[ $option_name ] = $poll_options[ $option_name ];
						}
					}
					$this->make_answers_from_request_data( $request, $config );
					if ( count( $this->answers ) > 0 ) {
						foreach ( $this->answers as $answer ) {
							$answer_id = self::insert_answer_to_database( $answer );

							//insert poll answer options to db
							if( 'other' != $answer['type'] ) {
								if ( isset( $request['yop_poll_answer_options'][ $answer['name'] ] ) ) {
									$poll_answer_options = array();
									foreach ( $request['yop_poll_answer_options'][ $answer['name'] ] as $option_name => $option_value ) {
										if ( $default_options[ $option_name ] != $option_value ) {
											$poll_answer_options[ $option_name ] = $option_value;
										}		
									}
									//if ( count( $poll_answer_options ) > 0 )
										update_yop_poll_answer_meta( $answer_id, 'options', $poll_answer_options, true );
								}	
							}
						}
					}

					//inserting custom fields to db
					$this->make_custom_fields_from_request_data( $request, $config );
					if ( count( $this->custom_fields ) > 0 ) {
						foreach ( $this->custom_fields as $custom_field ) {
							self::insert_custom_field_to_database( $custom_field );		
						}
					}					

					return $this->poll['id'];
				}			
				else {
					$this->error = __( 'This poll already exists! Please choose another name!', 'yop_poll' );
					return false;
				}
			}
			else {
				return false;
			}
		}

		public function edit_poll_in_database ( $request = array(), $config = null ) {
			if( $this->verify_request_data( $request, $config ) ) {
				$this->make_poll_from_request_data( $request, $config );
				$result = self::get_poll_from_database_by_id( $this->poll['id'] );
				if ( isset( $result['id'] ) ) {
					//update poll in db
					self::update_poll_in_database( $this->poll );

					//update poll options in db
					$poll_options		= array();
					$default_options	= get_option( 'yop_poll_options', false );


					if ( isset( $request['yop_poll_options'] ) ) {
						foreach ($request['yop_poll_options'] as $option_name => $option_value ) {
							if ( $default_options ) {
								if( isset ( $default_options[ $option_name ] ) ) {
									if ( $default_options[ $option_name ] != $option_value ) {
										$poll_options[ $option_name ] = $option_value;
									}
								}
							} 
							else
								$poll_options[ $option_name ] = $option_value;	
						}
					} 
					//this is for checkbox options
					if( ! isset( $request['yop_poll_options']['never_expire'] ) ) {
						$poll_options['never_expire']	= 'no';
					}
					//if ( count( $poll_options ) > 0 )
						update_yop_poll_meta( $this->poll['id'], 'options', $poll_options );

					//add update answers in db
					foreach( $default_options as $option_name => $option_value ) {
						if( isset( $poll_options[ $option_name ]) ) {
							$default_options[ $option_name ] = $poll_options[ $option_name ];
						}
					}

					$this->make_answers_from_request_data( $request, $config );
					if ( count( $this->answers ) > 0 ) {
						$answer_ids_for_not_remove = array();
						$all_poll_answers = self::get_poll_answers( $this->poll['id'], array( 'default', 'other') );
						foreach ( $this->answers as $answer ) {
							if ( $answer['id'] ) {
								self::update_answer_in_database( $answer );
								$answer_id = $answer['id'];
							}
							else {
								$answer_id = self::insert_answer_to_database( $answer );
							}
							//if( 'other' != $answer['type'] )
							$answer_ids_for_not_remove[] = $answer_id;

							//insert poll answer options to db
							if( 'other' != $answer['type'] ) {
								if ( isset( $request['yop_poll_answer_options'][ $answer['name'] ] ) ) {
									$poll_answer_options = array();
									foreach ( $request['yop_poll_answer_options'][ $answer['name'] ] as $option_name => $option_value ) {
										if ( $default_options[ $option_name ] != $option_value ) {
											$poll_answer_options[ $option_name ] = $option_value;
										}		
									}
									//if ( count( $poll_answer_options ) > 0 ) 
										update_yop_poll_answer_meta( $answer_id, 'options', $poll_answer_options, true );
								}	
							}
						}
						//deleting removed answers
						if ( count( $all_poll_answers ) > 0 )
							foreach( $all_poll_answers as $answer )
								if( ! in_array( $answer['id'], $answer_ids_for_not_remove ) ) {
									self::delete_poll_answers_from_db( $answer['id'], $this->poll['id'] );
									delete_yop_poll_answer_meta( $answer['id'], 'options' ); 
								}
					}

					//update insert custom fields in db
					$this->make_custom_fields_from_request_data( $request, $config );
					if ( count( $this->custom_fields ) > 0 ) {
						$customfield_ids_for_not_remove = array();
						$all_poll_customfields = self::get_poll_customfields( $this->poll['id']);
						foreach ( $this->custom_fields as $custom_field ) {
							if ( $custom_field['id'] ) {
								self::update_custom_field_in_database( $custom_field );
								$custom_field_id = $custom_field['id'];
							}
							else {
								$custom_field_id = self::insert_custom_field_to_database( $custom_field );
							}	
							$customfield_ids_for_not_remove[] = $custom_field_id;	
						}
						//deleting removed custom_fields
						if ( count( $all_poll_customfields ) > 0 )
							foreach( $all_poll_customfields as $customfield )
								if( ! in_array( $customfield['id'], $customfield_ids_for_not_remove ) ) {
									self::delete_poll_customfields_from_db( $customfield['id'], $this->poll['id'] );
								}
					}					

					return $this->poll['id'];
				}			
				else {
					$this->error = __( 'This poll doesn`t exist!', 'yop_poll' );
					return false;
				}
			}
			else {
				return false;
			}
		}

		public function get_current_poll() {
			return $this->poll;
		}

		public static function get_poll_answers( $poll_id, $types = array( 'default' ), $order = 'id', $order_dir = '', $include_others = false, $percentages_decimals = 0 ) {
			global $wpdb;

			if( $include_others ) {
				$types	= array_diff( $types, array( 'other' ) );
			}

			$type_sql = '';
			if ( count( $types ) > 0 ) {
				$type_sql .= ' AND type in (';
				foreach ( $types as $type ) {
					$type_sql .= "'".$type."',";		
				}
				$type_sql = trim ( $type_sql, ',' );
				$type_sql .= ' ) ';
			}
			$is_votes_sort	= false;
			if ( 'votes' == $order ) {
				$order			= 'id';
				$is_votes_sort	= true;
			}
			$answers = $wpdb->get_results( 
				$wpdb -> prepare(
					"
					SELECT * 
					FROM ".$wpdb->yop_poll_answers." 
					WHERE poll_id = %d ".$type_sql."
					ORDER BY ".$order." ".$order_dir, 
					$poll_id
				),
				ARRAY_A 
			);

			if ( $include_others ) {
				$other_answer_details	= $wpdb->get_row(
					$wpdb -> prepare(
						"
						SELECT * 
						FROM ".$wpdb->yop_poll_answers." 
						WHERE poll_id = %d AND type = 'other' ",
						$poll_id
					),
					ARRAY_A	
				);

				$other_answers_values	= self::get_other_answers_votes( $other_answer_details['id'] );
				if (  count( $other_answers_values ) > 0 ) {
					if ( 'id' == $order && 'desc' == $order_dir ) 
						$interval	= range( count( $other_answers_values ) - 1, 0, -1 );
					else
						$interval	= range( 0, count( $other_answers_values ) - 1, 1 );
					for( $i = 0; $i < count( $other_answers_values ); $i++ ) {
						$answers[] = 
						array(
							'id'		=> $other_answer_details['id'],
							'poll_id'	=> $poll_id,
							'answer'	=> $other_answers_values[ $interval[ $i ] ]['other_answer_value'],
							'votes'		=> $other_answers_values[ $interval[ $i ] ]['votes'],
							'status'	=> 'active',
							'type'		=> 'other'
						);
					}
				}
				else {
					$answers[]	= $other_answer_details;
				}
			} 
			$total_votes	= self::get_sum_poll_votes( $poll_id );
			if( count( $answers ) > 0 ) {
				for( $i = 0; $i < count ( $answers ); $i++ ) {
					if( 0 == intval( $total_votes ) )
						$answers[$i]['procentes']	= 0;
					else {
						$answers[$i]['procentes']	= round( ( intval( $answers[$i]['votes']) / intval( $total_votes ) * 100 ), $percentages_decimals ) ;
						if ( 0 < $answers[$i]['procentes'] )
							$answers[$i]['procentes']	= number_format( $answers[$i]['procentes'], $percentages_decimals ); 
					}
				}
			}

			if ( $is_votes_sort ) {
				$order_dir	= ( '' == $order_dir ) ? 'asc' : $order_dir;    
				usort($answers, array( 'Yop_Poll_Model', "sort_answers_by_votes_".$order_dir."_callback" ) );
			}
			if ( $include_others ) {
				if ( 'answer' == $order ) {
					$order_dir	= ( '' == $order_dir ) ? 'asc' : $order_dir;    
					usort($answers, array( 'Yop_Poll_Model', "sort_answers_alphabetical_".$order_dir."_callback" ) );	
				}

				if ( 'rand()' == $order ) {
					$interval		= range( 0, count( $answers ) - 1, 1 );
					shuffle( $interval );
					$new_answers	= array();
					foreach ( $interval as $number ) {
						$new_answers[]	= $answers[ $number ];		
					}
					$answers	= $new_answers;
				}
			}
			return $answers;	
		}

		public static function get_count_poll_answers( $poll_id, $types = array( 'default' ), $include_others = false ) {
			global $wpdb;

			$answers_no			= 0;
			$other_answers_no	= 0;

			if( $include_others ) {
				$types	= array_diff( $types, array( 'other' ) );
			}

			$type_sql = '';
			if ( count( $types ) > 0 ) {
				$type_sql .= ' AND type in (';
				foreach ( $types as $type ) {
					$type_sql .= "'".$type."',";		
				}
				$type_sql = trim ( $type_sql, ',' );
				$type_sql .= ' ) ';
			}

			$answers_no = $wpdb->get_var( 
				$wpdb -> prepare(
					"
					SELECT count(*) 
					FROM ".$wpdb->yop_poll_answers." 
					WHERE poll_id = %d ".$type_sql,
					$poll_id
				) 
			);

			if ( $include_others ) {
				$other_answer_details	= $wpdb->get_row(
					$wpdb -> prepare(
						"
						SELECT * 
						FROM ".$wpdb->yop_poll_answers." 
						WHERE poll_id = %d AND type = 'other' ",
						$poll_id
					),
					ARRAY_A	
				);

				$other_answers_no	= count( self::get_other_answers_votes( $other_answer_details['id'] ) );
			} 

			return $answers_no  + $other_answers_no;	
		}

		public static function get_poll_answer_by_id( $answer_id ) {
			global $wpdb;
			$answer = $wpdb->get_row( 
				$wpdb -> prepare(
					"
					SELECT * 
					FROM ".$wpdb->yop_poll_answers." 
					WHERE id = %d 
					LIMIT 0,1", 
					$answer_id
				),
				ARRAY_A 
			);	
			return $answer;
		}

		public static function get_poll_customfields( $poll_id ) {
			global $wpdb;
			$result = $wpdb->get_results( 
				$wpdb -> prepare(
					"
					SELECT * 
					FROM ".$wpdb->yop_poll_custom_fields." 
					WHERE poll_id = %d
					", 
					$poll_id
				),
				ARRAY_A 
			);
			return $result;	
		}

		public static function get_poll_customfields_logs( $poll_id, $orderby = 'vote_date', $order = 'desc', $offset = 0, $per_page = 99999999, $sdate = '', $edate = '' ) {
			global $wpdb;
			$sdatesql	= '';
			$edatesql	= '';
			if ( $sdate != '' )
				$sdatesql	= $wpdb->prepare( ' AND vote_date >= %s ', $sdate.' 00:00:00 ' );
			if ( $edate != '' )
				$edatesql	= $wpdb->prepare( ' AND vote_date <= %s ', $edate.' 23:59:59 ' );
			$result = $wpdb->get_results( 
				$wpdb -> prepare(
					"
					SELECT group_concat(`custom_field_value`, '-', `custom_field_id`) as vote_log, vote_id, vote_date, user_id, id 
					FROM ".$wpdb->yop_poll_votes_custom_fields." 
					WHERE poll_id = %d ".
					$sdatesql .
					$edatesql .
					"GROUP BY vote_id
					ORDER BY " . esc_attr( $orderby ) . " " . esc_attr( $order ) . "
					LIMIT %d, %d
					", 
					$poll_id,
					$offset,
					$per_page
				),
				ARRAY_A 
			);
			return $result;	
		}

		public static function get_poll_total_customfields_logs( $poll_id, $sdate = '', $edate = '' ) {
			global $wpdb;
			$sdatesql	= '';
			$edatesql	= '';
			if ( $sdate != '' )
				$sdatesql	= $wpdb->prepare( ' AND vote_date >= %s ', $sdate.' 00:00:00 ' );
			if ( $edate != '' )
				$edatesql	= $wpdb->prepare( ' AND vote_date <= %s ', $edate.' 23:59:59 ' );
			$wpdb->query( 
				$wpdb -> prepare(
					"
					SELECT count(*) 
					FROM ".$wpdb->yop_poll_votes_custom_fields." 
					WHERE poll_id = %d ".
					$sdatesql .
					$edatesql .
					"GROUP BY vote_id
					", 
					$poll_id
				)
			);
			return $wpdb->get_var('SELECT FOUND_ROWS()');	
		}

		public static function get_poll_from_database_by_name ( $poll_name ) {
			global $wpdb;
			$result = $wpdb->get_row( 
				$wpdb -> prepare(
					"
					SELECT * 
					FROM ".$wpdb->yop_polls." 
					WHERE name = %s 
					LIMIT 0,1
					", 
					$poll_name
				),
				ARRAY_A 
			);
			return $result;	
		}

		public static function get_default_template() {
			global $wpdb;
			$result = $wpdb->get_row( 
				"
				SELECT * 
				FROM ".$wpdb->yop_poll_templates." 
				WHERE status = 'default' 
				LIMIT 0,1
				",
				ARRAY_A 
			);
			return $result;	
		}

		public static function get_poll_template_from_database_by_name ( $template_name, $exclude_ids = array() ) {
			global $wpdb;
			$exclude_sql = '';
			if ( count( $exclude_ids ) > 0 ) {
				$exclude_sql .= 'AND id NOT IN(';
				foreach ( $exclude_ids as $id ) {
					$exclude_sql .= $id.',';
				}
				$exclude_sql = trim( $exclude_sql, ',' );
				$exclude_sql .= ')';	
			}
			$result = $wpdb->get_row( 
				$wpdb -> prepare(
					"
					SELECT * 
					FROM ".$wpdb->yop_poll_templates." 
					WHERE name = %s " . $exclude_sql, 
					$template_name
				),
				ARRAY_A 
			);
			return $result;	
		}

		public static function count_poll_from_database_by_name ( $poll_name ) {
			global $wpdb;
			$result = $wpdb->get_var( 
				$wpdb -> prepare(
					"
					SELECT count(*) 
					FROM ".$wpdb->yop_polls." 
					WHERE name = %s 
					", 
					$poll_name
				)
			);
			return $result;	
		}

		public static function count_poll_from_database_like_name ( $poll_name ) {
			global $wpdb;
			$result = $wpdb->get_var( 
				$wpdb -> prepare(
					"
					SELECT count(*) 
					FROM ".$wpdb->yop_polls." 
					WHERE name like %s 
					", 
					$poll_name.'%'
				)
			);
			return $result;	
		}

		public static function get_poll_from_database_by_id ( $poll_id ) {
			global $wpdb;
			$result = $wpdb->get_row( 
				$wpdb -> prepare(
					"
					SELECT * 
					FROM ".$wpdb->yop_polls." 
					WHERE id = %d 
					LIMIT 0,1
					", 
					$poll_id
				),
				ARRAY_A 
			);
			return $result;	
		}

		private static function insert_poll_to_database ( $poll ) {
			global $wpdb;
			$wpdb->query(
				$wpdb->prepare(
					"
					INSERT INTO ".$wpdb->yop_polls."
					SET name = %s,
					question = %s,
					start_date = %s,
					end_date = %s,
					total_votes = %s,
					total_voters = %s,
					status = %s,
					date_added = now(),	
					last_modified = now(),	
					show_in_archive = %s,	
					archive_order  = %d	
					",
					$poll['name'],
					$poll['question'],
					$poll['start_date'],
					$poll['end_date'],
					$poll['total_votes'],
					$poll['total_voters'],
					$poll['status'],					 		
					$poll['show_in_archive'],					 		
					$poll['archive_order']					 		
				)
			);
			return $wpdb->insert_id;	
		}

		private static function update_poll_in_database ( $poll ) {
			global $wpdb;
			$wpdb->query(
				$wpdb->prepare(
					"
					UPDATE ".$wpdb->yop_polls."
					SET name = %s,
					question = %s,
					start_date = %s,
					end_date = %s,
					last_modified = now(),	
					show_in_archive = %s,	
					archive_order  = %d	
					WHERE
					id = %d	
					",
					$poll['name'],
					$poll['question'],
					$poll['start_date'],
					$poll['end_date'],					 		
					$poll['show_in_archive'],					 		
					$poll['archive_order'],
					$poll['id']					 		
				)
			);
		}

		private static function insert_answer_to_database ( $answer ) {
			global $wpdb;
			$wpdb->query(
				$wpdb->prepare(
					"
					INSERT INTO ".$wpdb->yop_poll_answers."
					SET poll_id = %d,
					answer = %s,
					votes = %d,
					status = %s,
					type = %s
					",
					$answer['poll_id'],
					$answer['answer'],
					$answer['votes'],
					$answer['status'],
					$answer['type']							
				)
			);
			return $wpdb->insert_id;	
		}

		private static function get_answer_from_database( $answer_id ) {
			global $wpdb;
			$result	= $wpdb->get_row(
				$wpdb->prepare(
					"
					SELECT * 
					FROM ".$wpdb->yop_poll_answers."
					WHERE id = %d
					LIMIT 0,1
					",
					$answer_id							
				), 
				ARRAY_A
			);
			return $result;	
		}

		private static function update_answer_in_database ( $answer ) {
			global $wpdb;
			$wpdb->query(
				$wpdb->prepare(
					"
					UPDATE ".$wpdb->yop_poll_answers."
					SET answer = %s
					WHERE id = %d
					",
					$answer['answer'],
					$answer['id']							
				)
			);
		}

		public static function get_archive_polls( $orderby = 'archive_order', $order = 'asc', $offset = 0, $per_page = 99999 ) {
			global $wpdb;
			$archive	= $wpdb->get_results( 
				$wpdb->prepare(
					"
					SELECT id
					FROM ".$wpdb->yop_polls." 
					WHERE
					show_in_archive	= 'yes' 
					ORDER BY " . esc_attr( $orderby ) . " " . esc_attr( $order ) . "
					LIMIT %d, %d
					",
					$offset,
					$per_page
				), 
				ARRAY_A );
			return $archive;
		}

		public static function get_current_active_poll() {
			global $wpdb;
			$current_date	= self::get_mysql_curent_date();
			return $wpdb->get_row( 
				$wpdb->prepare(
					"
					SELECT * FROM ".$wpdb->yop_polls."
					WHERE
					%s	>= start_date AND
					%s  <= end_date
					LIMIT 0,1
					", 
					$current_date,
					$current_date
				),
				ARRAY_A
			);
		}

		public static function get_yop_polls_filter_search ( $orderby = 'id', $order = 'desc', $filter = array( 'field' => NULL, 'value' => NULL, 'operator' => '=' ), $search = array( 'fields' => array(), 'value' => NULL ) ) {
			global $wpdb;
			$sql		= "SELECT * FROM ".$wpdb->yop_polls;
			$sql_filter	= '';
			$sql_search	= '';
			if ( $filter['field'] && $filter['value'] ) {
				$sql_filter	.= $wpdb -> prepare( ' `'.esc_attr( $filter['field'] ).'` '.esc_attr( $filter['operator'] ).' %s ', esc_attr( $filter['value'] ) );
			}
			if( count ( $search['fields'] ) > 0 ) {
				if ( $filter['field'] && $filter['value'] )
					$sql_search	= ' AND ';
				$sql_search	.= ' ( ';
				foreach( $search['fields'] as $field ) {
					$sql_search	.= $wpdb -> prepare( ' `'.esc_attr( $field ).'` like \'%%%s%%\' OR', $search['value'] );
				}
				$sql_search	= trim( $sql_search, 'OR' );	
				$sql_search	.= ' ) ';
			}
			if ( ($filter['field'] && $filter['value']) ||  count ( $search['fields'] ) > 0 )
				$sql	.= ' WHERE '.$sql_filter.$sql_search;
			$sql	.= ' ORDER BY '.esc_attr( $orderby ).' '.esc_attr( $order );
			return $wpdb->get_results( $sql, ARRAY_A );
		}

		public static function get_logs_filter_search ( $orderby = 'id', $order = 'desc' , $search = array( 'fields' => array(), 'value' => NULL ), $poll_id = NULL, $offset = 0, $per_page = 99999999 ) {
			global $wpdb;

			if ( 'id' == $orderby )
				$orderby	= $wpdb->yop_poll_logs . ".id";

			$sql_search	= ' ';

			if( $poll_id ) {
				$sql_search .= $wpdb->prepare( 'WHERE ' . $wpdb->yop_poll_logs . '.poll_id = %d', $poll_id );	
			}
			if( '_GUEST' == $search['value'] ) {
				if ( $poll_id )
					$sql_search	.= ' AND  ';
				else
					$sql_search	.= ' WHERE ';
				$sql_search	.= $wpdb->yop_poll_logs . '.user_id=0 ';
			}
			else {
				if( count ( $search['fields'] ) > 0 ) {
					if ( $poll_id )
						$sql_search	.= ' AND ( ';
					else
						$sql_search	.= ' WHERE (';
					foreach( $search['fields'] as $field ) {
						$sql_search	.= $wpdb -> prepare( ' '.esc_attr( $field ).' like \'%%%s%%\' OR', $search['value'] );
					}
					$sql_search	= trim( $sql_search, 'OR' );	
					$sql_search	.= ' ) ';
				}
			}

			$sql	= "SELECT 
			" . $wpdb->yop_poll_logs . ".id, 
			" . $wpdb->yop_poll_logs . ".ip, 
			" . $wpdb->yop_poll_logs . ".http_referer, 
			" . $wpdb->yop_poll_logs . ".vote_date, 
			" . $wpdb->yop_polls . ".name, 
			" . $wpdb->yop_poll_answers . ".answer, 
			IFNULL( " . $wpdb->users . ".user_nicename, '".__( '_GUEST', 'yop_poll' )."' ) as user_nicename, 
			" . $wpdb->users . ".user_email 

			FROM " . $wpdb->yop_poll_logs . " 
			LEFT JOIN (" . $wpdb->yop_polls . ',' . $wpdb->yop_poll_answers . ") 
			ON (
			" . $wpdb->yop_poll_logs . ".poll_id = " . $wpdb->yop_polls . ".id AND
			" . $wpdb->yop_poll_logs . ".answer_id = " . $wpdb->yop_poll_answers . ".id 
			)
			LEFT JOIN (" . $wpdb->users . ") 
			ON (
			" . $wpdb->yop_poll_logs . ".user_id = " . $wpdb->users . ".ID 
			)
			"; 
			$sql	.= $sql_search;
			$sql	.= ' ORDER BY '.esc_attr( $orderby ).' '.esc_attr( $order );
			$sql	.= $wpdb->prepare( ' LIMIT %d, %d', $offset, $per_page);
			return $wpdb->get_results( $sql, ARRAY_A ); 
		}

		public static function get_total_logs_filter_search ( $search = array( 'fields' => array(), 'value' => NULL ), $poll_id = NULL ) {
			global $wpdb;

			$sql_search	= ' ';

			if( $poll_id ) {
				$sql_search .= $wpdb->prepare( 'WHERE ' . $wpdb->yop_poll_logs . '.poll_id = %d', $poll_id );	
			}
			if( '_GUEST' == $search['value'] ) {
				if ( $poll_id )
					$sql_search	.= ' AND  ';
				else
					$sql_search	.= ' WHERE ';
				$sql_search	.= $wpdb->yop_poll_logs . '.user_id=0 ';
			}
			else {
				if( count ( $search['fields'] ) > 0 ) {
					if ( $poll_id )
						$sql_search	.= ' AND ( ';
					else
						$sql_search	.= ' WHERE (';
					foreach( $search['fields'] as $field ) {
						$sql_search	.= $wpdb -> prepare( ' '.esc_attr( $field ).' like \'%%%s%%\' OR', $search['value'] );
					}
					$sql_search	= trim( $sql_search, 'OR' );	
					$sql_search	.= ' ) ';
				}
			}

			$sql	= "SELECT 
			count(*)
			FROM " . $wpdb->yop_poll_logs . " 
			LEFT JOIN (" . $wpdb->yop_polls . ',' . $wpdb->yop_poll_answers . ") 
			ON (
			" . $wpdb->yop_poll_logs . ".poll_id = " . $wpdb->yop_polls . ".id AND
			" . $wpdb->yop_poll_logs . ".answer_id = " . $wpdb->yop_poll_answers . ".id 
			)
			LEFT JOIN (" . $wpdb->users . ") 
			ON (
			" . $wpdb->yop_poll_logs . ".user_id = " . $wpdb->users . ".ID 
			)
			"; 
			$sql	.= $sql_search;
			return $wpdb->get_var( $sql ); 
		} 

		public static function get_bans_filter_search ( $orderby = 'id', $order = 'desc' , $search = array( 'fields' => array(), 'value' => NULL ), $type = NULL, $poll_id = NULL, $offset = 0, $per_page = 99999999 ) {
			global $wpdb;

			if ( 'id' == $orderby )
				$orderby	= $wpdb->yop_poll_bans . ".id";

			$sql_search	= ' ';

			if( $poll_id ) {
				$sql_search .= $wpdb->prepare( 'WHERE ' . $wpdb->yop_poll_bans . '.poll_id = %d', $poll_id );	
			}
			if( $type ) {
				if ( $poll_id )
					$sql_search	.= ' AND  ';
				else
					$sql_search	.= ' WHERE ';
				$sql_search	.= $wpdb->prepare( $wpdb->yop_poll_bans . '.type= %s', $type );
			}
			if( count ( $search['fields'] ) > 0 ) {
				if ( $poll_id || $type )
					$sql_search	.= ' AND ( ';
				else
					$sql_search	.= ' WHERE (';
				foreach( $search['fields'] as $field ) {
					$sql_search	.= $wpdb -> prepare( ' '.esc_attr( $field ).' like \'%%%s%%\' OR', $search['value'] );
				}
				$sql_search	= trim( $sql_search, 'OR' );	
				$sql_search	.= ' ) ';
			}

			$sql	= "SELECT 
			" . $wpdb->yop_poll_bans . ".id, 
			" . $wpdb->yop_poll_bans . ".value, 
			" . $wpdb->yop_poll_bans . ".type, 
			IFNULL( " . $wpdb->yop_polls . ".name, '".__( 'All Yop Polls', 'yop_poll' )."' ) as name

			FROM " . $wpdb->yop_poll_bans . " 
			LEFT JOIN (" . $wpdb->yop_polls . ") 
			ON (
			" . $wpdb->yop_poll_bans . ".poll_id = " . $wpdb->yop_polls . ".id  
			)
			"; 
			$sql	.= $sql_search;
			$sql	.= ' ORDER BY '.esc_attr( $orderby ).' '.esc_attr( $order );
			$sql	.= $wpdb->prepare( ' LIMIT %d, %d', $offset, $per_page);
			return $wpdb->get_results( $sql, ARRAY_A ); 
		}

		private static function delete_poll_answers_from_db ( $answer_id, $poll_id ) {
			global $wpdb;
			$wpdb->query(
				$wpdb->prepare(
					"
					DELETE FROM ".$wpdb->yop_poll_answers."
					WHERE id = %d AND 
					poll_id = %d
					",
					$answer_id,
					$poll_id
				)
			);
		}

		private static function delete_all_poll_answer_from_db ( $poll_id ) {
			global $wpdb;
			$answers = self::get_poll_answers( $poll_id, array( 'default', 'other' ) );
			if ( $answers ) {
				foreach ( $answers as $answer ) {
					delete_yop_poll_answer_meta( $answer['id'], 'options' );
				}
			}

			$wpdb->query(
				$wpdb->prepare(
					"
					DELETE FROM ".$wpdb->yop_poll_answers."
					WHERE poll_id = %d
					",
					$poll_id
				)
			);
		}

		private static function insert_custom_field_to_database ( $custom_field ) {
			global $wpdb;
			$wpdb->query(
				$wpdb->prepare(
					"
					INSERT INTO ".$wpdb->yop_poll_custom_fields."
					SET poll_id = %d,
					custom_field = %s,
					required = %s,
					status = %s
					",
					$custom_field['poll_id'],
					$custom_field['custom_field'],
					$custom_field['required'],
					$custom_field['status']
				)
			);
			return $wpdb->insert_id;	
		}

		private static function update_custom_field_in_database ( $custom_field ) {
			global $wpdb;
			$wpdb->query(
				$wpdb->prepare(
					"
					UPDATE ".$wpdb->yop_poll_custom_fields."
					SET custom_field = %s,
					required = %s
					WHERE id = %d
					",
					$custom_field['custom_field'],
					$custom_field['required'],
					$custom_field['id']
				)
			);
		}

		private static function delete_poll_customfields_from_db ( $customfield_id, $poll_id ) {
			global $wpdb;
			$wpdb->query(
				$wpdb->prepare(
					"
					DELETE FROM ".$wpdb->yop_poll_custom_fields."
					WHERE id = %d AND 
					poll_id = %d
					",
					$customfield_id,
					$poll_id
				)
			);
		}

		private static function delete_all_poll_customfields_from_db ( $poll_id ) {
			global $wpdb;
			$wpdb->query(
				$wpdb->prepare(
					"
					DELETE FROM ".$wpdb->yop_poll_custom_fields."
					WHERE poll_id = %d
					",
					$poll_id
				)
			);
		}

		public static function delete_poll_from_db ( $poll_id ) {
			global $wpdb;

			$wpdb->query(
				$wpdb->prepare(
					'
					DELETE FROM '.$wpdb->yop_polls.'
					WHERE id = %d
					',
					$poll_id
				)
			);	

			delete_yop_poll_meta( $poll_id, 'options' );
			self::delete_all_poll_answer_from_db( $poll_id );
			self::delete_all_poll_customfields_from_db( $poll_id );
			self::delete_all_poll_logs( $poll_id );
		}

		public static function clone_poll ( $poll_id ) {
			global $wpdb;
			$poll_details = self::get_poll_from_database_by_id( $poll_id );
			$clone_number = self::count_poll_from_database_like_name( $poll_details['name']. ' - clone' );
			if ( $poll_details ) {
				$poll	= array(
					'name'			=> $poll_details['name'] . ' - clone' . ( 0 == $clone_number ? '' : $clone_number),
					'question'		=> $poll_details['question'],
					'start_date'	=> $poll_details['start_date'],
					'end_date'		=> $poll_details['end_date'],
					'total_votes'	=> 0,
					'total_voters'	=> 0,
					'status'		=> $poll_details['status'],
					'date_added'	=> NULL,
					'show_in_archive'=> $poll_details['show_in_archive'],
					'archive_order'	=> $poll_details['archive_order'] + 1
				);
				$new_poll_id = self::insert_poll_to_database( $poll );	

				$poll_options = get_yop_poll_meta( $poll_id, 'options' );
				update_yop_poll_meta( $new_poll_id, 'options', $poll_options[0] );

				$answers = self::get_poll_answers( $poll_id, array( 'default', 'other' ) );
				if ( $answers ) {
					foreach ( $answers as $answer ) {
						$answer_to_insert = array(
							'poll_id'	=> $new_poll_id,
							'answer'	=> $answer['answer'],
							'votes'		=> 0,
							'status'	=> $answer['status'],
							'type'		=> $answer['type']
						);
						$new_answer_id = self::insert_answer_to_database ( $answer_to_insert );

						if( $answer['type'] != 'other' ) {
							$answer_options = get_yop_poll_answer_meta( $answer['id'], 'options' );
							update_yop_poll_answer_meta( $new_answer_id, 'options', $answer_options[0]);
						}
					}
				}

				$custom_fields = self::get_poll_customfields( $poll_id );
				if ( $custom_fields ) {
					foreach ( $custom_fields as $custom_field ) {
						$custom_field_to_insert = array(
							'poll_id'		=> $new_poll_id,
							'custom_field'	=> $custom_field['custom_field'],
							'required'		=> $custom_field['required'],
							'status'		=> $custom_field['status']
						);
						$new_custom_field_id = self::insert_custom_field_to_database( $custom_field_to_insert );
					}
				}
			}
		}

		public static function get_yop_poll_templates_search ( $orderby = 'last_modified', $order = 'desc', $search = array( 'fields' => array(), 'value' => NULL ) ) {
			global $wpdb;
			$sql		= "SELECT * FROM ".$wpdb->yop_poll_templates;
			$sql_search	= '';
			if( count ( $search['fields'] ) > 0 ) {
				$sql_search	.= ' ( ';
				foreach( $search['fields'] as $field ) {
					$sql_search	.= $wpdb -> prepare( ' `'.$field.'` like \'%%%s%%\' OR', $search['value'] );
				}
				$sql_search	= trim( $sql_search, 'OR' );	
				$sql_search	.= ' ) ';
			}
			if ( count ( $search['fields'] ) > 0 )
				$sql	.= ' WHERE '.$sql_search;
			$sql	.= ' ORDER BY '.$orderby.' '.$order;
			return $wpdb->get_results( $sql, ARRAY_A );
		}

		public static function get_poll_template_from_database_by_id ( $template_id = 0 ) {
			global $wpdb;
			$result = $wpdb->get_row( 
				$wpdb -> prepare(
					"
					SELECT * 
					FROM ".$wpdb->yop_poll_templates." 
					WHERE id = %d 
					LIMIT 0,1
					", 
					$template_id
				),
				ARRAY_A 
			);
			return $result;	
		}

		private static function insert_poll_template_to_database ( $template ) {
			global $wpdb;
			$wpdb->query(
				$wpdb->prepare(
					"
					INSERT INTO ".$wpdb->yop_poll_templates."
					SET name = %s,
					before_vote_template = %s,
					after_vote_template = %s,
					before_start_date_template = %s,
					after_end_date_template = %s,
					css = %s,
					js = %s,
					date_added = now(), 
					last_modified = now(),
					status = %s
					",
					$template['name'],
					$template['before_vote_template'],
					$template['after_vote_template'],
					$template['before_start_date_template'],
					$template['after_end_date_template'],
					$template['css'],
					$template['js'],
					$template['status']
				)
			);
			return $wpdb->insert_id;	
		}

		private static function insert_ban_to_database ( $ban ) {
			global $wpdb;
			$wpdb->query(
				$wpdb->prepare(
					"
					INSERT INTO ".$wpdb->yop_poll_bans."
					SET poll_id = %d,
					type = %s,
					value = %s
					",
					$ban['poll_id'],
					$ban['type'],
					$ban['value']
				)
			);
			return $wpdb->insert_id;	
		}

		private static function update_poll_template_in_database ( $template ) {
			global $wpdb;
			$wpdb->query(
				$wpdb->prepare(
					"
					UPDATE ".$wpdb->yop_poll_templates."
					SET name = %s,
					before_vote_template = %s,
					after_vote_template = %s,
					before_start_date_template = %s,
					after_end_date_template = %s,
					css = %s,
					js = %s,
					last_modified = now()
					WHERE
					id = %d	
					",
					$template['name'],
					$template['before_vote_template'],
					$template['after_vote_template'],
					$template['before_start_date_template'],
					$template['after_end_date_template'],
					$template['css'],
					$template['js'],
					$template['id']					 		
				)
			);
		}

		public static function delete_poll_template_from_db ( $template_id ) {
			global $wpdb;

			$wpdb->query(
				$wpdb->prepare(
					'
					DELETE FROM '.$wpdb->yop_poll_templates.'
					WHERE id = %d
					',
					$template_id
				)
			);	
		}

		public static function delete_poll_log_from_db ( $log_id ) {
			global $wpdb;

			$wpdb->query(
				$wpdb->prepare(
					'
					DELETE FROM '.$wpdb->yop_poll_logs.'
					WHERE id = %d
					',
					$log_id
				)
			);	
		}

		public static function delete_poll_ban_from_db ( $ban_id ) {
			global $wpdb;

			$wpdb->query(
				$wpdb->prepare(
					'
					DELETE FROM '.$wpdb->yop_poll_bans.'
					WHERE id = %d
					',
					$ban_id
				)
			);	
		}

		public static function clone_poll_template ( $template_id ) {
			global $wpdb;
			$template_details = self::get_poll_template_from_database_by_id( $template_id );
			$clone_number = self::count_poll_template_from_database_like_name( $template_details['name']. ' - clone' );
			if ( $template_details ) {
				$template	= array(
					'name'							=> $template_details['name'] . ' - clone' . ( 0 == $clone_number ? '' : $clone_number),
					'before_vote_template'			=> $template_details['before_vote_template'],
					'after_vote_template'			=> $template_details['after_vote_template'],
					'before_start_date_template'	=> $template_details['before_start_date_template'],
					'after_end_date_template'		=> $template_details['after_end_date_template'],
					'css'							=> $template_details['css'],
					'js'							=> $template_details['js'],
					'status'						=> ( 'default' == $template_details['status'] ) ? 'other' : $template_details['status'],
					'date_added'					=> NULL,
					'last_modified'					=> NULL
				);
				$new_template_id = self::insert_poll_template_to_database( $template );	
			}
		}

		public static function reset_votes_for_poll( $poll_id ) {
			global $wpdb;
			$wpdb->query( $wpdb->prepare( 'UPDATE '.$wpdb->yop_polls.' SET total_votes = 0, total_voters = 0 WHERE id = %d', $poll_id ) );
			$wpdb->query( $wpdb->prepare( 'UPDATE '.$wpdb->yop_poll_answers.' SET votes = 0 WHERE poll_id = %d', $poll_id ) );
		}

		public static function count_poll_template_from_database_like_name ( $template_name ) {
			global $wpdb;
			$result = $wpdb->get_var( 
				$wpdb -> prepare(
					"
					SELECT count(*) 
					FROM ".$wpdb->yop_poll_templates." 
					WHERE name like %s 
					", 
					$template_name.'%'
				)
			);
			return $result;	
		} 

		private static function insert_vote_in_database( $answer = array() ) {
			global $wpdb;

			$wpdb->query(
				$wpdb->prepare(
					"
					INSERT INTO ".$wpdb->yop_poll_logs."
					SET
					poll_id				= %d,
					vote_id				= %s,
					answer_id			= %d,
					ip					= %s,
					user_id				= %s,
					http_referer		= %s,
					host				= %s,
					other_answer_value	= %s,
					vote_date			= now()					
					",
					$answer['poll_id'],
					$answer['vote_id'],
					$answer['answer_id'],
					$answer['ip'],
					$answer['user_id'],
					$answer['http_referer'],
					$answer['host'],
					isset( $answer['other_answer_value'] ) ? $answer['other_answer_value'] : ''
				)
			);
			return $wpdb->insert_id;
		}

		public static function delete_all_poll_logs( $poll_id ) {
			global $wpdb;
			$wpdb->query( $wpdb->prepare( "DELETE FROM ".$wpdb->yop_poll_logs." WHERE poll_id = %d ", $poll_id ) );
		}

		private static function insert_vote_custom_field_in_database( $custom_field = array() ) {
			global $wpdb;

			$wpdb->query(
				$wpdb->prepare(
					"
					INSERT INTO ".$wpdb->yop_poll_votes_custom_fields."
					SET
					poll_id				= %d,
					vote_id				= %s,
					custom_field_id		= %d,
					user_id				= %s,
					custom_field_value	= %s,
					vote_date			= now()					
					",
					$custom_field['poll_id'],
					$custom_field['vote_id'],
					$custom_field['custom_field_id'],
					$custom_field['user_id'],
					$custom_field['custom_field_value']
				)
			);
			return $wpdb->insert_id;
		} 

		private function update_poll_votes_and_voters( $votes = 0, $voters = 0 ) {
			global $wpdb;
			$wpdb->query(
				$wpdb->prepare(
					"
					UPDATE {$wpdb->yop_polls}
					SET
					total_votes = total_votes + %d,
					total_voters = total_voters + %d
					WHERE
					id = %d					
					",
					$votes,
					$voters,
					$this->poll['id']
				)
			);
		}

		private function update_answer_votes( $answer_id = 0, $votes = 0 ) {
			global $wpdb;
			$wpdb->query(
				$wpdb->prepare(
					"
					UPDATE {$wpdb->yop_poll_answers}
					SET
					votes = votes + %d
					WHERE
					id = %d					
					",
					$votes,
					$answer_id
				)
			);
		}

		public function register_vote( $request ) {
			global $current_user;
			$poll_id 			= $this->poll['id'];
			$poll_details		= $this->poll;
			$poll_options		= $this->poll_options;
			$vote_id			= uniqid('vote_id_');
			$current_date = YOP_POLL_MODEL::get_mysql_curent_date();
			if ( $this->is_allowed_to_vote() ) {
				if ( $current_date >= $poll_details['start_date'] ) {
					if ( $current_date <= $poll_details['end_date'] ) {
						if( 'closed' == $poll_details['status'] ) {
							$this->error = __( 'This poll is closed!', 'yop_poll' );
							return false;
						}	
						else {
							if ( ! $this->is_voted() ) {
								$answers	= array();
								if ( isset ( $request['yop_poll_answer'] ) ) {
									if ( 'yes' == $poll_options['allow_multiple_answers'] ) {
										if ( count( $request['yop_poll_answer'] ) <= intval( $poll_options['allow_multiple_answers_number'] ) ) {
											foreach( $request['yop_poll_answer'] as $answer_id ) {
												$answer							= array();
												$answer['poll_id']				= $poll_id;
												$answer['vote_id']				= $vote_id;
												$answer['answer_id']			= $answer_id;
												$answer['ip']					= self::get_ip();
												$answer['user_id']				= $current_user->ID;
												$answer['http_referer']			= $_SERVER['HTTP_REFERER'];
												$answer['host']					= esc_attr(@gethostbyaddr(self::get_ip()));
												$answer['other_answer_value']	= '';
												$answer_details					= self::get_poll_answer_by_id( $answer_id );
												if ( 'other' == $answer_details['type'] ) {
													if( isset( $request['yop_poll_other_answer'] ) ) {
														if ( '' != trim( $request['yop_poll_other_answer'] ) ) {
															$answer['other_answer_value']	= $request['yop_poll_other_answer'];
														}
														else {
															$this->error	= __( 'Your other answer is empty!', 'yop_poll' );
															return false;	
														}
													}
													else {
														$this->error	= __( 'Your other answer is invalid!', 'yop_poll' );
														return false;	
													}
												}
												$answers[]	= $answer;
											}
										}
										else {
											$this->error	= __( 'To many answers! Only ', 'yop_poll' ) . $poll_options['allow_multiple_answers_number'] . __(' answers allowed!', 'yop_poll' );
											return false;
										}
									}
									else {
										$answer							= array();
										$answer['poll_id']				= $poll_id;
										$answer['vote_id']				= $vote_id;
										$answer['answer_id']			= $request['yop_poll_answer'];
										$answer['ip']					= self::get_ip();
										$answer['user_id']				= $current_user->ID;
										$answer['http_referer']			= $_SERVER['HTTP_REFERER'];
										$answer['host']					= esc_attr(@gethostbyaddr(self::get_ip()));
										$answer['other_answer_value']	= '';
										$answer_details					= self::get_poll_answer_by_id( $request['yop_poll_answer'] );
										if ( 'other' == $answer_details['type'] ) {
											if( isset( $request['yop_poll_other_answer'] ) ) {
												if ( '' != trim( $request['yop_poll_other_answer'] ) ) {
													$answer['other_answer_value']	= $request['yop_poll_other_answer'];
												}
												else {
													$this->error	= __( 'Your other answer is empty!', 'yop_poll' );
													return false;	
												}
											}
											else {
												$this->error	= __( 'Your other answer is invalid!', 'yop_poll' );
												return false;	
											}
										}
										$answers[]	= $answer;
									}
									if ( count($answers) > 0 ) {
										$custom_fields		= array();
										$poll_custom_fields	= self::get_poll_customfields( $poll_id);
										if ( count( $poll_custom_fields ) > 0 ) {
											if( isset( $request['yop_poll_customfield'] ) ) {
												foreach ( $poll_custom_fields as $custom_field ) {
													if ( isset( $request['yop_poll_customfield'][ $custom_field['id'] ] ) ) {
														if ( '' == trim( $request['yop_poll_customfield'][ $custom_field['id'] ] ) &&  'yes' == $custom_field['required'] ) {
															$this->error	= __( 'Custom field ', 'yop_poll').$custom_field['custom_field'].__( ' is required! ', 'yop_poll' );
															return false;	
														}
														else {
															if (  $request['yop_poll_customfield'][ $custom_field['id'] ] != '' ) {
																$new_custom_field						=  array();
																$new_custom_field['poll_id']			= $poll_id;
																$new_custom_field['vote_id']			= $vote_id;
																$new_custom_field['custom_field_id']	= $custom_field['id'];
																$new_custom_field['user_id']			= $current_user->ID;
																$new_custom_field['custom_field_value']	= trim( $request['yop_poll_customfield'][ $custom_field['id'] ] );
																$custom_fields[]						= $new_custom_field;
															}
														}
													}
													else {
														$this->error	= __( 'Custom field ', 'yop_poll').$custom_field['custom_field'].__( ' is missing! ', 'yop_poll' );
														return false;
													}
												}
											}
											else {
												$this->error	= __('Custom fields are missing!', 'yop_poll');
												return false;
											}
										}
										$cookie_ids = '';
										$votes		= 0;
										foreach( $answers as $answer ) {
											self::insert_vote_in_database( $answer );
											$cookie_ids	.= $answer['answer_id'].',';
											$this->update_answer_votes( $answer['answer_id'], 1 );
											$votes++;
										}

										$this->update_poll_votes_and_voters( $votes, 1 );

										foreach( $custom_fields as $custom_field ) {
											self::insert_vote_custom_field_in_database( $custom_field );
										}
										$this->set_vote_cookie( trim($cookie_ids, ',' ) );
										$this->vote		= true;
										$this -> poll	= self::get_poll_from_database_by_id( $poll_id );
										return do_shortcode( $this->return_poll_html() ); 
									}
									else {
										$this->error	= __( 'No vote was registered!', 'yop_poll');
										return false;
									}
								}
								else {
									$this->error = __( 'No answer selected!', 'yop_poll' );
									return false;
								}
							}
							else {
								$this->error = __( 'You Already voted!', 'yop_poll' );
								return false;
							}
						}
					}
					else {
						$this->error = __( 'This poll is closed!', 'yop_poll' );
						return false;	
					}
				}
				else {
					$this->error = __( 'You can vote once the poll starts!', 'yop_poll' );
					return false;
				}
			}
			else {
				$this->error = __( 'You are not allowed to vote!', 'yop_poll' );
				return false;
			}
		}

		public function return_poll_css() {
			$poll_id 			= $this->poll['id'];
			if( ! $poll_id )
				return '';
			$poll_details		= $this->poll;
			$poll_options		= $this->poll_options;
			$template_id		= $poll_options['template'];
			if ( '' == $template_id ) {
				$default_template	= self::get_default_template();
				$template_id	= $default_template['id'] ? $default_template['id'] : 0;
			}
			$template_details	= self::get_poll_template_from_database_by_id( $template_id );
			$template			= $template_details['css'];
			$template			= str_ireplace( '%POLL-ID%', $poll_id, $template );
			return stripslashes( $template );			
		}

		public function return_poll_js() {
			$poll_id 			= $this->poll['id'];
			if( ! $poll_id )
				return '';
			$poll_details			= $this->poll;
			$poll_options			= $this->poll_options;
			$template_id			= $poll_options['template'];
			$display_other_answers_values	= false;
			if ( isset( $poll_options['display_other_answers_values'] ) ) {
				if ( 'yes' == $poll_options['display_other_answers_values'] )
					$display_other_answers_values	= true;
				else
					$display_other_answers_values	= false;
			}
			if ( '' == $template_id ) {
				$default_template	= self::get_default_template();
				$template_id	= $default_template['id'] ? $default_template['id'] : 0;
			}
			$answers_tabulated_cols		= 1;
			$results_tabulated_cols		= 1;
			if ( 'orizontal'	== $poll_options['display_answers'] ) { 
				$ans_no			= self::get_count_poll_answers( $poll_id, array( 'default', 'other' ) ) ;
				if( $ans_no > 0 )
					$answers_tabulated_cols	= $ans_no;	
			}
			if ( 'orizontal'	== $poll_options['display_results'] ) { 
				$ans_no			= self::get_count_poll_answers( $poll_id, array( 'default', 'other' ), $display_other_answers_values ) ;
				if( $ans_no > 0 )
					$results_tabulated_cols	= $ans_no;	
			}
			if ( 'tabulated'	== $poll_options['display_answers'] ) 
				$answers_tabulated_cols	= $poll_options['display_answers_tabulated_cols'];
			if ( 'tabulated'	== $poll_options['display_results'] ) 
				$results_tabulated_cols	= $poll_options['display_results_tabulated_cols'];
			$template_details	= self::get_poll_template_from_database_by_id( $template_id );
			$template			= $template_details['js'];
			$template			= str_ireplace( '%POLL-ID%', $poll_id, $template );
			$template			= str_ireplace( '%ANSWERS-TABULATED-COLS%', $answers_tabulated_cols, $template );
			$template			= str_ireplace( '%RESULTS-TABULATED-COLS%', $results_tabulated_cols, $template );
			return stripslashes( $template );			
		}

		public function return_poll_html( ) {
			$poll_id 			= $this->poll['id'];
			if ( ! $poll_id )
				return '';
			$poll_details		= $this->poll;
			$poll_options		= $this->poll_options;
			$template_id		= $poll_options['template'];
			if ( '' == $template_id ) {
				$default_template	= self::get_default_template();
				$template_id	= $default_template['id'] ? $default_template['id'] : 0;
			}
			$template_details	= self::get_poll_template_from_database_by_id( $template_id );
			$is_voted			= $this->is_voted();
			$current_date		= self::get_mysql_curent_date();

			if( $current_date >= $poll_details['start_date']) {
				if( $current_date <= $poll_details['end_date'] ) {
					if ( ! $is_voted ) {
						$template		= $template_details['before_vote_template'];
						if ( 'before' == $poll_options['view_results'] )
							$template	= str_ireplace( '%POLL-ANSWER-RESULT-LABEL%', $poll_options['answer_result_label'], $template );
						$template	= str_ireplace( '%POLL-VOTE-BUTTON%', '<button class="yop_poll_vote_button" id="yop_poll_vote-button-'.$poll_id.'" onclick="yop_poll_do_vote(\''.$poll_id.'\'); return false;">'.$poll_options['vote_button_label'].'</button>', $template );
					}
					else {
						$template		= $template_details['after_vote_template'];
						if ( 'after' == $poll_options['view_results'] || 'before' == $poll_options['view_results'] )
							$template	= str_ireplace( '%POLL-ANSWER-RESULT-LABEL%', $poll_options['answer_result_label'], $template );

						if( 'yes' == $poll_options['view_back_to_vote_link'] ) {
							$vote		= $this->vote;
							$this->vote	= false;
							if ( ! $this->is_voted() ) {
								$template		= str_ireplace( '%POLL-BACK-TO-VOTE-LINK%', '<a href="javascript:void(0)" class="yop_poll_back_to_vote_link" id="yop_poll_back_to_vote_link'.$poll_id.'" onClick="yop_poll_back_to_vote(\''.$poll_id.'\')">'.$poll_options['view_back_to_vote_link_label'].'</a>', $template );
							}
							$this->vote	= $vote;
						}
					}	
				}
				else {
					$template			= $template_details['after_end_date_template'];
					if ( 'after-poll-end-date' == $poll_options['view_results'] )
						$template		= str_ireplace( '%POLL-ANSWER-RESULT-LABEL%', $poll_options['answer_result_label'], $template );
				}
			} 
			else {
				$template				= $template_details['before_start_date_template'];
				if ( 'before' == $poll_options['view_results'] )
					$template			= str_ireplace( '%POLL-ANSWER-RESULT-LABEL%', $poll_options['answer_result_label'], $template );
			}

			if ( 'custom-date' == $poll_options['view_results'] ) {
				if ( $current_date >= $poll_options['view_results_start_date'] )
					$template	= str_ireplace( '%POLL-ANSWER-RESULT-LABEL%', $poll_options['answer_result_label'], $template );
			}
			$template			= stripslashes_deep( $template );
			$template			= str_ireplace( '%POLL-ID%', $poll_id, $template );
			$template			= str_ireplace( '%POLL-NAME%', esc_html( stripslashes( $poll_details['name'] ) ), $template );
			$template			= str_ireplace( '%POLL-START-DATE%', esc_html( stripslashes( $poll_details['start_date'] ) ), $template );
			$template			= str_ireplace( '%POLL-PAGE-URL%', esc_html( stripslashes( $poll_options['poll_page_url'] ) ), $template );
			if ( '9999-12-31 23:59:59' == $poll_details['end_date'] )
				$template			= str_ireplace( '%POLL-END-DATE%', 'Never Expire', $template );
			else
				$template			= str_ireplace( '%POLL-END-DATE%', esc_html( stripslashes( $poll_details['end_date'] ) ), $template );
			$template			= str_ireplace( '%POLL-QUESTION%', esc_html( stripslashes( $poll_details['question'] ) ), $template );

			if( 'yes' == $poll_options['view_results_link'] ) {
				$template		= str_ireplace( '%POLL-VIEW-RESULT-LINK%', '<a href="javascript:void(0)" class="yop_poll_result_link" id="yop_poll_result_link'.$poll_id.'" onClick="yop_poll_view_results(\''.$poll_id.'\')">'.$poll_options['view_results_link_label'].'</a>', $template );
			}

			if( 'yes' == $poll_options['view_poll_archive_link'] ) {
				$template		= str_ireplace( '%POLL-VIEW-ARCHIVE-LINK%', '<a href="'.$poll_options['poll_archive_url'].'" class="yop_poll_archive_link" id="yop_poll_archive_link_'.$poll_id.'" >'.$poll_options['view_poll_archive_link_label'].'</a>', $template );
			}
			if( 'yes' == $poll_options['view_total_voters'] ) {
				$template		= str_ireplace( '%POLL-TOTAL-VOTERS%', $poll_options['view_total_voters_label'], $template );
				$template		= str_ireplace( '%POLL-TOTAL-VOTERS%', $poll_details['total_voters'], $template );
			}
			if( 'yes' == $poll_options['view_total_votes'] ) {
				$template		= str_ireplace( '%POLL-TOTAL-VOTES%', $poll_options['view_total_votes_label'], $template );
				$template		= str_ireplace( '%POLL-TOTAL-VOTES%', $poll_details['total_votes'], $template );
			}
			$pattern			= '\[(\[?)(ANSWER_CONTAINER)\b([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
			$template			= preg_replace_callback( "/$pattern/s", array(&$this,'answer_replace_callback'), $template );
			$pattern			= '\[(\[?)(OTHER_ANSWER_CONTAINER)\b([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
			$template			= preg_replace_callback( "/$pattern/s", array(&$this,'other_answer_replace_callback'), $template );
			$pattern			= '\[(\[?)(CUSTOM_FIELD_CONTAINER)\b([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
			$template			= preg_replace_callback( "/$pattern/s", array(&$this,'customfield_replace_callback'), $template );
			$pattern			= '\[(\[?)(ANSWER_RESULT_CONTAINER)\b([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
			$template			= preg_replace_callback( "/$pattern/s", array(&$this,'answer_result_replace_callback'), $template );
			$template			= self::strip_all_tags( $template );
			$template			= '<div id="yop-poll-container-'.$poll_id.'" class="yop-poll-container"><form id="yop-poll-form-'.$poll_id.'" class="yop-poll-forms">'.$template.'</form></div>';
			$template			= '<div id="yop-poll-container-error-'.$poll_id.'" class="yop-poll-container-error"></div>'.$template;
			return $template;	
		}

		public function answer_result_replace_callback( $m ) {
			$poll_id			= $this->poll['id'];
			$poll_options		= $this->poll_options;
			$return_string		= '';
			$is_voted			= $this->is_voted();
			if ( 
				( 
					( 'before' == $poll_options['view_results'] ) || 
					( 'after' == $poll_options['view_results'] && $is_voted ) || 
					( 'custom-date' == $poll_options['view_results'] && self::get_mysql_curent_date() >= $poll_options['view_results_start_date'] ) ||
					( 'after-poll-end-date' == $poll_options['view_results'] && self::get_mysql_curent_date() >= $this -> poll['end_date'] ) 
				) && 'never' != $poll_options['view_results']
			) {
				$display_other_answers_values	= false;
				if ( 'yes' == $poll_options['display_other_answers_values'] )
					$display_other_answers_values	= true;
				else
					$display_other_answers_values	= false;

				$percentages_decimals	= 0;
				if ( isset( $poll_options['percentages_decimals'] ) )
					$percentages_decimals	= $poll_options['percentages_decimals'];
				if( isset( $poll_options['sorting_results'] ) ) {
					if( 'exact' == $poll_options['sorting_results'] ) {
						$order_dir = 'asc';
						if( isset( $poll_options['sorting_results_direction'] ) )
							$order_dir = ('asc' == $poll_options['sorting_results_direction']) ? 'asc' : 'desc';
						$answers	= self::get_poll_answers( $poll_id, array('default', 'other'), 'id', $order_dir, $display_other_answers_values, $percentages_decimals );
					}
					elseif( 'alphabetical' == $poll_options['sorting_results'] ) {
						$order_dir = 'asc';
						if( isset( $poll_options['sorting_results_direction'] ) )
							$order_dir = ('asc' == $poll_options['sorting_results_direction']) ? 'asc' : 'desc';
						$answers	= self::get_poll_answers( $poll_id, array('default', 'other'), 'answer',  $order_dir, $display_other_answers_values, $percentages_decimals );
					}
					elseif( 'random' == $poll_options['sorting_results'] ) {
						$answers	= self::get_poll_answers( $poll_id, array('default', 'other'), 'rand()', '', $display_other_answers_values, $percentages_decimals );
					}
					elseif( 'votes' == $poll_options['sorting_results'] ) {
						$order_dir = 'asc';
						if( isset( $poll_options['sorting_results_direction'] ) )
							$order_dir = ('asc' == $poll_options['sorting_results_direction']) ? 'asc' : 'desc';
						$answers		= self::get_poll_answers( $poll_id, array('default', 'other'), 'votes', $order_dir, $display_other_answers_values, $percentages_decimals );
					}
					else {
						$order_dir = 'asc';
						if( isset( $poll_options['sorting_results_direction'] ) )
							$order_dir = ('asc' == $poll_options['sorting_results_direction']) ? 'asc' : 'desc';
						$answers	= self::get_poll_answers( $poll_id, array('default', 'other'), 'id', $order_dir, $display_other_answers_values, $percentages_decimals );
					}
				}
				else {
					$order_dir = 'asc';
					if( isset( $poll_options['sorting_results_direction'] ) )
						$order_dir = ('asc' == $poll_options['sorting_results_direction']) ? 'asc' : 'desc';
					$answers	= self::get_poll_answers( $poll_id, array('default', 'other'), 'id', $order_dir, $display_other_answers_values, $percentages_decimals );
				}
				if( count( $answers ) > 0 ) {
					foreach( $answers as $answer ) {
						$poll_options = $this->poll_options;
						$answer_options = get_yop_poll_answer_meta( $answer['id'], 'options', true);
						if ( $answer_options ) {
							foreach ( $answer_options as $option_name => $option_value ) {
								if( isset( $poll_options[ $option_name ] ) ) {
									if( $option_value != $poll_options[ $option_name ] )
										$poll_options[ $option_name ] =  $option_value;
								}
								else {
									$poll_options[ $option_name ] =  $option_value;
								}	
							}
						}
						$temp_string	= str_ireplace( '%POLL-ANSWER-RESULT-VOTES%', self::display_poll_result_votes( $answer, $poll_options ), $m[5] );		
						$temp_string	= str_ireplace( '%POLL-ANSWER-RESULT-PERCENTAGES%', self::display_poll_result_percentages( $answer, $poll_options ), $temp_string );		
						$temp_string	= str_ireplace( '%POLL-ANSWER-LABEL%', esc_html( stripslashes( $answer['answer'] ) ), $temp_string );
						$temp_string	= str_ireplace( '%POLL-ANSWER-RESULT-BAR%', self::display_poll_result_bar( $poll_id, $answer['id'], $answer['procentes'], $poll_options ), $temp_string );
						$return_string	.= $temp_string;			
					}
				}
			}

			return $return_string;	
		}

		public function customfield_replace_callback( $m ) {
			$poll_id			= $this->poll['id'];
			$poll_options		= $this->poll_options;
			$return_string		= '';
			$is_voted			= $this->is_voted();
			if( ! $is_voted ) {
				$custom_fields	= self::get_poll_customfields( $poll_id );
				if( count( $custom_fields ) > 0 ) {
					foreach ($custom_fields as $custom_field) {
						$temp_string	= str_ireplace( '%POLL-CUSTOM-FIELD-LABEL%', '<label for="yop-poll-customfield-'.$custom_field['id'].'">'.esc_html( stripslashes( $custom_field['custom_field'] ) ).'</label>', $m[5] );
						$temp_string	= str_ireplace( '%POLL-CUSTOM-FIELD-TEXT-INPUT%', '<input type="text" value="" name="yop_poll_customfield['.$custom_field['id'].']" id="yop-poll-customfield-'.$custom_field['id'].'" />', $temp_string );
						$return_string	.= $temp_string;
					}
				}
			}
			return $return_string;
		}

		public function other_answer_replace_callback( $m ) {
			$poll_id			= $this->poll['id'];
			$poll_options		= $this->poll_options;
			$return_string		= '';
			$is_voted			= $this->is_voted();
			$percentages_decimals	= 0;
			if ( isset( $poll_options['percentages_decimals'] ) )
				$percentages_decimals	= $poll_options['percentages_decimals'];
			if( ! $is_voted ) {
				$multiple_answers = false;
				if( isset( $poll_options['allow_multiple_answers'] ) )
					if ( 'yes' == $poll_options['allow_multiple_answers'] )
						$multiple_answers = true;

					if( isset( $poll_options['allow_other_answers'] ) ){
					if( 'yes' == $poll_options['allow_other_answers'] ) {
						$other_answer = self::get_poll_answers( $poll_id, array( 'other') );
						if( ! $other_answer ) {
							$answer = array(
								'id'		=> NULL,
								'poll_id'	=> $poll_id,
								'answer'	=> isset( $poll_options['other_answers_label'] ) ? $poll_options['other_answers_label'] : 'Other',
								'votes'		=> 0,
								'status'	=> 'active',
								'type'		=> 'other'
							);
							$other_answer_id	= self::insert_answer_to_database( $answer );	
						}
						$other_answer = self::get_poll_answers( $poll_id, array( 'other'), 'id', '', false, $percentages_decimals );
						if( $multiple_answers )
							$temp_string	= str_ireplace( '%POLL-OTHER-ANSWER-CHECK-INPUT%', '<input type="checkbox" value="'.$other_answer[0]['id'].'" name="yop_poll_answer['.$other_answer[0]['id'].']" id="yop-poll-answer-'.$other_answer[0]['id'].'" />', $m[5] );			
						else 
							$temp_string	= str_ireplace( '%POLL-OTHER-ANSWER-CHECK-INPUT%', '<input type="radio" value="'.$other_answer[0]['id'].'" name="yop_poll_answer" id="yop-poll-answer-'.$other_answer[0]['id'].'" />', $m[5] );
						$temp_string	= str_ireplace( '%POLL-OTHER-ANSWER-LABEL%', '<label for="yop-poll-answer-'.$other_answer[0]['id'].'">'.esc_html( stripslashes( $other_answer[0]['answer'] ) ).'</label>', $temp_string );
						$temp_string	= str_ireplace( '%POLL-OTHER-ANSWER-TEXT-INPUT%', '<label><input onclick="document.getElementById(\'yop-poll-answer-'.$other_answer[0]['id'].'\').checked=true;" type="text" value="" name="yop_poll_other_answer" id="yop-poll-other-answer-'.$other_answer[0]['id'].'" /></label>', $temp_string );
						if ( 
							( 'before' == $poll_options['view_results'] ) || 
							( 'after' == $poll_options['view_results'] && $is_voted ) || 
							( 'custom-date' == $poll_options['view_results'] && self::get_mysql_curent_date() >= $poll_options['view_results_start_date'] ) ||
							( self::get_mysql_curent_date() >= $this -> poll['end_date'] ) 
						) {
							$temp_string	= str_ireplace( '%POLL-ANSWER-RESULT-BAR%', self::display_poll_result_bar( $poll_id, $other_answer[0]['id'], $other_answer[0]['procentes'], $poll_options ), $temp_string );
							$temp_string	= str_ireplace( '%POLL-OTHER-ANSWER-RESULT-BAR%', self::display_poll_result_bar( $poll_id, $other_answer[0]['id'], $other_answer[0]['procentes'], $poll_options ), $temp_string );
							$temp_string	= str_ireplace( '%POLL-ANSWER-RESULT-VOTES%', self::display_poll_result_votes( $other_answer[0], $poll_options ), $temp_string );
							$temp_string	= str_ireplace( '%POLL-OTHER-ANSWER-RESULT-VOTES%', self::display_poll_result_votes( $other_answer[0], $poll_options ), $temp_string );
							$temp_string	= str_ireplace( '%POLL-ANSWER-RESULT-PERCENTAGES%', self::display_poll_result_percentages( $other_answer[0], $poll_options ), $temp_string );
							$temp_string	= str_ireplace( '%POLL-OTHER-ANSWER-RESULT-PERCENTAGES%', self::display_poll_result_percentages( $other_answer[0], $poll_options ), $temp_string );
						}
						$return_string	.= $temp_string;
					}
				}
			}
			return $return_string;
		}

		public function answer_replace_callback( $m ) {
			$poll_id			= $this->poll['id'];
			$poll_options		= $this->poll_options;
			$return_string		= '';
			$is_voted			= $this->is_voted();
			$percentages_decimals	= 0;
			if ( isset( $poll_options['percentages_decimals'] ) )
				$percentages_decimals	= $poll_options['percentages_decimals'];
			if( ! $is_voted ) {
				if( isset( $poll_options['sorting_answers'] ) ) {
					if( 'exact' == $poll_options['sorting_answers'] ) {
						$order_dir = 'asc';
						if( isset( $poll_options['sorting_answers_direction'] ) )
							$order_dir = ('asc' == $poll_options['sorting_answers_direction']) ? 'asc' : 'desc';
						$answers	= self::get_poll_answers( $poll_id, array( 'default'), 'id', $order_dir, false, $percentages_decimals );
					}
					elseif( 'alphabetical' == $poll_options['sorting_answers'] ) {
						$order_dir = 'asc';
						if( isset( $poll_options['sorting_answers_direction'] ) )
							$order_dir = ('asc' == $poll_options['sorting_answers_direction']) ? 'asc' : 'desc';
						$answers	= self::get_poll_answers( $poll_id, array('default'), 'answer',  $order_dir, false, $percentages_decimals );
					}
					elseif( 'random' == $poll_options['sorting_answers'] ) {
						$answers	= self::get_poll_answers( $poll_id, array('default'), 'rand()', '', false, $percentages_decimals );
					}
					elseif( 'votes' == $poll_options['sorting_answers'] ) {
						$order_dir = 'asc';
						if( isset( $poll_options['sorting_answers_direction'] ) )
							$order_dir = ('asc' == $poll_options['sorting_answers_direction']) ? 'asc' : 'desc';
						$answers		= self::get_poll_answers( $poll_id, array('default'), 'votes', $order_dir, '', $percentages_decimals );
					}
					else {
						$order_dir = 'asc';
						if( isset( $poll_options['sorting_answers_direction'] ) )
							$order_dir = ('asc' == $poll_options['sorting_answers_direction']) ? 'asc' : 'desc';
						$answers	= self::get_poll_answers( $poll_id, array( 'default'), 'id', $order_dir, false, $percentages_decimals );
					}
				}
				else {
					$order_dir = 'asc';
					if( isset( $poll_options['sorting_answers_direction'] ) )
						$order_dir = ('asc' == $poll_options['sorting_answers_direction']) ? 'asc' : 'desc';
					$answers	= self::get_poll_answers( $poll_id, array( 'default'), 'id', $order_dir, false, $percentages_decimals );
				}
				$multiple_answers = false;
				if( isset( $poll_options['allow_multiple_answers'] ) )
					if ( 'yes' == $poll_options['allow_multiple_answers'] )
						$multiple_answers = true;	
					if( count( $answers ) > 0 ) {
					foreach( $answers as $answer ) {
						$poll_options = $this->poll_options;
						$answer_options = get_yop_poll_answer_meta( $answer['id'], 'options', true);
						if ( $answer_options ) {
							foreach ( $answer_options as $option_name => $option_value ) {
								if( isset( $poll_options[ $option_name ] ) ) {
									if( $option_value != $poll_options[ $option_name ] )
										$poll_options[ $option_name ] =  $option_value;
								}
								else {
									$poll_options[ $option_name ] =  $option_value;
								}	
							}
						}
						if ( $multiple_answers )
							$temp_string	= str_ireplace( '%POLL-ANSWER-CHECK-INPUT%', '<input type="checkbox" value="'.$answer['id'].'" name="yop_poll_answer['.$answer['id'].']" id="yop-poll-answer-'.$answer['id'].'" />', $m[5] );			
						else 
							$temp_string	= str_ireplace( '%POLL-ANSWER-CHECK-INPUT%', '<input type="radio" value="'.$answer['id'].'" name="yop_poll_answer" id="yop-poll-answer-'.$answer['id'].'" />', $m[5] );
						$temp_string	= str_ireplace( '%POLL-ANSWER-LABEL%', '<label for="yop-poll-answer-'.$answer['id'].'">'.esc_html( stripslashes( $answer['answer'] ) ).'</label>', $temp_string );
						if ( 
							( 'before' == $poll_options['view_results'] ) || 
							( 'after' == $poll_options['view_results'] && $is_voted ) || 
							( 'custom-date' == $poll_options['view_results'] && self::get_mysql_curent_date() >= $poll_options['view_results_start_date'] ) ||
							( self::get_mysql_curent_date() >= $this -> poll['end_date'] ) 
						) {
							$temp_string	= str_ireplace( '%POLL-ANSWER-RESULT-BAR%', self::display_poll_result_bar( $poll_id, $answer['id'], $answer['procentes'], $poll_options ), $temp_string );
							$temp_string	= str_ireplace( '%POLL-ANSWER-RESULT-VOTES%', self::display_poll_result_votes( $answer, $poll_options ), $temp_string );
							$temp_string	= str_ireplace( '%POLL-ANSWER-RESULT-PERCENTAGES%', self::display_poll_result_percentages( $answer, $poll_options ), $temp_string );
						}
						$return_string	.= $temp_string;			
					}
				}
			}
			return $return_string;
		}

		public static function get_answer_votes_from_logs( $answer_id ) {
			global $wpdb;
			$sql = $wpdb->prepare(
				"
				SELECT count(*) as votes
				FROM ".$wpdb->yop_poll_logs."
				WHERE answer_id = %d
				",
				$answer_id
			);
			return $wpdb->get_var( $sql );
		}

		public static function get_other_answers_votes( $answer_id, $offset = 0, $per_page = 99999999 ) {
			global $wpdb;
			$sql = $wpdb->prepare(
				"
				SELECT count(*) as votes, other_answer_value
				FROM ".$wpdb->yop_poll_logs."
				WHERE answer_id = %d
				GROUP BY other_answer_value
				ORDER BY id
				LIMIT %d, %d
				",
				$answer_id, 
				$offset, 
				$per_page
			);
			return $wpdb->get_results( $sql, ARRAY_A );
		}

		public static function get_poll_votes_from_logs( $poll_id ) {
			global $wpdb;
			$sql = $wpdb->prepare(
				"
				SELECT count(*) as votes,
				answer_id
				FROM ".$wpdb->yop_poll_logs."
				WHERE poll_id = %d
				GROUP BY answer_id
				",
				$poll_id
			);
			return $wpdb->get_results( $sql, ARRAY_A );
		}

		public static function get_poll_votes( $poll_id ) {
			global $wpdb;
			$sql = $wpdb->prepare(
				"
				SELECT votes,
				id as answer_id
				FROM ".$wpdb->yop_poll_answers."
				WHERE poll_id = %d
				",
				$poll_id
			);
			return $wpdb->get_results( $sql, ARRAY_A );
		}

		public static function get_sum_poll_votes_from_logs( $poll_id ) {
			global $wpdb;
			$sql = $wpdb->prepare(
				"
				SELECT count(*) as votes
				FROM ".$wpdb->yop_poll_logs."
				WHERE poll_id = %d
				",
				$poll_id
			);
			return $wpdb->get_var( $sql );
		}

		public static function get_sum_poll_votes( $poll_id ) {
			global $wpdb;
			$sql = $wpdb->prepare(
				"
				SELECT sum( votes ) as votes
				FROM ".$wpdb->yop_poll_answers."
				WHERE poll_id = %d
				",
				$poll_id
			);
			return $wpdb->get_var( $sql );
		}

		public static function sort_answers_alphabetical_asc_callback( $a, $b ) {
			$cmp	= strcmp( $a['answer'], $b['answer'] );
			if (  $cmp == 0 )
				return 0;
			return ( $cmp < 0 ) ? -1 : 1;
		}

		public static function sort_answers_alphabetical_desc_callback( $a, $b ) {
			$cmp	= strcmp( $a['answer'], $b['answer'] );
			if (  $cmp == 0 )
				return 0;
			return ( $cmp < 0 ) ? 1 : -1;
		}

		public static function sort_answers_by_votes_asc_callback( $a, $b ) {
			if ( intval( $a['votes'] ) == intval( $b['votes'] ) )
				return 0;
			return ( intval( $a['votes'] ) < intval( $b['votes'] ) ) ? -1 : 1;
		}

		public static function sort_answers_by_votes_desc_callback( $a, $b ) {
			if ( intval( $a['votes'] ) == intval( $b['votes'] ) )
				return 0;
			return ( intval( $a['votes'] ) < intval( $b['votes'] ) ) ? 1 : -1;
		}

		public static function display_poll_result_bar( $poll_id = 0, $answer_id = 0, $procent = 0, $poll_options = array() ) {

			$result_bar	= '<div style="'.
			'width:'.$procent.'%; ';
			if ( 'no' == $poll_options['use_template_bar'] ) {
				$result_bar	.=	'height:'.$poll_options['bar_height'].'px; '.
				'background-color:#'.$poll_options['bar_background'].'; '.
				'border-style:'.$poll_options['bar_border_style'].'; '.
				'border-width:'.$poll_options['bar_border_width'].'px; '.
				'border-color:#'.$poll_options['bar_border_color'].'; ';
			}
			$result_bar	.= '" '.
			'id="yop-poll-result-bar-'.$answer_id.'" '.
			'class="yop-poll-result-bar-'.$poll_id.'"'.
			'>'.
			'</div>';
			return $result_bar;  
		}

		private static function display_poll_result_votes ( $answer = array(), $poll_options = array() ) {
			if ( 'votes-number' == $poll_options['view_results_type'] || 'votes-number-and-percentages' == $poll_options['view_results_type'] )	
				if ( '1' == $answer['votes'] )		
					return $answer['votes'].' '.$poll_options['singular_answer_result_votes_number_label'];
				else
					return $answer['votes'].' '.$poll_options['plural_answer_result_votes_number_label'];
		}

		private static function display_poll_result_percentages ( $answer = array(), $poll_options = array() ) {
			if ( 'percentages' == $poll_options['view_results_type'] || 'votes-number-and-percentages' == $poll_options['view_results_type'] )				
				return $answer['procentes'].'%';
			else
				return '';
		}

		public static function strip_all_tags( $template ) {

			$tags	= array(
				'%POLL-VIEW-ARCHIVE-LINK%',
				'%POLL-PAGE-URL%',
				'%POLL-VOTE-BUTTON%',
				'%POLL-START-DATE%',
				'%POLL-END-DATE%',
				'%POLL-ANSWER-RESULT-LABEL%',
				'%POLL-BACK-TO-VOTE-LINK%',
				'%POLL-VIEW-RESULT-LINK%',
				'%POLL-TOTAL-VOTERS%',
				'%POLL-TOTAL-VOTES%',
				'%POLL-ID%',
				'%POLL-NAME%',
				'%POLL-QUESTION%',
				'%POLL-ANSWER-RESULT-VOTES%',
				'%POLL-ANSWER-RESULT-PERCENTAGES%',
				'%POLL-ANSWER-RESULT-LABEL%',
				'%POLL-ANSWER-LABEL%',
				'%POLL-ANSWER-RESULT-BAR%',
				'%POLL-CUSTOM-FIELD-LABEL%',
				'%POLL-CUSTOM-FIELD-TEXT-INPUT%',
				'%POLL-OTHER-ANSWER-CHECK-INPUT%',
				'%POLL-OTHER-ANSWER-LABEL%',
				'%POLL-OTHER-ANSWER-TEXT-INPUT%',
				'%POLL-ANSWER-RESULT%',
				'%POLL-OTHER-ANSWER-RESULT%',
				'%POLL-ANSWER-RESULT-VOTES%',
				'%POLL-OTHER-ANSWER-RESULT-VOTES%',
				'%POLL-ANSWER-RESULT-PERCENTAGES%',
				'%POLL-OTHER-ANSWER-RESULT-PERCENTAGES%',
				'%POLL-ANSWER-CHECK-INPUT%',
				'%POLL-ANSWER-LABEL%',
				'%POLL-ANSWER-RESULT%'
			);

			foreach( $tags as $tag )
				$template	= str_ireplace( $tag, '', $template );		
			return $template;
		}

		private function is_voted() {
			if ( $this->vote )
				return true;
			if ( isset( $this->poll_options['blocking_voters'] ) ) {
				switch ( $this->poll_options['blocking_voters'] ) {
					case 'ip' :
						return $this->is_voted_ip();
						break;
					case 'cookie':
						return $this->is_voted_cookie();
						break;
					case 'username':
						return $this->is_voted_username();
						break;
					case 'cookie-ip':
						if ( $this->is_voted_cookie() )
							return true;
						else 
							return $this->is_voted_ip();
						break;
					case 'dont-block':
						return false;
						break;	
				}
			}
			return true;
		}

		private function is_ban( ) {
			global $wpdb, $current_user;
			$ip		= self::get_ip();
			$sql	= $wpdb->prepare(
				"
				SELECT id 
				FROM ".$wpdb->yop_poll_bans."
				WHERE poll_id in( 0, %d) AND
				(
				(type = 'ip' and value = %s ) OR
				(type = 'username' and value = %s ) OR 
				(type = 'email' and value = %s ) 
				)
				LIMIT 0,1
				",
				$this->poll['id'],
				$ip,
				$current_user->data->user_login,
				$current_user->data->user_email
			);
			return	$wpdb->get_var( $sql );			
		}

		private function is_voted_ip() {
			global $wpdb;
			$unit	= 'DAY';
			if ( isset( $this->poll_options['blocking_voters_interval_unit'] ) ) {
				switch ( $this->poll_options['blocking_voters_interval_unit'] ) {
					case 'seconds' :
						$unit = 'SECOND';
						break;
					case 'minutes' :
						$unit = 'MINUTE';
						break;
					case 'hours' :
						$unit = 'HOUR';
						break;
					case 'days' :
						$unit = 'DAY';
						break;
				}
			}
			$value	= 30;
			if ( isset( $this->poll_options['blocking_voters_interval_value'] ) ) {
				$value	= $this->poll_options['blocking_voters_interval_value'];
			}
			$ip	= self::get_ip();
			$log_id	= $wpdb->get_var( 
				$wpdb->prepare(
					"
					SELECT id 
					FROM ".$wpdb->yop_poll_logs."
					WHERE poll_id = %d AND 
					ip = %s AND
					vote_date >= DATE_ADD( NOW(), INTERVAL -%d ".$unit.")
					",
					$this->poll['id'],
					$ip,
					$value
				) 
			);

			return $log_id;
		}

		private function is_voted_cookie() {
			if ( isset( $_COOKIE[ 'yop_poll_voted_'.$this->poll['id'] ] ) )
				return true;
			return false;
		}

		private function set_vote_cookie( $answer_ids = '0') {
			$expire_cookie	= 0;
			$value			= 30;
			if ( isset( $this->poll_options['blocking_voters_interval_value'] ) )
				$value		= $this->poll_options['blocking_voters_interval_value'];
			$unit			= 'days';
			if ( isset( $this->poll_options['blocking_voters_interval_unit'] ) )
				$unit		= $this->poll_options['blocking_voters_interval_unit'];	

			switch ( $unit ) {
				case 'seconds' :
					$expire_cookie	= time() + $value;
					break;
				case 'minutes' :
					$expire_cookie	= time() + ( 60 * $value );
					break;
				case 'hours' :
					$expire_cookie	= time() + ( 60 * 60 * $value );
					break;
				case 'days' :
					$expire_cookie	= time() + ( 60 * 60 * 24 * $value );
					break;
			}
			setcookie( 'yop_poll_voted_'.$this->poll['id'], $answer_ids , $expire_cookie, COOKIEPATH, COOKIE_DOMAIN, false); 
		}

		private function is_voted_username() {
			global $current_user, $wpdb;

			//user is guest
			if ( ! is_user_logged_in() ) {
				return $this->is_voted_ip();
			}

			$unit	= 'DAY';
			if ( isset( $this->poll_options['blocking_voters_interval_unit'] ) ) {
				switch ( $this->poll_options['blocking_voters_interval_unit'] ) {
					case 'seconds' :
						$unit = 'SECOND';
						break;
					case 'minutes' :
						$unit = 'MINUTE';
						break;
					case 'hours' :
						$unit = 'HOUR';
						break;
					case 'days' :
						$unit = 'DAY';
						break;
				}
			}
			$value	= 30;
			if ( isset( $this->poll_options['blocking_voters_interval_value'] ) ) {
				$value	= $this->poll_options['blocking_voters_interval_value'];
			}
			$ip	= self::get_ip();
			$log_id	= $wpdb->get_var( 
				$wpdb->prepare(
					"
					SELECT id 
					FROM ".$wpdb->yop_poll_logs."
					WHERE poll_id = %d AND 
					user_id = %s AND
					vote_date >= DATE_ADD( NOW(), INTERVAL -%d ".$unit.")
					",
					$this->poll['id'],
					$current_user->ID,
					$value
				) 
			);

			return $log_id;

		} 

		public static function get_mysql_curent_date() {
			global $wpdb;
			return $wpdb->get_var("SELECT NOW() ");
		}

		private function is_allowed_to_vote() {
			global $current_user;
			if( self::is_ban() )
				return false;
			if ( isset( $this->poll_options['vote_permisions'] ) ) {
				switch ( $this->poll_options['vote_permisions'] ) {
					case 'quest-only':
						if ( $current_user->ID > 0 ) {
							return false;
						} 
						return true;
						break;
					case 'registered-only':
						if ( $current_user->ID > 0 ) {
							return true;
						} 
						return false;
						break;
					default :
						return true;
				}
			}
			return true;
		}

		public static function get_ip() {
			$REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
			if ( ! empty( $_SERVER['X_FORWARDED_FOR'] ) ) {
				$X_FORWARDED_FOR = explode(',', $_SERVER['X_FORWARDED_FOR'] );
				if ( ! empty( $X_FORWARDED_FOR ) ) {
					$REMOTE_ADDR = trim( $X_FORWARDED_FOR[0] );
				}
			}
			elseif ( ! empty($_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
				$HTTP_X_FORWARDED_FOR= explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
				if ( ! empty($HTTP_X_FORWARDED_FOR ) ) {
					$REMOTE_ADDR = trim($HTTP_X_FORWARDED_FOR[0]);
				}
			}
			return preg_replace('/[^0-9a-f:\., ]/si', '', $REMOTE_ADDR);
		}
}