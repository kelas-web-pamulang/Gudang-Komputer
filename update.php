<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Data</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php
        require_once 'config_db.php';

        $db = new ConfigDB();
        $conn = $db->connect();

        $productId = $_GET['id'];
        $result = $db->select("products", ['AND id=' => $productId]);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $price = $_POST['price'];
            $category = $_POST['id_category'];
            $supplier = $_POST['id_supplier'];
            $addStock = $_POST['add_stock'];

            $currentStock = $result[0]['stock'];
            $newStock = $currentStock + $addStock;

            $conn->begin_transaction();
            try {
                $query = "UPDATE products SET 
                            name = '$name', 
                            price = '$price', 
                            id_category = '$category', 
                            id_supplier = '$supplier', 
                            stock = '$newStock' 
                          WHERE id = $productId";

                if ($conn->query($query) === TRUE) {
                    $conn->commit();
                    echo "<div class='alert alert-success mt-3' role='alert'>Data updated successfully</div>";
                } else {
                    throw new Exception($conn->error);
                }
            } catch (Exception $e) {
                $conn->rollback();
                echo "<div class='alert alert-danger mt-3' role='alert'>Transaction failed: " . $e->getMessage() . "</div>";
            }

            $result = $db->select("products", ['AND id=' => $productId]);
        } 

    ?>
    <div class="container">
        <h1 class="text-center mt-5">Ubah Data</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="nameInput">Name</label>
                <input type="text" class="form-control" id="nameInput" name="name" placeholder="Enter Name" required value="<?php echo $result[0]['name'] ?>">
            </div>
            <div class="form-group">
                <label for="priceInput">Price</label>
                <input type="number" class="form-control" id="priceInput" name="price" placeholder="Enter Price" required value="<?php echo $result[0]['price'] ?>">
            </div>
            <div class="form-group">
                <label for="categorySelect">Category</label>
                <select class="form-control" id="categorySelect" name="id_category" required>
                    <?php
                        $categories = $conn->query("SELECT id_category, name FROM categories");
                        while ($category = $categories->fetch_assoc()) {
                            $selected = $category['id_category'] == $result[0]['id_category'] ? 'selected' : '';
                            echo "<option value='{$category['id_category']}' $selected>{$category['name']}</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="supplierSelect">Supplier</label>
                <select class="form-control" id="supplierSelect" name="id_supplier" required>
                    <?php
                        $suppliers = $conn->query("SELECT id_supplier, name FROM supplier");
                        while ($supplier = $suppliers->fetch_assoc()) {
                            $selected = $supplier['id_supplier'] == $result[0]['id_supplier'] ? 'selected' : '';
                            echo "<option value='{$supplier['id_supplier']}' $selected>{$supplier['name']}</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="stockInput">Stock</label>
                <input type="number" class="form-control" id="stockInput" name="current_stock" placeholder="Current Stock" readonly value="<?php echo $result[0]['stock'] ?>">
            </div>
            <div class="form-group">
                <label for="addStockInput">Tambah Stock</label>
                <input type="number" class="form-control" id="addStockInput" name="add_stock" placeholder="0" required>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="index.php" class="btn btn-info">Kembali</a>
        </form>

        <?php
            $conn->close();
        ?>
    </div>
</body>
</html>
