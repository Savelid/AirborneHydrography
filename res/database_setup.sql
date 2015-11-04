CREATE TABLE log (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	datetime TIMESTAMP,

	type VARCHAR(40),
	user VARCHAR(40),
	sql_string TEXT,
	serial_nr VARCHAR(30),

	PRIMARY KEY (id)
);

CREATE TABLE overview (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	datetime TIMESTAMP,

	message TEXT,
	author VARCHAR(40),

	PRIMARY KEY (id)
);

CREATE TABLE system (

	id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(10) NOT NULL,
	datetime TIMESTAMP,

	art_nr VARCHAR(20),
	client VARCHAR(50),
	configuration VARCHAR(50),
	comment TEXT,
	sensor_unit_sn VARCHAR(10),
	-- foreign key & Relatinship
	control_unit_sn VARCHAR(10),
	-- foreign key & Relatinship
	deep_system_sn VARCHAR(10),
	-- foreign key & Relatinship
	cooling_system VARCHAR(10),

	status_potta_heat BOOLEAN,
	status_shallow_heat BOOLEAN,
	status_scu_pdu BOOLEAN,
	status_hv_topo BOOLEAN,
	status_hv_shallow BOOLEAN,
	status_hv_deep BOOLEAN,
	status_cat BOOLEAN,
	status_pwr_cable BOOLEAN,

	PRIMARY KEY (id)
);

CREATE TABLE sensor_unit (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(10) NOT NULL,
	datetime TIMESTAMP,

	imu VARCHAR(10),
	leica_cam_sn VARCHAR(10),
	-- foreign key & Relatinship
	topo_sensor_sn VARCHAR(10),
	-- foreign key & Relatinship
	shallow_sensor_sn VARCHAR(10),
	-- foreign key & Relatinship

	PRIMARY KEY (id)
);

CREATE TABLE control_unit (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(10) NOT NULL,
	datetime TIMESTAMP,

	battery VARCHAR(10),
	cc32 VARCHAR(10),
	pdu VARCHAR(10),
	scu_sn VARCHAR(10),
	-- foreign key & Relatinship

	PRIMARY KEY (id)
);

CREATE TABLE deep_system (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(10) NOT NULL,
	datetime TIMESTAMP,

	control_system VARCHAR(10),
	imu VARCHAR(10),
	pro_pack VARCHAR(10),
	deep_sensor_sn VARCHAR(10),
	-- foreign key & Relatinship

	PRIMARY KEY (id)
);

CREATE TABLE scu (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(10) NOT NULL,
	datetime TIMESTAMP,

	configuration VARCHAR(20),
	digitaizer1 VARCHAR(10),
	digitaizer2 VARCHAR(10),
	sat VARCHAR(10),
	cpu VARCHAR(10),
	version VARCHAR(20),
	status VARCHAR(30),

	PRIMARY KEY (id)
);

CREATE TABLE sensor (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(10) NOT NULL,
	datetime TIMESTAMP,
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

CREATE TABLE hv_card (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(10) NOT NULL,
	datetime TIMESTAMP,

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

CREATE TABLE laser (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(10) NOT NULL,
	datetime TIMESTAMP,

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

CREATE TABLE leica_cam (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(10) NOT NULL,
	datetime TIMESTAMP,

	configuration VARCHAR(20),
	breakdown INTEGER,
	operating_voltage INTEGER,

	PRIMARY KEY (id)
);

CREATE TABLE receiver_chip (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(10) NOT NULL,
	datetime TIMESTAMP,

	unit VARCHAR(30),
	firmware VARCHAR(10),
	art_nr VARCHAR(20),

	PRIMARY KEY (id)
);