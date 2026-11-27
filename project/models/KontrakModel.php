<?php
interface KontrakModel {
    /**
     * Mengambil seluruh data kandidat sebagai array objek Kandidat.
     * @return Kandidat[]
     */
    public function getAllKandidat(): array;

    /**
     * Mengambil satu data kandidat berdasarkan ID.
     */
    public function getKandidatById(int $id): ?Kandidat;

    public function addKandidat(string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void;
    public function updateKandidat(int $id, string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void;
    public function deleteKandidat(int $id): void;
}
?>
