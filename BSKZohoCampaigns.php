<?php

/*
Plugin Name: BSK Zoho Campaigns
Plugin URI: http://www.bannersky.com
Description: The plugin may hlep you add contacts to Zoho Campaigns.
Version: 1.0.0
Author: BannerSky
Author URI: http://www.bannersky.com

------------------------------------------------------------------------

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, 
or any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*/

class BSKZohoCampaigns{

	public function __construct() {
	
		if ( is_admin() ) {
			add_action( 'admin_menu', array($this, 'bsk_campaigns_plugin_settings') );
			add_action( 'admin_init', array($this, 'bsk_campaigns_register_settings') );
		}
		
		add_action('wp_print_scripts', array(&$this, 'bsk_campaigns_enqueue_scripts'));
		
		register_uninstall_hook( __FILE__, 'BSKZohoCampaigns::bsk_campaigns_deinstall' );
		register_activation_hook( __FILE__, array($this, 'bsk_campaigns_activate') );
		register_deactivation_hook( __FILE__, array($this, 'bsk_campaigns_deactivate') );
		
		// register widget
		add_action( 'widgets_init', array($this, 'bsk_campaigns_register_widget') );
		add_action( 'init', array($this, 'bsk_campaigns_post_action') );
		add_action( 'bsk_campaigns_widget_submit', array($this, 'bsk_campaigns_widget_submit') );
	}
	
	function bsk_campaigns_activate(){
		
	}
	
	function bsk_campaigns_deactivate(){

	}
	
	function bsk_campaigns_deinstall(){
		//remove saved setting
		delete_option( 'bsk_campaigns_zoho_authtoken' );
		delete_option( 'bsk_campaigns_zoho_mailing_list_key' );
	} 
	
	function bsk_campaigns_enqueue_scripts() {
		if ( is_admin() ) {
			//wp_enqueue_script( 'bsk-campaigns', plugin_dir_url( __FILE__ ) . 'js/bsk-campaign-admin.js', array( 'jquery' ) );
		}
	}

	function bsk_campaigns_register_settings() {
		register_setting( 'bsk_campaigns-settings', 'bsk_campaigns_zoho_authtoken' );
		register_setting( 'bsk_campaigns-settings', 'bsk_campaigns_zoho_mailing_list_key' );
	}
	
	function bsk_campaigns_plugin_settings() {
		require 'inc/bsk-zoho-campaigns-option.php';
		add_options_page('BSK Zoho Campaigns', 'BSK Zoho Campaigns', 'manage_options', 'bsk-zoho-campaigns-settings', 'bsk_campaigns_option_page');
	}
	
	function bsk_campaigns_register_widget(){
		require 'inc/bsk-zoho-campaigns-wgtclass.php';
		
		register_widget( "BSKZohoCampaignsWidget" );
	}
	
	function bsk_campaigns_post_action(){
		if( isset( $_POST['bsk_campaigns_action'] ) && strlen($_POST['bsk_campaigns_action']) >0 ) {
			do_action( 'bsk_campaigns_' . $_POST['bsk_campaigns_action'], $_POST );
		}
	}
	
	function bsk_campaigns_widget_submit(){
		//check if email valid
		if( !isset( $_POST['bsk_campaigns_widget_email'] ) || strlen($_POST['bsk_campaigns_widget_email']) < 3 ){
			return; 
		}
		$email = $_POST['bsk_campaigns_widget_email'];
		if ( !is_email( $email ) ){
			return;
		}
		$token = trim( get_option('bsk_campaigns_zoho_authtoken', '') );
		$list_key = trim( get_option('bsk_campaigns_zoho_mailing_list_key', '') );
		if (!$token || !$list_key){
			return;
		}
		//call API
		$remote_url = 'https://campaigns.zoho.com/api/xml/listsubscribe?authtoken='.
					  $token.'&scope=CampaignsAPI&version=1&resfmt=XML&listkey='.$list_key.'&contactinfo='.urlencode('<xml><fl val="Contact Email">'.$email.'</fl></xml>');
		
		$get_result = wp_remote_post( $remote_url, array('timeout' => 15, 'sslverify' => false) );
	}
}

$bsk_campaigns_instance = new BSKZohoCampaigns();

