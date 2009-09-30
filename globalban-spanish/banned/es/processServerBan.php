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

// This is the page that the ES script calls to ban
require_once(ROOTDIR."/include/class.rcon.php");
require_once(ROOTDIR."/include/database/class.BanQueries.php");
require_once(ROOTDIR."/include/database/class.UserQueries.php");
require_once(ROOTDIR."/include/database/class.LengthQueries.php");
require_once(ROOTDIR."/include/database/class.ServerQueries.php");
require_once(ROOTDIR."/include/objects/class.Length.php");
require_once(ROOTDIR."/include/objects/class.Server.php");
require_once(ROOTDIR."/include/database/class.ReasonQueries.php");

// Initialize Objects
$reasonQueries = new ReasonQueries();

// For getting from URL which is used by ES
$hash = $_GET['hash']; // User is passed if given from ES (admin's Steam ID)
$steamId = $_GET['steamId']; // One being banned
$lengthId = $_GET['len']; // The ID of the ban length or the real length if timescale specified
$timeScale = $_GET['ts']; // If this is set to "ignore" that means the length is the lengthID
$reason = $_GET['r']; // Reason of ban
$banner = $_GET['b']; // Steam ID of banner
$serverId = $_GET['sid'];
$nameOfBanned = $_GET['name']; // Name of banned user
$ipOfBanned = $_GET['ip']; // IP address of banned user

// Make sure the process in ES is calling it
// otherwise it is a hack attempt from the outside

if($hash == $config->matchHash) {

  // Make sure special chars for MySQL are escaped
  $nameOfBanned = addslashes($nameOfBanned);

  $banQueries = new BanQueries();
  $userQueries = new UserQueries();
  $lengthQueries = new LengthQueries();

  $user = $userQueries->getUserInfoBySteamId($banner);
  // i for ignore
  if($timeScale == "i") {
    $length = $lengthQueries->getBanLength($lengthId);
  } else {
    $length = new Length();
    $length->setLength($lengthId);
    $length->setTimeScale($timeScale);
  }
  
  $isUserMember = false;
  // If we are not allowing admin bans, then make sure the one being banned is not an admin
  if(!$config->allowAdminBans) {
    $isUserMember = $userQueries->isMember($steamId);
  }
  
  $username = trim($user->getName());
  
  $pending = 0; // Default pending state is off
  
  // HARDCODED: 4 = member
  if($user->getAccessLevel() == 4) {
    $pending = 1;
  }
  
  // Validate IP
  if(!preg_match("/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/", $ipOfBanned)) {
    $ipOfBanned = null;
  }
  
  // Do not ban if it was an admin
  if(!$isUserMember) {
    if($length->getLength() > 0) {
      $lengthInSec = $length->getLengthInSeconds();
      $expireDate = time() + $lengthInSec; // Expire date

      // Add the new ban non-perma ban
      if($length->getTimeScale() == "minutes" || $length->getTimeScale() == "hours" || ($length->getTimeScale() == "days" && $length->getLength() == 1)) {
        // 1 day bans or shorter take affect immediately for all members
        $banId = $banQueries->addBan($steamId, $length->getLength(), $length->getTimeScale(), $expireDate, $reason, $user->getName(), 0, $nameOfBanned, $serverId, $ipOfBanned, $banner);
      } else {
        // bans longer than 1 day are put into pending mode if the user only has member level priveliges
        $banId = $banQueries->addBan($steamId, $length->getLength(), $length->getTimeScale(), $expireDate, $reason, $user->getName(), $pending, $nameOfBanned, $serverId, $ipOfBanned, $banner);
      }
    } else {
      // Add perma ban
      $banId = $banQueries->addBan($steamId, $length->getLength(), $length->getTimeScale(), time(), $reason, $user->getName(), $pending, $nameOfBanned, $serverId, $ipOfBanned, $banner);
    }

	$motivo = $reasonQueries->getReason($reason);

    // Now kick the user
    kickUser($steamId, $serverId, eregi_replace("time",$length->getReadable(),$config->banMessage),$nameOfBanned, $length->getReadable(), $motivo);

    if($configOdonel->enableAutoPoste107Forum) {

      // Use this to build the URL link (replace processServerBan with updateBan)
      $url = "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
      $url = str_replace("processServerBan", "banlist", $url);

      $postId = NewPostForum_e107(addslashes($nameOfBanned)." - ".addslashes($steamId),"[b]Admin:[/b] [color=#009900]".addslashes($username)."[/color]\r\n\r\n[b]Nick Baneado: [/b][color=#990000][link=".$url."&searchText=".addslashes($steamId)."]".addslashes($nameOfBanned)." - ".addslashes($steamId)."[/link][/color]\r\n\r\n[b]Motivo:[/b] ".$motivo."\r\n\r\n[b]Periodo:[/b] ".$length->getReadable(), time(), $configOdonel);
      UpdateBanWebpage ($postId , $banId, $configOdonel);
	}
  }
  
  // Make sure $banId is valid and that the user wants emails sent
  if($banId > 0 && $config->sendEmails) {
    // Email
    $subject = "Ban Added In-Game by ".$user->getName();
    
    $body = "<html><body>";
    $body .= "The following ban has been added by ";
    if($member) {
      $body .= "a Member and MUST be reviewed.";
    } else {
      $body .= "an Admin.";
    }
    $body .= "\n\n";
    
    // Use this to build the URL link (replace processServerBan with updateBan)
    $url = "http://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    $url = str_replace("processServerBan", "updateBan", $url);
    
      $body .= "\n\n";
      $body .= "Haga click en el siguiente link para ver el ban: <a href='".$url."&banId=".$banId."'>Nuevo Ban</a>";
      $body .= "<p>".$bannedName." (".$steamId.") ha sido baneado de todos los servidores.</p>";  
	  if($configOdonel->enableAutoPoste107Forum) {
	    $body .= "<p>Post en el Foro: <a href='".$configOdonel->e107Url."e107_plugins/forum/forum_viewtopic.php?".$postId."'>Link</a></p>";
      }
      $body .= "</body></html>";
      
    $banManagerEmails = $config->banManagerEmails;
    for($i=0; $i<count($banManagerEmails); $i++) {
      
      // To send HTML mail, the Content-type header must be set
      $headers  = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type: text/html; charset=utf-8" . "\r\n";			
      // Additional headers
      $headers .= "From: ".$config->siteName." Ban Management <".$config->emailFromHeader.">" . "\r\n";
      
      // Send an email message to those that wish to recieve a notice of a newly added ban
      mail($banManagerEmails[$i], $subject, $body, $headers);
    }
  }
}

