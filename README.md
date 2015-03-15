MirenDB - Control version system for your database.
============================

MirenDB is born as a tool to help developers to integrate their project's databases - not just schema, but data, too - into their control version system.

This may be specially useful for CMS like Wordpress, Drupal, Magento, etc. where the projects status is always defined by its data.

Any project can take advantage of MirenDB, anyway. Even if you don't need your data to be under CVS, just your schema, MirenDB helps you forget about generating
the migration scripts but it does it automatically. You can even integrate it with [FlyWayDB](http://flywaydb.org), [DBV](https://github.com/victorstanciu/dbv) or [Laravel migrations](http://laravel.com/).

Current Status
----------------
The current status of the project is not stable at all, we're currently developing it.

To check a rough roadmap please go to http://github.com/ojoven/mirendb/blob/master/TODO.md

Important features like ~~Importing revisions, adding a pull hook~~, handling multiple developers and merges, are still missing. Please feel free to contribute.

How to use it
----------------
Please check [MirenDB Client](http://github.com/ojoven/mirendb_client) as an example of how to install the tool on any project.

The main way to install it is by **using Composer**.

Please copy into your project this composer.json - or integrate it in your existing one -

    {
      "name": "ojoven/mirendb_client",
      "repositories": [
        {
          "type": "vcs",
          "url": "https://github.com/ojoven/mirendb"
        }
      ],
      "require": {
        "ojoven/mirendb": "dev-master"
      },
      "scripts": {
        "post-install-cmd": "php vendor/ojoven/mirendb/scripts/post-install-cmd.php"
      }
    }

And run:

    composer install

You should get something like this:
![Composer](https://github.com/ojoven/mirendb/blob/master/App/Project/composer.png "Composer")

Now you must configure your project's features - DB credentials, etc. - on .sql/App/config.ini or better, run the configuration assistant:

    php .sql/scripts/configurator.php

A configurator will ask you in a human way your credentials and preferences:
![MirenDB Configurator](https://github.com/ojoven/mirendb/blob/master/App/Project/configurator.png "MirenDB Configurator")


Worflow
----------------
The intent of MirenDB is to integrate in your workflow in the most stealthy way as possible. We've already succeeded on integrating
it with GIT, by installing a pre-commit and a post-merge hook.

So, once the hooks are installed - automatically if you run the configurator - everytime you run:

    git commit -m "Whatever"

MirenDB checks the differences between the already stored revisions and the current DB and it generates a new revision in case they're different.

![MirenDB revision](https://github.com/ojoven/mirendb/blob/master/App/Project/revision.png "MirenDB revision")

At the same time, if you run:

    git pull --all

MirenDB imports the revisions into your database, so it updates it in case another developer had updated on his local environment.

Credits
----------------
We're using MySqliDB class as a wrapper for MySQL connection handling: https://github.com/joshcam/PHP-MySQLi-Database-Class

Contribution
----------------
Please let me know your thoughts and feedback at http://twitter.com/ojoven or write me an email to mikeltorresmail@gmail.com