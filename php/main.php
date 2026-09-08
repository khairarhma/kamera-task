<?php

require_once __DIR__ . '/Camera.php';

/**
 * Program utama:
 *  1. Membuat objek Camera yang valid dan menampilkannya.
 *  2. Melakukan satu perubahan yang SAH (takePhoto()).
 *  3. Mencoba dua operasi yang TIDAK SAH -> objek harus menolak.
 */

// 1. Buat objek valid & tampilkan
$camera = new Camera('Canon', 'EOS R50', 24.2, 1000);
$camera->turnOn();
echo "Objek awal (valid):\n";
echo $camera . "\n";

// 2. Satu perubahan yang SAH
$camera->takePhoto(50);
echo "\nSetelah 1 perubahan sah (takePhoto(50)):\n";
echo $camera . "\n";

// 3. Dua operasi TIDAK SAH -> harus ditolak

// Operasi tidak sah #1: menghapus foto lebih besar dari yang tersimpan
echo "\nMencoba operasi tidak sah #1: deletePhoto(9999)\n";
try {
    $camera->deletePhoto(9999);
    echo "  -> Tidak seharusnya sampai sini!\n";
} catch (RuntimeException $e) {
    echo "  -> DITOLAK: " . $e->getMessage() . "\n";
}

// Operasi tidak sah #2: mengambil foto yang melebihi sisa kapasitas penyimpanan
echo "\nMencoba operasi tidak sah #2: takePhoto(2000) (melebihi kapasitas)\n";
try {
    $camera->takePhoto(2000);
    echo "  -> Tidak seharusnya sampai sini!\n";
} catch (RuntimeException $e) {
    echo "  -> DITOLAK: " . $e->getMessage() . "\n";
}

echo "\nObjek akhir (invarian tetap terjaga):\n";
echo $camera . "\n";
