<?php
if (isset($_POST['create'])) {
    include("../../inc_utils.php");

    $connDB = getSchoolDB("../../");

    if ($connDB) {
        // Stores the varchar length for each column in order
        $max_chars_for_columns = array();

        // Gets the varchar length for each column
        if ($result = $connDB->query("pragma table_info('Students')")) {
            while ($row = $result->fetchArray()) {
                preg_match('/(?<=\()\d+?(?=\))/', $row[2], $matches);
                array_push($max_chars_for_columns, $matches[0]);
            }
            $result->finalize();
        } else {
            error_log(print_r(htmlspecialchars($connDB->lastErrorMsg()), true));
        }

        extract($_POST);

        $studentID = sanitize_input($studentID);
        $firstName = sanitize_input($firstName);
        $lastName = sanitize_input($lastName);
        $school = sanitize_input($school);

        // Dies if input length exceeds column's allowed length
        if (strlen($studentID) > $max_chars_for_columns[0]) {
            die("StudentId can only have {$max_chars_for_columns[0]} characters");
        }
        if (strlen($firstName) > $max_chars_for_columns[1]) {
            die("FirstName can only have {$max_chars_for_columns[1]} characters");
        }
        if (strlen($lastName) > $max_chars_for_columns[2]) {
            die("LastName can only have {$max_chars_for_columns[2]} characters");
        }
        if (strlen($school) > $max_chars_for_columns[3]) {
            die("School can only have {$max_chars_for_columns[3]} characters");
        }

        $stringSQL = "";
        $stringSQL .= "INSERT OR IGNORE INTO Students (StudentId, FirstName, LastName, School) VALUES ";
        $stringSQL .= "(:id, :fname, :lname, :schl)";

        // Create a prepared statement
        if ($stmtSQL = $connDB->prepare($stringSQL)) {

            // Bind values to the markers
            $stmtSQL->bindValue(":id", $studentID, SQLITE3_TEXT);
            $stmtSQL->bindValue(":fname", $firstName, SQLITE3_TEXT);
            $stmtSQL->bindValue(":lname", $lastName, SQLITE3_TEXT);
            $stmtSQL->bindValue(":schl", $school, SQLITE3_TEXT);

            // Execute the query
            $executeResult = $stmtSQL->execute();

            if ($executeResult === false) {
                error_log('SQLite3 execute() failed: ');
                error_log(print_r(htmlspecialchars($connDB->lastErrorMsg()), true));
            }

            $stmtSQL->close();
        }

        $connDB->close();
        
    }

    include("../../inc_to_list.php");
} else {
    include("../../inc_header.php");

    echo "<h4><br>The URL is invalid.</h4>\n";

    echo '<a href="/" class="btn btn-small btn-primary">&lt;&lt; BACK</a>';

    include("../../inc_footer.php");
}
?>