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
        .total {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 4px;
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
    <h1>Log Pembayaran Denda - Perpustakaan</h1>
    <div class="meta">
        Tanggal cetak: <?= esc($tanggalCetak); ?><br>
        Total transaksi: <?= esc($jumlahTransaksi); ?>
        | Total denda terbayar: <?= format_rupiah($totalTerbayar); ?>
        <?php if (! empty($cari) || ! empty($filterJalur)): ?>
            <br>Filter aktif: <?= esc(implode(' & ', array_filter([
                ! empty($cari) ? 'cari "' . $cari . '"' : null,
                $filterJalur === 'admin' ? 'jalur: Lewat Admin' : ($filterJalur === 'mandiri' ? 'jalur: Mandiri (Anggota)' : null),
            ]))); ?>
        <?php endif; ?>
    </div>

    <?php if (empty($pembayaran)): ?>
        <div class="empty">Tidak ada pembayaran yang sesuai dengan filter.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>Nama Anggota</th>
                    <th>Judul Buku</th>
                    <th style="width: 14%;">Nominal Denda</th>
                    <th style="width: 16%;">Tanggal Bayar</th>
                    <th style="width: 13%;">Jalur Bayar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pembayaran as $i => $r): ?>
                    <tr>
                        <td><?= $i + 1; ?></td>
                        <td><?= esc($r['nama']); ?></td>
                        <td><?= esc($r['judul']); ?></td>
                        <td><?= format_rupiah($r['denda']); ?></td>
                        <td><?= empty($r['tanggal_bayar']) ? '-' : esc(date('d M Y H:i', strtotime($r['tanggal_bayar']))); ?></td>
                        <td><?= match ($r['dibayar_oleh'] ?? null) {
                            'admin'   => 'Admin',
                            'mandiri' => 'Mandiri',
                            default   => '-',
                        }; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="total">Total Denda Terbayar: <?= format_rupiah($totalTerbayar); ?></div>
    <?php endif; ?>

    <div class="note">Dokumen ini dihasilkan otomatis oleh sistem perpustakaan pada <?= esc($tanggalCetak); ?>.</div>
</body>
</html>