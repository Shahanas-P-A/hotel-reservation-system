<?php
require 'session.php';
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $customer_id = $_POST['customer_id'];
    $room_id = $_POST['room_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    if (strtotime($check_in) >= strtotime($check_out)) {

        $message = "<div class='alert alert-danger'>Check-out date must be after Check-in date.</div>";

    } else {

        try {

            $stmt = $pdo->prepare("
                INSERT INTO reservations
                (customer_id, room_id, check_in, check_out)
                VALUES (?,?,?,?)
            ");

            $stmt->execute([
                $customer_id,
                $room_id,
                $check_in,
                $check_out
            ]);

            $message = "<div class='alert alert-success'>Room Booked Successfully.</div>";

        } catch(PDOException $e){

            $message = "<div class='alert alert-danger'>".$e->getMessage()."</div>";

        }

    }

}

$customers = $pdo->query("
SELECT customer_id,name
FROM customers
ORDER BY name
")->fetchAll(PDO::FETCH_ASSOC);

$rooms = $pdo->query("
SELECT *
FROM available_rooms
ORDER BY price
")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Book Room</title>

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
Book a Room
</h1>

</div>

<?= $message ?>

<div class="form-container">

<form method="POST">

<div class="form-group">

<label>Customer</label>

<select
name="customer_id"
class="form-control"
required>

<option value="">
Select Customer
</option>

<?php foreach($customers as $c): ?>

<option value="<?= $c['customer_id'] ?>">

<?= htmlspecialchars($c['name']) ?>

</option>

<?php endforeach; ?>

</select>

</div>

<div class="form-group">

<label>Available Room</label>

<select
name="room_id"
class="form-control"
required>

<option value="">
Select Room
</option>

<?php foreach($rooms as $r): ?>

<option value="<?= $r['room_id'] ?>">

Room #<?= $r['room_id'] ?>

-

<?= htmlspecialchars($r['room_type']) ?>

($<?= number_format($r['price'],2) ?>)

</option>

<?php endforeach; ?>

</select>

</div>

<div class="form-group">

<label>Check In</label>

<input
type="date"
name="check_in"
class="form-control"
required>

</div>

<div class="form-group">

<label>Check Out</label>

<input
type="date"
name="check_out"
class="form-control"
required>

</div>

<button class="btn btn-primary btn-block">

Confirm Booking

</button>

</form>

</div>

</div>
<footer class="footer">
    <p>© 2026 LuxeStay MS | Hotel Reservation System</p>
</footer>
</body>

</html>