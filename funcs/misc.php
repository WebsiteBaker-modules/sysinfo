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
?>
<table cellpadding="3" border="0">
	<tbody>
	<tr class="h">
		<td><h1 class="p">WebsiteBaker - Miscellaneous</h1></td>
	</tr>
	</tbody>
</table>
<br/>
<?php	
$htaccess = file_exists(WB_PATH.'/.htaccess') ? file_get_contents(WB_PATH.'/.htaccess'):'Not found';
$robots = file_exists(WB_PATH.'/robots.txt') ? file_get_contents(WB_PATH.'/robots.txt'):'Not found';
?>
<table>
	<tr class="v"><td><h3><?php echo WB_URL ?>/.htaccess</h3></td></tr>
	<tr><td class="e"><pre><?php echo htmlspecialchars($htaccess) ?></pre></td><tr>
</table>
<br/>
<table>
	<tr class="v"><td><h3><?php echo WB_URL ?>/robots.txt</h3></td></tr>
	<tr><td class="e"><pre><?php echo htmlspecialchars($robots) ?></pre></td><tr>
</table>



