<!-- create safe database connection, no credential shows -->
<?php require_once("includes/db_connection_root.php"); ?>
<!-- load functions -->
<?php require_once("includes/functions.php"); ?>
<!-- load header -->
<?php include("includes/header.php"); ?>
<div class="contain_all">
        <h1>Query</h1>    
        <?php
           //2. perform a database query, get the order numbers from database
            $query1 = "SELECT orderNumber ";
            $query1 .= "FROM orders ";
            $query1 .= "ORDER BY orderNumber ASC";

            $num_result= mysqli_query($connection,$query1);

             // check if theres a query error, function required from functions.php
            confirm_query($num_result,$connection);
           ?>
        <!-- submit to itself -->
        <form action="" method="post">
        <div class="search_table"> 
            <div class="col-half">
            <h2>Select Order Parameters</h2>
                Order Number:
                <select name="order_num">
      <option vlaue =""></option>
      <?php         
      //3. use returned data, fetch_assoc returns a associative array
      while($od_num = mysqli_fetch_assoc($num_result)){
    ?>
        <!-- loop the order number and put it into the dropdown list in descending order -->
     <option vlaue ="<?php echo $od_num["orderNumber"];?>">
     <?php echo $od_num["orderNumber"];?>
    </option>
     <?php
        }
    ?>
         <?php
        // 4. free returned data 
        mysqli_free_result($num_result);
     ?>
            </select>    
                or<br><br>
                Order Date (YYYY-MM-DD)<br>
                form:
                <input type="date" name="date_from">
                to:
                <input type="date" name="date_to">
            </div>
            <div class="col-half">
            <h2>Select Columns to Display</h2>
                <input type="checkbox" value = "orders.orderNumber" name="order_number">Order Number<br>
                <input type="checkbox" value = "orders.orderDate" name="order_date">Order Date<br>
                <input type="checkbox" value = "orders.shippedDate" name="shipped_date">Shipped Date<br>
                <input type="checkbox" value = "products.productName" name="product_name">Product Name<br>
                <input type="checkbox" value = "products.productDescription" name="product_description">Product Description<br>
                <input type="checkbox" value = "orderdetails.quantityOrdered" name="quantity_ordered">Quantity Ordered<br>
                <input type="checkbox" value = "orderdetails.priceEach" name="price_each">Price Each<br>
                </div>
                <div class = "col-full">
                <input type="submit" name="submit_btn" value="SearchOrders">
                </div>
                </div>
            </form>
    <!-- process form -->
        <?php 
        //create an array of selected columns
        $cols = array();
        //create emtpy query
        $query = "";

        if(isset($_POST["submit_btn"])){
            
            // echo '<pre>'; var_dump($_POST); echo '</pre>';
            //check if the post values are set, then add to the cols array
            if(isset($_POST["order_num"])){$orderNum = cleanData($_POST["order_num"]);}
            if(isset($_POST["date_from"])){$dateFrom =  cleanData($_POST["date_from"]);}
            if(isset($_POST["date_to"])){$dateTo =  cleanData($_POST["date_to"]);}
            //display cols
            if(isset($_POST["order_number"]))
            {$order_number = cleanData($_POST["order_number"]);
                array_push($cols, $order_number);}
            if(isset($_POST["order_date"]))
            {$order_date = cleanData($_POST["order_date"]);
                array_push($cols, $order_date);}
            if(isset($_POST["shipped_date"]))
            {$shipped_date = cleanData($_POST["shipped_date"]);
                array_push($cols, $shipped_date);}
            if(isset($_POST["product_name"]))
            {$product_name = cleanData($_POST["product_name"]);
                array_push($cols, $product_name);}
            if(isset($_POST["product_description"]))
            {$product_description = cleanData($_POST["product_description"]);
                array_push($cols, $product_description);}
            if(isset($_POST["quantity_ordered"]))
            {$quantity_ordered = cleanData($_POST["quantity_ordered"]);
                array_push($cols, $quantity_ordered);}
            if(isset($_POST["price_each"]))
            {$price_each = cleanData($_POST["price_each"]);
                array_push($cols, $price_each);}
        
            //covert $cols arry into string, and add , to separate each column    
            $cols_clause = implode(",", $cols);

            //validation
            //check if at least one comlum is selected to display the query result
            // if($order_number&&!$order_date&&!$shipped_date&&!$product_name&&!$product_description&&!$quantity_ordered&&!$price_each){
            //     echo " You have to select at least one column to display the search result.";
            //     exit;
            // }

             //check if dateFrom is earlier than dateTo
             if($dateFrom>$dateTo){
                 echo "The date to has to be later than the date from!";
                 echo $dateFrom .">". $dateTo;
             }
            

            //create a variable for conditions based on user's choice
            if ((!empty($orderNum)&&!empty($dateFrom)&&!empty($dateTo)) || (!empty($orderNum)&&!empty($dateFrom)) || (!empty($orderNum)&&!empty($dateTo)))
            {
                die ("Please select by either Order Number or Order Date!");               
            }elseif (!empty($dateFrom)&&!empty($dateTo))
            {
                $conditions= "WHERE orders.orderDate >= '$dateFrom' AND orders.orderDate <= '$dateTo' ";
            } elseif(!empty($orderNum)) 
            {
                $conditions= "WHERE orders.orderNumber = $orderNum ";
            }

           //2. perform a daynamic query based on user's input
           $query = "SELECT $cols_clause ";
           $query .= "FROM orders INNER JOIN orderdetails ON orders.orderNumber = orderdetails.orderNumber INNER JOIN products ON orderdetails.productCode = products.productCode ";
           $query .= $conditions;
           // $query .= "XOR (orders.orderDate >= '$dateFrom' AND orders.orderDate <= '$dateTo')";

       $order_result= mysqli_query($connection,$query);

        // check if theres a query error, function required from functions.php
       confirm_query($order_result,$connection);

        } 

        //    echo '<pre>'; var_dump($cols); echo '</pre>';

        //    if(in_array("orders", $cols)&&in_array("orderdetails", $cols)&&in_array("products", $cols)){
        //     $table_clause = "FROM orders INNER JOIN orderdetails ON orders.orderNumber = orderdetails.orderNumber 
        //     INNER JOIN products ON orderdetails.productCode = products.productCode";
            
        // } elseif (in_array("orders", $cols)&&in_array("orderdetails", $cols)){
        //  $table_clause = "FROM orders INNER JOIN orderdetails ON orders.orderNumber = orderdetails.orderNumber";
       
        // }elseif (in_array("ordersdetails", $cols)&&in_array("products", $cols)){
        //  $table_clause = "FROM orderdetails INNER JOIN products ON orderdetails.productCode = products.productCode";
         
        // }elseif (in_array("orders", $cols)&&in_array("products", $cols)){
        //  $table_clause = "FROM orders,products";
        
        //  $table_clause = "FROM orders,products";
        // } elseif (in_array("orders", $cols)) {
        //  $table_clause = "FROM orders";
        //  }elseif (in_array("orderdetails", $cols)) {
        //      $table_clause = "FROM orderdetails";
        //      } else{
        //          $table_clause = "FROM products";
        //      }
        //      echo $table_clause;
           ?>
   
    <h3>SQL Query:</h3>
    <div class="query_text">
        <?php
        echo $query; ?>
    </div>
    <h1>Result</h1>
    <div class="query_result">    
    
   <table border="1">
   <?php             
    //3. use returned data 
        //loop if there is still data from the $order
        //cannot use foreach(mysqli_fetch_row(), because cannot increment the pointer at the end
        
        //mysqli_fetch_assoc returns a associative array
        //create table colums

    //if the form is not submitted, dont run these code 
    if(!empty($order_result)) {
        echo "<tr>"; 
        foreach(mysqli_fetch_assoc($order_result) as $key=>$value){
            echo "<th>".$key."</th>";
        }
        echo"</tr>";
        //put values into each row
        while($order = mysqli_fetch_assoc($order_result)){
            echo "<tr>";
            foreach ($order as $key=>$value){
                echo "<td>".$value."</td>";
            }
            echo "</tr>\n";
        }
    }
    ?>
     </table>
   
     <?php
        // 4. free returned data 
     @mysqli_free_result($order_result);?>
    </div>
</div>

<?php include("includes/footer.php"); ?>
