<?php

/*
Plugin Name: Paraphraser
Plugin URI: https://www.paraphraser.io/
Description: This is a Paraphraser plugin which gives the best paraphrasing of an article. you can just edit your post and hit Paraphrase button and your content will be fully paraphrased by our advanced algorithm.
Version: 1.0
Author: Paraphraser.io
Author URI: https://profiles.wordpress.org/paraphrasingtool/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/
global $wpdb;
global $jal_db_version;
$jal_db_version = '1.0';




// Act on plugin activation
register_activation_hook( __FILE__, "chk_paraphrase87764_mine_plugin_activate_myplugin" );

// Activate Plugin
function chk_paraphrase87764_mine_plugin_activate_myplugin() {
	global $wpdb;
	$plugin = plugin_dir_url( __FILE__ );
	
	
	
	// $loginHtml = file_get_contents(ABSPATH . 'wp-admin/admin-footer.php');

	// $loginHtml.= $script;
	// file_put_contents(ABSPATH . 'wp-admin/admin-footer.php', $loginHtml);

	global $jal_db_version;
	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	
	$sqlM = $wpdb->prepare("set global sql_mode = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';");
	$wpdb->get_results($sqlM);

	$table_name = $wpdb->prefix . 'plugin_paraphraser';
	
	$charset_collate = $wpdb->get_charset_collate();


	$sqlM = $wpdb->prepare( "CREATE TABLE $table_name (
		id int(11) NOT NULL AUTO_INCREMENT,
		api_key varchar(100) DEFAULT '' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;" );
	$wpdb->get_results($sqlM);
	
	add_option( 'jal_db_version', $jal_db_version );
}


// Act on plugin activation

register_deactivation_hook( __FILE__, 'chk_paraphrase87764_mine_plugin_deactivate_myplugin' );

function chk_paraphrase87764_mine_plugin_deactivate_myplugin() {
    global $wpdb;

	global $jal_db_version;

	$table_name = $wpdb->prefix . 'plugin_paraphraser';
	
	$sqlM = $wpdb->prepare( "DROP TABLE IF EXISTS $table_name" );
	$wpdb->get_results($sqlM);

    delete_option("my_plugin_db_version");

}



add_action('admin_menu', 'chk_paraphrase87764_mine_plugin_plag_plugin_setup_menu');

 
function chk_paraphrase87764_mine_plugin_plag_plugin_setup_menu(){
    add_menu_page( 'Paraphraser Plugin Page', 'Paraphraser.io', 'manage_options', 'paraphraserio-settings', 'chk_paraphrase87764_mine_plugin_load_plag_page','dashicons-schedule', 3);
}
 
function chk_paraphrase87764_mine_plugin_load_plag_page(){

	global $wpdb;
	$wpdb_tablename = $wpdb->prefix.'plugin_paraphraser';
	$result = $wpdb->get_results("SELECT * FROM $wpdb_tablename");

	$api = "";
	$acton = "";
	$id = "";
	if(!empty($result))
	{
		$api = $result[0]->api_key;
		$acton = "update";
		$id = $result[0]->id;
	}
	else
	{
		$acton = "add";
		
	}
	$plugin = plugin_dir_url( __FILE__ );
    $allowed_html = array(
		'h1'      => array(),
		'h3'      => array(
			'style'  => array()
		),
		'form'     => array(
			'action'  => array(),
			'method' => array(),
		),
		'table'     => array(
			'class'  => array(),
			'role' => array(),
		),
		'tr' => array(),
		'th' => array(
			'scope'  => array(),
		),
		'td' => array(
			'colspan'  => array(),
		),
		'tbody' => array(),
		'input' => array(
			'name'  => array(),
			'type' => array(),
			'id' => array(),
			'value' => array(),
			'class' => array(),
		),
		'p' => array(
			'class'  => array(),
		),
		'a' => array(
			'href'  => array(),
			'target' => array(),
			'style' => array(),
		),

	);
	echo wp_kses('<h1>Paraphraser.io Plugin Settings</h1><form action="'.admin_url( 'admin.php' ).'" method="post"><table class="form-table" role="presentation">

				<tbody>
					<tr>
						<th scope="row">
							<label for="blogname">API Key</label>
						</th>
						<td>
							<input name="apikey" type="text" id="apikey" value="'.$api.'" class="regular-text">
							<input type="hidden" name="acton" value="'.$acton.'">
							<input type="hidden" name="id" value="'.$id.'">
							<input type="hidden" name="action" value="wpse10500paraphraser">
						</td>
					</tr>
					<tr>
						
						<td colspan="2">Please Login or Signup at <a href="https://www.paraphraser.io/" style="font-weight: bold;" target="_blank">paraphraser.io</a> to get your API key</td>
					</tr>
					<tr>
						<td colspan="2">
							<h3 style="font-weight: bold;color: red;margin: 0px;">Note: </h3>
							<p style="font-weight: bold;">if you want to use free basic paraphraser please leave API key field empty</p>
						</td>
					</tr>
				</tbody>
			</table><p class="submit">
			<input type="submit" name="submitApi" id="submit" class="button button-primary" value="Save Changes">
		</p></form>',$allowed_html);
}

add_action( 'admin_action_wpse10500paraphraser', 'chk_paraphrase87764_mine_plugin_wpse10500paraphraser_admin_action' );
function chk_paraphrase87764_mine_plugin_wpse10500paraphraser_admin_action()
{
	global $wpdb;
	
	$table_name = $wpdb->prefix . 'plugin_paraphraser';
	
	$charset_collate = $wpdb->get_charset_collate();
	if(sanitize_text_field($_POST['acton']) == "add")
	{
			$sqll = $wpdb->prepare("INSERT into  $table_name (api_key) VALUES(%s) ",sanitize_text_field($_POST['apikey']));
	}
	elseif(sanitize_text_field($_POST['acton']) == "update")
	{
			$sqll = $wpdb->prepare("UPDATE $table_name SET `api_key` = %s WHERE `id` = %d", sanitize_text_field($_POST['apikey']), sanitize_text_field($_POST['id']) );
	}
	
	$wpdb->get_results($sqll);
	wp_redirect($_SERVER['HTTP_REFERER']);
	die();
}

add_action( 'wp_ajax_chk_paraphrase87764_mine_plugin_paraphraseGet', 'chk_paraphrase87764_mine_plugin_paraphraseGet' );

function chk_paraphrase87764_mine_plugin_paraphraseGet() 
{
	global $wpdb;
	$wpdb_tablename = $wpdb->prefix.'plugin_paraphraser';
	$sqll = $wpdb->prepare("SELECT * FROM $wpdb_tablename order by id ASC");
	$result = $wpdb->get_results($sqll);
	$api = "";
	if(!empty($result))
	{
		$api = $result[0]->api_key;
		
	}
	
	$mode = 1;
	if(empty($api))
	{
		$ajaxUrl = 'https://www.paraphraser.io/paraphrasing-apis';
	}
	else
	{
		$body = array("key" => $api);
		$args = array(
			'method' => 'POST',
			'headers' => array(
			),
			'httpversion' => '1.0',
			'timeout' => 25,
			'blocking'    => true,
			'sslverify' => false,
			'cookies'     => array(),
			'body' => $body
			);
		$data = wp_remote_retrieve_body(wp_remote_get( 'https://www.paraphraser.io/apis/userInfo', $args));
		$userArr = json_decode(wp_specialchars_decode(esc_html($data),ENT_QUOTES));
		// echo "<pre>";
		// print_r($userArr);
		// exit;
		if($userArr->is_premium == 1 || $userArr->error)
		{
			$mode = 3;
			$ajaxUrl = 'https://www.paraphraser.io/paraphrasing-api';
		}
		else
		{
			$ajaxUrl = 'https://www.paraphraser.io/paraphrasing-apis';

		}
	}
	$text = sanitize_text_field($_POST['text']);
	$text = strip_tags($text);
	$text = trim($text);
	$langg = get_locale();
	$langg = explode('_', $langg);

	$body = array("key" => $api,'data'=>$text,'lang'=>$langg[0],'mode'=>$mode,'style'=>'1');
	$args = array(
		'method' => 'POST',
		'headers' => array(
		),
		'httpversion' => '1.0',
		'timeout' => 25,
		'blocking'    => true,
		'sslverify' => false,
		'cookies'     => array(),
		'body' => $body
		);
	$data = wp_remote_retrieve_body(wp_remote_get( $ajaxUrl, $args));

	echo wp_specialchars_decode(esc_html($data),ENT_QUOTES) ;

	die();
}

add_action( 'admin_footer', 'chk_paraphrase87764_mine_plugin_add_script' );
function chk_paraphrase87764_mine_plugin_add_script() {
	if(basename($_SERVER['PHP_SELF']) == "post.php") {
			$allowed_html = array(
				'div'     => array(
					'class'  => array(),
					'style'  => array()
				),
				'input' => array(
					'name'  => array(),
					'type' => array(),
					'id' => array(),
					'value' => array(),
					'class' => array(),
				),
				'button' => array(
					'type' => array(),
					'id' => array(),
					'class' => array(),
				),
				'a' => array(
					'href' => array(),
					'target' => array(),
					'class' => array(),
				),
				'script' => array(),
				'br' => array(),
				'style' => array(
					'display' => array()
				),
				
			);
			?>
			
			<div class="paraphrasingPluginScript">
			<style>
				.chk_parpshe_j3344_alert-box-wrap{display: none;background:gray;filter:alpha(opacity=60);-moz-opacity:.6;-khtml-opacity:.6;opacity:.6;position:fixed;left:0;top:0;bottom:0;right:0;z-index:10;}
				.chk_parpshe_j3344_alert-box{display: none;width:464px;height:285px;box-shadow:0 30px 60px 0 #0000004d;background:#fff;border-radius:10px;margin:0;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:10}
				.chk_parpshe_j3344_alert-box-body{background:linear-gradient(#FFF,#CFCFCF);height:196px;border-radius:0 0 10px 10px}
				.chk_parpshe_j3344_alert-box-heading,.chk_parpshe_j3344_alert-box-subheading{color:#0C2E60;font-size:18px;display:block}
				.chk_parpshe_j3344_alert-box-heading{line-height:50px;font-weight: bold;}
				.chk_parpshe_j3344_alert-box-subheading{line-height:26px;font-size:15px;}
				.chk_parpshe_j3344_alert-box-ok{background:#45c3ac;border-radius:22px;width:132px;color:#fff;cursor:pointer;margin-left:auto;margin-right:auto;margin-bottom:1rem!important;margin-top:1rem!important;padding-bottom:.5rem!important;padding-top:.5rem!important}
				.chk_parpshe_j3344_alert-box-img{border-radius:10px 10px 0 0;height:115px;background:#3dd9b1 url(https://www.paraphraser.io//images/alert-box-header.png) no-repeat center;background-size:cover}
				.chk_parpshe_j3344_text-center{text-align:center !important;}
			</style>

			<div class="chk_parpshe_j3344_alert-box-wrap" ></div>

			<div class="chk_parpshe_j3344_alert-box" >
				<div class="chk_parpshe_j3344_alert-box-img"></div>
				<div class="chk_parpshe_j3344_alert-box-body">
				<div class="chk_parpshe_j3344_alert-box-heading chk_parpshe_j3344_text-center"></div>
				<div class="chk_parpshe_j3344_alert-box-subheading chk_parpshe_j3344_text-center"></div>
				<div class="chk_parpshe_j3344_alert-box-ok chk_parpshe_j3344_text-center mt-2 mx-auto mb-3 py-2">Close</div>
				</div>
			</div>

				<script>

				function chk_parpshe_j3344_alert_box(msg="",title="Error!", close = false){

					jQuery('.chk_parpshe_j3344_alert-box-heading').html(title);
					jQuery('.chk_parpshe_j3344_alert-box-subheading').html(`<p>`+msg+`</p>`);
					jQuery('.chk_parpshe_j3344_alert-box-wrap, .chk_parpshe_j3344_alert-box').show();
					if(close){
						setTimeout(() => {
							chk_parpshe_j3344_alert_box_hide();
						}, 500);
					}
				}
			
				function chk_parpshe_j3344_alert_box_hide(){
					jQuery('.chk_parpshe_j3344_alert-box-wrap, .chk_parpshe_j3344_alert-box').hide();
				}
			
				jQuery('.chk_parpshe_j3344_alert-box-wrap, .chk_parpshe_j3344_alert-box-ok').on('click', function(){
					chk_parpshe_j3344_alert_box_hide()
				 });

					window.addEventListener("load",function(){
				
						jQuery(document).find(".edit-post-header__settings").prepend('<button type="button" class="components-button paraphrasemyBtnAll is-primary" id="paraphrasemyBtnAll">Paraphrase</button>');
				
					},false);
				
					function runAllAjax(idsArr) {
						// initialize index counter
						var i = 0;
				
						function next() {
							var id = idsArr[i].id;
							var elemm = idsArr[i].elemm;
							var txt = elemm.text(); 
							
							
				
							jQuery.ajax({
								url: ajaxurl, // this is the object instantiated in wp_localize_script function
								type: 'POST',
								dataType: 'json',
								async: true,
								data:{ 
									action 				: 'chk_paraphrase87764_mine_plugin_paraphraseGet',
									actionagain 				: "checkStatus",
										
								text: txt, // this is the function in your functions.php that will be triggered
								},
								beforeSend: function()
								{
									jQuery(document).find(".paraphrasemyBtnAll").addClass("is-busy");
									jQuery(document).find(".paraphrasemyBtnAll").attr("disabled","disabled");
									jQuery(document).find(".paraphrasemyBtnAll").html("Paraphrasing ... ");
								},
								success: function(dat)
								{
									console.log(dat);
				
									++i;

									if(typeof(dat.error) != "undefined" && dat.error !== null) {
										if(dat.error == 'API Key not provided')
										{
											var msgg = 'Api Key Not Provided. Kindly Get Your API Key From <a href="https://www.paraphraser.io/" target="_blank">Paraphraser.io</a> Or Enter Your Api Key<a href="<?php echo admin_url() ?> admin.php?page=paraphraserio-settings" target="_blank"> Here.</a>';
											var heading = 'Authorization Error';
											
										}
										else if(dat.error == 'Authentication Error')
										{
											var msgg = 'API Key You Provided Is Wrong. Kindly Get Your API Key From <a href="https://www.paraphraser.io/" target="_blank">Paraphraser.io</a> Or Enter Your Api Key<a href="<?php echo admin_url() ?> admin.php?page=paraphraserio-settings" target="_blank"> Here.</a>';
											var heading = 'Authentication Error';
											
										}
										else
										{
											var msgg = dat.error;
											var heading = ``;
										}
										jQuery(document).find(".paraphrasemyBtnAll").removeAttr("disabled")
										jQuery(document).find(".paraphrasemyBtnAll").removeClass("is-busy");
										jQuery(document).find(".paraphrasemyBtnAll").html("Paraphrase");
										chk_parpshe_j3344_alert_box(msgg,heading)
										return;
									}

									elemm.html(dat.paraphrasedContent);	
									if(i >= idsArr.length) {
										jQuery(document).find(".paraphrasemyBtnAll").removeAttr("disabled")
										jQuery(document).find(".paraphrasemyBtnAll").removeClass("is-busy");
										jQuery(document).find(".paraphrasemyBtnAll").html("Paraphrase");
									} else {
										// do the next ajax call
										jQuery(document).find(".paraphrasemyBtnAll").addClass("is-busy");
										jQuery(document).find(".paraphrasemyBtnAll").attr("disabled","disabled");
										jQuery(document).find(".paraphrasemyBtnAll").html("Paraphrasing ... ");
										next();
									}
				
									
								}
							});
						}
						// start the first one
						next();
					}
				
					jQuery(document).on("click",".paraphrasemyBtnAll", function() {

						var texttt = jQuery(".block-editor-block-list__layout").text();
						console.log('asdasd')
						if(texttt.length < 30)
						{
							console.log(texttt);
							var msgg = `Please Enter atleast 15 words to paraphrase the article`;
							var heading = `Short Content`;
							chk_parpshe_j3344_alert_box(msgg,heading)
							return;
						}
						else
						{

							var sel_txt = jQuery(`.block-editor-block-list__layout`);
							var idsArr = new Array;
					
							sel_txt.children().each(function (ind, val) {
								if(jQuery(this).text().trim().length >= 30)
								{
									var chkArr = [];
									var elemm = jQuery(this);
									var id = ind;
									chkArr[`id`] = id;
									chkArr[`elemm`] = elemm;
									idsArr.push(chkArr);
								}
								// var txt = jQuery(this).text(); 
							});
							if(idsArr.length !== 0)
							{
								runAllAjax(idsArr);
							}
						}
					});
					
				</script>
			</div>
<?php
		
	}
}
?>