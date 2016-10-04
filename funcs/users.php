<?php
/**
 *
 * @category        admintool
 * @package         phpinfo
 * @author          Ruud Eisinga - www.dev4me.nl
 * @link			http://www.dev4me.nl/
 * @license         http://www.gnu.org/licenses/gpl.html
 * @platform        WebsiteBaker 2.8.x
 * @version         0.4.1
 * @lastmodified    August 23, 2016
 *
 */

// Must include code to stop this file being access directly
if(defined('WB_PATH') == false) { die("Cannot access this file directly"); }

?>
<table cellpadding="3" border="0">
	<tbody>
	<tr class="h">
		<td><h1 class="p">WebsiteBaker - Users</h1></td>
	</tr>
	</tbody>
</table>
<br/>

<table cellpadding="3" border="0">
<tr><td class='h'>Login</td>
	<td class='h'>Username</td>
	<td class='h'>Email address</td>
	<td class='h'>Last login</td>
	<td class='h'>From IP</td>
</tr>
<?php	
	//$res = $database->query("SHOW TABLES");
	$res = $database->query("SELECT * FROM ".TABLE_PREFIX."users order by `login_when` DESC");
	if($res) {
		while ($row = $res->fetchRow()) {
			echo "<tr>
				<td class='v'>".$row['username']."</td>
				<td class='v'>".$row['display_name']."</td>
				<td class='v'>".$row['email']."</td>
				<td class='v'>".date(DATE_FORMAT." - ".TIME_FORMAT,$row['login_when'])."</td>
				<td class='v'>".$row['login_ip']."</td>
				</tr>";
		}
	}
?>
</table>
