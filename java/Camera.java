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
public class Camera {

    private static final int MIN_BATTERY = 0;
    private static final int MAX_BATTERY = 100;
    private static final int BATTERY_COST_PER_PHOTO = 2;

    private final String brand;
    private final String model;
    private final double megapixels;
    private int batteryLevel;
    private final int storageCapacityMB;
    private int storageUsedMB;
    private boolean isOn;

    public Camera(String brand, String model, double megapixels, int storageCapacityMB) {
        if (brand == null || brand.isBlank()) {
            throw new IllegalArgumentException("brand tidak boleh kosong");
        }
        if (model == null || model.isBlank()) {
            throw new IllegalArgumentException("model tidak boleh kosong");
        }
        validateMegapixels(megapixels);
        if (storageCapacityMB <= 0) {
            throw new IllegalArgumentException("storageCapacityMB harus lebih dari 0");
        }

        this.brand = brand;
        this.model = model;
        this.megapixels = megapixels;
        this.storageCapacityMB = storageCapacityMB;
        this.batteryLevel = MAX_BATTERY; // baterai penuh saat baru
        this.storageUsedMB = 0;
        this.isOn = false;
    }

    // ---------- Getter ----------

    public String getBrand() {
        return brand;
    }

    public String getModel() {
        return model;
    }

    public double getMegapixels() {
        return megapixels;
    }

    public int getBatteryLevel() {
        return batteryLevel;
    }

    public int getStorageCapacityMB() {
        return storageCapacityMB;
    }

    public int getStorageUsedMB() {
        return storageUsedMB;
    }

    public boolean isOn() {
        return isOn;
    }

    // ---------- Method yang mengubah state (tervalidasi) ----------

    /** Menyalakan kamera. Menolak jika baterai 0%. */
    public void turnOn() {
        if (batteryLevel <= MIN_BATTERY) {
            throw new IllegalStateException("Tidak bisa menyalakan kamera: baterai habis");
        }
        isOn = true;
    }

    public void turnOff() {
        isOn = false;
    }

    /**
     * Mengambil foto sebesar sizeMB. Menambah storageUsedMB dan
     * mengurangi batteryLevel. Menolak jika kamera mati, baterai
     * tidak cukup, atau penyimpanan akan melebihi kapasitas.
     */
    public void takePhoto(int sizeMB) {
        if (sizeMB <= 0) {
            throw new IllegalArgumentException("Ukuran foto harus lebih dari 0 MB");
        }
        if (!isOn) {
            throw new IllegalStateException("Tidak bisa mengambil foto: kamera dalam keadaan mati");
        }
        if (batteryLevel < BATTERY_COST_PER_PHOTO) {
            throw new IllegalStateException("Tidak bisa mengambil foto: baterai tidak cukup");
        }
        if (storageUsedMB + sizeMB > storageCapacityMB) {
            throw new IllegalStateException("Tidak bisa mengambil foto: penyimpanan penuh");
        }
        storageUsedMB += sizeMB;
        batteryLevel -= BATTERY_COST_PER_PHOTO;
    }

    /** Menghapus foto sebesar sizeMB. Menolak jika hasilnya akan menjadi negatif. */
    public void deletePhoto(int sizeMB) {
        if (sizeMB <= 0) {
            throw new IllegalArgumentException("Ukuran foto harus lebih dari 0 MB");
        }
        if (sizeMB > storageUsedMB) {
            throw new IllegalStateException("Tidak bisa menghapus foto: ukuran melebihi penyimpanan terpakai (invarian storageUsedMB >= 0)");
        }
        storageUsedMB -= sizeMB;
    }

    /** Mengisi baterai. Nilai akhir tidak pernah melebihi 100 (invarian). */
    public void chargeBattery(int amount) {
        if (amount <= 0) {
            throw new IllegalArgumentException("Jumlah isi baterai harus lebih dari 0");
        }
        batteryLevel = Math.min(MAX_BATTERY, batteryLevel + amount);
    }

    private static void validateMegapixels(double megapixels) {
        if (megapixels <= 0) {
            throw new IllegalArgumentException("megapixels harus lebih dari 0");
        }
    }

    @Override
    public String toString() {
        return "Camera{" +
                "brand='" + brand + '\'' +
                ", model='" + model + '\'' +
                ", megapixels=" + megapixels +
                ", batteryLevel=" + batteryLevel +
                ", storageUsedMB=" + storageUsedMB + "/" + storageCapacityMB +
                ", isOn=" + isOn +
                '}';
    }
}
