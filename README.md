1- Create new Laravel Project (Laravel +8).
2- Use SQLite Database.
3- Make authentication system using Sanctum.
a. /register endpoint that receives and validates the following:
i. Name.
ii. Phone number.
iii. Password.
b. /login endpoint.
c. Both of the previous endpoint should return the user data with access token.
d. Generate random 6-digits verification code for every user.
e. Send the code for every user (Just log it).
f. Make an endpoint that verifies the code sent to the user.
g. Only verified accounts can login to the system.
4- Create tags API resource.
a. Authenticated users can view all tags.
b. Authenticated users can store new tags.
c. Authenticated users can update single tag.
d. Authenticated users can delete single tag.
e. Tags only has names and the name should be a unique one.
5- Create posts API resource.
a. Authenticated users can view only their posts.
b. Authenticated users can store new posts.
c. Authenticated users can view a single post of their posts.
d. Authenticated users can update single post of their posts.
e. Authenticated users can delete (Softly) single post of their posts.
f. Authenticated users can view their deleted posts.
g. Authenticated users can restore one of their deleted posts.
h. Posts have the following data:
i. Title. [Required, Maximum Characters: 255]
ii. Body. [Required, String]
iii. Cover image. [Required only when storing, Optional when updating, Image]
iv. Pinned. [Required, Boolean]
v. One or more tags. (Hint: Many-to-many Relationship).
i. Pinned Posts should appear first for every user.
j. All the received data for storing and updating posts should be validated.
6- Create a Job that runs daily and force-deletes all softly-deleted posts for more than 30 days.
7- Create a job that runs every six hours and makes HTTP Request to this end endpoint and log only the results
object in the response. https://randomuser.me/api/
8- Make /stats API endpoint.
a. That endpoint should return the following:
i. Number of all users.
ii. Number of all posts.
iii. Number of users with 0 posts.
b. The results should be cached and update with every update to the related models (User and Post)
