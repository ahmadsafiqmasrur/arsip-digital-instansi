<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Arsip Akta Nikah KUA Jetis</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #0c7a5c;
            color: #ffffff;
            height: 100vh;
            display: flex;
            position: relative;
            overflow: hidden;
        }

        .left-content {
            padding: 60px 80px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            height: 100%;
            z-index: 2;
        }

        .logo-wrapper {
            margin-bottom: 30px;
        }

        .logo-wrapper img {
            width: 50px;
            height: 50px;
            background-color: transparent;
            display: block;
            object-fit: contain;
        }

        .text-wrapper {
            margin-bottom: 40px;
        }

        .text-wrapper h1 {
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 15px;
            letter-spacing: -0.5px;
        }

        .text-wrapper h2 {
            font-size: 2rem;
            font-weight: 400;
            color: #e0e0e0;
        }

        .illustration-wrapper {
            flex-grow: 1;
            display: flex;
            align-items: flex-start;
        }

        .illustration-wrapper img {
            max-width: 500px;
            height: auto;
            display: block;
            /* Placeholder styling in case image is broken */
            min-height: 300px;
            min-width: 400px;
            background-color: transparent;
        }

        .right-content {
            position: absolute;
            bottom: 60px;
            right: 80px;
            z-index: 2;
        }

        .btn-login {
            background-color: #007bff;
            /* Bright blue button */
            color: #ffffff;
            padding: 16px 40px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-login:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        }

        .btn-login-koor {
            background-color: #ffffff;
            color: #0c7a5c;
            padding: 16px 40px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            display: inline-block;
            margin-right: 15px;
        }

        .btn-login-koor:hover {
            background-color: #f0f0f0;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 255, 255, 0.3);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            body {
                overflow-y: auto;
            }

            .left-content {
                padding: 40px 30px;
                width: 100%;
            }

            .text-wrapper h1 {
                font-size: 2.5rem;
            }

            .text-wrapper h2 {
                font-size: 1.5rem;
            }

            .illustration-wrapper img {
                max-width: 100%;
                min-width: 0;
            }

            .right-content {
                position: relative;
                bottom: auto;
                right: auto;
                padding: 0 30px 40px 30px;
                text-align: center;
            }

            .btn-login {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="left-content">
        <div class="logo-wrapper">

            <img src="kemenag.png" alt="Logo">
        </div>

        <div class="text-wrapper">
            <h1>Sistem Arsip<br>Akta Nikah KUA Jetis</h1>
            <h2>Bantul, Yogyakarta</h2>
        </div>


        <div class="illustration-wrapper">

            <img src="landing.png" alt="Ilustrasi Arsip"
                onerror="this.style.backgroundColor='rgba(255,255,255,0.1)'; this.style.border='2px dashed rgba(255,255,255,0.3)'; this.style.borderRadius='6px';">
        </div>
    </div>


    <div class="right-content"
        style="display: flex; flex-direction: row; gap: 15px; flex-wrap: wrap; justify-content: center;">
        <a href="auth/login_koordinator.php" class="btn-login-koor" style="margin-right: 0;">Login Koordinator</a>
        <a href="auth/login.php" class="btn-login">Login Petugas</a>
    </div>
</body>

</html>