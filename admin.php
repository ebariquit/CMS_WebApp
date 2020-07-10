<?php

    // This script controls the backend admin functions of the CMS.

    require("config.php");

    // Start a new session for the user, which we will use to track their status (logged in/out).
    // Note: sessions require cookies. Cookies are sent to browser before content. Good practice
    // to call session_start() at the top of the script BEFORE any content is sent to browser.
    session_start();

    // Store these values if they are set, otherwise store empty strings. 
    $action = isset($_GET['action']) ? $_GET['action'] : "";
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

    // If username isn't set (because user hasn't logged in), and they aren't trying to login or logout,
    // display the login page and exit.
    if ($action != "login" && $action != "logout" && !$username) {
        login();
        exit;
    }

    // Decide which action to perform.
    switch ($action) {

        case 'login':
            login();
            break;

        case 'logout':
            logout();
            break;

        case 'newArticle':
            newArticle();
            break;

        case 'editArticle':
            editArticle();
            break;

        case 'deleteArticle':
            deleteArticle();
            break;

        default:
            listArticles();

    }

    // Checks the form values for username and password. If they match the admin's credentials,
    // set the 'username' session key and redirect admin to admin.php.
    function login() {

        $results = array();
        $results['pageTitle'] = "Admin Login";

        
        // User has posted the login form; attempt to log the user in.
        if (isset($_POST['login'])) {

            // Successful login.
            if ($_POST['username'] == ADMIN_USERNAME && $_POST['password'] == ADMIN_PASSWORD) {
                
                $_SESSION['username'] = ADMIN_USERNAME;
                header("Location: admin.php");

            } 

            // Login failed.
            else { 
                
                $results['errorMessage'] = "Incorrect username or password. Please try again.";
                require(TEMPLATE_PATH . "/admin/loginForm.php");

            }

        }
        // User has not posted the login form; display the form.
        else {
            require(TEMPLATE_PATH . "/admin/loginForm.php");
        }


    }

    // Remove 'username' session key and redirect to admin.php.
    function logout() {
        unset($_SESSION['username']);
        header("Location: admin.php");

    }

    function newArticle() {

        $results = array();
        $results['pageTitle'] = "New Article";
        $results['formAction'] = "newArticle";

        // User has posted the article edit form; save the new article.
        if (isset($_POST['saveChanges'])) {
            $article = new Article;
            $article->storeFormValues($_POST);
            $article->insert();
            header("Location: admin.php?status=changesSaved");
        }

        // User has cancelled their edits; return to the article list.
        elseif (isset($_POST['cancel'])) {
            header("Location: admin.php");
        }

        // User has not posted the article edit form yet; display the form.
        else {
            $results['article'] = new Article;
            require(TEMPLATE_PATH . "/admin/editArticle.php");
        }

    }

    function editArticle() {

        $results = array();
        $results['pageTitle'] = "Edit Article";
        $results['formAction'] = "editArticle";

        // User has posted the article edit form; save the article changes.
        if (isset($_POST['saveChanges'])) {
            
            // Check that article exists (check for an id - if there is one,
            // store the article object).
            if (!$article = Article::getById( (int) $_POST['articleId'])) {
                header("Location: admin.php?error=articleNotFound");
                return;
            }

            $article->storeFormValues($_POST);
            $article->update();
            
            header("Location: admin.php?status=changesSaved");

        }

        // User has cancelled their edits; return to the article list.
        elseif (isset($_POST['cancel'])) {
            header("Location: admin.php");
        }

        // User has not posted the article edit form yet; display the form.
        else {
            $results['article'] = Article::getById( (int) $_GET['articleId']);
            require(TEMPLATE_PATH . "/admin/editArticle.php");
        }

    }

    function deleteArticle() {

        if (!$article = Article::getById( (int) $_GET['articleId'])) {
            header("Location: admin.php?error=articleNotFound");
            return;
        }

        $article->delete();
        header("Location: admin.php?status=articleDeleted");

    }

    // Before loading the template with data ($results), this function checks
    // 'error' and 'status' URL parameters to see if any message needs to be
    // displayed to the admin. 
    function listArticles() {

        $results = array();
        $data = Article::getList();

        $results['articles'] = $data['results'];
        $results['totalRows'] = $data['totalRows'];
        $results['pageTitle'] = "All Articles";

        // Check for an error and store message for the template to use.
        if (isset($_GET['error'])) {

            if ($_GET['error'] == "articleNotFound") 
                $results['errorMessage'] = "Error: Article not found.";
            
        }

        // Check for status changes and store message(s) for the template to use.
        if (isset($_GET['status'])) {

            if ($_GET['status'] == "changesSaved")
                $results['statusMessage'] = "Your changes have been saved.";

            if ($_GET['status'] == "articleDeleted")
                $results['statusMessage'] = "Article deleted.";

        }

        require(TEMPLATE_PATH . "/admin/listArticles.php");

    }


?>