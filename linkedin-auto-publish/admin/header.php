<?php
if( !defined('ABSPATH') ){ exit();}
?><style>
a.xyz_header_link:hover{text-decoration:underline;}
.xyz_header_link{text-decoration:none;}
</style>

<?php 
if($_POST && isset($_POST['xyz_credit_link']))
{
	if (! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'xyz_lnap_basic_settings_nonce' )) {
		wp_nonce_ays( 'xyz_lnap_basic_settings_nonce' );
		exit;
	}
	$xyz_credit_link=$_POST['xyz_credit_link'];
	
	update_option('xyz_credit_link', $xyz_credit_link);
	?>
<div class="xyz_lnap_system_notice_area_style1" id="xyz_lnap_system_notice_area">
	<?php _e('Settings updated successfully.','linkedin-auto-publish'); ?> &nbsp;&nbsp;&nbsp;<span id="xyz_lnap_system_notice_area_dismiss"> <?php _e('Dismiss','linkedin-auto-publish'); ?> </span>
</div>
	<?php 
}
if(!$_POST && isset($_GET['lnap_blink'])&&isset($_GET['lnap_blink'])=='en'){
	if (! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'],'lnap-blk')){
		wp_nonce_ays( 'lnap-blk');
		exit;
	}
	update_option('xyz_credit_link',"lnap");
?>
<div class="xyz_lnap_system_notice_area_style1" id="xyz_lnap_system_notice_area">
<?php _e('Thank you for enabling backlink.','linkedin-auto-publish'); ?>
 &nbsp;&nbsp;&nbsp;<span id="xyz_lnap_system_notice_area_dismiss"> <?php _e('Dismiss','linkedin-auto-publish'); ?> </span>
</div>

<style type="text/css">
	.xyz_blink{
		display:none !important;
	}
</style>

<?php 
}
if(get_option('xyz_credit_link')=="0" &&(get_option('xyz_lnap_credit_dismiss')=="0")){
	?>
<div style="float:left;background-color: #FFECB3;border-radius:5px;padding: 0px 5px;margin-top: 10px;border: 1px solid #E0AB1B" id="xyz_backlink_div">

	<?php _e('Please do a favour by enabling backlink to our site.','linkedin-auto-publish'); ?> <a id="xyz_lnap_backlink" class="xyz_lnap_backlink" style="cursor: pointer;" > <?php _e('Okay, Enable','linkedin-auto-publish'); ?> </a>.
    <a id="xyz_lnap_dismiss" style="cursor: pointer;" > <?php _e('Dismiss','linkedin-auto-publish'); ?> </a>.
<script type="text/javascript">
jQuery(document).ready(function() {

	jQuery('#xyz_lnap_backlink').click(function(){
		xyz_filter_blink(1)
	});
	jQuery('#xyz_lnap_dismiss').click(function(){
		xyz_filter_blink(-1)
	});
	function xyz_filter_blink(stat){
		var backlink_nonce= '<?php echo wp_create_nonce('backlink');?>';


		var dataString = { 
				action: 'xyz_lnap_ajax_backlink', 
				enable: stat ,
				_wpnonce: backlink_nonce
				};

		jQuery.post(ajaxurl, dataString, function(response) {
			if(response==1)
		       	alert(xyz_script_lnap_var.alert3); 
			if(response=="lnap"){
				jQuery('.xyz_lnap_backlink').hide();
			jQuery("#xyz_backlink_div").html(xyz_script_lnap_var.html4);
			jQuery("#xyz_backlink_div").css('background-color', '#D8E8DA');
			jQuery("#xyz_backlink_div").css('border', '1px solid #0F801C');
		}
			if(response==-1){
				jQuery("#xyz_backlink_div").remove();
		}

});
};
});
</script>
</div>
	<?php 
}



?>


 
<div style="margin-top: 10px">
<table style="float:right; ">
<tr>
<td  style="float:right;">
	<a  class="xyz_header_link" style="margin-left:8px;margin-right:12px;"   target="_blank" href="https://xyzscripts.com/donate/5"> <?php _e('Donate','linkedin-auto-publish'); ?> </a>
</td>
<td style="float:right;">
	<a class="xyz_header_link" style="margin-left:8px;"  target="_blank" href="http://help.xyzscripts.com/docs/linkedin-auto-publish/faq/"> <?php _e('FAQ','linkedin-auto-publish'); ?> </a> | 
</td>
<td style="float:right;">

	<a class="xyz_header_link" style="margin-left:8px;" target="_blank" href="http://help.xyzscripts.com/docs/linkedin-auto-publish/"> <?php _e('Readme','linkedin-auto-publish'); ?> </a> | 
</td>
<td style="float:right;">
	<a class="xyz_header_link" style="margin-left:8px;" target="_blank" href="https://xyzscripts.com/wordpress-plugins/linkedin-auto-publish/details"> <?php _e('About','linkedin-auto-publish'); ?> </a> | 
</td>
<td style="float:right;">
	<a class="xyz_header_link" target="_blank" href="https://xyzscripts.com">XYZScripts</a> | 
</td>

</tr>
</table>
</div>


<div style="clear: both"></div>
<div style="border: 1px solid #CCC; border-radius:2px;padding:10px;width:97%;overflow-x: auto;" id="xyz_lnap_content_border">
