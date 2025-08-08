<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

// Connect to database
$conn = new mysqli("localhost", "root", "", "cv_database");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search_results = [];
$keyword = '';

if (isset($_GET['search'])) {
    $keyword = $conn->real_escape_string($_GET['keyword']);

    // ✅ Updated table and column names
    $sql = "SELECT * FROM cv_data WHERE name LIKE '%$keyword%' OR email LIKE '%$keyword%'";
    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $search_results[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin - Search Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #222;
            color: white;
            padding: 20px;
        }
        input[type=text] {
            padding: 8px;
            width: 300px;
            border-radius: 4px;
            border: none;
            margin-right: 10px;
        }
        input[type=submit] {
            padding: 8px 16px;
            border-radius: 4px;
            border: none;
            background: #007bff;
            color: white;
            cursor: pointer;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: #333;
        }
        th, td {
            padding: 12px;
            border: 1px solid #444;
        }
        th {
            background: #444;
        }
        a.logout {
            float: right;
            color: #ff4d4d;
            text-decoration: none;
            font-weight: bold;
        }
        a.logout:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Search Users</h1>
    <a href="admin_logout.php" class="logout">Logout</a>

    <form method="get" action="">
        <input type="text" name="keyword" placeholder="Search by name or email" required
            value="<?= htmlspecialchars($keyword) ?>" />
        <input type="submit" name="search" value="Search" />
    </form>

    <?php if (!empty($search_results)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>View CV</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($search_results as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><a href="view_cv.php?id=<?= $user['id'] ?>" target="_blank">View CV</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif (isset($_GET['search'])): ?>
        <p>No results found for <strong><?= htmlspecialchars($keyword) ?></strong>.</p>
    <?php endif; ?>
</body>
</html>
