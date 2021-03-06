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
$thisurl = $toolurl.'&type=pages';

function _build_pagelist($parent) {
      global $database, $links;
      $iterated_parents = array(); // keep count of already iterated parents to prevent duplicates
 
 
      if ( $query_pages = $database->query("SELECT * FROM ".TABLE_PREFIX."pages WHERE parent = ".$parent." ORDER BY level, position ASC")) {
            while($res = $query_pages->fetchRow()) {
				$links[] = $res;
                if (!in_array($res['page_id'], $iterated_parents)) {
                    _build_pagelist($res['page_id']);
                    $iterated_parents[] = $res['page_id'];
                }
            }
      }
}

function _build_posts() {
      global $database, $links;
      if ( $query_pages = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_news_posts WHERE `link` != '' ORDER BY position ASC")) {
            while($res = $query_pages->fetchRow()) {
				$links[] = $res;
            }
      }
}

function _create_page($page_id) {
	global $database;
	$link = $database->get_one("SELECT `link` FROM ".TABLE_PREFIX."pages WHERE page_id = ".$page_id);
	$filename = WB_PATH.PAGES_DIRECTORY.$link.PAGE_EXTENSION;
	$level = level_count($page_id);
	create_access_file($filename,$page_id,$level);
}

function _create_post($post_id) {
		global $database, $admin, $MESSAGE;
		
		$query_posts = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_news_posts WHERE `post_id` = '$post_id'");
		if(!$query_posts || $query_posts->numRows() == 0) return false;
        $res = $query_posts->fetchRow();
		$link = $res['link'];
		$page_id = $res['page_id'];
		$section_id = $res['section_id'];
		
		$sPagesPath = WB_PATH.PAGES_DIRECTORY;
		$sPostsPath = $sPagesPath.'/posts';

		if(!file_exists($sPostsPath)) {
			if(is_writable($sPagesPath)) {
				make_dir(WB_PATH.PAGES_DIRECTORY.'/posts/');
			}else {
				$admin->print_error($MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE']);
			}
		}

		if(!is_writable($sPostsPath.'/')) {
			$admin->print_error($MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE']);
		}

		$newFile = $sPagesPath.$link.PAGE_EXTENSION;
		// $backSteps = preg_replace('/^'.preg_quote(WB_PATH).'/', '', $sPostsPath);
		$backSteps = preg_replace('@^'.preg_quote(WB_PATH).'@', '', $sPostsPath);
		$backSteps = str_repeat( '../', substr_count($backSteps, '/'));
		$content =
			'<?php'."\n".
			'// *** This file is generated by WebsiteBaker Ver.'.WB_VERSION."\n".
			'// *** Creation date: '.date('c')."\n".
			'// *** Do not modify this file manually'."\n".
			'// *** WB will rebuild this file from time to time!!'."\n".
			'// *************************************************'."\n".
			"\t".'$page_id      = '.$page_id.';'."\n".
			"\t".'$section_id   = '.$section_id.';'."\n".
			"\t".'$post_id      = '.$post_id.';'."\n".
			"\t".'$post_section = '.$section_id.';'."\n".
//			"\t".'define(\'POST_SECTION\', '.$section_id.');'."\n".
//			"\t".'define(\'POST_ID\',      '.$post_id.');'."\n".
			"\t".'require(\''.$backSteps.'index.php\');'."\n".
			'// *************************************************'."\n";
		if( file_put_contents($newFile, $content) !== false ) {
		// Chmod the file
			change_mode($newFile);
		}else {
			$admin->print_error($MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE'],ADMIN_URL.'/pages/modify.php?page_id='.$page_id);
			// $admin->print_error($MESSAGE['PAGES_CANNOT_CREATE_ACCESS_FILE'].': '.$newFile);

		}
}

function _page_exists($link) {
	return file_exists(WB_PATH.PAGES_DIRECTORY.$link.PAGE_EXTENSION)?'Exists':'<span style="color:red">Missing</span>';
}
function _mod_used($page_id) {
	global $database;
	$rval = '';
    if ( $query_sections = $database->query("SELECT * FROM ".TABLE_PREFIX."sections WHERE page_id = ".$page_id." ORDER BY position ASC")) {
		while($res = $query_sections->fetchRow()) {
			$rval .= $rval ? ", ":"";
			$rval .= $res['module'];
		}
	}
	return $rval;
}

$create = isset($_GET['create'])? intval($_GET['create']) : false;
if ($create !== false) _create_page($create);
$post = isset($_GET['post'])? intval($_GET['post']) : false;
if ($post !== false) _create_post($post);

$mod = isset($_GET['mod'])? $_GET['mod'] : false; // show only one module
$title = $mod ? 'Pages using the module "'.$mod.'"' : 'Pages';
$lang = isset($_GET['lng'])? $_GET['lng'] : false; // show only one module
$title = $lang ? 'Pages using the language "'.$lang.'"' : $title;

$links = array();
_build_pagelist(0);
?>
<table cellpadding="3" border="0">
	<tbody>
	<tr class="h">
		<td><h1 class="p">WebsiteBaker - Pages check</h1></td>
	</tr>
	</tbody>
</table>
<br/>

<h2><?php echo $title ?></h2>
<table cellpadding='3' border='0'>
	<tr>
		<td class='h'>ID</td>
		<td class='h'>Title</td>
		<td class='h'>Visibility</td>
		<td class='h'>Language</td>
		<td class='h'>Link</td>
		<td class='h'>Modules</td>
		<td class='h'>Check</td>
		<td class='h'>Rebuild</td>
	</tr>
<?php
	foreach ($links as $link) {
		$used = _mod_used($link['page_id']);
		if ((!$mod && !$lang) || strpos ($used,$mod) !== false || $link['language'] == $lang) {
			echo "<tr>
				<td class='v small'>".$link['page_id']."</td>
				<td class='v'><a title='Open this page' href='".page_link($link['link'])."' target='_blank'>".str_repeat("+ ",$link['level']).$link['page_title']."</a></td>
				<td class='v'>".$link['visibility']."</td>
				<td class='v'>".$link['language']."</td>
				<td class='v'>".$link['link']."</td>
				<td class='v'>"._mod_used($link['page_id'])."</td>
				<td class='v small'>"._page_exists($link['link'])."</td>
				<td class='v small'><a href='".$thisurl."&create=".$link['page_id']."'>Recreate</a></td>
				</tr>";
		}
	}
?>
</table><br/>

<?php 
if (!$mod && !$lang) {

	$links = array();
	_build_posts();

?>
<h2>News posts</h2>
<table cellpadding='3' border='0'>
	<tr>
		<td class='h'>ID</td>
		<td class='h'>Title</td>
		<td class='h'>active</td>
		<td class='h'>Link</td>
		<td class='h'>Check</td>
		<td class='h'>Rebuild</td>
	</tr>
<?php
	foreach ($links as $link) {
		echo "<tr>
				<td class='v small'>".$link['post_id']."</td>
				<td class='v'><a title='Open this page' href='".page_link($link['link'])."' target='_blank'>".$link['title']."</a></td>
				<td class='v'>".$link['active']."</td>
				<td class='v'>".$link['link']."</td>
				<td class='v small'>"._page_exists($link['link'])."</td>
				<td class='v small'><a href='".$thisurl."&post=".$link['post_id']."'>Recreate</a></td>
				</tr>";
	}
?>
</table><br/>
<?php }
