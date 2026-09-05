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
        .summary .kecil {
            font-size: 10px;
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
    <h1>Rekap Statistik Peminjaman Buku - Perpustakaan</h1>
    <div class="meta">Tanggal cetak: <?= esc($tanggalCetak); ?></div>

    <div class="summary">
        <table>
            <tr>
                <td>
                    <div class="angka"><?= (int) $totalBuku; ?></div>
                    Total Buku
                </td>
                <td>
                    <?php if ($bukuTerpopuler !== null && (int) $bukuTerpopuler['total_dipinjam'] > 0): ?>
                        <div class="kecil">Paling Sering Dipinjam:</div>
                        <div class="angka" style="font-size: 12px;"><?= esc($bukuTerpopuler['judul']); ?></div>
                        <div class="kecil"><?= (int) $bukuTerpopuler['total_dipinjam']; ?>x dipinjam</div>
                    <?php else: ?>
                        <div class="kecil">Paling Sering Dipinjam</div>
                        <div class="kecil">Belum ada data peminjaman</div>
                    <?php endif; ?>
                </td>
                <td>
                    <div class="angka"><?= (int) $jumlahBelumDipinjam; ?></div>
                    Belum Pernah Dipinjam
                </td>
                <td>
                    <div class="angka"><?= (int) $jumlahTerlambat; ?></div>
                    Total Keterlambatan
                </td>
            </tr>
        </table>
    </div>

    <?php if (empty($statistik)): ?>
        <div class="empty">Belum ada buku yang terdaftar.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>Judul Buku</th>
                    <th>Pengarang</th>
                    <th class="c" style="width: 10%;">Total Dipinjam</th>
                    <th class="c" style="width: 11%;">Sedang Dipinjam</th>
                    <th class="c" style="width: 9%;">Terlambat</th>
                    <th style="width: 14%;">Terakhir Dipinjam</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($statistik as $i => $s): ?>
                    <tr>
                        <td><?= $i + 1; ?></td>
                        <td><?= esc($s['judul']); ?></td>
                        <td><?= esc($s['pengarang']); ?></td>
                        <td class="c"><?= (int) $s['total_dipinjam']; ?></td>
                        <td class="c"><?= (int) $s['sedang_dipinjam']; ?></td>
                        <td class="c"><?= (int) $s['jumlah_terlambat']; ?></td>
                        <td><?= empty($s['terakhir_dipinjam']) ? '-' : esc($s['terakhir_dipinjam']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="note">Dokumen ini dihasilkan otomatis oleh sistem perpustakaan pada <?= esc($tanggalCetak); ?>.</div>
</body>
</html>
