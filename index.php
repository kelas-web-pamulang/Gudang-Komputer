<?php
require_once 'vendor/autoload.php';
\Sentry\init([
    'dsn' => 'https://8fe5bdc8b306ed66f97ce9fbcb34beed@o4507456514949120.ingest.us.sentry.io/4507456516653056',
    // Specify a fixed sample rate
    'traces_sample_rate' => 1.0,
    // Set a sampling rate for profiling - this is relative to traces_sample_rate
    'profiles_sample_rate' => 1.0,
  ]);
session_start();

// Periksa apakah pengguna sudah login atau belum
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('https://4kwallpapers.com/images/walls/thumbs_3t/16713.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9); /* Added transparency to show the background */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-weight: bold;
        }
        .search-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .search-bar form {
            display: flex;
            align-items: center;
        }
        .search-bar input,
        .search-bar select {
            margin-right: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            flex: 1;
        }
        .search-bar button {
            padding: 10px 20px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        .btn-search {
            background-color: #28a745;
            color: white;
        }
        .btn-add {
            background-color: #007bff;
            color: white;
        }
        .btn-transaksi {
            background-color: #ffc107;
            color: white;
        }
        .btn-logout {
            background-color: #dc3545;
            color: white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            padding: 15px;
            text-align: left;
        }
        table th {
            background-color: #343a40;
            color: #ffffff;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-info {
            background-color: #17a2b8;
            color: white;
        }
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gudang Komputer</h1>
        <div class="search-bar">
            <form action="" method="get">
                <input type="text" placeholder="Cari Data" name="search">
                <select name="search_by">
                    <option value="">Cari Semua</option>
                    <option value="name">Supplier</option>
                    <option value="category">Category</option>
                </select>
                <button type="submit" class="btn btn-search">Cari</button>
            </form>
            <a href="insert.php"><button class="btn btn-add">Tambah Data</button></a>
            <a href="transaksi.php"><button class="btn btn-transaksi">Transaksi</button></a>
            <a href="logout.php"><button class="btn btn-logout">Logout</button></a>
        </div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Supplier</th>
                    <th>Stock</th>
                    <th>Tgl. Buat</th>
                    <th colspan="2">Pilihan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // ini_set('display_errors', '0');
                // ini_set('display_startup_errors', '1');
                // error_reporting(E_ALL);

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
                


                if (isset($_GET['delete'])) {
                    $deleteId = (int)$_GET['delete'];
                    $conn->begin_transaction();
                    try {
                        $query = "UPDATE products SET deleted_at = '".date('Y-m-d H:i:s')."' WHERE id = $deleteId";
                        if ($conn->query($query) === TRUE) {
                            $conn->commit();
                            echo "<div class='alert alert-success'>Data berhasil dihapus.</div>";
                        } else {
                            throw new Exception("Error: " . $conn->error);
                        }
                    } catch (Exception $e) {
                        $conn->rollback();
                        echo "<div class='alert alert-danger'>Gagal menghapus data: " . $e->getMessage() . "</div>";
                    }
                }

                $searchCondition = "";
                if (isset($_GET['search'])) {
                    $search = $conn->real_escape_string($_GET['search']);
                    $searchCondition = "AND (a.name LIKE '%$search%' OR b.name LIKE '%$search%' OR c.name LIKE '%$search%')";
                }

                $query = "
                SELECT a.id, a.name, a.price, a.stock, a.created_at, b.name AS category_name, c.name AS supplier_name
                FROM products a 
                LEFT JOIN categories b ON a.id_category = b.id_category
                LEFT JOIN supplier c ON a.id_supplier = c.id_supplier
                WHERE a.deleted_at IS NULL
                ORDER BY a.id DESC
                $searchCondition
                ";
                $result = $conn->query($query);

                if ($result) {
                    if ($result->num_rows > 0) {
                        foreach ($result as $key => $row) {
                            echo "<tr>";
                            echo "<td>".($key + 1)."</td>";
                            echo "<td>".$row['name']."</td>";
                            echo "<td>".$row['price']."</td>";
                            echo "<td>".$row['category_name']."</td>";
                            echo "<td>".$row['supplier_name']."</td>";
                            echo "<td>".$row['stock']."</td>";
                            echo "<td>".$row['created_at']."</td>";
                            echo "<td><a class='btn btn-info' href='update.php?id=".$row['id']."'>Update</a></td>";
                            echo "<td><a class='btn btn-danger' href='index.php?delete=".$row['id']."' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>Delete</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center'>No Data</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' class='text-center'>Error: " . $conn->error . "</td></tr>";
                }

                $db->close();
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+z2gtmGIKwUIA6ztJ7Fzo2+fQutL4" crossorigin="anonymous"></script>
</body>
</html>
