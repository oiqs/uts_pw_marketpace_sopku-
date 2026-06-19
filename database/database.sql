/*
SQLyog Ultimate v13.1.1 (64 bit)
MySQL - 8.0.30 : Database - marketplace
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`marketplace` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `marketplace`;

/*Table structure for table `cart_items` */

DROP TABLE IF EXISTS `cart_items`;

CREATE TABLE `cart_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `session_id` varchar(64) DEFAULT NULL,
  `product_id` int NOT NULL,
  `qty` int DEFAULT '1',
  `selected` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  KEY `idx_session_id` (`session_id`),
  CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `cart_items` */

insert  into `cart_items`(`id`,`user_id`,`session_id`,`product_id`,`qty`,`selected`,`created_at`) values 
(1,1,NULL,1,1,1,'2026-06-13 19:10:57'),
(2,1,NULL,2,2,1,'2026-06-13 19:10:57'),
(3,1,NULL,3,1,0,'2026-06-13 19:10:57');

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `categories` */

insert  into `categories`(`id`,`name`) values 
(1,'electronics'),
(2,'fashion-pria'),
(3,'fashion-wanita'),
(4,'aksesoris'),
(5,'tas-sepatu'),
(6,'kecantikan'),
(7,'rumah-dapur'),
(8,'olahraga'),
(9,'mainan-hobi'),
(10,'otomotif');

/*Table structure for table `couriers` */

DROP TABLE IF EXISTS `couriers`;

CREATE TABLE `couriers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `estimasi` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `couriers` */

insert  into `couriers`(`id`,`code`,`name`,`estimasi`,`price`) values 
(1,'jne-reg','JNE Regular','2-3 hari',15000.00),
(2,'jne-yes','JNE YES','1 hari',35000.00),
(3,'sicepat','SiCepat','1-2 hari',20000.00),
(4,'jnt','J&T Express','2-3 hari',13000.00),
(5,'gosend','GoSend Same Day','Hari ini',50000.00);

/*Table structure for table `order_items` */

DROP TABLE IF EXISTS `order_items`;

CREATE TABLE `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` int NOT NULL,
  `returned_qty` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `order_items` */

insert  into `order_items`(`id`,`order_id`,`product_id`,`title`,`image`,`price`,`qty`,`returned_qty`) values 
(1,1,1,'Fjallraven - Foldsack No. 1 Backpack, Fits 15 Laptops','https://fakestoreapi.com/img/81fPKd-2AYL._AC_SL1500_.jpg',1649250.00,1,0),
(2,1,2,'Mens Casual Premium Slim Fit T-Shirts','https://fakestoreapi.com/img/71-3HjGNDUL._AC_SY879._SX._UX._SY._UY_.jpg',334500.00,2,0);

