<?php
class BSKZohoCampaignsWidget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			'bsk_campaigns_widget', // Base ID
			'BSK Zoho Campaigns Widget', // Name
			array( 'description' => __( 'Use this widget to show the mail address field in widget. There use may subscribe and mail address will be post to Zoho Campaigns\' mailing list. You should specify the list key in plugin settings page first', 'text_domain' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		$token = get_option('bsk_campaigns_zoho_authtoken', '');
		$list_key = get_option('bsk_campaigns_zoho_mailing_list_key', '');
		if (!$token || !$list_key){
			return;
		}
		
		$title = $instance['bsk_campaigns_widget_title'];
					

		echo $args['before_widget']."\n";
		if ($title){
			echo $args['before_title'].$title.$args['after_title'];
		}
		
		$current_url = home_url(add_query_arg(array(),$wp->request));
		$token = get_option('bsk_campaigns_zoho_authtoken', '');
		$list_key = get_option('bsk_campaigns_zoho_mailing_list_key', '');
		
		if ($token && $list_key){
			echo 
				'<form method="post" id="bsk_campaigns_widget_form_id">
				 	<div>
						<input type="text" value="" name="bsk_campaigns_widget_email" id="bsk_campaigns_widget_email_id" placeholder="enter your email"/>
						<input type="submit" id="bsk_campaigns_submit" value="Submit" />
				 	</div>
				 	<input type="hidden" value="widget_submit" name="bsk_campaigns_action" />
				 </form>';
		}else{
			echo '<p>Please senter Zoho Campaigns authtoken and mailing list key first.</p>';
		}
		
		echo $args['after_widget'];
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$title = $new_instance['bsk_campaigns_widget_title'];
		$title = trim($title, ',');
		
		$instance['bsk_campaigns_widget_title'] = $title;
		
		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
	
		$title = $instance['bsk_campaigns_widget_title'];
		$title_field_name = $this->get_field_name( 'bsk_campaigns_widget_title' );
		$title_field_id = $this->get_field_id( 'wp_cpmw_hidden_saved_pages' );

		$token = get_option('bsk_campaigns_zoho_authtoken', '');
		$list_key = get_option('bsk_campaigns_zoho_mailing_list_key', '');
		
		if ($token && $list_key){
			echo '<p>
					<label for="'.$title_field_name.'">Title:
						<input class="widefat" id="'.$title_field_id.'" name="'.$title_field_name.'" type="text" value="'.esc_attr($title).'" />
					</label>
				  </p>';
		}else{
			echo '<p>Please senter Zoho Campaigns authtoken and mailing list key first.</p>';
		}
	}
}