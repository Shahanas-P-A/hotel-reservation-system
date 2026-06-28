<?php
require 'session.php';
require 'db.php';

$query = "
SELECT
    r.reservation_id,
    c.name,
    c.phone,
    c.email,
    ro.room_type,
    ro.price,
    r.check_in,
    r.check_out,
    r.reservation_date
FROM reservations r
JOIN customers c
ON r.customer_id=c.customer_id
JOIN rooms ro
ON r.room_id=ro.room_id
ORDER BY r.reservation_date DESC
";

$reservations = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
$totalBookings = count($reservations);
$totalRevenue = 0;

foreach ($reservations as $res) {
    $date1 = new DateTime($res['check_in']);
    $date2 = new DateTime($res['check_out']);
    $nights = $date2->diff($date1)->days;
    $totalRevenue += $nights * $res['price'];
}
?>



<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Booking History</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body>

<nav class="navbar">

<a href="index.php" class="navbar-brand">

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
        <i class="fas fa-history"></i>
        Reservation History
    </h1>

    

</div>

<div class="dashboard-grid">

    <div class="card">
        <i class="fas fa-calendar-check card-icon"></i>
        <div class="card-title">Total Reservations</div>
        <div class="card-value"><?= $totalBookings ?></div>
    </div>

    <div class="card">
        <i class="fas fa-dollar-sign card-icon"></i>
        <div class="card-title">Revenue</div>
        <div class="card-value">$<?= number_format($totalRevenue,2) ?></div>
    </div>

    <div class="card">
        <i class="fas fa-clock card-icon"></i>
        <div class="card-title">Latest Booking</div>
        <div class="card-value">
            <?= $totalBookings>0 ? date('M d',strtotime($reservations[0]['reservation_date'])) : '-' ?>
        </div>
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

<th>Nights</th>

<th>Total</th>

<th>Booked On</th>

</tr>

</thead>

<tbody>

<?php if(count($reservations)>0): ?>

<?php foreach($reservations as $row):

$in = new DateTime($row['check_in']);
$out = new DateTime($row['check_out']);

$nights = $out->diff($in)->days;

$total = $nights * $row['price'];

?>

<tr>

<td>#<?= $row['reservation_id'] ?></td>

<td>

<strong><?= htmlspecialchars($row['name']) ?></strong>

<br>

<small><?= htmlspecialchars($row['phone']) ?></small>

</td>

<td>

<?= htmlspecialchars($row['room_type']) ?>

<br>

<small>$<?= number_format($row['price'],2) ?>/Night</small>

</td>

<td>

<?= date("d M Y",strtotime($row['check_in'])) ?>

</td>

<td>

<?= date("d M Y",strtotime($row['check_out'])) ?>

</td>

<td>

<?= $nights ?>

</td>

<td>

<strong>$<?= number_format($total,2) ?></strong>

</td>

<td>

<?= date("d M Y H:i",strtotime($row['reservation_date'])) ?>

</td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="8" style="text-align:center;padding:40px;">

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