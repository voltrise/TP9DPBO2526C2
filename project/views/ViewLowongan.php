<?php
require_once __DIR__ . '/KontrakViewLowongan.php';

class ViewLowongan implements KontrakViewLowongan {
    private string $skinPath;
    private string $formPath;

    public function __construct(string $skinPath = '', string $formPath = '') {
        $this->skinPath = $skinPath ?: __DIR__ . '/../template/skinLowongan.html';
        $this->formPath = $formPath ?: __DIR__ . '/../template/formLowongan.html';
    }

    public function renderDaftar(string $tableRowsHtml, int $totalData, string $statusBannerHtml = ''): string {
        $template = file_get_contents($this->skinPath);

        $tokens = [
            '{{STATUS_BANNER}}' => $statusBannerHtml,
            '{{TABLE_ROWS}}'    => $tableRowsHtml,
            '{{TOTAL_DATA}}'    => (string)$totalData,
        ];

        return str_replace(array_keys($tokens), array_values($tokens), $template);
    }

    public function renderForm(string $title, string $action, array $formData = [], string $statusBannerHtml = ''): string {
        $template = file_get_contents($this->formPath);
        $tipeKerja = $formData['tipeKerja'] ?? 'Full-time';
        $status = $formData['status'] ?? 'Aktif';

        $tokens = [
            '{{STATUS_BANNER}}'  => $statusBannerHtml,
            '{{FORM_TITLE}}'     => htmlspecialchars($title, ENT_QUOTES, 'UTF-8'),
            '{{ACTION}}'         => htmlspecialchars($action, ENT_QUOTES, 'UTF-8'),
            '{{ID}}'             => htmlspecialchars((string)($formData['id'] ?? ''), ENT_QUOTES, 'UTF-8'),
            '{{JUDUL}}'          => htmlspecialchars($formData['judul'] ?? '', ENT_QUOTES, 'UTF-8'),
            '{{DEPARTEMEN}}'     => htmlspecialchars($formData['departemen'] ?? '', ENT_QUOTES, 'UTF-8'),
            '{{LOKASI}}'         => htmlspecialchars($formData['lokasi'] ?? '', ENT_QUOTES, 'UTF-8'),
            '{{KUOTA}}'          => htmlspecialchars((string)($formData['kuota'] ?? 1), ENT_QUOTES, 'UTF-8'),
            '{{TIPE_FULL_TIME}}' => $tipeKerja === 'Full-time' ? 'selected' : '',
            '{{TIPE_PART_TIME}}' => $tipeKerja === 'Part-time' ? 'selected' : '',
            '{{TIPE_KONTRAK}}'   => $tipeKerja === 'Kontrak' ? 'selected' : '',
            '{{TIPE_MAGANG}}'    => $tipeKerja === 'Magang' ? 'selected' : '',
            '{{STATUS_AKTIF}}'   => $status === 'Aktif' ? 'selected' : '',
            '{{STATUS_DITUTUP}}' => $status === 'Ditutup' ? 'selected' : '',
        ];

        return str_replace(array_keys($tokens), array_values($tokens), $template);
    }
}
?>
