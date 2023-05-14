-- mysqldump-php https://github.com/ifsnop/mysqldump-php
--
-- Host: localhost	Database: inventoryweb_ci4
-- ------------------------------------------------------
-- Server version 	10.4.24-MariaDB
-- Date: Sun, 14 May 2023 04:01:46 +0000

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40101 SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `barang`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `barang` (
  `brgid` int(11) NOT NULL AUTO_INCREMENT,
  `brgkode` char(14) NOT NULL,
  `brgnama` varchar(100) NOT NULL,
  `brgkatid` int(10) unsigned NOT NULL,
  `brglokid` int(10) unsigned DEFAULT NULL,
  `brgsatid` int(10) unsigned NOT NULL,
  `brgharga` double NOT NULL,
  `brgstok` double NOT NULL,
  `brggambar` varchar(200) DEFAULT NULL,
  `jumlahkebutuhantahun` double DEFAULT 0,
  `biayapesan` double DEFAULT 0,
  `biayapenyimpanan` double DEFAULT 0,
  `penjualantertinggiharian` double DEFAULT 0,
  `leadtimeterlama` double DEFAULT 0,
  `ratapenjualanharian` double DEFAULT 0,
  `rataleadtime` double DEFAULT 0,
  PRIMARY KEY (`brgid`),
  UNIQUE KEY `brgkode` (`brgkode`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barang`
--

LOCK TABLES `barang` WRITE;
/*!40000 ALTER TABLE `barang` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `barang` VALUES (2,'L01R010523001','test 5',1,1,1,200,48,NULL,0,0,0,0,0,0,0),(3,'L01R010523002','test 2',1,1,1,400,-5,NULL,4000,350,0.025,10,30,10,20),(4,'L01R010523003','Ember',1,1,1,30000,40,'1683688382_efa4d07ac6c8303e8968.jpeg',5000,30000,0.025,20,30,10,20),(5,'L02R010523001','Tas',1,2,1,0,0,NULL,0,0,0,0,0,0,0);
/*!40000 ALTER TABLE `barang` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `barang` with 4 row(s)
--

--
-- Table structure for table `barangkeluar`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `barangkeluar` (
  `faktur` varchar(20) NOT NULL,
  `tglfaktur` date DEFAULT NULL,
  `namapelanggan` varchar(255) DEFAULT NULL,
  `totalharga` double DEFAULT NULL,
  `jumlahuang` double NOT NULL,
  `sisauang` double NOT NULL,
  `order_id` char(20) NOT NULL,
  `transaction_status` varchar(50) NOT NULL,
  `payment_type` varchar(50) NOT NULL,
  `payment_method` enum('C','M') NOT NULL,
  `inputby` int(11) NOT NULL,
  PRIMARY KEY (`faktur`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barangkeluar`
--

LOCK TABLES `barangkeluar` WRITE;
/*!40000 ALTER TABLE `barangkeluar` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `barangkeluar` VALUES ('FK0605230001','2023-05-06','Pelanggan test',1600,0,0,'','','','C',3),('FK0605230002','2023-05-06','Pelanggan 1',200,1,-200,'','','','C',3),('FK0605230003','2023-05-06','Pelanggan 1',1600,400,0,'','','','C',3);
/*!40000 ALTER TABLE `barangkeluar` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `barangkeluar` with 3 row(s)
--

--
-- Table structure for table `barangmasuk`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `barangmasuk` (
  `faktur` varchar(20) NOT NULL,
  `tglfaktur` date DEFAULT NULL,
  `totalharga` double DEFAULT NULL,
  `inputby` int(11) NOT NULL,
  PRIMARY KEY (`faktur`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barangmasuk`
--

LOCK TABLES `barangmasuk` WRITE;
/*!40000 ALTER TABLE `barangmasuk` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `barangmasuk` VALUES ('F050523001','2023-05-14',910000,1);
/*!40000 ALTER TABLE `barangmasuk` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `barangmasuk` with 1 row(s)
--

--
-- Table structure for table `barangpermintaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `barangpermintaan` (
  `nosuratjalan` varchar(20) NOT NULL,
  `tglpermintaan` date DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `inputby` int(11) NOT NULL,
  PRIMARY KEY (`nosuratjalan`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `barangpermintaan`
--

LOCK TABLES `barangpermintaan` WRITE;
/*!40000 ALTER TABLE `barangpermintaan` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `barangpermintaan` VALUES ('FSJ0905230001','2023-05-09','waiting',1);
/*!40000 ALTER TABLE `barangpermintaan` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `barangpermintaan` with 1 row(s)
--

--
-- Table structure for table `detail_barangkeluar`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detail_barangkeluar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `detfaktur` varchar(20) DEFAULT NULL,
  `detbrgkode` varchar(14) DEFAULT NULL,
  `dethargajual` double DEFAULT NULL,
  `detjml` int(11) DEFAULT NULL,
  `detsubtotal` double DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_barangkeluar`
--

LOCK TABLES `detail_barangkeluar` WRITE;
/*!40000 ALTER TABLE `detail_barangkeluar` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `detail_barangkeluar` VALUES (32,'FK0605230001','L01R010523001',200,2,400),(33,'FK0605230001','L01R010523002',400,3,1200),(34,'FK0605230002','L01R010523001',200,1,200),(35,'FK0605230003','L01R010523002',400,4,1600);
/*!40000 ALTER TABLE `detail_barangkeluar` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `detail_barangkeluar` with 4 row(s)
--

--
-- Table structure for table `detail_barangmasuk`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detail_barangmasuk` (
  `iddetail` bigint(20) NOT NULL AUTO_INCREMENT,
  `detfaktur` varchar(20) DEFAULT NULL,
  `detbrgkode` varchar(14) DEFAULT NULL,
  `dethargamasuk` double DEFAULT NULL,
  `dethargajual` double DEFAULT NULL,
  `detjml` int(11) DEFAULT NULL,
  `detsubtotal` double DEFAULT NULL,
  PRIMARY KEY (`iddetail`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_barangmasuk`
--

LOCK TABLES `detail_barangmasuk` WRITE;
/*!40000 ALTER TABLE `detail_barangmasuk` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `detail_barangmasuk` VALUES (41,'F050523001','L01R010523001',200,200,50,10000),(42,'F050523001','L01R010523003',30000,30000,30,900000);
/*!40000 ALTER TABLE `detail_barangmasuk` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `detail_barangmasuk` with 2 row(s)
--

--
-- Table structure for table `detail_barangpermintaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `detail_barangpermintaan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `detnosuratjalan` varchar(20) DEFAULT NULL,
  `detbrgkode` varchar(14) DEFAULT NULL,
  `detjml` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detail_barangpermintaan`
--

LOCK TABLES `detail_barangpermintaan` WRITE;
/*!40000 ALTER TABLE `detail_barangpermintaan` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `detail_barangpermintaan` VALUES (32,'FSJ0905230001','L01R010523001',10),(33,'FSJ0905230001','L01R010523002',10);
/*!40000 ALTER TABLE `detail_barangpermintaan` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `detail_barangpermintaan` with 2 row(s)
--

--
-- Table structure for table `kategori`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kategori` (
  `katid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `katnama` varchar(50) NOT NULL,
  KEY `katid` (`katid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori`
--

LOCK TABLES `kategori` WRITE;
/*!40000 ALTER TABLE `kategori` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `kategori` VALUES (1,'Perabotan');
/*!40000 ALTER TABLE `kategori` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `kategori` with 1 row(s)
--

--
-- Table structure for table `levels`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `levels` (
  `levelid` int(11) NOT NULL AUTO_INCREMENT,
  `levelnama` varchar(50) NOT NULL,
  PRIMARY KEY (`levelid`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `levels`
--

LOCK TABLES `levels` WRITE;
/*!40000 ALTER TABLE `levels` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `levels` VALUES (1,'Admin'),(3,'Karyawan'),(4,'Pembelian');
/*!40000 ALTER TABLE `levels` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `levels` with 3 row(s)
--

--
-- Table structure for table `lokasi`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lokasi` (
  `lokid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `loklorong` varchar(255) NOT NULL,
  `lokrak` varchar(255) NOT NULL,
  `lokkode` varchar(255) NOT NULL,
  KEY `lokid` (`lokid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lokasi`
--

LOCK TABLES `lokasi` WRITE;
/*!40000 ALTER TABLE `lokasi` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `lokasi` VALUES (1,'LORONG01','RAK01','L01R01'),(2,'LORONG02','RAK01','L02R01');
/*!40000 ALTER TABLE `lokasi` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `lokasi` with 2 row(s)
--

--
-- Table structure for table `migrations`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `migrations` VALUES (1,'2023-05-03-030403','App\\Database\\Migrations\\Kategori','default','App',1683165142,1),(2,'2023-05-03-030422','App\\Database\\Migrations\\Satuan','default','App',1683165142,1),(3,'2023-05-03-031117','App\\Database\\Migrations\\Barang','default','App',1683165143,1),(4,'2023-05-04-010159','App\\Database\\Migrations\\Lokasi','default','App',1683165143,1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `migrations` with 4 row(s)
--

--
-- Table structure for table `satuan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `satuan` (
  `satid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `satnama` varchar(50) NOT NULL,
  KEY `satid` (`satid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `satuan`
--

LOCK TABLES `satuan` WRITE;
/*!40000 ALTER TABLE `satuan` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `satuan` VALUES (1,'PCS');
/*!40000 ALTER TABLE `satuan` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `satuan` with 1 row(s)
--

--
-- Table structure for table `temp_barangkeluar`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `temp_barangkeluar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `detfaktur` varchar(20) DEFAULT NULL,
  `detbrgkode` varchar(14) DEFAULT NULL,
  `dethargajual` double DEFAULT NULL,
  `detjml` int(11) DEFAULT NULL,
  `detsubtotal` double DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_barangkeluar`
--

LOCK TABLES `temp_barangkeluar` WRITE;
/*!40000 ALTER TABLE `temp_barangkeluar` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `temp_barangkeluar` VALUES (32,'FK0905230001','L01R010523001',200,1,200),(33,'FK1405230001','L01R010523001',200,20,4000);
/*!40000 ALTER TABLE `temp_barangkeluar` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `temp_barangkeluar` with 2 row(s)
--

--
-- Table structure for table `temp_barangmasuk`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `temp_barangmasuk` (
  `iddetail` bigint(20) NOT NULL AUTO_INCREMENT,
  `detfaktur` varchar(20) DEFAULT NULL,
  `detbrgkode` varchar(14) DEFAULT NULL,
  `dethargamasuk` double DEFAULT NULL,
  `dethargajual` double DEFAULT NULL,
  `detjml` int(11) DEFAULT NULL,
  `detsubtotal` double DEFAULT NULL,
  PRIMARY KEY (`iddetail`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_barangmasuk`
--

LOCK TABLES `temp_barangmasuk` WRITE;
/*!40000 ALTER TABLE `temp_barangmasuk` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `temp_barangmasuk` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `temp_barangmasuk` with 0 row(s)
--

--
-- Table structure for table `temp_barangpermintaan`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `temp_barangpermintaan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `detnosuratjalan` varchar(20) DEFAULT NULL,
  `detbrgkode` varchar(14) DEFAULT NULL,
  `detjml` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `temp_barangpermintaan`
--

LOCK TABLES `temp_barangpermintaan` WRITE;
/*!40000 ALTER TABLE `temp_barangpermintaan` DISABLE KEYS */;
SET autocommit=0;
/*!40000 ALTER TABLE `temp_barangpermintaan` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `temp_barangpermintaan` with 0 row(s)
--

--
-- Table structure for table `users`
--

/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `usernama` varchar(100) NOT NULL,
  `usernamalengkap` varchar(255) NOT NULL,
  `userpassword` varchar(255) NOT NULL,
  `userlevelid` int(11) NOT NULL,
  `useraktif` char(1) DEFAULT '1',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `usernama` (`usernama`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
SET autocommit=0;
INSERT INTO `users` VALUES (1,'admin','Administrator','$2y$10$z5TJyJvV2SI6DiiyPVM94OYDouTl8btwgezgViTc5SUIn.W5zicGq',1,'1'),(5,'test','test crdu','$2y$10$mWK6DMcaAnMAdDHHIdGneePR9eUyD1IxjiCY0tGkFVOtqRSvG/hru',4,'1'),(6,'test1','test','$2y$10$3TfQsqMQeITelYDY/KIYSOM1xDb6fRHpzYKNFRLAkGzI4GogQhiAe',3,'1');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;

-- Dumped table `users` with 3 row(s)
--

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET AUTOCOMMIT=@OLD_AUTOCOMMIT */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on: Sun, 14 May 2023 04:01:47 +0000
