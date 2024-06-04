<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
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
        .search-bar select,
        .search-bar button {
            margin-right: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .search-bar button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        .search-bar a button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn {
            padding: 5px 10px;
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
                <button type="submit">Cari</button>
            </form>
            <a href="insert.php"><button>Tambah Data</button></a>
        </div>
        <table>
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
                ini_set('display_errors', '1');
                ini_set('display_startup_errors', '1');
                error_reporting(E_ALL);

                require_once 'config_db.php';

                $db = new ConfigDB();
                $conn = $db->connect();

                // Proses delete jika ada parameter 'delete'
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
                        echo "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
                    }
                }

                // Kondisi untuk pencarian
                $searchCondition = "";
                if (isset($_GET['search'])) {
                    $search = $conn->real_escape_string($_GET['search']);
                    $searchCondition = "AND (a.name LIKE '%$search%' OR b.name LIKE '%$search%' OR c.name LIKE '%$search%')";
                }

                // Query untuk mengambil data produk beserta kategori dan supplier
                $query = "
                SELECT a.id, a.name, a.price, a.stock, a.created_at, b.name AS category_name, c.name AS supplier_name
                FROM products a 
                LEFT JOIN categories b ON a.id_category = b.id_category
                LEFT JOIN supplier c ON a.id_supplier = c.id_supplier
                WHERE a.deleted_at IS NULL
                $searchCondition
                ";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    // Loop melalui hasil query dan tampilkan data
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

                $db->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
