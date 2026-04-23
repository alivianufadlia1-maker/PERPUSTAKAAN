<?= $this->extend('layout/header'); ?>
<?= $this->section('content'); ?>

<div class="container">
    <div class="row">
        <div class="col">
            <h1>Daftar Buku</h1>
            <form action="" method="get">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Masukan Pencarian Data Buku"
                     name="cari">
                    <button class="btn btn-outline-secondary" type="submit" name="submit">Cari</button>
                    </div>
            </form>

            <?php if (session()->getFlashdata('pesan')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('pesan'); ?>
                </div>
            <?php endif; ?>

            <a href="/buku/tambah" class="btn btn-primary mb-3">+ Tambah Buku</a>

            <table class="table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Sampul</th>
                        <th>Judul</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                   <?php 
                        $i = 1 + (2 * ((int)$current - 1)); 
                    ?>
                    <?php foreach ($buku as $b): ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><img src="/img/<?= $b['sampul']; ?>" alt="" width="75"></td>
                            <td><?= $b['judul']; ?></td>
                            <td>
                                <a href="/buku/<?= $b['id_buku']; ?>" class="btn btn-success">Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?=$pager->links('buku', 'page_buku');?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>