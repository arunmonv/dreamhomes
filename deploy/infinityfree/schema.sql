-- Dream Homes: import once in phpMyAdmin (select your database first).
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(191) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `properties` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(191) NOT NULL,
  `type` enum('land','new_house','resale') NOT NULL,
  `location` varchar(191) NOT NULL,
  `price` bigint unsigned NOT NULL,
  `area_sqft` int unsigned DEFAULT NULL,
  `bedrooms` tinyint unsigned DEFAULT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `sort` int unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `properties_slug_unique` (`slug`),
  KEY `properties_type_index` (`type`),
  KEY `properties_location_index` (`location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `settings` (
  `key` varchar(191) NOT NULL,
  `value` text,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `enquiries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `message` text,
  `property_id` bigint unsigned DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `enquiries_property_id_foreign` (`property_id`),
  CONSTRAINT `enquiries_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- Default site content (edit later in Admin -> Site Settings)
INSERT INTO `settings` (`key`, `value`) VALUES
('site_name', 'Dream Homes'), ('tagline', 'Real Estate & Builders'),
('whatsapp', '919999999999'), ('phone', '+91 99999 99999'),
('hero_title', 'Find Your'), ('hero_highlight', 'Dream Property'),
('hero_text', 'We build homes, sell lands and help you find the right property.'),
('cta_title', 'Custom House Construction'),
('cta_text', 'Tell us your requirements. We will design and build your dream home.'),
('trust_1', 'Trusted by 1000+ Families'), ('trust_2', 'Quality Construction'),
('trust_3', 'Serving Tamil Nadu & Kerala');

-- Admin login (password is in ADMIN-LOGIN.txt next to the package; change email/password later in phpMyAdmin if needed)
INSERT INTO `users` (`name`, `email`, `password`, `created_at`, `updated_at`)
VALUES ('Admin', '__ADMIN_EMAIL__', '__ADMIN_HASH__', NOW(), NOW());
