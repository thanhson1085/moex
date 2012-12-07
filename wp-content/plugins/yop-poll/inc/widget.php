<?php 
	class Yop_Poll_Widget extends WP_Widget {
		//constructor
		function Yop_Poll_Widget() {
			$widget_options	= array(
				'classname'	=> 'Yop Poll Widget',
				'description'	=> 'Yop Poll Polls'
			);
			parent::WP_Widget('yop_poll_widget', 'Yop Polls', $widget_options );
		}

		function widget( $args, $instance ) {
			extract ( $args, EXTR_SKIP );
			$title		= ( $instance['title'] ) ? esc_attr( $instance['title'] ) : __( 'Yop Poll Widget', 'yop_poll' );
			$poll_id	= ( $instance['poll_id'] ) ? intval( $instance['poll_id'] ) : -1;
			if ( -99 == $poll_id )
				return '';
			require_once( YOP_POLL_INC.'/yop_poll_model.php');
			$yop_poll_model		= new YOP_POLL_MODEL( $poll_id );
			$poll_id			= $yop_poll_model->poll['id'];
			$template			= $yop_poll_model->return_poll_html( );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_style( 'yop-poll-user-defined_'.$poll_id, wp_nonce_url( add_query_arg( array( 'id' => $poll_id ), admin_url('admin-ajax.php', (is_ssl() ? 'https' : 'http')).'?action=yop_poll_load_css' ), 'yop-poll-public-css' ), array(), YOP_POLL_VERSION);
			wp_enqueue_script( 'yop-poll-user-defined_'.$poll_id, wp_nonce_url( add_query_arg( array( 'id' => $poll_id ), admin_url('admin-ajax.php', (is_ssl() ? 'https' : 'http')).'?action=yop_poll_load_js' ), 'yop-poll-public-js' ), array( 'jquery' ), YOP_POLL_VERSION, true);
			wp_enqueue_script( 'yop-poll-public_'.$poll_id, YOP_POLL_URL.'/js/yop-poll-public.js', array(), YOP_POLL_VERSION );
			$yop_poll_public_config = array(
				'ajax' => array(
					'url'					=> admin_url('admin-ajax.php', (is_ssl() ? 'https' : 'http')),
					'vote_action' 			=> 'yop_poll_do_vote',
					'view_results_action'	=> 'yop_poll_view_results',
					'back_to_vote_action'	=> 'yop_poll_back_to_vote'					
				)
			);
			wp_localize_script( 'yop-poll-public_'.$poll_id, 'yop_poll_public_config', $yop_poll_public_config );
			echo $before_widget;
			echo $before_title . $title . $after_title;
			echo do_shortcode($template);
			echo $after_widget;
		}

		function update( $new_instance, $old_instance ) {
			var_dump( $new_instance );
			if ( ! isset( $new_instance['doSave'] ) )
				return false;
			if ( 'yes' != $new_instance['doSave'] )
				return false;
			$instance				= $old_instance;
			$instance['title']		= strip_tags($new_instance['title']);
			$instance['poll_id']	= intval($new_instance['poll_id']);
			return $instance;
		}

		function form( $instance ) {
			$instance 	= wp_parse_args( (array) $instance, array('title' => __('Yop Polls', 'yop_poll'), 'poll_id' => -99) );
			$title		= esc_attr( $instance['title'] );
			$poll_id	= intval( $instance['poll_id'] );
			global $wpdb;
			require_once( YOP_POLL_INC.'/yop_poll_model.php');
			$yop_polls	= Yop_Poll_Model::get_yop_polls_filter_search( 'id', 'asc' );
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">
				<span>Title:</span>
				<input id="<?php echo $this->get_field_id('title'); ?>"
					name="<?php echo $this->get_field_name('title'); ?>"
					value="<?php echo $title ?>" />
			</label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('poll_id'); ?>">
				<span>Poll to Display:</span>
				<select id="<?php echo $this->get_field_id('poll_id'); ?>" name="<?php echo $this->get_field_name('poll_id'); ?>" class="widefat">
					<option value="-99"<?php selected(-99, $poll_id); ?>><?php _e('Do NOT Display Poll (Disable)', 'yop-poll'); ?></option>
					<option value="-3"<?php selected(-3, $poll_id); ?>><?php _e('Display Random Poll', 'yop-poll'); ?></option>
					<option value="-2"<?php selected(-2, $poll_id); ?>><?php _e('Display Latest Poll', 'yop-poll'); ?></option>
					<option value="-1"<?php selected(-1, $poll_id); ?>><?php _e('Display Current Active Poll', 'yop-poll'); ?></option>
					<?php
						if( count( $yop_polls ) > 0 ) {
							foreach( $yop_polls as $poll ) {
							?>
							<option value="<?php echo $poll['id']; ?>"<?php selected($poll['id'], $poll_id); ?>><?php echo esc_attr( $poll['name'] ); ?></option>
							<?php	
							}
						} 
					?>
				</select>
			</label>
		</p>
		<input type="hidden" id="<?php echo $this->get_field_id('doSave'); ?>" name="<?php echo $this->get_field_name('doSave'); ?>" value="yes" />
		<?php
		}
}