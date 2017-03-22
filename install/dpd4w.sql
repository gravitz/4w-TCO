-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2017 at 10:57 AM
-- Server version: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dpd4w`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE IF NOT EXISTS `activities` (
  `activity_Id` smallint(5) unsigned NOT NULL,
  `title` varchar(500) NOT NULL,
  `description` varchar(5000) NOT NULL,
  `regDate` datetime NOT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `pCode` varchar(15) NOT NULL,
  `activityStatus` enum('Pipeline/identification','Implementation','Completion','Post-completion','Cancelled','Suspended') DEFAULT NULL,
  `activityType` enum('Development','Humanitarian Relief','Recovery, Rehabilitation and Reconstruction') DEFAULT NULL,
  `fundingStatus` enum('Pledge','Commitment','Contribution','W') DEFAULT NULL,
  `fundingType` enum('Assessed Contributions','Voluntary Contribution','In-kind Donations','Multi-donor Trust Funds','Bi-lateral','Multi-Lateral') DEFAULT NULL,
  `fundingAmount` double DEFAULT NULL,
  `focalPoint` varchar(200) DEFAULT NULL,
  `subclassification_id` smallint(5) unsigned NOT NULL,
  `activityThemes_id` smallint(5) unsigned NOT NULL,
  `classificationCode` varchar(8) NOT NULL,
  `currencyCode` varchar(5) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `activities`
--

-- --------------------------------------------------------

--
-- Table structure for table `activityclassifications`
--

CREATE TABLE IF NOT EXISTS `activityclassifications` (
  `classificationCode` varchar(8) NOT NULL,
  `classification_id` smallint(5) unsigned NOT NULL,
  `classification` varchar(200) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `activityclassifications`
--

INSERT INTO `activityclassifications` (`classificationCode`, `classification_id`, `classification`) VALUES
('EDU', 7, 'Education'),
('FSC', 6, 'Food Security'),
('HEA', 4, 'Health'),
('NUT', 3, 'Nutrition'),
('PRO', 2, 'Protection'),
('PRO-CPN', 8, 'Child Protection'),
('PRO-GBV', 5, 'Gender-based Violence'),
('WSH', 1, 'Water, Sanitation and Hygiene');

-- --------------------------------------------------------

--
-- Table structure for table `activitycoverage`
--

CREATE TABLE IF NOT EXISTS `activitycoverage` (
  `activityCoverage_id` smallint(5) unsigned NOT NULL,
  `activity_Id` smallint(5) unsigned NOT NULL,
  `ward_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `regHasc` varchar(6) NOT NULL,
  `distHasc` varchar(10) NOT NULL DEFAULT ''
) ENGINE=InnoDB AUTO_INCREMENT=232 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `activitycoverage`
--

-- --------------------------------------------------------

--
-- Table structure for table `activityorganisations`
--

