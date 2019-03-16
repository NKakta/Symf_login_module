#User module by Nedas Kakta n.nevada@gmail.com

To setup the database run these commands:
  php bin/console doctrine:database:create, 
  php bin/console doctrine:schema:update --force
  

This is a user module.
In memory admin credentials:
  Username: admin@admin.lt
  Password: admin

If you want some mock users please use the fixtures

Superadmin is able to manage users.

#Search for user by email:

UserController->searchAction
Path: /admin/search/{email}
The email value will be validated so make sure to type a correct value.

#Email notifications

Email is configured to gmail. (.env)
Sent to user when he registers or is created by admin.


