# User management

User management is a user crud operations created with Symfony.
## Installation

Download the code. Open new terminal and install [Symfony](https://symfony.com/).
```bash
composer install

```
Configure your database in .env file.

```bash
DATABASE_URL=mysql://user:password@127.0.0.1:3306/db_name
```

Configure MAILER_DSN:
```bash
MAILER_DSN=smtp://user:pass@smtp.example.com:port
```

## Crud operations

* Register user
  * username
  * email
  * password
  * password_confirmation
* Verify user email with link.
* Login
  * email
  * password
* Forgotten password
  * Reset password via email.
* Changing password with checking old password.
* Profile page
* Edit profile page
  *  If the email is changed verification is required


## License
[MIT](https://choosealicense.com/licenses/mit/)
