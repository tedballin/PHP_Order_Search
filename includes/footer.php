</body>
</html>

<?php
//5. close database connection
//it only closes on pages that have database connection
if(isset($connection)){
   mysqli_close($connection);
}
?>