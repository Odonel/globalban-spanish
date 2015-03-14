# GlobalBan v3.4.1 [r155](https://code.google.com/p/globalban-spanish/source/detail?r=155) (25/05/2011) Multilanguages (English / Spanish / French / Russian / German / Latvian): #

This combination of es scripts and php scripts allows you and your fellow admins to ban players from ALL your servers in-game or out of game.

All bans are stored in a MySQL database on your web server, no more need to mess with valve's banned\_users.cfg file! I have encompassed as much as possible into the web front end as possible, much more will be added as time goes on.

If you use steambans, then this script probably is not for you, this script was written for those that wish to maintain their own ban system.

Admins can be managed from the application and it supports SMF forum integration so that you can use existing groups to manage your admins.

An export/import feature allows you to grab bans from another site running GlobalBan and import them directly into your own database.


---


I made some changes to the wonderful Soynuts work´s, with the intention to see as much information as possible on the ban list without having to go looking the bans one by one.

See at a glance which bans are with demo, previous offenses, comments and the post link to the forum when discuss the withdrawal of the punishment.

I´m sorry about my low level of English. xD




[![](http://globalban-spanish.googlecode.com/svn/wiki/images/banlist1.png)](http://forums.eventscripts.com/viewtopic.php?p=293960#293960)

## Working Demos: ##

  * http://clancentenarios.es/baneados/index.php?page=banlist&lg=English
  * http://ban.it-service-düsseldorf.de/index.php?page=banlist&lg=English
  * http://bans.sachsenkneipe.de/index.php?page=banlist&lg=English


## Changes in web: ##

  * English, Spanish, French and Russian languages to choose by clicking on the flags
  * Link Orange color to bans with demo.
  * Yellow advice for banner with previus offenses and blue icon for more info when mouse over.
  * Show time in the date.
  * Blue icon for show the comments when mouse over.
  * Link to HLstatsX Community Edition searching this Steam\_ID.
  * Link to Steam Comunity Profile Steam\_ID when PHP extension bcmath is loaded.
  * Auto post of GlobalBan in e107 CMS forum with the ban info and link using the website link of the import bans. Because maybe the banned person may want to do a reply.
  * Delete Ban buttom (only access to fullpower administrators).
  * Kicks Counter for every banned player with every try of join server before the ban was expired.
  * Added statistics according to admin or reasons and type of ban: permanent, temporary or expired.
  * Allow to change admin, website link, server in update ban page.
  * No allow .dem demos files, only .rar and .zip.
  * Fix exploit allow upload PHP files and exec it.
  * Fix don´t allow ban other admin.
  * Fix link to 'update new ban' in the email that advise about a new ban added.
  * Added new configuration options for new features. (allow set the URL of your HLstatsX Community Edition Website, enable the e107 auto post option and your own website)
  * Add check in configuration.php if is\_writable("config/class.Config.php"
  * Add ini\_get("post\_max\_size") and ini\_get("upload\_max\_filesize") in configuration.php
  * Fix manageUsers.php don´t send password to new user when was registered by fullpower
admin from Configuration->Users.
  * Fix don´t required use PHP open tag.
  * Fix "Join Server" link in serverList.php new format steam://connect/213.149.245.86:27015
  * Fix allow select reason to import CFG bans
  * Add by Fantole allowed edit/delete Server Groups
  * Use this repository to check official vesion and not http://unbuinc.net
  * Add SiteLogo auto check if logo file is .swf (Flash Player) or normal imagine
  * Remove the call to style css/print.css because no file, professedly used when the document is printed.


## Changes in game: ##

  * Advise in game when connect a expired baned player, show left panel menu with previus nick name, reason, period, admin...
  * Kick message with reason and lenght to banned players.
  * 'Permanent' ban period is now the last option in lenght list of !banmenu panel.
  * Upgrade to the last Wget version for Windows server.
  * Temporal ban in Valve system set to 5 minutes, to prevent retry join.
  * Change all es.server.cmd() in GlobalBan.py because this command has been reported to crash some Linux servers. Now it use es.server.queuecmd instead.



![http://globalban-spanish.googlecode.com/svn/wiki/images/banlistcoment1.png](http://globalban-spanish.googlecode.com/svn/wiki/images/banlistcoment1.png)

This is the advise when a expired baned player join to the server:

![http://globalban-spanish.googlecode.com/svn/wiki/images/baningameadvise.png](http://globalban-spanish.googlecode.com/svn/wiki/images/baningameadvise.png)

![http://globalban-spanish.googlecode.com/svn/wiki/images/banedit1.png](http://globalban-spanish.googlecode.com/svn/wiki/images/banedit1.png)

![http://globalban-spanish.googlecode.com/svn/wiki/images/banlstadist1.png](http://globalban-spanish.googlecode.com/svn/wiki/images/banlstadist1.png)

![http://globalban-spanish.googlecode.com/svn/wiki/images/banlistcoment2.png](http://globalban-spanish.googlecode.com/svn/wiki/images/banlistcoment2.png)

![http://globalban-spanish.googlecode.com/svn/wiki/images/banforum1.png](http://globalban-spanish.googlecode.com/svn/wiki/images/banforum1.png)

![http://globalban-spanish.googlecode.com/svn/wiki/images/banforum2.png](http://globalban-spanish.googlecode.com/svn/wiki/images/banforum2.png)

# These are the files that are not yet in multi-language mode: #
**=== Public Area: ===
  1. Completed.**

**=== Install and Upgrade Area: ===
  1. /banned/install.php
  1. /banned/installComplete.php
  1. /banned/upgrade.php
  1. /banned/upgradeComplete.php**

**=== Admin Area: ===
  1. /banned/admin/badNames.php
  1. /banned/admin/banLengths.php
  1. /banned/admin/banReasons.php
  1. /banned/admin/manageAdminGroups.php
  1. /banned/admin/manageServerAdmins.php
  1. /banned/admin/manageServers.php
  1. /banned/admin/manageUsers.php
  1. /banned/admin/uploadAdmins.php
  1. /banned/admin/uploadBanLengths.php
  1. /banned/admin/uploadBanReasons.php**

Warning when some admin ban a player:
![http://globalban-spanish.googlecode.com/svn/wiki/images/Warning_to_all_when_new_ban.jpg](http://globalban-spanish.googlecode.com/svn/wiki/images/Warning_to_all_when_new_ban.jpg)

Warning if a banned-player try to join before the ban was expired:
![http://globalban-spanish.googlecode.com/svn/wiki/images/Advise_of_some_banned_player_trying_to_join.jpg](http://globalban-spanish.googlecode.com/svn/wiki/images/Advise_of_some_banned_player_trying_to_join.jpg)

Warning that only see the ex-banned player when the ban is espired:
![http://globalban-spanish.googlecode.com/svn/wiki/images/Warning_to_only_the_Expired-Banned_player_when_Join.jpg](http://globalban-spanish.googlecode.com/svn/wiki/images/Warning_to_only_the_Expired-Banned_player_when_Join.jpg)

Warning that see allbody when join a ex-banned player that have expired the ban:
![http://globalban-spanish.googlecode.com/svn/wiki/images/Warning_to_all_the_Expired-Banned_player_when_Join.jpg](http://globalban-spanish.googlecode.com/svn/wiki/images/Warning_to_all_the_Expired-Banned_player_when_Join.jpg)

Warning that see allbody when join a ex-banned player that have expired the ban:
![http://globalban-spanish.googlecode.com/svn/wiki/images/Warning_to_all_the_Expired-Banned_player_when_Join_(bis).jpg](http://globalban-spanish.googlecode.com/svn/wiki/images/Warning_to_all_the_Expired-Banned_player_when_Join_(bis).jpg)

New Configuration Options:
![http://globalban-spanish.googlecode.com/svn/wiki/images/configuration_r87.png](http://globalban-spanish.googlecode.com/svn/wiki/images/configuration_r87.png)