# CMS_WebApp
A simple cms app: a blog.

This app has only been tested in a local environment, running on 

A single admin account has the ability to write, edit, or delete articles within the blog. Other users are free to view the available articles. 

# Requirements
- Apache Server
- MySQL Server
- PHP

I used the XAMPP stack for PHP and an Apache Server. I already had another MySQL Server running, so I used it instead of the one provided by XAMPP.

# Setup
If you are using the XAMPP stack, download this project into your htdocs folder. 

Start your MySQL Server and create a new database named 'cms'.

Run the createTable_articles.sql file to create the 'articles' table in your database.

Open the config.php file in this project, and alter the define() statements with your database access details.

In config.php, you can also define the login credentials for the webapp admin.

# Usage
Start your Apache Server and navigate to localhost/cms_webapp (or whatever you choose to name this project folder in htdocs).

Click 'site-admin' link at bottom of page and login with your admin credentials. 

Create, edit, or delete articles for users to read.

# Plans for Future
- Upgrade from blog to forum
    - Allow registered users to post articles.git
    - Admin can post and remove articles
    - 'Article' class becomes 'Discussion' class, which will be composed of 'Post' class instances
        - Discussion class: User, Date, Title, Summary, Posts (rather than Content).
        - Post class: User, Date, Content.

