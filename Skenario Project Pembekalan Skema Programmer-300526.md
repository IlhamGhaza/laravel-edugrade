## **Skenario Project Pembekalan Skema Programmer** 

## **Silahkan Baca dan Pahami scenario berikut dan ikuti tahapan yang telah diberikan!** 

**Skenario :** Berdasarkan kebutuhan sebuah institusi pendidikan, anda diminta untuk berperan sebagai seorang programmer yang bertugas membangun aplikasi pengolahan nilai siswa berbasis komputer. Aplikasi yang dibuat harus mampu membantu proses pengelolaan data nilai siswa secara lebih cepat, rapi, dan terstruktur, mulai dari pengelolaan data siswa, proses input nilai, perhitungan nilai akhir, hingga penyajian laporan hasil belajar siswa. 

Dalam pengembangan aplikasi ini, anda diminta untuk menerapkan dua pendekatan pemrograman, yaitu: 

1. **Pemrograman Terstruktur** , dengan menggunakan fungsi atau prosedur untuk menangani proses logika program seperti: 

   - validasi nilai, 

   - perhitungan nilai akhir, 

   - penentuan status kelulusan, 

   - dan pengolahan laporan. 

2. **Pemrograman Berorientasi Objek (Object Oriented Programming/OOP)** , dengan memodelkan objek dalam sistem seperti: 

   - Siswa, 

   - Guru, 

   - Nilai, 

ke dalam bentuk class, object, atribut, dan method. 

Kedua pendekatan tersebut harus digunakan secara terintegrasi dalam satu aplikasi sehingga mampu menunjukkan bagaimana pemrograman terstruktur dan OOP saling mendukung dalam menyelesaikan sebuah studi kasus pemrograman. 

## **A. DESKRIPSI SISTEM** 

Sistem pengolahan nilai yang akan dibuat memiliki kebutuhan sebagai berikut: 

1. Sistem dapat menyimpan data siswa. 

2. Sistem dapat mengelola data nilai siswa. 

3. Sistem dapat menghitung nilai akhir secara otomatis. 

4. Sistem dapat menentukan status kelulusan siswa. 

5. Sistem dapat menampilkan laporan hasil nilai siswa. 

## **B. HAK AKSES PENGGUNA** 

## **1. Admin** 

Admin memiliki hak akses penuh terhadap sistem, meliputi: 

- menambah data, 

- melihat data, 

- mengubah data, 

- menghapus data (CRUD), 

- serta mengelola laporan. 

## **2. Guru** 

Guru bertugas: 

- menginput nilai siswa, 

- melihat rekap nilai, 

- memvalidasi nilai siswa sesuai mata pelajaran. 

## **3. Siswa** 

Siswa dapat: 

- melihat nilai pribadi, 

- melihat status kelulusan. 

## **C. KETENTUAN SISTEM** 

1. Data siswa minimal terdiri dari: 

   - NIS (unik), 

   - Nama, 

   - Kelas, 

   - Nilai Tugas, 

   - Nilai UTS, 

   - Nilai UAS. 

2. Data guru minimal terdiri dari: 

   - ID Guru, 

   - Nama Guru, 

   - • Mata Pelajaran. 

Perhitungan nilai akhir menggunakan rumus: 

𝑁𝑖𝑙𝑎𝑖 𝐴𝑘ℎ𝑖𝑟= (30% × 𝑇𝑢𝑔𝑎𝑠) + (30% × 𝑈𝑇𝑆) + (40% × 𝑈𝐴𝑆) 

Ketentuan nilai: 

- Rentang nilai valid: 0–100 

- Siswa dinyatakan lulus jika nilai akhir ≥ 70 

## **TUGAS 1 — ANALISIS DAN PERANCANGAN SISTEM** 

Lakukan analisis kebutuhan sistem berdasarkan studi kasus yang diberikan, kemudian buat rancangan aplikasi yang akan dikembangkan. 

Dokumen rancangan minimal mencakup: 

1. Tujuan sistem 

2. Analisis kebutuhan pengguna 

3. Fungsi utama sistem 

4. Spesifikasi fungsional dan nonfungsional 

5. Alur kerja sistem 

6. Rancangan antarmuka (UI) 

7. Rancangan database 

8. Batasan sistem 

Selain itu, pada tahap ini anda juga harus menentukan: 

- minimal 3 fungsi/prosedur sebagai implementasi pemrograman terstruktur, 

- • minimal 2 class beserta method sebagai implementasi OOP. 

## **Output Tugas 1** 

1. Dokumen spesifikasi program 

2. Flowchart atau UML sistem 

3. Rancangan database 

4. Desain antarmuka aplikasi 

5. Koneksi database berhasil 

6. Rancangan fungsi/procedure dan class/method 

## **TUGAS 2 — IMPLEMENTASI PROGRAM** 

Berdasarkan hasil rancangan yang telah dibuat, lakukan proses implementasi aplikasi menggunakan bahasa pemrograman yang telah ditentukan. 

Dalam proses implementasi: 

- kode program harus mengikuti coding guidelines dan best practices, 

- aplikasi harus terhubung dengan database, 

- serta mampu menunjukkan implementasi pemrograman terstruktur dan OOP. 

Selain itu, selama proses pengembangan, lakukan debugging terhadap error yang muncul pada program. 

## **Output Tugas 2** 

1. Halaman login berdasarkan role 

2. Form input data siswa dan nilai 

3. Proses perhitungan nilai akhir 

4. Laporan hasil nilai siswa 

5. Bukti pengujian database 

6. Catatan error/debugging 

7. Perbaikan error dan hasil setelah diperbaiki 

8. Potongan kode fungsi/procedure 

9. Potongan kode class dan method 

10. Penjelasan library atau komponen yang digunakan 

11. Penjelasan coding guidelines dan best practices 

## **TUGAS 3 — PENGUJIAN DAN DOKUMENTASI PROGRAM** 

Setelah aplikasi selesai dibuat, lakukan pengujian aplikasi untuk memastikan seluruh fitur berjalan sesuai kebutuhan sistem. 

Pengujian dilakukan terhadap: 

- proses login, 

- input data, 

- validasi nilai, 

- perhitungan nilai akhir, 

- laporan, 

- dan koneksi database. 

Dokumentasikan seluruh proses pengujian beserta hasil yang diperoleh. 

## **Output Tugas 3** 

1. Dokumentasi tahapan pengujian aplikasi 

2. Skenario dan test case pengujian 

3. Hasil pengujian aplikasi 

4. Bukti pengujian berhasil/gagal 

5. Dokumentasi debugging 

6. Dokumentasi kode program 

7. Penjelasan fungsi, modul, dan class 

8. Evaluasi hasil pengujian aplikasi 

Aplikasi yang telah selesai dibuat akan dipresentasikan di akhir sesi pembekalan dan divalidasi oleh instruktur sebagai bagian dari persiapan menghadapi Uji Kompetensi LSP Skema Programmer dengan mendownload surat keterangan dari aplikasi. 

