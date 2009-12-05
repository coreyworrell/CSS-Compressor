<?php
/**
 * Easily compress all your CSS files into one streamlined file.
 * This file strips your CSS of all unnecessary line breaks, spaces, and characters.
 *
 * @author   Corey Worrell
 * @url      http://coreyworrell.com
 * @version  1.0
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

// Name of generated "cache" file (w/out '.css' extension)
$compressed = 'compressed';
// End configuration

function bad_request()
{
	header('HTTP/1.1 400 Bad Request');
	echo 'HTTP/1.1 400 Bad Request';
	exit;
}

$url = parse_url($_SERVER['REQUEST_URI']);
$files = explode(',', $url['query']);

$compress = FALSE;

if (file_exists($compressed.'.css'))
{
	foreach ($files as $file)
	{
		if ( ! file_exists($file.'.css'))
		{
			bad_request();
		}
		
		if ($time = filemtime($file.'.css') AND $time > filemtime($compressed.'.css'))
		{
			$compress = TRUE;
		}
	}
}

if (file_exists($compressed.'.css') AND ! $compress)
{
	$css = file_get_contents($compressed.'.css');
}
else
{
	$file = '';
	$css  = '';
	
	foreach ($files as $file)
	{
		if ( ! file_exists($file.'.css'))
		{
			bad_request();
		}
		
		$css .= file_get_contents($file.'.css');
	}

	$replace = array
	(
		'/\s+/'                                     => ' ',
		'/[\s+]?(;|:|{|}|,)[\s+]?/'                 => '$1',
		'/[\t\r\n]/'                                => '',
		'/\/\*(.*?)\*\//'                           => '',
		'/;}/'                                      => '}',
		'/}(\s+)?/'                                 => '}',
		'/#([\da-f])\1([\da-f])\2([\da-f])\3/i'     => '#$1$2$3',
		'/([^\d])0(px|em|pt|ex|%|pc|cm|in|mm)/i'    => '${1}0',
	);

	$css = trim(preg_replace(array_keys($replace), array_values($replace), $css));
	
	$handle = fopen($compressed.'.css', 'w');
			  fwrite($handle, $css);
			  fclose($handle);
}

header('Content-type: text/css');

echo $css;