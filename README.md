# ✂️ POS Barber App — Backend API

> RESTful API backend untuk sistem Point of Sale (POS) Barbershop, dibangun dengan **Laravel 13** dan **Sanctum** authentication.

---

## 📋 Deskripsi

**POS Barber App** adalah backend API untuk mengelola operasional barbershop secara digital. Sistem ini mencakup manajemen barber, jadwal, layanan, booking, produk, transaksi, serta laporan bisnis. API ini dirancang untuk mendukung frontend React ([barber-frontend-app](https://github.com/tirtanusa/barber-frontend-app)) dengan arsitektur yang modular dan aman menggunakan role-based access control.

---

## 🚀 Tech Stack

| Teknologi            | Versi / Detail        |
| -------------------- | --------------------- |
| **PHP**              | ^8.3                  |
| **Laravel**          | ^13.8                 |
| **Laravel Sanctum**  | ^4.0 (Authentication) |
| **Database**         | MySQL                 |
| **Testing**          | Pest ^4.7             |

---

## 📁 Struktur Proyek

```
pos-barber-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          # Login, Register, Logout, Me
│   │   │   ├── UserController.php          # CRUD User Management
│   │   │   ├── BarberController.php        # CRUD Barber
│   │   │   ├── BarberScheduleController.php# Jadwal kerja barber
│   │   │   ├── TimeSlotController.php      # Generate & kelola slot waktu
│   │   │   ├── ServiceController.php       # CRUD Layanan barbershop
│   │   │   ├── BookingController.php       # Booking & status management
│   │   │   ├── ProductController.php       # CRUD Produk & stok
│   │   │   ├── TransactionController.php   # Transaksi POS
│   │   │   └── ReportController.php        # Laporan & analytics
│   │   └── Middleware/
│   │       └── RoleMiddleware.php          # Role-based access control
│   ├── Models/
│   │   ├── User.php
│   │   ├── Barber.php
│   │   ├── BarberSchedule.php
│   │   ├── Booking.php
│   │   ├── TimeSlot.php
│   │   ├── Service.php
│   │   ├── Product.php
│   │   ├── Transaction.php
│   │   └── TransactionItem.php
│   └── Traits/
│       └── ApiResponse.php                 # Standardized API response
├── database/
│   ├── migrations/                         # 12 migration files
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── UserSeeder.php
│       ├── BarberSeeder.php
│       ├── BarberScheduleSeeder.php
│       ├── ServiceSeeder.php
│       └── ProductSeeder.php
├── routes/
│   └── api.php                             # Semua API routes
└── Postman/
    └── Postman Endpoint Documentation.json # Collection untuk testing
```

---

## 🔐 Autentikasi & Otorisasi

Menggunakan **Laravel Sanctum** dengan token-based authentication dan role-based middleware.

### Roles

| Role      | Akses                                              |
| --------- | -------------------------------------------------- |
| **admin** | Full akses ke semua resource & laporan              |
| **user**  | Booking, lihat transaksi sendiri, update profil     |

---

## 📡 API Endpoints

### 🔑 Authentication

| Method | Endpoint          | Deskripsi              | Auth |
| ------ | ----------------- | ---------------------- | ---- |
| POST   | `/api/auth/login`    | Login user             | ❌   |
| POST   | `/api/auth/register` | Registrasi user baru   | ❌   |
| POST   | `/api/auth/logout`   | Logout (revoke token)  | ✅   |
| GET    | `/api/auth/me`       | Profil user yang login | ✅   |

---

### 👥 Users (Admin Only)

| Method | Endpoint               | Deskripsi          |
| ------ | ---------------------- | ------------------ |
| GET    | `/api/users`           | List semua user    |
| POST   | `/api/users/add-user`  | Tambah user baru   |
| GET    | `/api/users/{id}`      | Detail user        |
| PUT    | `/api/users/{id}`      | Update user *(admin & user)* |
| DELETE | `/api/users/{id}`      | Hapus user         |

---

### 💈 Barbers

| Method | Endpoint                  | Deskripsi              | Auth  |
| ------ | ------------------------- | ---------------------- | ----- |
| GET    | `/api/barber`             | List semua barber      | ❌    |
| GET    | `/api/barber/{id}`        | Detail barber          | ❌    |
| POST   | `/api/barber/add-barber`  | Tambah barber          | Admin |
| PUT    | `/api/barber/{id}`        | Update barber          | Admin |
| DELETE | `/api/barber/{id}`        | Hapus barber           | Admin |

---

### 📅 Barber Schedules

| Method | Endpoint                                  | Deskripsi            | Auth  |
| ------ | ----------------------------------------- | -------------------- | ----- |
| GET    | `/api/barber/{id}/schedule`               | Lihat jadwal barber  | ❌    |
| POST   | `/api/barber/{id}/schedule`               | Tambah jadwal        | Admin |
| PUT    | `/api/barber/{id}/schedule/{schedule_id}` | Update jadwal        | Admin |
| DELETE | `/api/barber/{id}/schedule/{schedule_id}` | Hapus jadwal         | Admin |

---

### ⏰ Time Slots

| Method | Endpoint                          | Deskripsi              | Auth  |
| ------ | --------------------------------- | ---------------------- | ----- |
| GET    | `/api/barber/{id}/slots`          | Lihat slot waktu       | ❌    |
| POST   | `/api/barber/{id}/slots/generate` | Generate slot otomatis | Admin |
| PATCH  | `/api/slots/{id}/block`           | Blokir slot            | Admin |
| PATCH  | `/api/slots/{id}/unblock`         | Buka blokir slot       | Admin |

---

### 🛎️ Services

| Method | Endpoint              | Deskripsi          | Auth  |
| ------ | --------------------- | ------------------ | ----- |
| GET    | `/api/services`       | List semua service | ❌    |
| GET    | `/api/services/{id}`  | Detail service     | ❌    |
| POST   | `/api/services`       | Tambah service     | Admin |
| PUT    | `/api/services/{id}`  | Update service     | Admin |
| DELETE | `/api/services/{id}`  | Hapus service      | Admin |

---

### 📋 Bookings

| Method | Endpoint                       | Deskripsi                 | Auth       |
| ------ | ------------------------------ | ------------------------- | ---------- |
| POST   | `/api/bookings`                | Buat booking baru         | User/Admin |
| GET    | `/api/bookings/my`             | Booking milik user login  | User/Admin |
| GET    | `/api/bookings/{id}`           | Detail booking            | User/Admin |
| PATCH  | `/api/bookings/{id}/cancel`    | Batalkan booking          | User/Admin |
| GET    | `/api/bookings`                | List semua booking        | Admin      |
| PATCH  | `/api/bookings/{id}/status`    | Update status booking     | Admin      |

**Status Flow:** `pending` → `confirmed` → `in_progress` → `completed` | `cancelled`

---

### 📦 Products

| Method | Endpoint                     | Deskripsi          | Auth  |
| ------ | ---------------------------- | ------------------ | ----- |
| GET    | `/api/products`              | List semua produk  | ❌    |
| GET    | `/api/products/{id}`         | Detail produk      | ❌    |
| POST   | `/api/products`              | Tambah produk      | Admin |
| PUT    | `/api/products/{id}`         | Update produk      | Admin |
| DELETE | `/api/products/{id}`         | Hapus produk       | Admin |
| PATCH  | `/api/products/{id}/stock`   | Update stok produk | Admin |

---

### 💳 Transactions

| Method | Endpoint                          | Deskripsi                   | Auth       |
| ------ | --------------------------------- | --------------------------- | ---------- |
| GET    | `/api/transactions`               | List semua transaksi        | Admin      |
| POST   | `/api/transactions`               | Buat transaksi baru         | Admin      |
| PATCH  | `/api/transactions/{id}/status`   | Update status transaksi     | Admin      |
| GET    | `/api/transaction/my`             | Transaksi milik user login  | User       |
| GET    | `/api/transactions/{id}`          | Detail transaksi            | User/Admin |

**Payment Methods:** `cash`, `debit`, `credit`
**Status:** `pending`, `success`, `failed`

---

### 📊 Reports (Admin Only)

| Method | Endpoint                          | Deskripsi                           |
| ------ | --------------------------------- | ----------------------------------- |
| GET    | `/api/reports/summary`            | Dashboard summary (revenue, count)  |
| GET    | `/api/reports/top-services`       | Top 10 service terlaris             |
| GET    | `/api/reports/top-products`       | Top 10 produk terlaris              |
| GET    | `/api/reports/top-barbers`        | Top 10 barber (by total booking)    |
| GET    | `/api/reports/top-rated-barber`   | Top 10 barber (by rating) *(Public)* |
| GET    | `/api/reports/revenue`            | Laporan revenue (daily/monthly/yearly) |

---

## ⚙️ Instalasi & Setup

### Prerequisites

- PHP >= 8.3
- Composer
- MySQL
- Node.js & NPM

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/tirtanusa/pos-barber-app.git
cd pos-barber-app

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Konfigurasi database di .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=pos_barber_app
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Jalankan migration & seeder
php artisan migrate
php artisan db:seed

# 6. Jalankan server
php artisan serve
```

Atau gunakan script otomatis:

```bash
composer setup   # Install, migrate, build
composer dev     # Jalankan server + queue + vite
```

---

## 🧪 Testing

```bash
# Jalankan semua test
php artisan test

# Atau menggunakan Pest
./vendor/bin/pest
```

---

## 📬 Postman Collection

File Postman Collection tersedia di folder `Postman/` untuk mempermudah testing API:

```
Postman/Postman Endpoint Documentation.json
```

Import file ini ke Postman untuk mendapatkan dokumentasi lengkap semua endpoint beserta contoh request/response.

---

## 🗃️ Database Schema

```
users
├── id, name, email, password, phone_number, role
│
barbers
├── id, name, phone, rating, is_active, specialization, photo
│
barber_schedules
├── id, barber_id (FK), day_of_week, start_time, end_time
│
time_slots
├── id, barber_id (FK), booking_id (FK), date, start_time, end_time, status
│
services
├── id, name, price, duration_minutes, description
│
bookings
├── id, user_id (FK), barber_id (FK), service_id (FK)
├── booking_date, start_time, end_time, status, notes
│
products
├── id, name, description, price, stock, category, image
│
transactions
├── id, user_id (FK), booking_id (FK)
├── subtotal_service, subtotal_product, total_payment
├── payment_method, status
│
transaction_items
├── id, transaction_id (FK), product_id (FK)
├── quantity, unit_price, total_price
```

---

## 🔗 Related Repository

- **Frontend (React):** [barber-frontend-app](https://github.com/tirtanusa/barber-frontend-app)

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
