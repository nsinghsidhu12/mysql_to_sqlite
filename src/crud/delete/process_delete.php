<?php
if (isset($_POST['StudentId'])) {
    include("../../inc_utils.php");

    $connDB = getSchoolDB("../../");

    $executeResult = FALSE;

    if ($connDB) {
        $id = sanitize_input($_POST['StudentId']);

        // Create a prepared statement
        if ($stmtSQL = $connDB->prepare("DELETE FROM Students WHERE StudentId=:id")) {

            // Bind values to the markers
            $stmtSQL->bindValue(":id", $id, SQLITE3_TEXT);

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

    echo "<h4><br>The URL is invalid. A Studentid parameter must be given.</h4>\n";

    echo '<a href="/" class="btn btn-small btn-primary">&lt;&lt; BACK</a>';

    include("../../inc_footer.php");
}

include("../../inc_footer.php");
?>