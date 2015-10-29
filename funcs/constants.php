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

$ver = defined(WB_VERSION) ? WB_VERSION : VERSION;
$rev = defined(WB_REVISION)? WB_REVISION : REVISION;
?>
<table cellpadding="3" border="0">
	<tbody>
	<tr class="h">
		<td><h1 class="p">WebsiteBaker - Version <?php echo $ver ?> - Revision <?php echo $rev ?></h1></td>
	</tr>
	</tbody>
</table>
<br/>
<?php
$c = get_defined_constants(true);
$cu = $c['user'];
ksort($cu);
echo '<table>';
echo '	<tr class="h"><td><h3 class="p">Defines</h3></td></tr>';
foreach($cu as $key => $value) {
	$value = htmlspecialchars($value);
	if(stripos($key,'pass') !== false) $value = '<i>hidden</i>';
	if($value===false) $value = '<i>false</i>';
	if($value===true) $value = '<i>true</i>';
	if($value==="") $value = '<i>no value</i>';
	echo "<tr><td class='e'>$key</td><td class='v'>".$value."</td></tr>";
}
echo '</table><br/>';

echo '<table>';
echo '	<tr class="h"><td><h3 class="p">Session variables</h3></td></tr>';
foreach($_SESSION as $key => $value) {
	if(is_array($value)) $value = 'array('.implode (', ',$value).')';
	$value = htmlspecialchars($value);
	if(stripos($key,'pass') !== false) $value = '<i>hidden</i>';
	if($value===false) $value = '<i>false</i>';
	if($value===true) $value = '<i>true</i>';
	if($value==="") $value = '<i>no value</i>';
	echo "<tr><td class='e'>_SESSION['".$key."']</td><td class='v'>".$value."</td></tr>";
}
echo '</table>';