<?php
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $conn = new mysqli("localhost", "root", "", "cv_database");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get photo filename
    $sql = "SELECT photo FROM cv_data WHERE id = $id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $photo = $row['photo'];

        // Delete photo file
        if (!empty($photo) && file_exists("uploads/" . $photo)) {
            unlink("uploads/" . $photo);
        }

        // Delete record
        $deleteSql = "DELETE FROM cv_data WHERE id = $id";
        if ($conn->query($deleteSql) === TRUE) {
            header("Location: form.php?deleted=true");
            exit();
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    } else {
        echo "No CV found.";
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
