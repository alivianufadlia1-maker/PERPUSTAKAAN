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
        .anggota {
            border: 1px solid #000;
            padding: 8px 10px;
            margin-bottom: 16px;
            font-size: 10px;
        }
        .anggota strong {
            font-size: 13px;
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
        .denda {
            font-weight: bold;
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
    <h1>Riwayat Peminjaman - Perpustakaan</h1>
    <div class="meta">Tanggal cetak: <?= esc($tanggalCetak); ?></div>

    <div class="anggota">
        <strong><?= esc($anggota['nama']); ?></strong><br>
        Email: <?= esc($anggota['email']); ?><br>
        No. Telepon: <?= empty($anggota['no_telp']) ? '-' : esc($anggota['no_telp']); ?><br>
        Alamat: <?= empty($anggota['alamat']) ? '-' : esc($anggota['alamat']); ?><br>
        Tanggal Daftar: <?= empty($anggota['tanggal_daftar']) ? '-' : esc($anggota['tanggal_daftar']); ?>
    </div>

    <div class="summary">
        <table>
            <tr>
                <td><div class="angka"><?= (int) $totalPinjam; ?></div>Total Peminjaman</td>
                <td><div class="angka"><?= (int) $sedangDipinjam; ?></div>Sedang Dipinjam</td>
                <td><div class="angka"><?= (int) $terlambat; ?></div>Terlambat</td>
                <td><div class="angka"><?= format_rupiah($totalDenda); ?></div>Denda Belum Dibayar</td>
            </tr>
        </table>
    </div>

    <?php if (empty($peminjaman)): ?>
        <div class="empty">Anggota ini belum memiliki riwayat peminjaman.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>Judul Buku</th>
                    <th style="width: 12%;">Tanggal Pinjam</th>
                    <th style="width: 14%;">Tanggal Kembali</th>
                    <th style="width: 12%;">Status</th>
                    <th style="width: 12%;">Denda</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($peminjaman as $i => $p): ?>
                    <tr>
                        <td><?= $i + 1; ?></td>
                        <td><?= esc($p['judul']); ?></td>
                        <td><?= esc($p['tanggal_pinjam']); ?></td>
                        <td><?= empty($p['tanggal_kembali']) ? '-' : esc($p['tanggal_kembali']); ?></td>
                        <td><?= $p['status_tampil'] === 'dikembalikan' ? 'Dikembalikan' : ($p['status_tampil'] === 'terlambat' ? 'Terlambat' : 'Dipinjam'); ?></td>
                        <td class="denda"><?= (int) $p['denda'] > 0 ? format_rupiah($p['denda']) : '-'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="note">Dokumen ini dihasilkan otomatis oleh sistem perpustakaan pada <?= esc($tanggalCetak); ?>.</div>
</body>
</html>
