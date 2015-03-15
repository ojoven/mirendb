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

Behaviours
----------------

MirenDB includes, too, additional behaviours to use it as a tool for single SQL diff generations between 2 SQL files / databases.

At this moment there are 3 different behaviours handled in the tool:

1. BothDatabaseBehaviour -> It generates a SQL file result of **the difference between 2 databases**.

2. BothFileBehaviour -> It generates a SQL file result of **the difference between 2 SQL files**.

3. StandardControlVersionBehaviour -> It takes a database and **generates revision SQL files** when changes are made to it.
This behaviour is the real final aim of this project, as a tool to ease the databases control version automatically.

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

Credits
----------------
We're using MySqliDB class as a wrapper for MySQL connection handling: https://github.com/joshcam/PHP-MySQLi-Database-Class

Contribution
----------------
Please let me know your thoughts and feedback at http://twitter.com/ojoven or write me an email to mikeltorresmail@gmail.com