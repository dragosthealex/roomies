-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 13, 2015 at 03:56 AM
-- Server version: 5.5.41-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `2014_comp10120_x7`
--

-- --------------------------------------------------------

--
-- Table structure for table `comp101lab8users`
--

CREATE TABLE IF NOT EXISTS `comp101lab8users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(70) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

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
-- Table structure for table `raccommodations`
--

CREATE TABLE IF NOT EXISTS `raccommodations` (
  `accommodation_id` int(11) NOT NULL AUTO_INCREMENT,
  `accommodation_name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `accommodation_no_photos` int(11) NOT NULL,
  `accommodation_date` date NOT NULL,
  `accommodation_rating` char(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'N/A',
  `accommodation_description` varchar(10000) COLLATE utf8_unicode_ci NOT NULL,
  `accommodation_author` int(11) NOT NULL,
  PRIMARY KEY (`accommodation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `raccommodations`
--

INSERT INTO `raccommodations` (`accommodation_id`, `accommodation_name`, `accommodation_no_photos`, `accommodation_date`, `accommodation_rating`, `accommodation_description`, `accommodation_author`) VALUES
(1, 'Whitworth Park', 0, '2015-02-25', 'N/A', 'Bedding Packs: To assist you and limit what you have to transport to the hall you can order a bedding pack online. The price for a single bedding pack is £20.00 and the pack includes a hollow fibre pillow, a 13.5tog duvet, a sheet, a pillowcase and a duvet cover. Double bedding packs are available at a price of £36.00.\n\nWhitworth Park is centrally located on the University of Manchester campus, close to sports centre, libraries and Students Union.\n\nIt comprises of eight low-rise houses containing one to three storey flats for groups of seven, eight or nine students. Accommodation is in single study bedrooms, with shared kitchen, lounge and bathroom in each flat.\n\nGrove House houses the administrative and social centre of the community. Facilities include two squash courts, launderette, bar, gym and large dance hall.\n\nActive Residents Association. Visit the Whitworth Park RA Website...*.\n\nPostgraduate students are housed in parts of Thorncliffe and Aberdeen.', 21);

-- --------------------------------------------------------

--
-- Table structure for table `ranswers`
--

CREATE TABLE IF NOT EXISTS `ranswers` (
  `answer_id` int(11) NOT NULL AUTO_INCREMENT,
  `answer_text` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`answer_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

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

CREATE TABLE IF NOT EXISTS `rconexions` (
  `conexion_id` int(11) NOT NULL AUTO_INCREMENT,
  `conexion_user_id1` int(11) NOT NULL,
  `conexion_user_id2` int(11) NOT NULL,
  `conexion_status` int(11) NOT NULL,
  PRIMARY KEY (`conexion_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=36 ;

--
-- Dumping data for table `rconexions`
--

INSERT INTO `rconexions` (`conexion_id`, `conexion_user_id1`, `conexion_user_id2`, `conexion_status`) VALUES
(28, 12, 26, 2),
(29, 23, 12, 2);

-- --------------------------------------------------------

--
-- Table structure for table `rdetails`
--

CREATE TABLE IF NOT EXISTS `rdetails` (
  `profile_filter_id` int(11) NOT NULL COMMENT 'The profile''s ID',
  `first_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `birthday` date NOT NULL,
  `country` tinyint(3) unsigned NOT NULL,
  `language` tinyint(3) unsigned NOT NULL,
  `gender` tinyint(3) unsigned NOT NULL,
  `uni_city` int(11) NOT NULL,
  PRIMARY KEY (`profile_filter_id`)
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
(50, 'adsds', 'dasdassad', 0, '2006-03-03', 3, 3, 3, 1),
(54, 'Alex', 'Radu', 0, '1995-04-01', 174, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rfiltersmap`
--

CREATE TABLE IF NOT EXISTS `rfiltersmap` (
  `filter_value` int(11) NOT NULL AUTO_INCREMENT,
  `map_country` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `map_language` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `map_gender` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `map_uni_city` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`filter_value`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=237 ;

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

CREATE TABLE IF NOT EXISTS `rlog` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='logs when users get the pass wrong. protects against bruteforce attacks' AUTO_INCREMENT=19 ;

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

CREATE TABLE IF NOT EXISTS `rmessages` (
  `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `message_user_id1` int(10) unsigned NOT NULL,
  `message_user_id2` int(10) unsigned NOT NULL,
  `message_text` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `message_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `messages_read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=267 ;

--
-- Dumping data for table `rmessages`
--

INSERT INTO `rmessages` (`message_id`, `message_user_id1`, `message_user_id2`, `message_text`, `message_timestamp`, `messages_read`) VALUES
(124, 12, 12, 'Here''s a test:\n&lt;div class=&quot;test&quot;&gt;&lt;br&gt;&lt;/div&gt;\n&lt;div class=''test''&gt;&lt;/div&gt;', '2015-02-14 17:15:09', 1),
(126, 12, 12, 'I haven''t even started on the mobile version yet ._.', '2015-02-14 17:16:03', 1),
(128, 12, 12, 'Gonna fix the chat replacing issue', '2015-02-14 17:17:45', 1),
(142, 12, 12, 'I''m not seeing my messages when I refresh though o.o', '2015-02-14 17:27:20', 1),
(143, 12, 12, 'Well, only when I send a new message, all of the recent ones I sent get displayed', '2015-02-14 17:27:46', 1),
(144, 12, 12, 'Ah... I''m so confused right now.\nWe need the longpolling thing to only check for the unread messages sent by the other user, and the send button to only get the unread messages sent by the other user along with the single most recent message sent by the current user (i.e. th message they just sent)', '2015-02-14 17:38:47', 1),
(160, 12, 12, 'Test?', '2015-02-14 19:09:48', 1),
(161, 12, 12, 'Test', '2015-02-14 19:28:06', 1),
(162, 12, 12, 'newtest', '2015-02-15 03:18:57', 1),
(163, 12, 12, 'newtest2', '2015-02-15 03:20:34', 1),
(164, 12, 12, 'newtest3', '2015-02-15 03:36:38', 1),
(165, 12, 12, 'newtest4', '2015-02-15 03:37:55', 1),
(166, 12, 12, 'newtest5', '2015-02-15 03:40:47', 1),
(167, 12, 12, 'newtest6', '2015-02-15 03:50:56', 1),
(176, 49, 12, 'shit fuck', '2015-02-27 21:41:48', 1),
(177, 49, 12, 'fuck', '2015-02-27 21:41:53', 1),
(178, 49, 12, 'shit', '2015-02-27 21:41:55', 1),
(179, 49, 12, 'piss', '2015-02-27 21:41:57', 1),
(180, 49, 12, 'arse', '2015-02-27 21:41:59', 1),
(181, 49, 12, 'asdasd', '2015-02-27 21:42:03', 1),
(182, 49, 12, 'fuck\n\n\nshit piss', '2015-02-27 21:42:20', 1),
(183, 12, 49, 'fdsfds', '2015-03-12 02:57:43', 0),
(184, 12, 49, 'wait', '2015-03-13 01:22:59', 0),
(185, 12, 49, 'wut', '2015-03-13 01:23:04', 0),
(186, 12, 49, 'sdasda', '2015-03-13 01:23:08', 0),
(187, 12, 49, 'dsadsa', '2015-03-13 01:23:10', 0),
(188, 12, 49, 'dsadsa', '2015-03-13 01:23:12', 0),
(189, 12, 49, 'dsadsadsa', '2015-03-13 01:23:15', 0),
(190, 12, 12, 'as', '2015-03-13 03:13:16', 1),
(191, 12, 12, 's', '2015-03-13 03:13:16', 1),
(192, 12, 12, 'sd', '2015-03-13 03:13:16', 1),
(193, 12, 12, 'dd', '2015-03-13 03:13:17', 1),
(194, 12, 12, 'f', '2015-03-13 03:13:17', 1),
(195, 12, 12, 'f', '2015-03-13 03:13:17', 1),
(196, 12, 12, 'ge', '2015-03-13 03:13:18', 1),
(197, 12, 12, 'q', '2015-03-13 03:13:18', 1),
(198, 12, 12, 's', '2015-03-13 03:13:19', 1),
(199, 12, 12, 'c', '2015-03-13 03:13:19', 1),
(200, 12, 12, 'c', '2015-03-13 03:13:19', 1),
(201, 12, 12, 'ed', '2015-03-13 03:13:20', 1),
(202, 12, 12, 'dd', '2015-03-13 03:13:20', 1),
(203, 12, 12, 'we', '2015-03-13 03:13:20', 1),
(204, 12, 12, 'e', '2015-03-13 03:13:22', 1),
(205, 12, 12, 'd', '2015-03-13 03:13:22', 1),
(206, 12, 12, 'd', '2015-03-13 03:13:22', 1),
(207, 12, 12, '1', '2015-03-13 03:13:25', 1),
(208, 12, 12, '2', '2015-03-13 03:13:26', 1),
(209, 12, 12, '3', '2015-03-13 03:13:26', 1),
(210, 12, 12, '4', '2015-03-13 03:13:26', 1),
(211, 12, 12, '5', '2015-03-13 03:13:27', 1),
(212, 12, 12, '6', '2015-03-13 03:13:27', 1),
(213, 12, 12, '7', '2015-03-13 03:13:27', 1),
(214, 12, 12, '8', '2015-03-13 03:13:28', 1),
(215, 12, 12, '9', '2015-03-13 03:13:30', 1),
(216, 12, 12, '8', '2015-03-13 03:13:31', 1),
(217, 12, 12, '7', '2015-03-13 03:13:31', 1),
(218, 12, 12, '6', '2015-03-13 03:13:31', 1),
(219, 12, 12, '5', '2015-03-13 03:13:32', 1),
(220, 12, 12, '4', '2015-03-13 03:13:32', 1),
(221, 12, 12, '3', '2015-03-13 03:13:32', 1),
(222, 12, 12, '2', '2015-03-13 03:13:33', 1),
(223, 12, 12, '1', '2015-03-13 03:13:33', 1),
(224, 12, 12, '2', '2015-03-13 03:13:33', 1),
(225, 12, 12, '3', '2015-03-13 03:13:33', 1),
(226, 12, 12, '4', '2015-03-13 03:13:34', 1),
(227, 12, 12, '5', '2015-03-13 03:13:34', 1),
(228, 12, 12, '6', '2015-03-13 03:13:34', 1),
(229, 12, 12, '7', '2015-03-13 03:13:35', 1),
(230, 12, 12, '8', '2015-03-13 03:13:35', 1),
(231, 12, 12, '9', '2015-03-13 03:13:36', 1),
(232, 12, 12, '8', '2015-03-13 03:13:36', 1),
(233, 12, 12, '7', '2015-03-13 03:13:36', 1),
(234, 12, 12, '6', '2015-03-13 03:13:37', 1),
(235, 12, 12, '5', '2015-03-13 03:13:37', 1),
(236, 12, 12, '4', '2015-03-13 03:13:38', 1),
(237, 12, 12, '3', '2015-03-13 03:13:38', 1),
(238, 12, 12, '2', '2015-03-13 03:13:38', 1),
(239, 12, 12, '1', '2015-03-13 03:13:40', 1),
(240, 12, 12, 'i', '2015-03-13 03:13:47', 1),
(241, 12, 12, 'a', '2015-03-13 03:13:47', 1),
(242, 12, 12, 'd', '2015-03-13 03:13:48', 1),
(243, 12, 12, 'c', '2015-03-13 03:13:49', 1),
(244, 12, 12, 'a', '2015-03-13 03:13:49', 1),
(245, 12, 12, 'as', '2015-03-13 03:13:49', 1),
(246, 12, 12, 'd', '2015-03-13 03:13:50', 1),
(247, 12, 12, 'd', '2015-03-13 03:13:50', 1),
(248, 12, 12, 'w', '2015-03-13 03:13:50', 1),
(249, 12, 12, 'd', '2015-03-13 03:13:50', 1),
(250, 12, 12, 'f', '2015-03-13 03:13:51', 1),
(251, 12, 12, 'r', '2015-03-13 03:13:51', 1),
(252, 12, 12, 'f', '2015-03-13 03:13:51', 1),
(253, 12, 12, 's', '2015-03-13 03:13:51', 1),
(254, 12, 12, 'q', '2015-03-13 03:13:52', 1),
(255, 12, 12, '1', '2015-03-13 03:13:52', 1),
(256, 12, 12, '3', '2015-03-13 03:13:52', 1),
(257, 12, 12, '4', '2015-03-13 03:13:53', 1),
(258, 12, 12, '5', '2015-03-13 03:13:53', 1),
(259, 12, 12, '6', '2015-03-13 03:13:53', 1),
(260, 12, 12, '7', '2015-03-13 03:13:53', 1),
(261, 12, 12, '89', '2015-03-13 03:13:54', 1),
(262, 12, 12, '9', '2015-03-13 03:13:54', 1),
(263, 12, 12, '6', '2015-03-13 03:13:54', 1),
(264, 12, 12, '4', '2015-03-13 03:13:54', 1),
(265, 12, 12, '4', '2015-03-13 03:13:55', 1),
(266, 12, 12, '3', '2015-03-13 03:13:55', 0);

-- --------------------------------------------------------

--
-- Table structure for table `rpercentages`
--

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
  `percentage_city` int(11) NOT NULL,
  PRIMARY KEY (`percentage_user_id1`,`percentage_user_id2`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `rpercentages`
--

INSERT INTO `rpercentages` (`percentage_user_id1`, `percentage_user_id2`, `percentage`, `id1_1`, `id1_10`, `id1_50`, `id2_1`, `id2_10`, `id2_50`, `id1_max`, `id2_max`, `percentage_city`) VALUES
(12, 22, 10, 0, 0, 11, 0, 0, 0, 550, 242, 1),
(12, 23, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(12, 26, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(12, 27, 62, 0, 0, 10, 0, 0, 2, 550, 242, 1),
(12, 28, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(12, 49, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(12, 50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(12, 54, 21, 0, 1, 0, 0, 0, 2, 110, 242, 1),
(22, 23, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(22, 26, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(22, 27, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(22, 28, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(22, 49, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(22, 50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(22, 54, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(23, 26, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(23, 27, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(23, 28, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(23, 49, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(23, 50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(23, 54, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(26, 27, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(26, 28, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(26, 49, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(26, 50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(26, 54, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(27, 28, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(27, 49, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(27, 50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(27, 54, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(28, 49, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(28, 50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(28, 54, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(49, 50, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(49, 54, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(50, 54, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rquestionsmap`
--

CREATE TABLE IF NOT EXISTS `rquestionsmap` (
  `question_id` int(11) NOT NULL AUTO_INCREMENT,
  `question_text` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `question_answers` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`question_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

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

CREATE TABLE IF NOT EXISTS `rreviews` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `review_acc_id` int(11) NOT NULL,
  `review_text` text COLLATE utf8_unicode_ci,
  `review_rating` int(11) DEFAULT NULL,
  `review_author` int(11) DEFAULT NULL,
  `review_date` date NOT NULL,
  PRIMARY KEY (`review_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rsiteinfo`
--

CREATE TABLE IF NOT EXISTS `rsiteinfo` (
  `info_id` int(11) NOT NULL AUTO_INCREMENT,
  `info` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`info_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `rsiteinfo`
--

INSERT INTO `rsiteinfo` (`info_id`, `info`, `value`) VALUES
(1, 'no_questions', '2');

-- --------------------------------------------------------

--
-- Table structure for table `rtempusers`
--

CREATE TABLE IF NOT EXISTS `rtempusers` (
  `temp_id` int(11) NOT NULL AUTO_INCREMENT,
  `temp_username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `temp_email` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `temp_salt` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `temp_pass` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `conf` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`temp_id`),
  UNIQUE KEY `temp_username` (`temp_username`,`temp_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rusers`
--

CREATE TABLE IF NOT EXISTS `rusers` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'The user id',
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The username, used for URL',
  `user_email` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The email of the user',
  `user_salt` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The salt, used for pass hashing',
  `user_pass` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT 'The inserted pass',
  `user_rank` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user' COMMENT 'Can be user/mod/admin/?',
  `matches` longtext COLLATE utf8_unicode_ci NOT NULL,
  `has_updated` longtext COLLATE utf8_unicode_ci NOT NULL,
  `user_cookie` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `facebook_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `image_url` varchar(150) COLLATE utf8_unicode_ci NOT NULL DEFAULT '/media/img/default.gif',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`,`user_email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=55 ;

--
-- Dumping data for table `rusers`
--

INSERT INTO `rusers` (`user_id`, `username`, `user_email`, `user_salt`, `user_pass`, `user_rank`, `matches`, `has_updated`, `user_cookie`, `facebook_id`, `image_url`) VALUES
(11, 'guest', 'guest@cs.man.ac.uk', '1012506342', 'bbbfb2a1528fb0fad3f7309b5c3b1df750ff5bc3e9c89d0c66d36a48715a902c', 'user', '', '', '', '', '/media/img/default.gif'),
(12, 'Kanoshi', 'd_hod@hotmail.com', '1495115196', 'b560713298b7bd5df68e6532448f65395c8363ee4f880b319d8560ea355957c1', 'user', '', '', '', '', '/media/img/default.gif'),
(22, 'mitalip', 'mitalipalsikar@gmail.com', '1960714236', '9aa6cf1b193d65a96e3d4685ac4c98f8a61fc25b73cf3a65f9b67a30382a3c8c', 'user', '', '', '', '', '/media/img/default.gif'),
(23, 'PakChoi', 'liam.higgins3@googlemail.com', '217625186', 'b65cfaac7014fc5b83d53e5e965a5b88316e623feb26e3c9c336e937a7e2cd3b', 'user', '', '', '', '', '/media/img/default.gif'),
(24, 'Elnur', 'ebaku2015@gmail.com', '1791532909', 'b9c5389873c5389c47ec68e88777309aea840a4c1bcf41e06b016c9c4c1ec1da', 'user', '', '', '', '', '/media/img/default.gif'),
(26, 'dragosthealex2', 'dragosthealex2@gmail.com', '296003121', 'f7963866ff4ca304ce8f96f8882ab3b40a3cbb14994690e73891409651a76cd7', 'user', '', '', '', '', '/media/img/default.gif'),
(27, 'SirKiwiTheGreat', 'kiwis@gmail.com', '835198187', '09cfc5f0c89cc125c2508e7095bd4add6ec1c39e8cd6f51780227906e2209ac8', 'user', '', '', '', '', '/media/img/default.gif'),
(28, 'test_init_perc', 'test_init_perc@yahoo.com', '1841271249', '0a6079d6de193e2db8be8ab090e7ed6c81b96ff719a9bb0c7a393fd1f2a87b63', 'user', '', '', '', '', '/media/img/default.gif'),
(29, 'johnTheRapist', 'john@gmail.com', '260991738', '2a213ad966944aa148f0a5871aa765f15155b87adb8f2e1b3ec61f3cfc31338e', 'user', '', '', '', '', '/media/img/default.gif'),
(49, 'alexFacebook', 'dragosthealx@gmail.com', '1922294828', 'e456b4f45a512087dc7d99254244bcaf333422db11f56e301e9f88a20e20eee8', 'user', '', '', '', '797073513702931', 'https://graph.facebook.com/797073513702931/picture?type=large'),
(50, 'asdasd', 'asdsd@asasd.cim', '1852453316', 'a9619e99a93fe4c05aaeb01fc2ad88b1bdfd4aea6508edca4a6044f185df2591', 'user', '', '', '', '', '/media/img/default.gif'),
(53, 'Bob-facebook', 'kerplll_rosenthalsky_1424621506@tfbnw.ne', '1710536754', '9b1cb646b471ad5143df808dd6dac5e870dccdbcbe55c101acf1218cb84745be', 'user', '', '', '', '1377925132525015', 'https://graph.facebook.com/1377925132525015/picture?type=large'),
(54, 'dragosthealx', 'shit@gmail.com', '2076150000', 'a3dff4881dfc8105fa360cf8f1da33d2255579dcc5f3ef676bf299ced84e1f06', 'user', '', '', '', '', '/media/img/default.gif');

-- --------------------------------------------------------

--
-- Table structure for table `rusersettings`
--

CREATE TABLE IF NOT EXISTS `rusersettings` (
  `setting_user_id` int(11) NOT NULL,
  `is_private` tinyint(1) NOT NULL DEFAULT '1',
  `is_invisible` tinyint(1) NOT NULL DEFAULT '0',
  `notif_request` tinyint(1) NOT NULL DEFAULT '1',
  `notif_accept` tinyint(1) NOT NULL DEFAULT '1',
  `notif_message` tinyint(1) NOT NULL DEFAULT '1',
  `notif_over90` tinyint(1) NOT NULL DEFAULT '1',
  `notif_fbfriend` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`setting_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rusersettings`
--

INSERT INTO `rusersettings` (`setting_user_id`, `is_private`, `is_invisible`, `notif_request`, `notif_accept`, `notif_message`, `notif_over90`, `notif_fbfriend`) VALUES
(11, 1, 0, 1, 1, 1, 1, 1),
(12, 1, 0, 1, 1, 1, 1, 1),
(22, 1, 0, 1, 1, 1, 1, 1),
(23, 1, 0, 1, 1, 1, 1, 1),
(24, 1, 0, 1, 1, 1, 1, 1),
(26, 1, 0, 1, 1, 1, 1, 1),
(27, 1, 0, 1, 1, 1, 1, 1),
(28, 1, 0, 1, 1, 1, 1, 1),
(29, 1, 0, 1, 1, 1, 1, 1),
(49, 1, 0, 1, 1, 1, 1, 1),
(50, 1, 0, 1, 1, 1, 1, 1),
(53, 1, 0, 1, 1, 1, 1, 1),
(54, 1, 1, 0, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ruser_qa`
--

CREATE TABLE IF NOT EXISTS `ruser_qa` (
  `answer_user_id` int(11) NOT NULL,
  `question1` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `question2` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `question3` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`answer_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `ruser_qa`
--

INSERT INTO `ruser_qa` (`answer_user_id`, `question1`, `question2`, `question3`) VALUES
(22, '4:3,2,1:50', '', NULL),
(27, '2:1,2:50', '', NULL),
(54, '2:3:10', '', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
