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
) ENGINE=InnoDB AUTO_INCREMENT=236 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `file_barang` */

insert  into `file_barang`(`id`,`kd_barang`,`nama_barang`,`lokasi`,`harga_beli`,`harga_jual`,`stok`,`aktif`) values (11,'8993988270027','Joyko Oil Pastel 12 Colors Non Toxic','-',0,14500,0,1),(12,'8997019130090','Greebel Classic Colour Pencils (12)','-',0,15000,0,1),(13,'8997013538045','Pensil Warna Super Color Big good product (12 Colos)','-',0,10000,0,1),(14,'8993988270034','Joyko Oil Pastel 18 Colors','-',0,28000,0,1),(15,'8993988270102','Joyko 12 Oil Pastel','-',0,12500,0,1),(16,'6925411099822','Debozz Oil Pastel 12 Colors','-',0,11500,0,1),(17,'8997013538038','Pensil Warna Super Colors Big good product 12','-',0,18500,0,1),(18,'4005401158523','Faber Castell 12 Classic Colour Pencils','-',0,32500,0,1),(19,'4005401200635','Faber Castell 12 Hexagonal Oil Pastels','-',0,32500,0,1),(20,'8993988708650','Joyko Acrylic Colour 12','-',0,51000,0,1),(21,'4005401548157','Stabillo Faber Castle','-',0,11000,0,1),(22,'6935840913491','Stabilo 3 Way Boxer','-',0,3500,0,1),(23,'6922227308902','Trifelo TF-1143','-',0,3000,0,1),(24,'8993988130048','Stabilo Joyko','-',0,5000,0,1),(25,'4006381333672','StabiloBoss','-',0,8000,0,1),(26,'6924247417138','Stabilo Joyko','-',0,5000,0,1),(27,'6924247417084','Stabilo NHON','-',0,4000,0,1),(28,'6973889050104','Stabilo XC-036','-',0,26000,0,1),(29,'6924247437136','Stabilo 2 Color CHOSCH','-',0,5000,0,1),(30,'8993988130475','Stabilo Joyko','-',0,5000,0,1),(31,'8993988130062','Stabilo Joyko','-',0,5000,0,1),(32,'8993988130079','Stabilo Joyko','-',0,5000,0,1),(33,'4005401190455','Paket Uian Standar Faber Castle','-',0,24500,0,1),(34,'8997010762498','Paket Uian Mantap Faber Castle','-',0,32500,0,1),(35,'8997023380436','Paket Sukses Ujian','-',0,19500,0,1),(36,'4970129729037','Snowman umbo Marker Biru','-',0,21000,0,1),(37,'1102320010178','Bolpoint Stadard B\'Live','-',0,3500,0,1),(38,'011298720013','Bolpoint Bolpenku','-',0,2500,0,1),(39,'1100120020472','Bolpoint Standard AE7','-',0,2000,0,1),(40,'4970129703020','Bolpoint Snowman V3','-',0,3500,0,1),(41,'4970129745020','Bollpoint Snowmn V5','-',0,3500,0,1),(42,'4970129703037','Snowman V-3 0.5 mm Biru','-',0,3500,0,1),(43,'4970129703013','Snowman V-3 Hitam','-',0,3500,0,1),(44,'6923655547512','Bolpoint Tizo Black','-',0,2500,0,1),(45,'6923655546577','Bollpoint Tizo 0.5 Black','-',0,2500,0,1),(46,'8802207085062','Bollpoint DirectFill X-2','-',0,1500,0,1),(47,'8802219085050','Bollpoint Direct Fill M-1','-',0,1500,0,1),(48,'4007817104767','Pensil Staedler 5B','-',0,4500,0,1),(49,'8996680170183','Pensil Grebel 2B','-',0,4000,0,1),(50,'4007817104798','Pensil Staedler 2B','-',0,4500,0,1),(51,'4005401990079','Pensil Staedler 2B','-',0,4500,0,1),(52,'8993988283447','Joyko I-ech 2 GP-266','-',0,3500,0,1),(53,'6923655544870','Tizo Gel Ink Pen','-',0,2500,0,1),(54,'BRG000054','Bolpoint Pilot Stylo a Bille','-',0,13000,0,1),(55,'8998838360972','Bolpoint Kenko Hi-Tech 0.4 mm','-',0,5000,0,1),(56,'4970129055013','Snowman 700 Calligraphy Pen','-',0,9000,0,1),(57,'4970129759515','Brush Pen Hitam','-',0,6500,0,1),(58,'4970129759683','Snowman Brush Pen Biru Tua','-',0,6500,0,1),(59,'4970129759522','Snowman Brush Pen Merah','-',0,6500,0,1),(60,'4970129759546','Snowman Brushman Hiau','-',0,6500,0,1),(61,'4970129759607','Brush Pen Biru','-',0,6500,0,1),(62,'8993988130444','Joyko Highlighter (Joyko)','-',0,4000,0,1),(63,'4970129022541','Snowman OPF Hijau','-',0,9000,0,1),(64,'4970129022527','Snowman OPF Merah','-',0,9000,0,1),(65,'4970129023517','Snowman OPF Hitam','-',0,9000,0,1),(66,'4970129023524','Snowman OPM Merah','-',0,9000,0,1),(67,'4970129023531','Snowan OPM Biru','-',0,9000,0,1),(68,'4970129023548','Snowman OPM Hiau','-',0,9000,0,1),(69,'4970129006640','Snowman White (Spidol Putih Besar)','-',0,16500,0,1),(70,'4970129007647','Snowman White (Spidol Putih Kecil)','-',0,16500,0,1),(71,'4970129007654','Snowman Silver (Spidol Silver Kecil)','-',0,16500,0,1),(72,'4970129007661','Snowman Gold (Spidol Emas Kecil)','-',0,16500,0,1),(73,'4970129006657','Snowman Silver (Spidol Silver Besar)','-',0,16500,0,1),(74,'4970129006664','Snowman Gold (Spidol Emas Besar)','-',0,16500,0,1),(75,'4970129028512','Drawing Pen 0.1','-',0,9000,0,1),(76,'4970129029519','Drawing Pen 0.2','-',0,9000,0,1),(77,'4970129030515','Drawing Pen 0.3','-',0,9000,0,1),(78,'4970129063513','Drawing Pen 0.4','-',0,9000,0,1),(79,'4970129031512','Drawing Pen 0.5','-',0,9000,0,1),(80,'4970129064510','Drawing Pen 0.6','-',0,9000,0,1),(81,'4970129032519','Drawing Pen 0.7','-',0,9000,0,1),(82,'4970129065517','Drawing Pen 0.8','-',0,9000,0,1),(83,'BRG000083','Pentel Rolling Writer','-',0,23000,0,1),(84,'8998838360958','Hitech Kenko Biru 0.4','-',0,5000,0,1),(85,'4970129056515','Snowman Drawing Pen 2.0 For Caligraphy','-',0,9000,0,1),(86,'4970129057512','Drawing Pen 3.0 Caligraphy / Kaligrafi','-',0,9000,0,1),(87,'4970129055518','Drawing Pen 1.0 Caligraphy','-',0,9000,0,1),(88,'4902505139314','Hi-Tec-C Pilot Hitam','-',0,27000,0,1),(89,'4902505139338','Hi-Tec-C Pilot Hitam','-',0,27000,0,1),(90,'4902505166457','Drwaing Pen Pilot 0.3','-',0,27000,0,1),(91,'BRG000091','Drawing Pen Pilot 21000','-',0,21000,0,1),(92,'6924898208123','Kalkulator Canzem CT-812BN','-',0,33000,0,1),(93,'6925625437472','Kenko KK-82MS-5','-',0,40000,0,1),(94,'6952318500601','Kalkultor Citizen CT-320','-',0,30000,0,1),(95,'6925625440878','Kalkulator Kenko KK-402','-',0,15000,0,1),(96,'6926801901121','Kalkulator Citizetv','-',0,28500,0,1),(97,'6925625411526','Kalkulator Kenko KK-185','-',0,25000,0,1),(98,'6924898208888','Kalkulator Canzen CT-888','-',0,97000,0,1),(99,'6927729007148','Kalkulator Citizen CT-714D','-',0,96000,0,1),(100,'6924898266147','Kalkulator Citizen CT-6614','-',0,115000,0,1),(101,'6924898298162','Kalkulator Citizen CT-9816','-',0,100000,0,1),(102,'6926801920009','Kalklator Citizetv CT-2000TV','-',0,56000,0,1),(103,'6924898288187','Kalkulator Citizen CT-8818','-',0,68500,0,1),(104,'8997031736362','Canon Blue Print Tinta Printer','-',0,49000,0,1),(105,'8997031730629','Canon Blue Print Biru Tinta Printer','-',0,49500,0,1),(106,'8997031730643','Canon Blue Print Tinta Kuning','-',0,49500,0,1),(107,'BRG000107','Pekt Whiteboar Marker muvon','-',0,39000,0,1),(108,'8997014330020','Canon Printer Data Print  Tinta Hitam DP 40','-',0,37500,0,1),(109,'8997014330037','Canon Printer Data Print  Tinta Wana DP 41','-',0,32500,0,1),(110,'BRG000110','Tinta Printer Epson Ribbon Pack #7755','-',0,12000,0,1),(111,'8886022830243','Baterai ABC','-',0,3000,0,1),(112,'BRG000112','CD RW plus ','-',0,7500,0,1),(113,'BRG000113','CD R plus','-',0,5000,0,1),(114,'BRG000114','DVD R plus','-',0,6000,0,1),(115,'704315007616','Full Mark Nylon Printer Ribbon','-',0,32500,0,1),(116,'041689300494','Zippo Lighter Fluid','-',0,32500,0,1),(117,'BRG000117','Tempat CD','-',0,2500,0,1),(118,'BRG000109','Times CD-RW 700MB 80 Min','-',0,5700,0,1),(119,'8997026933592','Pita Print E-Print','-',0,6000,0,1),(120,'4970129727033','Sowman White Board Marker','-',0,8500,0,1),(121,'6925473855749','Tata gel Hapus 0.5 mm','-',0,3500,0,1),(122,'4970129726036','Snowman Marker Giant Biru','-',0,7500,0,1),(123,'BRG000123','Ball Point Pilot BPT-P','-',0,2500,0,1),(124,'4970129701019','Snowman V-1 1.0 Semi-Gwl Pen','-',0,2500,0,1),(125,'4970129701026','Snowman V-1 1.0 Ball Point Pen ( Tinta Merah)','-',0,2500,0,1),(126,'6925473855763','Erasable Gel Again 2026 Football','-',0,3500,0,1),(127,'6925473855619','Debozz Erasable 0.5 mm DB-593ER','-',0,3500,0,1),(128,'6925473855732','Erasable Gel G-306ER Gel Hapus','-',0,3500,0,1),(129,'BRG000129','Joyko  GP-157 CometGel','-',0,4000,0,1),(130,'6925473855756','Gel Hapus G-308ER 0.5 mm Motor','-',0,3500,0,1),(131,'6925473855688','Erasable Gel G-301ER 0.5 mm','-',0,3500,0,1),(132,'6925473855695','Erasable Gel G-302ER Fifa World Cup','-',0,3500,0,1),(133,'6925473855671','Gel Hapus Erasable Gel  G-300ER','-',0,3500,0,1),(134,'9556089008167','Fber Castle 0.7 RX7','-',0,4000,0,1),(135,'6925473855725','Erasable Gel G-305ER 0.5mm','-',0,3500,0,1),(136,'8998000501530','Zebra Penciltic','-',0,4500,0,1),(137,'8997013535433','Big Good Product Pensil 2B Neon Black 9203','-',0,2000,0,1),(138,'8997013535044','Metalic Glow 966 pencil series Big good statiomery','-',0,2500,0,1),(139,'8997013535334','Big good produuct Gold Spot pensil 2B 9201','-',0,2000,0,1),(140,'8997013530308','Big good product Pensil 2B Executive gold pencils','-',0,2000,0,1),(141,'BRG000141','Kacamata 13000','-',0,13000,0,1),(142,'BRG000142','Kacamata 15000','-',0,15000,0,1),(143,'BRG000143','Kacamata 16000','-',0,16000,0,1),(144,'BRG000144','Kacamata 17500','-',0,17500,0,1),(145,'BRG000145','Kacamata 18000','-',0,18000,0,1),(146,'BRG000146','Kacamata 22000','-',0,22000,0,1),(147,'BRG000147','Kacamata 20000','-',0,20000,0,1),(148,'8802018650039','CNX-5202 Isi Tinta','-',0,1000,0,1),(149,'BRG000149','Kaos Kaki SD Capello (SD School Shock 19-20)','-',0,15000,0,1),(150,'BRG000150','Kaos Kaki SD Capello (SD School Shock 21-22)','-',0,15500,0,1),(151,'BRG000151','Kaos Kaki SMP Capello (SMP School Shock 21-22)','-',0,16000,0,1),(152,'9211802','X\'TEND Kaos Kaki Putih 23-24','-',0,16500,0,1),(153,'BRG000153','Kaos Kaki SMP Capello (SMP School Shock 23-24)','-',0,16500,0,1),(154,'9211902','Kaos Kaki X\'TEND Putih 25-26','-',0,16500,0,1),(155,'BRG000155','Kaos Kaki Hitam SMA Spandex','-',0,9500,0,1),(156,'822728729970','Kaos Kaki Pramuka SD Spandex Hitam','-',0,8000,0,1),(157,'BRG000157','Kaos Kaki Pramuka SMP Spandex Hitam','-',0,8000,0,1),(158,'BRG000158','Kaos Kaki SMA Capello (SMA School Shock 25-26)','-',0,17500,0,1),(159,'BRG000159','Kaos Kaki SMA Capello (SMA School Shock 25-26) Hitam Putih','-',0,18000,0,1),(160,'BRG000160','Kaos Kaki SMP Capello (SMP School Shock 23-24 Hitam Putih)','-',0,17000,0,1),(161,'BRG000161','Kaos Kaki SMA Capello (SMA School Shock 25-26) Hitam Putih','-',0,18500,0,1),(162,'BRG000162','Kaos Kaki SMP Capello (SMP School Shock 25-26) Hitam Putih','-',0,17500,0,1),(163,'9114833','Kaos kaki X\'TEND HItam Putih','-',0,17500,0,1),(164,'BRG000164','Kaos Kaki SD Capello Hitam(SD School Shock 21-22)','-',0,15500,0,1),(165,'BRG000165','Kaos Kaki SD Capello (SD School Shock 17-18 Hitam)','-',0,14500,0,1),(166,'BRG000166','Kaos Kaki SD Capello (SD School Shock 19-20 Hitam)','-',0,15000,0,1),(167,'BRG000167','Kaos Kaki Ijjtihad Wa Taqwa','-',0,17000,0,1),(168,'BRG000168','Kaos Kaki Qman Girls Sock','-',0,13000,0,1),(169,'BRG000169','Kaos Kaki Ijtihad Wa Taqwa Putih','-',0,15000,0,1),(170,'BRG000170','Kaos Kaki Ijtihad Wa Taqwa Putih La Style Selutut','-',0,16000,0,1),(171,'BRG000171','Kaos Kaki Ijtihad Wa Taqwa Putih Telapak Hitam','-',0,17000,0,1),(172,'BRG000172','Kaos Kaki Ijtihad Wa Taqwa Putih Telapak Hitam','-',0,16500,0,1),(173,'BRG000173','Kaos Kaki Ijtihad Wa Taqwa Putih Hitam Spandex Linking','-',0,15000,0,1),(174,'BRG000174','Kaos Kaki Ijtihad Wa Taqwa Hitam Pendek','-',0,8000,0,1),(175,'BRG000175','Kaos Kaki HItam Boxer 2 jari','-',0,7500,0,1),(176,'BRG000176','Kaos kaki hitam Fit Bob','-',0,10000,0,1),(177,'BRG000177','Kaos Kaki Ijtihad Wa Taqwa Coklat Telapak Hitam','-',0,16500,0,1),(178,'BRG000178','Kaos Kaki Ijtihad Wa Taqwa Coklat Telapak Hitam','-',0,15000,0,1),(179,'BRG000179','Kaos kaki Hitam Muzdalifah','-',0,12000,0,1),(180,'BRG000180','Kaos Kaki Putih Muzdalifah','-',0,12000,0,1),(181,'BRG000181','Kaos kaki Coklat Motif Muzdalifah','-',0,14000,0,1),(182,'BRG000182','Buku Kisah Nabi Ismail','-',0,5000,0,1),(183,'BRG000183','Kisah Nabi Sholeh','-',0,5000,0,1),(184,'BRG000184','Kisah Nabi Ayub','-',0,5000,0,1),(185,'BRG000185','Kisah Nabi Hud','-',0,5000,0,1),(186,'BRG000186','Kisah Nabi Yakub','-',0,5000,0,1),(187,'BRG000187','Kisah Nabi Isa','-',0,5000,0,1),(188,'BRG000188','Kisah Nabi Harun','-',0,5000,0,1),(189,'BRG000189','Kisah Nabi Yusuf','-',0,5000,0,1),(190,'BRG000190','Kisah Nabi Nuh','-',0,5000,0,1),(191,'BRG000191','Kisah Nabi Idris','-',0,5000,0,1),(192,'BRG000192','Kisah Nabi Ibrahim','-',0,5000,0,1),(193,'BRG000193','Kisah Nabi Muhammad','-',0,5000,0,1),(194,'BRG000194','Kisah Nabi Adam','-',0,5000,0,1),(195,'BRG000195','Kisah Nabi Yakub','-',0,5000,0,1),(196,'BRG000196','Kisah Nabi Ilyas','-',0,5000,0,1),(197,'BRG000197','Kisah Nabi Syuaib','-',0,5000,0,1),(198,'BRG000198','Kisah Nabi Ilyasa','-',0,5000,0,1),(199,'BRG000199','Kisah Nabi Zakariya','-',0,5000,0,1),(200,'BRG000200','Kisah Nabi Sulaiman','-',0,5000,0,1),(201,'BRG000201','Kisah Nabi Yunus','-',0,5000,0,1),(202,'BRG000202','Kisah Nabi Ishaq','-',0,5000,0,1),(203,'BRG000203','Kisah Nabi Yahya','-',0,5000,0,1),(204,'BRG000204','Nabi Zakria as. Potret Zakaria as','-',0,7500,0,1),(205,'9786237868644','Semangat Anak Gajah','-',0,6000,0,1),(206,'9786237868637','Nurng Unta dan Kasuari','-',0,6000,0,1),(207,'9786237868569','Eyang Katak Yang Bijaksana','-',0,6000,0,1),(208,'9786237868613','Anak Domba Yang Tersesat','-',0,6000,0,1),(209,'9786237868583','Burung Gagak Yang Cerdik','-',0,6000,0,1),(210,'9786237868620','Ratu Lebah Yang Sombong','-',0,6000,0,1),(211,'9786237868590','Nasihat Burung Kenari','-',0,6000,0,1),(212,'9786237868606','Anak Beruang Yang Mandiri','-',0,6000,0,1),(213,'9786029323597','Menggunting dan Menempel 5','-',0,5500,0,1),(214,'9786029323580','Menggunting dan Menempel 4','-',0,6500,0,1),(215,'9786029323573','Menggunting dan Menempel 3','-',0,5500,0,1),(216,'9786029323566','Menggnting dan Menempel 2','-',0,5500,0,1),(217,'9786029323481','Mencocok dan Mewarna 3','-',0,5500,0,1),(218,'9786029323474','Mencocok dan Mewarna 2','-',0,5500,0,1),(219,'BRG000219','Belajar Mewarna Seri Kapal','-',0,4000,0,1),(220,'BRG000220','Belajar Mewarna Seri Superman 2','-',0,4000,0,1),(221,'BRG000221','Belajar Mewarna Seri Superman','-',0,4000,0,1),(222,'BRG000222','Belaar Mewarna Burung 2','-',0,4000,0,1),(223,'BRG000223','Belaar Mewarna Buah 2','-',0,4000,0,1),(224,'BRG000224','Coloring Book Electronic series','-',0,3500,0,1),(225,'BRG000225','Mengenal dan Mewarnai Huruf Hjjaiyah','-',0,6500,0,1),(226,'BRG000226','Toli dan Menangan Yang Cerdik','-',0,9500,0,1),(227,'BRG000227','Kiti dan Keti','-',0,10500,0,1),(228,'BRG000228','Buku Kegiatan, Mewarna Mengenal Hruf Alquran','-',0,5500,0,1),(229,'BRG000229','Si Piyek dan anak Kwek kwek','-',0,9500,0,1),(230,'BRG000230','Mencari Jejak ','-',0,6500,0,1),(231,'BRG000231','Kancil dan Kuci','-',0,7000,0,1),(232,'BRG000232','Sang Itik dan Si Koi','-',0,10500,0,1),(233,'BRG000233','Kisah Acil dan Kimang','-',0,10500,0,1),(234,'BRG000234','Menggunting, Menempel, Mewarna Seri 2','-',0,6000,0,1),(235,'BRG000235','Menggunting, Menempel, Mewarna Seri 1','-',0,6000,0,1);

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

