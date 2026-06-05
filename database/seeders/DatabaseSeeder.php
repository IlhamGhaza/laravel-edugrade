<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * DatabaseSeeder — Seeder utama untuk EduGrade.
 *
 * Urutan seed:
 * 1. ShieldSeeder  → membuat permissions & role super_admin
 * 2. RoleSeeder    → membuat role admin, guru, siswa + assign permissions
 * 3. Mata Pelajaran → master data mapel
 * 4. Demo users    → user beserta profil siswa/guru
 * 5. Pivot guru-mapel → relasi guru ↔ mapel
 * 6. Demo nilai    → data nilai sample
 */
class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        // =====================================================================
        //  1. Seed roles & permissions via Shield
        // =====================================================================
        $this->call([
            ShieldSeeder::class,
            RoleSeeder::class,
        ]);

        // =====================================================================
        //  2. Create master data Mata Pelajaran
        // =====================================================================
        $mapelMtk = MataPelajaran::create([
            'kode_mapel' => 'MTK',
            'nama_mapel' => 'Matematika',
        ]);

        $mapelBin = MataPelajaran::create([
            'kode_mapel' => 'BIN',
            'nama_mapel' => 'Bahasa Indonesia',
        ]);

        $mapelIpa = MataPelajaran::create([
            'kode_mapel' => 'IPA',
            'nama_mapel' => 'Ilmu Pengetahuan Alam',
        ]);

        // =====================================================================
        //  3. Create demo users
        // =====================================================================

        // Super Admin
        $superAdmin = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'ilham@admin.com',
            'password' => Hash::make('12345678'),
        ]);
        $superAdmin->assignRole('super_admin');

        // Guru 1 — Matematika & IPA
        $guruUser1 = User::factory()->create([
            'name' => 'Budi Santoso',
            'email' => 'guru1@edugrade.com',
            'password' => Hash::make('password'),
        ]);
        $guruUser1->assignRole('guru');

        $guru1 = Guru::create([
            'user_id' => $guruUser1->id,
            'id_guru' => 'GR-001',
            'nama_guru' => 'Budi Santoso',
        ]);
        // Attach mapel ke guru (many-to-many)
        $guru1->mataPelajarans()->attach([$mapelMtk->id, $mapelIpa->id]);

        // Guru 2 — Bahasa Indonesia
        $guruUser2 = User::factory()->create([
            'name' => 'Siti Nurhaliza',
            'email' => 'guru2@edugrade.com',
            'password' => Hash::make('password'),
        ]);
        $guruUser2->assignRole('guru');

        $guru2 = Guru::create([
            'user_id' => $guruUser2->id,
            'id_guru' => 'GR-002',
            'nama_guru' => 'Siti Nurhaliza',
        ]);
        // Attach mapel ke guru (many-to-many)
        $guru2->mataPelajarans()->attach([$mapelBin->id]);

        // Siswa 1
        $siswaUser1 = User::factory()->create([
            'name' => 'Andi Prasetyo',
            'email' => 'siswa1@edugrade.com',
            'password' => Hash::make('password'),
        ]);
        $siswaUser1->assignRole('siswa');

        $siswa1 = Siswa::create([
            'user_id' => $siswaUser1->id,
            'nis' => '2024001',
            'nama' => 'Andi Prasetyo',
            'kelas' => 'XII-IPA-1',
        ]);

        // Siswa 2
        $siswaUser2 = User::factory()->create([
            'name' => 'Citra Dewi',
            'email' => 'siswa2@edugrade.com',
            'password' => Hash::make('password'),
        ]);
        $siswaUser2->assignRole('siswa');

        $siswa2 = Siswa::create([
            'user_id' => $siswaUser2->id,
            'nis' => '2024002',
            'nama' => 'Citra Dewi',
            'kelas' => 'XII-IPA-1',
        ]);

        // Siswa 3
        $siswaUser3 = User::factory()->create([
            'name' => 'Rizki Ramadhan',
            'email' => 'siswa3@edugrade.com',
            'password' => Hash::make('password'),
        ]);
        $siswaUser3->assignRole('siswa');

        $siswa3 = Siswa::create([
            'user_id' => $siswaUser3->id,
            'nis' => '2024003',
            'nama' => 'Rizki Ramadhan',
            'kelas' => 'XII-IPS-1',
        ]);

        // =====================================================================
        //  4. Create demo nilai
        //     Menggunakan model event 'saving' yang akan auto-hitung nilai_akhir & status
        // =====================================================================

        // Nilai Matematika (Guru 1, Mapel MTK)
        Nilai::create([
            'siswa_id' => $siswa1->id,
            'guru_id' => $guru1->id,
            'mapel_id' => $mapelMtk->id,
            'semester' => 'Ganjil',
            'nilai_tugas' => 85,
            'nilai_uts' => 78,
            'nilai_uas' => 82,
        ]);

        Nilai::create([
            'siswa_id' => $siswa2->id,
            'guru_id' => $guru1->id,
            'mapel_id' => $mapelMtk->id,
            'semester' => 'Ganjil',
            'nilai_tugas' => 92,
            'nilai_uts' => 88,
            'nilai_uas' => 95,
        ]);

        Nilai::create([
            'siswa_id' => $siswa3->id,
            'guru_id' => $guru1->id,
            'mapel_id' => $mapelMtk->id,
            'semester' => 'Genap',
            'nilai_tugas' => 60,
            'nilai_uts' => 55,
            'nilai_uas' => 65,
        ]);

        // Nilai Bahasa Indonesia (Guru 2, Mapel BIN)
        Nilai::create([
            'siswa_id' => $siswa1->id,
            'guru_id' => $guru2->id,
            'mapel_id' => $mapelBin->id,
            'semester' => 'Ganjil',
            'nilai_tugas' => 78,
            'nilai_uts' => 82,
            'nilai_uas' => 80,
        ]);

        Nilai::create([
            'siswa_id' => $siswa2->id,
            'guru_id' => $guru2->id,
            'mapel_id' => $mapelBin->id,
            'semester' => 'Genap',
            'nilai_tugas' => 90,
            'nilai_uts' => 85,
            'nilai_uas' => 88,
        ]);

        Nilai::create([
            'siswa_id' => $siswa3->id,
            'guru_id' => $guru2->id,
            'mapel_id' => $mapelBin->id,
            'semester' => 'Genap',
            'nilai_tugas' => 70,
            'nilai_uts' => 65,
            'nilai_uas' => 72,
        ]);

        $this->command->info('✅ Demo data berhasil dibuat!');
        $this->command->info('');
        $this->command->table(
            ['Email', 'Password', 'Role'],
            [
                ['admin@edugrade.com', 'password', 'super_admin'],
                ['guru1@edugrade.com', 'password', 'guru'],
                ['guru2@edugrade.com', 'password', 'guru'],
                ['siswa1@edugrade.com', 'password', 'siswa'],
                ['siswa2@edugrade.com', 'password', 'siswa'],
                ['siswa3@edugrade.com', 'password', 'siswa'],
            ]
        );
    }
}
