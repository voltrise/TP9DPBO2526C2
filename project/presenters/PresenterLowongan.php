<?php
require_once __DIR__ . '/BasePresenter.php';
require_once __DIR__ . '/KontrakPresenterLowongan.php';
require_once __DIR__ . '/../models/KontrakModelLowongan.php';
require_once __DIR__ . '/../models/Lowongan.php';
require_once __DIR__ . '/../views/KontrakViewLowongan.php';

class PresenterLowongan extends BasePresenter implements KontrakPresenterLowongan {
    private KontrakModelLowongan $model;
    private KontrakViewLowongan $view;

    public function __construct(KontrakModelLowongan $model, KontrakViewLowongan $view) {
        $this->model = $model;
        $this->view = $view;
    }

    private function buildRowHtml(int $no, Lowongan $lowongan): string {
        $id = (int)$lowongan->getId();
        $judul = htmlspecialchars($lowongan->getJudul(), ENT_QUOTES, 'UTF-8');
        $departemen = htmlspecialchars($lowongan->getDepartemen(), ENT_QUOTES, 'UTF-8');
        $tipeKerja = htmlspecialchars($lowongan->getTipeKerja(), ENT_QUOTES, 'UTF-8');
        $lokasi = htmlspecialchars($lowongan->getLokasi(), ENT_QUOTES, 'UTF-8');
        $kuota = number_format($lowongan->getKuota()) . ' Orang';
        $statusRaw = $lowongan->getStatus();
        $statusText = htmlspecialchars($statusRaw, ENT_QUOTES, 'UTF-8');
        $statusClass = $statusRaw === 'Aktif' ? 'status-badge--aktif' : 'status-badge--ditutup';

        return "<tr>
            <td class='col-id' data-label='No'>{$no}</td>
            <td data-label='Judul Posisi'><strong>{$judul}</strong></td>
            <td data-label='Departemen'>{$departemen}</td>
            <td data-label='Tipe Kerja'>{$tipeKerja}</td>
            <td data-label='Lokasi'>{$lokasi}</td>
            <td data-label='Kuota'>{$kuota}</td>
            <td data-label='Status'><span class='status-badge {$statusClass}'>{$statusText}</span></td>
            <td class='col-actions' data-label='Aksi'>
                <div class='action-group'>
                    <a href='index.php?entity=lowongan&screen=edit&id={$id}' class='btn btn-edit'>Edit</a>
                    <form method='post' action='index.php?entity=lowongan' class='inline-form' onsubmit=\"return confirm('Hapus lowongan ini?')\">
                        <input type='hidden' name='entity' value='lowongan'>
                        <input type='hidden' name='action' value='hapus'>
                        <input type='hidden' name='id' value='{$id}'>
                        <button type='submit' class='btn btn-delete'>Hapus</button>
                    </form>
                </div>
            </td>
        </tr>";
    }

    private function isValidInput(string $judul, string $departemen, string $tipeKerja, string $lokasi, int $kuota, string $status): bool {
        $allowedTipe = ['Full-time', 'Part-time', 'Kontrak', 'Magang'];
        $allowedStatus = ['Aktif', 'Ditutup'];

        if ($judul === '' || $departemen === '' || $lokasi === '') {
            return false;
        }
        if (!in_array($tipeKerja, $allowedTipe, true) || !in_array($status, $allowedStatus, true)) {
            return false;
        }
        if ($kuota < 1) {
            return false;
        }
        return true;
    }

    public function tampilkanLowongan(?string $statusCode = null): string {
        $listLowongan = $this->model->getAllLowongan();
        $totalLowongan = count($listLowongan);
        $bannerHtml = $this->buildStatusBanner($statusCode, 'lowongan');

        $rowsHtml = '';
        if (empty($listLowongan)) {
            $rowsHtml = "<tr><td colspan='8' class='empty-cell'>Belum ada data lowongan. Klik '+ Tambah Lowongan' untuk membuka posisi baru.</td></tr>";
        } else {
            $no = 1;
            foreach ($listLowongan as $lowongan) {
                $rowsHtml .= $this->buildRowHtml($no, $lowongan);
                $no++;
            }
        }

        return $this->view->renderDaftar($rowsHtml, $totalLowongan, $bannerHtml);
    }

    public function tampilkanFormLowongan(?int $id = null, ?string $statusCode = null): string {
        $bannerHtml = $this->buildStatusBanner($statusCode, 'lowongan');

        if ($id !== null) {
            $lowongan = $this->model->getLowonganById($id);
            if ($lowongan === null) {
                $this->redirectToEntity('lowongan', 'notfound');
            }

            $formData = [
                'id'         => $lowongan->getId(),
                'judul'      => $lowongan->getJudul(),
                'departemen' => $lowongan->getDepartemen(),
                'tipeKerja'  => $lowongan->getTipeKerja(),
                'lokasi'     => $lowongan->getLokasi(),
                'kuota'      => $lowongan->getKuota(),
                'status'     => $lowongan->getStatus(),
            ];
            return $this->view->renderForm('Ubah Data Lowongan', 'ubah', $formData, $bannerHtml);
        }

        return $this->view->renderForm('Tambah Lowongan Baru', 'tambah', [], $bannerHtml);
    }

    public function tambahLowongan(string $judul, string $departemen, string $tipeKerja, string $lokasi, int $kuota, string $status): void {
        $judul = trim($judul);
        $departemen = trim($departemen);
        $lokasi = trim($lokasi);

        if (!$this->isValidInput($judul, $departemen, $tipeKerja, $lokasi, $kuota, $status)) {
            $this->redirectToEntity('lowongan', 'invalid');
        }

        $this->model->addLowongan($judul, $departemen, $tipeKerja, $lokasi, $kuota, $status);
        $this->redirectToEntity('lowongan', 'added');
    }

    public function ubahLowongan(int $id, string $judul, string $departemen, string $tipeKerja, string $lokasi, int $kuota, string $status): void {
        $judul = trim($judul);
        $departemen = trim($departemen);
        $lokasi = trim($lokasi);

        if ($id <= 0 || !$this->isValidInput($judul, $departemen, $tipeKerja, $lokasi, $kuota, $status)) {
            $this->redirectToEntity('lowongan', 'invalid');
        }

        $this->model->updateLowongan($id, $judul, $departemen, $tipeKerja, $lokasi, $kuota, $status);
        $this->redirectToEntity('lowongan', 'updated');
    }

    public function hapusLowongan(int $id): void {
        if ($id <= 0) {
            $this->redirectToEntity('lowongan', 'notfound');
        }

        $this->model->deleteLowongan($id);
        $this->redirectToEntity('lowongan', 'deleted');
    }
}
?>
