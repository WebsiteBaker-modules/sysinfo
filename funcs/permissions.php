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
clearstatcache();
$path = isset($_GET['path']) ? 
	$admin->add_slashes($_GET['path']): '/';
	
$r = scan_directory_recursively(WB_PATH.$path);
?>
<table cellpadding="3" border="0">
	<tbody>
	<tr class="h">
		<td><h1 class="p">WebsiteBaker - Permission checking</h1></td>
	</tr>
	</tbody>
</table>
<br/>

<table cellpadding="3" border="0">
	<tbody>
	<tr class="h">
		<td>Tree: 
<?php 
$tree = explode('/', $path);
$p = '';
echo '<a href="'.$toolurl.'&type=permissions&path=/">root</a> / ';
foreach($tree as $elem) {
	if($elem) {
	  $p .= '/'.$elem;
	  echo '<a href="'.$toolurl.'&type=permissions&path='.$p.'">'.$elem.'</a> / ';
	}
}
?>
		</td>
	</tr>
	</tbody>
</table>
<br/>
<table cellpadding="3" border="0">
<tr><td class='h'>Directory / File</td>
	<td class='h'>Permissions</td>
	<td class='h'>Last modified</td>
	<td class='h'>Can read</td>
	<td class='h'>Can write</td>
	<td class='h'>Owner / Group</td>
</tr>
<?php 
foreach($r as $ra) {
	$p = str_replace(WB_PATH,'',$ra['path']);
	if($ra['kind']=='directory') $p = '<a href="'.$toolurl.'&type=permissions&path='.$p.'">'.$p.'</a>';
	$class="good";
	if($ra['details']['perms']['octal1']=='777') $class="w";
	if($ra['details']['perms']['octal1']=='666') $class="w";
	
	echo '<tr>';
	echo '<td class="v">'.$p.'</td>';
	echo '<td class="v '.$class.'">'.$ra['details']['perms']['octal2'].'  ('.$ra['details']['perms']['human'].')</td>';
	echo '<td class="v">'.$ra['details']['time']['modified'].'</td>';
	echo '<td class="v">'.$ra['details']['filetype']['is_readable'].'</td>';
	echo '<td class="v">'.$ra['details']['filetype']['is_writable'].'</td>';
	if(isset($ra['details']['owner']['owner']['name'])) {
		echo '<td class="v">'.$ra['details']['owner']['owner']['name']. ' / ' .$ra['details']['owner']['group']['name'].'</td>';
	} else {
		echo '<td class="v">'.$ra['details']['owner']['fileowner']. ' / ' .$ra['details']['owner']['filegroup'].'</td>';
	}
	echo '</tr>';
}
?>
</table>
<?php

function scan_directory_recursively($directory, $filter=FALSE)
{
	if(substr($directory,-1) == '/')
	{
		$directory = substr($directory,0,-1);
	}
	if(!file_exists($directory) || !is_dir($directory))
	{
		return FALSE;
	}elseif(is_readable($directory))
	{
		$directory_tree = array();
		$directory_list = opendir($directory);
		while($file = readdir($directory_list))
		{
			if($file != '.' && $file != '..')
			{
				$path = $directory.'/'.$file;
				if(is_readable($path))
				{
					$subdirectories = explode('/',$path);
					if(is_dir($path))
					{
						$directory_tree[] = array(
							'path'      => $path,
							'name'      => end($subdirectories),
							'kind'      => 'directory',
							'details' => alt_stat($path));
							//'content'   => scan_directory_recursively($path, $filter));
					}elseif(is_file($path))
					{
						$finfo = explode('.',end($subdirectories));
						$extension = end($finfo);
						if($filter === FALSE || $filter == $extension)
						{
							$directory_tree[] = array(
							'path'		=> $path,
							'name'		=> end($subdirectories),
							'extension' => $extension,
							'size'		=> filesize($path),
							'kind'		=> 'file',
							'details' => alt_stat($path));
						}
					}
				}
			}
		}
		closedir($directory_list);
		return $directory_tree;
	}else{
		return FALSE;
	}
}

function alt_stat($file) {
 
 $ss=@stat($file);
 if(!$ss) return false; //Couldnt stat file
 
 $ts=array(
  0140000=>'ssocket',
  0120000=>'llink',
  0100000=>'-file',
  0060000=>'bblock',
  0040000=>'ddir',
  0020000=>'cchar',
  0010000=>'pfifo'
 );
 
 $p=$ss['mode'];
 $t=decoct($ss['mode'] & 0170000); // File Encoding Bit
 
 $str =(array_key_exists(octdec($t),$ts))?$ts[octdec($t)]{0}:'u';
 $str.=(($p&0x0100)?'r':'-').(($p&0x0080)?'w':'-');
 $str.=(($p&0x0040)?(($p&0x0800)?'s':'x'):(($p&0x0800)?'S':'-'));
 $str.=(($p&0x0020)?'r':'-').(($p&0x0010)?'w':'-');
 $str.=(($p&0x0008)?(($p&0x0400)?'s':'x'):(($p&0x0400)?'S':'-'));
 $str.=(($p&0x0004)?'r':'-').(($p&0x0002)?'w':'-');
 $str.=(($p&0x0001)?(($p&0x0200)?'t':'x'):(($p&0x0200)?'T':'-'));
 
 $s=array(
 'perms'=>array(
  'umask'=>sprintf("%04o",@umask()),
  'human'=>$str,
  'octal1'=>sprintf("%o", ($ss['mode'] & 000777)),
  'octal2'=>sprintf("0%o", 0777 & $p),
  'decimal'=>sprintf("%04o", $p),
  'fileperms'=>@fileperms($file),
  'mode1'=>$p,
  'mode2'=>$ss['mode']),
 
 'owner'=>array(
  'fileowner'=>$ss['uid'],
  'filegroup'=>$ss['gid'],
  'owner'=>
  (function_exists('posix_getpwuid'))?
  @posix_getpwuid($ss['uid']):'',
  'group'=>
  (function_exists('posix_getgrgid'))?
  @posix_getgrgid($ss['gid']):''
  ),
 
 'file'=>array(
  'filename'=>$file,
  'realpath'=>(@realpath($file) != $file) ? @realpath($file) : '',
  'dirname'=>@dirname($file),
  'basename'=>@basename($file)
  ),

 'filetype'=>array(
  'type'=>substr($ts[octdec($t)],1),
  'type_octal'=>sprintf("%07o", octdec($t)),
  'is_file'=>@is_file($file),
  'is_dir'=>@is_dir($file),
  'is_link'=>@is_link($file),
  'is_readable'=> @is_readable($file),
  'is_writable'=> @is_writable($file)
  ),
  
 'size'=>array(
  'size'=>$ss['size'], //Size of file, in bytes.
  'blocks'=>$ss['blocks'], //Number 512-byte blocks allocated
  'block_size'=> $ss['blksize'] //Optimal block size for I/O.
  ),
 
 'time'=>array(
  'mtime'=>$ss['mtime'], //Time of last modification
  'atime'=>$ss['atime'], //Time of last access.
  'ctime'=>$ss['ctime'], //Time of last status change
  'accessed'=>@date('Y-m-d H:i:s',$ss['atime']),
  'modified'=>@date('Y-m-d H:i:s',$ss['mtime']),
  'created'=>@date('Y-m-d H:i:s',$ss['ctime'])
  ),
 );
 
 clearstatcache();
 return $s;
}