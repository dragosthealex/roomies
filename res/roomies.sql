-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 18, 2015 at 03:00 PM
-- Server version: 5.5.41
-- PHP Version: 5.4.36-0+deb7u3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `roomies`
--

-- --------------------------------------------------------

--
-- Table structure for table `raccommodations`
--

CREATE TABLE IF NOT EXISTS `raccommodations` (
  `accommodation_id` int(11) NOT NULL AUTO_INCREMENT,
  `accommodation_name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `accommodation_no_photos` int(11) NOT NULL,
  `accommodation_date` date NOT NULL,
  `accommodation_rating` char(4) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `accommodation_description` varchar(10000) COLLATE utf8_unicode_ci NOT NULL,
  `accommodation_author` int(11) NOT NULL,
  `accommodation_rating_array` varchar(10000) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`accommodation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `raccommodations`
--

INSERT INTO `raccommodations` (`accommodation_id`, `accommodation_name`, `accommodation_no_photos`, `accommodation_date`, `accommodation_rating`, `accommodation_description`, `accommodation_author`, `accommodation_rating_array`) VALUES
(1, 'Whitworth Park', 0, '2015-02-25', '80', 'Bedding Packs: To assist you and limit what you have to transport to the hall you can order a bedding pack online. The price for a single bedding pack is  	&pound;20.00 and the pack includes a hollow fibre pillow, a 13.5tog duvet, a sheet, a pillowcase and a duvet cover. Double bedding packs are available at a price of  	&pound;36.00.\n\nWhitworth Park is centrally located on the University of Manchester campus, close to sports centre, libraries and Students Union.\n\nIt comprises of eight low-rise houses containing one to three storey flats for groups of seven, eight or nine students. Accommodation is in single study bedrooms, with shared kitchen, lounge and bathroom in each flat.\n\nGrove House houses the administrative and social centre of the community. Facilities include two squash courts, launderette, bar, gym and large dance hall.\n\nActive Residents Association. Visit the Whitworth Park RA Website...*.\n\nPostgraduate students are housed in parts of Thorncliffe and Aberdeen.', 54, ',49:,80');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
  `ethnicity` tinyint(4) NOT NULL,
  `smokes` tinyint(4) NOT NULL,
  `drinks` tinyint(4) NOT NULL,
  `drugs` tinyint(4) NOT NULL,
  `religion` tinyint(4) NOT NULL,
  `sign` tinyint(4) NOT NULL,
  `degree` tinyint(4) NOT NULL,
  `studies` tinyint(4) NOT NULL,
  `parties` tinyint(4) NOT NULL,
  `offspring` tinyint(4) NOT NULL,
  `pets` tinyint(4) NOT NULL,
  `orientation` tinyint(4) NOT NULL,
  PRIMARY KEY (`profile_filter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `rdetails`
--

INSERT INTO `rdetails` (`profile_filter_id`, `first_name`, `last_name`, `completed`, `birthday`, `country`, `language`, `gender`, `uni_city`, `ethnicity`, `smokes`, `drinks`, `drugs`, `religion`, `sign`, `degree`, `studies`, `parties`, `offspring`, `pets`, `orientation`) VALUES
(11, 'Guest', 'Account', 0, '1996-01-01', 221, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(12, 'Daniel', 'Hodgson', 0, '1996-08-11', 221, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(21, 'Alex', 'Radu', 0, '0000-00-00', 174, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(22, 'M', 'P', 0, '1995-09-05', 100, 1, 2, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(23, 'Liam', 'Higgins', 0, '1996-06-28', 221, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(26, 'Alex2', 'Radu2', 0, '1995-04-02', 174, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(27, 'His Kiwiness', 'Mr. Kiwi', 0, '0000-00-00', 12, 14, 3, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(28, 'test', 'init perc', 0, '0000-00-00', 5, 4, 3, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(49, 'Dragos', 'Radu', 0, '1995-04-01', 174, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(50, 'adsds', 'dasdassad', 0, '2006-03-03', 3, 3, 3, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(54, 'Alex', 'Radu', 0, '1995-04-01', 174, 1, 1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

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
  `map_ethnicity` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `map_smokes` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `map_drinks` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `map_drugs` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `map_religion` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `map_sign` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `map_degree` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `map_studies` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `map_parties` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `map_offspring` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `map_pets` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `map_orientation` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`filter_value`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=237 ;

--
-- Dumping data for table `rfiltersmap`
--

INSERT INTO `rfiltersmap` (`filter_value`, `map_country`, `map_language`, `map_gender`, `map_uni_city`, `map_ethnicity`, `map_smokes`, `map_drinks`, `map_drugs`, `map_religion`, `map_sign`, `map_degree`, `map_studies`, `map_parties`, `map_offspring`, `map_pets`, `map_orientation`) VALUES
(1, 'Afghanistan', 'English', 'man', 'Manchester', 'Asian', 'Yes', 'Very often', 'Often', '', 'Leo', 'Accounting, Business & Finance', 'Very Often', 'Often', 'Has a kid', 'None', ''),
(2, 'Albania', 'Afar', 'woman', '', 'Native American', 'Sometimes', 'Often', 'Sometimes', '', 'Aquarius', 'Agriculture and Horticulture', 'Often', 'Sometimes', 'Has kids', 'Dog(s)', ''),
(3, 'Algeria', 'Abkhazian', 'trans', '', 'Hispanic / Latin', 'When drinking', 'Socially', 'Never', '', 'Cancer', 'Archaeology', 'Sometimes', 'Never', 'Does not have kids', 'Cat(s)', ''),
(4, 'American Samoa', 'Afrikaans', '', '', 'Middle Eastern', 'Trying to quit', 'Rarely', '', '', 'Taurus', 'Architecture, Building & Planning', 'Rarely', '', 'Eats kids', 'Arachnide(s)', ''),
(5, 'Andorra', 'Amharic', '', '', 'Indian', 'No', 'Desperately', '', '', 'Scorpio', 'Art and design', 'Never', '', '', 'Snake(s)', ''),
(6, 'Angola', 'Arabic', '', '', 'White', '', 'Not at all', '', '', 'Virgo', 'Biology', '', '', '', 'Bird(s)', ''),
(7, 'Anguilla', 'Assamese', '', '', 'Black / African American', '', '', '', '', 'Pisces', 'Chemistry', '', '', '', 'Rodent(s)', ''),
(8, 'Antarctica', 'Aymara', '', '', 'Pacific Islander (just fucking Pacific)', '', '', '', '', 'Aries', 'Communication and Media', '', '', '', 'Little brother(s)', ''),
(9, 'Antigua and Barbuda', 'Azerbaijani', '', '', 'Other', '', '', '', '', 'Gemini', 'Computing & IT', '', '', '', '', ''),
(10, 'Argentina', 'Bashkir', '', '', 'Other2, for those not in Other', '', '', '', '', 'Libra', 'Dentistry', '', '', '', '', ''),
(11, 'Armenia', 'Byelorussian', '', '', '', '', '', '', '', 'Sagittarius', 'Earth Sciences', '', '', '', '', ''),
(12, 'Aruba', 'Bulgarian', '', '', '', '', '', '', '', 'Capricorn', 'Economics', '', '', '', '', ''),
(13, 'Australia', 'Bihari', '', '', '', '', '', '', '', '', 'Education', '', '', '', '', ''),
(14, 'Austria', 'Bislama', '', '', '', '', '', '', '', '', 'Engineering', '', '', '', '', ''),
(15, 'Azerbaijan', 'Bengali/Bangla', '', '', '', '', '', '', '', '', 'English Language', '', '', '', '', ''),
(16, 'Bahamas', 'Tibetan', '', '', '', '', '', '', '', '', 'English Literature', '', '', '', '', ''),
(17, 'Bahrain', 'Breton', '', '', '', '', '', '', '', '', 'Fashion and textiles', '', '', '', '', ''),
(18, 'Bangladesh', 'Catalan', '', '', '', '', '', '', '', '', 'Geography', '', '', '', '', ''),
(19, 'Barbados', 'Corsican', '', '', '', '', '', '', '', '', 'Health and Medicine', '', '', '', '', ''),
(20, 'Belarus', 'Czech', '', '', '', '', '', '', '', '', 'History', '', '', '', '', ''),
(21, 'Belgium', 'Welsh', '', '', '', '', '', '', '', '', 'Hospitality & Catering', '', '', '', '', ''),
(22, 'Belize', 'Danish', '', '', '', '', '', '', '', '', 'Languages', '', '', '', '', ''),
(23, 'Benin', 'German', '', '', '', '', '', '', '', '', 'Law', '', '', '', '', ''),
(24, 'Bermuda', 'Bhutani', '', '', '', '', '', '', '', '', 'Management', '', '', '', '', ''),
(25, 'Bhutan', 'Greek', '', '', '', '', '', '', '', '', 'Marketing', '', '', '', '', ''),
(26, 'Bolivia', 'Esperanto', '', '', '', '', '', '', '', '', 'Mathematics', '', '', '', '', ''),
(27, 'Bosnia and Herzegowi', 'Spanish', '', '', '', '', '', '', '', '', 'Music', '', '', '', '', ''),
(28, 'Botswana', 'Estonian', '', '', '', '', '', '', '', '', 'Nursing', '', '', '', '', ''),
(29, 'Bouvet Island', 'Basque', '', '', '', '', '', '', '', '', 'Pharmacology', '', '', '', '', ''),
(30, 'Brazil', 'Persian', '', '', '', '', '', '', '', '', 'Philosophy', '', '', '', '', ''),
(31, 'British Indian Ocean', 'Finnish', '', '', '', '', '', '', '', '', 'Physics', '', '', '', '', ''),
(32, 'Brunei Darussalam', 'Fiji', '', '', '', '', '', '', '', '', 'Politics', '', '', '', '', ''),
(33, 'Bulgaria', 'Faeroese', '', '', '', '', '', '', '', '', 'Psychology and Counselling', '', '', '', '', ''),
(34, 'Burkina Faso', 'French', '', '', '', '', '', '', '', '', 'Social Work', '', '', '', '', ''),
(35, 'Burundi', 'Frisian', '', '', '', '', '', '', '', '', 'Sociology', '', '', '', '', ''),
(36, 'Cambodia', 'Irish', '', '', '', '', '', '', '', '', 'Sports & Leisure', '', '', '', '', ''),
(37, 'Cameroon', 'Scots/Gaelic', '', '', '', '', '', '', '', '', 'Theatre & Dramatic Arts', '', '', '', '', ''),
(38, 'Canada', 'Galician', '', '', '', '', '', '', '', '', 'Theology & Religion', '', '', '', '', ''),
(39, 'Cape Verde', 'Guarani', '', '', '', '', '', '', '', '', 'Travel and Tourism', '', '', '', '', ''),
(40, 'Cayman Islands', 'Gujarati', '', '', '', '', '', '', '', '', 'Veterinary Medicine', '', '', '', '', ''),
(41, 'Central African Repu', 'Hausa', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(42, 'Chad', 'Hindi', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(43, 'Chile', 'Croatian', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(44, 'China', 'Hungarian', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(45, 'Christmas Island', 'Armenian', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(46, 'Cocos (Keeling) Isla', 'Interlingua', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(47, 'Colombia', 'Interlingue', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(48, 'Comoros', 'Inupiak', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(49, 'Congo', 'Indonesian', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(50, 'Congo, the Democrati', 'Icelandic', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(51, 'Cook Islands', 'Italian', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(52, 'Costa Rica', 'Hebrew', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(53, 'Croatia (Hrvatska)', 'Yiddish', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(54, 'Cuba', 'Javanese', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(55, 'Cyprus', 'Georgian', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(56, 'Czech Republic', 'Kazakh', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(57, 'Denmark', 'Greenlandic', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(58, 'Djibouti', 'Cambodian', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(59, 'Dominica', 'Kannada', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(60, 'Dominican Republic', 'Korean', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(61, 'East Timor', 'Kashmiri', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(62, 'Ecuador', 'Kurdish', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(63, 'Egypt', 'Kirghiz', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(64, 'El Salvador', 'Latin', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(65, 'Equatorial Guinea', 'Lingala', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(66, 'Eritrea', 'Laothian', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(67, 'Estonia', 'Lithuanian', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(68, 'Ethiopia', 'Latvian/Lettish', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(69, 'Falkland Islands (Ma', 'Malagasy', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(70, 'Faroe Islands', 'Maori', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(71, 'Fiji', 'Macedonian', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(72, 'Finland', 'Malayalam', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(73, 'France', 'Mongolian', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(74, 'France Metropolitan', 'Moldavian', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(75, 'French Guiana', 'Marathi', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(76, 'French Polynesia', 'Malay', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(77, 'French Southern Terr', 'Maltese', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(78, 'Gabon', 'Burmese', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(79, 'Gambia', 'Nauru', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(80, 'Georgia', 'Nepali', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(81, 'Germany', 'Dutch', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(82, 'Ghana', 'Norwegian', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(83, 'Gibraltar', 'Occitan', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(84, 'Greece', '(Afan)/Oromoor/Oriya', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(85, 'Greenland', 'Punjabi', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(86, 'Grenada', 'Polish', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(87, 'Guadeloupe', 'Pashto/Pushto', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(88, 'Guam', 'Portuguese', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(89, 'Guatemala', 'Quechua', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(90, 'Guinea', 'Rhaeto-Romance', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(91, 'Guinea-Bissau', 'Kirundi', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(92, 'Guyana', 'Romanian', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(93, 'Haiti', 'Russian', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(94, 'Heard and Mc Donald ', 'Kinyarwanda', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(95, 'Holy See (Vatican Ci', 'Sanskrit', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(96, 'Honduras', 'Sindhi', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(97, 'Hong Kong', 'Sangro', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(98, 'Hungary', 'Serbo-Croatian', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(99, 'Iceland', 'Singhalese', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(100, 'India', 'Slovak', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(101, 'Indonesia', 'Slovenian', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(102, 'Iran (Islamic Republ', 'Samoan', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(103, 'Iraq', 'Shona', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(104, 'Ireland', 'Somali', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(105, 'Israel', 'Albanian', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(106, 'Italy', 'Serbian', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(107, 'Jamaica', 'Siswati', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(108, 'Japan', 'Sesotho', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(109, 'Jordan', 'Sundanese', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(110, 'Kazakhstan', 'Swedish', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(111, 'Kenya', 'Swahili', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(112, 'Kiribati', 'Tamil', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(113, 'Korea, Republic of', 'Tajik', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(114, 'Kuwait', 'Thai', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(115, 'Kyrgyzstan', 'Tigrinya', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(116, 'Latvia', 'Tagalog', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(117, 'Lebanon', 'Setswana', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(118, 'Lesotho', 'Tonga', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(119, 'Liberia', 'Turkish', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(120, 'Libyan Arab Jamahiri', 'Tsonga', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(121, 'Liechtenstein', 'Tatar', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(122, 'Lithuania', 'Twi', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(123, 'Luxembourg', 'Ukrainian', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(124, 'Macau', 'Urdu', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(125, 'Macedonia, The Forme', 'Uzbek', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(126, 'Madagascar', 'Vietnamese', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(127, 'Malawi', 'Volapuk', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(128, 'Malaysia', 'Wolof', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(129, 'Maldives', 'Xhosa', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(130, 'Mali', 'Yoruba', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(131, 'Malta', 'Chinese', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(132, 'Marshall Islands', 'Zulu', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(133, 'Martinique', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(134, 'Mauritania', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(135, 'Mauritius', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(136, 'Mayotte', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(137, 'Mexico', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(138, 'Micronesia, Federate', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(139, 'Moldova, Republic of', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(140, 'Monaco', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(141, 'Mongolia', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(142, 'Montserrat', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(143, 'Morocco', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(144, 'Mozambique', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(145, 'Myanmar', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(146, 'Namibia', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(147, 'Nauru', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(148, 'Nepal', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(149, 'Netherlands', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(150, 'Netherlands Antilles', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(151, 'New Caledonia', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(152, 'New Zealand', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(153, 'Nicaragua', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(154, 'Niger', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(155, 'Nigeria', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(156, 'Niue', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(157, 'Norfolk Island', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(158, 'Northern Mariana Isl', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(159, 'Norway', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(160, 'Oman', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(161, 'Pakistan', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(162, 'Palau', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(163, 'Panama', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(164, 'Papua New Guinea', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(165, 'Paraguay', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(166, 'Peru', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(167, 'Philippines', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(168, 'Pitcairn', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(169, 'Poland', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(170, 'Portugal', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(171, 'Puerto Rico', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(172, 'Qatar', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(173, 'Reunion', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(174, 'Romania', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(175, 'Russian Federation', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(176, 'Rwanda', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(177, 'Saint Kitts and Nevi', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(178, 'Saint Lucia', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(179, 'Saint Vincent and th', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(180, 'Samoa', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(181, 'San Marino', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(182, 'Sao Tome and Princip', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(183, 'Saudi Arabia', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(184, 'Senegal', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(185, 'Seychelles', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(186, 'Sierra Leone', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(187, 'Singapore', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(188, 'Slovakia (Slovak Rep', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(189, 'Slovenia', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(190, 'Solomon Islands', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(191, 'Somalia', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(192, 'South Africa', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(193, 'South Georgia and th', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(194, 'Spain', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(195, 'Sri Lanka', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(196, 'St. Helena', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(197, 'St. Pierre and Mique', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(198, 'Sudan', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(199, 'Suriname', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(200, 'Svalbard and Jan May', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(201, 'Swaziland', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(202, 'Sweden', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(203, 'Switzerland', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(204, 'Syrian Arab Republic', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(205, 'Taiwan, Province of ', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(206, 'Tajikistan', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(207, 'Tanzania, United Rep', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(208, 'Thailand', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(209, 'Togo', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(210, 'Tokelau', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(211, 'Tonga', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(212, 'Trinidad and Tobago', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(213, 'Tunisia', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(214, 'Turkey', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(215, 'Turkmenistan', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(216, 'Turks and Caicos Isl', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(217, 'Tuvalu', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(218, 'Uganda', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(219, 'Ukraine', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(220, 'United Arab Emirates', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(221, 'United Kingdom', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(222, 'United States', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(223, 'United States Minor ', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(224, 'Uruguay', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(225, 'Uzbekistan', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(226, 'Vanuatu', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(227, 'Venezuela', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(228, 'Vietnam', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(229, 'Virgin Islands (Brit', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(230, 'Virgin Islands (U.S.', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(231, 'Wallis and Futuna Is', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(232, 'Western Sahara', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(233, 'Yemen', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(234, 'Yugoslavia', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(235, 'Zambia', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(236, 'Zimbabwe', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `rgroups`
--

CREATE TABLE IF NOT EXISTS `rgroups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(200) NOT NULL,
  `group_description` varchar(2000) NOT NULL,
  `group_admin` int(11) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `rgroups`
--

INSERT INTO `rgroups` (`group_id`, `group_name`, `group_description`, `group_admin`) VALUES
(1, 'First group ever', 'huge orgy here', 54),
(2, 'second group', 'smaller orgy here', 54);

-- --------------------------------------------------------

--
-- Table structure for table `rlog`
--

CREATE TABLE IF NOT EXISTS `rlog` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='logs when users get the pass wrong. protects against bruteforce attacks' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rmessages`
--

CREATE TABLE IF NOT EXISTS `rmessages` (
  `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `message_user_id1` int(10) unsigned NOT NULL,
  `message_user_id2` int(10) unsigned DEFAULT NULL,
  `message_text` varchar(1000) COLLATE utf8_unicode_ci NOT NULL,
  `message_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `messages_read` tinyint(1) NOT NULL DEFAULT '0',
  `message_group` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rowners`
--

CREATE TABLE IF NOT EXISTS `rowners` (
  `owner_id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_email` varchar(50) NOT NULL,
  `owner_username` varchar(20) NOT NULL,
  `owner_first_name` varchar(20) NOT NULL,
  `owner_last_name` varchar(20) NOT NULL,
  `owner_password` varchar(200) NOT NULL,
  `owner_salt` int(11) NOT NULL,
  `owner_country` int(11) NOT NULL,
  `owner_city` int(11) NOT NULL,
  `owner_post_code` varchar(10) NOT NULL,
  `owner_gender` int(11) NOT NULL,
  `owner_phone` varchar(15) NOT NULL,
  `owner_img_url` varchar(100) NOT NULL,
  `owner_birthday` date NOT NULL,
  PRIMARY KEY (`owner_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `rowners`
--

INSERT INTO `rowners` (`owner_id`, `owner_email`, `owner_username`, `owner_first_name`, `owner_last_name`, `owner_password`, `owner_salt`, `owner_country`, `owner_city`, `owner_post_code`, `owner_gender`, `owner_phone`, `owner_img_url`, `owner_birthday`) VALUES
(9, 'owner1@gmail.com', 'owner1', 'owner', 'first', '6d10617994cca2e9ec7efb14e3bd8947563444a6af56e9185c975a57d767287a', 1402851473, 0, 0, 'M9 13WJ', 0, '121412123123', '', '2003-09-12'),
(10, 'owner2@gmail.com', 'owner2', 'owner', '2', '8846ed52d8b5d3470409e86ec189c296e53780b0174e094495629f624b4efe33', 10902441, 0, 0, 'M9 13WJ', 0, '123234123312', '', '2013-02-04');

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
(12, 22, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(12, 23, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(12, 26, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(12, 27, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(12, 28, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(12, 49, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(12, 50, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(12, 54, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(22, 23, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(22, 26, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(22, 27, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(22, 28, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(22, 49, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(22, 50, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(22, 54, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(23, 26, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(23, 27, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(23, 28, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(23, 49, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(23, 50, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(23, 54, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(26, 27, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(26, 28, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(26, 49, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(26, 50, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(26, 54, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(27, 28, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(27, 49, 100, 0, 1, 0, 0, 0, 1, 10, 50, 1),
(27, 50, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(27, 54, 10, 0, 0, 0, 0, 0, 1, 10, 50, 1),
(28, 49, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(28, 50, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(28, 54, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(49, 50, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1),
(49, 54, 7, 0, 1, 0, 0, 1, 0, 20, 20, 1),
(50, 54, 20, 0, 0, 0, 0, 0, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rposts`
--

CREATE TABLE IF NOT EXISTS `rposts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_parent_id` int(11) NOT NULL,
  `post_text` text NOT NULL,
  `post_likes` varchar(1000) NOT NULL,
  `post_likes_no` int(11) NOT NULL DEFAULT '0',
  `post_date` date NOT NULL,
  `post_type` tinyint(4) NOT NULL,
  `post_author` int(11) NOT NULL,
  UNIQUE KEY `post_id` (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `rposts`
--

INSERT INTO `rposts` (`post_id`, `post_parent_id`, `post_text`, `post_likes`, `post_likes_no`, `post_date`, `post_type`, `post_author`) VALUES
(4, 1, 'heyyyyaa', '49', 1, '2015-03-18', 0, 49);

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
-- Table structure for table `rtempowners`
--

CREATE TABLE IF NOT EXISTS `rtempowners` (
  `temp_id` int(11) NOT NULL AUTO_INCREMENT,
  `temp_username` varchar(50) NOT NULL,
  `temp_email` varchar(100) NOT NULL,
  `temp_pass` varchar(200) NOT NULL,
  `temp_salt` int(11) NOT NULL,
  `conf` int(11) NOT NULL,
  `temp_details` varchar(1000) NOT NULL,
  PRIMARY KEY (`temp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

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
  `temp_details` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
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
(49, 1, 0, 1, 1, 0, 1, 1),
(50, 1, 0, 1, 1, 1, 1, 1),
(53, 1, 0, 1, 1, 1, 1, 1),
(54, 1, 1, 1, 0, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ruser_groups`
--

CREATE TABLE IF NOT EXISTS `ruser_groups` (
  `group_group_id` int(11) NOT NULL,
  `group_user_id` int(10) unsigned NOT NULL,
  `group_user_rank` varchar(10) NOT NULL DEFAULT 'member',
  PRIMARY KEY (`group_group_id`,`group_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ruser_groups`
--

INSERT INTO `ruser_groups` (`group_group_id`, `group_user_id`, `group_user_rank`) VALUES
(1, 12, 'member'),
(1, 22, 'member'),
(1, 49, 'member'),
(1, 54, 'member'),
(2, 22, 'member'),
(2, 49, 'member'),
(2, 54, 'member');

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
(27, '2:1,2:50', '', NULL),
(49, '2:2:10', '5:5:10', NULL),
(54, '2:1:10', '7:7,6,5:10', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
