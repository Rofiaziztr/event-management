# 🚀 Google OAuth Setup Guide - UPDATED

## ❌ Error "Missing required paramet### **Step 7: Test Login**

1. Jalankan aplikasi: `php artisan serve`
2. Buka `http://localhost:8000/login`
3. Klik **"Masuk dengan Google"**
4. Pilih akun Google → **"Continue"**
5. Akan redirect kembali dan login otomatis! ✅

---

## 🔗 Google Calendar Integration Setup

### **Per-User Calendar Authorization**

Sistem ini mendukung sinkronisasi event ke Google Calendar masing-masing peserta. Setiap peserta dapat menghubungkan calendar mereka sendiri.

#### **Step 1: Setup untuk Peserta**

1. Login sebagai peserta
2. Buka Dashboard Peserta (`/participant/dashboard`)
3. Cari section **"Google Calendar Integration"**
4. Klik **"Hubungkan Calendar"**
5. Berikan izin akses ke Google Calendar
6. Event akan otomatis muncul di calendar peserta

#### **Step 2: Verifikasi Sinkronisasi**

1. Buat event baru sebagai admin
2. Event akan otomatis sync ke calendar semua peserta yang sudah authorize
3. Peserta dapat melihat event di Google Calendar mereka

#### **Step 3: Sync Existing Events**

Jika ada event yang sudah ada sebelum setup calendar:

```bash
# Sync untuk semua users
php artisan app:sync-existing-events-to-calendars

# Sync untuk user tertentu
php artisan app:sync-existing-events-to-calendars --user=123
```

---

## 🔧 Troubleshooting

### **Error: redirect_uri_mismatch**

-   Pastikan `GOOGLE_REDIRECT_URI` di `.env` SAMA PERSIS dengan yang didaftarkan di Google Console
-   Pastikan menggunakan `http://localhost:8000` (bukan `http://127.0.0.1:8000`)

### **Error: invalid_client**

-   Pastikan `GOOGLE_CLIENT_ID` benar
-   Pastikan `GOOGLE_CLIENT_SECRET` benar

### **Calendar Tidak Sync**

-   Pastikan user sudah authorize calendar access
-   Cek log file untuk error messages
-   Pastikan Google Calendar API enabled di Google Console
-   Verifikasi scopes: `https://www.googleapis.com/auth/calendar.events`ent_id" - SOLVED!

Error ini terjadi karena `GOOGLE_CLIENT_ID` belum dikonfigurasi di file `.env`. Ikuti panduan lengkap di bawah ini.

---

## 📋 Step-by-Step Setup Google OAuth

### **Step 1: Kunjungi Google Cloud Console**

1. Buka browser dan kunjungi: https://console.cloud.google.com/
2. Login dengan akun Google Anda
3. Jika belum punya project, klik **"Create Project"**
4. Beri nama project (contoh: "Event Management App")
5. Klik **"Create"**

### **Step 2: Aktifkan Google OAuth APIs**

1. Di sidebar kiri, klik **"APIs & Services"** → **"Library"**
2. Cari **"Google+ API"** dan klik
3. Klik **"Enable"** untuk mengaktifkan API

### **Step 3: Buat OAuth 2.0 Credentials**

1. Di sidebar kiri, klik **"APIs & Services"** → **"Credentials"**
2. Klik **"+ CREATE CREDENTIALS"** → **"OAuth 2.0 Client ID"**

3. **Configure OAuth Consent Screen** (jika diminta):

    - User Type: **"External"**
    - App name: **"Event Management System"**
    - User support email: **(pilih email Anda)**
    - Developer contact info: **(isi email Anda)**
    - Klik **"Save and Continue"**

4. **Create OAuth Client ID**:

    - Application type: **"Web application"**
    - Name: **"Event Management Web App"**

5. **Authorized redirect URIs** - Tambahkan untuk login OAuth:

    ```
    http://localhost:8000/auth/google/callback
    ```

    **DAN** untuk Google Calendar OAuth:

    ```
    http://localhost:8000/google-calendar/callback
    ```

    ⚠️ **PENTING**: Pastikan KEDUA redirect URI didaftarkan di Google Console

6. Klik **"Create"**

### **Step 4: Dapatkan Credentials**

Setelah dibuat, Anda akan mendapat:

-   **Client ID**: String panjang yang dimulai dengan angka
-   **Client Secret**: String yang lebih pendek

### **Step 5: Konfigurasi di Laravel**

1. Buka file `.env` di root project
2. Cari bagian **Google OAuth Configuration**
3. Isi credentials yang didapat:

```bash
# Google OAuth Configuration
GOOGLE_CLIENT_ID=123456789-abcdefghijklmnopqrstuvwxyz.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=ABC123-DEF456-GHI789
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
```

### **Step 6: Clear Cache Laravel**

```bash
php artisan config:clear
php artisan cache:clear
```

### **Step 7: Test Login**

1. Jalankan aplikasi: `php artisan serve`
2. Buka `http://localhost:8000/login`
3. Klik **"Masuk dengan Google"**
4. Pilih akun Google → **"Continue"**
5. Akan redirect kembali dan login otomatis!

---

## 🔧 Troubleshooting

### **Error: redirect_uri_mismatch**

-   Pastikan `GOOGLE_REDIRECT_URI` di `.env` SAMA PERSIS dengan yang didaftarkan di Google Console
-   Pastikan menggunakan `http://localhost:8000` (bukan `http://127.0.0.1:8000`)

### **Error: invalid_client**

-   Pastikan `GOOGLE_CLIENT_ID` benar
-   Pastikan `GOOGLE_CLIENT_SECRET` benar

### **Error: access_denied**

-   User membatalkan authorization
-   Coba lagi atau cek consent screen

### **Error: 400 invalid_request**

-   Pastikan semua parameter sudah diisi di `.env`
-   Clear cache Laravel

---

## 📱 Production Setup

Untuk production, pastikan:

1. **HTTPS Required**: Google OAuth mengharuskan HTTPS di production
2. **Authorized Redirect URIs**: Tambahkan domain production
    ```
    https://yourdomain.com/auth/google/callback
    ```
3. **Environment Variables**: Set di server production
4. **OAuth Consent Screen**: Publish ke production jika perlu

---

## 🎯 Fitur Google OAuth

✅ **Auto Email Verification**: Email langsung terverifikasi dari Google
✅ **Auto User Creation**: User baru dibuat otomatis jika belum ada
✅ **Account Linking**: Jika email sudah ada, Google account di-link
✅ **Secure**: OAuth 2.0 protocol
✅ **User-Friendly**: One-click login

---

## 📞 Butuh Bantuan?

Jika masih ada error:

1. Check `.env` file - pastikan credentials sudah diisi
2. Check Google Console - pastikan redirect URI benar
3. Check Laravel logs: `storage/logs/laravel.log`
4. Test dengan credentials baru jika perlu

**Error sudah teratasi!** 🚀
