# SWGRO Account System

The system allows for a server to setup a separate client sign up system from the standard SWGEmu system, an authocode is required for users to create a
new game account on the server. The authcode is generated by the server admin and sent to the user via email/ticket system at which time the user will go
to the provided user and enter in their username, password, email, and authcode and then the account will be created as long as all the requirements are met.

The system will do validation on the username (if a user with the same name exists it will not create the account), email address (it will look to see if the user has used this email address previously and if they have more the the allowed times it will not create the account), ip (it will look to see if the users
ip has been used previously and if they have more the the allowed times it will not create the account).

All these settings are editable in the api/swgAPI/config/settings.php file you can turn on validation for IP and Email as well as increase the number of accounts that a user can have.

The only requirements for the front end if you choose not to use the default front end is that you can get and pass a bearer token back to the API with out being able to do that it will always fail. (see default front end for examples).

### Example
[SWGRO Account System](http://clientaccess.swgrogueone.com)

### Install
Clone the repository
``` bash
$ git clone https://github.com/SidiousRNDS/SWGRO-AccountSystem.git
```
Run composer install
``` bash
$ composer install
```

### Built with
* [Slim](https://www.slimframework.com/) - Slim framework
* [Slim JWT Auth](https://github.com/tuupola/slim-jwt-auth) - JWT Auth for Slim framework

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/SidiousRNDS/SWGRO-AccountSystem/tags).

## Authors

* **Ian Ford** aka(Sidious)- *Initial work* - [SidiousRNDS](https://github.com/SidiousRNDS)

See also the list of [contributors](https://github.com/SidiousRNDS/SWGRO-AccountSystem/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
