<?php
require 'session.php';
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {

    if ($_POST['action'] == 'add_room') {

        $room_type = $_POST['room_type'];
        $price = $_POST['price'];

        try {
            $stmt = $pdo->prepare("INSERT INTO rooms(room_type,price) VALUES(?,?)");
            $stmt->execute([$room_type,$price]);

            $message="<div class='alert alert-success'>Room Added Successfully.</div>";

        } catch(PDOException $e){

            $message="<div class='alert alert-danger'>".$e->getMessage()."</div>";

        }

    }

    if($_POST['action']=="delete_room"){

        $stmt=$pdo->prepare("DELETE FROM rooms WHERE room_id=?");
        $stmt->execute([$_POST['room_id']]);

        $message="<div class='alert alert-success'>Room Deleted Successfully.</div>";

    }

}

$rooms=$pdo->query("SELECT * FROM rooms ORDER BY room_id ASC")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<title>Rooms</title>

<link rel="stylesheet" href="style.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

.grid{

display:grid;

grid-template-columns:350px 1fr;

gap:30px;

}

@media(max-width:900px){

.grid{

grid-template-columns:1fr;

}

}

</style>

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
        <i class="fas fa-bed"></i>
        Room Management
    </h1>

    
</div>

<?= $message ?>

<div class="grid">

<div class="form-container">

<h2 style="margin-bottom:20px;">Add Room</h2>

<form method="POST">

<input type="hidden" name="action" value="add_room">

<div class="form-group">

<label>Room Type</label>

<select name="room_type" class="form-control">

<option>Standard</option>

<option>Deluxe</option>

<option>Suite</option>

</select>

</div>

<div class="form-group">

<label>Price</label>

<input
type="number"
step="0.01"
name="price"
class="form-control"
required>

</div>

<button class="btn btn-primary btn-block">

Add Room

</button>

</form>

</div>

<div class="table-container">

<table>

<thead>

<tr>

<th>ID</th>

<th>Type</th>

<th>Price</th>

<th>Status</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php foreach($rooms as $room): ?>

<tr>

<td>#<?= $room['room_id'] ?></td>

<td><?= htmlspecialchars($room['room_type']) ?></td>

<td>$<?= number_format($room['price'],2) ?></td>

<td>

<?php if($room['status']=="Available"): ?>

<span class="badge badge-available">

Available

</span>

<?php else: ?>

<span class="badge badge-booked">

Booked

</span>

<?php endif; ?>

</td>

<td>

<form method="POST">

<input type="hidden" name="action" value="delete_room">

<input
type="hidden"
name="room_id"
value="<?= $room['room_id'] ?>">

<button class="btn btn-danger btn-sm">

Delete

</button>

</form>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</div>
<footer class="footer">
    <p>© 2026 LuxeStay MS | Hotel Reservation System</p>
</footer>
</body>

</html>