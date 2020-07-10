<?php

    // This script controls the display of frontend pages on the site.    

    // Include the config file.
    // Note: require() instead of include(); require() throws error if file not found.
    require( "config.php" );

    // Fetch the action parameter, if it is set.
    $action = isset($_GET['action']) ? $_GET['action'] : "";

    // Decide which action to perform.
    switch ( $action ) {

        case 'archive':
            archive();
            break;

        case 'viewArticle':
            viewArticle();
            break;

        default:
            homepage();

    }

    // Displays the list of all articles.
    function archive() {

        // Stores data which the archive template will use.
        $results = array();             

        $data = Article::getList();

        $results['articles'] = $data['results'];
        $results['totalRows'] = $data['totalRows'];
        $results['pageTitle'] = "Article Archive | Widget News";

        // Include the template file to display the page.
        require(TEMPLATE_PATH . "/archive.php");

    }

    // Displays a single article.
    function viewArticle() {

        // This function requires an 'articleId' URL parameter.
        // Return from the funciton if this parameter isn't set.
        if (!isset($_GET["articleId"]) || !$_GET["articleId"]) {
            homepage();
            return;
        }

        // Stores data which the viewArticle template will use.
        $results = array();     

        $results['article'] = Article::getById( (int) $_GET["articleId"] );
        $results['pageTitle'] = $results['article']->title . " | Widget News";

        require(TEMPLATE_PATH . "/viewArticle.php");

    }

    // Displays the homepage containing up to HOMEPAGE_NUM_ARTICLES (5 by default).
    function homepage() {

        // stores data which the homepage template will use.
        $results = array();     

        $data = Article::getList(HOMEPAGE_NUM_ARTICLES);

        $results['articles'] = $data['results'];
        $results['totalRows'] = $data['totalRows'];
        $results['pageTitle'] = "Widget News";

        require(TEMPLATE_PATH . "/homepage.php");

    }

?>