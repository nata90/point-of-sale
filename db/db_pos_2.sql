/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 8.0.27 : Database - db_pos_2
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_pos_2` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `db_pos_2`;

/*Table structure for table `detail_pembelian` */

DROP TABLE IF EXISTS `detail_pembelian`;

CREATE TABLE `detail_pembelian` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_pembelian` int NOT NULL,
  `kd_barang` varchar(30) NOT NULL,
  `satuan` varchar(10) NOT NULL DEFAULT '-',
  `jumlah` decimal(19,4) NOT NULL DEFAULT '0.0000',
  `harga_beli` int NOT NULL DEFAULT '0',
  `harga_jual` int NOT NULL DEFAULT '0',
  `status_delete` tinyint(1) NOT NULL DEFAULT '0',
  `tgl_delete` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `detail_pembelian` */

/*Table structure for table `dt_transaksi` */

DROP TABLE IF EXISTS `dt_transaksi`;

CREATE TABLE `dt_transaksi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `no_transaksi` varchar(64) NOT NULL,
  `kd_barang` varchar(30) NOT NULL,
  `harga_satuan` int DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `total_harga` int DEFAULT NULL,
  `id_stok_barang` int DEFAULT NULL,
  `status_hapus` tinyint(1) DEFAULT '0',
  `tgl_hapus` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `dt_transaksi` */

insert  into `dt_transaksi`(`id`,`no_transaksi`,`kd_barang`,`harga_satuan`,`qty`,`total_harga`,`id_stok_barang`,`status_hapus`,`tgl_hapus`) values (1,'202208085','BRG000002',2000,5,10000,0,0,NULL),(2,'202208085','BRG000003',2000,5,10000,0,0,NULL),(3,'202208086','BRG000001',15000,10,150000,0,0,NULL),(4,'202208151','BRG000001',15000,2,30000,0,0,NULL),(5,'202208151','BRG000002',2000,1,2000,0,0,NULL),(6,'202208152','BRG000001',15000,2,30000,0,0,NULL),(7,'202208152','BRG000002',2000,2,4000,0,0,NULL),(8,'202208152','BRG000003',2000,3,6000,0,0,NULL),(9,'202208191','BRG000002',2000,2,4000,0,0,NULL),(10,'202208191','BRG000003',2000,1,2000,0,0,NULL),(11,'202208192','BRG000002',2000,2,4000,0,0,NULL),(12,'202208192','BRG000003',2000,1,2000,0,0,NULL),(13,'202208193','BRG000002',2000,2,4000,0,0,NULL),(14,'202208193','BRG000003',2000,1,2000,0,0,NULL),(15,'202208194','BRG000002',2000,2,4000,0,0,NULL),(16,'202208194','BRG000003',2000,2,4000,0,0,NULL),(17,'202208195','BRG000002',2000,2,4000,0,0,NULL),(18,'202208195','BRG000003',2000,2,4000,0,0,NULL),(19,'202208196','BRG000002',2000,2,4000,0,0,NULL),(20,'202208196','BRG000003',2000,2,4000,0,0,NULL),(21,'202208197','BRG000002',2000,1,2000,0,0,NULL),(22,'202208197','BRG000003',2000,5,10000,0,0,NULL),(23,'202208221','BRG000004',15000,2,30000,0,0,NULL),(24,'202208221','BRG000006',18000,1,18000,0,0,NULL),(25,'202208222','BRG000004',15000,2,30000,0,0,NULL),(26,'202208222','BRG000006',18000,1,18000,0,0,NULL),(27,'202208223','BRG000004',15000,2,30000,0,0,NULL),(28,'202208224','BRG000004',15000,2,30000,0,0,NULL),(29,'202209051','BRG000004',15000,1,15000,0,0,NULL),(30,'202209051','BRG000005',20000,1,20000,0,0,NULL),(31,'202209052','BRG000005',20000,2,40000,0,0,NULL),(32,'202209053','BRG000005',20000,1,20000,0,0,NULL),(33,'202209054','BRG000004',15000,2,30000,0,0,NULL),(34,'202209054','BRG000005',20000,2,40000,0,0,NULL),(35,'202209055','BRG000004',15000,1,15000,0,0,NULL),(36,'202209056','BRG000004',15000,1,15000,0,0,NULL),(37,'202209056','BRG000005',20000,2,40000,0,0,NULL),(38,'202209057','BRG000004',15000,1,15000,0,0,NULL),(39,'202209058','BRG000004',15000,2,30000,0,0,NULL),(40,'202209058','BRG000005',20000,3,60000,0,0,NULL),(41,'202209059','BRG000005',20000,2,40000,0,0,NULL),(42,'202209061','BRG000006',18000,1,18000,0,0,NULL),(43,'202309071','BRG000004',15000,1,15000,0,0,NULL),(44,'202309071','BRG000005',20000,2,40000,0,0,NULL),(45,'202309071','BRG000001',15000,1,15000,0,0,NULL),(46,'202309072','BRG000005',20000,1,20000,0,0,NULL),(47,'202309072','BRG000001',15000,1,15000,0,0,NULL),(48,'202309073','BRG000005',20000,1,20000,0,0,NULL),(49,'202309073','BRG000001',15000,1,15000,0,0,NULL),(50,'202309073','BRG000003',2000,1,2000,0,0,NULL),(51,'202309074','9780132350884',25000,1,25000,0,0,NULL),(52,'202309075','9786026486646',45000,1,45000,0,0,NULL),(53,'202309076','9786026486646',45000,2,90000,0,0,NULL);

/*Table structure for table `file_barang` */

DROP TABLE IF EXISTS `file_barang`;

CREATE TABLE `file_barang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kd_barang` varchar(30) NOT NULL,
  `nama_barang` varchar(200) NOT NULL,
  `lokasi` varchar(100) DEFAULT '-',
  `harga_beli` int DEFAULT '0',
  `harga_jual` int DEFAULT '0',
  `stok` int DEFAULT '0',
  `aktif` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `kd_barang` (`kd_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `file_barang` */

