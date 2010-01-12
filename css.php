<?php
/**
 * Easily compress all your CSS files into one streamlined file.
 * This file strips your CSS of all unnecessary line breaks, spaces, and characters.
 *
 * @author   Corey Worrell
 * @url      http://coreyworrell.com
 * @version  1.1
 *
 * Usage:
 * <link rel="stylesheet" type="text/css" href="css.php?reset,grid,styles" />
 *
 * Example folder structure:
 * httpdocs
 *   - css
 *      - css.php
 *      - grid.css
 *      - reset.css
 *      - styles.css
 *   - js
 *   - img
 *   - index.html
 */

define('EXT', '.css');

$url = explode('?', $_SERVER['REQUEST_URI'], 2);
$cache = md5($url[1]).EXT;
$files = explode(',', $url[1]);

$cache_exists = file_exists($cache);
$cache_mtime  = $cache_exists ? filemtime($cache) : NULL;

$compress = $cache_exists ? FALSE : TRUE;

foreach ($files as $key => $file)
{
	$file = $files[$key] = $file.EXT;
	
	if (strpos($file, '://') === FALSE)
	{
		if ( ! file_exists($file))
		{
			header('Content-type: text/html');
			header('HTTP/1.1 400 Bad Request');
			exit('File <code>'.$file.'</code> could not be found.');
		}
		
		if ( ! $compress AND filemtime($file) > $cache_mtime)
		{
			$compress = TRUE;
		}
	}
}

if ($compress)
{
	$replace = array
	(
		'/\s+/'                                  => ' ',
		'/[\s+]?(;|:|{|}|,)[\s+]?/'              => '$1',
		'/[\t\r\n]/'                             => '',
		'/\/\*(.*?)\*\//'                        => '',
		'/;}/'                                   => '}',
		'/}(\s+)?/'                              => '}',
		'/#([\da-f])\1([\da-f])\2([\da-f])\3/i'  => '#$1$2$3',
		'/([^\d])0(px|em|pt|ex|%|pc|cm|in|mm)/i' => '${1}0',
	);
	
	$css = '';
	
	foreach ($files as $file)
	{
		$css .= file_get_contents($file);
	}

	$css = trim(preg_replace(array_keys($replace), array_values($replace), $css));
	
	file_put_contents($cache, $css);
}
else
{
	$css = file_get_contents($cache);
}

header('Content-type: text/css');

exit($css);