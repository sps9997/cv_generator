<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cv_database";

// Connect to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle file upload
$photoName = $_FILES['photo']['name'] ?? '';
$photoTmp = $_FILES['photo']['tmp_name'] ?? '';
$photoPath = '';

if (!empty($photoTmp)) {
    $photoPath = uniqid() . "_" . basename($photoName);
    move_uploaded_file($photoTmp, "uploads/" . $photoPath);
}

$name = $_POST['name'];
$job_title = $_POST['job_title'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$address = $_POST['address'];
$skills = $_POST['skills'];
$languages = $_POST['languages'];
$hobbies = $_POST['hobbies'];
$profile_summary = $_POST['profile_summary'];
$work_experience = $_POST['work_experience'];
$education = $_POST['education'];

// Check for update
if (isset($_POST['update']) && $_POST['update'] === 'true') {
    $id = $_POST['id'];
    $oldPhoto = $_POST['old_photo'];

    if (empty($photoPath)) {
        $photoPath = $oldPhoto;
    } else {
        if (file_exists("uploads/" . $oldPhoto)) {
            unlink("uploads/" . $oldPhoto);
        }
    }

    $sql = "UPDATE cv_data SET photo='$photoPath', name='$name', job_title='$job_title', phone='$phone', email='$email', address='$address', skills='$skills', languages='$languages', hobbies='$hobbies', profile_summary='$profile_summary', work_experience='$work_experience', education='$education' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: view_cv.php?id=$id");
        exit();
    } else {
        echo "Error updating CV: " . $conn->error;
    }

} else {
    $sql = "INSERT INTO cv_data (photo, name, job_title, phone, email, address, skills, languages, hobbies, profile_summary, work_experience, education)
            VALUES ('$photoPath', '$name', '$job_title', '$phone', '$email', '$address', '$skills', '$languages', '$hobbies', '$profile_summary', '$work_experience', '$education')";

    if ($conn->query($sql) === TRUE) {
        $last_id = $conn->insert_id;
        header("Location: view_cv.php?id=$last_id");
        exit();
    } else {
        echo "Error saving CV: " . $conn->error;
    }
}

$conn->close();
?>
