<?php

if (! function_exists('format_rupiah')) {
    /**
     * Format angka menjadi Rupiah gaya Indonesia, tanpa desimal.
     * Contoh: 15000 -> "Rp15.000", 3000 -> "Rp3.000", 0 -> "Rp0".
     */
    function format_rupiah($angka): string
    {
        return 'Rp' . number_format((int) $angka, 0, ',', '.');
    }
}
