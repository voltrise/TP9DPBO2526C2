<?php
require_once __DIR__ . '/DB.php';
require_once __DIR__ . '/KontrakModelLowongan.php';
require_once __DIR__ . '/Lowongan.php';

class TabelLowongan extends DB implements KontrakModelLowongan {

    public function __construct(
        string $host = "localhost",
        string $db_name = "mvp_kandidat_db",
        string $username = "root",
        string $password = ""
    ) {
        parent::__construct($host, $db_name, $username, $password);
    }

    private function mapRowToLowongan(array $row): Lowongan {
        return new Lowongan(
            (int)$row['id'],
            $row['judul'],
            $row['departemen'],
            $row['tipeKerja'],
            $row['lokasi'],
            (int)$row['kuota'],
            $row['status']
        );
    }

    public function getAllLowongan(): array {
        $query = "SELECT * FROM lowongan ORDER BY status ASC, id ASC";
        $this->executeQuery($query);
        $rows = $this->getAllResult();

        $list = [];
        foreach ($rows as $row) {
            $list[] = $this->mapRowToLowongan($row);
        }
        return $list;
    }

    public function getLowonganById(int $id): ?Lowongan {
        $query = "SELECT * FROM lowongan WHERE id = :id LIMIT 1";
        $this->executeQuery($query, [':id' => $id]);
        $rows = $this->getAllResult();
        if (empty($rows)) {
            return null;
        }
        return $this->mapRowToLowongan($rows[0]);
    }

    public function addLowongan(string $judul, string $departemen, string $tipeKerja, string $lokasi, int $kuota, string $status): void {
        $query = "INSERT INTO lowongan (judul, departemen, tipeKerja, lokasi, kuota, status)
                  VALUES (:judul, :departemen, :tipeKerja, :lokasi, :kuota, :status)";
        $this->executeQuery($query, [
            ':judul'      => $judul,
            ':departemen' => $departemen,
            ':tipeKerja'  => $tipeKerja,
            ':lokasi'     => $lokasi,
            ':kuota'      => $kuota,
            ':status'     => $status,
        ]);
    }

    public function updateLowongan(int $id, string $judul, string $departemen, string $tipeKerja, string $lokasi, int $kuota, string $status): void {
        $query = "UPDATE lowongan
                  SET judul = :judul,
                      departemen = :departemen,
                      tipeKerja = :tipeKerja,
                      lokasi = :lokasi,
                      kuota = :kuota,
                      status = :status
                  WHERE id = :id";
        $this->executeQuery($query, [
            ':id'         => $id,
            ':judul'      => $judul,
            ':departemen' => $departemen,
            ':tipeKerja'  => $tipeKerja,
            ':lokasi'     => $lokasi,
            ':kuota'      => $kuota,
            ':status'     => $status,
        ]);
    }

    public function deleteLowongan(int $id): void {
        $query = "DELETE FROM lowongan WHERE id = :id";
        $this->executeQuery($query, [':id' => $id]);
    }
}
?>
