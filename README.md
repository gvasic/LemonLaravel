**Lemonade Stand Online API**

Routes:

post: /api/auth/register
params:
email
email_confirmation
password
password_confirmation


post: /api/auth/login
email
password

//Returns user details of loged in user
get: /api/get_user_details


//Activates user after registration
get /api/user/activation/token



---
