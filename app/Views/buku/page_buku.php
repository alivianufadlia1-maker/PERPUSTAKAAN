<?php $pager->setSurroundCount(2); ?>

<?php if ($pager->getPageCount() > 1): ?>
    <nav aria-label="Navigasi halaman" class="mt-4">
        <ul class="pagination justify-content-center flex-wrap">
            <?php if ($pager->hasPrevious()): ?>
                <li class="page-item">
                    <a class="page-link" href="<?= esc($pager->getFirst()); ?>" aria-label="Halaman pertama">
                        <span aria-hidden="true"><i class="bi bi-chevron-double-left"></i></span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="<?= esc($pager->getPreviousPage()); ?>" aria-label="Halaman sebelumnya">
                        <span aria-hidden="true"><i class="bi bi-chevron-left"></i></span>
                    </a>
                </li>
            <?php endif; ?>

            <?php foreach ($pager->links() as $link): ?>
                <li class="page-item <?= $link['active'] ? 'active' : ''; ?>">
                    <a class="page-link" href="<?= esc($link['uri']); ?>"><?= esc($link['title']); ?></a>
                </li>
            <?php endforeach; ?>

            <?php if ($pager->hasNext()): ?>
                <li class="page-item">
                    <a class="page-link" href="<?= esc($pager->getNextPage()); ?>" aria-label="Halaman berikutnya">
                        <span aria-hidden="true"><i class="bi bi-chevron-right"></i></span>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="<?= esc($pager->getLast()); ?>" aria-label="Halaman terakhir">
                        <span aria-hidden="true"><i class="bi bi-chevron-double-right"></i></span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif; ?>