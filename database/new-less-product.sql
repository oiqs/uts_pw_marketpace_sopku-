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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `cart_items` */

insert  into `cart_items`(`id`,`user_id`,`session_id`,`product_id`,`qty`,`selected`,`created_at`) values 
(1,1,NULL,1,1,1,'2026-06-13 19:10:57'),
(2,1,NULL,2,2,1,'2026-06-13 19:10:57'),
(4,4,NULL,2,1,1,'2026-06-16 00:03:24');

/*Table structure for table `categories` */

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `categories` */

insert  into `categories`(`id`,`name`) values 
(1,'elektronik'),
(11,'Sepatu'),
(12,'Transportasi'),
(13,'Pakaian');

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
(7,1,'USB-C Fast Charger','Adaptor charger USB-C fast charging yang aman, ringkas, dan cocok untuk perangkat modern.',145000.00,'https://loremflickr.com/640/640/usb-c,charger?lock=1007',3.9,299,14,'2026-06-15 20:13:29');

/*Table structure for table `promo_codes` */

DROP TABLE IF EXISTS `promo_codes`;

CREATE TABLE `promo_codes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `discount_percent` decimal(5,2) NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `promo_codes` */

insert  into `promo_codes`(`id`,`code`,`discount_percent`,`is_active`) values 
(3,'GRATIS',15.00,1),
(4,'THORIQGANTENG',100.00,1);

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

/*Table structure for table `reviews` */

DROP TABLE IF EXISTS `reviews`;

CREATE TABLE `reviews` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `comment` text COLLATE utf8mb4_general_ci,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `reviews` */

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
(4,'Administrator','admin@sopku.com','admin123','admin','2026-06-15 00:00:00');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
