AJAX Calendar :

Link to Web: http://ec2-3-143-211-150.us-east-2.compute.amazonaws.com/~clee20/module5_g/homepage.php

The calendar is displayed as a table grid with days as the columns and weeks as the rows, one month at a time 
The user can view different months as far in the past or future as desired 

Events can be added, modified, and deleted 
Events have a title, date, and time 
Users can log into the site, and they cannot view or manipulate events associated with other users 
Don't fall into the Abuse of Functionality trap! Check user credentials on the server side as well as on the client side.
All actions are performed over AJAX, without ever needing to reload the page
Refreshing the page does not log a user out 

Code is well formatted and easy to read, with proper commenting 
If storing passwords, they are stored salted and hashed 
All AJAX requests that either contain sensitive information or modify something on the server are performed via POST, not GET
Safe from XSS attacks; that is, all content is escaped on output 
Safe from SQL Injection attacks
CSRF tokens are passed when adding/editing/deleting events 
Session cookie is HTTP-Only 
Page passes the W3C validator

Site is intuitive to use and navigate

