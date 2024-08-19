<?php
include 'dbconnect.php';

if(isset($_GET['plate_number'])) {
    $plate_number = $_GET['plate_number'];

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to fetch events based on name
    $sql = "SELECT DISTINCT recycle_event.event_name, recycle_event.event_loc, recycle_event.event_date, pickup_session.pickup_status, pickup_session.pickup_date, driver.name
    FROM recycle_event 
    join pickup_session 
    on pickup_session.event_id= recycle_event.event_id
    join pickup_lorry
    on pickup_lorry.lorry_id = pickup_session.lorry_id
    join lorry_driver
    on lorry_driver.lorry_id = pickup_lorry.lorry_id
    join driver
    on driver.driver_id = lorry_driver.driver_id
    WHERE pickup_lorry.plate_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $plate_number);
    $stmt->execute();
    $result = $stmt->get_result();
   

    //second query to count total
    // Count the total number of rows
    $totalCount = $result->num_rows;
      
    echo "<p>Lorry Plate Number: $plate_number</p>";
    // Display the total count
    echo "<p>Total Event: $totalCount</p>";

    $index = 1; // Initialize index for auto-numbering

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $formatevent = date('d-m-Y', strtotime($row['event_date']));
            $formatpickup = date('d-m-Y', strtotime($row['pickup_date']));
            echo "<tr>
                    <td>" . $index++ . "</td>
                    <td>" . $row["event_name"] . "</td>
                    <td>" . $row["event_loc"] . "</td>
                    <td>" . $formatevent . "</td>
                    <td>" . $row["pickup_status"] . "</td>
                    <td>" . $formatpickup . "</td>
                    <td>" . $row["name"] . "</td>
                  </tr>
                  ";
                 
        }
    } else {
        echo "<tr><td colspan='6'>No events found</td></tr>";
    }

  

    $stmt->close();
    $conn->close();
}
?>