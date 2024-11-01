=== Visitors Notify ===
Contributors: cuvintealese
Tags: visitors, notify, 
Requires at least: 2.9.2
Tested up to:3.2.1
Stable tag: 0.6

When your visitors don't want or don't have anything to comment, they can still mark their visits.

== Description ==

Upgrade Notice : 
If you have a previos version downloaded from my server (www.cuvintealese.ro), after update you may notice that now you have 2 versions of the plugin, one updated (ver. 0.5 ), and one still not updated (ver. 0.1 ).If so, just delete the not updated version.No loss whatsoever.The inconvenient comes from a mistaken "underscore" which was suposed to be a hyphen, so wordpress won't recognize that folder on update.

The plugin shows below each post the name (link or text) of the people who visited it, based on their cookies, and their comment status approval.The plugin is a win-win model type, on one hand - for all the times when the user doesn't feel or doesn't have anything to comment, but he still marks his visits on your blog, and on the other hand - he gets "repaid" for his visit by listing his name under the posts he visited.

The plugin list the visitors name based on their cookies, if no cookies then a submit form will show up.
For preventing spam,  displaying the visitor's name will need at least one comment approved.
You can setup the plugin to show the name as link(if any) or text
		
Note : This plugin is licensed under GPL v2.0 and is made available by Ionut Sandu (http://www.cuvintealese.ro/mygraphicsstuff/visitors-notify/),based on Christi's ideea (http://www.mnealui.info).


A few notes about the sections above:

*   "Contributors" cuvintealese
*   "Tags" visitors, notify
*   "Requires at least" 2.9.2
*   "Tested up to" 3.1.3
* 

   
== Changelog == 

= 0.6 =
* improved compatibility with other common used  plugins 
* stopped logging every request.If the visitor has a previously comment approved he'll get listed, otherwise no log, no entries (spam protection and reduce db overoad)

= 0.5 =  
* added post/page menu to disable the plugin on posts/pages you don't want to use it (you need to be save it first)
* changed the form method from $_GET to $_POST
* added an extra check for blogs that uses allows comments without an email adress ( we don't save them, spam prevention)
* added Gravatar support. Now you can choose whether to display the name of your visitors or their gravatars
* added option to set the form button text
* added a small piece of javascript to open/close the widget,  in the post


== Installation ==

1.Upload the files via wordpress upload installer or ftp, to root/wp-plugins

2.Activate from the wordpress "Plugins" menu 

3.Edit the options from "Settings" menu

4.You can disable the plugin on specific posts/pages by using the menu in the post/page edit ( since ver. 0.5 )


== Frequently Asked Questions ==

= How does it work? = 
If a visitors stumble one of your page, and he had previously commented on your blog but this time has nothing to say (he's just surfing :) ), he'll be automatically added in the list below the post, so he can still mark he's visit.
If the visitor has no cookie set,  a form will show up just to enter his details and in a second he'' be listed.
IMPORTANT : Only visitors with at least one comment approved can benefit from this -  spam protection , db lower usage, and make them comment at least once :)

== Screenshots ==




== Upgrade Notice ==

If you have a previos version downloaded from my server (www.cuvintealese.ro), after update you may notice that now you have 2 versions of the plugin, one updated (ver. 0.5 ), and one still not updated (ver. 0.1 ).If so, just delete the not updated version.No loss whatsoever.The inconvenient comes from a mistaken "underscore" which was suposed to be a hyphen, so wordpress won't recognize that folder on update.


== Arbitrary section ==

