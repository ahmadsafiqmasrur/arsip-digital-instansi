<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Arsip Akta Nikah KUA Jetis</title>
    <!-- Memuat Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=EB+Garamond:ital,wght@0,400..800;1,400..800&family=El+Messiri:wght@400..700&family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&family=Space+Grotesk:wght@300..700&family=TikTok+Sans:opsz,wght@12..36,300..900&family=Zalando+Sans+SemiExpanded:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: #0c7a5c;
            color: #ffffff;
            height: 100vh;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        /* Konten sebelah kiri (Judul dan Ilustrasi) */
        .left-content {
            padding: 60px 80px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            height: 100%;
            z-index: 2;
            flex: 1.2;
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
            max-width: 350px;
            height: auto;
            display: block;
            min-height: 100px;
            min-width: 150px;
            background-color: transparent;
        }

        /* Form login di sebelah kanan */
        .right-content {
            margin-right: 120px;
            z-index: 2;
            flex: 0.8;
            max-width: 450px;
            width: 100%;
        }

        .login-card {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            color: #333333;
        }

        .login-card h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0c7a5c;
            margin-bottom: 25px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            color: #333333;
        }

        .form-control {
            width: 100%;
            padding: 14px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #f9f9f9;
            font-size: 0.95rem;
            color: #333333;
            box-sizing: border-box;
            outline: none;
            transition: all 0.2s ease;
            font-family: 'DM Sans', sans-serif;
        }

        .form-control:focus {
            border-color: #0c7a5c;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(12, 122, 92, 0.15);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: #0c7a5c;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease;
            margin-top: 10px;
        }

        .btn-submit:hover {
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

        /* Responsivitas untuk layar HP */
        @media (max-width: 992px) {
            body {
                flex-direction: column;
                height: auto;
                overflow-y: auto;
                align-items: center;
                justify-content: flex-start;
                padding-bottom: 50px;
            }

            .left-content {
                padding: 40px 30px;
                width: 100%;
                height: auto;
                align-items: center;
                text-align: center;
                flex: none;
            }

            .text-wrapper h1 {
                font-size: 2.5rem;
            }

            .text-wrapper h2 {
                font-size: 1.5rem;
            }

            .illustration-wrapper img {
                max-width: 80%;
                min-width: 0;
                margin: 0 auto;
            }

            .right-content {
                margin-right: 0;
                padding: 0 30px;
                width: 100%;
                max-width: 450px;
                flex: none;
                margin-top: 30px;
            }
        }
    </style>
</head>

<body>
    <!-- Konten Utama (Kiri) -->
    <div class="left-content">
        <div class="logo-wrapper">
            <img src="<?= base_url('assets/images/kemenag.png') ?>" alt="Logo">
        </div>

        <div class="text-wrapper">
            <h1>Sistem Arsip<br>Akta Nikah KUA Jetis</h1>
            <h2>Bantul, Yogyakarta</h2>
        </div>

        <div class="illustration-wrapper">
            <img src="<?= base_url('assets/images/landingvector.png') ?>" alt="Ilustrasi Arsip">
        </div>
    </div>

    <!-- Form Login Terpadu (Kanan) -->
    <div class="right-content">
        <div class="login-card">
            <h3>Login Sistem Arsip</h3>
            
            <!-- Menampilkan pesan error jika login gagal -->
            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($this->session->flashdata('error')); ?></div>
            <?php endif; ?>
            
            <form action="<?= site_url('auth/do_login') ?>" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Masukkan Username" required autocomplete="username">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan Password" required autocomplete="current-password">
                </div>
                
                <button type="submit" class="btn-submit">Login</button>
            </form>
        </div>
    </div>
</body>

</html>
