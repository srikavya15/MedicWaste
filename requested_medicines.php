<?php
include "config.php"; // Database connection

// Search Filter
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch requested medicines from database
$sql = "SELECT * FROM requested_medicines WHERE medicine_name LIKE ? OR location LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$search%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requested Medicines</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Requested Medicines</h2>
        <input type="text" id="search" placeholder="Search by name or location" onkeyup="searchMedicine()">

        <table>
            <thead>
                <tr>
                    <th>Medicine Name</th>
                    <th>Quantity</th>
                    <th>Location</th>
                    <th>Requester</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="medicineTable">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['medicine_name']) ?></td>
                        <td><?= htmlspecialchars($row['quantity']) ?></td>
                        <td><?= htmlspecialchars($row['location']) ?></td>
                        <td><?= htmlspecialchars($row['requester']) ?></td>
                        <td><a href="donate.php?medicine=<?= urlencode($row['medicine_name']) ?>" class="donate-btn">Donate Now</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchMedicine() {
            let searchValue = document.getElementById('search').value.toLowerCase();
            let rows = document.querySelectorAll("#medicineTable tr");

            rows.forEach(row => {
                let name = row.cells[0].innerText.toLowerCase();
                let location = row.cells[2].innerText.toLowerCase();
                row.style.display = (name.includes(searchValue) || location.includes(searchValue)) ? "" : "none";
            });
        }
    </script>
</body>
</html>