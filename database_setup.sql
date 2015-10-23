DROP TABLE overview;
CREATE TABLE overview (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	datetime TIMESTAMP,

	message TEXT,
	author VARCHAR(40),

	PRIMARY KEY (id)
);

DROP TABLE system;
CREATE TABLE system (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(10) NOT NULL,
	datetime TIMESTAMP,

	art_nr VARCHAR(20),
	client VARCHAR(50),
	configuration VARCHAR(50),
	comment TEXT,
	system_status_sn INTEGER,
	-- foreign key & Relatinship
	sensor_unit_sn VARCHAR(10),
	-- foreign key & Relatinship
	control_unit_sn VARCHAR(10),
	-- foreign key & Relatinship
	deep_system_sn VARCHAR(10),
	-- foreign key & Relatinship
	cooling_system VARCHAR(10),

	PRIMARY KEY (id),
	FOREIGN KEY (system_status_sn) REFERENCES system_status(id)
	FOREIGN KEY (sensor_unit_sn) REFERENCES sensor_unit(serial_nr)
	FOREIGN KEY (control_unit_sn) REFERENCES control_unit(serial_nr)
	FOREIGN KEY (deep_system_sn) REFERENCES deep_system(serial_nr)
);

DROP TABLE system_status;
CREATE TABLE system_status (

	id INTEGER NOT NULL,

	potta_heat BOOLEAN,
	shallow_heat BOOLEAN,
	-- TODO: add rest

	PRIMARY KEY (id)
);

DROP TABLE sensor_unit;
CREATE TABLE sensor_unit (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(10) NOT NULL,

	imu VARCHAR(10),
	leica_cam_sn VARCHAR(10),
	-- foreign key & Relatinship
	topo_sensor_sn VARCHAR(10),
	-- foreign key & Relatinship
	shallow_sensor_sn VARCHAR(10),
	-- foreign key & Relatinship

	PRIMARY KEY (id)
);
DROP TABLE control_unit;
CREATE TABLE control_unit (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(10) NOT NULL,

	battery VARCHAR(10),
	cc32 VARCHAR(10),
	pdu VARCHAR(10),
	scu_sn VARCHAR(10),
	-- foreign key & Relatinship

	PRIMARY KEY (id)
);

DROP TABLE deep_system;
CREATE TABLE deep_system (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(10) NOT NULL,

	control_system VARCHAR(10),
	imu VARCHAR(10),
	pro_pack VARCHAR(10),
	deep_sensor_sn VARCHAR(10),
	-- foreign key & Relatinship

	PRIMARY KEY (id)
);

DROP TABLE scu;
CREATE TABLE scu (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(10) NOT NULL,

	configuration VARCHAR(20),
	digitaizer1 VARCHAR(10),
	digitaizer2 VARCHAR(10),
	sat VARCHAR(10),
	cpu VARCHAR(10),
	version VARCHAR(20),
	status VARCHAR(30),

	PRIMARY KEY (id)
);

DROP TABLE sensor;
CREATE TABLE sensor (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(10) NOT NULL,
	sensor_type ENUM('topo', 'shallow', 'deep'),

	cat VARCHAR(10),
	fpga_id VARCHAR(10),
	laser_sn VARCHAR(10),
	-- foreign key & Relatinship
	hv_card_sn VARCHAR(10),
	-- foreign key & Relatinship
	receiver_unit VARCHAR(10),
	receiver_chip_sn VARCHAR(10),
	-- foreign key & Relatinship
	hv_card_2_sn VARCHAR(10),
	-- foreign key & Relatinship
	receiver_unit_2 VARCHAR(10),
	receiver_chip_2_sn VARCHAR(10),
	-- foreign key & Relatinship
	dps_value_input_offset_t0 INTEGER,
	dps_value_input_offset_rec INTEGER,
	dps_value_pulse_width_t0 INTEGER,
	dps_value_pulse_width_rec INTEGER,
	status VARCHAR(30),

	PRIMARY KEY (id)
);

DROP TABLE hv_card;
CREATE TABLE hv_card (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(10) NOT NULL,

	configuration VARCHAR(20),
	art_nr VARCHAR(20),
	k_value FLOAT,
	m_value FLOAT,
	v_0 FLOAT,
	v_500 FLOAT,
	v_1000 FLOAT,
	v_1500 FLOAT,
	v_2000 FLOAT,
	v_2500 FLOAT,
	v_3000 FLOAT,
	v_3250 FLOAT,

	PRIMARY KEY (id)
);

DROP TABLE laser;
CREATE TABLE laser (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(10) NOT NULL,

	v_0 FLOAT,
	v_5 FLOAT,
	v_10 FLOAT,
	v_15 FLOAT,
	v_20 FLOAT,
	v_25 FLOAT,
	v_30 FLOAT,
	v_40 FLOAT,
	v_50 FLOAT,
	v_60 FLOAT,
	v_70 FLOAT,
	v_80 FLOAT,
	v_90 FLOAT,
	v_100 FLOAT,

	PRIMARY KEY (id)
);

DROP TABLE leica_cam;
CREATE TABLE leica_cam (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(10) NOT NULL,

	configuration VARCHAR(20),
	breakdown INTEGER,
	operating_voltage INTEGER,

	PRIMARY KEY (id)
);

DROP TABLE receiver_chip;
CREATE TABLE receiver_chip (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(10) NOT NULL,

	unit VARCHAR(30),
	firmware VARCHAR(10),
	art_nr VARCHAR(20),

	PRIMARY KEY (id)
);