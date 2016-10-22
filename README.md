# Linux command line 101

When running through this tutorial, you'll be exposed for some special signs
which have a special meaning.

`#` means that the command should be runned as an administrator.
`$` means that the command should be runned as a regular user.

# Installation instructions

This tutorial assumes that you use Ubuntu Linux 16.04, other distros may require
modifications to the commands. Installation of PHP and required extensions
commands can be done using the following command:

```
# apt-get install php7.0-cli php7.0-sqlite php-xml
```

After PHP is installed, Composer must be installed for managing packages related
to the project. Installation of Composer and the project dependencies can be
done using the following commands:

```
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar install
```

Initial setup of the database can be done with the following command:

```
$ php composer.phar up
```

After all installation is done, the application can be started using:

```
$ php -S localhost:8080 -t web web/index.php
```

You can now access the application through the address, `localhost:8080`.
Happy hacking!

## Reset the application

Have you screwed up the application? Start reseting it by tearing down the
database with the following commands:

```
$ php composer.phar down
$ php composer.phar up
```
