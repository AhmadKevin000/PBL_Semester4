<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre School Division - Bimbel Mantap</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --secondary: #f43f5e;
            --bg: #0f172a;
            --text: #f8fafc;
        }
        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            margin: 0;
            line-height: 1.6;
        }
        .hero {
            height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            text-align: center;
            padding: 20px;
        }
        h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(to right, #818cf8, #f472b6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .content {
            max-width: 800px;
            margin: -50px auto 50px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }
    </style>
</head>
<body>
    <div class="hero">
        <div>
            <h1>Pre School Division</h1>
            <p style="font-size: 1.2rem; opacity: 0.8;">Membangun Fondasi Kecerdasan Sejak Dini</p>
        </div>
    </div>

    <div class="content">
        <h2>Selamat Datang di Program Pre School</h2>
        <p>Program Pre School kami dirancang khusus untuk anak-anak usia dini (3-5 tahun) untuk mengembangkan kemampuan kognitif, motorik, dan sosial melalui metode belajar yang menyenangkan.</p>
        
        <h3>Keunggulan Kami:</h3>
        <ul>
            <li>Metode bermain sambil belajar</li>
            <li>Fokus pada pengenalan huruf dan angka dasar</li>
            <li>Pengembangan karakter dan kemandirian</li>
            <li>Lingkungan yang aman dan nyaman</li>
        </ul>

        <div style="margin-top: 30px; text-align: center;">
            <a href="/admin/siswas/create" class="btn">Daftar Sekarang</a>
        </div>
    </div>
</body>
</html>
