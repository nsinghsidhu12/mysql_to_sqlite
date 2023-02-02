<?php
ini_set('display_errors', '1');

/* Combination of creating the Students table and populating the Students 
   table with sample data from seeds-data.csv */
include("./inc_utils.php");

$connDB = getSchoolDB("./");

// Creates the Student table
if ($connDB) {
    $stringSQL = "CREATE TABLE IF NOT EXISTS Students (
        StudentId VARCHAR(10) NOT NULL,
        FirstName VARCHAR(80),
        LastName VARCHAR(80),
        School VARCHAR(50),
        PRIMARY KEY (StudentId)
        );";
    
    $executeResult = $connDB->exec($stringSQL);

    if ($executeResult === FALSE) {
        error_log('SQLite3 execute() failed: ');
        error_log(print_r(htmlspecialchars($connDB->lastErrorMsg()), true));
    }
}

if ($connDB) {
    // Get the row count from the Students table
    $stringSQL = "SELECT COUNT(*) FROM Students";
    $queryResult = $connDB->query($stringSQL);
    $rowsCount = $queryResult->fetchArray()[0];
    $dataRowsInserted = "";

    // If there are no rows, populate the Students table with sample data
    if ($rowsCount == 0) {
        $dataIsReady = "";
        
        if (($handle = fopen("seed-data.csv", "r")) !== FALSE) {
            $dataRow = fgetcsv($handle, 1000, ",");
            $dataCount = 0;

            while (($dataRow = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $id = sanitize_input($dataRow[0]);
                $firstName = sanitize_input($dataRow[1]);
                $lastName = sanitize_input($dataRow[2]);
                $school = sanitize_input($dataRow[3]);

                $stringSQL = "INSERT INTO Students (StudentId, FirstName, LastName, School)";
                $stringSQL .= " VALUES "; 
                $stringSQL .= " (:id, :fname, :lname, :schl)";

                // Create a prepared statement
                if ($stmtSQL = $connDB->prepare($stringSQL)) {

                    // Bind values to the markers
                    $stmtSQL->bindValue(":id", $id, SQLITE3_TEXT);
                    $stmtSQL->bindValue(":fname", $firstName, SQLITE3_TEXT);
                    $stmtSQL->bindValue(":lname", $lastName, SQLITE3_TEXT);
                    $stmtSQL->bindValue(":schl", $school, SQLITE3_TEXT);
        
                    // Execute the query
                    $executeResult = $stmtSQL->execute();
        
                    if ($executeResult === false) {
                        error_log('SQLite3 execute() failed: ');
                        error_log(print_r(htmlspecialchars($connDB->lastErrorMsg()), true));
                    } else {
                        $dataCount++;
                    }

                    // Close the statement
                    $stmtSQL->close();
                }
            }

            $dataRowsInserted = "<p>$dataCount records were inserted";
        }
    } else {
        $dataIsReady = "<p>The data is all set and ready to be used</p>";
    }
}
?>