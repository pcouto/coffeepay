# Host: localhost  (Version 5.6.21)
# Date: 2017-11-01 12:19:50
# Generator: MySQL-Front 6.0  (Build 1.172)


#
# Structure for table "login_attempts"
#

DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
  `user_id` int(11) NOT NULL,
  `time` varchar(30) COLLATE latin1_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

#
# Data for table "login_attempts"
#


#
# Structure for table "datos"
#

DROP TABLE IF EXISTS `datos`;
CREATE TABLE `datos` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `Password` varchar(128) COLLATE latin1_spanish_ci DEFAULT NULL,
  `Email` varchar(50) COLLATE latin1_spanish_ci DEFAULT NULL,
  `Salt` varchar(128) COLLATE latin1_spanish_ci DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_spanish_ci;

#
# Data for table "datos"
#

INSERT INTO `datos` VALUES (1,'mainuser','.Asarjira5555','mainuser@gmail.com',NULL),(2,'pepe','52b414e7d1c269cb8d4b314116c49de38b6ffa9158a9c88935c84bdb50c954f63d556e3111985699708ccbbb824259b0b7257ef328a34a3f470d188ae439fb48','pepe@pepe.com','e2c314e328841bc7ae689329b0af5d00ee8b12bacc0f2c14c01c18ce6adabaae25079472fc0762bb9217559493694c83230c3695a7d4d3d56a256d2b0e48d08f'),(3,'pepe2','8dae7077b62c430c332f9ac1949c518fee342059df559fc426efcb8dc44b289ad7d7ae78c0e3633a025399282fa644238f5686f57dc616ad87e441fde64a8831','pepe2@pepe.com','e8c00a824ec9cee7d10784bbca5a96bbeb432aacae1d81981e53115d03b0aae2d14d8e6867ba02e88b5fa69e0b86577cdef03e12769cce6193098e13c5d4d8da');
