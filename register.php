<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Buat Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #fff;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: #fff;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.3);
        }
        .btn-primary {
            background: #2575fc;
            border: none;
        }
        .btn-primary:hover {
            background: #1a63cc;
        }
        .text-center a {
            color: #ffefba;
            text-decoration: none;
        }
        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container w-50">
        <h1 class="text-center mt-5">Buat Akun</h1>
        <form action="" method="post">
            <div class="form-group mb-3">
                <label for="nameInput">Nama</label>
                <input type="text" class="form-control" id="nameInput" name="name" placeholder="Masukkan Nama" required>
            </div>
            <div class="form-group mb-3">
                <label for="emailInput">Email</label>
                <input type="email" class="form-control" id="emailInput" name="email" placeholder="Masukkan Email" required>
            </div>
            <div class="form-group mb-3">
                <label for="passwordInput">Password</label>
                <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Masukkan Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Daftar</button>
        </form>
        <div class="text-center mt-3">
            <p>Sudah punya akun? <a href="login.php">Masuk sekarang</a></p>
        </div>
        <?php
            ini_set('display_errors', '1');
            ini_set('display_startup_errors', '1');
            error_reporting(E_ALL);

            require_once 'config_db.php';

            $db = new ConfigDB();
            $conn = $db->connect();

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $name = $_POST['name'];
                $email = $_POST['email'];
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $createdAt = date('Y-m-d H:i:s');

                // Pengecekan apakah email sudah terdaftar
                $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
                $checkStmt = $conn->prepare($checkEmailQuery);
                $checkStmt->bind_param("s", $email);
                $checkStmt->execute();
                $result = $checkStmt->get_result();

                if ($result->num_rows > 0) {
                    echo "<div class='alert alert-danger mt-3' role='alert'>Email sudah terdaftar</div>";
                } else {
                    // Melakukan pendaftaran jika email belum terdaftar
                    $stmt = $conn->prepare("INSERT INTO users (email, full_name, password, role, created_at) VALUES (?, ?, ?, 'admin', ?)");
                    $stmt->bind_param("ssss", $email, $name, $password, $createdAt);

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success mt-3' role='alert'>Data inserted successfully</div>";
                    } else {
                        echo "<div class='alert alert-danger mt-3' role='alert'>Error: " . $stmt->error . "</div>";
                    }

                    $stmt->close();
                }

                $checkStmt->close();
            }

            $conn->close();
        ?>
    </div>
</body>
</html>
