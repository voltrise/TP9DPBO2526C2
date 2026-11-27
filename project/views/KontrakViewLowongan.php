<?php
/**
 * Kontrak View Lowongan (Strict Passive View)
 * View tidak mengenal entitas domain, tidak melakukan loop, dan tidak menghitung agregat.
 */
interface KontrakViewLowongan {
    public function renderDaftar(string $tableRowsHtml, int $totalData, string $statusBannerHtml = ''): string;
    public function renderForm(string $title, string $action, array $formData = [], string $statusBannerHtml = ''): string;
}
?>
