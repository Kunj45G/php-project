<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 800px;
            margin-top: 50px;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .form-control[readonly] {
            background-color: #e9ecef;
        }
        .btn-remove {
            background-color: #dc3545;
            color: #fff;
        }
        .btn-remove:hover {
            background-color: #c82333;
        }
    </style>
    <script>
        function calculateAmount(index) {
            var qty = document.getElementsByName('qty[]')[index].value;
            var rate = document.getElementsByName('rate[]')[index].value;
            var amount = qty * rate;
            document.getElementsByName('amount[]')[index].value = amount.toFixed(2);
            calculateGrossAmount();
        }

        function calculateGrossAmount() {
            var amounts = document.getElementsByName('amount[]');
            var grossAmount = 0;
            for (var i = 0; i < amounts.length; i++) {
                grossAmount += parseFloat(amounts[i].value);
            }
            document.getElementById('gross_amount').value = grossAmount.toFixed(2);
            calculateTotalAmount();
        }

        function calculateTotalAmount() {
            var grossAmount = parseFloat(document.getElementById('gross_amount').value);
            var discountPercentage = parseFloat(document.getElementById('discount').value);
            var shippingCharges = parseFloat(document.getElementById('shipping_charges').value);

            var discountAmount = (grossAmount * discountPercentage) / 100;
            var netAmount = grossAmount - discountAmount + shippingCharges;
            var roundOff = Math.round(netAmount);
            var totalAmount = roundOff;

            document.getElementById('round_off').value = (roundOff - netAmount).toFixed(2);
            document.getElementById('total_amount').value = totalAmount.toFixed(2);
        }

        function addRow() {
            var table = document.getElementById("product_table_body");
            var rowCount = table.rows.length;
            var row = table.insertRow(-1);

            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);
            var cell5 = row.insertCell(4);

            cell1.innerHTML = '<input type="text" name="product_name[]" class="form-control" required>';
            cell2.innerHTML = '<input type="number" name="qty[]" class="form-control" oninput="calculateAmount(' + rowCount + ')" required>';
            cell3.innerHTML = '<input type="number" name="rate[]" class="form-control" oninput="calculateAmount(' + rowCount + ')" required>';
            cell4.innerHTML = '<input type="text" name="amount[]" class="form-control" readonly>';
            cell5.innerHTML = '<button type="button" class="btn btn-remove" onclick="deleteRow(this)">X</button>';
        }

        function deleteRow(button) {
            var row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
            calculateGrossAmount();
        }
    </script>
</head>
<body>

<div class="container">
    <h2 class="mb-4">Invoice Management</h2>
    <form method="POST" action="save_invoice.php">
        <div class="mb-3">
            <label for="customer_name" class="form-label">Name</label>
            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
        </div>
        <div class="mb-3">
            <label for="mobile_no" class="form-label">Mobile No</label>
            <input type="text" class="form-control" id="mobile_no" name="mobile_no" required>
        </div>
        <div class="mb-4">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" id="address" name="address" required>
        </div>

        <h4>Products</h4>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Product Name</th>
                    <th>Qty</th>
                    <th>Rate</th>
                    <th>Amount</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody id="product_table_body">
                <tr>
                    <td><input type="text" name="product_name[]" class="form-control" required></td>
                    <td><input type="number" name="qty[]" class="form-control" oninput="calculateAmount(0)" required></td>
                    <td><input type="number" name="rate[]" class="form-control" oninput="calculateAmount(0)" required></td>
                    <td><input type="text" name="amount[]" class="form-control" readonly></td>
                    <td><button type="button" class="btn btn-success" onclick="addRow()">+</button></td>
                </tr>
            </tbody>
        </table>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="gross_amount" class="form-label">Gross Amount</label>
                <input type="text" class="form-control" id="gross_amount" name="gross_amount" readonly>
            </div>
            <div class="col-md-6">
                <label for="discount" class="form-label">Discount (%)</label>
                <input type="number" class="form-control" id="discount" name="discount" value="0" oninput="calculateTotalAmount()">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="shipping_charges" class="form-label">Shipping Charges</label>
                <input type="number" class="form-control" id="shipping_charges" name="shipping_charges" value="0" oninput="calculateTotalAmount()">
            </div>
            <div class="col-md-6">
                <label for="round_off" class="form-label">Round Off</label>
                <input type="text" class="form-control" id="round_off" name="round_off" readonly>
            </div>
        </div>

        <div class="mb-4">
            <label for="total_amount" class="form-label">Total Amount</label>
            <input type="text" class="form-control" id="total_amount" name="total_amount" readonly>
        </div>

        <button type="submit" class="btn btn-primary w-100">Save</button>
    </form>
</div>

<!-- Bootstrap JS (optional but recommended for better functionality) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
