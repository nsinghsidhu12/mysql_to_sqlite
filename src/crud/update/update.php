<?php include("../../inc_header.php"); ?>

<?php
if (isset($_GET['id'])) {
    include("../../inc_utils.php");

    $connDB = getSchoolDB("../../");

    $showDiv = TRUE;
    $studentID = "";
    $firstName = "";
    $lastName = "";
    $school = "";

    if ($connDB) {
        $id = sanitize_input($_GET['id']);

        // Create a prepared statement
        if ($stmtSQL = $connDB->prepare("SELECT * FROM Students WHERE StudentId=:id")) {

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
    $showDiv = FALSE;
    $studentID = "";
    $firstName = "";
    $lastName = "";
    $school = "";

    echo "<h4><br>The URL is invalid. An id parameter must be given.</h4>\n";
}
if ($showDiv) {
?>

<h1>Update</h1>

<div class="row">
    <div class="col-md-4">
        <form action="process_update.php" method="post">

            <div class="form-group">
                <input type="hidden" value="<?php echo $studentID ?>" name="studentID" />
                <label class="control-label">Student ID</label>
                <?php echo $studentID ?>
            </div>

            <div class="form-group">
                <label for="FirstName" class="control-label">First Name</label>
                <input for="FirstName" class="form-control" name="firstName" id="FirstName" 
                    value="<?php echo $firstName; ?>" />
            </div>

            <div class="form-group">
                <label for="LastName" class="control-label">Last Name</label>
                <input for="LastName" class="form-control" name="lastName" id="LastName" 
                    value="<?php echo $lastName; ?>" />
            </div>

            <div class="form-group">
                <label for="School" class="control-label">School</label>
                <input for="School" class="form-control" name="school" id="School" 
                    value="<?php echo $school; ?>" />
            </div>

            <div class="form-group">
                <a href="../../" class="btn btn-small btn-primary">&lt;&lt; BACK</a>
                &nbsp;&nbsp;&nbsp;
                <input type="submit" value="Update" name="update" class="btn btn-warning" />
            </div>
        </form>
    </div>
</div>
<?php
}
?>
<?php
if ($showDiv == FALSE) {
?>
<br>
<a href="../../" class="btn btn-small btn-primary">&lt;&lt; BACK</a>
<?php
}
?>

<br />


<?php include("../../inc_footer.php"); ?>