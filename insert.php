<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Insert Data</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php

    require_once 'vendor/autoload.php';

\Sentry\init([
  'dsn' => 'https://8fe5bdc8b306ed66f97ce9fbcb34beed@o4507456514949120.ingest.us.sentry.io/4507456516653056',
  // Specify a fixed sample rate
  'traces_sample_rate' => 1.0,
  // Set a sampling rate for profiling - this is relative to traces_sample_rate
  'profiles_sample_rate' => 1.0,
]);

        date_default_timezone_set('Asia/Jakarta');
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);

        require_once 'config_db.php';

        $db = new ConfigDB();
        $conn = $db->connect();

        // function checkNum($number) {
                //     if($number>1) {
                //       throw new Exception("Value must be 1 or below");
                //     }
                //     return true;
                //   }
                // function logError($error) {
                //     error_log($error, 3, 'error.log');
                //  }
                //  try {
                //     echo checkNum(2);    
                // } catch (Exception $e) {
                //     logError($e->getMessage());
                //     echo 'Error : '.$e->getMessage();
                // }
                    
                // echo 'Finish';
    ?>
    <div class="container">
        <h1 class="text-center mt-5">Insert Data</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="nameInput">Nama</label>
                <input type="text" class="form-control" id="nameInput" name="name" placeholder="Masukkan Nama Produk" required>
            </div>
            <div class="form-group">
                <label for="priceInput">Harga</label>
                <input type="number" class="form-control" id="priceInput" name="price" placeholder="Masukkan Harga" required>
            </div>
            <div class="form-group">
                <label for="categoryInput">Kategori</label>
                <?php
                    $categories = $conn->query("SELECT id_category, name FROM categories");
                    echo "<select class='form-control form-select' id='categoryInput' name='category'>";
                    echo "<option value=''>Pilih Category</option>";
                    while ($category = $categories->fetch_assoc()) {
                        echo "<option value='{$category['id_category']}'>{$category['name']}</option>";
                    }
                    echo "</select>";
                ?>
            </div>
            <div class="form-group">
                <label for="supplierInput">Supplier</label>
                <?php
                    $suppliers = $conn->query("SELECT id_supplier, name FROM supplier");
                    echo "<select class='form-control' id='supplierInput' name='id_supplier' required>";
                    echo "<option value=''>Pilih Supplier</option>";
                    while ($supplier = $suppliers->fetch_assoc()) {
                        echo "<option value='{$supplier['id_supplier']}'>{$supplier['name']}</option>";
                    }
                    echo "</select>";
                ?>
            </div>
            <div class="form-group">
                <label for="stockInput">Stok</label>
                <input type="number" class="form-control" id="stockInput" name="stock" placeholder="Masukkan Stok" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="index.php" class="btn btn-success">Kembali</a>
        </form>

        <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $name = Trim(Htmlentities($_POST['name']));
                $price = $_POST['price'];
                $category = $_POST['category'];
                $supplier = $_POST['id_supplier'];
                $stock = $_POST['stock'];
                $createdAt = date('Y-m-d H:i:s');

                try {
                    $conn->begin_transaction();
                    $query = "INSERT INTO products (name, price, id_category, id_supplier, stock, created_at) 
                              VALUES ('$name', '$price', '$category', '$supplier', '$stock', '$createdAt')";

                    if ($conn->query($query) === TRUE) {
                        $conn->commit();
                        echo "<div class='alert alert-success mt-3' role='alert'>Data inserted successfully</div>";
                    } else {
                        throw new Exception($conn->error);
                    }
                } catch (Exception $e) {
                    $conn->rollback();
                    echo "<div class='alert alert-danger mt-3' role='alert'>Transaction failed: " . $e->getMessage() . "</div>";
                }
            }
            $conn->close();
        ?>
    </div>
</body>
</html>
