<?php

include_once("inc/HTMLTemplate.php");

$feedback='';

/*Admin form*/
$adminForm = <<<END
<div class="container">
   <div class="col-md-6 col-md-offset-3" >
      <div id="login-form-container">
         <form action="admin.php" method="post" id="login-form">

         <div class="row">
            <div class="col-md-6 col-md-offset-3" >
               <legend>Admin login</legend>
            </div>
         </div>

         <div class="row">
            <div class="col-md-4 col-md-offset-3"
               <label for="username">Username: </label><br> <!-- 1st ROW  -->
            </div>
         </div>

         <div class="row">
            <div class="col-md-4 col-md-offset-4">
               <input type="text" class="form-control input-sm" id="username" name="username" placeholder="Username"/><br><!-- 2nd ROW  -->
            </div>
         </div>
         

         <div class="row">
            <div class="col-md-4 col-md-offset-3"
               <label for="username">Password: </label><br><!-- 3rd ROW  -->
            </div>
         </div>

         <div class="col-md-4 col-md-offset-4">
            <input type="password" class="form-control input-sm" name="password" placeholder="Password"/><br><!-- 4th ROW  -->
         </div>
         
         <br>

         <div class="row">
            <div class="col-md-6 col-md-offset-3">
               <button type="submit" class="btn btn-default">Submit</button>
            </div>
         </div>

         </form>
      </div> <!-- login-form-container  -->
   </div> <!-- col-md-6 col-md-offset-3 -->
</div><!-- container -->
END;


// if the user hit submit
if(! empty($_POST)){

    include_once("inc/connstring.php");
    $table="admin"; // name of the DB table

    // retrieve data
    $username = isset($_POST['username']) ? $_POST['username']  : '';
    $password = isset($_POST['password']) ? $_POST['password']  : '';

    // if fields were empty
    if($username == '' || $password =='') {
        $feedback="<p>Please fill out all the fields.</p>";
    }
    else{
        // --------------------
        // Prevent SQL injecti�
        // --------------------
        $username = $mysqli->real_escape_string($username);
        $password = $mysqli->real_escape_string($password);

        // define query
        $query = <<<END
        --
        -- Seek the corresponding username
        -- and password in the DB
        --
        SELECT name, password
        FROM $table
        WHERE name = "{$username}";
END;

        // perform query and catch result
        // if query fails display error message with corresponding nb
        $res = $mysqli->query($query) or die ("could not query database" . $mysqli->errno . " : " . $mysqli->error);

        if($res->num_rows == 1) {

            //encrypt the given password
            $pswMD5 = md5($password);

            // retreive data
            $row = $res->fetch_object();

            // if the encrypt given psw corresponds to the encrypt psw in the DB
            if($row->password == $pswMD5) {
                header("Location: index.php");
                die();
                $feedback = "<p>Welcome !</p>";
                
            }
            else{
                $feedback = "<p>Wrong password</p>";
            }
            $res->close();
        }
        else {
            $feedback = "<p>Wrong username</p>";
        }
        $mysqli->close();

    }

}

echo $header;
echo $adminForm;
echo $feedback;
echo $footer;

?>