
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;




CREATE TABLE `access_log` (
  `log_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `date_time` datetime NOT NULL,
  `session_ID` varchar(32) NOT NULL,
  `IP_Address` varchar(32) NOT NULL,
  `hostname` varchar(50) NOT NULL,
  `schedule` text NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=2546 ;



CREATE TABLE `Bells` (
  `Type` varchar(1) NOT NULL,
  `Day` varchar(3) NOT NULL,
  `Period` varchar(1) NOT NULL,
  `Start` time NOT NULL,
  `End` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



CREATE TABLE `Days` (
  `Date` date NOT NULL,
  `Day` varchar(3) NOT NULL,
  `Type` varchar(1) NOT NULL,
  PRIMARY KEY (`Date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
