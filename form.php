<!-- Complete CV Generator Project -->
<!-- FILE 1: form.php -->
<?php
$updateMode = false;
$data = [];

if (isset($_GET['update']) && $_GET['update'] === 'true' && isset($_GET['id'])) {
    $updateMode = true;
    $user_id = $_GET['id'];
    $conn = new mysqli('localhost', 'root', '', 'cv_database');
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
    $sql = "SELECT * FROM cv_data WHERE id = $user_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo $updateMode ? 'Update CV' : 'Create CV'; ?></title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<form action="save_cv.php" method="POST" enctype="multipart/form-data">
  <h2><?php echo $updateMode ? 'Update Your CV' : 'Create Your CV'; ?></h2>

  <?php if ($updateMode): ?>
    <input type="hidden" name="update" value="true">
    <input type="hidden" name="id" value="<?php echo $user_id; ?>">
    <input type="hidden" name="old_photo" value="<?php echo $data['photo']; ?>">
  <?php endif; ?>

  <button type="button" class="accordion">Profile Photo</button>
  <div class="panel">
    <label>Upload Photo:</label>
    <input type="file" name="photo" <?php echo $updateMode ? '' : 'required'; ?>>
    <?php if ($updateMode && !empty($data['photo'])): ?>
      <img src="uploads/<?php echo $data['photo']; ?>" alt="Current Photo">
    <?php endif; ?>
  </div>

  <button type="button" class="accordion">Personal Information</button>
  <div class="panel">
    <label>Name:</label>
    <input type="text" name="name" value="<?php echo $data['name'] ?? ''; ?>" required>

    <label>Job Title:</label>
    <input type="text" name="job_title" value="<?php echo $data['job_title'] ?? ''; ?>" required>

    <label>Phone:</label>
    <input type="text" name="phone" value="<?php echo $data['phone'] ?? ''; ?>" required>

    <label>Email:</label>
    <input type="email" name="email" value="<?php echo $data['email'] ?? ''; ?>" required>

    <label>Address:</label>
    <input type="text" name="address" value="<?php echo $data['address'] ?? ''; ?>" required>
  </div>

  <button type="button" class="accordion">Skills, Languages & Hobbies</button>
  <div class="panel">
    <label>Skills (comma-separated):</label>
    <input type="text" name="skills" value="<?php echo $data['skills'] ?? ''; ?>" required>

    <label>Languages (comma-separated):</label>
    <input type="text" name="languages" value="<?php echo $data['languages'] ?? ''; ?>" required>

    <label>Hobbies (comma-separated):</label>
    <input type="text" name="hobbies" value="<?php echo $data['hobbies'] ?? ''; ?>" required>
  </div>

  <button type="button" class="accordion">Profile Summary</button>
  <div class="panel">
    <textarea name="profile_summary" required><?php echo $data['profile_summary'] ?? ''; ?></textarea>
  </div>

  <button type="button" class="accordion">Work Experience</button>
  <div class="panel">
    <textarea name="work_experience" required><?php echo $data['work_experience'] ?? ''; ?></textarea>
  </div>

  <button type="button" class="accordion">Education</button>
  <div class="panel">
    <textarea name="education" required><?php echo $data['education'] ?? ''; ?></textarea>
  </div>

  <input type="submit" value="<?php echo $updateMode ? 'Update CV' : 'Generate CV'; ?>">
</form>

<script>
  const acc = document.getElementsByClassName("accordion");
  for (let i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
      this.classList.toggle("active");
      const panel = this.nextElementSibling;
      panel.style.display = panel.style.display === "block" ? "none" : "block";
    });
  }
</script>
</body>
</html>