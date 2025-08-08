<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cv_database";

// Connect
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$id = $_GET['id'];
$sql = "SELECT * FROM cv_data WHERE id=$id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
} else {
  die("CV not found.");
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>CV Preview</title>
  <style>
    body { margin:0; font-family: Arial; }
    .container {
      display: flex;
      width: 210mm;
      height: 297mm;
      margin: 20px auto;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }
    .sidebar {
      width: 30%;
      background: #2c3e50;
      color: white;
      padding: 20px;
      box-sizing: border-box;
    }
    .sidebar img {
      width: 100%;
      height: auto;
      border-radius: 8px;
    }
    .content {
      width: 70%;
      background: white;
      padding: 20px;
      box-sizing: border-box;
    }
    h2 { border-bottom: 1px solid #ccc; padding-bottom: 5px; }
    ul { padding-left: 20px; }

    .download-btn, .update-btn, .delete-btn {
      position: fixed;
      bottom: 20px;
      padding: 12px 20px;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
      color: white;
    }

    .download-btn {
      right: 30px;
      background: #27ae60;
    }
    .download-btn:hover { background: #219150; }

    .update-btn {
      left: 30px;
      background: #007bff;
    }
    .update-btn:hover { background: #0056b3; }

    .delete-btn {
      left: 160px;
      background: #dc3545;
    }
    .delete-btn:hover { background: #a71d2a; }
  </style>
</head>
<body>

<div class="container">
  <div class="sidebar">
    <img src="uploads/<?php echo $row['photo']; ?>" alt="Profile Photo">
    <h3><?php echo $row['name']; ?></h3>
    <p><strong><?php echo $row['job_title']; ?></strong></p>
    <p><strong>Phone:</strong> <?php echo $row['phone']; ?></p>
    <p><strong>Email:</strong> <?php echo $row['email']; ?></p>
    <p><strong>Address:</strong> <?php echo $row['address']; ?></p>
    <h2>Skills</h2>
    <ul>
      <?php foreach (explode(",", $row['skills']) as $skill) echo "<li>$skill</li>"; ?>
    </ul>
    <h2>Languages</h2>
    <ul>
      <?php foreach (explode(",", $row['languages']) as $lang) echo "<li>$lang</li>"; ?>
    </ul>
    <h2>Hobbies</h2>
    <ul>
      <?php foreach (explode(",", $row['hobbies']) as $hobby) echo "<li>$hobby</li>"; ?>
    </ul>
  </div>

  <div class="content">
    <h2>Profile Summary</h2>
    <p><?php echo nl2br($row['profile_summary']); ?></p>

    <h2>Work Experience</h2>
    <p><?php echo nl2br($row['work_experience']); ?></p>

    <h2>Education</h2>
    <p><?php echo nl2br($row['education']); ?></p>
  </div>
</div>

<a href="form.php?update=true&id=<?php echo $row['id']; ?>" class="update-btn">Update</a>
<a href="delete_cv.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this CV?');">Delete</a>
<a href="download_cv.php?id=<?php echo $row['id']; ?>" class="download-btn" target="_blank">Download CV</a>

</body>
</html>
