<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Alat</title>
    <!-- Import Google Font Fredoka -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Fredoka', sans-serif;
        }

        body {
            background-color: #f0f9ff;
            color: #0c4a6e;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .navbar {
            background-color: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 5%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.25rem;
            font-weight: 700;
            color: #0284c7;
        }

        .btn-login {
            background-color: #38bdf8;
            color: #ffffff;
            text-decoration: none;
            padding: 0.6rem 1.4rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(56, 189, 248, 0.3);
        }

        .btn-login:hover {
            background-color: #0284c7;
            transform: translateY(-2px);
        }

        /* Container Utama */
        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 3rem 1.5rem;
            width: 100%;
        }

        /* Hero Section */
        .hero {
            text-align: center;
            padding: 3rem 1rem;
            background: linear-gradient(135deg, #ffffff 0%, #e0f2fe 100%);
            border-radius: 24px;
            border: 1px solid #bae6fd;
            box-shadow: 0 10px 25px rgba(2, 132, 199, 0.05);
            margin-bottom: 4rem;
        }

        .hero-tag {
            background-color: #e0f2fe;
            color: #0284c7;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 1px;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .hero h1 {
            font-size: 2.8rem;
            color: #0369a1;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.1rem;
            color: #334155;
            max-width: 650px;
            margin: 0 auto 2rem auto;
            line-height: 1.6;
        }

        .btn-start {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background-color: #0284c7;
            color: #ffffff;
            text-decoration: none;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-size: 1.05rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(2, 132, 199, 0.3);
        }

        .btn-start:hover {
            background-color: #0369a1;
            transform: translateY(-2px);
        }

        /* Fitur Section */
        .features-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .features-header h2 {
            font-size: 2rem;
            color: #0369a1;
            margin-bottom: 0.5rem;
        }

        .features-header p {
            color: #64748b;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .feature-card {
            background: #ffffff;
            padding: 2rem;
            border-radius: 20px;
            border: 1px solid #f0f9ff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(2, 132, 199, 0.1);
            border-color: #bae6fd;
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: inline-block;
            background-color: #f0f9ff;
            padding: 12px;
            border-radius: 16px;
        }

        .feature-card h3 {
            font-size: 1.3rem;
            color: #0369a1;
            margin-bottom: 0.5rem;
        }

        .feature-card p {
            color: #475569;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        /* Footer */
        footer {
            margin-top: auto;
            text-align: center;
            padding: 1.5rem;
            color: #64748b;
            font-size: 0.9rem;
            border-top: 1px solid #e0f2fe;
            background-color: #ffffff;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="brand">
            <span>🧰</span> Peminjaman Alat
        </div>
        <a href="login.php" class="btn-login">Login</a>
    </nav>

    <!-- Content Container -->
    <div class="container">
        
        <!-- Hero Section -->
        <section class="hero">
            <span class="hero-tag">SISTEM INFORMASI</span>
            <h1>Peminjaman Alat Jadi Lebih Mudah</h1>
            <p>Aplikasi Peminjaman Alat merupakan sistem informasi yang membantu proses pengelolaan alat, peminjaman, dan pengembalian secara lebih mudah, teratur, dan terdokumentasi.</p>
            <a href="login.php" class="btn-start">Mulai Sekarang &rarr;</a>
        </section>

        <!-- Features Section -->
        <section class="features">
            <div class="features-header">
                <h2>Fitur Aplikasi</h2>
                <p>Beberapa fitur utama yang tersedia dalam aplikasi</p>
            </div>

            <div class="features-grid">
                <!-- Card 1 -->
                <div class="feature-card">
                    <div class="feature-icon">📦</div>
                    <h3>Data Alat</h3>
                    <p>Mengelola data alat, stok, kategori, dan kondisi alat secara terstruktur dan rinci.</p>
                </div>

                <!-- Card 2 -->
                <div class="feature-card">
                    <div class="feature-icon">📝</div>
                    <h3>Peminjaman</h3>
                    <p>Memudahkan pengguna dalam mengajukan dan mengontrol proses peminjaman alat secara online.</p>
                </div>

                <!-- Card 3 -->
                <div class="feature-card">
                    <div class="feature-icon">↪️</div>
                    <h3>Pengembalian</h3>
                    <p>Membantu proses pencatatan pengembalian alat serta denda dan kondisi barang pasca guna.</p>
                </div>
            </div>
        </section>

    </div>

    <!-- Footer -->
    <footer>
        &copy; 2027 Aplikasi Peminjaman Alat - UKK RPL
    </footer>

</body>
</html>