<?php
session_start();
require_once 'config_db.php';

$db = new ConfigDB();
$conn = $db->connect();

// Handle new transaction submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $buyer_name = $_POST['buyer_name'];
    $products = $_POST['products'];
    $total_amount = 0;

    // Insert transaction into database
    if (!empty($buyer_name) && !empty($products)) {
        $conn->begin_transaction();
        try {
            foreach ($products as $index => $product) {
                $product_id = (int)$product['product_id'];
                $quantity = (int)$product['quantity'];
                
                if ($product_id > 0 && $quantity > 0) {
                    // Get product price and stock
                    $stmt = $conn->prepare("SELECT price, stock FROM products WHERE id = ?");
                    $stmt->bind_param("i", $product_id);
                    $stmt->execute();
                    $stmt->bind_result($price, $stock);
                    $stmt->fetch();
                    $stmt->close();
                    
                    if ($price !== null) {
                        if ($stock >= $quantity) {
                            // Calculate total amount
                            $total_amount += $price * $quantity;
                            
                            // Insert transaction
                            $stmt = $conn->prepare("INSERT INTO transactions (product_id, quantity, buyer_name) VALUES (?, ?, ?)");
                            $stmt->bind_param("iis", $product_id, $quantity, $buyer_name);
                            if ($stmt->execute()) {
                                // Update product stock
                                $stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                                $stmt->bind_param("ii", $quantity, $product_id);
                                if (!$stmt->execute()) {
                                    throw new Exception("Gagal memperbarui stok: " . $stmt->error);
                                }
                            } else {
                                throw new Exception("Gagal menambahkan transaksi: " . $stmt->error);
                            }
                        } else {
                            throw new Exception("Stok untuk produk ini tidak mencukupi!");
                        }
                    } else {
                        throw new Exception("Produk tidak ditemukan!");
                    }
                } else {
                    throw new Exception("Produk dan jumlah harus valid!");
                }
            }
            $conn->commit();
            $_SESSION['message'] = "Transaksi berhasil ditambahkan! Total Pembelian: Rp" . number_format($total_amount, 2, ',', '.');
            header("Location: transaksi.php");
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            $_SESSION['message'] = $e->getMessage();
            header("Location: transaksi.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "Nama pembeli dan produk harus valid!";
        header("Location: transaksi.php");
        exit();
    }
}

// Fetch products for the form
$product_query = "SELECT id, name, stock, price FROM products WHERE deleted_at IS NULL";
$product_result = $conn->query($product_query);
$products = [];
while ($row = $product_result->fetch_assoc()) {
    $products[] = $row;
}

// Fetch transactions to display
$transaction_query = "SELECT t.id, t.product_id, t.quantity, t.buyer_name, t.created_at, p.name AS product_name, (t.quantity * p.price) AS total_price 
                      FROM transactions t 
                      JOIN products p ON t.product_id = p.id";
$transaction_result = $conn->query($transaction_query);
$transactions = [];
while ($row = $transaction_result->fetch_assoc()) {
    $transactions[] = $row;
}

// Fetch total shopping
$total_shopping_query = "SELECT SUM(t.quantity * p.price) AS total_shopping FROM transactions t JOIN products p ON t.product_id = p.id";
$total_shopping_result = $conn->query($total_shopping_query);
$total_shopping = 0;
if ($row = $total_shopping_result->fetch_assoc()) {
    $total_shopping = $row['total_shopping'];
}

$conn->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
    </style>
    <script>
        function addProductRow() {
            const productRow = document.querySelector('.product-row');
            const newRow = productRow.cloneNode(true);
            newRow.querySelectorAll('select, input').forEach(input => input.name = input.name.replace(/\[\d+\]/, '[' + document.querySelectorAll('.product-row').length + ']'));
            document.getElementById('products-container').appendChild(newRow);
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Transaksi</h1>
        <div class="btn-container">
            <a href="index.php" class="btn btn-primary">Kembali ke Daftar Produk</a>
        </div>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
        <?php endif; ?>

        <form action="transaksi.php" method="post" class="mb-3">
            <div class="mb-3">
                <label for="buyer_name" class="form-label">Nama Pembeli</label>
                <input type="text" name="buyer_name" id="buyer_name" class="form-control" required>
            </div>
            <div id="products-container">
                <div class="product-row">
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Produk</label>
                        <select name="products[0][product_id]" id="product_id" class="form-select" required>
                            <?php foreach ($products as $product): ?>
                                <option value="<?php echo $product['id']; ?>">
                                    <?php echo $product['name']; ?> (Stok: <?php echo $product['stock']; ?>, Harga: Rp<?php echo number_format($product['price'], 2, ',', '.'); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Jumlah</label>
                        <input type="number" name="products[0][quantity]" id="quantity" class="form-control" required min="1">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Tambah Transaksi</button>
        </form>

        <h2>Daftar Transaksi</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Nama Pembeli</th>
                    <th>Tanggal Transaksi</th>
                    <th>Total Harga (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($transactions) > 0): ?>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?php echo $transaction['id']; ?></td>
                            <td><?php echo $transaction['product_name']; ?></td>
                            <td><?php echo $transaction['quantity']; ?></td>
                            <td><?php echo $transaction['buyer_name']; ?></td>
                            <td><?php echo $transaction['created_at']; ?></td>
                            <td><?php echo number_format($transaction['total_price'], 2, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No Transactions Found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h2>Total Pembelanjaan</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Total Pembelanjaan (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo number_format($total_shopping, 2, ',', '.'); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
