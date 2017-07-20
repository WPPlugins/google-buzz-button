<?php 
/*
Plugin Name: Google Buzz
Plugin URI: http://wpapi.com
Description: Adds a button which allows you to share post on google buzz. 
License: GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/gpl.html
Version: 2.0.1
Author: Purab Kharat
Author URI: http://wpapi.com
*/

if ( !defined('GOOGLEBUZZ_URL') ) {

	define('GOOGLEBUZZ_URL',get_option('siteurl').'/wp-content/plugins/'.plugin_basename(dirname(__FILE__)).'/');

} else {

	define('GOOGLEBUZZ_URL',WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/');

}


function google_buzz_button($content) {
	global $post;
    $url = '';
    if (get_post_status($post->ID) == 'publish') {
    $url = get_permalink();
	$title = get_the_title($post->ID);
    }	

	if (get_option('buzz_where')=='manual' && get_option('buzz_style')!=''){
$button = '<div id="buzz_share_1" style="'.get_option('buzz_style').'">
<a class="google-buzz-button" data-button-style="normal-count" href="http://www.google.com/buzz/post" title="Post on Google Buzz"></a>
    <script src="http://www.google.com/buzz/api/button.js" type="text/javascript"></script>
</div>';
	} else {
	$button = '<div id="buzz_share_1" style="float: right; margin-right: 10px">
<a class="google-buzz-button" data-button-style="normal-count" href="http://www.google.com/buzz/post" title="Post on Google Buzz"></a>
    <script src="http://www.google.com/buzz/api/button.js" type="text/javascript"></script>
</div>';
	}	

			if (get_option('buzz_where') == 'beforeandafter') {
				return $button . $content . $button;
			} else if (get_option('buzz_where') == 'before') {
				return $button . $content;
			} else {
				return $content . $button;
			}


}


add_filter('the_content', 'google_buzz_button');
add_filter('the_excerpt', 'google_buzz_button');

function googlebuzz_options() {
	add_menu_page('Google Buzz', 'Google Buzz', 8, basename(__FILE__), 'buzz_options_page');
	add_submenu_page(basename(__FILE__), 'Settings', 'Settings', 8, basename(__FILE__), 'buzz_options_page');
}


if(is_admin()){
    add_action('admin_menu', 'googlebuzz_options');
    add_action('admin_init', 'buzz_init');
}

function buzz_init(){
    if(function_exists('register_setting')){       
        register_setting('buzz-options', 'buzz_where');       
		register_setting('buzz-options', 'buzz_style');       
    }
}

function buzz_activate(){
    add_option('buzz_where', 'before');
    add_option('buzz_style', 'before');
	
}

function buzz_options_page() {
?>
<div style="padding:50px;">
<h2>Settings for Google Buzz button Integration in your blog</h2>
			<p>This plugin will install Google buzz button in page and post. This plugin will provide you more updated features.  </p>
			<form method="post" action="options.php">
			<?php
				// New way of setting the fields, for WP 2.7 and newer
				if(function_exists('settings_fields')){
					settings_fields('buzz-options');
				} else {
					wp_nonce_field('update-options');?>

					<input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="buzz_where" />
            <?php }?> Display Position<br>
                		<select name="buzz_where" onchange="if(this.value == 'manual'){getElementById('manualhelp').style.display = 'block';} else {getElementById('manualhelp').style.display = 'none';}">

                			<option <?php if (get_option('buzz_where') == 'before') echo 'selected="selected"'; ?> value="before">Before</option>

                			<option <?php if (get_option('buzz_where') == 'after') echo 'selected="selected"'; ?> value="after">After</option>

                			<option <?php if (get_option('buzz_where') == 'beforeandafter') echo 'selected="selected"'; ?> value="beforeandafter">Before and After</option>

							<option <?php if (get_option('buzz_where') == 'manual') echo 'selected="selected"'; ?> value="manual">Manual</option>

                		</select><br>
<p>
If you use google buzz button it like on digcms.com then use<b> clear:left; float: left; margin-right: 10px; margin-top:10px;</b> </p>

                    <input name="buzz_style" type="text" id="buzz_style" value="<?php echo htmlspecialchars(get_option('buzz_style')); ?>" size="30" />
                  

		<br><br>
            <input type="submit" name="Submit" value="<?php _e('Save Changes') ?>" />
    </form>
		</div>
<?php } ?>
