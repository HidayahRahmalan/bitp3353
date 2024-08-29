<?php
include 'dbconnect.php';

header('Content-Type: application/json');

if (isset($_POST['event_id'])) {
    $event_id = $conn->real_escape_string($_POST['event_id']);

    // Query to calculate the total number of donated items for the selected event
    $totalDonationsQuery = $conn->query("SELECT COUNT(*) as total FROM donate_item WHERE event_id = '$event_id'");
    
    if ($totalDonationsQuery) {
        $totalDonationsResult = $totalDonationsQuery->fetch_assoc();
        $totalDonations = $totalDonationsResult['total'];
        echo json_encode($totalDonations);
    } else {
        echo json_encode('Error executing query');
    }
} else {
    echo json_encode('No event ID provided');
}
?>
