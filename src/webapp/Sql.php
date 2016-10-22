<?php

namespace tdt4237\webapp;

use tdt4237\webapp\models\User;

class Sql
{
    static $pdo;

    function __construct()
    {
    }

    /**
     * Create tables.
     */
    static function up()
    {
        $q1 = "CREATE TABLE users (id INTEGER PRIMARY KEY, user VARCHAR(50), pass VARCHAR(50), email varchar(50) default null, first_name varchar(50), last_name varchar(50), phone varchar (8), company varchar(50), isadmin INTEGER);";
        $q6 = "CREATE TABLE patent (patentId INTEGER PRIMARY KEY AUTOINCREMENT, company TEXT NOT NULL, title TEXT NOT NULL, file TEXT NOT NULL, description TEXT NOT NULL, date TEXT NOT NULL, FOREIGN KEY(patentId) REFERENCES users(company));";

        self::$pdo->exec($q1);
        self::$pdo->exec($q6);

        print "[tdt4237] Done creating all SQL tables.".PHP_EOL;

        self::insertDummyUsers();
        self::insertPatents();
    }

    static function insertDummyUsers()
    {
        $hash1 = Hash::make(bin2hex(openssl_random_pseudo_bytes(2)));
        $hash2 = Hash::make('techit');
        $hash3 = Hash::make('mundbjar');

        $q1 = "INSERT INTO users(user, pass, isadmin, first_name, last_name, phone, company, email) VALUES ('systemmanager', '$hash1', 1, 'Approv', 'Patents', '53290672', 'Patentsy AS', 'systemmanager@patentsy.com')";
        $q2 = "INSERT INTO users(user, pass, isadmin, first_name, last_name, phone, company, email) VALUES ('ittechnican', '$hash2', 1, 'Robert', 'Green', '92300847', 'Patentsy AS', 'ittechnican@patentsy.com')";
        $q3 = "INSERT INTO users(user, pass, isadmin, first_name, last_name, phone, company, email) VALUES ('ceobjarnitorgmund', '$hash3', 1, 'Bjarni', 'Torgmund', '32187625', 'Patentsy AS', 'ceobjarnitorgmund@patentsy.com')";

        self::$pdo->exec($q1);
        self::$pdo->exec($q2);
        self::$pdo->exec($q3);


        print "[tdt4237] Done inserting dummy users.".PHP_EOL;
    }

    static function insertPatents() {
        $q4 = "INSERT INTO patent(company, title, file, description, date) VALUES ('Patentsy AS', 'Search System', 'web/uploads/test.txt', 'New algorithm making search as fast as speed of light.', '20062016')";
        $q5 = "INSERT INTO patent(company, title, file, description, date) VALUES ('Patentsy AS', 'New litteum battery technology', 'web/uploads/test.txt', 'A new technology that will take batteries through a new revolution.', '26072016')";

        self::$pdo->exec($q4);
        self::$pdo->exec($q5);
        print "[tdt4237] Done inserting patents.".PHP_EOL;

    }

    static function down()
    {
        $q1 = "DROP TABLE users";
        $q4 = "DROP TABLE patent";

        self::$pdo->exec($q1);
        self::$pdo->exec($q4);

        print "[tdt4237] Done deleting all SQL tables.".PHP_EOL;
    }
}
try {
    // Create (connect to) SQLite database in file
    Sql::$pdo = new \PDO('sqlite:app.db');
    // Set errormode to exceptions
    Sql::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
} catch (\PDOException $e) {
    echo $e->getMessage();
    exit();
}
