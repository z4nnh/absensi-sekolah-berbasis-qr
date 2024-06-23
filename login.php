<?php
include 'includes/db.php';
session_start();

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($role == 'admin') {
        $sql = "SELECT * FROM tbl_admin WHERE username='$username'";
    } elseif ($role == 'siswa') {
        $sql = "SELECT * FROM tbl_siswa WHERE username='$username'";
    } elseif ($role == 'guru') {
        $sql = "SELECT * FROM tbl_guru WHERE username='$username'";
    } else {
        $error_message = "Peran tidak valid!";
    }

    if (empty($error_message)) {
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_role'] = $role;
                $_SESSION['user_id'] = $row['id'];
                if ($role == 'admin') {
                    header("Location: admin/dashboard_admin.php");
                } elseif ($role == 'siswa') {
                    header("Location: siswa/dashboard_siswa.php");
                } elseif ($role == 'guru') {
                    header("Location: guru/dashboard_guru.php");
                }
                exit();
            } else {
                $error_message = "Password salah!";
            }
        } else {
            $error_message = "Username tidak ditemukan!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMA Perintis 1 Sepatan</title>
    <link rel="icon" type="image/png" href="assets/img/logo_sekolah.png">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: url('assets/img/foto_sekolah.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5); /* optional: for darker overlay */
            backdrop-filter: blur(10px);
        }

        .card {
            border: 1px solid #0000;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-body">
                        <?php if (!empty($error_message)): ?>
                            <div class="error-message"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <form method="post" action="login.php">
                            <div class="text-center">
                                <h2>LOGIN</h2>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="admin">Admin</option>
                                    <option value="siswa">Siswa</option>
                                    <option value="guru">Guru</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
