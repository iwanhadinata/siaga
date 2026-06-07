# PROJECT CONTEXT: SIAGA (Sistem Informasi Administrasi Gereja)
Sistem skala menengah (~5.000 entitas jemaat) dengan pertumbuhan data transaksional tinggi.

# TECH STACK UTAMA:
- Backend: CodeIgniter 4.7.2 (PHP 8.2)
- Frontend: Tailwind CSS 4.2 (Native CSS variables) + Alpine.js 3
- Database: MySQL 8 (InnoDB)
- Keamanan: CodeIgniter Shield

# MANDATORY CODING GUIDELINES UNTUK GEMINI AGENT:
Setiap kali saya meminta Anda membuat atau memodifikasi file (Controller, Model, View, Migration), Anda WAJIB mematuhi aturan berikut:

1. BACKEND & LOGIC (CI4 & PHP 8.2):
- Gunakan arsitektur "Skinny Controller, Fat Model". Controller HANYA untuk HTTP flow dan validasi.
- Selalu pisahkan manipulasi data menggunakan CI4 Entities.
- Gunakan PHP 8.2 Enums (`app/Enums/`) untuk atribut data statis. Jangan buat tabel database untuk referensi status/opsi kecil.
- Cegah N+1 Query Problem: Gunakan Query Builder CI4 dengan metode JOIN langsung di dalam Model saat mengambil data berelasi.

2. DATABASE STRATEGY (MYSQL 8):
- Selalu sediakan file Migration (up/down lengkap) & Seeder untuk struktur tabel baru.
- Gunakan Strict Foreign Key Constraints (CASCADE/RESTRICT/SET NULL) pada setiap relasi.
- Terapkan Indexing yang eksplisit pada kolom Foreign Keys dan kolom pencarian utama (seperti nik, nama, id_wilayah).
- Sediakan kolom `audit_trail` (tipe data JSON) di tabel utama untuk log metadata/histori.

3. FRONTEND & UI/UX (TAILWIND V4 + ALPINE.JS):
- Views HARUS menggunakan sintaks Tailwind CSS v4. Jangan panggil konfigurasi dari `tailwind.config.js`. Gunakan palet warna semantic (primary, secondary, surface, dsb) sesuai desain.
- Gunakan arsitektur komponen CI4 (View Cells / View Layouts) untuk elemen berulang.
- Wajib menggunakan Alpine.js 3 (x-data, x-model, dsb) untuk reaktivitas client-side. JANGAN gunakan jQuery.
- Untuk tabel data master/transaksi, tuliskan struktur HTML untuk Server-Side Processing (AJAX). Jangan meload ribuan data dalam satu request.

4. SECURITY & AUTH (CI SHIELD):
- RBAC Absolutely: Route Filters bawaan Shield wajib digunakan di `app/Config/Routes.php` untuk memproteksi endpoint. Jangan periksa `$this->auth()->inGroup()` secara manual berulang-ulang di Controller kecuali untuk logika spesifik di luar akses rute.
- Selalu gunakan `csrf_field()` di setiap form HTML dan lakukan validasi di Controller.