insert  into `file_barang`(`id`,`kd_barang`,`nama_barang`,`lokasi`,`harga_beli`,`harga_jual`,`stok`,`aktif`) values (1,'BRG000001','Buku Tulis','-',10000,15000,0,1),(2,'BRG000002','Pensil 2B','-',1000,2000,0,1),(3,'BRG000003','Pensil 2H','-',1500,2000,0,1),(4,'BRG000004','Paracetamol 500 mg','-',0,15000,0,1),(5,'BRG000005','Dexamethasone','-',0,20000,0,1),(6,'BRG000006','Penisilin','-',0,18000,0,1),(7,'BRG000007','Madu Curcuma','-',10000,15000,0,1),(8,'620188007','Buku Keajaiban Toko','-',20000,25000,0,1),(9,'9780132350884','Clean Code','-',20000,25000,0,1),(10,'9786026486646','The Psikologi of money','-',40000,45000,0,1);

/*Table structure for table `file_stok_barang` */

DROP TABLE IF EXISTS `file_stok_barang`;

CREATE TABLE `file_stok_barang` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kd_barang` varchar(30) NOT NULL,
  `tgl_ed` date NOT NULL,
  `stok_akhir` float NOT NULL,
  `nomor_batch` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `file_stok_barang` */

/*Table structure for table `hd_transaksi` */

DROP TABLE IF EXISTS `hd_transaksi`;

CREATE TABLE `hd_transaksi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `no_transaksi` varchar(60) NOT NULL,
  `tgl_bayar` datetime NOT NULL,
  `status_bayar` tinyint(1) DEFAULT '0',
  `total` int DEFAULT NULL,
  `jumlah_bayar` int DEFAULT NULL,
  `status_hapus` tinyint(1) DEFAULT '0',
  `tgl_hapus` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `hd_transaksi` */

