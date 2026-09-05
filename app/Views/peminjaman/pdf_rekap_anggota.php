<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000;
        }
        h1 {
            font-size: 18px;
            margin: 0 0 4px;
        }
        .meta {
            font-size: 10px;
            color: #333;
            margin-bottom: 16px;
        }
        .summary {
            margin-bottom: 16px;
        }
        .summary table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: center;
            font-size: 10px;
        }
        .summary .angka {
            font-size: 15px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px 7px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background: #eee;
            font-weight: bold;
        }
        td.c, th.c {
            text-align: center;
        }
        .note {
            font-size: 9px;
            color: #555;
            margin-top: 16px;
        }
        .empty {
            padding: 20px 0;
            text-align: center;
            color: #333;
        }
    </style>
</head>
<body>
    <h1>Rekap Peminjaman Seluruh Anggota - Perpustakaan</h1>
    <div class="meta">Tanggal cetak: <?= esc($tanggalCetak); ?></div>

    <div class="summary">
        <table>
            <tr>
                <td><div class="angka"><?= (int) $totalAnggota; ?></div>Total Anggota</td>
                <td><div class="angka"><?= (int) $totalPinjam; ?></div>Total Peminjaman</td>
                <td><div class="angka"><?= (int) $totalAktif; ?></div>Sedang Dipinjam</td>
                <td><div class="angka"><?= (int) $totalTerlambat; ?></div>Terlambat</td>
            </tr>
        </table>
    </div>

    <?php if (empty($statistik)): ?>
        <div class="empty">Belum ada anggota yang terdaftar.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>Nama Anggota</th>
                    <th class="c" style="width: 12%;">Total Pinjam</th>
                    <th class="c" style="width: 12%;">Sedang Dipinjam</th>
                    <th class="c" style="width: 10%;">Terlambat</th>
                    <th style="width: 15%;">Terakhir Pinjam</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($statistik as $i => $s): ?>
                    <tr>
                        <td><?= $i + 1; ?></td>
                        <td><?= esc($s['nama']); ?></td>
                        <td class="c"><?= (int) $s['total_pinjam']; ?></td>
                        <td class="c"><?= (int) $s['aktif']; ?></td>
                        <td class="c"><?= (int) $s['terlambat']; ?></td>
                        <td><?= empty($s['terakhir_pinjam']) ? '-' : esc($s['terakhir_pinjam']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="note">Dokumen ini dihasilkan otomatis oleh sistem perpustakaan pada <?= esc($tanggalCetak); ?>.</div>
</body>
</html>
