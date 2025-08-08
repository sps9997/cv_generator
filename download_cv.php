<?php
require 'vendor/autoload.php';
use Dompdf\Dompdf;

$id = $_GET['id'] ?? die("ID not provided.");

$conn = new mysqli('localhost', 'root', '', 'cv_database');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$result = $conn->query("SELECT * FROM cv_data WHERE id = $id");
if ($result->num_rows == 0) die("CV not found.");
$data = $result->fetch_assoc();
$conn->close();

$imagePath = $_SERVER['DOCUMENT_ROOT'] . "/cv_generator/uploads/" . $data['photo'];
$imageData = base64_encode(file_get_contents($imagePath));
$imageSrc = 'data:image/jpeg;base64,' . $imageData;

$html = "
<html>
<head>
  <style>
    body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
    .container { display: flex; height: 100vh; }
    .left { width: 30%; background-color: #1c2b36; color: white; padding: 20px; }
    .right { width: 70%; padding: 20px; }
    img { width: 100%; border-radius: 10px; }
    h2, h3 { margin-bottom: 5px; }
    .section { margin-bottom: 20px; }
    .section-title { border-bottom: 1px solid #ccc; margin-bottom: 10px; font-weight: bold; }
    ul { margin-top: 5px; padding-left: 20px; }
  </style>
</head>
<body>
  <div class='container'>
    <div class='left'>
      <img src='$imageSrc'>
      <h2>{$data['name']}</h2>
      <p>{$data['job_title']}</p>
      <p>Phone: {$data['phone']}</p>
      <p>Email: {$data['email']}</p>
      <p>Address: {$data['address']}</p>
      <div class='section'>
        <div class='section-title'>Skills</div>
        <ul><li>" . str_replace(",", "</li><li>", $data['skills']) . "</li></ul>
      </div>
      <div class='section'>
        <div class='section-title'>Languages</div>
        <ul><li>" . str_replace(",", "</li><li>", $data['languages']) . "</li></ul>
      </div>
      <div class='section'>
        <div class='section-title'>Hobbies</div>
        <ul><li>" . str_replace(",", "</li><li>", $data['hobbies']) . "</li></ul>
      </div>
    </div>
    <div class='right'>
      <div class='section'>
        <div class='section-title'>Profile Summary</div>
        <p>{$data['profile_summary']}</p>
      </div>
      <div class='section'>
        <div class='section-title'>Work Experience</div>
        <p>{$data['work_experience']}</p>
      </div>
      <div class='section'>
        <div class='section-title'>Education</div>
        <p>{$data['education']}</p>
      </div>
    </div>
  </div>
</body>
</html>
";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("cv_{$id}.pdf", array("Attachment" => true));
?>
