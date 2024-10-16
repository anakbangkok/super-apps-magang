<<<<<<< HEAD
# super-apps-magang
=======
# Project Absensi Magang

## Deskripsi
Aplikasi super-apps-magang menggunakan Laravel 11.

## Instalasi

1. Clone repository:
   ```bash
   git clone https://github.com/anakbangkok/super-apps-magang.git
  
2. Masuk ke direktori proyek:
    cd absensi-magang

3. Instal dependensi Composer:
    composer install

4. Instal dependensi NPM (jika diperlukan):
    npm install
    npm run dev

5. Buat file .env dari .env.example dan atur konfigurasi:
    cp .env.example .env
    php artisan key:generate

6. Atur konfigurasi database di .env.

7. Jalankan migrasi:
   php artisan migrate

8. Jalankan server:
   php artisan serve


# NB
**Jangan Unggah File Sensitif:

Pastikan file .env dan file lain yang mengandung informasi sensitif tidak diunggah ke GitHub.
Gunakan GitHub Actions (Opsional):

Automasi proses CI/CD untuk pengujian, build, dan deployment.
Perbarui .gitignore Secara Berkala:

Tambahkan file atau direktori baru yang seharusnya diabaikan oleh Git.
Commit Secara Teratur:

Lakukan commit dengan pesan yang jelas dan deskriptif untuk melacak perubahan dengan mudah.
Gunakan Tag dan Release:

Tandai versi stabil proyek Anda dengan tag dan rilis di GitHub.
**
>>>>>>> c5ddb83 (first commit)