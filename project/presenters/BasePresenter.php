<?php
abstract class BasePresenter {
    /**
     * Membangun banner status dari kode status URL secara aman (allowlisted).
     */
    protected function buildStatusBanner(?string $statusCode, string $entityLabel): string {
        if ($statusCode === null || $statusCode === '') {
            return '';
        }

        $safeLabel = htmlspecialchars($entityLabel, ENT_QUOTES, 'UTF-8');
        $map = [
            'added'    => ['success', 'status', "Berhasil: Data {$safeLabel} baru telah ditambahkan."],
            'updated'  => ['success', 'status', "Berhasil: Data {$safeLabel} telah diperbarui."],
            'deleted'  => ['success', 'status', "Berhasil: Data {$safeLabel} telah dihapus."],
            'invalid'  => ['error',   'alert',  "Perhatian: Pastikan seluruh kolom {$safeLabel} terisi dengan nilai yang valid."],
            'notfound' => ['error',   'alert',  "Perhatian: Data {$safeLabel} yang dipilih tidak ditemukan."],
        ];

        if (!isset($map[$statusCode])) {
            return '';
        }

        [$variant, $role, $message] = $map[$statusCode];
        return "<div class='status-banner status-banner--{$variant}' role='{$role}'>{$message}</div>";
    }

    protected function redirectToEntity(string $entity, string $status = ''): void {
        $url = 'index.php?entity=' . urlencode($entity);
        if ($status !== '') {
            $url .= '&status=' . urlencode($status);
        }
        header("Location: {$url}");
        exit();
    }
}
?>
