<?php
interface KontrakModelLowongan {
    /**
     * Mengambil seluruh data lowongan sebagai array objek Lowongan.
     * @return Lowongan[]
     */
    public function getAllLowongan(): array;

    /**
     * Mengambil satu data lowongan berdasarkan ID.
     */
    public function getLowonganById(int $id): ?Lowongan;

    public function addLowongan(string $judul, string $departemen, string $tipeKerja, string $lokasi, int $kuota, string $status): void;
    public function updateLowongan(int $id, string $judul, string $departemen, string $tipeKerja, string $lokasi, int $kuota, string $status): void;
    public function deleteLowongan(int $id): void;
}
?>
