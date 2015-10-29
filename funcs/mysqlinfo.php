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

if(is_object($database->DbHandle)) {
	$title = "MySQLi Info";
	$server_info 		= mysqli_get_server_info($database->DbHandle);
	$host_info 			= mysqli_get_host_info($database->DbHandle);
	$proto_info 		= mysqli_get_proto_info($database->DbHandle);
	$client_info 		= mysqli_get_client_info($database->DbHandle);
	$client_encoding 	= mysqli_character_set_name($database->DbHandle);
	$status = explode('  ', mysqli_stat($database->DbHandle));
} else {
	$title = "MySQL Info";
	$server_info 		= mysql_get_server_info();
	$host_info 			= mysql_get_host_info();
	$proto_info 		= mysql_get_proto_info();
	$client_info 		= mysql_get_client_info();
	$client_encoding 	= mysql_client_encoding();
	$status = explode('  ', mysql_stat());
}

?>
<table cellpadding="3" border="0">
	<tbody>
	<tr class="h">
		<td><h1 class="p"><?php echo $title ?></h1></td>
	</tr>
	</tbody>
</table>
<br/>
<h2>Server</h2>
<table cellpadding="3" border="0">
	<tr><td class='e'>Server version</td><td class='v'><?php echo $server_info ?></td></tr>
	<tr><td class='e'>Host info</td><td class='v'><?php echo $host_info ?></td></tr>
	<tr><td class='e'>Protocol version</td><td class='v'><?php echo $proto_info ?></td></tr>
	<tr><td class='e'>Client info</td><td class='v'><?php echo $client_info ?></td></tr>
	<tr><td class='e'>Client encoding</td><td class='v'><?php echo $client_encoding ?></td></tr>
</table>
<br/>
<h2>Status</h2>
<table cellpadding="3" border="0">
	<?php 
	foreach($status as $key => $value)  {
		$v = explode(':',$value);
		echo '<tr><td class="e">'.$v[0].'</td><td class="v">'.$v[1].'</td></tr>';
	}
	?>
</table>
<br/>
<h2>Tables in use</h2>

<table cellpadding="3" border="0">
<tr><td class='h'>Table</td>
	<td class='h'>Engine</td>
	<td class='h'>Collation</td>
	<td class='h'>Rows</td>
	<td class='h'>Auto_increment</td>
	<td class='h'>Last updated</td>
</tr>
<?php	
	//$res = $database->query("SHOW TABLES");
	$res = $database->query("SHOW TABLE STATUS");
	if($res) {
		while ($row = $res->fetchRow()) {
			echo "<tr>
				<td class='v'>".$row['Name']."</td>
				<td class='v'>".$row['Engine']."</td>
				<td class='v'>".$row['Collation']."</td>
				<td class='v'>".$row['Rows']."</td>
				<td class='v'>".$row['Auto_increment']."</td>
				<td class='v'>".$row['Update_time']."</td>
				</tr>";
		}
	}
?>
</table>
