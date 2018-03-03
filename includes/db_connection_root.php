<!-- 1. create a database connection -->
<?php
    //use constants values
    define("DB_SERVER", "localhost");
    define("DB_USER", "root");
    define("DB_PASS", "");
    define("DB_NAME", "classicmodels");

    //create the handle (mysqli object)
    @$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

    //test if connection occured, if there's an error num or 0 is success;
    if(mysqli_connect_errno()){
      die("Database connection failed:" .
      mysqli_connect_error() .
      "(" . mysqli_connect_errno(). ")"
    );
    exit();
    }
?>