<?php
require_once __DIR__ . '/BasePresenter.php';
require_once __DIR__ . '/KontrakPresenter.php';
require_once __DIR__ . '/../models/KontrakModel.php';
require_once __DIR__ . '/../models/Kandidat.php';
require_once __DIR__ . '/../views/KontrakView.php';

class PresenterKandidat extends BasePresenter implements KontrakPresenter {
    private KontrakModel $model;
    private KontrakView $view;

    public function __construct(KontrakModel $model, KontrakView $view) {
        $this->model = $model;
        $this->view = $view;
    }

    private function buildRowHtml(int $no, Kandidat $kandidat): string {
        $id = (int)$kandidat->getId();
        $nama = htmlspecialchars($kandidat->getNama(), ENT_QUOTES, 'UTF-8');
        $posisi = htmlspecialchars($kandidat->getPosisi(), ENT_QUOTES, 'UTF-8');
        $domisili = htmlspecialchars($kandidat->getDomisili(), ENT_QUOTES, 'UTF-8');
        $skorTes = number_format($kandidat->getSkorTes()) . ' / 100';
        $pengalaman = number_format($kandidat->getPengalamanTahun()) . ' Tahun';

        return "<tr>
            <td class='col-id' data-label='No'>{$no}</td>
            <td data-label='Nama'><strong>{$nama}</strong></td>
            <td data-label='Posisi Dilamar'>{$posisi}</td>
            <td data-label='Domisili'>{$domisili}</td>
            <td data-label='Skor Tes'>{$skorTes}</td>
            <td data-label='Pengalaman'>{$pengalaman}</td>
            <td class='col-actions' data-label='Aksi'>
                <div class='action-group'>
                    <a href='index.php?entity=kandidat&screen=edit&id={$id}' class='btn btn-edit'>Edit</a>
                    <form method='post' action='index.php?entity=kandidat' class='inline-form' onsubmit=\"return confirm('Hapus kandidat ini?')\">
                        <input type='hidden' name='entity' value='kandidat'>
                        <input type='hidden' name='action' value='hapus'>
                        <input type='hidden' name='id' value='{$id}'>
                        <button type='submit' class='btn btn-delete'>Hapus</button>
                    </form>
                </div>
            </td>
        </tr>";
    }

    private function isValidInput(string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): bool {
        if ($nama === '' || $posisi === '' || $domisili === '') {
            return false;
        }
        if ($skorTes < 0 || $skorTes > 100 || $pengalamanTahun < 0) {
            return false;
        }
        return true;
    }

    public function tampilkanKandidat(?string $statusCode = null): string {
        $listKandidat = $this->model->getAllKandidat();
        $totalKandidat = count($listKandidat);
        $bannerHtml = $this->buildStatusBanner($statusCode, 'kandidat');

        $rowsHtml = '';
        if (empty($listKandidat)) {
            $rowsHtml = "<tr><td colspan='7' class='empty-cell'>Belum ada data kandidat. Klik '+ Tambah Kandidat' untuk menambahkan pelamar baru.</td></tr>";
        } else {
            $no = 1;
            foreach ($listKandidat as $kandidat) {
                $rowsHtml .= $this->buildRowHtml($no, $kandidat);
                $no++;
            }
        }

        return $this->view->renderDaftar($rowsHtml, $totalKandidat, $bannerHtml);
    }

    public function tampilkanFormKandidat(?int $id = null, ?string $statusCode = null): string {
        $bannerHtml = $this->buildStatusBanner($statusCode, 'kandidat');

        if ($id !== null) {
            $kandidat = $this->model->getKandidatById($id);
            if ($kandidat === null) {
                $this->redirectToEntity('kandidat', 'notfound');
            }

            $formData = [
                'id'              => $kandidat->getId(),
                'nama'            => $kandidat->getNama(),
                'posisi'          => $kandidat->getPosisi(),
                'domisili'        => $kandidat->getDomisili(),
                'skorTes'         => $kandidat->getSkorTes(),
                'pengalamanTahun' => $kandidat->getPengalamanTahun(),
            ];
            return $this->view->renderForm('Ubah Data Kandidat', 'ubah', $formData, $bannerHtml);
        }

        return $this->view->renderForm('Tambah Kandidat Baru', 'tambah', [], $bannerHtml);
    }

    public function tambahKandidat(string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void {
        $nama = trim($nama);
        $posisi = trim($posisi);
        $domisili = trim($domisili);

        if (!$this->isValidInput($nama, $posisi, $domisili, $skorTes, $pengalamanTahun)) {
            $this->redirectToEntity('kandidat', 'invalid');
        }

        $this->model->addKandidat($nama, $posisi, $domisili, $skorTes, $pengalamanTahun);
        $this->redirectToEntity('kandidat', 'added');
    }

    public function ubahKandidat(int $id, string $nama, string $posisi, string $domisili, int $skorTes, int $pengalamanTahun): void {
        $nama = trim($nama);
        $posisi = trim($posisi);
        $domisili = trim($domisili);

        if ($id <= 0 || !$this->isValidInput($nama, $posisi, $domisili, $skorTes, $pengalamanTahun)) {
            $this->redirectToEntity('kandidat', 'invalid');
        }

        $this->model->updateKandidat($id, $nama, $posisi, $domisili, $skorTes, $pengalamanTahun);
        $this->redirectToEntity('kandidat', 'updated');
    }

    public function hapusKandidat(int $id): void {
        if ($id <= 0) {
            $this->redirectToEntity('kandidat', 'notfound');
        }

        $this->model->deleteKandidat($id);
        $this->redirectToEntity('kandidat', 'deleted');
    }
}
?>
