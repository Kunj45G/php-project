<?php
// Database connection
$conn = new mysqli('localhost', 'root', 'Kunj@2411', 'invoice_management');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Save Invoice Data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST['customer_name'];
    $mobile_no = $_POST['mobile_no'];
    $address = $_POST['address'];
    $gross_amount = $_POST['gross_amount'];
    $discount_percentage = $_POST['discount'];
    $shipping_charges = $_POST['shipping_charges'];

    // Calculate Discount, Round Off and Total Amount
    $discount_amount = ($gross_amount * $discount_percentage) / 100;
    $net_amount = $gross_amount - $discount_amount + $shipping_charges;
    $round_off = round($net_amount);
    $total_amount = $round_off;

    // Save Invoice
    $conn->query("INSERT INTO invoices (customer_name, mobile_no, address, gross_amount, discount, shipping_charges, round_off, total_amount)
                  VALUES ('$customer_name', '$mobile_no', '$address', '$gross_amount', '$discount_percentage', '$shipping_charges', '$round_off', '$total_amount')");

    $invoice_id = $conn->insert_id;

    // Save Invoice Items
    foreach ($_POST['product_name'] as $index => $product_name) {
        $qty = $_POST['qty'][$index];
        $rate = $_POST['rate'][$index];
        $amount = $qty * $rate;

        $conn->query("INSERT INTO invoice_items (invoice_id, product_name, qty, rate, amount)
                      VALUES ('$invoice_id', '$product_name', '$qty', '$rate', '$amount')");
    }

    echo "Invoice saved successfully.";
}
?>
