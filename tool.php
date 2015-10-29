<?php
/**
 *
 * @category        admintool
 * @package         phpinfo
 * @author          Ruud Eisinga - www.dev4me.nl
 * @link			http://www.dev4me.nl/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @version         0.4.0
 * @lastmodified    April 8, 2014
 *
 */

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { die("Cannot access this file directly"); }
if(!defined('THEME_URL')) define('THEME_URL', ADMIN_URL);

//check admin groups
/*
$groups = explode(",", $_SESSION['GROUPS_ID']);
if(!in_array(1,$groups)) {
	echo "Sorry, No access";
	return;
}
*/
$type = isset($_GET['type']) ? $_GET['type']:'';
$toolurl = ADMIN_URL.'/admintools/tool.php?tool=sysinfo'
?>
<div class="phpinfo <?php echo DEFAULT_THEME ?>">
	<div class="sysmenu h">
		<a href="<?php echo $toolurl ?>&type=front">WebsiteBaker News</a>
		<a href="<?php echo $toolurl ?>&type=phpinfo">PHP info</a>
		<a href="<?php echo $toolurl ?>&type=mysql">MySQL info</a>
		<a href="<?php echo $toolurl ?>&type=permissions">File Permissions</a>
		<a href="<?php echo $toolurl ?>&type=constants">WB Constants</a>
		<a href="<?php echo $toolurl ?>&type=addons">Addons</a>
		<a href="<?php echo $toolurl ?>&type=pages">Pages</a>
		<a href="<?php echo $toolurl ?>&type=users">Users</a>
		<a href="<?php echo $toolurl ?>&type=misc">Misc.</a>
	</div>
<br/>
<br/>
<?php 
if ($type == 'constants') { 
	include ('funcs/constants.php');
} elseif ($type == 'mysql') { 
	include ('funcs/mysqlinfo.php');
} elseif ($type == 'permissions') { 
	include ('funcs/permissions.php');
} elseif ($type == 'addons') { 
	include ('funcs/addons.php');
} elseif ($type == 'pages') { 
	include ('funcs/pages.php');
} elseif ($type == 'users') { 
	include ('funcs/users.php');
} elseif ($type == 'phpinfo') { 
	include ('funcs/phpinfo.php');
} elseif ($type == 'front') { 
	include ('funcs/frontpage.php');
} elseif ($type == 'misc') { 
	include ('funcs/misc.php');
} else {	
?>
<table cellpadding="5" cellspacing="0" border="0" align="center" width="100%" style="border: 2px solid #f00; padding:0;">
	<tbody>
	<tr >
		<td class="">
			<h3>WARNING</h3>
			This tool exposes a lot of information about your current WebsiteBaker installation and your webspace configuration.<br/>
			It is intended to be used for debugging puposes only!<br/><br/>
			You <strong>must make sure</strong> no other users you allow to login to this system are able to see this information.<br/>
			This can be done by setting the correct permissions in the groups information linked to your users, or simply by uninstalling this tool when you do not need to use it!<br/><br/>
			The developer of this tool and the WebsiteBaker organisation will take no responsibility for any misue of the information exposed!<br/><br/>
		</td>
     </tr>
	</tbody>
</table>
<?php } ?>
</div>
<script type="text/javascript">
<!--
	if (document.getElementsByTagName) {
		var anchors = document.getElementsByTagName("a");
		var thisPage = location.href;
		for (var i=0; i<anchors.length; i++) {
			var anchor = anchors[i];
			thisHREF = anchor.getAttribute("href");
			if ((thisHREF == thisPage) || (location.protocol + "//" + location.hostname + thisHREF == thisPage)) {
				anchor.id = "current";
			}
		}
	}
-->
</script>
