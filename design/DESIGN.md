# Rancangan Class — Camera

> Domain: **Kamera**.
> Dokumen ini adalah versi digital dari rancangan di kertas.
> Sertakan juga foto rancangan tulisan tangan Anda di folder `design/` (mis. `design-photo.jpg`)
> sesuai ketentuan tugas ("Rancang di kertas ... foto & sertakan di repositori").

## Nama Class
`Camera` — merepresentasikan sebuah kamera digital beserta status penyimpanan dan baterainya.

## Field & Tipe

| Field              | Tipe    | Keterangan                                              |
|---------------------|---------|----------------------------------------------------------|
| brand               | String  | Merek kamera, diisi sekali saat dibuat                    |
| model               | String  | Nama model kamera, diisi sekali saat dibuat               |
| megapixels          | double  | Resolusi sensor dalam megapiksel, harus > 0               |
| batteryLevel        | int     | Level baterai dalam persen, 0–100                         |
| storageCapacityMB   | int     | Total kapasitas penyimpanan (MB), diisi sekali saat dibuat |
| storageUsedMB       | int     | Penyimpanan yang sudah terpakai (MB), 0 ≤ nilai ≤ kapasitas |
| isOn                | boolean | Status kamera menyala/mati                                |

## Invarian (aturan yang harus selalu benar)

1. **Invarian Baterai**: `batteryLevel` harus selalu berada pada rentang **0–100** (tidak boleh
   negatif, tidak boleh lebih dari 100).
2. **Invarian Penyimpanan**: `storageUsedMB` harus selalu memenuhi
   `0 <= storageUsedMB <= storageCapacityMB` (tidak boleh negatif, tidak boleh melebihi kapasitas).
3. **Invarian Resolusi** *(tambahan)*: `megapixels` harus **> 0** — kamera tanpa resolusi sensor
   tidak masuk akal.

Karena `batteryLevel`, `storageUsedMB`, dan `megapixels` punya invarian, class ini
**tidak menyediakan setter mentah** (`setBatteryLevel()`, `setStorageUsedMB()`, dll).
Semua perubahan dilakukan lewat method yang memvalidasi terlebih dahulu, dan operasi yang
melanggar invarian akan **ditolak** (melempar exception).

## Method

| Method                              | Perilaku                                                                 |
|---------------------------------------|---------------------------------------------------------------------------|
| `Camera(brand, model, megapixels, storageCapacityMB)` | Konstruktor. Validasi invarian resolusi & set state awal (baterai 100%, storage 0, mati). |
| `getBrand()` / `getModel()`           | Getter identitas kamera                                                   |
| `getMegapixels()`                     | Getter resolusi                                                            |
| `getBatteryLevel()`                   | Getter level baterai                                                       |
| `getStorageCapacityMB()`              | Getter kapasitas penyimpanan                                               |
| `getStorageUsedMB()`                  | Getter penyimpanan terpakai                                                |
| `isOn()`                              | Getter status nyala/mati                                                   |
| `turnOn()`                            | Menyalakan kamera. **Menolak** jika baterai 0%.                           |
| `turnOff()`                           | Mematikan kamera.                                                          |
| `takePhoto(int sizeMB)`               | Mengambil foto: menambah `storageUsedMB` & mengurangi `batteryLevel`. **Menolak** jika kamera mati, baterai tidak cukup, atau penyimpanan penuh. |
| `deletePhoto(int sizeMB)`             | Menghapus foto: mengurangi `storageUsedMB`. **Menolak** jika hasilnya akan menjadi negatif. |
| `chargeBattery(int amount)`           | Mengisi baterai. Nilai akhir dibatasi maksimal 100 (tidak pernah melebihi). |

## Diagram sederhana

```
+---------------------------------+
|             Camera               |
+---------------------------------+
| - brand: String                  |
| - model: String                  |
| - megapixels: double             |
| - batteryLevel: int              |
| - storageCapacityMB: int         |
| - storageUsedMB: int             |
| - isOn: boolean                  |
+---------------------------------+
| + getBrand() / getModel()        |
| + getMegapixels()                |
| + getBatteryLevel()              |
| + getStorageUsedMB()             |
| + isOn()                         |
| + turnOn()                       |
| + turnOff()                      |
| + takePhoto(int sizeMB)          |
| + deletePhoto(int sizeMB)        |
| + chargeBattery(int amount)      |
+---------------------------------+
```
