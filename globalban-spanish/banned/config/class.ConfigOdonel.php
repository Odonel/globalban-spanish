<?php
/*
    This file is part of GlobalBan.

    Written by Stefan Jonasson <soynuts@unbuinc.net>
    Copyright 2008 Stefan Jonasson
    
    GlobalBan is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    GlobalBan is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with GlobalBan.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 *  This makes it easier to include configuration variables to other classes
 *  by simply extending the class with the Config class.  Any new variables that
 *  get added MUST have a getter as that will be the only way to retrieve a Config
 *  value if the config value needs to be used outside as it's own object.
 *
 *  Change ALL values below to what you desire for your website.  If you did not
 *  change the gban.sql file, then the database name will be global_ban.  Otherwise
 *  all other variables, espeically those in the database block should be changed
 *  appropriately.
 */

class ConfigOdonel {
  /**
   * HLstatsX Community Edition specific settings (http://www.hlxcommunity.com/)
   */

  var $HLstatsUrl = "http://clanlds.nls.es/estadisticas/"; // Your stats web site Ej: "http://clanlds.nls.es/estadisticas/"


  /**
   * e107 Forum integration settings
   */
  var $enableAutoPoste107Forum = false;  // Whether to enable Auto-Post in the e107 Forum
  var $e107Url = "http://clanlds.nls.es/"; // Your e107 web site Ej: "http://www.e107.com"
  var $e107_dbName = "e107"; // Set the e107 Database to access
  var $e107_dbUserName = "dbuser"; // Set the Database's user name login (recommend a user with only select and insert privs)
  var $e107_dbPassword = "dbpass"; // Set the Database user's password login
  var $e107_dbHostName = "localhost"; // Set the Database's host
  
  var $e107TablePrefix = "e107_"; // The prefix of the SMF tables
  var $e107_bans_forum_category_number= "1"; // 
  var $e107_GlobalBan_user= "500.GlobalBan";
  
  function __construct() {
  }

  function ConfigOdonel() {
  }
}
?>