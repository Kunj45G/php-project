<?php
// Database connection
$conn = new mysqli('localhost', 'root', 'Kunj@2411', 'invoice_management');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data for the invoice to be edited
$id = $_GET['id'];
$invoice = $conn->query("SELECT * FROM invoices WHERE id = '$id'")->fetch_assoc();
$items = $conn->query("SELECT * FROM invoice_items WHERE invoice_id = '$id'");

// Handle Edit Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST['customer_name'];
    $mobile_no = $_POST['mobile_no'];
    $address = $_POST['address'];
    $gross_amount = $_POST['gross_amount'];
    $discount = $_POST['discount'];
    $shipping_charges = $_POST['shipping_charges'];
    $round_off = round($gross_amount - $discount + $shipping_charges);
    $total_amount = $gross_amount - $discount + $shipping_charges + $round_off;

    // Update the invoice details
    $conn->query("UPDATE invoices SET customer_name = '$customer_name', mobile_no = '$mobile_no', address = '$address',
                  gross_amount = '$gross_amount', discount = '$discount', shipping_charges = '$shipping_charges',
                  round_off = '$round_off', total_amount = '$total_amount' WHERE id = '$id'");

    // Remove existing invoice items before inserting updated ones
    $conn->query("DELETE FROM invoice_items WHERE invoice_id = '$id'");

    // Insert updated invoice items
    foreach ($_POST['product_name'] as $index => $product_name) {
        $qty = $_POST['qty'][$index];
        $rate = $_POST['rate'][$index];
        $amount = $qty * $rate;
        $conn->query("INSERT INTO invoice_items (invoice_id, product_name, qty, rate, amount)
                      VALUES ('$id', '$product_name', '$qty', '$rate', '$amount')");
    }

    echo "Invoice updated successfully.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Invoice</title>
    <style>
        /* Same CSS as in the initial form */
    </style>
    <script>
        /* Same JavaScript functions as in the initial form */
    </script>
</head>
<body>

<h2>Edit Invoice</h2>
<form method="POST" action="">
    Name: <input type="text" name="customer_name" value="<?php echo $invoice['customer_name']; ?>" required><br>
    Mobile No: <input type="text" name="mobile_no" value="<?php echo $invoice['mobile_no']; ?>" required><br>
    Address: <input type="text" name="address" value="<?php echo $invoice['address']; ?>" required><br>

    <table border="1">
        <tr>
            <th>Product Name</th>
            <th>Qty</th>
            <th>Rate</th>
            <th>Amount</th>
            <th>#</th>
        </tr>
        <?php $i = 0; while ($item = $items->fetch_assoc()) { ?>
        <tr>
            <td><input type="text" name="product_name[]" value="<?php echo $item['product_name']; ?>" required></td>
            <td><input type="number" name="qty[]" value="<?php echo $item['qty']; ?>" required oninput="calculateAmount(<?php echo $i; ?>)"></td>
            <td><input type="number" name="rate[]" value="<?php echo $item['rate']; ?>" required oninput="calculateAmount(<?php echo $i; ?>)"></td>
            <td><input type="text" name="amount[]" value="<?php echo $item['amount']; ?>" readonly></td>
            <td><button type="button" onclick="addRow()">+</button></td>
        </tr>
        <?php $i++; } ?>
    </table>

    Gross Amount: <input type="text" id="gross_amount" name="gross_amount" value="<?php echo $invoice['gross_amount']; ?>" readonly><br>
    Discount: <input type="number" id="discount" name="discount" value="<?php echo $invoice['discount']; ?>" oninput="calculateTotalAmount()"><br>
    Shipping Charges: <input type="number" id="shipping_charges" name="shipping_charges" value="<?php echo $invoice['shipping_charges']; ?>" oninput="calculateTotalAmount()"><br>
    Round Off: <input type="text" id="round_off" name="round_off" value="<?php echo $invoice['round_off']; ?>" readonly><br>
    Total Amount: <input type="text" id="total_amount" name="total_amount" value="<?php echo $invoice['total_amount']; ?>" readonly><br>

    <button type="submit">Save</button>
</form>

</body>
</html>
