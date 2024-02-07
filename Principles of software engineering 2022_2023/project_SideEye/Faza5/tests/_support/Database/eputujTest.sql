-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 08, 2023 at 07:19 PM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `eputuj`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

drop schema if exists eputujTest;
create schema eputujTest;
use eputujTest;

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `SifK` int NOT NULL,
  PRIMARY KEY (`SifK`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--


-- --------------------------------------------------------

--
-- Table structure for table `jedobio`
--

DROP TABLE IF EXISTS `jedobio`;
CREATE TABLE IF NOT EXISTS `jedobio` (
  `SifK` int NOT NULL,
  `SifPokl` int NOT NULL,
  `JeDobioPK` int NOT NULL,
  `Datum` datetime NOT NULL,
  PRIMARY KEY (`JeDobioPK`),
  KEY `fk_jedobio_sifpokl` (`SifPokl`),
  KEY `FK_SifK_idx` (`SifK`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `korisnik`
--

DROP TABLE IF EXISTS `korisnik`;
CREATE TABLE IF NOT EXISTS `korisnik` (
  `SifK` int NOT NULL,
  `KorisnickoIme` varchar(20) NOT NULL,
  `Lozinka` varchar(20) NOT NULL,
  `TraziBrisanje` int NOT NULL,
  `Ime` varchar(20) NOT NULL,
  `Prezime` varchar(20) NOT NULL,
  `BrTel` bigint NOT NULL,
  `Email` varchar(45) NOT NULL,
  `PrivatnikIliKorisnik` varchar(1) NOT NULL,
  `Novac` float NOT NULL,
  `ProfilnaSlika` text NOT NULL,
  PRIMARY KEY (`SifK`),
  UNIQUE KEY `Kime` (`KorisnickoIme`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `korisnik`
--


-- --------------------------------------------------------

--
-- Table structure for table `kupljenakarta`
--

DROP TABLE IF EXISTS `kupljenakarta`;
CREATE TABLE IF NOT EXISTS `kupljenakarta` (
  `SifKar` int NOT NULL,
  `NacinPlacanja` decimal(10,2) NOT NULL,
  `SifP` int NOT NULL,
  `SifK` int NOT NULL,
  PRIMARY KEY (`SifKar`),
  KEY `R_24` (`SifP`),
  KEY `R_25` (`SifK`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kupljenakarta`
--


-- --------------------------------------------------------

--
-- Table structure for table `mesto`
--

DROP TABLE IF EXISTS `mesto`;
CREATE TABLE IF NOT EXISTS `mesto` (
  `SifM` int NOT NULL,
  `Naziv` varchar(20) NOT NULL,
  PRIMARY KEY (`SifM`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `mesto`
--


-- --------------------------------------------------------

--
-- Table structure for table `obicankorisnik`
--

DROP TABLE IF EXISTS `obicankorisnik`;
CREATE TABLE IF NOT EXISTS `obicankorisnik` (
  `SifK` int NOT NULL,
  `token` int NOT NULL,
  PRIMARY KEY (`SifK`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `obicankorisnik`
--


-- --------------------------------------------------------

--
-- Table structure for table `ocena`
--

DROP TABLE IF EXISTS `ocena`;
CREATE TABLE IF NOT EXISTS `ocena` (
  `SifO` int NOT NULL,
  `Ocena` int NOT NULL,
  `Komentar` varchar(20) DEFAULT NULL,
  `SifPriv` int NOT NULL,
  `SifK` int NOT NULL,
  PRIMARY KEY (`SifO`),
  KEY `R_30` (`SifPriv`),
  KEY `R_31` (`SifK`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ocena`
--


-- --------------------------------------------------------

--
-- Table structure for table `poklon`
--

DROP TABLE IF EXISTS `poklon`;
CREATE TABLE IF NOT EXISTS `poklon` (
  `SifPokl` int NOT NULL,
  `Iznos` decimal(10,2) NOT NULL,
  `TipPoklona` varchar(20) NOT NULL,
  PRIMARY KEY (`SifPokl`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `poklon`
--


-- --------------------------------------------------------

--
-- Table structure for table `ponuda`
--

DROP TABLE IF EXISTS `ponuda`;
CREATE TABLE IF NOT EXISTS `ponuda` (
  `SifP` int NOT NULL,
  `BrMesta` int NOT NULL,
  `DatumOd` date NOT NULL,
  `DatumDo` date DEFAULT NULL,
  `VremeOd` time NOT NULL,
  `VremeDo` time DEFAULT NULL,
  `CenaKarte` decimal(10,2) NOT NULL,
  `SifMesDo` int NOT NULL,
  `SifMesOd` int NOT NULL,
  `SifSred` int NOT NULL,
  `SifK` int NOT NULL,
  `Slika` text NOT NULL,
  `SifPriv` int NOT NULL,
  PRIMARY KEY (`SifP`),
  KEY `R_10` (`SifMesDo`),
  KEY `R_11` (`SifMesOd`),
  KEY `R_12` (`SifSred`),
  KEY `R_26_idx` (`SifK`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ponuda`
--

-- --------------------------------------------------------

--
-- Table structure for table `poruka`
--

DROP TABLE IF EXISTS `poruka`;
CREATE TABLE IF NOT EXISTS `poruka` (
  `SifPonuda` int NOT NULL,
  `SifKor` int NOT NULL,
  `SifPriv` int NOT NULL,
  `SifPor` int NOT NULL,
  `SmerPoruke` int NOT NULL,
  PRIMARY KEY (`SifPor`),
  KEY `R_13` (`SifPonuda`),
  KEY `R_16` (`SifKor`),
  KEY `R_17` (`SifPriv`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `poruka`
--

-- --------------------------------------------------------

--
-- Table structure for table `postavljenaponuda`
--

DROP TABLE IF EXISTS `postavljenaponuda`;
CREATE TABLE IF NOT EXISTS `postavljenaponuda` (
  `SifP` int NOT NULL,
  `RokZaOtkazivanje` int NOT NULL,
  PRIMARY KEY (`SifP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `postavljenaponuda`
--


-- --------------------------------------------------------

--
-- Table structure for table `pretplata`
--

DROP TABLE IF EXISTS `pretplata`;
CREATE TABLE IF NOT EXISTS `pretplata` (
  `SifPret` int NOT NULL,
  `Naziv` varchar(20) NOT NULL,
  `Iznos` decimal(10,2) NOT NULL,
  PRIMARY KEY (`SifPret`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pretplata`
--

-- --------------------------------------------------------

--
-- Table structure for table `prevoznosredstvo`
--

DROP TABLE IF EXISTS `prevoznosredstvo`;
CREATE TABLE IF NOT EXISTS `prevoznosredstvo` (
  `SifSred` int NOT NULL,
  `Naziv` varchar(20) NOT NULL,
  PRIMARY KEY (`SifSred`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `prevoznosredstvo`
--


-- --------------------------------------------------------

--
-- Table structure for table `privatnik`
--

DROP TABLE IF EXISTS `privatnik`;
CREATE TABLE IF NOT EXISTS `privatnik` (
  `SifK` int NOT NULL,
  `SifPret` int NOT NULL,
  PRIMARY KEY (`SifK`),
  KEY `R_5` (`SifPret`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `privatnik`
--

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

DROP TABLE IF EXISTS `report`;
CREATE TABLE IF NOT EXISTS `report` (
  `Razlog` varchar(20) NOT NULL,
  `SifPrijavio` int NOT NULL,
  `SifRep` int NOT NULL,
  `SifPrijavljen` int NOT NULL,
  PRIMARY KEY (`SifRep`),
  KEY `R_28` (`SifPrijavljen`),
  KEY `f_32_idx` (`SifPrijavio`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `report`
--

-- --------------------------------------------------------

--
-- Table structure for table `rezervacija`
--

DROP TABLE IF EXISTS `rezervacija`;
CREATE TABLE IF NOT EXISTS `rezervacija` (
  `SifK` int NOT NULL,
  `SifP` int NOT NULL,
  `BrMesta` int NOT NULL,
  `SifR` int NOT NULL,
  `DatumRezervacije` datetime NOT NULL,
  PRIMARY KEY (`SifR`),
  KEY `R_18` (`SifK`),
  KEY `R_19` (`SifP`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `rezervacija`
--


-- --------------------------------------------------------

--
-- Table structure for table `uplata`
--

DROP TABLE IF EXISTS `uplata`;
CREATE TABLE IF NOT EXISTS `uplata` (
  `SifU` int NOT NULL,
  `DatumUplate` datetime NOT NULL,
  `Iznos` decimal(10,2) NOT NULL,
  `SifKar` int NOT NULL,
  PRIMARY KEY (`SifU`),
  KEY `R_23` (`SifKar`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vanrednaponuda`
--

DROP TABLE IF EXISTS `vanrednaponuda`;
CREATE TABLE IF NOT EXISTS `vanrednaponuda` (
  `SifP` int NOT NULL,
  `RokZaOtkazivanje` int NOT NULL,
  PRIMARY KEY (`SifP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vanrednaponuda`
--


-- --------------------------------------------------------

--
-- Table structure for table `zahtevponuda`
--

DROP TABLE IF EXISTS `zahtevponuda`;
CREATE TABLE IF NOT EXISTS `zahtevponuda` (
  `SifP` int NOT NULL,
  `CenaOd` decimal(10,2) NOT NULL,
  `CenaDo` decimal(10,2) NOT NULL,
  PRIMARY KEY (`SifP`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `zahtevponuda`
--


--
-- Constraints for dumped tables
--

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
