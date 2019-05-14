<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script>
function goBack() {
          window.history.back()
}
</script>
<link rel="stylesheet" type="text/css" href="./../content/css/style.css">
<link rel="stylesheet" href="./../content/css/bootstrapcss.css">
<link rel="stylesheet" href="./../content/css/form.css">
<title>Assignment 04C</title>
</head>
<body>
<ul class="sidenav">
<a href="./../index.html"><img src="./../content/images/logo.png"/></a>
<li><a href="./../index.html">Home</a></li>
<li><a href="./../module01assign01/index.html">About Me</a></li>
</ul>
<div id="homepage" class="contentbox">
        <img src="./../content/images/goback.png" onclick="goBack()"/>
                <div class="container">

                        <br>
                        <h2>Assignment 4C</h2>
                        <br>
                        <?php
                        extract($_POST);
                        print_r($_POST);
                        if ($button == "Query"){
                            if(count($tbls) > 0){
                                echo "<form method='post' action='ex3query.php'>";
                                echo "<br><br>Select which table you would like to run query on?<br><br>";
                                foreach ($tbls as $key=>$value){
                                    echo "<input class='btn btn-secondary' type='submit' name='btn' value=$value>";
                                }

                                echo <<< HERE
                                <input type="hidden" name="uName" value=$uName>
                            <input type ="hidden" name="pass" value=$pass>
                                <input type="hidden" name="button" value =$button>
                            <input type="hidden" name="dbName" value=$dbName>
HERE;
                                echo "</form>";
                            }
                            $table = $btn;
                                if ($_POST['btn']){

                                $conn = mysqli_connect("localhost",$uName,$pass,$dbName);
                                echo <<< HERE
                                <form method="post" action="ex3query.php">
                                <h3>Employee table query</h3>
                                <table>
                                <tr>
                                <th>Fields to Display</th>
                                <th>First Name</th>
                                <th>Gender</th>
                                </tr>
                                <tr>
                                <td>
HERE;
                            // get the field names from the database and put them in checkboxes
                            // Note that I made this generic to work with any table in a DB.
                            // You could write it for a specific DB by just using known names.
                            $sql = "select * from $table";
                            $result = mysqli_query($conn,$sql);
                            while ($field = mysqli_fetch_field($result))
                            {
                                $fieldname = $field->name;
                                echo "<input type=\"checkbox\" name=\"$fieldname\" CHECKED>$fieldname";
                                echo "<br>";
                            }
                            echo <<< HERE
                            </td>
                            <td valign="top">
HERE;
                            // get the owners (no duplicates) from the DB and display as radio buttons
                            // Note that I made this generic to work with any table in a DB.
                            // You could write it for a specific DB by just using known names.
                            $sql = "select distinct First_Name from $table";
                            $result = mysqli_query($conn,$sql);
                            echo "<input type=\"radio\" name=\"FirstName\" value=\"All Employee\" CHECKED>
                                    All Employee's First Name<br>";
                            while ($row = mysqli_fetch_assoc($result))
                            {
                                $FirstName = $row["First_Name"];
                                echo "<input type=\"radio\" name=\"FirstName\" value=\"$FirstName\">$FirstName";
                                echo "<br>";
                            }
                            echo <<< HERE
                            </td>
                            <td valign="top">
HERE;
                            // get the species (no duplicates) from the DB and display as drop down box
                            // Note that I made this generic to work with any table in a DB.
                            // You could write it for a specific DB by just using known names.
                            $sql = "select distinct Gender from $table";
                            $result = mysqli_query($conn,$sql);
                            echo "<select name = \"gender\">";
                            echo "<option value = \"all gender\">All Gender</option>";
                            while ($row = mysqli_fetch_assoc($result))
                            {
                                $gender = $row["Gender"];
                                echo "<option value = \"$gender\">$gender</option>";
                            }
                            echo "</select>";
                            mysqli_close($conn);
                            echo <<< HERE
                            </td>
                            </tr>
                            </table>
                            <br>
                            <input class="btn btn-secondary" type="submit" name="button" value="Send Query">
                            <input type="hidden" name="uName" value=$uName>
                                <input type ="hidden" name="pass" value=$pass>
                                <input type="hidden" name="dbName" value=$dbName>
                                <input type="hidden" name="table" value=$table>
                            </form><br>
                            <form action="index.php">  <!-- change the action to where you want to go -->
                                     <input class="btn btn-success btn-lg btn-block" type="submit" value="Clear">
                                     <input type="hidden" name="uName" value=$uName>
                                <input type ="hidden" name="pass" value=$pass>
                                <input type="hidden" name="dbName" value=$dbName>
                                  </form>

HERE;
                        }
                        }
                        elseif ($button == "Send Query"){
                            $fieldnames = NULL;
                            $conn = mysqli_connect("localhost",$uName,$pass,$dbName);
                            $sql = "select * from $table";
                            $result = mysqli_query($conn,$sql);
                            while ($row = mysqli_fetch_field($result))
                            {
                                    if (isset($_REQUEST[$row->name]))
                                    {
                                    $fieldnames[] = $row->name;

                                    }
                            }

                            // Start building the query
                            if (mysqli_num_fields($result) == count($fieldnames)) // all the fields
                                $sql = "select * from $table";
                            else
                            {
                                $thefields = implode(",",$fieldnames);
                                $sql = "select " . $thefields . " from $table";
                            }
                            // Add the owner to the query.
                            if ($FirstName != "All Employee")
                                $sql = $sql . " where First_Name = '" . $FirstName . "'";
                            // Add the species to the query.
                            // Note: since "and" can only be used when there is more than one
                            // condition, I had to check whether the owner was added previously.
                            if ($gender != "all gender")
                                if ($FirstName != "All Employee")
                                    $sql = $sql . " and Gender = '" . $gender . "'";
                                else
                                    $sql = $sql . " where Gender = '" . $gender . "'";
                            // dump out the query for debugging purposes
                           // echo "<p><b>the query (output for debugging purposes only, remove
                             //       when program is working)</b>
                               //     <br><b>$sql</b></p>";
                            // send the query to the database
                            $result = mysqli_query($conn,$sql);
                            mysqli_close($conn);
                            printtable("Query Results",$result);
                            echo <<< HERE
                            </table>
                                    <br>
                            <form action="index.php">  <!-- change the action to where you want to go -->
                                <input class="btn btn-success btn-lg btn-block" type="submit" value="Clear">
                                <input type="hidden" name="uName" value=$uName>
                                <input type ="hidden" name="pass" value=$pass>
                                <input type="hidden" name="dbName" value=$dbName>

                                </form>
HERE;
                        }

function printtable($tablename,$result)
{
   //Display the entire table
   echo <<< HERE
      <h1>$tablename</h1>
      <table class="table">
      <thead class="thead-dark">
      <tr>
HERE;
   // Print the table column headers
   while ($field = mysqli_fetch_field($result))
   {
      echo "<th scope='col'><b>$field->name</b></th>\n";
   }
   echo "</tr>\n</thead>\n<tbody>";
   // Print each row.  $row is an associative array containing one
   // record in the table.
   while ($row = mysqli_fetch_assoc($result))
   {
      echo "<tr>\n";
      foreach($row as $field=>$value)
      {
         /* since the table has a border, put a blank (&nbsp;) in the variable
            if the database field is NULL so that the border of the table
            cell will be displayed */
         if ($value==NULL) $value="&nbsp;";
         echo "<td scope='row'>$value</td>\n";
      }
      echo "</tr>\n";
   }
   echo "</tbody></table>";
}

                        ?>

                </div>
</div>
<footer class="footer">
<div class="page-footer" style="text-align:center;">
Copyright &#169; Skaranjit inc.&#174;
</div>
</footer>
</body>
</html>
