<?php

	ini_set( "display_errors", true ); 			// Display errors in browser - helpful for debugging.
												// Remove if ever deployed.
	
	date_default_timezone_set( "America/Los_Angeles" );  
	
	// Database access details
	define( "DB_DSN", "mysql:host=localhost;dbname=cms" );
	define( "DB_USERNAME", "root" ); 			// Remember to delete if ever shared on Git.
	define( "DB_PASSWORD", "password" ); 		// Remember to delete if ever shared on Git.
	
	// Path names, relative to project's root folder
	define( "CLASS_PATH", "classes" );			// Path to the class files.
	define( "TEMPLATE_PATH", "templates" );		// Where our script should look for HTML template files.

	
	define( "HOMEPAGE_NUM_ARTICLES", 5 );		// Limit the homepage to 5 articles
	
	// Define admin access details
	define( "ADMIN_USERNAME", "admin" );
	define( "ADMIN_PASSWORD", "password" );
	
	require( CLASS_PATH . "/Article.php" ); 	// The article class file will be required by all scripts,
												// so let's add it to the class path.


	// A shortcut to handle exceptions. We define a new exception handler,
	// and set it as our default handler by calling PHP’s set_exception_handler() function.
	// The “proper” way to handle exceptions is to wrap all the PDO calls within Article.php in try/catch blocks.
	function handleException( $exception ) {
		echo "Sorry, a problem occurred. Please try later.";
		error_log( $exception->getMessage() );
	}
	

	
/*
 *	Security Note:
 *
 *	In a live server environment it’d be a good idea to place config.php somewhere 
 *	outside your website’s document root, since it contains usernames and passwords.
*/	
	
?>