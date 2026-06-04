<x-filament-panels::page>
    {{-- Statistik Summary --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-4 mb-6">
        <div class="fi-ta-content bg-white dark:bg-gray-900 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 p-4 text-center">
            <div class="text-2xl font-bold text-primary-600">{{ $totalData }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Total Data Nilai</div>
        </div>
        <div class="fi-ta-content bg-white dark:bg-gray-900 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ $rataRata }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Rata-rata Nilai</div>
        </div>
        <div class="fi-ta-content bg-white dark:bg-gray-900 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 p-4 text-center">
            <div class="text-2xl font-bold text-green-600">{{ $totalLulus }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Siswa Lulus</div>
        </div>
        <div class="fi-ta-content bg-white dark:bg-gray-900 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 p-4 text-center">
            <div class="text-2xl font-bold {{ $persenLulus >= 70 ? 'text-green-600' : 'text-red-600' }}">{{ $persenLulus }}%</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Persentase Lulus</div>
        </div>
    </div>

    {{-- Tabel Laporan Nilai --}}
    <div class="fi-ta-content bg-white dark:bg-gray-900 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-800">
                        <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">No</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">NIS</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Siswa</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Kelas</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Guru</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400">Mata Pelajaran</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 text-center">Tugas</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 text-center">UTS</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 text-center">UAS</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 text-center">Nilai Akhir</th>
                        <th class="px-4 py-3 text-sm font-medium text-gray-500 dark:text-gray-400 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($nilais as $index => $nilai)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 text-sm font-mono text-gray-600 dark:text-gray-300">{{ $nilai->siswa->nis ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $nilai->siswa->nama ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400">
                                {{ $nilai->siswa->kelas ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $nilai->guru->nama_guru ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400">
                                {{ $nilai->guru->mata_pelajaran ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-center tabular-nums">{{ $nilai->nilai_tugas }}</td>
                        <td class="px-4 py-3 text-sm text-center tabular-nums">{{ $nilai->nilai_uts }}</td>
                        <td class="px-4 py-3 text-sm text-center tabular-nums">{{ $nilai->nilai_uas }}</td>
                        <td class="px-4 py-3 text-sm text-center font-bold tabular-nums text-gray-900 dark:text-white">{{ $nilai->nilai_akhir }}</td>
                        <td class="px-4 py-3 text-sm text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $nilai->status === 'Lulus' ? 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400' : 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400' }}">
                                {{ $nilai->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="px-4 py-8 text-sm text-center text-gray-500">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Tidak ada data nilai.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
