<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
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
        <h1 class="text-center mt-5">Masuk</h1>
        <form action="" method="post">
            <div class="form-group mb-3">
                <label for="emailInput">Email</label>
                <input type="email" class="form-control" id="emailInput" name="email" placeholder="Masukkan Email" required>
            </div>
            <div class="form-group mb-3">
                <label for="passwordInput">Password</label>
                <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Masukkan Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-3">Masuk</button>
        </form>
        <div class="text-center mt-3">
            <p>Belum punya akun? <a href="register.php">Daftar sekarang</a></p>
        </div>
        <?php
            require_once 'vendor/autoload.php';

\Sentry\init([
  'dsn' => 'https://8fe5bdc8b306ed66f97ce9fbcb34beed@o4507456514949120.ingest.us.sentry.io/4507456516653056',
  // Specify a fixed sample rate
  'traces_sample_rate' => 1.0,
  // Set a sampling rate for profiling - this is relative to traces_sample_rate
  'profiles_sample_rate' => 1.0,
]);
            ini_set('display_errors', '1');
            ini_set('display_startup_errors', '1');
            error_reporting(E_ALL);

            session_start();

            if (isset($_SESSION['login'])) {
                header('Location: index.php');
                exit();
            }

            require_once 'config_db.php';

            try {
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

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $email = $_POST['email'];
                    $password = $_POST['password'];

                    $query = "SELECT id, email, full_name, password FROM users WHERE email = ?";
                    $stmt = $conn->prepare($query);
                    if (!$stmt) {
                        throw new Exception("Persiapan pernyataan gagal: " . $conn->error);
                    }
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $user = $result->fetch_assoc();
                        if (password_verify($password, $user['password'])) {
                            $_SESSION['login'] = true;
                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['user_email'] = $user['email'];
                            $_SESSION['user_name'] = $user['full_name'];
                            header('Location: index.php');
                            exit();
                        } else {
                            echo "<div class='alert alert-danger mt-3' role='alert'>Email atau Password salah</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger mt-3' role='alert'>Email atau Password salah</div>";
                    }
                    $stmt->close();
                }

                $conn->close();
            } catch (Exception $e) {
                echo "<div class='alert alert-danger mt-3' role='alert'>Terjadi kesalahan: " . $e->getMessage() . "</div>";
            }
        ?>
    </div>
</body>
</html>
