<?php include("./inc_header.php"); ?>

<h1>Noorarjun Sidhu</h1>

<h2>List of Students</h2>

<p>
     <a href="./crud/create/create.php" class="btn btn-small btn-success">Create New</a>
</p>

<?php
include("./inc_populate_db.php");

echo $dataIsReady;
echo $dataRowsInserted;

if ($connDB) {
     $SQLstring = "SELECT * FROM Students";
     if ($queryResult = $connDB->query($SQLstring)) {
          $fieldCount = $queryResult->numColumns();
          echo "<table width='100%' class='table table-striped'>\n";
          echo "<tr><th>Student ID</th>" .
               "<th>First Name</th>" .
               "<th>Last Name</th>" .
               "<th>School</th>" .
               "<th>CRUD</th></tr>\n";
          while ($row = $queryResult->fetchArray()) {
               echo "<tr>";

               for ($i = 0; $i < $fieldCount; $i++) {
                    if ($i === 0) {
                         $studentID = $row[$i];
                    }

                    echo "<td>{$row[$i]}</td>";
               }

               echo "<td>";
               echo "<a class='btn btn-small btn-primary' href='../../crud/display/display.php?id=$studentID'>disp</a>";
               echo "&nbsp;";
               echo "<a class='btn btn-small btn-danger' href='../../crud/delete/delete.php?id=$studentID'>del</a>";
               echo "&nbsp;";
               echo "<a class='btn btn-small btn-warning' href='../../crud/update/update.php?id=$studentID'>upd</a>";
               echo "</td></tr>\n";
          };
          echo "</table>\n";

          $queryResult->finalize();
     }

     $connDB->close();
}
?>

<?php include("./inc_footer.php"); ?>