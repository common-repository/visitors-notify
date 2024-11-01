<?php 
/*
Plugin Name: Visitors Notify
Plugin URI: http://www.cuvintealese.ro
Description: Notify users about their presence ont the blog.Developed based on <a href="http://mnealui.info">Christi's</a> idea.
Version: 0.1
Author: Ionut Ssndu
Author URI:http://www.cuvintealese.ro

	Copyright 2011  Ionut Sandu (cuvintealese)  (email : cuvintealese@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/ 

define('VISITORSNOTIFY',$wpdb->prefix.'visitors_notify');
add_action('init', 'visitorsnotify_init');
add_action('wp_head','visitorsnotify_style');
add_action('admin_menu', 'visitorsnotify_adminmenu');


	function visitorsnotify_adminmenu(){
	add_options_page( 'Visitors Notify', 'Visitors Notify', 8 , 'VisitorsNotify', 'visitorsnotify_options');}
	
	function visitorsnotify_options(){
	?>
	<div class="wrap">
	<h2>Visitors Notify</h2>
	<p> Shows the visitors who passed over your posts, only if the visitor has a previous comment approved.If the cookies aren't set then a form will show up for entering visitors data.<br/></p>
	<form method="post" action="options.php">
	<?php wp_nonce_field('update-options'); ?>
	<table class="form-table">

	<tr valign="top">
	<td>Show visitor link ? (1 = on | 0 = off)</td>
	<td><input type="text" name="show_visitor_link" value="<?php echo get_option('show_visitor_link'); ?>" /></td>
	</tr><tr>
	<td>Enter the title</td>
	<td><input type="text" name="visitors_notify_title" value="<?php echo get_option('visitors_notify_title'); ?>" /></td>
	</tr>
	</table>
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="page_options" value="show_visitor_link, visitors_notify_title" />

	<p class="submit">
	<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	</p>

	</form>
	<small>Developed by <a href="http://www.cuvintealese.ro">Ionut Sandu</a> based on <a href="http://mnealui.info">Christi's</a> idea.</small>
	</div>
	
	
	
	<?php }












	function visitorsnotify_style(){
	echo '<style type="text/css">
	#visitorsnotifymain { padding:10px 5px;font-size:12px;clear:both;}
	#visitorsnotifyform { padding:10px 5px;}
	#visitorsnotifytitle {font-weight:bold;}
	.visitorsnotifymsg {color:#c00;clear:both;padding:10px 5px;}
	input#n, input#m, input#w{width:100px;}
	#visitorsnotifyform .submit input {font-size:12px;}
	
	</style>';}

	function visitorsnotify_init() {

		if ( isset($_GET['activate']) || isset($_GET['activate-multi']) )
		{
        visitorsNotify_install();
		}
	}

	function visitorsNotify_install() {

		global $wpdb ;
		if ($wpdb->get_var("show tables like '".VISITORSNOTIFY."'") != VISITORSNOTIFY)
			{
			$wpdb->query("CREATE TABLE IF NOT EXISTS `".VISITORSNOTIFY."` (
					  `id` int(10) NOT NULL auto_increment,
					  `name` varchar(100) NOT NULL,
					  `postid` int(255) NOT NULL,
					  `email` varchar(150)  NULL,
					  `website` varchar(150)  NULL,
					  `aprobat` int(11) NOT NULL DEFAULT '0',
					  PRIMARY KEY  (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8");
		
			}
	}

	function checkCookie() {//DACA ARE COOKIE
	
		if(isset($_COOKIE['comment_author_'.COOKIEHASH]) && isset($_COOKIE['comment_author_email_'.COOKIEHASH])) {
		
			$author =  $_COOKIE['comment_author_'.COOKIEHASH]  ;
			$author_email = $_COOKIE['comment_author_email_'.COOKIEHASH];
			global $wpdb , $post, $id;			
			$checkentry = $wpdb->get_row("SELECT email FROM `".VISITORSNOTIFY."` WHERE postid = '$id' AND email = '$author_email' ");
			$checkemail = $wpdb->get_row("SELECT * FROM ". $wpdb->comments ." WHERE comment_author_email = '$author_email' AND comment_approved = 1 ");
			
			
				if($checkemail && $checkentry  && $checkentry->aprobat == 1){ return showVisitorsList();}
				
				else if($checkemail && $checkentry && $checkentry->aprobat == 0) {$wpdb->query("UPDATE ".VISITORSNOTIFY." SET aprobat = '1' WHERE email = '$author_email' AND postid = '$id' ");return showVisitorsList();}
				
				else if($checkemail  && !$checkentry ){
				
						if(isset($_COOKIE['comment_author_url_'.COOKIEHASH])){
						
							$author_url = $_COOKIE['comment_author_url_'.COOKIEHASH];
						}
						
						$wpdb->query("INSERT INTO ".VISITORSNOTIFY."(name, postid, email, website, aprobat) VALUES ('$author','$id', '$author_email', '$author_url', '1') ");
						return showVisitorsList();
				}
				
				else if(!$checkemail && $checkentry  && $checkentry->aprobat = '1'){
						
						$wpdb->query("UPDATE ".VISITORSNOTIFY." SET aprobat = '0' WHERE email = '$author_email' AND postid = '$id' ");
						return showVisitorsList();
				}
				else { if(!$checkemail && !$checkentry){
							if(isset($_COOKIE['comment_author_url_'.COOKIEHASH])){
						
								$author_url = $_COOKIE['comment_author_url_'.COOKIEHASH];
							}
						
							$wpdb->query("INSERT INTO ".VISITORSNOTIFY."(name, postid, email, website, aprobat) VALUES ('$author','$id', '$author_email', '$author_url', '0') ");
							return showVisitorsList();
						}
				}
						
		
			
		} 
		
		return  showVisitorsList() . addVisitors() ;
		
	}
	
	function addVisitors() {
	
		if(isset($_GET['n']) && isset($_GET['m'])) {
	
			if($_GET['n'] !== '' &&  $_GET['m'] !== ''){
			$author_email = $_GET['m'];
				if(!ereg("^[^@]{1,64}@[^@]{1,255}$",$author_email)) {
				return '<br /><span class="visitorsnotifymsg">Nume sau email incomplet/incorect !</span><br/><form method="get" action="" id="visitorsnotifyform">
			<label for="n">Nume</label>
			<input type="text" id="n" name="n"/>
			<label for="m">Mail</label>
			<input type="text" id="m" name="m"/>
			<label for="w">Blog</label>
			<input type="text" id="w" name="w"/>
			<p class="submit"><input type="submit"  value="Am fost p-aici"/></p>
			</form>';}
			
						$author = $_GET['n'];
						
						$author_url = $_GET['w'];
						$comment_cookie_lifetime = apply_filters('comment_cookie_lifetime', 30000000);
						setcookie('comment_author_' . COOKIEHASH, $author, time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN);
						setcookie('comment_author_email_' . COOKIEHASH, $author_email, time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN);
						setcookie('comment_author_url_' . COOKIEHASH, esc_url($author_url), time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN);
						return '<script language="JavaScript">
						window.location="'.get_permalink($id).'";
						</script>';
				
			} 
			
			
			return '<br /><span class="visitorsnotifymsg">Name or email, missing or incorrect  !</span><br/><form method="get" action="" id="visitorsnotifyform">
			<label for="n">Name</label>
			<input type="text" id="n" name="n"/>
			<label for="m">Mail</label>
			<input type="text" id="m" name="m"/>
			<label for="w">Blog</label>
			<input type="text" id="w" name="w"/>
			<p class="submit"><input type="submit"  value="Submit"/></p>
			</form>';
		}
	
		return '<br /><form method="get" action="" id="visitorsnotifyform">
			<label for="n">Name</label>
			<input type="text" id="n" name="n"/>
			<label for="m">Mail</label>
			<input type="text" id="m" name="m"/>
			<label for="w">Blog</label>
			<input type="text" id="w" name="w"/>
			<p class="submit"><input type="submit"  value="Submit"/></p>
			</form>';
	}
	
	function showVisitorsList() {
	
		global $wpdb , $post, $id;
		$checkauthors = $wpdb->get_results("SELECT name, website FROM `".VISITORSNOTIFY."` WHERE postid = '$id' AND aprobat = 1");
			
			if($checkauthors) {
					
				foreach ($checkauthors as $checkauthor) {
						if($checkauthor->website ==''){
						
						
					$link .=  '<span> '. $checkauthor->name .'</span> |' ;}
						else {
						if(get_option('show_visitor_link') == 1){
						
					$link .=  '<span> <a href="'. $checkauthor->website .'" rel="external nofollow">'. $checkauthor->name .'</a></span> |' ;
						} 
						else {$link .=  '<span> '. $checkauthor->name .'</span> |' ;}
						}
				}
		return  $link ;}
	}
	
	
	function visitors_passed($content) {
	
		if(is_single() || is_page()) {
			if(get_option('visitors_notify_title')==''){$visitors_notify_title = 'Au trecut pe aici : ';} else {$visitors_notify_title = get_option('visitors_notify_title');}

			return $content .'<div id="visitorsnotifymain" ><h3 id ="visitorsnotifytitle">'. $visitors_notify_title .'</h3>'. checkCookie() .'</div>' ;
			
		}
		
		return $content;
	}
		add_filter('the_content','visitors_passed');
?>