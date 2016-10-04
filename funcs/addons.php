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
		<td><h1 class="p">WebsiteBaker - Installed Addons</h1></td>
	</tr>
	</tbody>
</table>
<br/>

<?php	
get_addon('module');
get_addon('template');
get_addon('language');

function _is_in_use($mod_name, $type = 'module') {
	global $database;
	$query = "";
	if ($type == 'module') $query = "SELECT count('module') FROM `".TABLE_PREFIX."sections` WHERE `module`='$mod_name' ";
	if ($type == 'template') $query = "SELECT count('template') FROM `".TABLE_PREFIX."pages` WHERE `template`='$mod_name' ";
	if ($type == 'language') $query = "SELECT count('language') FROM `".TABLE_PREFIX."pages` WHERE `language`='$mod_name' ";
	if($query) return ($total = $database->get_one($query))?$total:'';
	return '';
}

function _is_default($mod_name, $type = 'module') {
	if ($type == 'language' && DEFAULT_LANGUAGE == $mod_name)  return "Default language ";
	if ($type == 'template' && DEFAULT_TEMPLATE == $mod_name)  return "Default template ";
	if ($type == 'template' && DEFAULT_THEME == $mod_name)  return "Default admin theme ";
	return '';
}

function get_addon($type = 'module') {
	global $database,$toolurl;
	echo "<table cellpadding='3' border='0'>
		<tr class='h'><td class='h' colspan='7'><h3>".$type."s</h3></td></tr>
		<tr><td class='h'>Name</td>
		<td class='h'>Used</td>
		<td class='h'>Type</td>
		<td class='h'>Version</td>
		<td class='h'>Platform</td>
		<td class='h'>Description</td>
		<td class='h'>Author</td>
		<td class='h'>License</td>
	</tr>";
	$res = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE `type`='$type' ORDER BY `function`");
	if($res && $res->numRows() > 0) {
		while ($row = $res->fetchRow()) {
			$inuse = _is_in_use($row['directory'],$type);
			$name = $row['function']=="page" && $inuse ? '<a title="find pages where this module is used" href="'.$toolurl.'&type=pages&mod='.$row['directory'].'">'.$row['name'].'</a>':$row['name'];
			$name = $type=="language" && $inuse ? '<a title="find pages where this language is used" href="'.$toolurl.'&type=pages&lng='.$row['directory'].'">'.$row['name'].'</a>':$name;
			echo "<tr>
				<td class='v'>".$name."</td>
				<td class='v'>"._is_default($row['directory'],$type).$inuse."</td>
				<td class='v'>".$row['type'].($row['function']?'/'.$row['function']:'')."</td>
				<td class='v'>".$row['version']."</td>
				<td class='v'>".$row['platform']."</td>
				<td class='v'>".$row['description']."</td>
				<td class='v'>".$row['author']."</td>
				<td class='v'>".$row['license']."</td>
				</tr>";
		}
	}
	echo "</table><br/>";
}