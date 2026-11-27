-- Database: mvp_kandidat_db
CREATE DATABASE IF NOT EXISTS `mvp_kandidat_db`;
USE `mvp_kandidat_db`;

CREATE TABLE IF NOT EXISTS `kandidat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `posisi` varchar(100) NOT NULL,
  `domisili` varchar(100) NOT NULL,
  `skorTes` int(11) NOT NULL DEFAULT 0,
  `pengalamanTahun` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `kandidat` (`id`, `nama`, `posisi`, `domisili`, `skorTes`, `pengalamanTahun`) VALUES
(1, 'Raka Pratama', 'Backend Developer', 'Bandung', 92, 3),
(2, 'Sinta Maharani', 'UI/UX Designer', 'Jakarta', 88, 2),
(3, 'Dimas Nugroho', 'Data Analyst', 'Yogyakarta', 85, 1),
(4, 'Nadia Kusuma', 'Frontend Developer', 'Surabaya', 90, 2),
(5, 'Bagas Setiawan', 'QA Engineer', 'Bandung', 81, 1);

CREATE TABLE IF NOT EXISTS `lowongan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(120) NOT NULL,
  `departemen` varchar(100) NOT NULL,
  `tipeKerja` varchar(50) NOT NULL,
  `lokasi` varchar(100) NOT NULL,
  `kuota` int(11) NOT NULL DEFAULT 1,
  `status` varchar(30) NOT NULL DEFAULT 'Aktif',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `lowongan` (`id`, `judul`, `departemen`, `tipeKerja`, `lokasi`, `kuota`, `status`) VALUES
(1, 'Backend Developer', 'Engineering', 'Full-time', 'Bandung', 2, 'Aktif'),
(2, 'UI/UX Designer', 'Product Design', 'Full-time', 'Jakarta', 1, 'Aktif'),
(3, 'Data Analyst', 'Business Intelligence', 'Kontrak', 'Yogyakarta', 2, 'Aktif'),
(4, 'Frontend Developer', 'Engineering', 'Part-time', 'Surabaya', 1, 'Aktif'),
(5, 'QA Engineer', 'Quality Assurance', 'Magang', 'Bandung', 3, 'Ditutup');
