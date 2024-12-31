<?php

namespace App;

// TODO: Improve the readability of this file through refactoring and documentation.

	// Create a constructor for defining parameters and variables used
	// Outline what each function does

require_once dirname( __DIR__ ) . '/globals.php';

class App {
	
public $ttl;
public $bd;
public $get;
	
// function that saves title and body content
	function save( $ttl, $bd ) {
		error_log( "Saving article $ttl, success!" );
		file_put_contents( $ttl, $bd );
	}

// function that allows title and body content to be updates
	function update( $ttl, $bd ) {
		$this->save( $ttl, $bd );
	}

// function that uses a get request to retrieve an article title 
	function fetch( $get ) {
		$title = $get['title'] ?? null;
		return is_array( $get ) ? file_get_contents( sprintf( 'articles/%s', $get['title'] ) ) :
			file_get_contents( sprintf( 'articles/%s', $_GET['title'] ) );
	}

// function that retrieves a list of articles
	public function getListOfArticles() {
		global $wgBaseArticlePath;
		return array_diff( scandir( $wgBaseArticlePath ), [ '.', '..', '.DS_Store' ] );
	}
}
