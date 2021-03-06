/*
SQLyog Ultimate v11.11 (32 bit)
MySQL - 5.7.29-0ubuntu0.16.04.1-log : Database - db_pos
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_pos` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `db_pos`;

/*Table structure for table `detail_pembelian` */

DROP TABLE IF EXISTS `detail_pembelian`;

CREATE TABLE `detail_pembelian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pembelian` int(11) NOT NULL,
  `kd_barang` varchar(30) NOT NULL,
  `satuan` varchar(10) NOT NULL DEFAULT '-',
  `jumlah` decimal(19,4) NOT NULL DEFAULT '0.0000',
  `harga_beli` int(11) NOT NULL DEFAULT '0',
  `harga_jual` int(11) NOT NULL DEFAULT '0',
  `status_delete` tinyint(1) NOT NULL DEFAULT '0',
  `tgl_delete` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pembelian` (`id_pembelian`),
  CONSTRAINT `detail_pembelian_ibfk_1` FOREIGN KEY (`id_pembelian`) REFERENCES `header_pembelian` (`id_pembelian`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;

/*Data for the table `detail_pembelian` */

insert  into `detail_pembelian`(`id`,`id_pembelian`,`kd_barang`,`satuan`,`jumlah`,`harga_beli`,`harga_jual`,`status_delete`,`tgl_delete`) values (34,37,'A0001','-',10.0000,10000,12000,0,NULL),(35,38,'A0001','-',20.0000,10000,12000,0,NULL),(36,38,'A0001','-',20.0000,10000,12000,0,NULL),(37,39,'A0003','-',10.0000,20000,30000,0,NULL),(38,39,'A0003','-',50.0000,20000,35000,0,NULL),(39,40,'BRG000004','-',4.0000,25000,30000,0,NULL),(40,40,'A0003','-',4.0000,10000,15000,0,NULL),(41,40,'A0003','-',10.0000,10000,15000,0,NULL);

/*Table structure for table `dt_transaksi` */

DROP TABLE IF EXISTS `dt_transaksi`;

CREATE TABLE `dt_transaksi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_transaksi` varchar(64) NOT NULL,
  `kd_barang` varchar(30) NOT NULL,
  `harga_satuan` int(11) DEFAULT NULL,
  `qty` int(10) DEFAULT NULL,
  `total_harga` int(11) DEFAULT NULL,
  `id_stok_barang` int(11) NOT NULL,
  `status_hapus` tinyint(1) DEFAULT '0',
  `tgl_hapus` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=latin1;

/*Data for the table `dt_transaksi` */

insert  into `dt_transaksi`(`id`,`no_transaksi`,`kd_barang`,`harga_satuan`,`qty`,`total_harga`,`id_stok_barang`,`status_hapus`,`tgl_hapus`) values (43,'201909303','A0001',20000,5,100000,0,1,'2019-09-30 10:47:54'),(44,'201910011','A0001',20000,4,80000,0,0,NULL),(45,'201910012','A0002',15000,3,45000,0,0,NULL),(46,'201910013','A0001',20000,3,60000,0,0,NULL),(47,'201910091','A0001',20000,4,80000,0,0,NULL),(48,'201910131','A0001',20000,4,80000,0,0,NULL),(49,'201910131','A0003',20000,2,40000,0,0,NULL),(50,'201910131','A0001',20000,1,20000,0,0,NULL),(51,'201910171','A0003',20000,5,100000,0,0,NULL),(52,'201910281','A0001',20000,4,80000,0,0,NULL),(53,'201910281','A0003',20000,4,80000,0,0,NULL),(54,'201911011','A0001',20000,4,80000,0,0,NULL),(55,'201911011','A0003',20000,3,60000,0,0,NULL),(56,'201911012','A0002',15000,3,45000,0,0,NULL),(57,'201911013','A0001',20000,5,100000,0,0,NULL),(58,'201911014','A0003',20000,7,140000,0,0,NULL),(59,'201911015','BRG000001',25000,5,125000,0,0,NULL),(60,'201911016','A0003',20000,4,80000,0,0,NULL),(61,'201911017','A0002',15000,4,60000,0,1,'2019-11-01 21:32:31'),(62,'201911018','A0002',15000,7,105000,0,0,NULL),(63,'201911021','A0001',20000,5,100000,0,0,NULL),(64,'201911022','A0003',20000,4,80000,0,0,NULL),(65,'201911023','A0003',20000,4,80000,0,0,NULL),(66,'201911024','BRG000001',25000,4,100000,0,0,NULL),(67,'201911025','A0002',15000,20,300000,0,0,NULL),(68,'201911025','A0001',20000,50,1000000,0,0,NULL),(69,'201911031','A0001',20000,45,900000,0,0,NULL),(70,'201911051','A0001',20000,5,100000,0,0,NULL),(71,'201911052','BRG000001',25000,7,175000,0,0,NULL),(72,'201911052','A0003',20000,4,80000,0,0,NULL),(73,'201911053','A0001',20000,3,60000,0,0,NULL),(74,'201911054','A0003',20000,7,140000,0,0,NULL),(75,'201911055','BRG000001',25000,8,200000,0,0,NULL),(76,'201911056','A0003',20000,5,100000,0,0,NULL),(77,'201911057','A0003',20000,8,160000,0,0,NULL),(78,'201911058','BRG000001',25000,6,150000,0,0,NULL),(79,'201911059','A0002',15000,4,60000,0,0,NULL),(80,'2019110510','A0003',20000,4,80000,0,0,NULL),(81,'2019110511','BRG000001',25000,7,175000,0,0,NULL),(82,'2019110512','A0003',20000,5,100000,0,0,NULL),(83,'2019110513','A0003',20000,7,140000,0,0,NULL),(84,'201911071','A0003',20000,4,80000,0,0,NULL),(85,'201911072','BRG000001',25000,4,100000,0,0,NULL),(86,'201911081','A0001',20000,3,60000,0,0,NULL),(87,'201911082','A0003',20000,3,60000,0,1,'2019-11-08 11:02:51'),(88,'201911131','A0003',20000,4,80000,0,0,NULL),(89,'201911132','A0001',20000,4,80000,0,0,NULL),(90,'201911133','A0003',20000,3,60000,0,0,NULL),(91,'201911133','A0001',20000,2,40000,0,0,NULL),(92,'201911133','BRG000001',25000,2,50000,0,0,NULL),(93,'201911191','A0003',20000,5,100000,0,0,NULL),(94,'201911192','A0001',20000,4,80000,0,0,NULL),(95,'201911193','A0003',20000,2,40000,0,0,NULL),(96,'201911281','A0003',20000,5,100000,0,0,NULL),(97,'201912041','A0003',20000,4,80000,0,0,NULL),(99,'201912051','A0003',20000,4,80000,0,0,NULL),(100,'201912052','A0001',20000,3,60000,0,0,NULL),(101,'201912053','A0003',20000,4,80000,0,0,NULL),(102,'201912061','A0003',20000,5,100000,0,0,NULL),(103,'201912062','A0001',20000,5,100000,0,0,NULL),(104,'201912062','A0003',20000,2,40000,0,0,NULL),(105,'201912111','A0003',20000,6,120000,0,0,NULL),(106,'201912112','A0003',20000,5,100000,0,0,NULL),(107,'201912131','A0003',20000,6,120000,0,0,NULL),(108,'201912161','A0003',20000,5,100000,0,0,NULL),(109,'201912171','BRG000002',250000,4,1000000,0,0,NULL),(110,'201912181','A0003',20000,5,100000,0,0,NULL),(111,'201912191','BRG000007',500000,5,2500000,0,0,NULL),(112,'202001061','A0001',20000,4,80000,0,0,NULL),(113,'202001141','A0003',50000,4,200000,0,0,NULL),(114,'202001142','BRG000004',25000,10,250000,0,1,'2020-01-14 10:14:27'),(115,'202001143','A0003',50000,2,100000,0,0,NULL),(116,'202001151','BRG000007',400000,2,800000,0,0,NULL),(117,'202001211','A0003',50000,4,200000,0,0,NULL),(118,'202001211','BRG000007',400000,3,1200000,0,0,NULL),(119,'202002021','A0003',50000,4,200000,0,0,NULL),(120,'202002022','A0001',25000,2,50000,0,1,'2020-02-02 10:34:42'),(121,'202002241','A0001',25000,4,100000,0,0,NULL),(122,'202003061','A0001',10000,4,40000,0,0,NULL),(123,'202003171','A0001',16000,5,80000,0,0,NULL),(124,'202003191','A0003',50000,5,250000,0,0,NULL),(125,'202003261','A0003',50000,2,100000,0,0,NULL),(126,'202003262','A0003',35000,1,35000,0,0,NULL),(127,'202003291','A0003',15000,4,60000,0,0,NULL),(128,'202004011','A0003',15000,4,60000,5,0,NULL),(129,'202004012','A0001',12000,5,60000,3,1,'2020-04-01 13:41:49'),(130,'202004013','A0001',12000,5,60000,3,1,'2020-04-01 13:45:13'),(131,'202004021','A0003',15000,5,75000,8,0,NULL);

/*Table structure for table `file_barang` */

DROP TABLE IF EXISTS `file_barang`;

CREATE TABLE `file_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kd_barang` varchar(30) NOT NULL,
  `nama_barang` varchar(200) NOT NULL,
  `lokasi` varchar(100) NOT NULL DEFAULT '-',
  `harga_beli` int(11) DEFAULT '0',
  `harga_jual` int(11) DEFAULT '0',
  `stok` int(11) NOT NULL DEFAULT '0',
  `aktif` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_barang` (`kd_barang`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

/*Data for the table `file_barang` */

insert  into `file_barang`(`id`,`kd_barang`,`nama_barang`,`lokasi`,`harga_beli`,`harga_jual`,`stok`,`aktif`) values (1,'A0001','PARACETAMOL 500 MG','-',10000,12000,238,1),(2,'A0002','PARACETAMOL CAIR','-',7500,15000,25,1),(3,'A0003','DEXAMETHAZONE','-',10000,15000,57,1),(7,'BRG000002','Flash disk','Rak 4',200000,250000,42,1),(8,'BRG000003','BUSCHOPAN','-',10000,15000,25,1),(9,'BRG000004','Ibuprofen 400 mg','-',25000,30000,14,1),(10,'BRG000005','BODREX','-',25000,30000,10,1),(11,'BRG000006','Ultra Sari Kacang Hijau','-',40000,45000,8,1),(12,'BRG000007','Sepatu Brodo','-',200000,400000,30,1),(13,'BRG000008','BOTOL TUMBLER','-',15000,20000,10,1);

/*Table structure for table `file_stok_barang` */

DROP TABLE IF EXISTS `file_stok_barang`;

CREATE TABLE `file_stok_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kd_barang` varchar(30) NOT NULL,
  `tgl_ed` date NOT NULL,
  `stok_akhir` float(19,4) NOT NULL,
  `nomor_batch` varchar(30) NOT NULL DEFAULT '-',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `file_stok_barang` */

insert  into `file_stok_barang`(`id`,`kd_barang`,`tgl_ed`,`stok_akhir`,`nomor_batch`) values (3,'A0001','2020-12-31',30.0000,'-'),(4,'A0001','2020-11-30',20.0000,'-'),(5,'A0003','2020-04-28',10.0000,'-'),(6,'A0003','2020-12-31',50.0000,'-'),(7,'BRG000004','2020-03-26',4.0000,'-'),(8,'A0003','2020-03-26',-1.0000,'-'),(9,'A0003','2020-03-25',10.0000,'-');

/*Table structure for table `hd_transaksi` */

DROP TABLE IF EXISTS `hd_transaksi`;

CREATE TABLE `hd_transaksi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_transaksi` varchar(64) NOT NULL,
  `tgl_bayar` datetime NOT NULL,
  `status_bayar` tinyint(1) DEFAULT '0',
  `total` int(11) DEFAULT NULL,
  `jumlah_bayar` int(11) NOT NULL DEFAULT '0',
  `status_hapus` tinyint(1) DEFAULT '0',
  `tgl_hapus` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_trans` (`no_transaksi`),
  KEY `tgl` (`tgl_bayar`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=latin1;

/*Data for the table `hd_transaksi` */

insert  into `hd_transaksi`(`id`,`no_transaksi`,`tgl_bayar`,`status_bayar`,`total`,`jumlah_bayar`,`status_hapus`,`tgl_hapus`) values (36,'201909303','2019-09-30 03:46:15',1,100000,100000,1,'2019-09-30 03:47:54'),(37,'201910011','2019-10-01 07:14:35',1,80000,100000,0,NULL),(38,'201910012','2019-10-01 07:14:47',1,45000,50000,0,NULL),(39,'201910013','2019-10-01 07:15:00',1,60000,100000,0,NULL),(40,'201910091','2019-10-09 08:28:16',1,80000,100000,0,NULL),(41,'201910131','2019-10-13 12:46:39',1,140000,150000,0,NULL),(42,'201910171','2019-10-17 03:35:48',1,100000,100000,0,NULL),(43,'201910281','2019-10-28 13:08:46',1,160000,200000,0,NULL),(44,'201911011','2019-11-01 03:46:21',1,140000,200000,0,NULL),(45,'201911012','2019-11-01 03:46:35',1,45000,50000,0,NULL),(46,'201911013','2019-11-01 07:01:36',1,100000,100000,0,NULL),(47,'201911014','2019-11-01 07:01:57',1,140000,150000,0,NULL),(48,'201911015','2019-11-01 07:02:20',1,125000,150000,0,NULL),(49,'201911016','2019-11-01 14:00:31',1,80000,100000,0,NULL),(50,'201911017','2019-11-01 14:32:00',1,60000,60000,1,'2019-11-01 14:32:31'),(51,'201911018','2019-11-01 22:21:54',1,105000,105000,0,NULL),(52,'201911021','2019-11-02 05:30:16',1,100000,100000,0,NULL),(53,'201911022','2019-11-02 05:31:26',1,80000,100000,0,NULL),(54,'201911023','2019-11-02 23:42:19',1,80000,100000,0,NULL),(55,'201911024','2019-11-02 23:53:16',1,100000,100000,0,NULL),(56,'201911025','2019-11-02 23:54:14',1,1300000,1500000,0,NULL),(57,'201911031','2019-11-03 13:51:47',1,900000,1000000,0,NULL),(58,'201911051','2019-11-05 11:01:53',1,100000,100000,0,NULL),(59,'201911052','2019-11-05 11:02:15',1,255000,300000,0,NULL),(60,'201911053','2019-11-05 12:52:35',1,60000,100000,0,NULL),(61,'201911054','2019-11-05 12:52:46',1,140000,150000,0,NULL),(62,'201911055','2019-11-05 12:53:01',1,200000,200000,0,NULL),(63,'201911056','2019-11-05 12:53:12',1,100000,100000,0,NULL),(64,'201911057','2019-11-05 12:55:42',1,160000,200000,0,NULL),(65,'201911058','2019-11-05 12:55:59',1,150000,150000,0,NULL),(66,'201911059','2019-11-05 12:56:12',1,60000,100000,0,NULL),(67,'2019110510','2019-11-05 12:57:16',1,80000,100000,0,NULL),(68,'2019110511','2019-11-05 12:58:42',1,175000,200000,0,NULL),(69,'2019110512','2019-11-05 13:02:07',1,100000,100000,0,NULL),(70,'2019110513','2019-11-05 13:02:19',1,140000,150000,0,NULL),(71,'201911071','2019-11-07 14:26:56',1,80000,100000,0,NULL),(72,'201911072','2019-11-07 14:27:13',1,100000,100000,0,NULL),(73,'201911081','2019-11-08 10:33:33',1,60000,100000,0,NULL),(74,'201911082','2019-11-08 10:34:31',1,60000,100000,1,'2019-11-08 11:02:51'),(75,'201911131','2019-11-13 13:14:26',1,80000,100000,0,NULL),(76,'201911132','2019-11-13 13:44:23',1,80000,100000,0,NULL),(77,'201911133','2019-11-13 14:37:42',1,150000,200000,0,NULL),(78,'201911191','2019-11-19 10:05:10',1,100000,100000,0,NULL),(79,'201911192','2019-11-19 10:05:24',1,80000,100000,0,NULL),(80,'201911193','2019-11-19 10:07:23',1,40000,100000,0,NULL),(81,'201911281','2019-11-28 08:51:33',1,100000,100000,0,NULL),(82,'201912041','2019-12-04 08:37:49',1,80000,100000,0,NULL),(83,'201912042','2019-12-04 08:49:57',1,1000000,1000000,0,NULL),(84,'201912051','2019-12-05 08:40:51',1,80000,80000,0,NULL),(85,'201912052','2019-12-05 08:41:17',1,60000,100000,0,NULL),(86,'201912053','2019-12-05 11:33:57',1,80000,100000,0,NULL),(87,'201912061','2019-12-06 09:23:44',1,100000,100000,0,NULL),(88,'201912062','2019-12-06 09:24:24',1,140000,150000,0,NULL),(89,'201912111','2019-12-11 10:37:32',1,120000,150000,0,NULL),(90,'201912112','2019-12-11 13:30:33',1,100000,100000,0,NULL),(91,'201912131','2019-12-13 09:00:48',1,120000,150000,0,NULL),(92,'201912161','2019-12-16 10:04:26',1,100000,100000,0,NULL),(93,'201912171','2019-12-17 08:53:10',1,1000000,1000000,0,NULL),(94,'201912181','2019-12-18 10:13:31',1,100000,100000,0,NULL),(95,'201912191','2019-12-19 10:05:39',1,2500000,2500000,0,NULL),(96,'202001061','2020-01-06 09:25:05',1,80000,100000,0,NULL),(97,'202001141','2020-01-14 09:30:00',1,200000,200000,0,NULL),(98,'202001142','2020-01-14 10:05:58',1,250000,250000,1,'2020-01-14 10:14:27'),(99,'202001143','2020-01-14 10:12:27',1,100000,100000,0,NULL),(100,'202001151','2020-01-15 09:28:50',1,800000,1000000,0,NULL),(101,'202001211','2020-01-21 13:09:11',1,1400000,1500000,0,NULL),(102,'202002021','2020-02-02 10:33:45',1,200000,200000,0,NULL),(103,'202002022','2020-02-02 10:34:29',1,50000,50000,1,'2020-02-02 10:34:42'),(104,'202002241','2020-02-24 21:09:38',1,100000,100000,0,NULL),(105,'202003061','2020-03-06 09:38:56',1,40000,50000,0,NULL),(106,'202003171','2020-03-17 13:35:19',1,80000,100000,0,NULL),(107,'202003191','2020-03-19 10:29:04',1,250000,300000,0,NULL),(108,'202003261','2020-03-26 13:54:49',1,100000,100000,0,NULL),(109,'202003262','2020-03-26 14:09:19',1,35000,40000,0,NULL),(110,'202003291','2020-03-29 16:17:10',1,60000,100000,0,NULL),(111,'202004011','2020-04-01 13:33:29',1,60000,100000,0,NULL),(112,'202004012','2020-04-01 13:38:42',1,60000,100000,1,'2020-04-01 13:41:49'),(113,'202004013','2020-04-01 13:44:35',1,60000,100000,1,'2020-04-01 13:45:13'),(114,'202004021','2020-04-02 09:04:53',1,75000,100000,0,NULL);

/*Table structure for table `header_pembelian` */

DROP TABLE IF EXISTS `header_pembelian`;

CREATE TABLE `header_pembelian` (
  `id_pembelian` int(11) NOT NULL AUTO_INCREMENT,
  `id_supplier` int(11) NOT NULL DEFAULT '1',
  `no_faktur` varchar(30) NOT NULL DEFAULT '-',
  `tgl_pembelian` datetime NOT NULL,
  `keterangan` text NOT NULL,
  `total_pembelian` decimal(19,4) NOT NULL,
  `status_delete` tinyint(1) NOT NULL DEFAULT '0',
  `tgl_delete` datetime DEFAULT NULL,
  PRIMARY KEY (`id_pembelian`),
  KEY `tgl_pembelian` (`tgl_pembelian`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;

/*Data for the table `header_pembelian` */

insert  into `header_pembelian`(`id_pembelian`,`id_supplier`,`no_faktur`,`tgl_pembelian`,`keterangan`,`total_pembelian`,`status_delete`,`tgl_delete`) values (37,2,'HYU','2020-03-19 00:00:00','-',0.0000,0,NULL),(38,2,'KUI','2020-03-19 00:00:00','-',0.0000,0,NULL),(39,2,'KOM','2020-03-26 00:00:00','-',0.0000,0,NULL),(40,2,'OABC','2020-03-28 00:00:00','-',0.0000,0,NULL);

/*Table structure for table `kode_generate` */

DROP TABLE IF EXISTS `kode_generate`;

CREATE TABLE `kode_generate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_transaksi` varchar(200) NOT NULL,
  `nama_alias` varchar(10) NOT NULL,
  `urutan` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `kode_generate` */

insert  into `kode_generate`(`id`,`nama_transaksi`,`nama_alias`,`urutan`) values (1,'kode barang','BRG',9);

/*Table structure for table `migration` */

DROP TABLE IF EXISTS `migration`;

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `migration` */

insert  into `migration`(`version`,`apply_time`) values ('m000000_000000_base',1564471846),('m190730_064935_create_user',1564471847),('m190730_070942_create_usergroup',1564471847),('m190806_062251_hd_transaksi',1565073446),('m190806_062305_dt_transaksi',1565073446),('m190806_064512_file_barang',1565074222),('m190812_022619_urutan_transaksi',1565577489),('m190918_070827_kode_generate',1568790908);

/*Table structure for table `setting_app` */

DROP TABLE IF EXISTS `setting_app`;

CREATE TABLE `setting_app` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_name` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `setting_app` */

/*Table structure for table `supplier` */

DROP TABLE IF EXISTS `supplier`;

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_supplier` varchar(100) NOT NULL DEFAULT '-',
  `alamat_supplier` varchar(100) NOT NULL DEFAULT '-',
  `no_telp` varchar(100) NOT NULL,
  `cp` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `supplier` */

insert  into `supplier`(`id`,`nama_supplier`,`alamat_supplier`,`no_telp`,`cp`) values (1,'-','-','0','0'),(2,'PT Berkah Jaya','Klaten','085672572453','Agus');

/*Table structure for table `urutan_transaksi` */

DROP TABLE IF EXISTS `urutan_transaksi`;

CREATE TABLE `urutan_transaksi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_transaksi` varchar(200) NOT NULL,
  `urutan` int(11) DEFAULT '0',
  `tgl_transaksi` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=latin1;

/*Data for the table `urutan_transaksi` */

insert  into `urutan_transaksi`(`id`,`nama_transaksi`,`urutan`,`tgl_transaksi`) values (11,'transaksi penjualan',3,'2019-09-30 03:22:33'),(12,'transaksi penjualan',3,'2019-10-01 07:14:35'),(13,'transaksi penjualan',1,'2019-10-09 08:28:16'),(14,'transaksi penjualan',1,'2019-10-13 12:46:39'),(15,'transaksi penjualan',1,'2019-10-17 03:35:48'),(16,'transaksi penjualan',1,'2019-10-28 13:08:46'),(17,'transaksi penjualan',8,'2019-11-01 03:46:20'),(18,'transaksi penjualan',5,'2019-11-02 05:30:16'),(19,'transaksi penjualan',1,'2019-11-03 13:51:47'),(20,'transaksi penjualan',13,'2019-11-05 11:01:53'),(21,'transaksi penjualan',2,'2019-11-07 14:26:56'),(22,'transaksi penjualan',2,'2019-11-08 10:33:33'),(23,'transaksi penjualan',3,'2019-11-13 13:14:26'),(24,'transaksi penjualan',3,'2019-11-19 10:05:10'),(25,'transaksi penjualan',1,'2019-11-28 08:51:33'),(26,'transaksi penjualan',2,'2019-12-04 08:37:49'),(27,'transaksi penjualan',3,'2019-12-05 08:40:51'),(28,'transaksi penjualan',2,'2019-12-06 09:23:44'),(29,'transaksi penjualan',2,'2019-12-11 10:37:32'),(30,'transaksi penjualan',1,'2019-12-13 09:00:48'),(31,'transaksi penjualan',1,'2019-12-16 10:04:26'),(32,'transaksi penjualan',1,'2019-12-17 08:53:10'),(33,'transaksi penjualan',1,'2019-12-18 10:13:31'),(34,'transaksi penjualan',1,'2019-12-19 10:05:39'),(35,'transaksi penjualan',1,'2020-01-06 09:25:05'),(36,'transaksi penjualan',3,'2020-01-14 09:30:00'),(37,'transaksi penjualan',1,'2020-01-15 09:28:50'),(38,'transaksi penjualan',1,'2020-01-21 13:09:11'),(39,'transaksi penjualan',2,'2020-02-02 10:33:45'),(40,'transaksi penjualan',1,'2020-02-24 21:09:38'),(41,'transaksi penjualan',1,'2020-03-06 09:38:56'),(42,'transaksi penjualan',1,'2020-03-17 13:35:19'),(43,'transaksi penjualan',1,'2020-03-19 10:29:04'),(44,'transaksi penjualan',2,'2020-03-26 13:54:49'),(45,'transaksi penjualan',1,'2020-03-29 16:17:10'),(46,'transaksi penjualan',3,'2020-04-01 13:33:29'),(47,'transaksi penjualan',1,'2020-04-02 09:04:53');

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `password` varchar(100) NOT NULL,
  `authkey` varchar(100) NOT NULL,
  `accesstoken` varchar(100) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `id_group` int(11) DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

/*Data for the table `user` */

insert  into `user`(`id`,`username`,`password`,`authkey`,`accesstoken`,`name`,`id_group`,`aktif`,`created_at`,`updated_at`) values (1,'nata','093d8a0793df4654fee95cc1215555b3','-','-','Administrator',1,1,'2019-10-09 07:23:10','2019-10-09 07:23:10'),(3,'admin','342acf8a1d482e65876ac159bc540a92','f7700fd6671892bb24883dd2745933e9','-','Administrator',1,1,'2019-10-15 03:07:10','2019-10-15 03:07:10');

/*Table structure for table `user_group` */

DROP TABLE IF EXISTS `user_group`;

CREATE TABLE `user_group` (
  `id_group` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(100) NOT NULL,
  `aktif` tinyint(1) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_group`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `user_group` */

insert  into `user_group`(`id_group`,`group_name`,`aktif`,`created_at`,`updated_at`) values (1,'admin',1,'2019-10-09 13:54:52','2019-10-09 13:54:56');

/* Trigger structure for table `detail_pembelian` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `pembelian_update_stok` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `pembelian_update_stok` AFTER INSERT ON `detail_pembelian` FOR EACH ROW BEGIN
	DECLARE stok_lama INT;
	DECLARE stok_baru INT;
	
	SELECT stok FROM `file_barang` WHERE kd_barang = NEW.kd_barang INTO stok_lama;
	
	SET stok_baru = stok_lama + NEW.jumlah;
	
	/*update stok*/
	UPDATE `file_barang` SET stok = stok_baru WHERE kd_barang = NEW.kd_barang;
	
	/*update harga jual*/
	UPDATE `file_barang` SET harga_jual = NEW.harga_jual WHERE kd_barang = NEW.kd_barang;
	
	/*update harga jual*/
	UPDATE `file_barang` SET harga_beli = NEW.harga_beli WHERE kd_barang = NEW.kd_barang;
    END */$$


DELIMITER ;

/* Trigger structure for table `dt_transaksi` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `penjualan_update_stok` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `penjualan_update_stok` AFTER INSERT ON `dt_transaksi` FOR EACH ROW BEGIN
	declare stok_lama INT;
	declare stok_baru INT;
	declare stok_lama_fsb INT;
	declare stok_baru_fsb INT;
	
	select stok from `file_barang` where kd_barang = NEW.kd_barang into stok_lama;
	
	SELECT stok_akhir FROM `file_stok_barang` WHERE id = NEW.id_stok_barang INTO stok_lama_fsb;
	
	set stok_baru = stok_lama - NEW.qty;
	set stok_baru_fsb = stok_lama_fsb - NEW.qty;
	
	update `file_barang` set stok = stok_baru where kd_barang = NEW.kd_barang;
	update `file_stok_barang` set stok_akhir = stok_baru_fsb where id = NEW.id_stok_barang;
	
    END */$$


DELIMITER ;

/* Trigger structure for table `dt_transaksi` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `penjualan_hapus_stok` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `penjualan_hapus_stok` AFTER UPDATE ON `dt_transaksi` FOR EACH ROW BEGIN
	declare stok_lama INT;
	declare stok_baru INT;
	DECLARE stok_lama_fsb INT;
	DECLARE stok_baru_fsb INT;
	
	if(NEW.status_hapus = 1)then
		SELECT stok FROM `file_barang` WHERE kd_barang = NEW.kd_barang INTO stok_lama;
		
		SELECT stok_akhir FROM `file_stok_barang` WHERE id = NEW.id_stok_barang INTO stok_lama_fsb;
		
		set stok_baru = stok_lama + NEW.qty;
		
		SET stok_baru_fsb = stok_lama_fsb + NEW.qty;
		
		UPDATE `file_barang` SET stok = stok_baru WHERE kd_barang = NEW.kd_barang;
		UPDATE `file_stok_barang` SET stok_akhir = stok_baru_fsb WHERE id = NEW.id_stok_barang;
	end if;
	
    END */$$


DELIMITER ;

/* Trigger structure for table `hd_transaksi` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `delete_detail_atk` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `delete_detail_atk` AFTER UPDATE ON `hd_transaksi` FOR EACH ROW BEGIN
	if new.status_hapus = 1 then
		update `dt_transaksi` set status_hapus = 1,tgl_hapus = NOW() where no_transaksi = new.no_transaksi;
	end if;
    END */$$


DELIMITER ;

/* Trigger structure for table `header_pembelian` */

DELIMITER $$

/*!50003 DROP TRIGGER*//*!50032 IF EXISTS */ /*!50003 `hapus_pembelian` */$$

/*!50003 CREATE */ /*!50017 DEFINER = 'root'@'localhost' */ /*!50003 TRIGGER `hapus_pembelian` AFTER UPDATE ON `header_pembelian` FOR EACH ROW BEGIN
	if(NEW.status_delete = 1)then
		update `detail_pembelian` set status_delete = 1, tgl_delete = curdate() where id_pembelian = NEW.id_pembelian;
	end if;
    END */$$


DELIMITER ;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
