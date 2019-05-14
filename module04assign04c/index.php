<!DOCTYPE html> <html> <head> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <script>
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
<div id="homepage" class="container-fluid">
        <img src="./../content/images/goback.png" onclick="goBack()"/>
                        <h2>Assignment 4C</h2>
                        <br>
                        <?php
                                extract($_REQUEST);
                                print_r($_REQUEST);
                           $data = array();
                           $today = date("l, F jS, Y g:i A");
                           echo "<p>Today is <strong>",$today,"</strong></p>";
                        if ($button == NULL || $button == "back to form"){
                                loginPage();
                        }
                        elseif ($button == "login" or $button == "Clear") {
                            loggedin($uName,$pass,$dbName,$tbls);
                        }
                        else{
                            print_r($tbls);
                            loadTable($button,$uName,$pass,$dbName);
                        }


// ***********************************************************
// FUNCTIONS
                        function loginPage(){
                           include "password.php";
                            echo <<< HERE
                            <form>
                            <div class="form-group">
                                <labl for="db">Databae Name</label>
                                <input type="text" class="form-control" id="db" name="dbName" placeholder="Enter Database Name" value=$dbname>
                                </div>
                            <div class="form-group">
                                <label for="username">UserName</label>
                                <input type="text" class="form-control" id="username" name="uName" placeholder="UserName" value=$uname>
                                </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="pass" placeholder="Password" value=$password>
                                </div>
                            <br>
                            <div class = "form-group">
                            <input type="submit" class="btn btn-primary" name="button" value="login">
                            </div>
                            <form>
HERE;
                          }
                        function loggedin($uName,$pass,$dbName,&$tbls){
                            $status = "Connection Failed";
                            // Create Connection
                            $conn = new mysqli('localhost',$uName,$pass,$dbName);
                            //Check Connection
                            if ($conn->connect_error){
                                die("Connection Failed! " . $conn->connect_error);
                            }
                            else{
                            $status = "Connection Successfull";}
                            if ($status == "Connection Failed"){
                                echo "<h2>",$status,"</h2>";
                            }
                            else{
                            echo <<< HERE
                                <form>
                                <h2>Database: $dbName</h2>
                                <h2>View Tables:
                                <input type="hidden" name="uName" value=$uName>
                                <input type ="hidden" name="pass" value=$pass>
                                <input type="hidden" name="dbName" value=$dbName>

HERE;
                                $sql = "SHOW TABLES FROM $dbName";
                                $result = mysqli_query($conn,$sql);
                                $tbls =  array();
                                while ($row = mysqli_fetch_array($result)) {
                                        echo "<input class='btn btn-secondary' type='submit' name='button' value='$row[0]'>";
                                        $tbls[] = $row[0];
                                }

                                echo <<< HERE
                                <BR>
                                </h2>
                                <br><br>
                                <h3>You can search,delete or add entry to the database</h3>
                                </form>
                                <br>
                                <form method="POST" action="ex3query.php">
                                <input type="hidden" name="uName" value=$uName>
                                <input type ="hidden" name="pass" value=$pass>
                                <input type="hidden" name="dbName" value=$dbName>
                                <input class ="btn btn-primary btn-block" type="submit" name="button" value="Query">
HERE;
                                passData($tbls);
                                echo <<< HERE
                                </form>
                                <form method="POST" action="ex3delete.php">
                                <input class="btn btn-danger btn-block" type="submit" name="button" value="Delete">
                                <input type="hidden" name="uName" value=$uName>
                                <input type ="hidden" name="pass" value=$pass>
                                <input type="hidden" name="dbName" value=$dbName>
HERE;
                                passData($tbls);
                                echo <<< HERE
                                </form>
                                <form method="POST" action="ex3add.php">
                                <input class="btn btn-success btn-block" type="submit" name="button" value="Add">
                                <input type="hidden" name="uName" value=$uName>
                                <input type ="hidden" name="pass" value=$pass>
                                <input type="hidden" name="dbName" value=$dbName>
HERE;
                                passData($tbls);
                                echo <<< HERE
                                </form>
                                <br>
                            <form action="./../index.html">  <!-- change the action to where you want to go -->
                                <input class="btn btn-dark btn-lg btn-block" type="submit" value="Return to Assignment index">
                                <input type="hidden" name="uName" value=$uName>
                            <input type ="hidden" name="pass" value=$pass>
                            <input type="hidden" name="dbName" value=$dbName>
                                </form>

HERE;
                                 }
                        }
                        function loadTable($button,$uName,$pass,$dbName){
                            echo <<< HERE
                            <form>
                            <input class="btn btn-secondary" type="submit" name="button" value="Clear">
                            <input type="hidden" name="uName" value=$uName>
                            <input type ="hidden" name="pass" value=$pass>
                            <input type="hidden" name="dbName" value=$dbName>
HERE;
                            passData($tbls);
                            echo "</form>";
                            $table = $button;
                            $conn = mysqli_connect("localhost",$uName,$pass,$dbName);
                            $sql = "select * from $table";
                            $result = mysqli_query($conn,$sql);

                            // output the field names
                            echo "<h3>Field Names in the $table table</h3>";
                            while ($field = mysqli_fetch_field($result))
                            {
                                echo "$field->name<br>\n";
                            }
                            // output the records
                            echo "<h3>Records in the $table table</h3>";
                            echo "------------------<br>";
                            while ($row = mysqli_fetch_assoc($result))
                            {
                                foreach ($row as $col=>$val)
                                    {
                                        echo "$col - $val<br>\n";
                                    }
                                echo "------------------<br>";
                            }
                        }
                    

                function passData($tbls){
                    foreach($tbls as $key=>$value){
                        echo "<input type='hidden' name='tbls[$key]' value=$value>";
                    }
                }
                echo "<HR>";
highlight_file("index.php");
                        ?>
                </div>
<footer class="footer">
<div class="page-footer" style="text-align:center;">
Copyright &#169; Skaranjit inc.&#174;
</div>
</footer>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>
