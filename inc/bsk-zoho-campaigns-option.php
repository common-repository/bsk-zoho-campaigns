<?php

function bsk_campaigns_option_page() {
  
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	
	$token = get_option('bsk_campaigns_zoho_authtoken', '');
	$list_key = get_option('bsk_campaigns_zoho_mailing_list_key', '');
?>
  	
    <div class="wrap">
        <form action="options.php" method="POST" id="bsk_campaigns_setting_form">
			<?php settings_fields( 'bsk_campaigns-settings' ); ?>
            <h2>BSK Zoho Campaigns</h2>
            <p>The plugin help you add contacts to your Zoho campaigns mailing list.</p>
            <table>
                <tr valign="top">
                    <td>Zoho Campaigns Authentication Key</td>
                    <td>
                    <input name="bsk_campaigns_zoho_authtoken" id="bsk_campaigns_zoho_authtoken_id" type="text" value="<?php echo $token; ?>" style="width:350px;" />
                    </td>
                </tr>
                <tr valign="top">
                    <td>Zoho Campaigns mailing list key</td>
                    <td>
                    <input name="bsk_campaigns_zoho_mailing_list_key" id="bsk_campaigns_zoho_mailing_list_key_id" type="text" value="<?php echo $list_key; ?>" style="width:350px;" />
                    </td>
                </tr>
            </table>
            <p style="margin-top: 20px"><button class="button-primary" type="submit" id="bsk_campaigns_option_save_id">Save Settings</button></p>
        </form>
    </div>
<?php
}
?>
  