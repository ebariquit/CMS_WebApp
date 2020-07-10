# CMS_WebApp
A simple cms app: a blog.

This app has only been tested in a local environment, yet to deploy.

A single admin account has the ability to write, edit, or delete articles within the blog. Other users are free to view the available articles. 

# Requirements
- Apache Server
- MySQL Server
- PHP

XAMPP provides everything you need.

# Plans for Future
- Upgrade from blog to forum
    - Allow registered users to post articles.
    - Admin can post and remove articles.
    - 'Article' class becomes 'Discussion' class
        - Discussion: Title, Summary, Posts (rather than Content).
        - Define Posts class
            - User, Date, Content.

