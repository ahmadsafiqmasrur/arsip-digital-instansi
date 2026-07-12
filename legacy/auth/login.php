<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Petugas - Sistem Arsip Akta Nikah KUA Jetis</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background-color: #fafafa; 
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-title {
            font-size: 2rem;
            font-weight: 500;
            color: #000000;
            margin-bottom: 30px;
            text-align: center;
        }

        .login-card {
            background-color: #ffffff;
            width: 100%;
            max-width: 500px;
            padding: 40px;
            border-radius: 16px;
            border: 1px solid #e0e0e0;
            box-sizing: border-box;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-size: 1.1rem;
            color: #000000;
            font-weight: 400;
        }

        .form-control {
            width: 100%;
            padding: 16px;
            border: none;
            border-radius: 8px;
            background-color: #f2f2f2; 
            font-size: 0.95rem;
            color: #333333;
            box-sizing: border-box;
            outline: none;
            font-family: 'Inter', sans-serif;
        }

        .form-control::placeholder {
            color: #9e9e9e;
        }

        .form-control:focus {
            box-shadow: 0 0 0 2px rgba(12, 122, 92, 0.2);
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background-color: #0c7a5c; 
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.2s ease;
        }

        .btn-login:hover {
            background-color: #09634a;
        }

        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
            text-align: center;
        }

        .alert-danger {
            background-color: #ffe5e5;
            color: #d63031;
            border: 1px solid #ffcccc;
        }

       
        @media (max-width: 600px) {
            .login-card {
                padding: 30px 20px;
                border: none;
                border-radius: 0;
                background-color: transparent;
            }
            body {
                background-color: #ffffff;
            }
        }
    </style>
</head>
<body>
    <h2 class="login-title">Masuk sebagai petugas</h2>
    
    <div class="login-card">
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <form action="../proses/auth.php?action=login" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            
            <button type="submit" class="btn-login">Login</button>
        </form>
    </div>
</body>
</html>