insert  into `hd_transaksi`(`id`,`no_transaksi`,`tgl_bayar`,`status_bayar`,`total`,`jumlah_bayar`,`status_hapus`,`tgl_hapus`) values (1,'202208083','2022-08-08 13:31:46',1,20000,30000,1,'2022-08-08 13:40:47'),(2,'202208084','2022-08-08 13:38:42',1,20000,30000,1,'2022-08-08 13:40:44'),(3,'202208085','2022-08-08 13:39:41',1,20000,30000,0,NULL),(4,'202208086','2022-08-08 13:47:38',1,150000,200000,0,NULL),(5,'202208151','2022-08-15 14:12:00',1,32000,35000,0,NULL),(6,'202208152','2022-08-15 14:35:42',1,40000,50000,0,NULL),(7,'202208191','2022-08-19 15:56:28',1,6000,10000,0,NULL),(8,'202208192','2022-08-19 15:56:38',1,6000,10000,0,NULL),(9,'202208193','2022-08-19 15:57:29',1,6000,10000,0,NULL),(10,'202208194','2022-08-19 15:59:31',1,8000,10000,0,NULL),(11,'202208195','2022-08-19 16:02:25',1,8000,100000,0,NULL),(12,'202208196','2022-08-19 16:04:05',1,8000,100000,0,NULL),(13,'202208197','2022-08-19 16:05:31',1,12000,15000,0,NULL),(14,'202208221','2022-08-22 13:45:18',1,48000,50000,0,NULL),(15,'202208222','2022-08-22 13:46:37',1,48000,50000,0,NULL),(16,'202208223','2022-08-22 14:09:57',1,30000,30000,0,NULL),(17,'202208224','2022-08-22 14:26:30',1,30000,50000,0,NULL),(18,'202209051','2022-09-05 15:19:49',1,35000,40000,0,NULL),(19,'202209052','2022-09-05 15:21:35',1,40000,50000,0,NULL),(20,'202209053','2022-09-05 15:22:46',1,20000,30000,0,NULL),(21,'202209054','2022-09-05 15:25:49',1,70000,100000,0,NULL),(22,'202209055','2022-09-05 15:27:41',1,15000,20000,0,NULL),(23,'202209056','2022-09-05 15:29:50',1,55000,60000,0,NULL),(24,'202209057','2022-09-05 15:30:57',1,15000,20000,0,NULL),(25,'202209058','2022-09-05 15:32:11',1,90000,100000,0,NULL),(26,'202209059','2022-09-05 15:45:08',1,40000,50000,0,NULL),(27,'202209061','2022-09-06 14:30:37',1,18000,20000,0,NULL),(28,'202309071','2023-09-07 19:40:43',1,70000,100000,0,NULL),(29,'202309072','2023-09-07 19:41:55',1,35000,50000,0,NULL),(30,'202309073','2023-09-07 19:47:16',1,37000,40000,0,NULL),(31,'202309074','2023-09-07 20:03:32',1,25000,30000,0,NULL),(32,'202309075','2023-09-07 20:04:54',1,45000,100000,0,NULL),(33,'202309076','2023-09-07 20:05:27',1,90000,100000,0,NULL);

/*Table structure for table `header_pembelian` */

DROP TABLE IF EXISTS `header_pembelian`;

