<?php
if( !defined('ABSPATH') ){ exit();}
function wp_lnap_admin_notice()
{
	add_thickbox();
	$sharelink_text_array_ln = array
						(
						"I use WP to LinkedIn Auto Publish wordpress plugin from @xyzscripts and you should too.",
						"WP to LinkedIn Auto Publish wordpress plugin from @xyzscripts is awesome",
						"Thanks @xyzscripts for developing such a wonderful LinkedIn auto publishing wordpress plugin",
						"I was looking for a LinkedIn publishing plugin and I found this. Thanks @xyzscripts",
						"Its very easy to use WP to LinkedIn Auto Publish wordpress plugin from @xyzscripts",
						"I installed WP to LinkedIn Auto Publish from @xyzscripts,it works flawlessly",
						"WP to LinkedIn Auto Publish wordpress plugin that i use works terrific",
						"I am using WP to LinkedIn Auto Publish wordpress plugin from @xyzscripts and I like it",
						"The WP to LinkedIn Auto Publish plugin from @xyzscripts is simple and works fine",
						"I've been using this LinkedIn plugin for a while now and it is really good",
						"WP to LinkedIn Auto Publish wordpress plugin is a fantastic plugin",
						"WP to LinkedIn Auto Publish wordpress plugin is easy to use and works great. Thank you!",
						"Good and flexible  WP to LinkedIn Auto publish plugin especially for beginners",
						"The best WP to LinkedIn Auto publish wordpress plugin I have used ! THANKS @xyzscripts",
						);
$sharelink_text_ln = array_rand($sharelink_text_array_ln, 1);
$sharelink_text_ln = $sharelink_text_array_ln[$sharelink_text_ln];
$xyz_lnap_link = admin_url('admin.php?page=linkedin-auto-publish-settings&lnap_blink=en');
$xyz_lnap_link = wp_nonce_url($xyz_lnap_link,'lnap-blk');
$xyz_lnap_notice = admin_url('admin.php?page=linkedin-auto-publish-settings&lnap_notice=hide');
$xyz_lnap_notice = wp_nonce_url($xyz_lnap_notice,'lnap-shw');
	echo '
	<script type="text/javascript">
			function xyz_lnap_shareon_tckbox(){
			tb_show("Share on","#TB_inline?width=500&amp;height=75&amp;inlineId=show_share_icons_ln&class=thickbox");
		}
	</script>
	<div id="lnap_notice_td" class="error" style="color: #666666;margin-left: 2px; padding: 5px;line-height:16px;">' ?>
	<p><?php
	   $lnap_url="https://wordpress.org/plugins/linkedin-auto-publish/";
	   $lnap_xyz_url="https://xyzscripts.com/";
	   $lnap_wp="WP to LinkedIn Auto Publish ";
	   $lnap_xyz_com="xyzscripts.com";
	   $lnap_thanks_msg=sprintf( __('Thank you for using <a href="%s" target="_blank"> %s </a> plugin from <a href="%s" target="_blank"> %s </a>. Would you consider supporting us with the continued development of the plugin using any of the below methods?','linkedin-auto-publish'),$lnap_url,$lnap_wp,$lnap_xyz_url,$lnap_xyz_com); 
	   echo $lnap_thanks_msg; ?></p>
	
       <p>
       <a href="https://wordpress.org/support/plugin/linkedin-auto-publish/reviews" class="button xyz_lnap_rate_btn" target="_blank"> <?php _e('Rate it 5★\'s on wordpress','linkedin-auto-publish'); ?> </a>
       <?php if(get_option('xyz_credit_link')=="0") ?>
		<a href="<?php echo $xyz_lnap_link; ?>" class="button xyz_lnap_backlink_btn xyz_blink"> <?php _e('Enable Backlink','linkedin-auto-publish'); ?> </a>
	
	<a class="button xyz_lnap_share_btn" onclick=xyz_lnap_shareon_tckbox();> <?php _e('Share on','linkedin-auto-publish'); ?></a>
		<a href="https://xyzscripts.com/donate/5" class="button xyz_lnap_donate_btn" target="_blank"> <?php _e('Donate','linkedin-auto-publish'); ?> </a>
	
	<a href="<?php echo $xyz_lnap_notice; ?>" class="button xyz_lnap_show_btn">  <?php _e('Don\'t Show This Again','linkedin-auto-publish'); ?> </a>
	</p>
	
	<div id="show_share_icons_ln" style="display: none;">
	<a class="button" style="background-color:#3b5998;color:white;margin-right:4px;margin-left:100px;margin-top: 25px;" href="http://www.facebook.com/sharer/sharer.php?u=https://xyzscripts.com/wordpress-plugins/linkedin-auto-publish/" target="_blank"> <?php _e('Facebook','linkedin-auto-publish'); ?> </a>
	<a class="button" style="background-color:#00aced;color:white;margin-right:4px;margin-left:20px;margin-top: 25px;" href="http://twitter.com/share?url=https://xyzscripts.com/wordpress-plugins/linkedin-auto-publish/&text='.$sharelink_text_ln.'" target="_blank"> <?php _e('Twitter','linkedin-auto-publish'); ?> </a> 
	<a class="button" style="background-color:#007bb6;color:white;margin-right:4px;margin-left:20px;margin-top: 25px;" href="http://www.linkedin.com/shareArticle?mini=true&url=https://xyzscripts.com/wordpress-plugins/linkedin-auto-publish/" target="_blank"> <?php _e('LinkedIn','linkedin-auto-publish'); ?> </a>
	</div>
	<?php echo '</div>';	
} 
$lnap_installed_date = get_option('lnap_installed_date');
if ($lnap_installed_date=="") {
	$lnap_installed_date = time();
}
if($lnap_installed_date < ( time() - (20*24*60*60) ))
{
	if (get_option('xyz_lnap_dnt_shw_notice') != "hide")
	{
		add_action('admin_notices', 'wp_lnap_admin_notice');
	}
}
/// --- SMAP Solutions Notice Section ---
function xyz_lnap_smapsolutions_admin_notice() {
    if (!current_user_can('administrator')) {
        return;
    }
    // --- SMAP Solutions Expiry Notices ---
    $expiry_data = get_option('xyz_lnap_smapsolutions_pack_expiry', []);
    if (empty($expiry_data)) 
        return;
    // Remove invalid or empty timestamps
    $expiry_data = array_filter($expiry_data, function($ts) {
        return !empty($ts) && is_numeric($ts);
    });
    if (empty($expiry_data)) 
        return;
    $now = current_time('timestamp');
    $messages = [];
    $displayed_services = [];
    // --- Only LinkedIn Service ---
    $service = 'smapsolution_linkedin_expiry';
    $expiry  = $expiry_data[$service] ?? null;
    if (empty($expiry)) 
        return;
    $service_name = xyz_lnap_format_smapsolutions_service_name($service);
    $dismissed_stage = get_user_meta(get_current_user_id(), "xyz_lnap_notice_dismissed_$service", true);
    $diff = $expiry - $now;
    // --- Individual Package Notice Conditions ---
    if ($diff <= 30 * DAY_IN_SECONDS && $diff > 7 * DAY_IN_SECONDS && $dismissed_stage !== '30days') {
        $messages[] = sprintf(
            __("SMAP Solutions %s package expires in 30 days.", "linkedin-auto-publish"),
            $service_name
        );
        $displayed_services[] = $service;
    } elseif ($diff <= 7 * DAY_IN_SECONDS && $diff > 0 && $dismissed_stage !== '1week') {
        $messages[] = sprintf(
            __("SMAP Solutions %s package expires in 1 week!", "linkedin-auto-publish"),
            $service_name
        );
        $displayed_services[] = $service;
    } elseif ($diff <= 0 && $dismissed_stage !== 'expired') {
        $messages[] = sprintf(
            __("SMAP Solutions %s package has expired!", "linkedin-auto-publish"),
            $service_name
        );
        $displayed_services[] = $service;
    }
    // --- Display the Notice ---
    if (!empty($messages)) {
        $dismiss_url = wp_nonce_url(
            add_query_arg([
                'xyz_lnap_dismiss' => 1,
                'services' => implode(',', $displayed_services)
            ]),
            'xyz_lnap_dismiss_notice'
        );
        echo '<div class="notice notice-warning is-dismissible">';
        echo '<p style="color:#2271b1;padding:0;margin:2px 0;"><strong>' 
             . esc_html__("SMAP Solutions Notice:", "linkedin-auto-publish") 
             . '</strong></p>';
        foreach ($messages as $msg) {
            echo '<span style="color:indianred;"><strong>' . esc_html($msg) . '</strong></span><br/>';
        }
        echo '<p style="text-align:right;padding:0;margin:2px 0;font-weight:bold;">
                <a href="' . esc_url($dismiss_url) . '">' 
                . esc_html__("Don't show this again", "linkedin-auto-publish") 
              . '</a></p>';
        echo '</div>';
    }
}
// --- Format Service Name ---
function xyz_lnap_format_smapsolutions_service_name($service) {
    // Remove prefix
    $name = str_replace('smapsolution_', '', $service);
    // Remove _expiry suffix
    $name = str_replace('_expiry', '', $name);
    return ucfirst($name);
}
add_action('admin_notices', 'xyz_lnap_smapsolutions_admin_notice');
// --- Handle Dismissal Only for LinkedIn Service ---
add_action('admin_init', function() {
    if (isset($_GET['xyz_lnap_dismiss']) && check_admin_referer('xyz_lnap_dismiss_notice')) {
        $expiry_data = get_option('xyz_lnap_smapsolutions_pack_expiry', []);
        $now = current_time('timestamp');
        // --- Only LinkedIn Service ---
        $service = 'smapsolution_linkedin_expiry';
        if (!isset($expiry_data[$service])) {
            return;
        }
        $expiry = $expiry_data[$service];
        $diff = $expiry - $now;
        $dismissed_stage = get_user_meta(get_current_user_id(), "xyz_lnap_notice_dismissed_$service", true);
        // --- 30 Days (30 > diff > 7) ---
        if ($diff <= 30 * DAY_IN_SECONDS && $diff > 7 * DAY_IN_SECONDS && $dismissed_stage !== '30days') {
            update_user_meta(get_current_user_id(), "xyz_lnap_notice_dismissed_$service", '30days');
        } 
        // --- 1 Week (7 > diff > 0) ---
        elseif ($diff <= 7 * DAY_IN_SECONDS && $diff > 0 && $dismissed_stage !== '1week') {
            update_user_meta(get_current_user_id(), "xyz_lnap_notice_dismissed_$service", '1week');
        } 
        // --- Expired (diff <= 0) ---
        elseif ($diff <= 0 && $dismissed_stage !== 'expired') {
            update_user_meta(get_current_user_id(), "xyz_lnap_notice_dismissed_$service", 'expired');
        }
        wp_safe_redirect(remove_query_arg(['xyz_lnap_dismiss', 'services']));
        exit;
    }
});
