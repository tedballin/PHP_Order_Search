<?php

 // check if theres a query error, and if the query actually changes something
function confirm_query ($result_set,$connection){
    if($result_set && mysqli_affected_rows($connection)>0){
        // echo "SUCCESS!";
      }else{
        die("Database query failed.".mysqli_error($connection));
      }
}

//clean input data
function cleanData($data){
  $data = trim($data); //trim white space
  $data = stripcslashes($data);
  $data = strip_tags($data); //removes all html and php tags
  return $data;
}

// check tables
function tables($array){
    $tables_clause="";
   if( in_array("orders.",$array) && in_array("orders.",$array)){
      $tables_clause= "orders";
      return $tables_clause;
   }

}

?>