CREATE TABLE `header_pembelian` (
  `id_pembelian` int NOT NULL AUTO_INCREMENT,
  `id_supplier` int NOT NULL DEFAULT '1',
  `no_faktur` varchar(30) NOT NULL DEFAULT '-',
  `tgl_pembelian` datetime NOT NULL,
  `keterangan` varchar(300) NOT NULL,
  `total_pembelian` decimal(19,4) NOT NULL,
  `status_delete` tinyint(1) NOT NULL DEFAULT '0',
  `tgl_delete` datetime DEFAULT NULL,
  PRIMARY KEY (`id_pembelian`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `header_pembelian` */

/*Table structure for table `kode_generate` */

DROP TABLE IF EXISTS `kode_generate`;

CREATE TABLE `kode_generate` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_transaksi` varchar(200) NOT NULL,
  `nama_alias` varchar(10) NOT NULL,
  `urutan` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `kode_generate` */

insert  into `kode_generate`(`id`,`nama_transaksi`,`nama_alias`,`urutan`) values (1,'kode barang','BRG',11);

/*Table structure for table `migration` */

DROP TABLE IF EXISTS `migration`;

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `migration` */

insert  into `migration`(`version`,`apply_time`) values ('m000000_000000_base',1658131264),('m190730_064935_create_user',1658131266),('m190730_070942_create_usergroup',1658131266),('m190806_062251_hd_transaksi',1658131266),('m190806_062305_dt_transaksi',1658131266),('m190806_064512_file_barang',1658131266),('m190812_022619_urutan_transaksi',1658131266),('m190918_070827_kode_generate',1658131266),('m220718_063102_supplier',1658131266),('m220718_063550_setting_app',1658131266),('m220718_064351_header_pembelian',1658131266),('m220718_065135_detail_pembelian',1658131267),('m220718_071435_file_stok_barang',1658131267);

/*Table structure for table `setting_app` */

DROP TABLE IF EXISTS `setting_app`;

CREATE TABLE `setting_app` (
  `id` int NOT NULL AUTO_INCREMENT,
  `app_name` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `ip_address` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `setting_app` */

insert  into `setting_app`(`id`,`app_name`,`email`,`ip_address`) values (1,'POS System','rahanata9@gmail.com','127.0.0.1');

/*Table structure for table `supplier` */

DROP TABLE IF EXISTS `supplier`;

CREATE TABLE `supplier` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_supllier` varchar(100) NOT NULL DEFAULT '-',
  `alamat_supplier` varchar(100) NOT NULL DEFAULT '-',
  `no_telp` varchar(100) NOT NULL,
  `cp` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `supplier` */

insert  into `supplier`(`id`,`nama_supllier`,`alamat_supplier`,`no_telp`,`cp`) values (1,'-','-','0','0');

/*Table structure for table `urutan_transaksi` */

DROP TABLE IF EXISTS `urutan_transaksi`;

CREATE TABLE `urutan_transaksi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_transaksi` varchar(200) NOT NULL,
  `urutan` int DEFAULT '0',
  `tgl_transaksi` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `urutan_transaksi` */

insert  into `urutan_transaksi`(`id`,`nama_transaksi`,`urutan`,`tgl_transaksi`) values (1,'transaksi penjualan',6,'2022-08-08 13:29:05'),(2,'transaksi penjualan',2,'2022-08-15 14:11:59'),(3,'transaksi penjualan',7,'2022-08-19 15:56:28'),(4,'transaksi penjualan',4,'2022-08-22 13:45:18'),(5,'transaksi penjualan',9,'2022-09-05 15:19:48'),(6,'transaksi penjualan',1,'2022-09-06 14:30:37'),(7,'transaksi penjualan',6,'2023-09-07 19:40:43');

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `password` varchar(100) NOT NULL,
  `authkey` varchar(100) NOT NULL,
  `accesstoken` varchar(100) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `id_group` int DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `user` */

insert  into `user`(`id`,`username`,`password`,`authkey`,`accesstoken`,`name`,`id_group`,`aktif`,`created_at`,`updated_at`) values (1,'admin','342acf8a1d482e65876ac159bc540a92','f7700fd6671892bb24883dd2745933e9','-','Administrator',1,1,'2022-07-18 08:01:06',NULL);

/*Table structure for table `user_group` */

DROP TABLE IF EXISTS `user_group`;

CREATE TABLE `user_group` (
  `id_group` int NOT NULL AUTO_INCREMENT,
  `group_name` varchar(100) NOT NULL,
  `aktif` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_group`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `user_group` */

insert  into `user_group`(`id_group`,`group_name`,`aktif`,`created_at`,`updated_at`) values (1,'admin',1,'2022-07-18 08:01:06',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
