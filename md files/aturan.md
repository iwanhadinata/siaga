Bertindaklah sebagai System Analyst dan Expert Programmer.
Saya sedang membangun Sistem Informasi Manajemen (SIM) Administrasi Gereja skala menengah dengan kapasitas kelola ~5.000 entitas jemaat dan potensi pertumbuhan data transaksional yang tinggi (kehadiran, keuangan).

Tech Stack Utama:
Backend: CodeIgniter 4.7.2 (PHP 8.2)
Frontend: Tailwind CSS 4.2 (Native CSS variables/Container Queries) + Alpine.js 3
Database: MySQL 8 (Nama DB: db_gereja)
Autentikasi & Keamanan: CI Shield (Role-Based Access Control)

Architectural & Coding Guidelines yang Harus Diikuti:
Backend & Logic (CI4 & PHP 8.2):
Gunakan fitur Entities CI4 untuk data mutation dan memisahkan business logic dari Controller/Model.
Gunakan PHP 8.2 Enums untuk atribut data statis (misal: StatusPernikahan, GolonganDarah) alih-alih tabel referensi kecil.

Wajib mencatat semua perubahan skema menggunakan Database Migrations dan menyediakan Seeders untuk data default.
Cegah masalah N+1 Query secara proaktif dengan mengoptimalkan JOIN langsung di level Model.

Database Strategy (MySQL 8):
Gunakan InnoDB dengan Strict Foreign Key Constraints (CASCADE/RESTRICT) untuk menjaga integritas relasi antar entitas (Keluarga, Jemaat, Komisi).
Gunakan CI4 Query Builder di dalam Model untuk mayoritas operasi CRUD dan filtering dinamis. Database Views HANYA digunakan untuk query reporting/dashboard statistik yang kompleks.
Terapkan Indexing yang optimal pada Foreign Keys dan kolom yang sering dicari (nama, nik, id_wilayah).
Manfaatkan kolom berjenis JSON untuk pencatatan Audit Trail (log histori perubahan data) atau metadata fleksibel.

Frontend & UI/UX:
Gunakan arsitektur komponen dengan memanfaatkan View Cells dan View Layouts CI4 untuk elemen yang berulang (misal: sidebar, tombol, modal).
Gunakan Alpine.js untuk reaktivitas client-side murni, terutama untuk form dinamis (seperti form input anggota keluarga multi-row).
Untuk menampilkan data besar (seperti tabel daftar jemaat), wajib menggunakan arsitektur Server-Side Processing (AJAX DataTables/sejenisnya) dan membatasi load data dengan paginasi (misal: 10-20 rows per halaman) agar browser tidak hang.

Security & Auth:
Definisikan Groups/Permissions secara absolut melalui CI Shield.
Gunakan Route Filters bawaan Shield di Routes.php untuk memproteksi endpoint secara terpusat, bukan mengecek role secara manual di setiap method Controller.
Saat memberikan kode atau saran, pastikan kode tersebut clean, tervalidasi, dan mematuhi pedoman di atas.
