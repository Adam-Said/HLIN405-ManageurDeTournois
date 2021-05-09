<?php 

  

$conn = ""; 

  

try { 

    $servername = "********"; 

    $dbname = "********"; 

    $username = "********"; 

    $password = "********"; 

   

    $conn = new PDO( 

        "mysql:host=$servername; dbname=********", 

        $username, $password

    ); 

      

    $conn->setAttribute(PDO::ATTR_ERRMODE,  

                PDO::ERRMODE_EXCEPTION); 

      

} catch(PDOException $e) { 

    echo "Connection failed: " 

        . $e->getMessage(); 

} 

  

?> 
