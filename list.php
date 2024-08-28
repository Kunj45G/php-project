<?php
// Database connection
$conn = new mysqli('localhost', 'root', 'Kunj@2411', 'invoice_management');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Delete Request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Delete the items associated with the invoice first
    $conn->query("DELETE FROM invoice_items WHERE invoice_id = '$delete_id'");

    // Then delete the invoice itself
    $conn->query("DELETE FROM invoices WHERE id = '$delete_id'");

    echo "Invoice deleted successfully.";
}

// Fetching Data
$result = $conn->query("SELECT * FROM invoices");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoice List</title>
</head>
<body>

<h2>Invoice List</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Mobile No</th>
        <th>Gross Amount</th>
        <th>Discount</th>
        <th>Shipping Charges</th>
        <th>Round Off</th>
        <th>Total Amount</th>
        <th>Action</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['customer_name']; ?></td>
        <td><?php echo $row['mobile_no']; ?></td>
        <td><?php echo $row['gross_amount']; ?></td>
        <td><?php echo $row['discount']; ?></td>
        <td><?php echo $row['shipping_charges']; ?></td>
        <td><?php echo $row['round_off']; ?></td>
        <td><?php echo $row['total_amount']; ?></td>
        <td>
            <a href="edit_invoice.php?id=<?php echo $row['id']; ?>">Edit</a>
            <a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this invoice?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>

</body>
</html>
