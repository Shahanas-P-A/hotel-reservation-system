<?php
require 'session.php';
require 'db.php';

// Dashboard Statistics
$totalCustomers = $pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn();
$totalRooms = $pdo->query("SELECT COUNT(*) FROM rooms")->fetchColumn();
$availableRooms = $pdo->query("SELECT COUNT(*) FROM available_rooms")->fetchColumn();
$totalReservations = $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();

// Recent Reservations
$sql = "
SELECT
    r.reservation_id,
    c.name,
    ro.room_type,
    r.check_in,
    r.check_out
FROM reservations r
JOIN customers c ON r.customer_id = c.customer_id
JOIN rooms ro ON r.room_id = ro.room_id
ORDER BY r.reservation_date DESC
LIMIT 5
";

$recentReservations = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hotel Reservation Dashboard</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body>

<nav class="navbar">

<a class="navbar-brand" href="index.php">
<i class="fas fa-hotel"></i>
LuxeStay MS
</a>

<ul class="nav-links">

<li><a href="index.php">Dashboard</a></li>
<li><a href="customers.php">Customers</a></li>
<li><a href="rooms.php">Rooms</a></li>
<li><a href="history.php">History</a></li>
<li>
<a href="book.php" class="btn btn-primary">
Book Room
</a>
</li>
<li>
    <a href="logout.php" class="btn btn-danger">
        Logout
    </a>
</li>

</ul>

</nav>

<div class="container">

<div class="page-header">
<h1 class="page-title">
Dashboard Overview
</h1>
</div>

<div class="dashboard-grid">

<div class="card">
<i class="fas fa-users card-icon"></i>

<div class="card-title">
Customers
</div>

<div class="card-value">
<?= $totalCustomers ?>
</div>

</div>

<div class="card">

<i class="fas fa-door-open card-icon"></i>

<div class="card-title">
Rooms
</div>

<div class="card-value">
<?= $totalRooms ?>
</div>

</div>

<div class="card">

<i class="fas fa-check-circle card-icon"></i>

<div class="card-title">
Available
</div>

<div class="card-value">
<?= $availableRooms ?>
</div>

</div>

<div class="card">

<i class="fas fa-calendar-check card-icon"></i>

<div class="card-title">
Reservations
</div>

<div class="card-value">
<?= $totalReservations ?>
</div>

</div>

</div>

<div class="page-header" style="margin-top: 3rem;">
    <h2 class="page-title" style="font-size: 1.4rem;">
        Recent Reservations
    </h2>

    <div style="display:flex; gap:10px;">
        <a href="book.php" class="btn btn-success">
            <i class="fas fa-plus"></i> New Booking
        </a>

        <a href="history.php" class="btn btn-primary">
            <i class="fas fa-history"></i> View History
        </a>
    </div>
</div>

<div class="table-container">

<table>

<thead>

<tr>

<th>ID</th>
<th>Customer</th>
<th>Room</th>
<th>Check In</th>
<th>Check Out</th>

</tr>

</thead>

<tbody>

<?php if(count($recentReservations)>0): ?>

<?php foreach($recentReservations as $row): ?>

<tr>

<td>#<?= $row['reservation_id'] ?></td>

<td><?= htmlspecialchars($row['name']) ?></td>

<td><?= htmlspecialchars($row['room_type']) ?></td>

<td><?= date("d M Y",strtotime($row['check_in'])) ?></td>

<td><?= date("d M Y",strtotime($row['check_out'])) ?></td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="5" style="text-align:center;padding:30px;">
No Reservations Found
</td>

</tr>

<?php endif; ?>

</tbody>

</table>

</div>

</div>
<footer class="footer">
    <p>© 2026 LuxeStay MS | Hotel Reservation System</p>
</footer>
</body>
</html>