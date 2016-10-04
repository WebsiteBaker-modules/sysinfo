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
		<td><h1 class="p">WebsiteBaker - Latest news</h1></td>
	</tr>
	</tbody>
</table>
<br/>
<base href="http://www.websitebaker.org/"/>
<?php	

if (isset($_SESSION['wb_home_news_feed'])) {
	$rawFeed = $_SESSION['wb_home_news_feed'];
} else {
	$url = 'http://www.websitebaker.org/modules/news/rss.php?page_id=9';
	$rawFeed = file_get_contents($url);
	$_SESSION['wb_home_news_feed'] = $rawFeed;
}

libxml_use_internal_errors(true);
$news = simplexml_load_string($rawFeed);
if ($news === false) {
    echo "<b>Failed loading XML</b>";
    foreach(libxml_get_errors() as $error) {
        echo "<br> - ", $error->message;
    }
} else {
	foreach ( $news->channel->item as $item ) {
		echo '<table cellpadding="3" border="0">';
		echo '<tr><td class="h"><h3><a href="'.(string)$item->link.'" target="_blank">'.(string)$item->title.'</a><span style="float:right">'.(string)$item->pubDate.'</span></h3></td></tr>';
		echo '<tr><td class="v">'.(string)$item->description.'</td></tr>';
		echo '<tr><td class="h"><a href="'.(string)$item->link.'" target="_blank">Read more</a></td></tr>';
		echo '</table><br/>';
	}
}
?>


