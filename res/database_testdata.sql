DELETE FROM overview;
INSERT INTO overview
	VALUES('0', '2015-03-15 07:14:07', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', 'Nisse');
INSERT INTO overview
	VALUES('1', '2015-04-15 06:14:07', 'Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.', 'Knut');

DELETE FROM system;
INSERT INTO system
	VALUES('0','1210','2015-09-15 03:14:07','1056 065-100 A','AHAB','HawkEyeIII','En kommentar','1310','1410','1510','1610','0','1','1','1','1','0','1','1');
INSERT INTO system
	VALUES('1','1211','2015-09-16 07:12:17','1056 065-101 A','Nisses Mekaniska','DualDragon','','1311','1411','1511','1611','1','1','1','1','1','1','1','1');

DELETE FROM sensor_unit;
INSERT INTO sensor_unit
	VALUES('0','1310','2015-01-15 03:14:07','3210','123-4567','1710','1810');
INSERT INTO sensor_unit
	VALUES('1','1311','2015-09-15 03:14:07','3211','123-4568','1711','1811');
INSERT INTO sensor_unit
	VALUES('2','1312','2015-04-15 03:14:07','3212','123-4569','1712','1812');
INSERT INTO sensor_unit
	VALUES('3','1313','2015-02-15 03:14:07','3213','123-4570','1713','1813');
INSERT INTO sensor_unit
	VALUES('4','1314','2015-06-15 03:14:07','3214','123-4571','1714','1814');

DELETE FROM control_unit;
INSERT INTO control_unit
	VALUES('0','1410','2015-09-14 03:14:07','3310','3410','3510','2010');
INSERT INTO control_unit
	VALUES('1','1411','2015-09-18 03:14:07','3311','3411','3511','2011');

DELETE FROM deep_system;
INSERT INTO deep_system
	VALUES('0','1510','2015-09-15 03:16:07','3110','3610','123-5567','1910');
INSERT INTO deep_system
	VALUES('1','1511','2015-09-15 13:11:07','3111','3611','123-5568','1911');
INSERT INTO deep_system
	VALUES('2','1512','2015-09-15 13:12:07','3112','3612','123-5569','1912');

DELETE FROM scu;
INSERT INTO scu
	VALUES('0','2010','2014-09-15 03:16:07','HawkEyeIII','5510','5610','','','2.0','OK');
INSERT INTO scu
	VALUES('1','2017','2015-07-15 03:16:07','DualDragon','5517','5617','','','1.7','Test');

DELETE FROM sensor;
INSERT INTO sensor
	VALUES('0','1710','2014-07-15 01:41:07','topo','','','','','','','','','','45','55','15','15','OK');
INSERT INTO sensor
	VALUES('1','1810','2015-08-15 08:44:07','shallow','','','','','','','','','','35','35','16','16','OK');
INSERT INTO sensor
	VALUES('2','1910','2015-09-15 08:21:07','deep','','','','','','','','','','35','35','16','16','OK');
INSERT INTO sensor
	VALUES('3','1811','2015-07-15 08:14:07','shallow','','','','','','','','','','35','35','16','16','OK');
INSERT INTO sensor
	VALUES('4','1812','2015-04-15 08:16:07','shallow','','','','','','','','','','35','35','16','16','OK');
INSERT INTO sensor
	VALUES('5','1817','2015-06-15 08:16:07','shallow','','','','','','','','','','35','35','16','16','OK');
INSERT INTO sensor
	VALUES('6','1911','2014-07-15 08:15:07','deep','','','','','','','','','','35','35','16','16','OK');