<?php
require 'session.php';
require 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {

    if ($_POST['action'] === 'add_customer') {

        $name = trim($_POST['name']);
        $phone = trim($_POST['phone']);
        $email = trim($_POST['email']);

        if ($name && $phone && $email) {

            try {

                $stmt = $pdo->prepare("INSERT INTO customers(name,phone,email) VALUES(?,?,?)");
                $stmt->execute([$name,$phone,$email]);

                $message="<div class='alert alert-success'>Customer added successfully.</div>";

            } catch(PDOException $e){

                $message="<div class='alert alert-danger'>".$e->getMessage()."</div>";

            }

        }

    }

    if($_POST['action']=="delete_customer"){

        $stmt=$pdo->prepare("DELETE FROM customers WHERE customer_id=?");
        $stmt->execute([$_POST['customer_id']]);

        $message="<div class='alert alert-success'>Customer deleted.</div>";

    }

}

$customers=$pdo->query("SELECT * FROM customers ORDER BY customer_id DESC")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Customers</title>

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
        <i class="fas fa-users"></i>
        Customer Management
    </h1>

    <div class="search-container">
        <input
            type="text"
            id="searchCustomer"
            class="search-input"
            placeholder="🔍 Search customer...">
    </div>
</div>

<?= $message ?>

<div class="grid">

<div class="form-container">

<h2 style="margin-bottom:20px;">Add Customer</h2>

<form method="POST">

<input type="hidden" name="action" value="add_customer">

<div class="form-group">

<label>Name</label>

<input
type="text"
name="name"
class="form-control"
required>

</div>

<div class="form-group">

<label>Phone</label>

<input
type="text"
name="phone"
class="form-control"
required>

</div>

<div class="form-group">

<label>Email</label>

<input
type="email"
name="email"
class="form-control"
required>

</div>

<button class="btn btn-primary btn-block">

Add Customer

</button>

</form>

</div>


<div class="table-container">

<table>

<thead>

<tr>

<th>ID</th>

<th>Name</th>

<th>Phone</th>

<th>Email</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php foreach($customers as $c): ?>

<tr>

<td>#<?= $c['customer_id'] ?></td>

<td><?= htmlspecialchars($c['name']) ?></td>

<td><?= htmlspecialchars($c['phone']) ?></td>

<td><?= htmlspecialchars($c['email']) ?></td>

<td>

<form method="POST">

<input type="hidden" name="action" value="delete_customer">

<input type="hidden" name="customer_id" value="<?= $c['customer_id'] ?>">

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
<script>
document.getElementById("searchCustomer").addEventListener("keyup", function () {
    let value = this.value.toLowerCase();

    document.querySelectorAll("tbody tr").forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(value)
            ? ""
            : "none";
    });
});
</script>
</body>

</html>