CREATE TABLE IF NOT EXISTS `activityorganisations` (
  `activityOrganisation_id` smallint(5) unsigned NOT NULL,
  `activity_id` smallint(5) unsigned NOT NULL,
  `organisation_id` smallint(5) unsigned NOT NULL,
  `organisation_role` enum('Funding','Accountable','Extending','Implementing') DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `activityorganisations`
--

-- --------------------------------------------------------

--
-- Table structure for table `activitysubclassifications`
--

CREATE TABLE IF NOT EXISTS `activitysubclassifications` (
  `subclassification_id` smallint(5) unsigned NOT NULL,
  `subclassification` varchar(50) NOT NULL,
  `classificationCode` varchar(8) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `activitysubclassifications`
--

INSERT INTO `activitysubclassifications` (`subclassification_id`, `subclassification`, `classificationCode`) VALUES
(1, 'Maternal New Child Health ', 'HEA'),
(2, 'Education policy and administrative management', 'EDU'),
(3, 'Education facilities and training', 'EDU'),
(4, 'Teacher training', 'EDU'),
(5, 'Educational research', 'EDU'),
(6, 'Primary education', 'EDU'),
(7, 'Basic life skills for youth and adults', 'EDU'),
(8, 'Basic life skills for youth', 'EDU'),
(9, 'Primary education equivalent for adults', 'EDU'),
(10, 'Early childhood education', 'EDU'),
(11, 'Secondary education', 'EDU'),
(12, 'Lower secondary education', 'EDU'),
(13, 'Upper secondary education', 'EDU'),
(14, 'Vocational training', 'EDU'),
(15, 'Higher education', 'EDU'),
(16, 'Advanced technical and managerial training', 'EDU'),
(17, 'Health policy and administrative management', 'HEA'),
(18, 'Medical education/training', 'HEA'),
(19, 'Medical research', 'HEA'),
(20, 'Medical services', 'HEA'),
(21, 'Basic health care', 'HEA'),
(22, 'Basic health infrastructure', 'HEA'),
(23, 'Basic nutrition', 'HEA'),
(24, 'Infectious disease control', 'HEA'),
(25, 'Health education', 'HEA'),
(26, 'Malaria control', 'HEA'),
(27, 'Tuberculosis control', 'HEA'),
(28, 'Health personnel development', 'HEA'),
(29, 'Population policy and administrative management', 'HEA'),
(30, 'Reproductive health care', 'HEA'),
(31, 'Family planning', 'HEA'),
(32, 'STD control including HIV/AIDS', 'HEA'),
(33, 'Personnel development for population and reproduct', 'HEA'),
(34, 'Water sector policy and administrative management', 'WSH'),
(35, 'Water resources conservation (including data colle', 'WSH'),
(36, 'Water supply and sanitation - large systems', 'WSH'),
(37, 'Water supply - large systems', 'WSH'),
(38, 'Sanitation - large systems', 'WSH'),
(39, 'Basic drinking water supply and basic sanitation', 'WSH'),
(40, 'Basic drinking water supply', 'WSH'),
(41, 'Basic sanitation', 'WSH'),
(42, 'River basins’ development', 'WSH'),
(43, 'Waste management / disposal', 'WSH'),
(44, 'Education and training in water supply and sanitat', 'WSH'),
(45, 'Infant and Young Child Feeding', 'NUT'),
(46, 'Vitamin A Supplementation', 'NUT'),
(47, 'SAM Management', 'NUT'),
(48, 'Nutrition Coordination', 'NUT');

-- --------------------------------------------------------

--
-- Table structure for table `activitythemes`
--

CREATE TABLE IF NOT EXISTS `activitythemes` (
  `activityThemes_id` smallint(5) unsigned NOT NULL,
  `theme` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `activitythemes`
--

INSERT INTO `activitythemes` (`activityThemes_id`, `theme`) VALUES
(1, 'Theme 1'),
(2, 'Theme 2'),
(3, 'Theme 3');

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE IF NOT EXISTS `districts` (
  `distHasc` varchar(10) NOT NULL DEFAULT '',
  `district_id` smallint(5) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `regHasc` varchar(6) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=196 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`distHasc`, `district_id`, `name`, `regHasc`) VALUES
('TZ.AS.AM', 1, 'Arusha DC', 'TZ.AS'),
('TZ.AS.AS', 2, 'Arusha CC', 'TZ.AS'),
('TZ.AS.KA', 23, 'Karatu DC', 'TZ.AS'),
('TZ.AS.LO', 127, 'Longido DC', 'TZ.AS'),
('TZ.AS.ME', 195, 'Meru DC', 'TZ.AS'),
('TZ.AS.MO', 67, 'Monduli DC', 'TZ.AS'),
('TZ.AS.NG', 86, 'Ngorongoro DC', 'TZ.AS'),
('TZ.DO.BA', 130, 'Bahi DC', 'TZ.DO'),
('TZ.DO.CB', 132, 'Chemba DC', 'TZ.DO'),
('TZ.DO.CW', 131, 'Chamwino DC', 'TZ.DO'),
('TZ.DO.DO', 11, 'Dodoma MC', 'TZ.DO'),
('TZ.DO.KD', 41, 'Kondoa DC', 'TZ.DO'),
('TZ.DO.KG', 42, 'Kongwa DC', 'TZ.DO'),
('TZ.DO.KT', 133, 'Kondoa TC', 'TZ.DO'),
('TZ.DO.MP', 73, 'Mpwapwa DC', 'TZ.DO'),
('TZ.DS.IL', 17, 'Ilala MC', 'TZ.DS'),
('TZ.DS.KG', 128, 'Kigamboni MC', 'TZ.DS'),
('TZ.DS.KI', 37, 'Kinondoni MC', 'TZ.DS'),
('TZ.DS.TE', 112, 'Temeke MC', 'TZ.DS'),
('TZ.DS.UB', 129, 'Ubungo MC', 'TZ.DS'),
('TZ.GE.BU', 8, 'Bukombe DC', 'TZ.GE'),
('TZ.GE.CH', 134, 'Chato DC', 'TZ.GE'),
('TZ.GE.GD', 12, 'Geita DC', 'TZ.GE'),
('TZ.GE.GT', 137, 'Geita TC', 'TZ.GE'),
('TZ.GE.MB', 135, 'Mbogwe DC', 'TZ.GE'),
('TZ.GE.NY', 136, 'Nyang''hwale DC', 'TZ.GE'),
('TZ.IG.ID', 20, 'Iringa DC', 'TZ.IG'),
('TZ.IG.IM', 19, 'Iringa MC', 'TZ.IG'),
('TZ.IG.KI', 33, 'Kilolo DC', 'TZ.IG'),
('TZ.IG.MA', 138, 'Mafinga TC', 'TZ.IG'),
('TZ.IG.MU', 76, 'Mufindi DC', 'TZ.IG'),
('TZ.KA.MD', 72, 'Mpanda DC', 'TZ.KA'),
('TZ.KA.ML', 141, 'Mlele DC', 'TZ.KA'),
('TZ.KA.MM', 143, 'Mpanda MC', 'TZ.KA'),
('TZ.KA.MP', 144, 'Mpimbwe DC', 'TZ.KA'),
('TZ.KA.NS', 142, 'Nsimbo DC', 'TZ.KA'),
('TZ.KG.BD', 120, 'Bukoba DC', 'TZ.KG'),
('TZ.KG.BI', 6, 'Biharamulo DC', 'TZ.KG'),
('TZ.KG.BM', 7, 'Bukoba MC', 'TZ.KG'),
('TZ.KG.KA', 22, 'Karagwe DC', 'TZ.KG'),
('TZ.KG.KY', 139, 'Kyerwa DC', 'TZ.KG'),
('TZ.KG.MI', 140, 'Misenye DC', 'TZ.KG'),
('TZ.KG.MU', 78, 'Muleba DC', 'TZ.KG'),
('TZ.KG.NG', 85, 'Ngara DC', 'TZ.KG'),
('TZ.KL.HA', 13, 'Hai DC', 'TZ.KL'),
('TZ.KL.MD', 70, 'Moshi DC', 'TZ.KL'),
('TZ.KL.MM', 71, 'Moshi MC', 'TZ.KL'),
('TZ.KL.MW', 81, 'Mwanga DC', 'TZ.KL'),
('TZ.KL.RO', 92, 'Rombo DC', 'TZ.KL'),
('TZ.KL.SA', 96, 'Same DC', 'TZ.KL'),
('TZ.KL.SI', 149, 'Siha DC', 'TZ.KL'),
('TZ.KM.BU', 145, 'Buhigwe DC', 'TZ.KM'),
('TZ.KM.KA', 26, 'Kasulu DC', 'TZ.KM'),
('TZ.KM.KD', 31, 'Kigoma DC', 'TZ.KM'),
('TZ.KM.KI', 29, 'Kibondo DC', 'TZ.KM'),
('TZ.KM.KK', 146, 'Kakonko DC', 'TZ.KM'),
('TZ.KM.KM', 30, 'Kigoma MC', 'TZ.KM'),
('TZ.KM.KT', 148, 'Kasulu TC', 'TZ.KM'),
('TZ.KM.UV', 147, 'Uvinza DC', 'TZ.KM'),
('TZ.LI.KI', 36, 'Kilwa DC', 'TZ.LI'),
('TZ.LI.LD', 47, 'Lindi DC', 'TZ.LI'),
('TZ.LI.LI', 49, 'Liwale DC', 'TZ.LI'),
('TZ.LI.LM', 48, 'Lindi MC', 'TZ.LI'),
('TZ.LI.NA', 82, 'Nachingwea DC', 'TZ.LI'),
('TZ.LI.RU', 93, 'Ruangwa DC', 'TZ.LI'),
('TZ.MA.BD', 152, 'Butiama DC', 'TZ.MA'),
('TZ.MA.BT', 154, 'Bunda TC', 'TZ.MA'),
('TZ.MA.BU', 123, 'Bunda DC', 'TZ.MA'),
('TZ.MA.MR', 122, 'Musoma DC', 'TZ.MA'),
('TZ.MA.MU', 79, 'Musoma MC', 'TZ.MA'),
('TZ.MA.RO', 153, 'Rorya DC', 'TZ.MA'),
('TZ.MA.SE', 121, 'Serengeti DC', 'TZ.MA'),
('TZ.MA.TA', 111, 'Tarime DC', 'TZ.MA'),
('TZ.MA.TT', 155, 'Tarime TC', 'TZ.MA'),
('TZ.MB.BU', 190, 'Busokelo DC', 'TZ.MB'),
('TZ.MB.CH', 10, 'Chunya DC', 'TZ.MB'),
('TZ.MB.KY', 46, 'Kyela DC', 'TZ.MB'),
('TZ.MB.MB', 57, 'Mbarali DC', 'TZ.MB'),
('TZ.MB.MR', 58, 'Mbeya CC', 'TZ.MB'),
('TZ.MB.MU', 59, 'Mbeya DC', 'TZ.MB'),
('TZ.MB.RU', 95, 'Rungwe DC', 'TZ.MB'),
('TZ.MO.GA', 157, 'Gairo DC', 'TZ.MO'),
('TZ.MO.IF', 156, 'Ifakara TC', 'TZ.MO'),
('TZ.MO.KM', 34, 'Kilombero DC', 'TZ.MO'),
('TZ.MO.KS', 35, 'Kilosa DC', 'TZ.MO'),
('TZ.MO.MA', 158, 'Malinyi DC', 'TZ.MO'),
('TZ.MO.MR', 68, 'Morogoro MC', 'TZ.MO'),
('TZ.MO.MU', 69, 'Morogoro DC', 'TZ.MO'),
('TZ.MO.MV', 80, 'Mvomero DC', 'TZ.MO'),
('TZ.MO.UL', 115, 'Ulanga DC', 'TZ.MO'),
('TZ.MT.MA', 55, 'Masasi DC', 'TZ.MT'),
('TZ.MT.MR', 74, 'Mtwara DC', 'TZ.MT'),
('TZ.MT.MT', 160, 'Masasi TC', 'TZ.MT'),
('TZ.MT.MU', 75, 'Mtwara MC', 'TZ.MT'),
('TZ.MT.NA', 162, 'Nanyamba TC', 'TZ.MT'),
('TZ.MT.ND', 159, 'Nanyumbu DC', 'TZ.MT'),
('TZ.MT.NE', 84, 'Newala DC', 'TZ.MT'),
('TZ.MT.NT', 161, 'Newala TC', 'TZ.MT'),
('TZ.MT.TA', 109, 'Tandahimba DC', 'TZ.MT'),
('TZ.MY.BA', 3, 'Babati DC', 'TZ.MY'),
('TZ.MY.BT', 150, 'Babati TC', 'TZ.MY'),
('TZ.MY.HA', 14, 'Hanang DC', 'TZ.MY'),
('TZ.MY.KI', 40, 'Kiteto DC', 'TZ.MY'),
('TZ.MY.MB', 61, 'Mbulu DC', 'TZ.MY'),
('TZ.MY.MT', 151, 'Mbulu TC', 'TZ.MY'),
('TZ.MY.SI', 101, 'Simanjiro DC', 'TZ.MY'),
('TZ.MZ.BU', 163, 'Buchosa DC', 'TZ.MZ'),
('TZ.MZ.IL', 126, 'Ilemela MC', 'TZ.MZ'),
('TZ.MZ.KW', 45, 'Kwimba DC', 'TZ.MZ'),
('TZ.MZ.MA', 124, 'Magu DC', 'TZ.MZ'),
('TZ.MZ.MI', 125, 'Missungwi DC', 'TZ.MZ'),
('TZ.MZ.NY', 89, 'Mwanza CC', 'TZ.MZ'),
('TZ.MZ.SE', 97, 'Sengerema DC', 'TZ.MZ'),
('TZ.MZ.UK', 114, 'Ukerewe DC', 'TZ.MZ'),
('TZ.NJ.LU', 50, 'Ludewa DC', 'TZ.NJ'),
('TZ.NJ.MA', 53, 'Makete DC', 'TZ.NJ'),
('TZ.NJ.MT', 193, 'Makambako TC', 'TZ.NJ'),
('TZ.NJ.NJ', 87, 'Njombe DC', 'TZ.NJ'),
('TZ.NJ.NT', 191, 'Njombe TC', 'TZ.NJ'),
('TZ.NJ.WA', 192, 'Wanging''ombe DC', 'TZ.NJ'),
('TZ.PN.MI', 63, 'Micheweni', 'TZ.PN'),
('TZ.PN.WE', 118, 'Wete', 'TZ.PN'),
('TZ.PS.CH', 9, 'Chake', 'TZ.PS'),
('TZ.PS.MK', 65, 'Mkoani', 'TZ.PS'),
('TZ.PW.BA', 4, 'Bagamoyo DC', 'TZ.PW'),
('TZ.PW.CH', 164, 'Chalinze DC', 'TZ.PW'),
('TZ.PW.KB', 28, 'Kibaha DC', 'TZ.PW'),
('TZ.PW.KD', 166, 'Kibiti DC', 'TZ.PW'),
('TZ.PW.KS', 38, 'Kisarawe DC', 'TZ.PW'),
('TZ.PW.KT', 165, 'Kibaha TC', 'TZ.PW'),
('TZ.PW.MA', 52, 'Mafia DC', 'TZ.PW'),
('TZ.PW.MK', 66, 'Mkuranga DC', 'TZ.PW'),
('TZ.PW.RU', 94, 'Rufiji DC', 'TZ.PW'),
('TZ.RU.KA', 167, 'Kalambo DC', 'TZ.RU'),
('TZ.RU.NK', 88, 'Nkasi DC', 'TZ.RU'),
('TZ.RU.SR', 106, 'Sumbawanga DC', 'TZ.RU'),
('TZ.RU.SU', 107, 'Sumbawanga MC', 'TZ.RU'),
('TZ.RV.MA', 170, 'Madaba DC', 'TZ.RV'),
('TZ.RV.MB', 60, 'Mbinga DC', 'TZ.RV'),
('TZ.RV.MT', 169, 'Mbinga TC', 'TZ.RV'),
('TZ.RV.NA', 83, 'Namtumbo DC', 'TZ.RV'),
('TZ.RV.NY', 168, 'Nyasa DC', 'TZ.RV'),
('TZ.RV.SR', 104, 'Songea DC', 'TZ.RV'),
('TZ.RV.SU', 105, 'Songea MC', 'TZ.RV'),
('TZ.RV.TU', 113, 'Tunduru DC', 'TZ.RV'),
('TZ.SD.ID', 176, 'Ikungi DC', 'TZ.SD'),
('TZ.SD.IR', 18, 'Iramba DC', 'TZ.SD'),
('TZ.SD.IT', 177, 'Itigi DC', 'TZ.SD'),
('TZ.SD.MA', 54, 'Manyoni DC', 'TZ.SD'),
('TZ.SD.MK', 178, 'Mkalama DC', 'TZ.SD'),
('TZ.SD.SI', 102, 'Singida DC', 'TZ.SD'),
('TZ.SD.SU', 103, 'Singida MC', 'TZ.SD'),
('TZ.SG.IL', 179, 'Ileje DC', 'TZ.SG'),
('TZ.SG.MD', 180, 'Mbozi DC', 'TZ.SG'),
('TZ.SG.MO', 181, 'Momba DC', 'TZ.SG'),
('TZ.SG.SG', 183, 'Songwe DC', 'TZ.SG'),
('TZ.SG.TT', 182, 'Tunduma TC', 'TZ.SG'),
('TZ.SI.BA', 5, 'Bariadi TC', 'TZ.SI'),
('TZ.SI.BD', 175, 'Bariadi DC', 'TZ.SI'),
('TZ.SI.BU', 173, 'Busega DC', 'TZ.SI'),
('TZ.SI.IT', 174, 'Itilima DC', 'TZ.SI'),
('TZ.SI.MA', 56, 'Maswa DC', 'TZ.SI'),
('TZ.SI.ME', 62, 'Meatu DC', 'TZ.SI'),
('TZ.SY.KA', 21, 'Kahama TC', 'TZ.SY'),
('TZ.SY.KI', 39, 'Kishapu DC', 'TZ.SY'),
('TZ.SY.MS', 171, 'Msalala DC', 'TZ.SY'),
('TZ.SY.SR', 98, 'Shinyanga DC', 'TZ.SY'),
('TZ.SY.SU', 99, 'Shinyanga MC', 'TZ.SY'),
('TZ.SY.US', 172, 'Ushetu DC', 'TZ.SY'),
('TZ.TB.IG', 16, 'Igunga DC', 'TZ.TB'),
('TZ.TB.KA', 184, 'Kaliua DC', 'TZ.TB'),
('TZ.TB.NT', 185, 'Nzega TC', 'TZ.TB'),
('TZ.TB.NZ', 90, 'Nzega DC', 'TZ.TB'),
('TZ.TB.SI', 100, 'Sikonge DC', 'TZ.TB'),
('TZ.TB.TA', 108, 'Tabora MC', 'TZ.TB'),
('TZ.TB.UR', 116, 'Urambo DC', 'TZ.TB'),
('TZ.TB.UY', 117, 'Uyui DC', 'TZ.TB'),
('TZ.TN.BU', 187, 'Bumbuli DC', 'TZ.TN'),
('TZ.TN.HA', 15, 'Handeni DC', 'TZ.TN'),
('TZ.TN.HT', 189, 'Handeni TC', 'TZ.TN'),
('TZ.TN.KI', 32, 'Kilindi DC', 'TZ.TN'),
('TZ.TN.KO', 43, 'Korogwe DC', 'TZ.TN'),
('TZ.TN.KT', 188, 'Korogwe TC', 'TZ.TN'),
('TZ.TN.LU', 51, 'Lushoto DC', 'TZ.TN'),
('TZ.TN.MK', 186, 'Mkinga DC', 'TZ.TN'),
('TZ.TN.MU', 77, 'Muheza DC', 'TZ.TN'),
('TZ.TN.PA', 91, 'Pangani DC', 'TZ.TN'),
('TZ.TN.TA', 110, 'Tanga CC', 'TZ.TN'),
('TZ.XX.XX', 194, 'Fake District', 'TZ.XX'),
('TZ.ZN.NA', 24, 'Kaskazini ''A''', 'TZ.ZN'),
('TZ.ZN.NB', 25, 'Kaskazini ''B''', 'TZ.ZN'),
('TZ.ZS.CE', 27, 'Kati', 'TZ.ZS'),
('TZ.ZS.SO', 44, 'Kusini', 'TZ.ZS'),
('TZ.ZW.TO', 64, 'Mjini', 'TZ.ZW'),
('TZ.ZW.WE', 119, 'Magharibi', 'TZ.ZW');

-- --------------------------------------------------------

--
-- Table structure for table `fundingcurrencies`
--

CREATE TABLE IF NOT EXISTS `fundingcurrencies` (
  `currencyCode` varchar(5) NOT NULL,
  `fundingCurrency_id` smallint(5) unsigned NOT NULL,
  `currency` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=170 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fundingcurrencies`
--

INSERT INTO `fundingcurrencies` (`currencyCode`, `fundingCurrency_id`, `currency`) VALUES
('AED', 1, 'UAE Dirham'),
('AFN', 2, 'Afghani'),
('ALL', 3, 'Lek'),
('AMD', 4, 'Armenian Dram'),
('ANG', 5, 'Netherlands Antillian Guilder'),
('AOA', 6, 'Kwanza'),
('ARS', 7, 'Argentine Peso'),
('AUD', 8, 'Australian Dollar'),
('AWG', 9, 'Aruban Guilder'),
('AZN', 10, 'Azerbaijanian Manat'),
('BAM', 11, 'Convertible Marks'),
('BBD', 12, 'Barbados Dollar'),
('BDT', 13, 'Taka'),
('BGN', 14, 'Bulgarian Lev'),
('BHD', 15, 'Bahraini Dinar'),
('BIF', 16, 'Burundi Franc'),
('BMD', 17, 'Bermudian Dollar'),
('BND', 18, 'Brunei Dollar'),
('BOB', 19, 'Boliviano'),
('BOV', 20, 'Mvdol'),
('BRL', 21, 'Brazilian Real'),
('BSD', 22, 'Bahamian Dollar'),
('BTN', 23, 'Ngultrum'),
('BWP', 24, 'Pula'),
('BYR', 25, 'Belarussian Ruble'),
('BZD', 26, 'Belize Dollar'),
('CAD', 27, 'Canadian Dollar'),
('CDF', 28, 'Congolese Franc'),
('CHF', 29, 'Swiss Franc'),
('CLF', 30, 'Unidades de fomento'),
('CLP', 31, 'Chilean Peso'),
('CNY', 32, 'Yuan Renminbi'),
('COP', 33, 'Colombian Peso'),
('COU', 34, 'Unidad de Valor Real'),
('CRC', 35, 'Costa Rican Colon'),
('CUC', 36, 'Peso Convertible'),
('CUP', 37, 'Cuban Peso'),
('CVE', 38, 'Cape Verde Escudo'),
('CZK', 39, 'Czech Koruna'),
('DJF', 40, 'Djibouti Franc'),
('DKK', 41, 'Danish Krone'),
('DOP', 42, 'Dominican Peso'),
('DZD', 43, 'Algerian Dinar'),
('EEK', 44, 'Kroon'),
('EGP', 45, 'Egyptian Pound'),
('ERN', 46, 'Nakfa'),
('ETB', 47, 'Ethiopian Birr'),
('EUR', 48, 'Euro'),
('FJD', 49, 'Fiji Dollar'),
('FKP', 50, 'Falkland Islands Pound'),
('GBP', 51, 'Pound Sterling'),
('GEL', 52, 'Lari'),
('GHS', 53, 'Cedi'),
('GIP', 54, 'Gibraltar Pound'),
('GMD', 55, 'Dalasi'),
('GNF', 56, 'Guinea Franc'),
('GTQ', 57, 'Quetzal'),
('GYD', 58, 'Guyana Dollar'),
('HKD', 59, 'Hong Kong Dollar'),
('HNL', 60, 'Lempira'),
('HRK', 61, 'Kuna'),
('HTG', 62, 'Gourde'),
('HUF', 63, 'Forint'),
('IDR', 64, 'Rupiah'),
('ILS', 65, 'New Israeli Sheqel'),
('INR', 66, 'Indian Rupee'),
('IQD', 67, 'Iraqi Dinar'),
('IRR', 68, 'Iranian Rial'),
('ISK', 69, 'Iceland Krona'),
('JMD', 70, 'Jamaican Dollar'),
('JOD', 71, 'Jordanian Dinar'),
('JPY', 72, 'Yen'),
('KES', 73, 'Kenyan Shilling'),
('KGS', 74, 'Som'),
('KHR', 75, 'Riel'),
('KMF', 76, 'Comoro Franc'),
('KPW', 77, 'North Korean Won'),
('KRW', 78, 'Won'),
('KWD', 79, 'Kuwaiti Dinar'),
('KYD', 80, 'Cayman Islands Dollar'),
('KZT', 81, 'Tenge'),
('LAK', 82, 'Kip'),
('LBP', 83, 'Lebanese Pound'),
('LKR', 84, 'Sri Lanka Rupee'),
('LRD', 85, 'Liberian Dollar'),
('LSL', 86, 'Loti'),
('LTL', 87, 'Lithuanian Litas'),
('LVL', 88, 'Latvian Lats'),
('LYD', 89, 'Libyan Dinar'),
('MAD', 90, 'Moroccan Dirham'),
('MDL', 91, 'Moldovan Leu'),
('MGA', 92, 'Malagasy Ariary'),
('MKD', 93, 'Denar'),
('MMK', 94, 'Kyat'),
('MNT', 95, 'Tugrik'),
('MOP', 96, 'Pataca'),
('MRO', 97, 'Ouguiya'),
('MUR', 98, 'Mauritius Rupee'),
('MVR', 99, 'Rufiyaa'),
('MWK', 100, 'Malawi Kwacha'),
('MXN', 101, 'Mexican Peso'),
('MXV', 102, 'Mexican Unidad de Inversion (UDI)'),
('MYR', 103, 'Malaysian Ringgit'),
('MZN', 104, 'Metical'),
('NAD', 105, 'Namibia Dollar'),
('NGN', 106, 'Naira'),
('NIO', 107, 'Cordoba Oro'),
('NOK', 108, 'Norwegian Krone'),
('NPR', 109, 'Nepalese Rupee'),
('NZD', 110, 'New Zealand Dollar'),
('OMR', 111, 'Rial Omani'),
('PAB', 112, 'Balboa'),
('PEN', 113, 'Nuevo Sol'),
('PGK', 114, 'Kina'),
('PHP', 115, 'Philippine Peso'),
('PKR', 116, 'Pakistan Rupee'),
('PLN', 117, 'Zloty'),
('PYG', 118, 'Guarani'),
('QAR', 119, 'Qatari Rial'),
('RON', 120, 'Romanian Leu'),
('RSD', 121, 'Serbian Dinar'),
('RUB', 122, 'Russian Ruble'),
('RWF', 123, 'Rwanda Franc'),
('SAR', 124, 'Saudi Riyal'),
('SBD', 125, 'Solomon Islands Dollar'),
('SCR', 126, 'Seychelles Rupee'),
('SDG', 127, 'Sudanese Pound'),
('SEK', 128, 'Swedish Krona'),
('SGD', 129, 'Singapore Dollar'),
('SHP', 130, 'Saint Helena Pound'),
('SLL', 131, 'Leone'),
('SOS', 132, 'Somali Shilling'),
('SRD', 134, 'Surinam Dollar'),
('SSP', 133, 'South Sudanese Pound'),
('STD', 135, 'Dobra'),
('SVC', 136, 'El Salvador Colon'),
('SYP', 137, 'Syrian Pound'),
('SZL', 138, 'Lilangeni'),
('THB', 139, 'Baht'),
('TJS', 140, 'Somoni'),
('TMT', 141, 'Manat'),
('TND', 142, 'Tunisian Dinar'),
('TOP', 143, 'Paanga'),
('TRY', 144, 'Turkish Lira'),
('TTD', 145, 'Trinidad and Tobago Dollar'),
('TWD', 146, 'New Taiwan Dollar'),
('TZS', 147, 'Tanzanian Shilling'),
('UAH', 148, 'Hryvnia'),
('UGX', 149, 'Uganda Shilling'),
('USD', 150, 'US Dollar'),
('USN', 151, 'US Dollar (Next day)'),
('USS', 152, 'US Dollar (Same day)'),
('UYI', 153, 'Uruguay Peso en Unidades Indexadas'),
('UYU', 154, 'Peso Uruguayo'),
('UZS', 155, 'Uzbekistan Sum'),
('VEF', 156, 'Bolivar'),
('VND', 157, 'Dong'),
('VUV', 158, 'Vatu'),
('WST', 159, 'Tala'),
('XAF', 160, 'CFA Franc BEAC'),
('XBT', 161, 'Bitcoin'),
('XCD', 162, 'East Caribbean Dollar'),
('XDR', 163, 'International Monetary Fund (IMF) Special Drawing '),
('XOF', 164, 'CFA Franc BCEAO'),
('XPF', 165, 'CFP Franc'),
('YER', 166, 'Yemeni Rial'),
('ZAR', 167, 'Rand'),
('ZMK', 168, 'Zambian Kwacha'),
('ZWL', 169, 'Zimbabwe Dollar');

-- --------------------------------------------------------

--
-- Table structure for table `organisations`
--

CREATE TABLE IF NOT EXISTS `organisations` (
  `organisation_id` smallint(5) unsigned NOT NULL,
  `name` varchar(50) NOT NULL,
  `type_id` smallint(5) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `organisations`
--

-- --------------------------------------------------------

--
-- Table structure for table `organisationtypes`
--

CREATE TABLE IF NOT EXISTS `organisationtypes` (
  `type_id` smallint(5) unsigned NOT NULL,
  `type` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `organisationtypes`
--

INSERT INTO `organisationtypes` (`type_id`, `type`) VALUES
(1, 'Government'),
(2, 'Other Public Sector'),
(3, 'International NGO'),
(4, 'National NGO'),
(5, 'Regional NGO'),
(6, 'Public Private Partnership'),
(7, 'Multilateral'),
(8, 'Foundation'),
(9, 'Private Sector'),
(10, 'Academic, Training and Research');

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE IF NOT EXISTS `regions` (
  `regHasc` varchar(6) NOT NULL,
  `region_id` smallint(5) unsigned NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`regHasc`, `region_id`, `name`) VALUES
('TZ.AS', 2, 'Arusha'),
('TZ.DO', 1, 'Dodoma'),
('TZ.DS', 7, 'Dar-es-salaam'),
('TZ.GE', 27, 'Geita'),
('TZ.IG', 11, 'Iringa'),
('TZ.KA', 28, 'Katavi'),
('TZ.KG', 18, 'Kagera'),
('TZ.KL', 3, 'Kilimanjaro'),
('TZ.KM', 16, 'Kigoma'),
('TZ.LI', 8, 'Lindi'),
('TZ.MA', 20, 'Mara'),
('TZ.MB', 12, 'Mbeya'),
('TZ.MO', 5, 'Morogoro'),
('TZ.MT', 9, 'Mtwara'),
('TZ.MY', 21, 'Manyara'),
('TZ.MZ', 19, 'Mwanza'),
('TZ.NJ', 29, 'Njombe'),
('TZ.PN', 25, 'Kaskazini-Pemba'),
('TZ.PS', 26, 'Kusini-Pemba'),
('TZ.PW', 6, 'Pwani'),
('TZ.RU', 15, 'Rukwa'),
('TZ.RV', 10, 'Ruvuma'),
('TZ.SD', 13, 'Singida'),
('TZ.SG', 31, 'Songwe'),
('TZ.SI', 30, 'Simiyu'),
('TZ.SY', 17, 'Shinyanga'),
('TZ.TB', 14, 'Tabora'),
('TZ.TN', 4, 'Tanga'),
('TZ.XX', 32, 'Fake Region'),
('TZ.ZN', 22, 'Kaskazini-Unguja'),
('TZ.ZS', 23, 'Kusini-Unguja'),
('TZ.ZW', 24, 'Mjini Magharibi');

-- --------------------------------------------------------

--
-- Table structure for table `wards`
--

CREATE TABLE IF NOT EXISTS `wards` (
  `ward_id` smallint(5) unsigned NOT NULL,
  `wardcode` varchar(8) NOT NULL,
  `name` varchar(21) NOT NULL,
  `distHasc` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`activity_Id`), ADD KEY `activities_pk` (`activity_Id`), ADD KEY `activityclassifications_activities_fk` (`classificationCode`), ADD KEY `activitysubclassification_activities_fk` (`subclassification_id`), ADD KEY `activitythemes_activities_fk` (`activityThemes_id`), ADD KEY `fundingcurrencies_activities_fk` (`currencyCode`);

--
-- Indexes for table `activityclassifications`
--
ALTER TABLE `activityclassifications`
  ADD PRIMARY KEY (`classificationCode`), ADD KEY `classification_Id` (`classification_id`), ADD KEY `activityclassifications_pk` (`classificationCode`);

--
-- Indexes for table `activitycoverage`
--
ALTER TABLE `activitycoverage`
  ADD PRIMARY KEY (`activityCoverage_id`,`activity_Id`,`ward_id`,`regHasc`,`distHasc`), ADD KEY `activitycoverage_pk` (`activityCoverage_id`,`activity_Id`,`ward_id`,`regHasc`,`distHasc`), ADD KEY `activities_activitycoverage_fk` (`activity_Id`), ADD KEY `districts_activitycoverage_fk` (`distHasc`), ADD KEY `regions_activitycoverage_fk` (`regHasc`), ADD KEY `wards_activitycoverage_fk` (`ward_id`);

--
-- Indexes for table `activityorganisations`
--
ALTER TABLE `activityorganisations`
  ADD PRIMARY KEY (`activityOrganisation_id`), ADD KEY `activityorganisations_pk` (`activityOrganisation_id`), ADD KEY `organisations_activitypartnerorganisations_fk` (`organisation_id`), ADD KEY `organisations_activity_fk` (`activity_id`);

--
-- Indexes for table `activitysubclassifications`
--
ALTER TABLE `activitysubclassifications`
  ADD PRIMARY KEY (`subclassification_id`), ADD KEY `activitysubclassification_pk` (`subclassification_id`), ADD KEY `activityclassifications_activitysubclassification_fk` (`classificationCode`);

--
-- Indexes for table `activitythemes`
--
ALTER TABLE `activitythemes`
  ADD PRIMARY KEY (`activityThemes_id`), ADD KEY `activitythemes_pk` (`activityThemes_id`);

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`distHasc`), ADD UNIQUE KEY `distHasc` (`distHasc`), ADD UNIQUE KEY `name` (`name`), ADD KEY `district_id` (`district_id`), ADD KEY `districts_pk` (`distHasc`), ADD KEY `regions_districts_fk` (`regHasc`);

--
-- Indexes for table `fundingcurrencies`
--
ALTER TABLE `fundingcurrencies`
  ADD PRIMARY KEY (`currencyCode`), ADD KEY `fundingCurrency_id` (`fundingCurrency_id`), ADD KEY `fundingcurrencies_pk` (`currencyCode`);

--
-- Indexes for table `organisations`
--
ALTER TABLE `organisations`
  ADD PRIMARY KEY (`organisation_id`), ADD KEY `organisations_pk` (`organisation_id`), ADD KEY `organisationtypes_organisations_fk` (`type_id`);

--
-- Indexes for table `organisationtypes`
--
ALTER TABLE `organisationtypes`
  ADD PRIMARY KEY (`type_id`), ADD KEY `organisationtypes_pk` (`type_id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`regHasc`), ADD UNIQUE KEY `regHasc` (`regHasc`), ADD UNIQUE KEY `name` (`name`), ADD KEY `region_id` (`region_id`), ADD KEY `regions_pk` (`regHasc`);

--
-- Indexes for table `wards`
--
ALTER TABLE `wards`
  ADD PRIMARY KEY (`ward_id`), ADD KEY `wards_pk` (`ward_id`), ADD KEY `districts_wards_fk` (`distHasc`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `activity_Id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=125;
--
-- AUTO_INCREMENT for table `activityclassifications`
--
ALTER TABLE `activityclassifications`
  MODIFY `classification_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `activitycoverage`
--
ALTER TABLE `activitycoverage`
  MODIFY `activityCoverage_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=232;
--
-- AUTO_INCREMENT for table `activityorganisations`
--
ALTER TABLE `activityorganisations`
  MODIFY `activityOrganisation_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `activitysubclassifications`
--
ALTER TABLE `activitysubclassifications`
  MODIFY `subclassification_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `activitythemes`
--
ALTER TABLE `activitythemes`
  MODIFY `activityThemes_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `district_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=196;
--
-- AUTO_INCREMENT for table `fundingcurrencies`
--
ALTER TABLE `fundingcurrencies`
  MODIFY `fundingCurrency_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=170;
--
-- AUTO_INCREMENT for table `organisations`
--
ALTER TABLE `organisations`
  MODIFY `organisation_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `organisationtypes`
--
ALTER TABLE `organisationtypes`
  MODIFY `type_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `region_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `wards`
--
ALTER TABLE `wards`
  MODIFY `ward_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
ADD CONSTRAINT `activityclassifications_activities_fk` FOREIGN KEY (`classificationCode`) REFERENCES `activityclassifications` (`classificationCode`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `activitysubclassification_activities_fk` FOREIGN KEY (`subclassification_id`) REFERENCES `activitysubclassifications` (`subclassification_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `activitythemes_activities_fk` FOREIGN KEY (`activityThemes_id`) REFERENCES `activitythemes` (`activityThemes_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fundingcurrencies_activities_fk` FOREIGN KEY (`currencyCode`) REFERENCES `fundingcurrencies` (`currencyCode`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `activitycoverage`
--
ALTER TABLE `activitycoverage`
ADD CONSTRAINT `activities_activitycoverage_fk` FOREIGN KEY (`activity_Id`) REFERENCES `activities` (`activity_Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `activityorganisations`
--
ALTER TABLE `activityorganisations`
ADD CONSTRAINT `organisations_activity_fk` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`activity_Id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `organisations_activitypartnerorganisations_fk` FOREIGN KEY (`organisation_id`) REFERENCES `organisations` (`organisation_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `activitysubclassifications`
--
ALTER TABLE `activitysubclassifications`
ADD CONSTRAINT `activityclassifications_activitysubclassification_fk` FOREIGN KEY (`classificationCode`) REFERENCES `activityclassifications` (`classificationCode`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `districts`
--
ALTER TABLE `districts`
ADD CONSTRAINT `regions_districts_fk` FOREIGN KEY (`regHasc`) REFERENCES `regions` (`regHasc`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `organisations`
--
ALTER TABLE `organisations`
ADD CONSTRAINT `organisationtypes_organisations_fk` FOREIGN KEY (`type_id`) REFERENCES `organisationtypes` (`type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `wards`
--
ALTER TABLE `wards`
ADD CONSTRAINT `districts_wards_fk` FOREIGN KEY (`distHasc`) REFERENCES `districts` (`distHasc`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
