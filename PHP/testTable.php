<?php
  session_start();  // On démarre la session
  include('database.php');
?>

<!DOCTYPE html>
<html>
   <head>
       <meta charset="utf-8" />
       <title>Profil</title>
   </head>

   <body>

    <table style="width:50%">
        <tr>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Age</th>
        </tr>
        <tr>
            <td>Jill</td>
            <td>Smith</td>
            <td>50</td>
        </tr>
        <tr>
            <td>Eve</td>
            <td>Jackson</td>
            <td>94
        </tr>
    </table>


   </body>
</html>