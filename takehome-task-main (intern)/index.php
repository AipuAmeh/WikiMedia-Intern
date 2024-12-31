<?php

// TODO A: Improve the readability of this file through refactoring and documentation.

	// Added spacing and comments, grouped items together

// TODO B: Review the HTML structure and make sure that it is valid and contains
// required elements. Edit and re-organize the HTML as needed.

	// Create a new page for functions to import and remove "echo"s

// TODO C: Review the index.php entrypoint for security and performance concerns
// and provide fixes. Note any issues you don't have time to fix.

	// I would add php to sanitize the action of post that is attached to the form element. That way, if an attacker tries to 
	// input special characters or another link in the text fields, it will not work. 

// TODO D: The list of available articles is hardcoded. Add code to get a
// dynamically generated list.

	// I would use the function found on App.php to retrieve all the articles,
	// map through the array, and echo out a list item with 
	// the title of each article



use App\App;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/App.php';

$app = new App();

// function for finding and retrieving article wordcount
function wfGetWc() {
	global $wgBaseArticlePath;
	$wgBaseArticlePath = 'articles/';
	$wc = 0;
	$dir = new DirectoryIterator( $wgBaseArticlePath );
	foreach ( $dir as $fileinfo ) {
		if ( $fileinfo->isDot() ) {
			continue;
		}
		$c = file_get_contents( $wgBaseArticlePath . $fileinfo->getFilename() );
		$ch = explode( " ", $c );
		$wc += count( $ch );
	}
	return "$wc words written";
}

// variables
$title = '';
$body = '';
$wordCount = wfGetWc();

echo "<head>
<link rel='stylesheet' href='http://design.wikimedia.org/style-guide/css/build/wmui-style-guide.min.css'>
<link rel='stylesheet' href='styles.css'>
<script src='main.js'></script>
</head>";

if ( isset( $_GET['title'] ) ) {
	$title = htmlentities( $_GET['title'] );
	$body = $app->fetch( $_GET );
	$body = file_get_contents( sprintf( 'articles/%s', $title ) );
}

// checks whether form has been submitted as post
// if so, form should be validated
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$title = test_input($_POST["title"]);
	$body = test_input($_POST["body"]);
}

// function that validates all data by trimming and stripping unnecessary characters
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
  }

echo "<body>";
echo "<div id='header' class='header'>
<a href='/'>Article editor</a><div>$wordCount</div>
</div>";
echo "<div class='page'>";
echo "<div class='main'>";
echo "<h2>Create/Edit Article</h2>
<p>Create a new article by filling out the fields below. Edit an article by typing the beginning of the title in the title field, selecting the title from the auto-complete list, and changing the text in the textfield.</p>
<form method='post' action='index.php' >
<input autofocus name='title' type='text' placeholder='Article title...' value={$title}>
<br />
<textarea name='body' type='text' placeholder='Article body...' >$body</textarea>
<br />
<a class='submit-button' href='#' />Submit</a>
<br />
<h2>Preview</h2>
$title\n\n
$body
<h2>Articles</h2>
<ul>
<li><a href='/index.php?title=Foo'>Foo</a></li>

</ul>
</form>";

if ( $_POST ) {
	$app->save( sprintf( "articles/%s", $_POST['title'] ), $_POST['body'] );
}
echo "</div>";
echo "</div>";
echo "</body";

