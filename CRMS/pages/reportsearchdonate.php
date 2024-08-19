<?php
include 'dbconnect.php';

if(isset($_GET['event_name']) || isset($_GET['cloth_condition'])) {
    $event_name = $_GET['event_name'];
    $cloth_condition = $_GET['cloth_condition'];

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Build the SQL query with optional filters
    $sql = "SELECT donate_item.cloth_condition, item_category.item_name, item_category.item_category
            FROM donate_item 
            JOIN recycle_event 
            ON donate_item.event_id= recycle_event.event_id
            JOIN item_category 
            ON item_category.item_id = donate_item.item_id
            WHERE 1=1";

    // Apply filters if they are set
    if ($event_name) {
        $sql .= " AND recycle_event.event_name = ?";
    }
    if ($cloth_condition) {
        $sql .= " AND donate_item.cloth_condition = ?";
    }

    $stmt = $conn->prepare($sql);

    // Bind parameters dynamically based on the filters
    if ($event_name && $cloth_condition) {
        $stmt->bind_param("ss", $event_name, $cloth_condition);
    } elseif ($event_name) {
        $stmt->bind_param("s", $event_name);
    } elseif ($cloth_condition) {
        $stmt->bind_param("s", $cloth_condition);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Count the total number of rows
    $totalCount = $result->num_rows;
      
    echo "<p>Event Name: $event_name</p>";
    echo "<p>Cloth Condition: $cloth_condition</p>";
    echo "<p>Total Item: $totalCount</p>";

    $index = 1; // Initialize index for auto-numbering

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $index++ . "</td>
                    <td>" . $row["item_name"] . "</td>
                    <td>" . $row["cloth_condition"] . "</td>
                    <td>" . $row["item_category"] . "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No items found</td></tr>";
    }

    $stmt->close();
    $conn->close();
}

?>