# CSS Compressor

Easily compress all your CSS files into one streamlined file.  
This file strips your CSS of all unnecessary line breaks, spaces, and characters.

## Usage

Example folder structure on server:

	httpdocs
		- css
			- css.php
			- grid.css
			- reset.css
			- styles.css
		- js
		- img
		- index.html

You would include the css like normal, using a `link` tag.  
Each file is seperated by a comma, and does not include the `.css` extension.

	<link rel="stylesheet" type="text/css" href="css/css.php?reset,grid,styles" />

To include a file from a remote website works just the same:

	<link rel="stylesheet" type="text/css" href="css/css.php?reset,grid,styles,http://mywebsite.com/css/global" />