/**
 * Program utama:
 *  1. Membuat objek Camera yang valid dan menampilkannya.
 *  2. Melakukan satu perubahan yang SAH (takePhoto()).
 *  3. Mencoba dua operasi yang TIDAK SAH -> objek harus menolak.
 */
public class Main {
    public static void main(String[] args) {
        // 1. Buat objek valid & tampilkan
        Camera camera = new Camera("Canon", "EOS R50", 24.2, 1000);
        camera.turnOn();
        System.out.println("Objek awal (valid):");
        System.out.println(camera);

        // 2. Satu perubahan yang SAH
        camera.takePhoto(50);
        System.out.println("\nSetelah 1 perubahan sah (takePhoto(50)):");
        System.out.println(camera);

        // 3. Dua operasi TIDAK SAH -> harus ditolak

        // Operasi tidak sah #1: menghapus foto lebih besar dari yang tersimpan
        System.out.println("\nMencoba operasi tidak sah #1: deletePhoto(9999)");
        try {
            camera.deletePhoto(9999);
            System.out.println("  -> Tidak seharusnya sampai sini!");
        } catch (IllegalStateException e) {
            System.out.println("  -> DITOLAK: " + e.getMessage());
        }

        // Operasi tidak sah #2: mengambil foto yang melebihi sisa kapasitas penyimpanan
        System.out.println("\nMencoba operasi tidak sah #2: takePhoto(2000) (melebihi kapasitas)");
        try {
            camera.takePhoto(2000);
            System.out.println("  -> Tidak seharusnya sampai sini!");
        } catch (IllegalStateException e) {
            System.out.println("  -> DITOLAK: " + e.getMessage());
        }

        System.out.println("\nObjek akhir (invarian tetap terjaga):");
        System.out.println(camera);
    }
}
