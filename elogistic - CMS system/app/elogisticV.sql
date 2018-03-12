-- phpMyAdmin SQL Dump
-- version 4.6.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Creato il: Mag 24, 2017 alle 12:15
-- Versione del server: 5.7.18-log
-- Versione PHP: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `elogistic`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `addresses`
--

CREATE TABLE `addresses` (
  `id` int(11) NOT NULL,
  `address` text NOT NULL,
  `post_code` varchar(12) NOT NULL,
  `city` varchar(20) NOT NULL,
  `country` varchar(20) NOT NULL,
  `to_addr` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `addresses`
--

INSERT INTO `addresses` (`id`, `address`, `post_code`, `city`, `country`, `to_addr`) VALUES
(1, '116 Mile End Rd', 'E1 4UN', 'London', 'United Kingdom', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `currencies`
--

CREATE TABLE `currencies` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `currencies`
--

INSERT INTO `currencies` (`id`, `name`) VALUES
(1, 'pound'),
(2, 'euro');

-- --------------------------------------------------------

--
-- Struttura della tabella `facilities`
--

CREATE TABLE `facilities` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(64) NOT NULL,
  `salt` binary(32) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(30) NOT NULL,
  `group_id` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL,
  `address_id_2` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `facilities`
--

INSERT INTO `facilities` (`id`, `username`, `password`, `salt`, `phone`, `email`, `group_id`, `currency_id`, `address_id`, `address_id_2`) VALUES
(1, 'admin', '28bb56f7b23cec94f02c20c4649e6904e5b0fdd5dbe4382514beabd619716b64', 0x4cef7dee5db1286eafc6df762b311c4f2a8f5f3b134b6ce62445cbf1da57175e, '02077027005', 'MISSING', 2, 1, 1, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `facilities_session`
--

CREATE TABLE `facilities_session` (
  `id` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL,
  `hash` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struttura della tabella `favourite_locations`
--

CREATE TABLE `favourite_locations` (
  `facility_id` int(11) NOT NULL,
  `address_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `favourite_locations`
--

INSERT INTO `favourite_locations` (`facility_id`, `address_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `permissions` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `groups`
--

INSERT INTO `groups` (`id`, `name`, `permissions`) VALUES
(1, 'Standard user', ''),
(2, 'Administrator', '{"admin": 1}');

-- --------------------------------------------------------

--
-- Struttura della tabella `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `shipment_id` int(11) NOT NULL,
  `from_addr` int(11) NOT NULL,
  `to_addr` int(11) NOT NULL,
  `from_date` datetime NOT NULL,
  `extra_info` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `orders`
--



-- --------------------------------------------------------

--
-- Struttura della tabella `orders_packages`
--

CREATE TABLE `orders_packages` (
  `order_id` int(11) NOT NULL,
  `package_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `orders_packages`
--



-- --------------------------------------------------------

--
-- Struttura della tabella `packages`
--

CREATE TABLE `packages` (
  `id` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `height` int(11) NOT NULL,
  `length` int(11) NOT NULL,
  `width` int(11) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `packages`
--



-- --------------------------------------------------------

--
-- Struttura della tabella `shipments`
--

CREATE TABLE `shipments` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `shipments`
--

INSERT INTO `shipments` (`id`, `name`) VALUES
(1, 'cargo'),
(2, 'normal');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `currency_id` (`currency_id`),
  ADD KEY `address_id` (`address_id`),
  ADD KEY `address_id_2` (`address_id_2`);

--
-- Indici per le tabelle `facilities_session`
--
ALTER TABLE `facilities_session`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facility_id` (`facility_id`);

--
-- Indici per le tabelle `favourite_locations`
--
ALTER TABLE `favourite_locations`
  ADD KEY `facility_id` (`facility_id`,`address_id`),
  ADD KEY `address_id` (`address_id`);

--
-- Indici per le tabelle `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facility_id` (`facility_id`),
  ADD KEY `shipment_id` (`shipment_id`);

--
-- Indici per le tabelle `orders_packages`
--
ALTER TABLE `orders_packages`
  ADD PRIMARY KEY (`order_id`,`package_id`),
  ADD KEY `package_id` (`package_id`);

--
-- Indici per le tabelle `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT per la tabella `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT per la tabella `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT per la tabella `facilities_session`
--
ALTER TABLE `facilities_session`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT per la tabella `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `packages`
--
ALTER TABLE `packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT per la tabella `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `facilities`
--
ALTER TABLE `facilities`
  ADD CONSTRAINT `facilities_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `facilities_ibfk_2` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`),
  ADD CONSTRAINT `facilities_ibfk_3` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`),
  ADD CONSTRAINT `facilities_ibfk_4` FOREIGN KEY (`address_id_2`) REFERENCES `addresses` (`id`);

--
-- Limiti per la tabella `favourite_locations`
--
ALTER TABLE `favourite_locations`
  ADD CONSTRAINT `favourite_locations_ibfk_1` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`),
  ADD CONSTRAINT `favourite_locations_ibfk_2` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`);

--
-- Limiti per la tabella `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`);

--
-- Limiti per la tabella `orders_packages`
--
ALTER TABLE `orders_packages`
  ADD CONSTRAINT `orders_packages_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `orders_packages_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
