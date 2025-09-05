# Konfigurasi Docker untuk POS Admin

Konfigurasi Docker ini menyediakan lingkungan pengembangan untuk aplikasi POS Admin dengan:
- PHP 8.1 dengan FPM
- Nginx stable:alpine
- MySQL 8
- Adminer 4

## Port yang Digunakan
- Webserver (Nginx): 8030
- MySQL: 3306 (default)
- Adminer: 8040

## Cara Penggunaan

### Persiapan Awal
1. Salin file `.env.docker` ke `.env`:
   ```
   cp .env.docker .env
   ```

2. Generate Laravel application key:
   ```
   php artisan key:generate
   ```

### Menjalankan Docker
1. Build dan jalankan container:
   ```
   docker-compose up -d
   ```

2. Masuk ke container aplikasi:
   ```
   docker-compose exec app bash
   ```

3. Di dalam container, install dependencies Laravel:
   ```
   composer install
   ```

4. Jalankan migrasi database:
   ```
   php artisan migrate
   ```

### Mengakses Layanan
- Aplikasi Web: http://localhost:8030
- Adminer (Database Management): http://localhost:8040
  - Server: db
  - Username: pos_user (sesuai .env)
  - Password: pos_password (sesuai .env)
  - Database: pos_admin (sesuai .env)

### Menghentikan Docker
```
docker-compose down
```

### Menghentikan dan Menghapus Volume
```
docker-compose down -v
```
