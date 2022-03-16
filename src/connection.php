<?php

    define('DB_DSN','mysql:host=localhost;dbname=netrunner;charset=utf8');
    define('DB_USER','netrunnerdb');
    define('DB_PASS','jZt5M46yBJCjEygH');

    // Create a PDO object called $db.
    $db = new PDO(DB_DSN, DB_USER, DB_PASS);

?>