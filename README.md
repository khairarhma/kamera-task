# Tugas 1 — Camera

**Domain hasil undian:** Kamera

## Class Utama: `Camera`

Merepresentasikan sebuah kamera digital, dengan field `brand`, `model`, `megapixels`,
`batteryLevel`, `storageCapacityMB`, `storageUsedMB`, dan `isOn`. Detail rancangan lengkap
ada di [`design/DESIGN.md`](design/DESIGN.md) (sertakan juga foto rancangan kertas Anda
di folder `design/`).

## Invarian & Alasannya

1. **`batteryLevel` harus 0–100.**
   Alasan: level baterai adalah persentase, secara fisik tidak mungkin negatif atau
   melebihi 100%.
2. **`storageUsedMB` harus `0 <= storageUsedMB <= storageCapacityMB`.**
   Alasan: penyimpanan yang terpakai tidak mungkin negatif, dan tidak mungkin melebihi
   kapasitas fisik kartu memori kamera.
3. **`megapixels` harus lebih dari 0.**
   Alasan: resolusi sensor adalah karakteristik dasar kamera; kamera tanpa resolusi
   valid tidak bermakna secara fisik.

Karena field-field tersebut punya invarian, class **tidak menyediakan setter mentah**
(`setBatteryLevel`, `setStorageUsedMB`, dst). Semua perubahan lewat method tervalidasi
(`takePhoto()`, `deletePhoto()`, `chargeBattery()`, dst) yang akan **menolak** (melempar
exception) bila hasil perubahan melanggar invarian.

## Struktur Folder

```
kamera-task/
├── design/
│   └── DESIGN.md          # rancangan class (field, method, invarian)
├── java/
│   ├── Camera.java        # implementasi class
│   └── Main.java          # program utama
├── php/
│   ├── Camera.php         # implementasi class
│   └── main.php           # program utama
└── README.md
```




Kedua program utama melakukan hal yang sama:
1. Membuat objek `Camera` yang valid (baterai 100%, storage 0/1000 MB) dan menampilkannya.
2. Melakukan satu perubahan yang **sah** (`takePhoto(50)`).
3. Mencoba dua operasi yang **tidak sah**:
   - `deletePhoto(9999)` → ditolak karena ukurannya melebihi penyimpanan yang terpakai
     (akan membuat `storageUsedMB` negatif).
   - `takePhoto(2000)` → ditolak karena akan membuat `storageUsedMB` melebihi
     `storageCapacityMB`.

## Deklarasi Penggunaan AI

Sebagian besar rancangan class, implementasi kode (Java & PHP), dan penulisan README ini
dibuat dengan bantuan Claude (Anthropic) sebagai asisten AI, berdasarkan arahan domain
("Kamera") dan ketentuan tugas yang diberikan. Kode telah ditinjau ulang secara manual
untuk memastikan logika invarian dan penolakan operasi tidak sah berjalan benar.
