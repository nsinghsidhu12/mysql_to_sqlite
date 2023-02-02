<?php include("../../inc_header.php"); ?>

<h1>Display Student</h1>

<?php
if (isset($_GET['id'])) {
    include("../../inc_utils.php");

    $connDB = getSchoolDB("../../");

    $showTable = TRUE;
    $studentID = "";
    $firstName = "";
    $lastName = "";
    $school = "";

    if ($connDB) {
        $id = sanitize_input($_GET['id']);
        $stringSQL = "SELECT * FROM Students WHERE StudentId=:id";

        // Create a prepared statement
        if ($stmtSQL = $connDB->prepare($stringSQL)) {

            // Bind values to the markers
            $stmtSQL->bindValue(":id", $id, SQLITE3_TEXT);

            // Execute the query
            $queryResult = $stmtSQL->execute();

            while ($row = $queryResult->fetchArray(SQLITE3_NUM)) {
                $studentID = $row[0];
                $firstName = $row[1];
                $lastName = $row[2];
                $school = $row[3];
            }

            $queryResult->finalize();
            $stmtSQL->close();
        }

        $connDB->close();
    }
} else {
    $showTable = FALSE;
    $studentID = "";
    $firstName = "";
    $lastName = "";
    $school = "";

    echo "<h4><br>The URL is invalid. An id parameter must be given.</h4>\n";
}
if ($showTable) {
?>

    <table>
        <tr>
            <td>Student ID:</td>
            <td><?php echo $studentID ?></td>
        </tr>
        <tr>
            <td>First name: </td>
            <td><?php echo $firstName ?></td>
        </tr>
        <tr>
            <td>Last name: </td>
            <td><?php echo $lastName ?></td>
        </tr>
        <tr>
            <td>School: </td>
            <td><?php echo $school ?></td>
        </tr>
    </table>

<?php
}
?>
<br />
<a href="/" class="btn btn-small btn-primary">&lt;&lt; BACK</a>

<?php include("../../inc_footer.php"); ?>