<?php

function getSchoolDB($path) {
    $pathDB =  $path . "school.db";
    
    if (file_exists($pathDB)) {
        $connDB = new SQLite3($pathDB, SQLITE3_OPEN_READWRITE);
    } else {
        $connDB = new SQLite3($pathDB);
    }
    
    if (!$connDB) {
        die("An error has occured in opening a SQLite3 database.");
    }

    return $connDB;
}

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}

?>