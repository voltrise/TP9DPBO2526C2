# Project MVP Strict Passive View - Kandidat & Lowongan Rekrutmen

Implementasi pola **Model-View-Presenter (MVP)** edisi **Strict Passive View (*Dumb View*)** menggunakan PHP Native, PDO MySQL Prepared Statements, dan Vanilla CSS.

## Setup Awal
1. Import file `mvp_db.sql` ke MySQL / phpMyAdmin (membuat database `mvp_kandidat_db` beserta tabel `kandidat` dan `lowongan`).
2. Pastikan folder `project/` dapat diakses melalui web server lokal (`htdocs/` pada XAMPP atau `www/` pada Laragon) atau PHP built-in server.
3. Jalankan Apache dan MySQL, lalu buka `index.php` di browser.

## Fitur Tersedia
- **Modul Kandidat (`?entity=kandidat`)**: CRUD lengkap (Create, Read, Update, Delete) data pelamar kerja, terurut dari `skorTes` tertinggi.
- **Modul Lowongan (`?entity=lowongan`)**: CRUD lengkap (Create, Read, Update, Delete) posisi lowongan kerja beserta departemen, tipe kerja, lokasi, kuota, dan status (`Aktif` / `Ditutup`).