// Kick the user from the specified server
function kickUser($steamId, $serverId, $message, $nameOfBanned, $periodo, $motivo) {
  // Leave this in to be compatible with the alternate thread version
  $kick = "kickid";
  $command = $kick." \"".$steamId."\" ".$message;
  echo $command;
  
  // This will send an RCON command to the server
  $serverQueries = new ServerQueries();

  $server = $serverQueries->getServer($serverId);

  $r = new rcon($server->getIp(),$server->getPort(),$server->getRcon());
  if($r->isValid()) {
    $r->Auth();
    $r->kickUser($steamId, $message);
    $r->sendRconCommand("banid 5 "."\"".$steamId."\" ");
	$r->sendRconCommand("es_msg #multi #green Baneados: #lightgreen ".$nameOfBanned." #green ha sido baneado #lightgreen ".$periodo." #green por #lightgreen ".$motivo." con la #lightgreen ".$steamId." #green !!!");
  }
}

function NewPostForum_e107($TituloPost, $AsuntoPost, $now, $configOdonel) {

	// Connecting, selecting database
    $link = mysql_connect($configOdonel->e107_dbHostName, $configOdonel->e107_dbUserName, $configOdonel->e107_dbPassword)
	    or die('No se pudo conectar a la BD_e107: ' . mysql_error());
	echo 'Connected successfully';
	mysql_select_db($configOdonel->e107_dbName) or die('Could not select database');
	
	// Performing SQL query
	$query = "INSERT INTO `".$configOdonel->e107TablePrefix."forum_t` (`thread_id`, `thread_name`, `thread_thread`, `thread_forum_id`, `thread_datestamp`, `thread_parent`, `thread_user`, `thread_views`, `thread_active`, `thread_lastpost`, `thread_s`, `thread_edit_datestamp`, `thread_lastuser`, `thread_total_replies`) ";
	$query .= "VALUES (NULL, '".$TituloPost."', '".$AsuntoPost."', '".$configOdonel->e107_bans_forum_category_number."', '".$now."', '0', '".$configOdonel->e107_GlobalBan_user."', '0', '1', '".$now."', '0', '0', '', '0')";
	
	mysql_query($query) or die('Query failed: ' . mysql_error());
	
	$insertId = mysql_insert_id();
    
	// Closing connection
	mysql_close($link);    
    
	return $insertId;
}

function UpdateBanWebpage ($postId , $banId, $configOdonel){
	
	$banQueries = new BanQueries();
    $banQueries->updateBanWebpage ($configOdonel->e107Url."e107_plugins/forum/forum_viewtopic.php?".$postId , $banId);

}
?>