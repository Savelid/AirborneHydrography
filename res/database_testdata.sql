DELETE FROM overview;
INSERT INTO overview
	VALUES('0', '2015-03-15 07:14:07', 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.', 'Nisse');
INSERT INTO overview
	VALUES('1', '2015-04-15 06:14:07', 'Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.', 'Knut');

DELETE FROM system;
INSERT INTO system
	VALUES('0','1210','2015-09-15 03:14:07','1056 065-100 A','AHAB', 'Home', 'HawkEyeIII','Service','En kommentar','1310','1410','1510');
INSERT INTO system
	VALUES('1','1211','2015-09-16 07:12:17','1056 065-101 A','Nisses Mekaniska', 'Piteå', 'DualDragon','Done','Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.','1311','1411','1511');

DELETE FROM system_status;
INSERT INTO system_status
	VALUES('0','1210','0','1','1','1','1','0','1','1');
INSERT INTO system_status
	VALUES('1','1211','1','1','1','1','1','1','1','1');

DELETE FROM sensor_unit;
INSERT INTO sensor_unit
	VALUES('0','1310','2015-01-15 03:14:07','3210','123-4567','123-4577','1710','1810');
INSERT INTO sensor_unit
	VALUES('1','1311','2015-09-15 03:14:07','3211','123-4568','123-4578','1711','1811');
INSERT INTO sensor_unit
	VALUES('2','1312','2015-04-15 03:14:07','3212','123-4569','123-4579','1712','1812');
INSERT INTO sensor_unit
	VALUES('3','1313','2015-02-15 03:14:07','3213','123-4570','123-4580','1713','1813');
INSERT INTO sensor_unit
	VALUES('4','1314','2015-06-15 03:14:07','3214','123-4571','123-4581','1714','1814');

DELETE FROM control_system;
INSERT INTO control_system
	VALUES('0','1410','2015-09-14 03:14:07','3310','3410','3510','2010');
INSERT INTO control_system
	VALUES('1','1411','2015-09-18 03:14:07','3311','3411','3511','2011');

DELETE FROM deep_system;
INSERT INTO deep_system
	VALUES('0','1510','2015-09-15 03:16:07','1610','3610','123-5567','1910');
INSERT INTO deep_system
	VALUES('1','1511','2015-09-15 13:11:07','1611','3611','123-5568','1911');
INSERT INTO deep_system
	VALUES('2','1512','2015-09-15 13:12:07','1612','3612','123-5569','1912');

DELETE FROM scu;
INSERT INTO scu
	VALUES('0','2010','2014-09-15 03:16:07','HawkEyeIII','5510','5610','','','2.0','Done');
INSERT INTO scu
	VALUES('1','2011','2015-07-15 03:16:07','DualDragon','5511','5611','','','1.7','Test');
INSERT INTO scu
	VALUES('2','2017','2011-08-15 03:16:07','DualDragon','5517','5617','','','1.7','Test');

DELETE FROM sensor;
INSERT INTO sensor
	VALUES('0','1710','2014-07-15 01:41:07','topo','0910','0810','4110','4010','0710','123-5510','4020','0720','123-5520','45','55','15','15','Done');
INSERT INTO sensor
	VALUES('1','1810','2015-08-15 08:44:07','shallow','','','','','','','','','','35','35','16','16','Done');
INSERT INTO sensor
	VALUES('2','1910','2015-09-15 08:21:07','deep','','','','','','','','','','35','35','16','16','Done');
INSERT INTO sensor
	VALUES('3','1811','2015-07-15 08:14:07','shallow','','','','','','','','','','35','35','16','16','Done');
INSERT INTO sensor
	VALUES('4','1812','2015-04-15 08:16:07','shallow','','','','','','','','','','35','35','16','16','Done');
INSERT INTO sensor
	VALUES('5','1817','2015-06-15 08:16:07','shallow','','','','','','','','','','35','35','16','16','Done');
INSERT INTO sensor
	VALUES('6','1911','2014-07-15 08:15:07','deep','','','','','','','','','','35','35','16','16','Done');

DELETE FROM hv_card;
INSERT INTO hv_card
	VALUES('0','4010','2013-07-25 08:15:07','HawkEyeIII','1056 065-400 A','1.41','21.2','','','','','','','','');
INSERT INTO hv_card
	VALUES('1','4011','2013-07-25 08:25:07','DualDragon','1056 065-401 A','','','2.3','3.1','5.3','6.1','7.4','8.5','9.3','10.3');
INSERT INTO hv_card
	VALUES('2','4017','2013-07-25 07:25:07','DualDragon','1056 065-407 A','1.10','21.1','','','','','','','','');

DELETE FROM laser;
INSERT INTO laser
	VALUES ('0','4110','2013-07-01 01:15:07','9.1','8.2','7.3','6.2','5.3','4.4','3.7','2.9','2.4','1.9','1.6','1.4','1.1','0.9');
INSERT INTO laser
	VALUES ('1','4120','2014-07-01 02:15:07','9.1','8.2','7.3','6.2','5.3','4.4','3.7','2.9','2.4','1.9','1.6','1.4','1.1','0.9');
INSERT INTO laser
	VALUES ('2','4111','2013-07-01 02:15:07','9.1','8.2','7.3','6.2','5.3','4.4','3.7','2.9','2.4','1.9','1.6','1.4','1.1','0.9');
INSERT INTO laser
	VALUES ('3','4112','2013-08-01 02:15:07','9.1','8.2','7.3','6.2','5.3','4.4','3.7','2.9','2.4','1.9','1.6','1.4','1.1','0.9');

DELETE FROM leica_cam;
INSERT INTO leica_cam
	VALUES ('0','123-4567','2013-07-11 01:15:45','HawkEyeIII','32','24V');
INSERT INTO leica_cam
	VALUES ('1','123-4568','2013-07-11 01:15:11','DualDragon','32','24V');
INSERT INTO leica_cam
	VALUES ('2','123-4573','2013-07-11 01:24:07','HawkEyeIII','32','24V');

DELETE FROM receiver_chip;
INSERT INTO receiver_chip
	VALUES ('0','123-5510','2013-07-11 01:33:15','unit','1.4.1','1056 065-100 A');
INSERT INTO receiver_chip
	VALUES ('1','123-5511','2013-07-11 01:43:15','unit','1.4.1','1056 065-101 A');
INSERT INTO receiver_chip
	VALUES ('2','123-5517','2013-07-11 01:42:15','unit','1.4.1','1056 065-107 A');