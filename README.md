SQL Diff Generator (MySQL)
============================

Tool to generate SQL files with the differences between 2 databases.

This means that having an origin DB, if we apply the SQL queries in the generated file result.sql we'll get the final target DB.
Not just the schema but the data, too.

Please check an example included in this repo (sql folder):

[target.sql](https://github.com/ojoven/sqldiffgenerator/tree/master/sql/target.sql) -
[origin.sql](https://github.com/ojoven/sqldiffgenerator/tree/master/sql/origin.sql) =
[result.sql](https://github.com/ojoven/sqldiffgenerator/tree/master/sql/result.sql)

The final aim of this is to use it as a tool for version control, compatible with [FlyWayDB](http://flywaydb.org) or [DBV](https://github.com/victorstanciu/dbv).

Not too ready for contribution yet - composer not defined, the config.ini is the one I'm using locally, no extended docs - but I promise I'll do it better once I have it a bit more advanced.

Behaviours
----------------

At this moment there are 3 different behaviours handled in the tool:

1. BothDatabaseBehaviour -> It generates a SQL file result of **the difference between 2 databases**.

2. BothFileBehaviour -> It generates a SQL file result of **the difference between 2 SQL files**.

3. DbvControlVersionBehaviour -> It takes a database and **generates revision SQL files** when changes are made to it.
This behaviour is the real final aim of this project, as a tool to ease the databases control version automatically.

How to use it
----------------
1. Clone this repo: git clone https://github.com/ojoven/sqldiffgenerator.git
2. Select your desired behaviour by changing the define BEHAVIOUR on index.php
3. Change the credentials and paths in App/Configs/[YourDesiredBehaviour]/Config.ini
4. Run the index.php file, it will generate the result.sql file with the differences between target - origin DBs, or generate new revision files if the behaviour selected is a control version one.


Credits
----------------
We're using MySqliDB class as a wrapper for MySQL connection handling: https://github.com/joshcam/PHP-MySQLi-Database-Class

How to use as a Version Control Tool
----------------
Just select one of the Control Version behaviours included in the project (Dbv, at this moment). Still have to study how to integrate better this tool into a continuous deployment flow.


Contribution
----------------
Please let me know your thoughts and feedback at http://twitter.com/ojoven