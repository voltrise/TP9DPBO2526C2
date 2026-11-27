<?php
require_once __DIR__ . '/models/TabelKandidat.php';
require_once __DIR__ . '/views/ViewKandidat.php';
require_once __DIR__ . '/presenters/PresenterKandidat.php';

require_once __DIR__ . '/models/TabelLowongan.php';
require_once __DIR__ . '/views/ViewLowongan.php';
require_once __DIR__ . '/presenters/PresenterLowongan.php';

function renderErrorPage(string $message): string {
    $safeMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    return "<!doctype html>
<html lang='id'>
<head>
  <meta charset='utf-8' />
  <meta name='viewport' content='width=device-width,initial-scale=1' />
  <title>Kesalahan Sistem - Sistem Rekrutmen MVP</title>
  <link rel='stylesheet' href='template/style.css' />
</head>
<body>
  <div class='container container--form'>
    <main class='card'>
      <header class='page-header'>
        <div>
          <h1 class='page-title'>Gagal Memuat Data Rekrutmen</h1>
          <p class='page-subtitle'>Periksa koneksi MySQL dan pastikan file mvp_db.sql sudah diimpor.</p>
        </div>
      </header>
      <div class='status-banner status-banner--error' role='alert'>Kesalahan: {$safeMessage}</div>
      <div class='actions'>
        <a href='index.php' class='btn btn-primary'>Muat Ulang Halaman</a>
      </div>
    </main>
  </div>
</body>
</html>";
}

$entity = $_POST['entity'] ?? $_GET['entity'] ?? 'kandidat';
if ($entity !== 'kandidat' && $entity !== 'lowongan') {
    $entity = 'kandidat';
}

try {
    if ($entity === 'lowongan') {
        $model = new TabelLowongan('localhost', 'mvp_kandidat_db', 'root', '');
        $view = new ViewLowongan();
        $presenter = new PresenterLowongan($model, $view);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'tambah') {
                $presenter->tambahLowongan(
                    (string)($_POST['judul'] ?? ''),
                    (string)($_POST['departemen'] ?? ''),
                    (string)($_POST['tipeKerja'] ?? ''),
                    (string)($_POST['lokasi'] ?? ''),
                    (int)($_POST['kuota'] ?? 1),
                    (string)($_POST['status'] ?? '')
                );
            } elseif ($action === 'ubah') {
                $presenter->ubahLowongan(
                    (int)($_POST['id'] ?? 0),
                    (string)($_POST['judul'] ?? ''),
                    (string)($_POST['departemen'] ?? ''),
                    (string)($_POST['tipeKerja'] ?? ''),
                    (string)($_POST['lokasi'] ?? ''),
                    (int)($_POST['kuota'] ?? 1),
                    (string)($_POST['status'] ?? '')
                );
            } elseif ($action === 'hapus') {
                $presenter->hapusLowongan((int)($_POST['id'] ?? 0));
            }
            header('Location: index.php?entity=lowongan');
            exit();
        }

        $screen = $_GET['screen'] ?? 'list';
        $status = $_GET['status'] ?? null;
        if ($screen === 'add') {
            echo $presenter->tampilkanFormLowongan(null, $status);
        } elseif ($screen === 'edit' && isset($_GET['id'])) {
            echo $presenter->tampilkanFormLowongan((int)$_GET['id'], $status);
        } else {
            echo $presenter->tampilkanLowongan($status);
        }
    } else {
        $model = new TabelKandidat('localhost', 'mvp_kandidat_db', 'root', '');
        $view = new ViewKandidat();
        $presenter = new PresenterKandidat($model, $view);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'tambah') {
                $presenter->tambahKandidat(
                    (string)($_POST['nama'] ?? ''),
                    (string)($_POST['posisi'] ?? ''),
                    (string)($_POST['domisili'] ?? ''),
                    (int)($_POST['skorTes'] ?? 0),
                    (int)($_POST['pengalamanTahun'] ?? 0)
                );
            } elseif ($action === 'ubah') {
                $presenter->ubahKandidat(
                    (int)($_POST['id'] ?? 0),
                    (string)($_POST['nama'] ?? ''),
                    (string)($_POST['posisi'] ?? ''),
                    (string)($_POST['domisili'] ?? ''),
                    (int)($_POST['skorTes'] ?? 0),
                    (int)($_POST['pengalamanTahun'] ?? 0)
                );
            } elseif ($action === 'hapus') {
                $presenter->hapusKandidat((int)($_POST['id'] ?? 0));
            }
            header('Location: index.php?entity=kandidat');
            exit();
        }

        $screen = $_GET['screen'] ?? 'list';
        $status = $_GET['status'] ?? null;
        if ($screen === 'add') {
            echo $presenter->tampilkanFormKandidat(null, $status);
        } elseif ($screen === 'edit' && isset($_GET['id'])) {
            echo $presenter->tampilkanFormKandidat((int)$_GET['id'], $status);
        } else {
            echo $presenter->tampilkanKandidat($status);
        }
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo renderErrorPage($e->getMessage());
}
?>