insert  into `kode_generate`(`id`,`nama_transaksi`,`nama_alias`,`urutan`) values (1,'kode barang','BRG',236);

/*Table structure for table `migration` */

DROP TABLE IF EXISTS `migration`;

CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `migration` */

insert  into `migration`(`version`,`apply_time`) values ('m000000_000000_base',1658131264),('m190730_064935_create_user',1658131266),('m190730_070942_create_usergroup',1658131266),('m190806_062251_hd_transaksi',1658131266),('m190806_062305_dt_transaksi',1658131266),('m190806_064512_file_barang',1658131266),('m190812_022619_urutan_transaksi',1658131266),('m190918_070827_kode_generate',1658131266),('m220718_063102_supplier',1658131266),('m220718_063550_setting_app',1658131266),('m220718_064351_header_pembelian',1658131266),('m220718_065135_detail_pembelian',1658131267),('m220718_071435_file_stok_barang',1658131267);

/*Table structure for table `pengeluaran` */

DROP TABLE IF EXISTS `pengeluaran`;

CREATE TABLE `pengeluaran` (
  `id` int NOT NULL AUTO_INCREMENT,
  `deskripsi` varchar(100) NOT NULL,
  `nilai` decimal(10,0) NOT NULL,
  `tanggal` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `pengeluaran` */

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