/*Table structure for table `orders` */

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_code` varchar(50) NOT NULL,
  `user_id` int DEFAULT NULL,
  `nama` varchar(100) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `alamat` text NOT NULL,
  `kota` varchar(100) NOT NULL,
  `kodepos` varchar(10) NOT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  `courier_id` int DEFAULT NULL,
  `payment_method` enum('transfer','cod','ewallet','kartu') NOT NULL,
  `payment_detail` varchar(100) DEFAULT NULL,
  `promo_code` varchar(50) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT '0.00',
  `promo_discount` decimal(10,2) DEFAULT '0.00',
  `shipping_cost` decimal(10,2) DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL,
  `status` enum('pending','diproses','dikirim','selesai','batal') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_code` (`order_code`),
  KEY `user_id` (`user_id`),
  KEY `courier_id` (`courier_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`courier_id`) REFERENCES `couriers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `orders` */

insert  into `orders`(`id`,`order_code`,`user_id`,`nama`,`telepon`,`email`,`alamat`,`kota`,`kodepos`,`catatan`,`courier_id`,`payment_method`,`payment_detail`,`promo_code`,`subtotal`,`discount`,`promo_discount`,`shipping_cost`,`total`,`status`,`created_at`) values 
(1,'SKU-LX9F2A1',1,'oiQs Gemink','081234567890','user@sopku.com','Jl. Merdeka No. 10','Kudus','59311','Titip di pos satpam',1,'transfer','BCA — 1234567890','SHOPKU10',2318250.00,0.00,231825.00,15000.00,2101425.00,'pending','2026-06-13 19:10:57');

/*Table structure for table `products` */

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `rating_rate` decimal(2,1) DEFAULT '0.0',
  `rating_count` int DEFAULT '0',
  `stock` int DEFAULT '100',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `products` */

insert  into `products`(`id`,`category_id`,`title`,`description`,`price`,`image`,`rating_rate`,`rating_count`,`stock`,`created_at`) values 
(1,1,'Wireless Headphones','Headphone nirkabel dengan bantalan empuk, koneksi stabil, dan suara jernih untuk musik maupun meeting.',349000.00,'https://loremflickr.com/640/640/wireless,headphones?lock=1001',4.6,77,25,'2026-06-15 20:13:29'),
(2,1,'Smartwatch AMOLED','Jam pintar layar AMOLED dengan pemantau kesehatan, notifikasi, dan baterai tahan lama.',799000.00,'https://loremflickr.com/640/640/smartwatch,amoled?lock=1002',4.3,114,38,'2026-06-15 20:13:29'),
(3,1,'Bluetooth Speaker','Speaker portabel dengan bass kuat, desain ringkas, dan koneksi Bluetooth cepat.',279000.00,'https://loremflickr.com/640/640/bluetooth,speaker?lock=1003',4.0,151,51,'2026-06-15 20:13:29'),
(4,1,'Mechanical Keyboard','Keyboard mekanikal dengan switch responsif, lampu RGB, dan rangka kokoh untuk kerja atau gaming.',625000.00,'https://loremflickr.com/640/640/mechanical,keyboard?lock=1004',4.8,188,64,'2026-06-15 20:13:29'),
(5,1,'Gaming Mouse RGB','Mouse gaming ergonomis dengan DPI dapat diatur, tombol cepat, dan pencahayaan RGB.',215000.00,'https://loremflickr.com/640/640/gaming,mouse?lock=1005',4.5,225,77,'2026-06-15 20:13:29'),
(6,1,'Power Bank 20000mAh','Power bank kapasitas besar dengan output cepat untuk ponsel, tablet, dan perangkat USB lain.',329000.00,'https://loremflickr.com/640/640/powerbank,charger?lock=1006',4.2,262,90,'2026-06-15 20:13:29'),
(7,1,'USB-C Fast Charger','Adaptor charger USB-C fast charging yang aman, ringkas, dan cocok untuk perangkat modern.',145000.00,'https://loremflickr.com/640/640/usb-c,charger?lock=1007',3.9,299,14,'2026-06-15 20:13:29'),
(8,1,'Webcam Full HD','Webcam Full HD dengan mikrofon bawaan untuk kelas online, rapat, dan streaming.',255000.00,'https://loremflickr.com/640/640/webcam,camera?lock=1008',4.7,336,27,'2026-06-15 20:13:29'),
(9,1,'Portable SSD 1TB','SSD eksternal ringkas berkapasitas 1TB dengan transfer cepat dan bodi tahan banting.',1299000.00,'https://loremflickr.com/640/640/portable,ssd?lock=1009',4.4,373,40,'2026-06-15 20:13:29'),
(10,1,'Smart LED Bulb','Lampu pintar hemat energi yang dapat diatur warna dan kecerahannya melalui aplikasi.',89000.00,'https://loremflickr.com/640/640/smart,led,bulb?lock=1010',4.1,410,53,'2026-06-15 20:13:29'),
(11,2,'Kemeja Oxford Pria','Kemeja oxford pria berbahan nyaman dengan potongan rapi untuk kerja dan acara santai.',189000.00,'https://loremflickr.com/640/640/mens,oxford,shirt?lock=1011',4.9,447,66,'2026-06-15 20:13:29'),
(12,2,'Kaos Basic Cotton','Kaos basic katun lembut dengan jahitan kuat dan warna mudah dipadukan.',79000.00,'https://loremflickr.com/640/640/mens,cotton,tshirt?lock=1012',4.6,484,79,'2026-06-15 20:13:29'),
(13,2,'Jaket Bomber Pria','Jaket bomber ringan dengan desain kasual, saku fungsional, dan bahan nyaman.',299000.00,'https://loremflickr.com/640/640/mens,bomber,jacket?lock=1013',4.3,61,92,'2026-06-15 20:13:29'),
(14,2,'Celana Chino Slim','Celana chino slim fit dengan bahan fleksibel untuk tampilan rapi sehari-hari.',219000.00,'https://loremflickr.com/640/640/mens,chino,pants?lock=1014',4.0,98,16,'2026-06-15 20:13:29'),
(15,2,'Hoodie Fleece Pria','Hoodie fleece hangat dengan kantong depan dan bahan halus untuk aktivitas santai.',249000.00,'https://loremflickr.com/640/640/mens,hoodie?lock=1015',4.8,135,29,'2026-06-15 20:13:29'),
(16,2,'Polo Shirt Premium','Polo shirt premium berbahan adem dengan kerah rapi dan warna solid.',139000.00,'https://loremflickr.com/640/640/mens,polo,shirt?lock=1016',4.5,172,42,'2026-06-15 20:13:29'),
(17,2,'Jeans Denim Regular','Jeans denim regular fit dengan warna klasik dan material kuat untuk pemakaian harian.',269000.00,'https://loremflickr.com/640/640/mens,denim,jeans?lock=1017',4.2,209,55,'2026-06-15 20:13:29'),
(18,2,'Sneakers Casual Pria','Sneakers casual pria dengan sol empuk dan desain minimalis yang mudah dipadukan.',349000.00,'https://loremflickr.com/640/640/mens,sneakers?lock=1018',3.9,246,68,'2026-06-15 20:13:29'),
(19,2,'Jam Tangan Pria','Jam tangan pria bergaya modern dengan strap nyaman dan tampilan elegan.',399000.00,'https://loremflickr.com/640/640/mens,wristwatch?lock=1019',4.7,283,81,'2026-06-15 20:13:29'),
(20,2,'Ikat Pinggang Kulit','Ikat pinggang kulit sintetis premium dengan buckle kokoh untuk gaya formal dan kasual.',119000.00,'https://loremflickr.com/640/640/leather,belt?lock=1020',4.4,320,94,'2026-06-15 20:13:29'),
(21,3,'Blouse Satin Wanita','Blouse satin wanita dengan tekstur lembut dan tampilan elegan untuk kerja atau acara.',159000.00,'https://loremflickr.com/640/640/womens,satin,blouse?lock=1021',4.1,357,18,'2026-06-15 20:13:29'),
(22,3,'Dress Midi Floral','Dress midi bermotif floral dengan bahan jatuh dan potongan feminin.',289000.00,'https://loremflickr.com/640/640/floral,midi,dress?lock=1022',4.9,394,31,'2026-06-15 20:13:29'),
(23,3,'Cardigan Knit Wanita','Cardigan rajut nyaman dengan warna netral untuk pelengkap gaya harian.',199000.00,'https://loremflickr.com/640/640/womens,knit,cardigan?lock=1023',4.6,431,44,'2026-06-15 20:13:29'),
(24,3,'Rok Plisket','Rok plisket ringan dengan lipit rapi dan pinggang elastis yang nyaman.',149000.00,'https://loremflickr.com/640/640/pleated,skirt?lock=1024',4.3,468,57,'2026-06-15 20:13:29'),
(25,3,'Celana Kulot Wanita','Celana kulot wanita berbahan adem dengan siluet longgar untuk aktivitas harian.',179000.00,'https://loremflickr.com/640/640/womens,culotte,pants?lock=1025',4.0,45,70,'2026-06-15 20:13:29'),
(26,3,'Hijab Voal Premium','Hijab voal premium mudah dibentuk, ringan, dan nyaman dipakai sepanjang hari.',69000.00,'https://loremflickr.com/640/640/voal,hijab?lock=1026',4.8,82,83,'2026-06-15 20:13:29'),
(27,3,'Tas Selempang Wanita','Tas selempang wanita compact dengan ruang penyimpanan cukup untuk kebutuhan harian.',229000.00,'https://loremflickr.com/640/640/womens,sling,bag?lock=1027',4.5,119,96,'2026-06-15 20:13:29'),
(28,3,'Flat Shoes Wanita','Flat shoes wanita dengan sol empuk dan desain simpel untuk kerja atau jalan.',199000.00,'https://loremflickr.com/640/640/womens,flat,shoes?lock=1028',4.2,156,20,'2026-06-15 20:13:29'),
(29,3,'Sandal Heels','Sandal heels elegan dengan hak stabil dan strap nyaman untuk acara spesial.',239000.00,'https://loremflickr.com/640/640/womens,heels,sandals?lock=1029',3.9,193,33,'2026-06-15 20:13:29'),
(30,3,'Outer Linen Wanita','Outer linen ringan dengan potongan santai untuk layering yang tetap rapi.',219000.00,'https://loremflickr.com/640/640/womens,linen,outerwear?lock=1030',4.7,230,46,'2026-06-15 20:13:29'),
(31,4,'Kacamata Anti Radiasi','Kacamata anti radiasi dengan frame ringan untuk penggunaan laptop dan ponsel lebih nyaman.',129000.00,'https://loremflickr.com/640/640/blue,light,glasses?lock=1031',4.4,267,59,'2026-06-15 20:13:29'),
(32,4,'Dompet Kulit Minimalis','Dompet kulit minimalis dengan banyak slot kartu dan ukuran ramping.',99000.00,'https://loremflickr.com/640/640/minimalist,wallet?lock=1032',4.1,304,72,'2026-06-15 20:13:29'),
(33,4,'Topi Baseball','Topi baseball kasual dengan strap adjustable dan bahan nyaman.',85000.00,'https://loremflickr.com/640/640/baseball,cap?lock=1033',4.9,341,85,'2026-06-15 20:13:29'),
(34,4,'Gelang Stainless','Gelang stainless bergaya modern yang tahan karat dan cocok untuk penggunaan harian.',79000.00,'https://loremflickr.com/640/640/stainless,bracelet?lock=1034',4.6,378,98,'2026-06-15 20:13:29'),
(35,4,'Kalung Pendant','Kalung pendant minimalis dengan rantai ringan dan desain elegan.',89000.00,'https://loremflickr.com/640/640/pendant,necklace?lock=1035',4.3,415,22,'2026-06-15 20:13:29'),
(36,4,'Cincin Silver','Cincin warna silver dengan desain sederhana untuk pelengkap gaya.',99000.00,'https://loremflickr.com/640/640/silver,ring?lock=1036',4.0,452,35,'2026-06-15 20:13:29'),
(37,4,'Scarf Motif','Scarf motif lembut yang bisa dipakai sebagai aksesori leher atau tas.',75000.00,'https://loremflickr.com/640/640/pattern,scarf?lock=1037',4.8,489,48,'2026-06-15 20:13:29'),
(38,4,'Payung Lipat','Payung lipat ringkas dengan rangka kuat dan kain tahan air.',69000.00,'https://loremflickr.com/640/640/folding,umbrella?lock=1038',4.5,66,61,'2026-06-15 20:13:29'),
(39,4,'Gantungan Kunci','Gantungan kunci unik dengan bahan ringan untuk tas, kunci motor, atau hadiah kecil.',25000.00,'https://loremflickr.com/640/640/keychain?lock=1039',4.2,103,74,'2026-06-15 20:13:29'),
(40,4,'Case Smartphone','Case smartphone fleksibel dengan perlindungan sudut dan desain stylish.',59000.00,'https://loremflickr.com/640/640/smartphone,case?lock=1040',3.9,140,87,'2026-06-15 20:13:29'),
(41,5,'Backpack Laptop','Backpack laptop dengan kompartemen empuk, banyak kantong, dan bahan tahan percikan air.',279000.00,'https://loremflickr.com/640/640/laptop,backpack?lock=1041',4.7,177,11,'2026-06-15 20:13:29'),
(42,5,'Tote Bag Kanvas','Tote bag kanvas kuat dengan ruang besar untuk kuliah, kerja, atau belanja.',99000.00,'https://loremflickr.com/640/640/canvas,tote,bag?lock=1042',4.4,214,24,'2026-06-15 20:13:29'),
(43,5,'Sling Bag Travel','Sling bag travel ringan dengan kantong tersembunyi dan strap adjustable.',149000.00,'https://loremflickr.com/640/640/travel,sling,bag?lock=1043',4.1,251,37,'2026-06-15 20:13:29'),
(44,5,'Tas Gym','Tas gym multifungsi dengan ruang sepatu terpisah dan bahan mudah dibersihkan.',199000.00,'https://loremflickr.com/640/640/gym,duffel,bag?lock=1044',4.9,288,50,'2026-06-15 20:13:29'),
(45,5,'Sepatu Running','Sepatu running ringan dengan bantalan nyaman dan grip stabil untuk latihan.',459000.00,'https://loremflickr.com/640/640/running,shoes?lock=1045',4.6,325,63,'2026-06-15 20:13:29'),
(46,5,'Sepatu Hiking','Sepatu hiking dengan outsole kuat dan perlindungan ekstra untuk jalur outdoor.',589000.00,'https://loremflickr.com/640/640/hiking,shoes?lock=1046',4.3,362,76,'2026-06-15 20:13:29'),
(47,5,'Sandal Outdoor','Sandal outdoor dengan strap kuat, sol anti slip, dan desain nyaman.',189000.00,'https://loremflickr.com/640/640/outdoor,sandals?lock=1047',4.0,399,89,'2026-06-15 20:13:29'),
(48,5,'Boots Kulit','Boots kulit bergaya klasik dengan konstruksi kokoh dan sol tebal.',699000.00,'https://loremflickr.com/640/640/leather,boots?lock=1048',4.8,436,13,'2026-06-15 20:13:29'),
(49,5,'Organizer Travel','Organizer travel untuk menyimpan kabel, dokumen, dan aksesori kecil saat bepergian.',85000.00,'https://loremflickr.com/640/640/travel,organizer?lock=1049',4.5,473,26,'2026-06-15 20:13:29'),
(50,5,'Pouch Kosmetik','Pouch kosmetik compact dengan lapisan mudah dibersihkan dan resleting kuat.',65000.00,'https://loremflickr.com/640/640/cosmetic,pouch?lock=1050',4.2,50,39,'2026-06-15 20:13:29'),
(51,6,'Sunscreen SPF50','Sunscreen SPF50 ringan, cepat meresap, dan nyaman untuk perlindungan harian.',95000.00,'https://loremflickr.com/640/640/sunscreen,spf50?lock=1051',3.9,87,52,'2026-06-15 20:13:29'),
(52,6,'Serum Vitamin C','Serum vitamin C untuk membantu kulit terlihat lebih cerah dan segar.',129000.00,'https://loremflickr.com/640/640/vitamin,c,serum?lock=1052',4.7,124,65,'2026-06-15 20:13:29'),
(53,6,'Moisturizer Gel','Moisturizer gel ringan yang melembapkan tanpa rasa lengket.',88000.00,'https://loremflickr.com/640/640/gel,moisturizer?lock=1053',4.4,161,78,'2026-06-15 20:13:29'),
(54,6,'Facial Wash','Facial wash lembut untuk membersihkan wajah dari minyak dan kotoran harian.',59000.00,'https://loremflickr.com/640/640/facial,wash?lock=1054',4.1,198,91,'2026-06-15 20:13:29'),
(55,6,'Lip Tint','Lip tint dengan warna natural, ringan, dan tahan lama untuk tampilan segar.',69000.00,'https://loremflickr.com/640/640/lip,tint?lock=1055',4.9,235,15,'2026-06-15 20:13:29'),
(56,6,'Cushion Foundation','Cushion foundation dengan coverage natural dan hasil akhir halus.',149000.00,'https://loremflickr.com/640/640/cushion,foundation?lock=1056',4.6,272,28,'2026-06-15 20:13:29'),
(57,6,'Body Lotion','Body lotion melembapkan kulit dengan aroma lembut dan tekstur cepat meresap.',75000.00,'https://loremflickr.com/640/640/body,lotion?lock=1057',4.3,309,41,'2026-06-15 20:13:29'),
(58,6,'Hair Tonic','Hair tonic untuk membantu merawat kulit kepala dan menjaga rambut tetap segar.',89000.00,'https://loremflickr.com/640/640/hair,tonic?lock=1058',4.0,346,54,'2026-06-15 20:13:29'),
(59,6,'Parfum Floral','Parfum floral dengan aroma lembut, feminin, dan tahan lama.',179000.00,'https://loremflickr.com/640/640/floral,perfume?lock=1059',4.8,383,67,'2026-06-15 20:13:29'),
(60,6,'Sheet Mask','Sheet mask praktis untuk melembapkan dan menyegarkan kulit wajah.',25000.00,'https://loremflickr.com/640/640/sheet,mask?lock=1060',4.5,420,80,'2026-06-15 20:13:29'),
(61,7,'Air Fryer Digital','Air fryer digital dengan pengaturan suhu praktis untuk memasak lebih sehat.',899000.00,'https://loremflickr.com/640/640/digital,air,fryer?lock=1061',4.2,457,93,'2026-06-15 20:13:29'),
(62,7,'Blender Portable','Blender portable rechargeable untuk jus dan smoothie di rumah maupun perjalanan.',219000.00,'https://loremflickr.com/640/640/portable,blender?lock=1062',3.9,494,17,'2026-06-15 20:13:29'),
(63,7,'Rice Cooker Mini','Rice cooker mini hemat tempat dengan fitur memasak dan menghangatkan nasi.',329000.00,'https://loremflickr.com/640/640/mini,rice,cooker?lock=1063',4.7,71,30,'2026-06-15 20:13:29'),
(64,7,'Panci Stainless','Panci stainless serbaguna dengan pegangan kuat dan distribusi panas merata.',189000.00,'https://loremflickr.com/640/640/stainless,pot?lock=1064',4.4,108,43,'2026-06-15 20:13:29'),
(65,7,'Wajan Anti Lengket','Wajan anti lengket dengan permukaan mudah dibersihkan dan gagang nyaman.',169000.00,'https://loremflickr.com/640/640/nonstick,pan?lock=1065',4.1,145,56,'2026-06-15 20:13:29'),
(66,7,'Set Pisau Dapur','Set pisau dapur tajam dengan beberapa ukuran untuk persiapan masak harian.',249000.00,'https://loremflickr.com/640/640/kitchen,knife,set?lock=1066',4.9,182,69,'2026-06-15 20:13:29'),
(67,7,'Rak Bumbu','Rak bumbu compact untuk membuat dapur lebih rapi dan mudah dijangkau.',99000.00,'https://loremflickr.com/640/640/spice,rack?lock=1067',4.6,219,82,'2026-06-15 20:13:29'),
(68,7,'Sprei Katun','Sprei katun lembut dengan jahitan rapi dan warna nyaman untuk kamar tidur.',199000.00,'https://loremflickr.com/640/640/cotton,bedsheet?lock=1068',4.3,256,95,'2026-06-15 20:13:29'),
(69,7,'Lampu Meja','Lampu meja minimalis dengan cahaya nyaman untuk kerja atau belajar.',129000.00,'https://loremflickr.com/640/640/desk,lamp?lock=1069',4.0,293,19,'2026-06-15 20:13:29'),
(70,7,'Vacuum Cleaner Mini','Vacuum cleaner mini portabel untuk membersihkan debu di rumah, sofa, dan mobil.',399000.00,'https://loremflickr.com/640/640/mini,vacuum,cleaner?lock=1070',4.8,330,32,'2026-06-15 20:13:29'),
(71,8,'Matras Yoga','Matras yoga anti slip dengan ketebalan nyaman untuk latihan di rumah.',139000.00,'https://loremflickr.com/640/640/yoga,mat?lock=1071',4.5,367,45,'2026-06-15 20:13:29'),
(72,8,'Dumbbell Adjustable','Dumbbell adjustable hemat ruang untuk latihan kekuatan berbagai level.',499000.00,'https://loremflickr.com/640/640/adjustable,dumbbell?lock=1072',4.2,404,58,'2026-06-15 20:13:29'),
(73,8,'Resistance Band','Resistance band elastis dengan beberapa tingkat beban untuk latihan fleksibel.',69000.00,'https://loremflickr.com/640/640/resistance,band?lock=1073',3.9,441,71,'2026-06-15 20:13:29'),
(74,8,'Bola Futsal','Bola futsal dengan pantulan stabil dan material tahan lama untuk latihan rutin.',159000.00,'https://loremflickr.com/640/640/futsal,ball?lock=1074',4.7,478,84,'2026-06-15 20:13:29'),
(75,8,'Raket Badminton','Raket badminton ringan dengan grip nyaman dan ayunan responsif.',229000.00,'https://loremflickr.com/640/640/badminton,racket?lock=1075',4.4,55,97,'2026-06-15 20:13:29'),
(76,8,'Botol Minum Sport','Botol minum sport anti bocor dengan kapasitas pas untuk latihan dan perjalanan.',79000.00,'https://loremflickr.com/640/640/sports,water,bottle?lock=1076',4.1,92,21,'2026-06-15 20:13:29'),
(77,8,'Sepeda Statis Mini','Sepeda statis mini untuk latihan ringan di rumah dengan desain hemat tempat.',799000.00,'https://loremflickr.com/640/640/mini,exercise,bike?lock=1077',4.9,129,34,'2026-06-15 20:13:29'),
(78,8,'Sarung Tangan Gym','Sarung tangan gym dengan padding nyaman dan grip kuat saat angkat beban.',99000.00,'https://loremflickr.com/640/640/gym,gloves?lock=1078',4.6,166,47,'2026-06-15 20:13:29'),
(79,8,'Jersey Training','Jersey training ringan, cepat kering, dan nyaman untuk olahraga harian.',129000.00,'https://loremflickr.com/640/640/training,jersey?lock=1079',4.3,203,60,'2026-06-15 20:13:29'),
(80,8,'Sepatu Basket','Sepatu basket dengan support pergelangan dan bantalan empuk untuk lapangan.',529000.00,'https://loremflickr.com/640/640/basketball,shoes?lock=1080',4.0,240,73,'2026-06-15 20:13:29'),
(81,9,'Puzzle 1000 Pieces','Puzzle 1000 pieces dengan gambar menarik untuk aktivitas santai dan melatih fokus.',129000.00,'https://loremflickr.com/640/640/jigsaw,puzzle?lock=1081',4.8,277,86,'2026-06-15 20:13:29'),
(82,9,'Action Figure','Action figure detail dengan pose dinamis untuk koleksi atau pajangan meja.',199000.00,'https://loremflickr.com/640/640/action,figure?lock=1082',4.5,314,10,'2026-06-15 20:13:29'),
(83,9,'Board Game Family','Board game keluarga dengan aturan mudah dan permainan seru untuk semua usia.',249000.00,'https://loremflickr.com/640/640/family,board,game?lock=1083',4.2,351,23,'2026-06-15 20:13:29'),
(84,9,'Drone Mini','Drone mini dengan kontrol mudah, kamera sederhana, dan baterai rechargeable.',599000.00,'https://loremflickr.com/640/640/mini,drone?lock=1084',3.9,388,36,'2026-06-15 20:13:29'),
(85,9,'Kamera Instan','Kamera instan compact untuk mencetak momen langsung dalam gaya retro.',899000.00,'https://loremflickr.com/640/640/instant,camera?lock=1085',4.7,425,49,'2026-06-15 20:13:29'),
(86,9,'Cat Akrilik Set','Set cat akrilik warna lengkap untuk melukis di kanvas, kayu, atau kertas.',99000.00,'https://loremflickr.com/640/640/acrylic,paint,set?lock=1086',4.4,462,62,'2026-06-15 20:13:29'),
(87,9,'Gitar Akustik','Gitar akustik dengan suara hangat dan body nyaman untuk pemula maupun hobi.',749000.00,'https://loremflickr.com/640/640/acoustic,guitar?lock=1087',4.1,499,75,'2026-06-15 20:13:29'),
(88,9,'Keyboard Musik Mini','Keyboard musik mini dengan banyak tone dan ritme untuk belajar bermain musik.',459000.00,'https://loremflickr.com/640/640/mini,music,keyboard?lock=1088',4.9,76,88,'2026-06-15 20:13:29'),
(89,9,'Buku Sketsa','Buku sketsa tebal dengan kertas halus untuk menggambar, lettering, dan ide kreatif.',59000.00,'https://loremflickr.com/640/640/sketchbook?lock=1089',4.6,113,12,'2026-06-15 20:13:29'),
(90,9,'Lego Bricks Set','Set balok susun kreatif dengan banyak bentuk untuk bermain dan membangun imajinasi.',299000.00,'https://loremflickr.com/640/640/building,blocks,set?lock=1090',4.3,150,25,'2026-06-15 20:13:29'),
(91,10,'Helm Half Face','Helm half face berstandar aman dengan ventilasi nyaman dan visor bening.',329000.00,'https://loremflickr.com/640/640/half,face,helmet?lock=1091',4.0,187,38,'2026-06-15 20:13:29'),
(92,10,'Sarung Tangan Motor','Sarung tangan motor dengan grip kuat dan bahan nyaman untuk berkendara harian.',99000.00,'https://loremflickr.com/640/640/motorcycle,gloves?lock=1092',4.8,224,51,'2026-06-15 20:13:29'),
(93,10,'Jaket Riding','Jaket riding dengan bahan kuat, saku fungsional, dan perlindungan saat berkendara.',399000.00,'https://loremflickr.com/640/640/riding,jacket?lock=1093',4.5,261,64,'2026-06-15 20:13:29'),
(94,10,'Cover Mobil','Cover mobil tahan air dan debu untuk melindungi kendaraan saat parkir.',249000.00,'https://loremflickr.com/640/640/car,cover?lock=1094',4.2,298,77,'2026-06-15 20:13:29'),
(95,10,'Vacuum Mobil','Vacuum mobil portabel untuk membersihkan jok, karpet, dan sela kabin.',189000.00,'https://loremflickr.com/640/640/car,vacuum?lock=1095',3.9,335,90,'2026-06-15 20:13:29'),
(96,10,'Charger Mobil USB','Charger mobil USB dengan pengisian cepat dan perlindungan arus berlebih.',79000.00,'https://loremflickr.com/640/640/car,usb,charger?lock=1096',4.7,372,14,'2026-06-15 20:13:29'),
(97,10,'Holder HP Motor','Holder HP motor kuat dengan penjepit aman untuk navigasi saat berkendara.',89000.00,'https://loremflickr.com/640/640/motorcycle,phone,holder?lock=1097',4.4,409,27,'2026-06-15 20:13:29'),
(98,10,'Pengharum Mobil','Pengharum mobil dengan aroma segar untuk menjaga kabin tetap nyaman.',35000.00,'https://loremflickr.com/640/640/car,air,freshener?lock=1098',4.1,446,40,'2026-06-15 20:13:29'),
(99,10,'Cairan Pembersih Kaca','Cairan pembersih kaca mobil yang membantu mengangkat noda dan menjaga visibilitas.',55000.00,'https://loremflickr.com/640/640/car,glass,cleaner?lock=1099',4.9,483,53,'2026-06-15 20:13:29'),
(100,10,'Pompa Ban Portable','Pompa ban portable dengan indikator tekanan untuk motor, mobil, dan sepeda.',299000.00,'https://loremflickr.com/640/640/portable,tire,inflator?lock=1100',4.6,60,66,'2026-06-15 20:13:29');

/*Table structure for table `promo_codes` */

DROP TABLE IF EXISTS `promo_codes`;

CREATE TABLE `promo_codes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `discount_percent` decimal(5,2) NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `promo_codes` */

insert  into `promo_codes`(`id`,`code`,`discount_percent`,`is_active`) values 
(1,'SHOPKU10',10.00,1),
(2,'HEMAT20',20.00,1),
(3,'GRATIS',15.00,1);

/*Table structure for table `return_items` */

DROP TABLE IF EXISTS `return_items`;

CREATE TABLE `return_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `return_id` int NOT NULL,
  `order_item_id` int NOT NULL,
  `product_id` int DEFAULT NULL,
  `qty` int NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `return_id` (`return_id`),
  KEY `order_item_id` (`order_item_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `return_items_ibfk_1` FOREIGN KEY (`return_id`) REFERENCES `returns` (`id`) ON DELETE CASCADE,
  CONSTRAINT `return_items_ibfk_2` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`),
  CONSTRAINT `return_items_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `return_items` */

/*Table structure for table `returns` */

DROP TABLE IF EXISTS `returns`;

CREATE TABLE `returns` (
  `id` int NOT NULL AUTO_INCREMENT,
  `return_code` varchar(50) NOT NULL,
  `order_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `reason` varchar(255) NOT NULL,
  `description` text,
  `evidence_image` varchar(255) DEFAULT NULL,
  `refund_amount` decimal(12,2) DEFAULT '0.00',
  `status` enum('diajukan','disetujui','ditolak','barang_dikirim','barang_diterima','refund_diproses','selesai','dibatalkan') DEFAULT 'diajukan',
  `admin_note` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `return_code` (`return_code`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `returns_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `returns_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `returns` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`password`,`role`,`created_at`) values 
(1,'oiQs Gemink','user@sopku.com','sopku123','user','2026-06-13 19:10:57'),
(2,'Demo User','demo@sopku.com','demo123','user','2026-06-13 19:10:57'),
(3,'Test User','test@sopku.com','test123','user','2026-06-13 19:10:57'),
(4,'Administrator','admin@sopku.com','admin123','admin','2026-06-15 00:00:00');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
