<?php

/**
 * Camera merepresentasikan sebuah kamera digital beserta
 * status baterai dan penyimpanannya.
 *
 * Invarian:
 *  1. batteryLevel harus selalu berada pada rentang 0-100.
 *  2. storageUsedMB harus selalu memenuhi 0 <= storageUsedMB <= storageCapacityMB.
 *  3. megapixels harus > 0.
 *
 * Karena batteryLevel, storageUsedMB, dan megapixels punya invarian,
 * class ini TIDAK menyediakan setter mentah. Semua perubahan dilakukan
 * lewat method yang tervalidasi, dan operasi yang melanggar invarian
 * akan DITOLAK (melempar exception).
 */
class Camera
{
    private const MIN_BATTERY = 0;
    private const MAX_BATTERY = 100;
    private const BATTERY_COST_PER_PHOTO = 2;

    private string $brand;
    private string $model;
    private float $megapixels;
    private int $batteryLevel;
    private int $storageCapacityMB;
    private int $storageUsedMB;
    private bool $isOn;

    public function __construct(string $brand, string $model, float $megapixels, int $storageCapacityMB)
    {
        if (trim($brand) === '') {
            throw new InvalidArgumentException('brand tidak boleh kosong');
        }
        if (trim($model) === '') {
            throw new InvalidArgumentException('model tidak boleh kosong');
        }
        self::validateMegapixels($megapixels);
        if ($storageCapacityMB <= 0) {
            throw new InvalidArgumentException('storageCapacityMB harus lebih dari 0');
        }

        $this->brand = $brand;
        $this->model = $model;
        $this->megapixels = $megapixels;
        $this->storageCapacityMB = $storageCapacityMB;
        $this->batteryLevel = self::MAX_BATTERY; // baterai penuh saat baru
        $this->storageUsedMB = 0;
        $this->isOn = false;
    }

    // ---------- Getter ----------

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getMegapixels(): float
    {
        return $this->megapixels;
    }

    public function getBatteryLevel(): int
    {
        return $this->batteryLevel;
    }

    public function getStorageCapacityMB(): int
    {
        return $this->storageCapacityMB;
    }

    public function getStorageUsedMB(): int
    {
        return $this->storageUsedMB;
    }

    public function isOn(): bool
    {
        return $this->isOn;
    }

    // ---------- Method yang mengubah state (tervalidasi) ----------

    /** Menyalakan kamera. Menolak jika baterai 0%. */
    public function turnOn(): void
    {
        if ($this->batteryLevel <= self::MIN_BATTERY) {
            throw new RuntimeException('Tidak bisa menyalakan kamera: baterai habis');
        }
        $this->isOn = true;
    }

    public function turnOff(): void
    {
        $this->isOn = false;
    }

    /**
     * Mengambil foto sebesar $sizeMB. Menambah storageUsedMB dan
     * mengurangi batteryLevel. Menolak jika kamera mati, baterai
     * tidak cukup, atau penyimpanan akan melebihi kapasitas.
     */
    public function takePhoto(int $sizeMB): void
    {
        if ($sizeMB <= 0) {
            throw new InvalidArgumentException('Ukuran foto harus lebih dari 0 MB');
        }
        if (!$this->isOn) {
            throw new RuntimeException('Tidak bisa mengambil foto: kamera dalam keadaan mati');
        }
        if ($this->batteryLevel < self::BATTERY_COST_PER_PHOTO) {
            throw new RuntimeException('Tidak bisa mengambil foto: baterai tidak cukup');
        }
        if ($this->storageUsedMB + $sizeMB > $this->storageCapacityMB) {
            throw new RuntimeException('Tidak bisa mengambil foto: penyimpanan penuh');
        }
        $this->storageUsedMB += $sizeMB;
        $this->batteryLevel -= self::BATTERY_COST_PER_PHOTO;
    }

    /** Menghapus foto sebesar $sizeMB. Menolak jika hasilnya akan menjadi negatif. */
    public function deletePhoto(int $sizeMB): void
    {
        if ($sizeMB <= 0) {
            throw new InvalidArgumentException('Ukuran foto harus lebih dari 0 MB');
        }
        if ($sizeMB > $this->storageUsedMB) {
            throw new RuntimeException('Tidak bisa menghapus foto: ukuran melebihi penyimpanan terpakai (invarian storageUsedMB >= 0)');
        }
        $this->storageUsedMB -= $sizeMB;
    }

    /** Mengisi baterai. Nilai akhir tidak pernah melebihi 100 (invarian). */
    public function chargeBattery(int $amount): void
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Jumlah isi baterai harus lebih dari 0');
        }
        $this->batteryLevel = min(self::MAX_BATTERY, $this->batteryLevel + $amount);
    }

    private static function validateMegapixels(float $megapixels): void
    {
        if ($megapixels <= 0) {
            throw new InvalidArgumentException('megapixels harus lebih dari 0');
        }
    }

    public function __toString(): string
    {
        $isOnStr = $this->isOn ? 'true' : 'false';
        return "Camera{brand='{$this->brand}', model='{$this->model}', megapixels={$this->megapixels}, " .
            "batteryLevel={$this->batteryLevel}, storageUsedMB={$this->storageUsedMB}/{$this->storageCapacityMB}, isOn={$isOnStr}}";
    }
}
