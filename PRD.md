# PRODUCT REQUIREMENTS DOCUMENT

# Grow a Garden — Smart Garden Management

---

# Vision

Grow a Garden adalah aplikasi manajemen kebun yang membantu pengguna merencanakan, memetakan, dan memantau pertumbuhan tanaman melalui kalender pertumbuhan otomatis dan pengingat perawatan berbasis template tanaman.

Target pengguna:

* Pekebun rumahan
* Urban farming
* Mini garden
* Hidroponik
* Sekolah
* Komunitas pertanian

---

# USER APPLICATION

## 1. Dashboard

### Ringkasan Kebun

Menampilkan:

* Total kebun
* Total tanaman aktif
* Jumlah plot
* Aktivitas hari ini

### Jadwal Hari Ini

* Penyiraman
* Pemupukan
* Pemeriksaan tanaman

### Upcoming Harvest

Tanaman yang mendekati panen.

---

## 2. Garden Map ⭐

Fitur utama pembeda.

### Input

* Nama kebun
* Panjang
* Lebar

### Grid System

Contoh:

A1 A2 A3 A4

B1 B2 B3 B4

C1 C2 C3 C4

### Plot

Setiap plot memiliki:

* kode
* tanaman
* jumlah tanaman
* tanggal tanam
* status

### Warna Plot

Hijau = sehat

Kuning = perlu perhatian

Merah = terlambat perawatan

Biru = baru ditanam

---

## 3. Plant Management

Tambah tanaman:

* komoditas
* varietas
* jumlah
* lokasi plot
* tanggal tanam

Informasi tanaman:

* umur tanaman
* fase pertumbuhan
* estimasi panen
* status

---

## 4. Growth Calendar ⭐

Timeline:

Tanam

↓

Germinasi

↓

Persemaian

↓

Vegetatif

↓

Berbunga

↓

Berbuah

↓

Panen

Seluruh milestone dibuat otomatis.

---

## 5. Care Reminder

Jenis:

* Penyiraman
* Pemupukan
* Pengendalian hama
* Pemeriksaan tanaman

Status:

* Pending
* Done
* Skip

Prioritas:

* High
* Medium
* Low

---

## 6. Activity Log

Input:

* tanggal
* aktivitas
* tanaman
* catatan singkat

Jenis aktivitas:

* Menyiram
* Memupuk
* Memangkas
* Penyemaian
* Panen

---

## 7. Weather Adjustment

Mode:

* Normal
* Musim hujan
* Musim kemarau

Pengaruh:

Hujan:

* pengurangan reminder penyiraman

Kemarau:

* penambahan reminder penyiraman

---

# ADMIN PANEL

## 8. Dashboard Admin

Menampilkan:

* total user
* total kebun
* total tanaman aktif
* tanaman populer
* aktivitas hari ini

---

## 9. Plant Master ⭐

Data:

* nama tanaman
* nama ilmiah
* kategori
* warna
* status

Status:

* Active
* Inactive

---

## 10. Growth Template ⭐⭐⭐

Fase:

* Germinasi
* Persemaian
* Vegetatif
* Berbunga
* Berbuah
* Panen

Contoh Cabai:

0-10 hari

11-30 hari

31-70 hari

71-90 hari

91-120 hari

120 hari

---

## 11. Care Template ⭐⭐⭐

Task bawaan:

Hari 1

Penyiraman

Hari 7

Pemupukan awal

Hari 30

Pemupukan vegetatif

Hari 70

Pemupukan berbunga

---

## 12. Stage Template

Default:

* Seed
* Seedling
* Vegetative
* Flowering
* Fruiting
* Harvest

---

## 13. Weather Rules

Musim hujan

-30% reminder penyiraman

Musim kemarau

+50% reminder penyiraman

---

## 14. Activity Type Master

* Menyiram
* Memupuk
* Memangkas
* Panen
* Pengendalian hama

---

## 15. Notification Template

* Hari ini jadwal penyiraman
* Saatnya pemupukan
* Panen dalam 3 hari

---

## 16. Regional Season Calendar

Data:

* provinsi
* bulan
* musim

Digunakan untuk weather adjustment.

---

## 17. User Management

* daftar user
* suspend user
* statistik pengguna

---

## 18. Analytics

* tanaman populer
* aktivitas terbanyak
* jumlah kebun
* rata-rata umur panen

---

# DATABASE

users

gardens

garden_plots

plants

plant_instances

growth_templates

care_templates

care_tasks

activities

activity_types

weather_rules

regional_seasons

notifications

---

# RULE ENGINE

Tambah tanaman

↓

Ambil growth template

↓

Generate milestone

↓

Generate task perawatan

↓

Hitung tanggal panen

↓

Masukkan ke kalender

↓

Dashboard menampilkan jadwal

---

Task selesai

↓

Update last activity

↓

Generate task berikutnya

---

Pergantian hari

↓

Cari task hari ini

↓

Tampilkan reminder

---

# MVP

✅ Dashboard

✅ Garden Map ⭐

✅ Plant Management

✅ Growth Calendar ⭐

✅ Care Reminder

✅ Activity Log

✅ Plant Master

✅ Growth Template

✅ Care Template

✅ User Management

✅ Analytics

---

# Future Features

* Disease Encyclopedia
* Export PDF
* Multi Garden
* Family Sharing
* QR Plot
* Harvest Statistics
* Backup Cloud

---

# Unique Selling Point

1. Garden Map seperti game farming.

2. Growth Calendar otomatis.

3. Reminder mengikuti fase pertumbuhan.

4. Rule engine berbasis template tanaman.

5. Mendukung seluruh komoditas Indonesia tanpa perangkat IoT.