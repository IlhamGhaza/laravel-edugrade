<x-filament-panels::page>
    {{-- Custom Styles --}}
    <style>
        /* ========== Stat Cards ========== */
        .laporan-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            position: relative;
            overflow: hidden;
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            color: #fff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .stat-card-bg {
            position: absolute;
            top: -20px;
            right: -20px;
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
        }
        .stat-card-bg-2 {
            position: absolute;
            bottom: -30px;
            left: -15px;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }
        .stat-card .stat-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border-radius: 0.75rem;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(6px);
            margin-bottom: 0.75rem;
        }
        .stat-card .stat-icon svg {
            width: 22px;
            height: 22px;
        }
        .stat-card .stat-value {
            font-size: 1.75rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -0.02em;
        }
        .stat-card .stat-label {
            font-size: 0.8rem;
            font-weight: 500;
            margin-top: 0.35rem;
            opacity: 0.85;
            letter-spacing: 0.02em;
        }

        .stat-total { background: linear-gradient(135deg, #6366f1, #818cf8); }
        .stat-rata  { background: linear-gradient(135deg, #3b82f6, #60a5fa); }
        .stat-lulus { background: linear-gradient(135deg, #10b981, #34d399); }
        .stat-persen { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
        .stat-persen.high { background: linear-gradient(135deg, #10b981, #34d399); }

        /* Progress bar inside stat card */
        .stat-progress-bar {
            margin-top: 0.75rem;
            height: 6px;
            border-radius: 999px;
            background: rgba(255,255,255,0.2);
            overflow: hidden;
        }
        .stat-progress-fill {
            height: 100%;
            border-radius: 999px;
            background: rgba(255,255,255,0.7);
            transition: width 0.8s ease;
        }

        /* ========== Table Card ========== */
        .laporan-table-card {
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.03);
            background: #fff;
            border: 1px solid rgba(0,0,0,0.05);
        }
        .dark .laporan-table-card {
            background: rgb(24, 24, 27);
            border: 1px solid rgba(255,255,255,0.07);
        }

        .laporan-table-header {
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            border-bottom: 1px solid rgba(0,0,0,0.06);
        }
        .dark .laporan-table-header {
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        .laporan-table-header h3 {
            font-size: 1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #18181b;
        }
        .dark .laporan-table-header h3 {
            color: #f4f4f5;
        }
        .laporan-table-header .record-count {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.2rem 0.6rem;
            border-radius: 999px;
            background: rgba(99,102,241,0.1);
            color: #6366f1;
        }

        /* Table */
        .laporan-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }
        .laporan-table thead th {
            padding: 0.75rem 1rem;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
            text-align: left;
            background: #fafafa;
            color: #71717a;
            border-bottom: 1px solid rgba(0,0,0,0.06);
        }
        .dark .laporan-table thead th {
            background: rgba(255,255,255,0.03);
            color: #a1a1aa;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }

        .laporan-table tbody td {
            padding: 0.75rem 1rem;
            white-space: nowrap;
            vertical-align: middle;
            color: #3f3f46;
            border-bottom: 1px solid rgba(0,0,0,0.04);
        }
        .dark .laporan-table tbody td {
            color: #d4d4d8;
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }

        .laporan-table tbody tr {
            transition: background 0.15s ease;
        }
        .laporan-table tbody tr:hover td {
            background: #f9fafb;
        }
        .dark .laporan-table tbody tr:hover td {
            background: rgba(255,255,255,0.03);
        }

        /* Numeric columns */
        .laporan-table .col-num {
            text-align: center;
            font-variant-numeric: tabular-nums;
            font-family: ui-monospace, SFMono-Regular, 'Cascadia Code', 'Consolas', monospace;
            font-size: 0.82rem;
        }

        /* Nama siswa emphasis */
        .laporan-table .col-nama {
            font-weight: 600;
            color: #18181b;
        }
        .dark .laporan-table .col-nama { color: #f4f4f5; }

        .laporan-table .col-no {
            text-align: center;
            font-weight: 500;
            width: 50px;
            color: #a1a1aa;
        }
        .dark .laporan-table .col-no { color: #71717a; }

        /* NIS mono */
        .laporan-table .col-nis {
            font-family: ui-monospace, SFMono-Regular, 'Cascadia Code', 'Consolas', monospace;
            font-size: 0.82rem;
        }

        /* Guru column */
        .laporan-table .col-guru {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .guru-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.7rem;
            color: #fff;
            flex-shrink: 0;
            background: linear-gradient(135deg, #6366f1, #818cf8);
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.2rem 0.6rem;
            border-radius: 0.375rem;
            font-size: 0.72rem;
            font-weight: 600;
            white-space: nowrap;
        }
        .badge-kelas {
            background: rgba(59,130,246,0.1);
            color: #3b82f6;
        }
        .badge-ganjil {
            background: rgba(99,102,241,0.1);
            color: #6366f1;
        }
        .badge-genap {
            background: rgba(245,158,11,0.1);
            color: #d97706;
        }
        .badge-mapel {
            background: rgba(16,185,129,0.1);
            color: #059669;
        }
        .badge-lulus {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.25rem 0.7rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            background: rgba(16,185,129,0.12);
            color: #059669;
        }
        .badge-tidak-lulus {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.25rem 0.7rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            background: rgba(239,68,68,0.12);
            color: #dc2626;
        }

        /* Nilai akhir highlight */
        .nilai-akhir {
            font-weight: 800;
            font-size: 0.9rem;
        }
        .nilai-akhir.tinggi { color: #059669; }
        .nilai-akhir.rendah { color: #dc2626; }

        /* Empty state */
        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
        }
        .empty-state-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1rem;
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f4f4f5;
            color: #a1a1aa;
        }
        .dark .empty-state-icon {
            background: rgba(255,255,255,0.05);
            color: #52525b;
        }
        .empty-state-icon svg {
            width: 32px;
            height: 32px;
        }
        .empty-state-title {
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.25rem;
            color: #3f3f46;
        }
        .dark .empty-state-title { color: #d4d4d8; }
        .empty-state-desc {
            font-size: 0.82rem;
            color: #a1a1aa;
        }
        .dark .empty-state-desc { color: #71717a; }

        /* Responsive table wrapper */
        .table-scroll-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    </style>

    {{-- ===== Statistik Cards ===== --}}
    <div class="laporan-stats-grid">
        {{-- Total Data --}}
        <div class="stat-card stat-total">
            <div class="stat-card-bg"></div>
            <div class="stat-card-bg-2"></div>
            <div class="stat-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div class="stat-value">{{ $totalData }}</div>
            <div class="stat-label">Total Data Nilai</div>
        </div>

        {{-- Rata-rata Nilai --}}
        <div class="stat-card stat-rata">
            <div class="stat-card-bg"></div>
            <div class="stat-card-bg-2"></div>
            <div class="stat-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                </svg>
            </div>
            <div class="stat-value">{{ $rataRata }}</div>
            <div class="stat-label">Rata-rata Nilai</div>
        </div>

        {{-- Siswa Lulus --}}
        {{-- <div class="stat-card stat-lulus">
            <div class="stat-card-bg"></div>
            <div class="stat-card-bg-2"></div>
            <div class="stat-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="stat-value">{{ $totalLulus }}</div>
            <div class="stat-label">Siswa Lulus</div>
        </div> --}}
        {{-- Persentase Lulus --}}
        {{-- <div class="stat-card stat-persen {{ $persenLulus >= 70 ? 'high' : '' }}">
            <div class="stat-card-bg"></div>
            <div class="stat-card-bg-2"></div>
            <div class="stat-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            </div>
            <div class="stat-value">{{ $persenLulus }}%</div>
            <div class="stat-label">Persentase Lulus</div>
            <div class="stat-progress-bar">
                <div class="stat-progress-fill" style="width: {{ $persenLulus }}%"></div>
            </div>
        </div> --}}
    </div>

    {{-- ===== Filter Section (Admin Only) ===== --}}
    @if(auth()->user()->hasRole(['admin', 'super_admin']))
    <div class="laporan-table-card" style="margin-bottom: 1.5rem; padding: 1.5rem;">
        <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end;">
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.5rem; color: #3f3f46;" class="dark:text-zinc-300">Cari Nama Siswa</label>
                <input type="text" wire:model.live.debounce.500ms="searchSiswa" placeholder="Ketik nama siswa..." style="width: 100%; padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid #d4d4d8; font-size: 0.85rem;" class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
            </div>

            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.5rem; color: #3f3f46;" class="dark:text-zinc-300">Kelas</label>
                <select wire:model.live="filterKelas" style="width: 100%; padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid #d4d4d8; font-size: 0.85rem;" class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $id => $namaKelas)
                        <option value="{{ $id }}">{{ $namaKelas }}</option>
                    @endforeach
                </select>
            </div>

            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.5rem; color: #3f3f46;" class="dark:text-zinc-300">Mata Pelajaran</label>
                <select wire:model.live="filterMapel" style="width: 100%; padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid #d4d4d8; font-size: 0.85rem;" class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
                    <option value="">Semua Mapel</option>
                    @foreach($mataPelajarans as $mapel)
                        <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
                    @endforeach
                </select>
            </div>

            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.5rem; color: #3f3f46;" class="dark:text-zinc-300">Semester</label>
                <select wire:model.live="filterSemester" style="width: 100%; padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid #d4d4d8; font-size: 0.85rem;" class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
                    <option value="">Semua Semester</option>
                    <option value="Ganjil">Ganjil</option>
                    <option value="Genap">Genap</option>
                </select>
            </div>

            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; font-size: 0.8rem; font-weight: 600; margin-bottom: 0.5rem; color: #3f3f46;" class="dark:text-zinc-300">Status</label>
                <select wire:model.live="filterStatus" style="width: 100%; padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid #d4d4d8; font-size: 0.85rem;" class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white">
                    <option value="">Semua Status</option>
                    <option value="Lulus">Lulus</option>
                    <option value="Tidak Lulus">Tidak Lulus</option>
                </select>
            </div>
            
            <div style="display: flex; gap: 0.5rem;">
                <button wire:click="resetFilters" style="background-color: #f4f4f5; color: #3f3f46; padding: 0.5rem 1rem; border-radius: 0.5rem; border: 1px solid #d4d4d8; font-weight: 600; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; height: 38px;" class="dark:bg-zinc-800 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-700">
                    <svg style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reset
                </button>

                <a href="{{ route('export.laporan.nilai', ['searchSiswa' => $searchSiswa, 'filterKelas' => $filterKelas, 'filterMapel' => $filterMapel, 'filterSemester' => $filterSemester, 'filterStatus' => $filterStatus]) }}" style="background-color: #10b981; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; border: none; font-weight: 600; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; height: 38px; text-decoration: none;">
                    <svg style="width:16px;height:16px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export Excel
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- ===== Tabel Laporan Nilai ===== --}}
    <div class="laporan-table-card">
        <div class="laporan-table-header">
            <h3>
                <svg style="width:20px;height:20px;color:#6366f1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                Detail Nilai
            </h3>
            <span class="record-count">{{ $totalData }} data</span>
        </div>

        <div class="table-scroll-wrapper">
            <table class="laporan-table">
                <thead>
                    <tr>
                        <th style="text-align:center">No</th>
                        <th>NIS</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Semester</th>
                        <th>Guru</th>
                        <th>Mata Pelajaran</th>
                        <th style="text-align:center">Tugas</th>
                        <th style="text-align:center">UTS</th>
                        <th style="text-align:center">UAS</th>
                        <th style="text-align:center">Nilai Akhir</th>
                        <th style="text-align:center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nilais as $index => $nilai)
                    <tr>
                        <td class="col-no">{{ $index + 1 }}</td>
                        <td class="col-nis">{{ $nilai->siswa->nis ?? '-' }}</td>
                        <td class="col-nama">{{ $nilai->siswa->nama ?? '-' }}</td>
                        <td>
                            <span class="badge badge-kelas">
                                {{ $nilai->siswa->kelas->nama_kelas ?? '-' }}
                            </span>
                        </td>
                        <td>
                            @if($nilai->semester)
                                <span class="badge {{ $nilai->semester === 'Ganjil' ? 'badge-ganjil' : 'badge-genap' }}">
                                    {{ $nilai->semester }}
                                </span>
                            @else
                                <span style="color:#a1a1aa">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="col-guru">
                                <div class="guru-avatar">
                                    {{ strtoupper(substr($nilai->guru->nama_guru ?? 'G', 0, 1)) }}
                                </div>
                                <span>{{ $nilai->guru->nama_guru ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-mapel">
                                {{ $nilai->mataPelajaran->nama_mapel ?? '-' }}
                            </span>
                        </td>
                        <td class="col-num">{{ number_format($nilai->nilai_tugas, 1) }}</td>
                        <td class="col-num">{{ number_format($nilai->nilai_uts, 1) }}</td>
                        <td class="col-num">{{ number_format($nilai->nilai_uas, 1) }}</td>
                        <td class="col-num">
                            <span class="nilai-akhir {{ $nilai->nilai_akhir >= 70 ? 'tinggi' : 'rendah' }}">
                                {{ number_format($nilai->nilai_akhir, 1) }}
                            </span>
                        </td>
                        <td style="text-align:center">
                            @if($nilai->status === 'Lulus')
                                <span class="badge-lulus">
                                    <svg style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Lulus
                                </span>
                            @else
                                <span class="badge-tidak-lulus">
                                    <svg style="width:12px;height:12px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Tidak Lulus
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                </div>
                                <div class="empty-state-title">Belum ada data nilai</div>
                                <div class="empty-state-desc">Data nilai akan muncul setelah guru menginput nilai siswa.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
