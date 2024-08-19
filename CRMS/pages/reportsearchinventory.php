<?php
include 'dbconnect.php';

if(isset($_GET['item_name'])) {
    $item_name = $_GET['item_name'];

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to fetch events based on name
    $sql = "SELECT donate_item.cloth_condition, item_category.item_name, item_category.item_category
    FROM donate_item 
    join recycle_event 
    on donate_item.event_id= recycle_event.event_id
    join item_category 
    on item_category.item_id = donate_item.item_id
    WHERE item_category.item_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $item_name);
    $stmt->execute();
    $result = $stmt->get_result();
   

    //second query to count total
    // Count the total number of rows
    $totalCount = $result->num_rows;
      
    
    // Display the total count
    echo "<p>Total Item: $totalCount</p>";

    $index = 1; // Initialize index for auto-numbering

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $index++ . "</td>
                    <td>" . $row["item_name"] . "</td>
                    <td>" . $row["cloth_condition"] . "</td>
                    <td>" . $row["item_category"] . "</td>
                    
                  </tr>
                  ";
                 
        }
    } else {
        echo "<tr><td colspan='6'>No item found</td></tr>";
    }

  

    $stmt->close();
    $conn->close();
}
?>