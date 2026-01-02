-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 02, 2026 at 08:55 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mehar_baba`
--

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(255) NOT NULL,
  `emp_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `cell` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `emp_id`, `name`, `cell`, `status`) VALUES
(1, '0001', 'Usman bashir', '0900', 1),
(2, '0002', 'Fareed', '12345', 0),
(3, '0003', 'Mani dogar', '1234', 1);

-- --------------------------------------------------------

--
-- Table structure for table `employee_ledger`
--

CREATE TABLE `employee_ledger` (
  `id` int(255) NOT NULL,
  `emp_id` varchar(255) NOT NULL,
  `details` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `add_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_ledger`
--

INSERT INTO `employee_ledger` (`id`, `emp_id`, `details`, `amount`, `date`, `time`, `type`, `add_by`) VALUES
(1, '0001', 'Advance wages', 5500.00, '2025-12-01', '07:40 PM', 'Debit', '1'),
(2, '0001', 'Advance wages', 2300.00, '2025-12-03', '07:42 PM', 'Credit', '1'),
(4, '0002', 'Wages payemnt', 2500.00, '2025-12-31', '07:45 PM', 'Debit', '1'),
(5, '0003', 'Loan from employee', 25000.00, '2025-12-31', '07:47 PM', 'Credit', '1'),
(6, '0001', 'Cash received', 3000.00, '2025-12-31', '08:40 PM', 'Credit', '1');

-- --------------------------------------------------------

--
-- Table structure for table `erp_type`
--

CREATE TABLE `erp_type` (
  `id` int(255) NOT NULL,
  `type_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `erp_type`
--

INSERT INTO `erp_type` (`id`, `type_id`, `name`) VALUES
(1, '001', 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(255) NOT NULL,
  `item_id` varchar(255) NOT NULL,
  `qty` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `item_id`, `qty`) VALUES
(1, '0003', 19.00),
(2, '0001', 3.00),
(3, '0002', 10.00);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_batch`
--

CREATE TABLE `inventory_batch` (
  `id` int(255) NOT NULL,
  `batch_no` varchar(255) NOT NULL,
  `item_id` varchar(255) NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_batch`
--

INSERT INTO `inventory_batch` (`id`, `batch_no`, `item_id`, `qty`, `price`, `date`, `status`) VALUES
(1, '2026-1-1', '0003', 2.00, 1300.00, '2026-01-01', 0),
(2, '2026-1-2', '0003', 5.00, 100.00, '2026-01-01', 0),
(3, '2026-1-3', '0001', 0.00, 1750.00, '2026-01-01', 1),
(4, '2026-1-4', '0001', 1.00, 1850.00, '2026-01-01', 0),
(5, '2026-1-5', '0001', 2.00, 1700.00, '2026-01-01', 0),
(6, '2026-1-6', '0002', 2.00, 1142.00, '2026-01-01', 0),
(7, '2026-1-7', '0002', 5.00, 1200.00, '2026-01-01', 0),
(8, '2026-1-8', '0002', 3.00, 1000.00, '2026-01-01', 0),
(9, '2026-1-9', '0003', 2.00, 500.00, '2026-01-02', 0),
(10, '2026-1-10', '0003', 10.00, 50.00, '2026-01-02', 0);

-- --------------------------------------------------------

--
-- Table structure for table `inventory_out`
--

CREATE TABLE `inventory_out` (
  `id` int(255) NOT NULL,
  `batch_no` varchar(255) NOT NULL,
  `item_id` varchar(255) NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(255) NOT NULL,
  `add_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_out`
--

INSERT INTO `inventory_out` (`id`, `batch_no`, `item_id`, `qty`, `price`, `date`, `time`, `add_by`) VALUES
(1, '2026-1-3', '0001', 5.00, 1750.00, '2026-01-01', '10:32 PM', '1'),
(2, '2026-1-4', '0001', 2.00, 1850.00, '2026-01-01', '10:32 PM', '1'),
(3, '2026-1-1', '0003', 2.00, 1300.00, '2026-01-02', '10:32 PM', '1'),
(4, '2026-1-6', '0002', 3.00, 1142.00, '2026-01-01', '10:33 PM', '1'),
(5, '2026-1-1', '0003', 1.00, 1300.00, '2026-01-02', '12:07 AM', '1');

-- --------------------------------------------------------

--
-- Table structure for table `item_table`
--

CREATE TABLE `item_table` (
  `id` int(255) NOT NULL,
  `item_id` varchar(255) NOT NULL,
  `cat_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sale_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_table`
--

INSERT INTO `item_table` (`id`, `item_id`, `cat_id`, `name`, `sale_price`) VALUES
(1, '0001', '001', 'Special makhni karahi', 3500.00),
(2, '0002', '001', 'Special yakhni(black papper)', 3500.00),
(4, '0004', '002', 'Regular', 3300.00),
(6, '0006', '002', 'Shnwari', 3400.00),
(8, '0008', '002', 'Achari', 3400.00),
(10, '0010', '002', 'White', 3400.00),
(12, '0012', '002', 'Makhni special', 3500.00),
(13, '0013', '003', 'Regular', 2000.00),
(14, '0014', '003', 'White', 2200.00),
(15, '0015', '003', 'Sulemani', 2200.00),
(16, '0016', '003', 'Shanwari', 2200.00),
(17, '0017', '003', 'Special makhni', 2300.00),
(18, '0018', '004', 'Regular', 1700.00),
(19, '0019', '004', 'Achari', 1900.00),
(20, '0020', '004', 'White', 1900.00),
(21, '0021', '004', 'Shnwari', 1900.00),
(22, '0022', '004', 'Special makhni', 2000.00),
(23, '0023', '005', 'Chicken tikka', 150.00),
(24, '0024', '005', 'Malai boti', 300.00),
(25, '0025', '005', 'Rajstani tikka', 350.00),
(26, '0026', '005', 'Hari bhari boti', 350.00),
(27, '0027', '005', 'Hara bhara rajstani', 350.00),
(28, '0028', '005', 'Chicken achari boti', 250.00),
(29, '0029', '005', 'Chicken kabab', 140.00),
(30, '0030', '005', 'Chicken gola kabab', 200.00),
(31, '0031', '005', 'Chicken cheese kabab', 230.00),
(32, '0032', '005', 'Chicken reshmi kabab', 250.00),
(33, '0033', '005', 'Chicken russian kabab', 250.00),
(34, '0034', '005', 'Chicken sikandri kabab', 250.00),
(35, '0035', '006', 'Chicken', 1100.00),
(36, '0036', '006', 'White', 1300.00),
(37, '0037', '006', 'Jalferazi', 1300.00),
(38, '0038', '006', 'Achari', 1300.00),
(39, '0039', '006', 'Ginger', 1300.00),
(40, '0040', '006', 'Kabab masala', 1400.00),
(41, '0041', '006', 'Special makhni', 1400.00),
(42, '0042', '007', 'Roti', 20.00),
(43, '0043', '007', 'Roghni naan', 80.00),
(44, '0044', '007', 'Kalwanji naan', 80.00),
(45, '0045', '007', 'Garlic naan', 120.00),
(46, '0046', '007', 'Qeema naan', 400.00),
(47, '0047', '007', 'Chicken cheese naan', 500.00),
(48, '0048', '007', 'Pizza naan', 500.00),
(49, '0049', '007', 'Cheese naan', 350.00),
(50, '0050', '007', 'Tandoori paratha', 120.00),
(51, '0051', '007', 'Achari paratha', 150.00),
(52, '0052', '008', 'Chicken(raita + salad)', 350.00),
(53, '0053', '009', 'Daal - half', 200.00),
(54, '0054', '009', 'Daal - full', 300.00),
(55, '0055', '009', 'Mix sabzi - half', 200.00),
(56, '0056', '009', 'Mix sabzi - full', 300.00),
(57, '0057', '009', 'Chicken qorma - half', 300.00),
(58, '0058', '009', 'Chicken qorma - full', 500.00),
(59, '0059', '009', 'Tawa qeema - half', 600.00),
(60, '0060', '009', 'Tawa qeema - full', 1000.00),
(61, '0061', '010', 'Fresh salad', 100.00),
(62, '0062', '010', 'Kachoomar salad', 200.00),
(63, '0063', '010', 'Mint raita', 60.00),
(64, '0064', '010', 'Zeera raita', 60.00),
(65, '0065', '011', 'Fajita - s', 600.00),
(66, '0066', '011', 'Fajita - m', 1000.00),
(67, '0067', '011', 'Fajita - l', 1500.00),
(68, '0068', '011', 'Tikka - s', 600.00),
(69, '0069', '011', 'Tikka - m', 1000.00),
(70, '0070', '011', 'Tikka - l', 1500.00),
(71, '0071', '011', 'Bbq - s', 600.00),
(72, '0072', '011', 'Bbq - m', 1000.00),
(73, '0073', '011', 'Bbq - l', 1500.00),
(74, '0074', '011', 'Supreme - s', 700.00),
(75, '0075', '011', 'Supreme - m', 1100.00),
(76, '0076', '011', 'Supreme - l', 1600.00),
(77, '0077', '011', 'Malai boti - s', 700.00),
(78, '0078', '011', 'Malai boti - m', 1100.00),
(79, '0079', '011', 'Malai boti - l', 1600.00),
(80, '0080', '011', 'Cheese lover - s', 700.00),
(81, '0081', '011', 'Cheese lover - m', 1100.00),
(82, '0082', '011', 'Cheese lover - l', 1600.00),
(83, '0083', '011', 'Mb special - m', 1300.00),
(84, '0084', '011', 'Mb special - l', 1800.00),
(85, '0085', '011', 'Kabab crust - m', 1300.00),
(86, '0086', '011', 'Kabab crust - l', 1800.00),
(87, '0087', '012', 'Chicken', 280.00),
(88, '0088', '012', 'Chicken olive', 350.00),
(89, '0089', '012', 'Zinger', 350.00),
(90, '0090', '012', 'Grill', 400.00),
(91, '0091', '012', 'Zinger jelapeno', 420.00),
(92, '0092', '012', 'Zinger tower', 520.00),
(93, '0093', '013', 'Chicken', 280.00),
(94, '0094', '013', 'Zinger', 320.00),
(95, '0095', '013', 'Grill', 350.00),
(96, '0096', '013', 'Mb special', 400.00),
(97, '0097', '013', 'Platter - (2bread)', 600.00),
(98, '0098', '013', 'Bread', 70.00),
(99, '0099', '014', 'Malai boti', 380.00),
(100, '0100', '014', 'Tikka boti', 280.00),
(101, '0101', '014', 'Kabab', 220.00),
(102, '0102', '015', 'Mb special', 550.00),
(103, '0103', '016', 'Masala fries', 300.00),
(104, '0104', '016', 'Plain fries', 200.00),
(105, '0105', '016', 'Nuggets - (6pc)', 500.00),
(106, '0106', '016', 'Nuggets - (9pc)', 750.00),
(107, '0107', '016', 'Hot wings (6pc)', 450.00),
(108, '0108', '016', 'Hot wings (9pc)', 650.00),
(109, '0109', '016', 'Hotshot (6pc)', 400.00),
(110, '0110', '016', 'Hotshot (9pc)', 600.00),
(111, '0111', '016', 'Loaded fries', 650.00),
(112, '0112', '017', 'Mint magrita', 150.00),
(113, '0113', '017', 'Regular', 70.00),
(114, '0114', '017', '1 ltr', 170.00),
(115, '0115', '017', '1.5 ltr', 220.00),
(116, '0116', '017', 'Water - s', 70.00),
(117, '0117', '017', 'Water - 1.5', 120.00),
(118, '0118', '017', 'Tea', 120.00),
(119, '0119', '018', 'Oreo', 450.00),
(120, '0120', '018', 'Vanilla', 450.00),
(121, '0121', '018', 'Mango', 450.00),
(122, '0122', '018', 'Strawberry', 450.00),
(123, '0123', '019', '(one plate)', 200.00),
(124, '0124', '020', '1zinger burger, 1fries, 1regular drink', 550.00),
(125, '0125', '021', '1zinger burger, 1small pizza, 1fries, 1ltr drink', 1200.00),
(126, '0126', '022', '1zinger burger, 1chicken burger, 1shawarma, 1zinger shwarma, 1small pizza 1fries, 1.5ltr drink', 2000.00),
(127, '0127', '023', '4zinger burger, 4chicken shwarma, 1large pizza, 1fries, 1.5ltr drink', 4000.00),
(128, '0128', '025', '1zinger burger, 1chicken shwarma, 1small pizza, 1malai boti roll, 1sandwich, 2chicken pcs, 6pcs nuggets, 1fries, 1.5ltr drink', 3500.00);

-- --------------------------------------------------------

--
-- Table structure for table `main_category`
--

CREATE TABLE `main_category` (
  `id` int(255) NOT NULL,
  `cat_id` varchar(255) NOT NULL,
  `level_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `main_category`
--

INSERT INTO `main_category` (`id`, `cat_id`, `level_id`, `name`) VALUES
(1, '001', '01', 'Desi murgha'),
(2, '002', '01', 'Mutton karahi'),
(3, '003', '01', 'Beaf karahi'),
(4, '004', '01', 'Chicken karahi'),
(5, '005', '01', 'Bbq'),
(6, '006', '01', 'Boneless handi'),
(7, '007', '01', 'Tandoor'),
(8, '008', '01', 'Biryani'),
(9, '009', '01', 'Simple handi'),
(10, '010', '01', 'Raita/salad'),
(11, '011', '02', 'Pizza'),
(12, '012', '02', 'Burger'),
(13, '013', '02', 'Shwarma'),
(14, '014', '02', 'Paratha roll'),
(15, '015', '02', 'Sandwich'),
(16, '016', '02', 'Snacks'),
(17, '017', '02', 'Cold drinks'),
(18, '018', '02', 'Shake'),
(19, '019', '01', 'Sweet dish'),
(20, '020', '03', 'Runway'),
(21, '021', '03', 'Combo'),
(22, '022', '03', 'Yum pum'),
(23, '023', '03', 'Family'),
(25, '025', '03', 'All in one');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `new_order`
--

CREATE TABLE `new_order` (
  `id` int(255) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `add_by` varchar(255) NOT NULL,
  `add_date` date NOT NULL,
  `add_time` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `clear_by` varchar(255) DEFAULT NULL,
  `clear_date` date DEFAULT NULL,
  `clear_time` varchar(255) DEFAULT NULL,
  `discount` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `new_order`
--

INSERT INTO `new_order` (`id`, `order_id`, `add_by`, `add_date`, `add_time`, `status`, `clear_by`, `clear_date`, `clear_time`, `discount`) VALUES
(1, 'mb-2025-1', '1', '2026-12-29', '11:00 AM', 1, '1', '2026-12-29', '11:02 AM', 1),
(2, 'mb-2025-2', '1', '2026-12-29', '11:05 AM', 1, '1', '2026-12-29', '11:05 AM', 1),
(5, 'mb-2025-3', '1', '2025-12-31', '09:47 PM', 1, '1', '2025-12-31', '09:50 PM', 1),
(6, 'mb-2026-1', '1', '2026-01-01', '03:33 PM', 0, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `new_purchase_order`
--

CREATE TABLE `new_purchase_order` (
  `id` int(255) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `add_by` varchar(255) NOT NULL,
  `add_date` date NOT NULL,
  `add_time` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `clear_by` varchar(255) DEFAULT NULL,
  `clear_date` date DEFAULT NULL,
  `clear_time` varchar(255) DEFAULT NULL,
  `discount` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `new_purchase_order`
--

INSERT INTO `new_purchase_order` (`id`, `order_id`, `add_by`, `add_date`, `add_time`, `status`, `clear_by`, `clear_date`, `clear_time`, `discount`) VALUES
(1, '2026-1', '1', '2026-01-01', '04:45 PM', 0, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_bill`
--

CREATE TABLE `order_bill` (
  `id` int(255) NOT NULL,
  `bill_no` varchar(255) NOT NULL,
  `paid` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `clear_date` date NOT NULL,
  `clear_time` varchar(255) NOT NULL,
  `clear_by` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_bill`
--

INSERT INTO `order_bill` (`id`, `bill_no`, `paid`, `discount`, `clear_date`, `clear_time`, `clear_by`) VALUES
(1, 'mb-2025-1', 3400.00, 0.00, '2026-12-29', '11:02 AM', 1),
(2, 'mb-2025-2', 5000.00, 0.00, '2026-12-29', '11:06 AM', 1),
(3, 'mb-2025-3', 5000.00, 875.00, '2025-12-31', '10:00 PM', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(255) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `item_id` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `clear_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `item_id`, `price`, `qty`, `clear_date`) VALUES
(1, 'mb-2025-1', '0001', 3500.00, 0.75, '2026-12-29'),
(2, 'mb-2025-1', '0115', 220.00, 1.00, '2026-12-29'),
(3, 'mb-2025-1', '0042', 20.00, 8.00, '2026-12-29'),
(4, 'mb-2025-1', '0049', 350.00, 1.00, '2026-12-29'),
(5, 'mb-2025-1', '0063', 60.00, 0.61, '2026-12-29'),
(6, 'mb-2025-2', '0127', 4000.00, 1.00, '2026-12-29'),
(7, 'mb-2025-3', '0004', 3300.00, 0.75, '2025-12-31'),
(8, 'mb-2025-3', '0022', 2000.00, 0.50, '2025-12-31'),
(9, 'mb-2025-3', '0042', 20.00, 3.00, '2025-12-31'),
(10, 'mb-2025-3', '0115', 220.00, 1.00, '2025-12-31'),
(11, 'mb-2025-3', '0063', 60.00, 2.00, '2025-12-31'),
(12, 'mb-2026-1', '0012', 3500.00, 0.75, NULL),
(13, 'mb-2026-1', '0102', 550.00, 2.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_bill`
--

CREATE TABLE `purchase_order_bill` (
  `id` int(255) NOT NULL,
  `bill_no` varchar(255) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `item_id` varchar(255) NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `supplier_id` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(255) NOT NULL,
  `add_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_order_bill`
--

INSERT INTO `purchase_order_bill` (`id`, `bill_no`, `order_id`, `item_id`, `qty`, `price`, `supplier_id`, `date`, `time`, `add_by`) VALUES
(1, '2026-1-1', '2026-1', '0003', 5.00, 6500.00, '0001', '2026-01-01', '04:46 PM', '1'),
(2, '2026-1-2', '2026-1', '0003', 5.00, 500.00, '0001', '2026-01-01', '04:47 PM', '1'),
(3, '2026-1-3', '2026-1', '0001', 5.00, 8750.00, '0003', '2026-01-01', '04:48 PM', '1'),
(4, '2026-1-4', '2026-1', '0001', 3.00, 5550.00, '0003', '2026-01-01', '04:49 PM', '1'),
(5, '2026-1-5', '2026-1', '0001', 2.00, 3400.00, '0003', '2026-01-01', '04:49 PM', '1'),
(6, '2026-1-6', '2026-1', '0002', 5.00, 5710.00, '0002', '2026-01-01', '04:50 PM', '1'),
(7, '2026-1-7', '2026-1', '0002', 5.00, 6000.00, '0002', '2026-01-01', '04:50 PM', '1'),
(8, '2026-1-8', '2026-1', '0002', 3.00, 3000.00, '0002', '2026-01-01', '04:52 PM', '1'),
(9, '2026-1-9', '2026-1', '0003', 2.00, 1000.00, '0001', '2026-01-02', '12:10 AM', '1'),
(10, '2026-1-10', '2026-1', '0003', 10.00, 500.00, '0001', '2026-01-02', '12:12 AM', '1');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_details`
--

CREATE TABLE `purchase_order_details` (
  `id` int(255) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `item_id` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `clear_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_order_details`
--

INSERT INTO `purchase_order_details` (`id`, `order_id`, `item_id`, `price`, `qty`, `clear_date`) VALUES
(1, '2026-1', '0003', 600.00, 12.00, NULL),
(2, '2026-1', '0001', 1700.00, 10.00, NULL),
(3, '2026-1', '0002', 1142.86, 10.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `store_item_table`
--

CREATE TABLE `store_item_table` (
  `id` int(255) NOT NULL,
  `item_id` varchar(255) NOT NULL,
  `cat_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sale_price` decimal(10,2) NOT NULL,
  `unit` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store_item_table`
--

INSERT INTO `store_item_table` (`id`, `item_id`, `cat_id`, `name`, `sale_price`, `unit`) VALUES
(1, '0001', '001', 'Reg', 1700.00, 'kg'),
(2, '0002', '002', 'Reg', 1000.00, 'kg'),
(3, '0003', '003', 'Reg', 50.00, 'kg'),
(4, '0004', '003', 'Desi', 1200.00, 'kg'),
(5, '0005', '003', 'Bbq - thigh', 650.00, 'kg'),
(6, '0006', '003', 'Bbq - boneless', 650.00, 'kg'),
(7, '0007', '003', 'Bbq - tikka', 500.00, 'kg'),
(8, '0008', '004', 'Reg', 100.00, 'kg'),
(9, '0009', '005', 'Reg', 500.00, 'kg'),
(10, '0010', '006', 'Reg', 500.00, 'kg'),
(11, '0011', '007', 'Reg', 100.00, 'kg'),
(12, '0012', '008', 'Reg', 200.00, 'kg'),
(13, '0013', '009', 'Reg', 200.00, 'kg'),
(14, '0014', '010', 'Reg', 100.00, 'kg'),
(15, '0015', '011', 'Reg', 100.00, 'kg'),
(16, '0016', '012', 'Reg', 100.00, 'kg'),
(17, '0017', '013', 'Reg', 100.00, 'kg'),
(18, '0018', '014', 'Reg', 100.00, 'kg'),
(19, '0019', '015', 'Reg', 100.00, 'kg'),
(20, '0020', '016', 'Reg', 100.00, 'kg'),
(21, '0021', '017', 'Reg', 150.00, 'kg'),
(22, '0022', '018', 'Reg', 200.00, 'kg'),
(23, '0023', '019', 'Reg', 40.00, 'kg'),
(24, '0024', '020', 'Reg', 700.00, 'kg'),
(25, '0025', '021', 'Reg', 1500.00, 'kg'),
(26, '0026', '022', 'Reg', 3500.00, 'kg'),
(27, '0027', '026', 'Reg', 1500.00, 'kg'),
(28, '0028', '027', 'Reg', 2500.00, 'kg'),
(29, '0029', '028', 'Reg', 1000.00, 'kg'),
(30, '0030', '029', 'Reg', 1200.00, 'kg'),
(31, '0031', '030', 'Reg', 7000.00, 'kg'),
(32, '0032', '031', '5 ltr', 350.00, 'pc'),
(33, '0033', '032', 'Ltr', 300.00, 'pc'),
(34, '0034', '033', 'Ltr', 350.00, 'pc'),
(35, '0035', '034', 'Reg', 1000.00, 'kg'),
(36, '0036', '035', 'Reg', 1300.00, 'kg'),
(37, '0037', '036', 'Reg', 500.00, 'kg'),
(38, '0038', '037', 'Reg', 600.00, 'kg'),
(39, '0039', '038', 'Reg', 650.00, 'kg'),
(40, '0040', '040', 'Reg', 25.00, 'pc'),
(41, '0041', '041', 'Reg', 190.00, 'kg'),
(42, '0042', '042', 'Reg', 1700.00, 'kg'),
(43, '0043', '043', 'Reg', 600.00, 'kg'),
(44, '0044', '044', 'Reg', 1500.00, 'pc'),
(45, '0045', '045', 'Reg', 350.00, 'pc'),
(46, '0046', '046', 'Packet', 450.00, 'pkt'),
(47, '0047', '046', '4ltr', 550.00, 'pc'),
(48, '0048', '047', 'Reg', 110.00, 'kg'),
(49, '0049', '048', 'Reg', 120.00, 'kg'),
(50, '0050', '049', 'Reg', 125.00, 'kg'),
(51, '0051', '050', 'Reg', 500.00, 'kg'),
(52, '0052', '051', 'Reg', 550.00, 'kg'),
(53, '0053', '052', 'Reg', 1200.00, 'kg'),
(54, '0054', '053', 'Haleeb', 180.00, 'pc'),
(55, '0055', '053', 'Micl pack', 230.00, 'pc'),
(56, '0056', '054', 'Makhan', 3500.00, 'kg'),
(57, '0057', '055', 'Reg', 125.00, 'kg'),
(58, '0058', '056', 'Lpg', 14000.00, 'pc'),
(59, '0059', '057', 'Reg', 32.00, 'kg'),
(60, '0060', '058', 'Reg', 500.00, 'kg'),
(61, '0061', '059', 'Reg', 8050.00, 'pc'),
(62, '0062', '060', 'Butter paper', 300.00, 'kg'),
(63, '0063', '060', 'Radi', 350.00, 'kg'),
(64, '0064', '061', '1ltr', 25.00, 'pc'),
(65, '0065', '061', '1.5ltr', 35.00, 'pc'),
(66, '0066', '061', 'Lp11', 1400.00, 'pc'),
(67, '0067', '062', 'Burger bag', 7.00, 'pc'),
(68, '0068', '062', 'Shopping bag', 500.00, 'kg'),
(69, '0069', '063', 'Glass', 2.00, 'pc'),
(70, '0070', '063', 'Cup', 2.00, 'pc'),
(71, '0071', '063', 'Spoons', 2.00, 'pc'),
(72, '0072', '064', 'Bottle straw', 700.00, 'pkt'),
(73, '0073', '064', 'Juice straw', 70.00, 'pkt'),
(74, '0074', '065', 'Reg', 100.00, 'kg'),
(75, '0075', '023', 'Reg', 800.00, 'kg'),
(76, '0076', '024', 'Reg', 800.00, 'kg'),
(77, '0077', '025', 'Reg', 1000.00, 'kg'),
(78, '0078', '039', 'Reg', 950.00, 'pkt');

-- --------------------------------------------------------

--
-- Table structure for table `store_main_category`
--

CREATE TABLE `store_main_category` (
  `id` int(255) NOT NULL,
  `cat_id` varchar(255) NOT NULL,
  `level_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store_main_category`
--

INSERT INTO `store_main_category` (`id`, `cat_id`, `level_id`, `name`) VALUES
(1, '001', '01', 'Mutton'),
(2, '002', '01', 'Beaf'),
(3, '003', '01', 'Chicken'),
(4, '004', '02', 'Pyaaz'),
(5, '005', '02', 'Adrak'),
(6, '006', '02', 'Lasan'),
(7, '007', '02', 'Tamatar'),
(8, '008', '02', 'Sabaz mirchi'),
(9, '009', '02', 'Sabaz dhaniya'),
(10, '010', '02', 'Lemon'),
(11, '011', '02', 'Gajar'),
(12, '012', '02', 'Muli'),
(13, '013', '02', 'Kheera'),
(14, '014', '02', 'Chukandar'),
(15, '015', '02', 'Band gobi'),
(16, '016', '02', 'Shimla mirch'),
(17, '017', '03', 'Milk'),
(18, '018', '03', 'Dahi'),
(19, '019', '04', 'Namak'),
(20, '020', '04', 'Laal mirch'),
(21, '021', '04', 'Kali mirch'),
(22, '022', '04', 'Safaid mirch'),
(23, '023', '04', 'Dhara mirch'),
(24, '024', '04', 'Haldi'),
(25, '025', '04', 'Suka dhaniya'),
(26, '026', '04', 'Zeera'),
(27, '027', '04', 'Garam masala'),
(28, '028', '04', 'Ajwain'),
(29, '029', '04', 'Kachri powder'),
(30, '030', '04', 'Bbq masala'),
(31, '031', '04', 'Sirka'),
(32, '032', '04', 'Chilli sauce'),
(33, '033', '04', 'Soya sauce'),
(34, '034', '04', 'Coconut powder'),
(35, '035', '04', 'Salt'),
(36, '036', '04', 'Meetha soda'),
(37, '037', '04', 'Gulocose'),
(38, '038', '04', 'Ikka'),
(39, '039', '04', 'East'),
(40, '040', '04', 'Egg'),
(41, '041', '04', 'Chini'),
(42, '042', '04', 'Sukha doodh'),
(43, '043', '04', 'Achar'),
(44, '044', '04', 'Bbq sauce'),
(45, '045', '04', 'Mayo'),
(46, '046', '04', 'Ketch'),
(47, '047', '04', 'Atta'),
(48, '048', '04', 'Maidah'),
(49, '049', '04', 'Fane'),
(50, '050', '04', 'Ghee'),
(51, '051', '04', 'Oil'),
(52, '052', '03', 'Cheese'),
(53, '053', '03', 'Cream'),
(54, '054', '03', 'Makhan'),
(55, '055', '05', 'Coil'),
(56, '056', '05', 'Cylender'),
(57, '057', '05', 'Dacha'),
(58, '058', '04', 'Fish oil'),
(59, '059', '04', 'Fast food oil'),
(60, '060', '06', 'Paper'),
(61, '061', '06', 'Parcel box'),
(62, '062', '06', 'Bags'),
(63, '063', '06', 'Cutlery'),
(64, '064', '06', 'Straw'),
(65, '065', '02', 'Allu');

-- --------------------------------------------------------

--
-- Table structure for table `store_top_level`
--

CREATE TABLE `store_top_level` (
  `id` int(255) NOT NULL,
  `level_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store_top_level`
--

INSERT INTO `store_top_level` (`id`, `level_id`, `name`) VALUES
(1, '01', 'Meat'),
(2, '02', 'Sabzi'),
(3, '03', 'Milk/dahi'),
(4, '04', 'Masala'),
(5, '05', 'Fuel'),
(6, '06', 'Packing items');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(255) NOT NULL,
  `supplier_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `cell` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `supplier_id`, `name`, `cell`) VALUES
(1, '0001', 'Abbas - chicken', '0305-4334353'),
(3, '0002', 'Mustafa - beaf', '0308-4155975'),
(4, '0003', 'Ramzan - mutton', '0300-7569006'),
(5, '0004', 'Majid - floor', '0301-3662086'),
(6, '0005', 'Inam ilahi - lpg', '0300-2859421'),
(7, '0006', 'Zeeshan - coil', '0300-4273913'),
(8, '0007', 'Usman bashir', '000');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_ledger`
--

CREATE TABLE `supplier_ledger` (
  `id` int(255) NOT NULL,
  `emp_id` varchar(255) NOT NULL,
  `details` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `time` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `add_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier_ledger`
--

INSERT INTO `supplier_ledger` (`id`, `emp_id`, `details`, `amount`, `date`, `time`, `type`, `add_by`) VALUES
(1, '0001', 'Purchase Order#: 2026-1   Bill #2026-1-1', 6500.00, '2026-01-01', '04:46 PM', 'Debit', '1'),
(2, '0001', 'Purchase Order#: 2026-1   Bill #2026-1-2', 500.00, '2026-01-01', '04:47 PM', 'Debit', '1'),
(3, '0003', 'Purchase Order#: 2026-1   Bill #2026-1-3', 8750.00, '2026-01-01', '04:48 PM', 'Debit', '1'),
(4, '0003', 'Purchase Order#: 2026-1   Bill #2026-1-4', 5550.00, '2026-01-01', '04:49 PM', 'Debit', '1'),
(5, '0003', 'Purchase Order#: 2026-1   Bill #2026-1-5', 3400.00, '2026-01-01', '04:49 PM', 'Debit', '1'),
(6, '0002', 'Purchase Order#: 2026-1   Bill #2026-1-6', 5710.00, '2026-01-01', '04:50 PM', 'Debit', '1'),
(7, '0002', 'Purchase Order#: 2026-1   Bill #2026-1-7', 6000.00, '2026-01-01', '04:50 PM', 'Debit', '1'),
(8, '0002', 'Purchase Order#: 2026-1   Bill #2026-1-8', 3000.00, '2026-01-01', '04:52 PM', 'Debit', '1'),
(9, '0001', 'Purchase Order#: 2026-1   Bill #2026-1-9', 1000.00, '2026-01-02', '12:10 AM', 'Debit', '1'),
(10, '0001', 'Purchase Order#: 2026-1   Bill #2026-1-10', 500.00, '2026-01-02', '12:12 AM', 'Debit', '1');

-- --------------------------------------------------------

--
-- Table structure for table `top_level`
--

CREATE TABLE `top_level` (
  `id` int(255) NOT NULL,
  `level_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `top_level`
--

INSERT INTO `top_level` (`id`, `level_id`, `name`) VALUES
(1, '01', 'Pakistani food'),
(2, '02', 'Fast food'),
(3, '03', 'Fast food deals');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `user` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `activation` tinyint(1) NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user`, `password`, `activation`, `type`) VALUES
(1, 'hadi', '1', 1, '001'),
(2, 'mb', '12', 1, '001');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emp_id` (`emp_id`);

--
-- Indexes for table `employee_ledger`
--
ALTER TABLE `employee_ledger`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `erp_type`
--
ALTER TABLE `erp_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type_id` (`type_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_id` (`item_id`);

--
-- Indexes for table `inventory_batch`
--
ALTER TABLE `inventory_batch`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `batch_no` (`batch_no`);

--
-- Indexes for table `inventory_out`
--
ALTER TABLE `inventory_out`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_table`
--
ALTER TABLE `item_table`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_id` (`item_id`);

--
-- Indexes for table `main_category`
--
ALTER TABLE `main_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cat_id` (`cat_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `new_order`
--
ALTER TABLE `new_order`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`);

--
-- Indexes for table `new_purchase_order`
--
ALTER TABLE `new_purchase_order`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_id` (`order_id`);

--
-- Indexes for table `order_bill`
--
ALTER TABLE `order_bill`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bill_no` (`bill_no`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchase_order_bill`
--
ALTER TABLE `purchase_order_bill`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bill_no` (`bill_no`);

--
-- Indexes for table `purchase_order_details`
--
ALTER TABLE `purchase_order_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store_item_table`
--
ALTER TABLE `store_item_table`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_id` (`item_id`);

--
-- Indexes for table `store_main_category`
--
ALTER TABLE `store_main_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cat_id` (`cat_id`);

--
-- Indexes for table `store_top_level`
--
ALTER TABLE `store_top_level`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `level_id` (`level_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `supplier_ledger`
--
ALTER TABLE `supplier_ledger`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `top_level`
--
ALTER TABLE `top_level`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `level_id` (`level_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `employee_ledger`
--
ALTER TABLE `employee_ledger`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `erp_type`
--
ALTER TABLE `erp_type`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `inventory_batch`
--
ALTER TABLE `inventory_batch`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `inventory_out`
--
ALTER TABLE `inventory_out`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `item_table`
--
ALTER TABLE `item_table`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `main_category`
--
ALTER TABLE `main_category`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `new_order`
--
ALTER TABLE `new_order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `new_purchase_order`
--
ALTER TABLE `new_purchase_order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_bill`
--
ALTER TABLE `order_bill`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `purchase_order_bill`
--
ALTER TABLE `purchase_order_bill`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `purchase_order_details`
--
ALTER TABLE `purchase_order_details`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `store_item_table`
--
ALTER TABLE `store_item_table`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT for table `store_main_category`
--
ALTER TABLE `store_main_category`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `store_top_level`
--
ALTER TABLE `store_top_level`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `supplier_ledger`
--
ALTER TABLE `supplier_ledger`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `top_level`
--
ALTER TABLE `top_level`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
