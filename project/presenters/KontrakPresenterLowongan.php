<?php
interface KontrakPresenterLowongan {
    public function tampilkanLowongan(): string;
    public function tampilkanFormLowongan(?int $id = null): string;
    public function tambahLowongan(string $judul, string $departemen, string $tipeKerja, string $lokasi, int $kuota, string $status): void;
    public function ubahLowongan(int $id, string $judul, string $departemen, string $tipeKerja, string $lokasi, int $kuota, string $status): void;
    public function hapusLowongan(int $id): void;
}
?>
