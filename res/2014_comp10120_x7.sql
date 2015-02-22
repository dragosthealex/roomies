-- phpMyAdmin SQL Dump
-- version 4.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 22, 2015 at 03:02 PM
-- Server version: 5.6.23-log
-- PHP Version: 5.5.21-pl0-gentoo

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `2014_comp10120_x7`
--
CREATE DATABASE IF NOT EXISTS `2014_comp10120_x7` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `2014_comp10120_x7`;

-- --------------------------------------------------------

--
-- Table structure for table `comp101lab8users`
--

DROP TABLE IF EXISTS `comp101lab8users`;
CREATE TABLE IF NOT EXISTS `comp101lab8users` (
  `id` int(11) NOT NULL,
  `name` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `comp101lab8users`
--

INSERT INTO `comp101lab8users` (`id`, `name`, `email`) VALUES
(1, 'Daniel Hodgson', 'daniel.hodgson-2@student.manchester.ac.uk'),
(2, 'Liam Higgins', 'liam.higgins@student.manchester.ac.uk'),
(3, 'Dragos Radu', 'dragos.radu@student.manchester.ac.uk'),
(4, 'Elnur Mammadli', 'elnur.mammadli@student.manchester.ac.uk'),
(5, 'Mitali Palsikar', 'mitali.palsikar@student.manchester.ac.uk'),
(6, 'Lilian Gorea', 'lilian.gorea@student.manchester.ac.uk'),
(8, 'Alex', 'asdd@yahoo.com');

-- --------------------------------------------------------

--
-- Table structure for table `raccomodations`
--

DROP TABLE IF EXISTS `raccomodations`;
CREATE TABLE IF NOT EXISTS `raccomodations` (
  `accom_id` int(11) NOT NULL,
  `accom_name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `accom_pic_id` int(11) NOT NULL,
  `accom_avg_review` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ranswers`
--

DROP TABLE IF EXISTS `ranswers`;
CREATE TABLE IF NOT EXISTS `ranswers` (
  `answer_id` int(11) NOT NULL,
  `answer_text` varchar(500) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ranswers`
--

INSERT INTO `ranswers` (`answer_id`, `answer_text`) VALUES
(1, 'Yes'),
(2, 'Sometimes'),
(3, 'No'),
(4, 'Never'),
(5, 'Few times a month'),
(6, 'Few times a week'),
(7, 'Few times a day'),
(8, 'Always'),
(9, 'I don''t care');

-- --------------------------------------------------------

--
-- Table structure for table `rconexions`
--

DROP TABLE IF EXISTS `rconexions`;
CREATE TABLE IF NOT EXISTS `rconexions` (
  `conexion_id` int(11) NOT NULL,
  `conexion_user_id1` int(11) NOT NULL,
  `conexion_user_id2` int(11) NOT NULL,
  `conexion_status` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `rconexions`
--

INSERT INTO `rconexions` (`conexion_id`, `conexion_user_id1`, `conexion_user_id2`, `conexion_status`) VALUES
(28, 12, 26, 2),
(29, 12, 9, 1),
(31, 21, 26, 1),
(32, 21, 23, 1),
(34, 21, 12, 2),
(35, 21, 22, 2);

-- --------------------------------------------------------

--
-- Table structure for table `rdetails`
--

DROP TABLE IF EXISTS `rdetails`;
CREATE TABLE IF NOT EXISTS `rdetails` (
  `profile_filter_id` int(11) NOT NULL COMMENT 'The profile''s ID',
  `first_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `birthday` date NOT NULL,
  `country` tinyint(3) unsigned NOT NULL,
  `language` tinyint(3) unsigned NOT NULL,
  `gender` tinyint(3) unsigned NOT NULL,
  `uni_city` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `rdetails`
--

INSERT INTO `rdetails` (`profile_filter_id`, `first_name`, `last_name`, `completed`, `birthday`, `country`, `language`, `gender`, `uni_city`) VALUES
(12, 'Daniel', 'Hodgson', 0, '1996-08-11', 221, 1, 1, 1),
(21, 'Alex', 'Radu', 0, '0000-00-00', 174, 1, 1, 1),
(22, 'M', 'P', 0, '1995-09-05', 100, 1, 2, 1),
(23, 'Liam', 'Higgins', 0, '1996-06-28', 221, 1, 1, 1),
(26, 'Alex2', 'Radu2', 0, '1995-04-02', 174, 1, 1, 1),
(27, 'His Kiwiness', 'Mr. Kiwi', 0, '0000-00-00', 12, 14, 3, 1),
(28, 'test', 'init perc', 0, '0000-00-00', 5, 4, 3, 1),
(49, 'Dragos', 'Radu', 0, '1995-04-01', 174, 1, 1, 1),
(50, 'adsds', 'dasdassad', 0, '2006-03-03', 3, 3, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rfiltersmap`
--

DROP TABLE IF EXISTS `rfiltersmap`;
CREATE TABLE IF NOT EXISTS `rfiltersmap` (
  `filter_value` int(11) NOT NULL,
  `map_country` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `map_language` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `map_gender` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `map_uni_city` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=237 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `rfiltersmap`
--

INSERT INTO `rfiltersmap` (`filter_value`, `map_country`, `map_language`, `map_gender`, `map_uni_city`) VALUES
(1, 'Afghanistan', 'English', 'man', 'Manchester'),
(2, 'Albania', 'Afar', 'woman', ''),
(3, 'Algeria', 'Abkhazian', 'trans', ''),
(4, 'American Samoa', 'Afrikaans', '', ''),
(5, 'Andorra', 'Amharic', '', ''),
(6, 'Angola', 'Arabic', '', ''),
(7, 'Anguilla', 'Assamese', '', ''),
(8, 'Antarctica', 'Aymara', '', ''),
(9, 'Antigua and Barbuda', 'Azerbaijani', '', ''),
(10, 'Argentina', 'Bashkir', '', ''),
(11, 'Armenia', 'Byelorussian', '', ''),
(12, 'Aruba', 'Bulgarian', '', ''),
(13, 'Australia', 'Bihari', '', ''),
(14, 'Austria', 'Bislama', '', ''),
(15, 'Azerbaijan', 'Bengali/Bangla', '', ''),
(16, 'Bahamas', 'Tibetan', '', ''),
(17, 'Bahrain', 'Breton', '', ''),
(18, 'Bangladesh', 'Catalan', '', ''),
(19, 'Barbados', 'Corsican', '', ''),
(20, 'Belarus', 'Czech', '', ''),
(21, 'Belgium', 'Welsh', '', ''),
(22, 'Belize', 'Danish', '', ''),
(23, 'Benin', 'German', '', ''),
(24, 'Bermuda', 'Bhutani', '', ''),
(25, 'Bhutan', 'Greek', '', ''),
(26, 'Bolivia', 'Esperanto', '', ''),
(27, 'Bosnia and Herzegowi', 'Spanish', '', ''),
(28, 'Botswana', 'Estonian', '', ''),
(29, 'Bouvet Island', 'Basque', '', ''),
(30, 'Brazil', 'Persian', '', ''),
(31, 'British Indian Ocean', 'Finnish', '', ''),
(32, 'Brunei Darussalam', 'Fiji', '', ''),
(33, 'Bulgaria', 'Faeroese', '', ''),
(34, 'Burkina Faso', 'French', '', ''),
(35, 'Burundi', 'Frisian', '', ''),
(36, 'Cambodia', 'Irish', '', ''),
(37, 'Cameroon', 'Scots/Gaelic', '', ''),
(38, 'Canada', 'Galician', '', ''),
(39, 'Cape Verde', 'Guarani', '', ''),
(40, 'Cayman Islands', 'Gujarati', '', ''),
(41, 'Central African Repu', 'Hausa', '', ''),
(42, 'Chad', 'Hindi', '', ''),
(43, 'Chile', 'Croatian', '', ''),
(44, 'China', 'Hungarian', '', ''),
(45, 'Christmas Island', 'Armenian', '', ''),
(46, 'Cocos (Keeling) Isla', 'Interlingua', '', ''),
(47, 'Colombia', 'Interlingue', '', ''),
(48, 'Comoros', 'Inupiak', '', ''),
(49, 'Congo', 'Indonesian', '', ''),
(50, 'Congo, the Democrati', 'Icelandic', '', ''),
(51, 'Cook Islands', 'Italian', '', ''),
(52, 'Costa Rica', 'Hebrew', '', ''),
(53, 'Croatia (Hrvatska)', 'Yiddish', '', ''),
(54, 'Cuba', 'Javanese', '', ''),
(55, 'Cyprus', 'Georgian', '', ''),
(56, 'Czech Republic', 'Kazakh', '', ''),
(57, 'Denmark', 'Greenlandic', '', ''),
(58, 'Djibouti', 'Cambodian', '', ''),
(59, 'Dominica', 'Kannada', '', ''),
(60, 'Dominican Republic', 'Korean', '', ''),
(61, 'East Timor', 'Kashmiri', '', ''),
(62, 'Ecuador', 'Kurdish', '', ''),
(63, 'Egypt', 'Kirghiz', '', ''),
(64, 'El Salvador', 'Latin', '', ''),
(65, 'Equatorial Guinea', 'Lingala', '', ''),
(66, 'Eritrea', 'Laothian', '', ''),
(67, 'Estonia', 'Lithuanian', '', ''),
(68, 'Ethiopia', 'Latvian/Lettish', '', ''),
(69, 'Falkland Islands (Ma', 'Malagasy', '', ''),
(70, 'Faroe Islands', 'Maori', '', ''),
(71, 'Fiji', 'Macedonian', '', ''),
(72, 'Finland', 'Malayalam', '', ''),
(73, 'France', 'Mongolian', '', ''),
(74, 'France Metropolitan', 'Moldavian', '', ''),
(75, 'French Guiana', 'Marathi', '', ''),
(76, 'French Polynesia', 'Malay', '', ''),
(77, 'French Southern Terr', 'Maltese', '', ''),
(78, 'Gabon', 'Burmese', '', ''),
(79, 'Gambia', 'Nauru', '', ''),
(80, 'Georgia', 'Nepali', '', ''),
(81, 'Germany', 'Dutch', '', ''),
(82, 'Ghana', 'Norwegian', '', ''),
(83, 'Gibraltar', 'Occitan', '', ''),
(84, 'Greece', '(Afan)/Oromoor/Oriya', '', ''),
(85, 'Greenland', 'Punjabi', '', ''),
(86, 'Grenada', 'Polish', '', ''),
(87, 'Guadeloupe', 'Pashto/Pushto', '', ''),
(88, 'Guam', 'Portuguese', '', ''),
(89, 'Guatemala', 'Quechua', '', ''),
(90, 'Guinea', 'Rhaeto-Romance', '', ''),
(91, 'Guinea-Bissau', 'Kirundi', '', ''),
(92, 'Guyana', 'Romanian', '', ''),
(93, 'Haiti', 'Russian', '', ''),
(94, 'Heard and Mc Donald ', 'Kinyarwanda', '', ''),
(95, 'Holy See (Vatican Ci', 'Sanskrit', '', ''),
(96, 'Honduras', 'Sindhi', '', ''),
(97, 'Hong Kong', 'Sangro', '', ''),
(98, 'Hungary', 'Serbo-Croatian', '', ''),
(99, 'Iceland', 'Singhalese', '', ''),
(100, 'India', 'Slovak', '', ''),
(101, 'Indonesia', 'Slovenian', '', ''),
(102, 'Iran (Islamic Republ', 'Samoan', '', ''),
(103, 'Iraq', 'Shona', '', ''),
(104, 'Ireland', 'Somali', '', ''),
(105, 'Israel', 'Albanian', '', ''),
(106, 'Italy', 'Serbian', '', ''),
(107, 'Jamaica', 'Siswati', '', ''),
(108, 'Japan', 'Sesotho', '', ''),
(109, 'Jordan', 'Sundanese', '', ''),
(110, 'Kazakhstan', 'Swedish', '', ''),
(111, 'Kenya', 'Swahili', '', ''),
(112, 'Kiribati', 'Tamil', '', ''),
(113, 'Korea, Republic of', 'Tajik', '', ''),
(114, 'Kuwait', 'Thai', '', ''),
(115, 'Kyrgyzstan', 'Tigrinya', '', ''),
(116, 'Latvia', 'Tagalog', '', ''),
(117, 'Lebanon', 'Setswana', '', ''),
(118, 'Lesotho', 'Tonga', '', ''),
(119, 'Liberia', 'Turkish', '', ''),
(120, 'Libyan Arab Jamahiri', 'Tsonga', '', ''),
(121, 'Liechtenstein', 'Tatar', '', ''),
(122, 'Lithuania', 'Twi', '', ''),
(123, 'Luxembourg', 'Ukrainian', '', ''),
(124, 'Macau', 'Urdu', '', ''),
(125, 'Macedonia, The Forme', 'Uzbek', '', ''),
(126, 'Madagascar', 'Vietnamese', '', ''),
(127, 'Malawi', 'Volapuk', '', ''),
(128, 'Malaysia', 'Wolof', '', ''),
(129, 'Maldives', 'Xhosa', '', ''),
(130, 'Mali', 'Yoruba', '', ''),
(131, 'Malta', 'Chinese', '', ''),
(132, 'Marshall Islands', 'Zulu', '', ''),
(133, 'Martinique', '', '', ''),
(134, 'Mauritania', '', '', ''),
(135, 'Mauritius', '', '', ''),
(136, 'Mayotte', '', '', ''),
(137, 'Mexico', '', '', ''),
(138, 'Micronesia, Federate', '', '', ''),
(139, 'Moldova, Republic of', '', '', ''),
(140, 'Monaco', '', '', ''),
(141, 'Mongolia', '', '', ''),
(142, 'Montserrat', '', '', ''),
(143, 'Morocco', '', '', ''),
(144, 'Mozambique', '', '', ''),
(145, 'Myanmar', '', '', ''),
(146, 'Namibia', '', '', ''),
(147, 'Nauru', '', '', ''),
(148, 'Nepal', '', '', ''),
(149, 'Netherlands', '', '', ''),
(150, 'Netherlands Antilles', '', '', ''),
(151, 'New Caledonia', '', '', ''),
(152, 'New Zealand', '', '', ''),
(153, 'Nicaragua', '', '', ''),
(154, 'Niger', '', '', ''),
(155, 'Nigeria', '', '', ''),
(156, 'Niue', '', '', ''),
(157, 'Norfolk Island', '', '', ''),
(158, 'Northern Mariana Isl', '', '', ''),
(159, 'Norway', '', '', ''),
(160, 'Oman', '', '', ''),
(161, 'Pakistan', '', '', ''),
(162, 'Palau', '', '', ''),
(163, 'Panama', '', '', ''),
(164, 'Papua New Guinea', '', '', ''),
(165, 'Paraguay', '', '', ''),
(166, 'Peru', '', '', ''),
(167, 'Philippines', '', '', ''),
(168, 'Pitcairn', '', '', ''),
(169, 'Poland', '', '', ''),
(170, 'Portugal', '', '', ''),
(171, 'Puerto Rico', '', '', ''),
(172, 'Qatar', '', '', ''),
(173, 'Reunion', '', '', ''),
(174, 'Romania', '', '', ''),
(175, 'Russian Federation', '', '', ''),
(176, 'Rwanda', '', '', ''),
(177, 'Saint Kitts and Nevi', '', '', ''),
(178, 'Saint Lucia', '', '', ''),
(179, 'Saint Vincent and th', '', '', ''),
(180, 'Samoa', '', '', ''),
(181, 'San Marino', '', '', ''),
(182, 'Sao Tome and Princip', '', '', ''),
(183, 'Saudi Arabia', '', '', ''),
(184, 'Senegal', '', '', ''),
(185, 'Seychelles', '', '', ''),
(186, 'Sierra Leone', '', '', ''),
(187, 'Singapore', '', '', ''),
(188, 'Slovakia (Slovak Rep', '', '', ''),
(189, 'Slovenia', '', '', ''),
(190, 'Solomon Islands', '', '', ''),
(191, 'Somalia', '', '', ''),
(192, 'South Africa', '', '', ''),
(193, 'South Georgia and th', '', '', ''),
(194, 'Spain', '', '', ''),
(195, 'Sri Lanka', '', '', ''),
(196, 'St. Helena', '', '', ''),
(197, 'St. Pierre and Mique', '', '', ''),
(198, 'Sudan', '', '', ''),
(199, 'Suriname', '', '', ''),
(200, 'Svalbard and Jan May', '', '', ''),
(201, 'Swaziland', '', '', ''),
(202, 'Sweden', '', '', ''),
(203, 'Switzerland', '', '', ''),
(204, 'Syrian Arab Republic', '', '', ''),
(205, 'Taiwan, Province of ', '', '', ''),
(206, 'Tajikistan', '', '', ''),
(207, 'Tanzania, United Rep', '', '', ''),
(208, 'Thailand', '', '', ''),
(209, 'Togo', '', '', ''),
(210, 'Tokelau', '', '', ''),
(211, 'Tonga', '', '', ''),
(212, 'Trinidad and Tobago', '', '', ''),
(213, 'Tunisia', '', '', ''),
(214, 'Turkey', '', '', ''),
(215, 'Turkmenistan', '', '', ''),
(216, 'Turks and Caicos Isl', '', '', ''),
(217, 'Tuvalu', '', '', ''),
(218, 'Uganda', '', '', ''),
(219, 'Ukraine', '', '', ''),
(220, 'United Arab Emirates', '', '', ''),
(221, 'United Kingdom', '', '', ''),
(222, 'United States', '', '', ''),
(223, 'United States Minor ', '', '', ''),
(224, 'Uruguay', '', '', ''),
(225, 'Uzbekistan', '', '', ''),
(226, 'Vanuatu', '', '', ''),
(227, 'Venezuela', '', '', ''),
(228, 'Vietnam', '', '', ''),
(229, 'Virgin Islands (Brit', '', '', ''),
(230, 'Virgin Islands (U.S.', '', '', ''),
(231, 'Wallis and Futuna Is', '', '', ''),
(232, 'Western Sahara', '', '', ''),
(233, 'Yemen', '', '', ''),
(234, 'Yugoslavia', '', '', ''),
(235, 'Zambia', '', '', ''),
(236, 'Zimbabwe', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `rlog`
--

DROP TABLE IF EXISTS `rlog`;
CREATE TABLE IF NOT EXISTS `rlog` (
  `log_id` int(11) NOT NULL,
  `log_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='logs when users get the pass wrong. protects against bruteforce attacks';

--
-- Dumping data for table `rlog`
--

INSERT INTO `rlog` (`log_id`, `log_email`, `log_time`) VALUES
(9, 'dragos.radu@student.manchester.ac.uk', '2015-01-18 01:35:30'),
(10, 'dragos.radu@student.manchester.ac.uk', '2015-01-18 02:08:46'),
(11, 'dragos.radu@student.manchester.ac.uk', '2015-01-18 02:08:57'),
(12, 'dragos.radu@student.manchester.ac.uk', '2015-01-18 02:09:11'),
(13, 'guest@cs.man.ac.uk', '2015-01-26 14:04:32'),
(14, 'guest@cs.man.ac.uk', '2015-01-26 14:04:37'),
(15, 'guest@cs.man.ac.uk', '2015-01-26 14:05:11'),
(16, 'guest@cs.man.ac.uk', '2015-01-26 14:05:22'),
(17, 'd_hod@hotmail.com', '2015-01-26 14:07:30'),
(18, 'guest@cs.man.ac.uk', '2015-01-26 14:13:53');

-- --------------------------------------------------------

--
-- Table structure for table `rmessages`
--

DROP TABLE IF EXISTS `rmessages`;
CREATE TABLE IF NOT EXISTS `rmessages` (
  `message_id` int(10) unsigned NOT NULL,
  `message_user_id1` int(10) unsigned NOT NULL,
  `message_user_id2` int(10) unsigned NOT NULL,
  `message_text` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `message_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `messages_read` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=176 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `rmessages`
--

INSERT INTO `rmessages` (`message_id`, `message_user_id1`, `message_user_id2`, `message_text`, `message_timestamp`, `messages_read`) VALUES
(4, 27, 21, 'wassup?', '2015-02-13 18:22:20', 1),
(5, 21, 27, 'fine, u?', '2015-02-13 21:28:16', 1),
(6, 27, 21, 'nuthing suck balls', '2015-02-13 18:22:24', 1),
(7, 27, 21, 'fuck you too!\n<br>\nshitfuck', '2015-02-13 21:28:16', 1),
(8, 12, 21, 'how are you?', '2015-02-13 18:22:41', 1),
(9, 21, 12, 'Fine, dear Sir. How are you?', '2015-02-13 18:22:35', 1),
(11, 21, 12, 'do you like mayonaise?', '2015-02-13 19:27:39', 1),
(12, 21, 12, 'heyyy, answer!!!', '2015-02-13 19:27:39', 1),
(13, 21, 12, '''''''''''''''''''''''', '2015-02-13 19:27:39', 1),
(14, 21, 12, '&lt;div&gt;', '2015-02-13 19:27:39', 1),
(15, 21, 12, '''', '2015-02-13 19:27:39', 1),
(16, 12, 21, 'shit?', '2015-02-13 19:27:39', 1),
(17, 12, 21, '&lt;div&gt;Test&lt;/div&gt;', '2015-02-13 19:29:53', 1),
(18, 12, 21, '&lt;div&gt;Test&lt;/div&gt;&lt;br&gt;&lt;p&gt;No&lt;/p&gt;\nYes.', '2015-02-13 19:30:18', 1),
(19, 21, 27, 'asdasd', '2015-02-13 21:28:16', 1),
(20, 21, 12, 'shit biatch', '2015-02-13 21:47:01', 1),
(26, 12, 21, 'blob', '2015-02-13 22:06:38', 1),
(34, 12, 21, 'LOL', '2015-02-13 22:42:04', 1),
(35, 21, 12, 'shit', '2015-02-13 22:45:33', 1),
(36, 12, 21, 'bleh', '2015-02-13 22:45:51', 1),
(37, 21, 12, 'shit', '2015-02-13 22:48:02', 1),
(38, 21, 12, 'shit2', '2015-02-13 22:48:02', 1),
(39, 21, 12, 'asdasd', '2015-02-13 22:49:20', 1),
(40, 21, 12, 'asdasd2', '2015-02-13 22:49:23', 1),
(41, 12, 21, 'test', '2015-02-13 22:51:18', 1),
(42, 21, 12, 'shit', '2015-02-13 22:51:31', 1),
(43, 12, 21, 'test', '2015-02-13 22:54:00', 1),
(44, 21, 12, 'fuck you', '2015-02-13 23:05:29', 1),
(45, 21, 12, 'heyyy :3\n', '2015-02-13 23:07:02', 1),
(46, 12, 21, 'Wahey!', '2015-02-13 23:07:13', 1),
(47, 12, 21, 'Wahey!hhghgfh', '2015-02-13 23:07:51', 1),
(48, 21, 12, 'stuff', '2015-02-14 01:23:05', 1),
(49, 21, 12, 'okay, aparently single quotation mark doesnt work\n', '2015-02-14 01:23:33', 1),
(50, 21, 12, 'also, textarea is too large for phone. i think its bc i set it to 60 cols', '2015-02-14 01:24:32', 1),
(51, 12, 21, 'I think I remember that single quotation mark only displayed 1 mark when 2 were used... something for you to work on while I work in style.css :P\nJust installing apache and shit on an Ubuntu VM', '2015-02-14 01:27:55', 1),
(52, 21, 12, 'okay. why not work on laptop? :o', '2015-02-14 01:29:12', 1),
(53, 21, 12, 'I also need to fix the stuff that shows how many unread messages are', '2015-02-14 01:30:45', 1),
(54, 21, 12, 'and the thing thay clears the textarea when sent', '2015-02-14 01:31:17', 1),
(55, 12, 21, 'This screen is clean, and this PC is faster, even on VM xD', '2015-02-14 01:40:26', 1),
(56, 12, 21, 'Will work on the styling, and having a message appear in the title upon a new message', '2015-02-14 01:40:51', 1),
(57, 12, 21, 'Also considering having the recursive function only retrieve the unread messages... then spamming every 1 or 2 seconds would not be that bad (ignore le weird sentences... avoiding apostrophes for now).', '2015-02-14 01:42:14', 1),
(58, 12, 21, 'Just figuring out what to install, to get PDO and shit all working fast', '2015-02-14 01:42:49', 1),
(59, 12, 21, 'Got it configured and shit. Shall begin styling.', '2015-02-14 02:05:30', 1),
(60, 21, 12, 'okay. ill fix the things now', '2015-02-14 02:05:58', 1),
(61, 12, 21, 'Can you add detection of empty strings, please? I will remove the empty string messages from the db for now... because the styling will look glitchy unless I specifically cater to the idea of allowing empty strings', '2015-02-14 02:42:45', 1),
(64, 12, 21, 'test?', '2015-02-14 02:44:25', 1),
(65, 12, 21, 'something broke ._.', '2015-02-14 02:44:32', 1),
(66, 12, 21, 'Strange. Maybe it was being slow?', '2015-02-14 02:46:39', 1),
(68, 21, 12, 'I think your message got sent two times. Ill add detection', '2015-02-14 02:49:44', 1),
(69, 12, 21, 'Yeah, some weird shit happened. Not sure what...', '2015-02-14 02:51:00', 1),
(70, 12, 21, 'Same weird shit happening again... hmmm', '2015-02-14 02:53:42', 1),
(71, 12, 21, 'Can you make it so that, if $_GET[unread] is set, it only retrieves unread messages?', '2015-02-14 02:59:10', 1),
(72, 12, 21, 'Also, seems to go buggy whenever I use single quotes, I think', '2015-02-14 02:59:32', 1),
(73, 12, 21, 'Some basic styling pushed. Nothing on the textarea yet, but it should almost feel instant-chat-like.', '2015-02-14 03:24:42', 1),
(74, 12, 21, 'Well, hopefully.', '2015-02-14 03:27:59', 1),
(75, 12, 21, 'Im currently trying to get the auto-scrolling fixed, so I might send some random messages. :P', '2015-02-14 03:38:58', 1),
(91, 21, 12, 'shit fuck 1', '2015-02-14 14:29:29', 1),
(92, 21, 12, 'shit fuck 2', '2015-02-14 14:29:34', 1),
(93, 21, 12, 'blob', '2015-02-14 14:33:52', 1),
(94, 21, 12, 'shti', '2015-02-14 14:34:56', 1),
(95, 21, 12, 'works', '2015-02-14 14:36:01', 1),
(96, 21, 12, 'works?', '2015-02-14 15:07:34', 1),
(97, 21, 12, 'blub', '2015-02-14 15:08:04', 1),
(98, 21, 12, 'blub', '2015-02-14 15:08:11', 1),
(99, 21, 12, 'asdasd', '2015-02-14 15:10:51', 1),
(100, 21, 12, 'asdasd', '2015-02-14 15:12:15', 1),
(101, 21, 12, 'asdasd', '2015-02-14 15:12:16', 1),
(102, 21, 12, 'asdasd', '2015-02-14 15:12:16', 1),
(103, 21, 12, 'asdasd', '2015-02-14 15:12:16', 1),
(104, 21, 12, 'asdasd', '2015-02-14 15:12:17', 1),
(105, 21, 12, 'asdasd', '2015-02-14 15:12:17', 1),
(106, 21, 12, 'asdasd', '2015-02-14 15:12:17', 1),
(107, 21, 12, 'Testing shit...', '2015-02-14 15:23:08', 1),
(108, 21, 12, 'test2', '2015-02-14 15:23:31', 1),
(109, 21, 12, 'test again', '2015-02-14 15:30:55', 1),
(110, 21, 12, 'hooray, works', '2015-02-14 15:31:01', 1),
(111, 21, 12, 'test?', '2015-02-14 15:43:53', 1),
(112, 21, 12, '''', '2015-02-14 16:06:05', 1),
(113, 21, 12, '''''', '2015-02-14 16:06:12', 1),
(114, 21, 12, '&quot;', '2015-02-14 16:06:18', 1),
(115, 21, 12, '''', '2015-02-14 16:12:58', 1),
(116, 21, 12, '&amp; ', '2015-02-14 16:13:23', 1),
(117, 21, 12, '&lt;div&gt; ', '2015-02-14 16:13:29', 1),
(118, 21, 12, '? : &quot;', '2015-02-14 16:13:34', 1),
(119, 21, 12, '!@#$%^&amp;*()', '2015-02-14 16:13:41', 1),
(120, 21, 12, '''', '2015-02-14 16:13:44', 1),
(121, 21, 12, 'it works now, doesn''t it?', '2015-02-14 16:13:53', 1),
(122, 21, 12, '''; DROP rmessages;', '2015-02-14 16:15:43', 1),
(123, 21, 12, 'it looks awesome on mobile btw', '2015-02-14 17:15:08', 1),
(124, 12, 12, 'Here''s a test:\n&lt;div class=&quot;test&quot;&gt;&lt;br&gt;&lt;/div&gt;\n&lt;div class=''test''&gt;&lt;/div&gt;', '2015-02-14 17:15:09', 1),
(125, 21, 12, 'testing focus', '2015-02-14 17:15:32', 1),
(126, 12, 12, 'I haven''t even started on the mobile version yet ._.', '2015-02-14 17:16:03', 1),
(127, 21, 12, 'test focus again', '2015-02-14 17:16:16', 1),
(128, 12, 12, 'Gonna fix the chat replacing issue', '2015-02-14 17:17:45', 1),
(129, 21, 12, 'testing again', '2015-02-14 17:19:23', 1),
(130, 21, 12, 'yeah.. the problem is that when I send, it should focus again on the message. instead it doesn''t do that\n', '2015-02-14 17:19:50', 1),
(131, 21, 12, 'commencing stress test', '2015-02-14 17:20:08', 1),
(132, 21, 12, 'a', '2015-02-14 17:20:08', 1),
(133, 21, 12, 'a', '2015-02-14 17:20:09', 1),
(134, 21, 12, 'a', '2015-02-14 17:20:12', 1),
(135, 21, 12, 'a', '2015-02-14 17:20:12', 1),
(136, 21, 12, 'a', '2015-02-14 17:20:12', 1),
(137, 21, 12, 'a', '2015-02-14 17:20:13', 1),
(138, 21, 12, 'a', '2015-02-14 17:20:14', 1),
(139, 21, 12, 'a', '2015-02-14 17:20:14', 1),
(140, 21, 12, 'okay, this works.', '2015-02-14 17:20:24', 1),
(141, 21, 12, 'looks nice btw', '2015-02-14 17:20:39', 1),
(142, 12, 12, 'I''m not seeing my messages when I refresh though o.o', '2015-02-14 17:27:20', 1),
(143, 12, 12, 'Well, only when I send a new message, all of the recent ones I sent get displayed', '2015-02-14 17:27:46', 1),
(144, 12, 12, 'Ah... I''m so confused right now.\nWe need the longpolling thing to only check for the unread messages sent by the other user, and the send button to only get the unread messages sent by the other user along with the single most recent message sent by the current user (i.e. th message they just sent)', '2015-02-14 17:38:47', 1),
(145, 12, 21, 'I''m thinking that we need 3 different types of output from message process php:\n1) The update that happens after the user sends a message (to return the last message sent by the current user).\n2) The longpolling: Only retrieve messages sent by the other person, that are unread.\n3) The scrolling up: Retrieve all messages, given an offset.', '2015-02-14 17:50:46', 1),
(146, 21, 12, 'message.process does not give any output', '2015-02-14 17:51:27', 1),
(147, 21, 12, 'the output is given by update_mesage', '2015-02-14 17:51:43', 1),
(148, 12, 21, 'I meant update_message process', '2015-02-14 17:51:52', 1),
(149, 21, 12, 'oh, the new messages will be appended by js?', '2015-02-14 17:52:28', 1),
(150, 12, 21, 'So I''ll have JS send a variable _GET[''type''] = ''new'' or ''old'' or ''sent''\n(New =&gt; get the new messages sent by other user, and mark them as read)\n(Old =&gt; get the old messages sent by either user, given an offset)\n(Sent =&gt; get the &quot;New&quot; messages AND the most recent message sent by the current user)', '2015-02-14 17:55:02', 1),
(151, 12, 21, 'Yeah, rather than replaced', '2015-02-14 17:55:31', 1),
(152, 12, 21, 'Then we can scrap the _GET[''unread''] stuff.', '2015-02-14 17:55:55', 1),
(153, 21, 12, 'yes', '2015-02-14 18:09:01', 1),
(154, 12, 21, 'AH!', '2015-02-14 18:33:56', 1),
(155, 12, 21, 'I got it. Maybe.', '2015-02-14 18:34:01', 1),
(156, 12, 21, 'Little change up in ideas (I know, annoying).', '2015-02-14 18:34:15', 1),
(157, 12, 21, 'Once we get the proper long-polling in place (not just the current consta-repeat thing), we can send the timestamp in _GET, and the server gets all messages from either user that were sent &gt;= the timestamp. (This is for type=new btw).', '2015-02-14 18:35:41', 1),
(158, 12, 21, 'What time do you think you''ll be up tomorrow?\nI''m thinking of coming in, because I don''t want to work on this and fuck shit up w/ merge errors, unless you got Skype?', '2015-02-14 18:44:22', 1),
(159, 12, 21, 'Oh, and remember when I mentioned that there''s OFFSET in SQL?\nhttp://www.petefreitag.com/item/451.cfm\ne.g. LIMIT 50 OFFSET $offset', '2015-02-14 19:05:17', 1),
(160, 12, 12, 'Test?', '2015-02-14 19:09:48', 1),
(161, 12, 12, 'Test', '2015-02-14 19:28:06', 1),
(162, 12, 12, 'newtest', '2015-02-15 03:18:57', 1),
(163, 12, 12, 'newtest2', '2015-02-15 03:20:34', 1),
(164, 12, 12, 'newtest3', '2015-02-15 03:36:38', 1),
(165, 12, 12, 'newtest4', '2015-02-15 03:37:55', 1),
(166, 12, 12, 'newtest5', '2015-02-15 03:40:47', 1),
(167, 12, 12, 'newtest6', '2015-02-15 03:50:56', 1),
(168, 21, 12, 'biatch', '2015-02-19 15:35:42', 1),
(169, 21, 12, 'see dis', '2015-02-19 15:35:47', 1),
(170, 21, 12, 'muthafucka', '2015-02-19 15:35:55', 1),
(171, 21, 12, 'hey, see this?', '2015-02-20 18:58:25', 1),
(172, 21, 12, 'see?', '2015-02-20 19:07:25', 1),
(173, 21, 12, 'asdasd', '2015-02-20 19:07:34', 1),
(174, 21, 12, 'asdasd', '2015-02-20 19:07:36', 1),
(175, 21, 27, 'asdasd', '2015-02-20 19:14:08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `rpercentages`
--

DROP TABLE IF EXISTS `rpercentages`;
CREATE TABLE IF NOT EXISTS `rpercentages` (
  `percentage_user_id1` int(11) NOT NULL,
  `percentage_user_id2` int(11) NOT NULL,
  `percentage` tinyint(4) NOT NULL DEFAULT '0',
  `id1_1` smallint(6) NOT NULL DEFAULT '0',
  `id1_10` smallint(6) NOT NULL DEFAULT '0',
  `id1_50` smallint(6) NOT NULL DEFAULT '0',
  `id2_1` smallint(6) NOT NULL DEFAULT '0',
  `id2_10` smallint(6) NOT NULL DEFAULT '0',
  `id2_50` smallint(6) NOT NULL DEFAULT '0',
  `id1_max` smallint(6) NOT NULL DEFAULT '0',
  `id2_max` smallint(6) NOT NULL DEFAULT '0',
  `percentage_city` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `rpercentages`
--

INSERT INTO `rpercentages` (`percentage_user_id1`, `percentage_user_id2`, `percentage`, `id1_1`, `id1_10`, `id1_50`, `id2_1`, `id2_10`, `id2_50`, `id1_max`, `id2_max`, `percentage_city`) VALUES
(12, 21, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(12, 22, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(12, 23, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(12, 26, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(12, 27, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(12, 28, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(12, 49, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(12, 50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(21, 22, 10, 0, 0, 1, 0, 0, 0, 50, 50, 1),
(21, 23, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(21, 26, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(21, 27, 10, 0, 0, 0, 0, 0, 1, 50, 50, 1),
(21, 28, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(21, 49, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(21, 50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(22, 23, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(22, 26, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(22, 27, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(22, 28, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(22, 49, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(22, 50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(23, 26, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(23, 27, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(23, 28, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(23, 49, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(23, 50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(26, 27, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(26, 28, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(26, 49, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(26, 50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(27, 28, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(27, 49, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(27, 50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(28, 49, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(28, 50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(49, 50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rquestionsmap`
--

DROP TABLE IF EXISTS `rquestionsmap`;
CREATE TABLE IF NOT EXISTS `rquestionsmap` (
  `question_id` int(11) NOT NULL,
  `question_text` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `question_answers` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `rquestionsmap`
--

INSERT INTO `rquestionsmap` (`question_id`, `question_text`, `question_answers`) VALUES
(1, 'Are you a loud person?', '1:2:3'),
(2, 'How often do you play musical instruments?', '8:7:6:5:4'),
(3, 'Would you live with someone of other religion than yours?', '1:3:9');

-- --------------------------------------------------------

--
-- Table structure for table `rreviews`
--

DROP TABLE IF EXISTS `rreviews`;
CREATE TABLE IF NOT EXISTS `rreviews` (
  `review_id` int(11) NOT NULL,
  `review_accom_id` int(11) NOT NULL,
  `review_text` text COLLATE utf8_unicode_ci,
  `review_rating` int(11) DEFAULT NULL,
  `review_user_id` int(11) DEFAULT NULL,
  `review_user_fname` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rsiteinfo`
--

DROP TABLE IF EXISTS `rsiteinfo`;
CREATE TABLE IF NOT EXISTS `rsiteinfo` (
  `info_id` int(11) NOT NULL,
  `info` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `rsiteinfo`
--

INSERT INTO `rsiteinfo` (`info_id`, `info`, `value`) VALUES
(1, 'no_questions', '2');

-- --------------------------------------------------------

--
-- Table structure for table `rtempusers`
--

DROP TABLE IF EXISTS `rtempusers`;
CREATE TABLE IF NOT EXISTS `rtempusers` (
  `temp_id` int(11) NOT NULL,
  `temp_username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `temp_email` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `temp_salt` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `temp_pass` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `conf` varchar(10) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rusers`
--

DROP TABLE IF EXISTS `rusers`;
CREATE TABLE IF NOT EXISTS `rusers` (
  `user_id` int(11) NOT NULL COMMENT 'The user id',
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The username, used for URL',
  `user_email` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The email of the user',
  `user_salt` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The salt, used for pass hashing',
  `user_pass` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The inserted pass',
  `user_rank` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user' COMMENT 'Can be user/mod/admin/?',
  `matches` longtext COLLATE utf8_unicode_ci NOT NULL,
  `has_updated` longtext COLLATE utf8_unicode_ci NOT NULL,
  `user_cookie` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `facebook_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `image_url` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '/media/img/default.gif'
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `rusers`
--

INSERT INTO `rusers` (`user_id`, `username`, `user_email`, `user_salt`, `user_pass`, `user_rank`, `matches`, `has_updated`, `user_cookie`, `facebook_id`, `image_url`) VALUES
(11, 'guest', 'guest@cs.man.ac.uk', '1012506342', 'bbbfb2a1528fb0fad3f7309b5c3b1df750ff5bc3e9c89d0c66d36a48715a902c', 'user', '', '', '', '', '/media/img/default.gif'),
(12, 'Kanoshi', 'd_hod@hotmail.com', '1495115196', 'b560713298b7bd5df68e6532448f65395c8363ee4f880b319d8560ea355957c1', 'user', '', '', '', '', '/media/img/default.gif'),
(21, 'dragosthealx', 'dragosthealex@gmail.com', '2021878810', '5b752fbbf990bba6dd819b2cfdad4d4717e74a1fa0f07e70564e7ea9d8e5fccf', 'user', '', '', '', '', '/media/img/default.gif'),
(22, 'mitalip', 'mitalipalsikar@gmail.com', '1960714236', '9aa6cf1b193d65a96e3d4685ac4c98f8a61fc25b73cf3a65f9b67a30382a3c8c', 'user', '', '', '', '', '/media/img/default.gif'),
(23, 'PakChoi', 'liam.higgins3@googlemail.com', '217625186', 'b65cfaac7014fc5b83d53e5e965a5b88316e623feb26e3c9c336e937a7e2cd3b', 'user', '', '', '', '', '/media/img/default.gif'),
(24, 'Elnur', 'ebaku2015@gmail.com', '1791532909', 'b9c5389873c5389c47ec68e88777309aea840a4c1bcf41e06b016c9c4c1ec1da', 'user', '', '', '', '', '/media/img/default.gif'),
(26, 'dragosthealex2', 'dragosthealex2@gmail.com', '296003121', 'f7963866ff4ca304ce8f96f8882ab3b40a3cbb14994690e73891409651a76cd7', 'user', '', '', '', '', '/media/img/default.gif'),
(27, 'SirKiwiTheGreat', 'kiwis@gmail.com', '835198187', '09cfc5f0c89cc125c2508e7095bd4add6ec1c39e8cd6f51780227906e2209ac8', 'user', '', '', '', '', '/media/img/default.gif'),
(28, 'test_init_perc', 'test_init_perc@yahoo.com', '1841271249', '0a6079d6de193e2db8be8ab090e7ed6c81b96ff719a9bb0c7a393fd1f2a87b63', 'user', '', '', '', '', '/media/img/default.gif'),
(29, 'johnTheRapist', 'john@gmail.com', '260991738', '2a213ad966944aa148f0a5871aa765f15155b87adb8f2e1b3ec61f3cfc31338e', 'user', '', '', '', '', '/media/img/default.gif'),
(49, 'alexFacebook', 'dragosthealx@gmail.com', '1922294828', 'e456b4f45a512087dc7d99254244bcaf333422db11f56e301e9f88a20e20eee8', 'user', '', '', ':6a8ec544305557555c5ab365faa91dfb77cf4358505808d5292817172457158d', '797073513702931', 'https://graph.facebook.com/797073513702931/picture?type=large'),
(50, 'asdasd', 'asdsd@asasd.cim', '1852453316', 'a9619e99a93fe4c05aaeb01fc2ad88b1bdfd4aea6508edca4a6044f185df2591', 'user', '', '', '', '', '/media/img/default.gif');

-- --------------------------------------------------------

--
-- Table structure for table `ruser_qa`
--

DROP TABLE IF EXISTS `ruser_qa`;
CREATE TABLE IF NOT EXISTS `ruser_qa` (
  `answer_user_id` int(11) NOT NULL,
  `question1` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `question2` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `question3` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ruser_qa`
--

INSERT INTO `ruser_qa` (`answer_user_id`, `question1`, `question2`, `question3`) VALUES
(22, '4:3,2,1:50', '', NULL),
(27, '2:1,2:50', '', NULL),
(34, '2:3:10', '', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comp101lab8users`
--
ALTER TABLE `comp101lab8users`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `raccomodations`
--
ALTER TABLE `raccomodations`
  ADD PRIMARY KEY (`accom_id`);

--
-- Indexes for table `ranswers`
--
ALTER TABLE `ranswers`
  ADD PRIMARY KEY (`answer_id`);

--
-- Indexes for table `rconexions`
--
ALTER TABLE `rconexions`
  ADD PRIMARY KEY (`conexion_id`);

--
-- Indexes for table `rdetails`
--
ALTER TABLE `rdetails`
  ADD PRIMARY KEY (`profile_filter_id`);

--
-- Indexes for table `rfiltersmap`
--
ALTER TABLE `rfiltersmap`
  ADD PRIMARY KEY (`filter_value`);

--
-- Indexes for table `rlog`
--
ALTER TABLE `rlog`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `rmessages`
--
ALTER TABLE `rmessages`
  ADD PRIMARY KEY (`message_id`);

--
-- Indexes for table `rpercentages`
--
ALTER TABLE `rpercentages`
  ADD PRIMARY KEY (`percentage_user_id1`,`percentage_user_id2`);

--
-- Indexes for table `rquestionsmap`
--
ALTER TABLE `rquestionsmap`
  ADD PRIMARY KEY (`question_id`);

--
-- Indexes for table `rreviews`
--
ALTER TABLE `rreviews`
  ADD PRIMARY KEY (`review_id`);

--
-- Indexes for table `rsiteinfo`
--
ALTER TABLE `rsiteinfo`
  ADD PRIMARY KEY (`info_id`);

--
-- Indexes for table `rtempusers`
--
ALTER TABLE `rtempusers`
  ADD PRIMARY KEY (`temp_id`), ADD UNIQUE KEY `temp_username` (`temp_username`,`temp_email`);

--
-- Indexes for table `rusers`
--
ALTER TABLE `rusers`
  ADD PRIMARY KEY (`user_id`), ADD UNIQUE KEY `username` (`username`,`user_email`);

--
-- Indexes for table `ruser_qa`
--
ALTER TABLE `ruser_qa`
  ADD PRIMARY KEY (`answer_user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comp101lab8users`
--
ALTER TABLE `comp101lab8users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `raccomodations`
--
ALTER TABLE `raccomodations`
  MODIFY `accom_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ranswers`
--
ALTER TABLE `ranswers`
  MODIFY `answer_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `rconexions`
--
ALTER TABLE `rconexions`
  MODIFY `conexion_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `rfiltersmap`
--
ALTER TABLE `rfiltersmap`
  MODIFY `filter_value` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=237;
--
-- AUTO_INCREMENT for table `rlog`
--
ALTER TABLE `rlog`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `rmessages`
--
ALTER TABLE `rmessages`
  MODIFY `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=176;
--
-- AUTO_INCREMENT for table `rquestionsmap`
--
ALTER TABLE `rquestionsmap`
  MODIFY `question_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `rreviews`
--
ALTER TABLE `rreviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rsiteinfo`
--
ALTER TABLE `rsiteinfo`
  MODIFY `info_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `rtempusers`
--
ALTER TABLE `rtempusers`
  MODIFY `temp_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rusers`
--
ALTER TABLE `rusers`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'The user id',AUTO_INCREMENT=51;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
