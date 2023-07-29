<?php
session_start();
require_once 'includes/db_config.php';
require_once 'includes/functions.php';


function getUserHierarchy() {
    global $conn;

    // Replace 'users' with your actual table name
    $query = "SELECT id, name, sponsor_id FROM users";

    $result = mysqli_query($conn, $query);
    $data = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $user_id = $row['id'];
        $name = $row['name'];
        $sponsor_id = $row['sponsor_id'];

        $data[$user_id] = array(
            'name' => $name,
            'children' => array()
        );

        // Check if the user has a sponsor
        if ($sponsor_id !== null) {
            $data[$sponsor_id]['children'][] = &$data[$user_id];
        }
    }

    // Find the root node (user without a sponsor)
    $root = null;
    foreach ($data as $user_id => $node) {
        if (!isset($data[$user_id]['parent'])) {
            $root = $data[$user_id];
            break;
        }
    }

    return $root;
}

// Get the user hierarchy data
$user_hierarchy = getUserHierarchy();

// Output the data as JSON
header('Content-Type: application/json');
echo json_encode($user_hierarchy);
?>
