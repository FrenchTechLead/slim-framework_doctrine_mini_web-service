# slim-framework_doctrine_mini_web-service

This is a mini web-service that consists of two sections:
  - SuperUser Section
  - User section

### Authentification
    
###### SuperUser IDs

* [email] - admin@admin.com
* [pass] - admin

###### User IDs

* [email] - user@user.com
* [pass] - user

The authentification is done by JsonWebToken so when you first login you have to return the token for all the next requests. (you have to include the token to the Authorization header)

### DOC

##### Authentificate
``
POST /v1/authentificate 
 ["email", "pass"]
``

##### Create User
``
POST /v1/users 
 ["email", "pass", "is_superuser"] 1 for superuser rights 
``
##### Get All UsersList
``
GET /v1/users
``
##### get User By Id
``
GET /v1/users/{id:\d+}
``

##### Edit User

``
PATCH /v1/users/{id:\d+}  ["email", "pass", "is_superuser"]
``

##### Delete User
``
DELETE /v1/users/{id:\d+} 
``

##### Create new Empty Form
``
POST /v1/form_version ["titre"]
``

##### Create A Question That Is Linked To A Form
``
POST /v1/question/{form_id:\d+} ["question"]
``
##### Delete A Question From A Form
``
DELETE /v1/question/{form_id:\d+} 
``
##### Aelete A Form With All Its Related Questions
``
DELETE /v1/form_version/{form_id:\d+}
``
##### Get a Form with Its Related Questions
``
GET /v1/form_version/{form_id:\d+}
``
##### Create A Form To Fill
``
POST /v1/answered_form/{linked_form_id:\d+}
``
##### Fill A Form
``
POST /v1/answer/{linked_form_id:\d+} ['answer', 'question_id']
``

##### Consult A Filled Form
``
GET /v1/answered_form/{form_id:\d+}
``

##### The front end is not complete yet but you can check it out at http:localhost:8080
