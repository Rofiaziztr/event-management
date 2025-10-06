# Panduan Integrasi Google Calendar

Dokumen ini menjelaskan langkah terperinci untuk mengaktifkan dan memelihara integrasi Google Calendar di Sistem Manajemen Event.

## 1. Prasyarat

- Akses ke Google Cloud Console dengan izin membuat Service Account.
- Kalender Google (primary atau sekunder) yang akan menyimpan jadwal event.
- Lingkungan aplikasi yang telah terinstal dependensi `spatie/laravel-google-calendar`.

## 2. Konfigurasi Google Cloud

1. Masuk ke Google Cloud Console dan aktifkan **Google Calendar API**.
2. Buat **Service Account** baru dan unduh file kredensial JSON.
3. Buka Google Calendar yang akan digunakan, klik **Settings & sharing** → **Share with specific people or groups**, tambahkan email Service Account sebagai "Editor".

## 3. Menyimpan Kredensial

1. Buat folder `storage/app/google-calendar` jika belum ada.
2. Simpan file JSON ke folder tersebut dengan nama `service-account-credentials.json` (atau gunakan nama lain namun diperbarui di variabel environment `GOOGLE_CALENDAR_CREDENTIALS_JSON`).

## 4. Variabel Environment

Tambahkan variabel berikut pada `.env`:

```env
GOOGLE_CALENDAR_SYNC_ENABLED=true
GOOGLE_CALENDAR_ID=primary
GOOGLE_CALENDAR_CREDENTIALS_JSON="storage/app/google-calendar/service-account-credentials.json"
GOOGLE_CALENDAR_TIMEZONE=Asia/Jakarta
GOOGLE_CALENDAR_SEND_UPDATES=all
GOOGLE_CALENDAR_REMINDER_EMAIL_MINUTES=1440
GOOGLE_CALENDAR_REMINDER_POPUP_MINUTES=30
GOOGLE_CALENDAR_CREATE_CONFERENCE=false
```

Opsi tambahan:

- `GOOGLE_CALENDAR_DEFAULT_ATTENDEES` — daftar email pemantau default (pisahkan dengan koma).
- `GOOGLE_CALENDAR_CREATE_CONFERENCE=true` — aktifkan pembuatan Google Meet otomatis.
- `GOOGLE_CALENDAR_CONFERENCE_TYPE` — jenis conference solution (`hangoutsMeet`, `addOn` dll.).

## 5. Alur Sinkronisasi Otomatis

- **Event dibuat** → Observer membuat event Google Calendar dan menyimpan tautan ke database.
- **Event diubah** (judul, jadwal, lokasi, status) → Observer memperbarui event Google Calendar.
- **Event dibatalkan** → Event Google Calendar dihapus.
- **Event dihapus** → Event Google Calendar ikut dihapus.

Semua status sinkronisasi, tautan Google Calendar, dan link konferensi ditampilkan di halaman detail event admin.

## 6. Sinkronisasi Manual

Gunakan perintah artisan untuk mengelola sinkronisasi secara massal:

```bash
php artisan events:sync-google             # sinkron semua event
php artisan events:sync-google 42          # sinkron event ID 42 atau kode EVT-xxx
php artisan events:sync-google 42 --delete # hapus event dari Google Calendar
php artisan events:sync-google --only-pending # sinkron event yang gagal/ belum pernah sync
```

Alternatif lain, gunakan tombol **Sinkron Ulang** dan **Hapus dari Calendar** pada halaman detail event.

## 7. Pemecahan Masalah

| Gejala | Penyebab Umum | Solusi |
| --- | --- | --- |
| Status "Gagal Sinkron" | Kredensial salah atau kalender tidak dibagikan | Pastikan Service Account memiliki akses dan file JSON valid |
| Status "Tidak Ditemukan" | Event terhapus manual di Google Calendar | Jalankan `php artisan events:sync-google {id}` untuk membuat ulang |
| Tidak ada tombol sinkronisasi | `GOOGLE_CALENDAR_SYNC_ENABLED` masih `false` | Aktifkan variabel di `.env` lalu reload aplikasi |
| Tidak muncul link Google Meet | `GOOGLE_CALENDAR_CREATE_CONFERENCE` masih `false` | Set ke `true` dan lakukan sinkron ulang |

## 8. Monitoring

- Periksa kolom `google_calendar_sync_status` pada tabel `events` untuk audit.
- Gunakan log Laravel untuk menelusuri error (tag "Google Calendar sync failed").
- Jalankan perintah artisan secara terjadwal (cron) jika ingin memastikan sinkronisasi berkala.

---

Dengan konfigurasi ini, seluruh lifecycle event pada aplikasi akan terhubung langsung ke Google Calendar, sehingga undangan dan jadwal selalu sinkron dengan kalender perusahaan.
