<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

/**
 * RoleSeeder — Membuat role admin, guru, siswa beserta permission yang sesuai.
 *
 * Permission format mengikuti Filament Shield convention: "Action:Model"
 * Contoh: "ViewAny:Siswa", "Create:Nilai", "View:LaporanNilai"
 *
 * Hak akses:
 * - Admin: semua permission (CRUD semua resource)
 * - Guru: CRUD Nilai + View Siswa + View LaporanNilai
 * - Siswa: View LaporanNilai saja
 */
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan role ada
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $guru = Role::firstOrCreate(['name' => 'guru', 'guard_name' => 'web']);
        $siswa = Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);

        // =====================================================================
        //  Admin — Akses penuh ke semua resource
        // =====================================================================
        $allPermissions = Permission::all();
        $admin->syncPermissions($allPermissions);

        // =====================================================================
        //  Guru — CRUD Nilai + View Siswa + Laporan Nilai
        // =====================================================================
        $guruPermissionNames = [
            // Nilai: full CRUD
            'ViewAny:Nilai', 'View:Nilai', 'Create:Nilai', 'Update:Nilai', 'Delete:Nilai',
            // Siswa: view only (untuk select di form Nilai)
            'ViewAny:Siswa', 'View:Siswa',
            // Guru: view own profile
            'ViewAny:Guru', 'View:Guru',
            // Laporan Nilai page
            'View:LaporanNilai',
        ];

        $guruPermissions = Permission::whereIn('name', $guruPermissionNames)->get();
        $guru->syncPermissions($guruPermissions);

        // =====================================================================
        //  Siswa — Hanya view laporan nilai pribadi
        // =====================================================================
        $siswaPermissionNames = [
            'View:LaporanNilai',
        ];

        $siswaPermissions = Permission::whereIn('name', $siswaPermissionNames)->get();
        $siswa->syncPermissions($siswaPermissions);
    }
}
