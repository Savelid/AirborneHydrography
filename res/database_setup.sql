CREATE TABLE log (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	datetime TIMESTAMP,

	type VARCHAR(100),
	user VARCHAR(100),
	sql_string TEXT,
	serial_nr VARCHAR(30),
	comment TEXT,

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
	serial_nr VARCHAR(30) NOT NULL,
	datetime TIMESTAMP,

	art_nr VARCHAR(30),
	client VARCHAR(100),
	place VARCHAR(100),
	configuration VARCHAR(30),
	status VARCHAR(100),
	comment TEXT,
	sensor_unit_sn VARCHAR(30),
	control_system_sn VARCHAR(30),
	deep_system_sn VARCHAR(30),
	pd60 VARCHAR(30),
	oc60_1_sn VARCHAR(30),
	oc60_2_sn VARCHAR(30),
	pav_sn VARCHAR(30),

	oc FLOAT,
	bitfile_topo VARCHAR(30),
	bitfile_shallow VARCHAR(30),
	bitfile_deep VARCHAR(30),
	bitfile_digitaizer1 VARCHAR(30),
	bitfile_digitaizer2 VARCHAR(30),
	bitfile_sat VARCHAR(30),

	PRIMARY KEY (id)
);

CREATE TABLE system_status (

	id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(30) NOT NULL,

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
	serial_nr VARCHAR(30) NOT NULL,
	datetime TIMESTAMP,

	imu VARCHAR(30),
	leica_cam_sn VARCHAR(30),
	leica_lens VARCHAR(30),
	topo_sensor_sn VARCHAR(30),
	topo_sensor_2_sn VARCHAR(30),
	shallow_sensor_sn VARCHAR(30),
	comment TEXT,

	PRIMARY KEY (id)
);

CREATE TABLE control_system (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(30) NOT NULL,
	datetime TIMESTAMP,

	battery VARCHAR(30),
	cc32_sn VARCHAR(30),
	pdu VARCHAR(30),
	scu_sn VARCHAR(30),
	comment TEXT,

	PRIMARY KEY (id)
);

CREATE TABLE deep_system (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(30) NOT NULL,
	datetime TIMESTAMP,

	cooling_system VARCHAR(30),
	imu VARCHAR(30),
	pro_pack VARCHAR(30),
	deep_sensor_sn VARCHAR(30),
	comment TEXT,

	PRIMARY KEY (id)
);

CREATE TABLE scu (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(30) NOT NULL,
	datetime TIMESTAMP,

	configuration VARCHAR(30),
	digitaizer1 VARCHAR(30),
	digitaizer2 VARCHAR(30),
	sat VARCHAR(30),
	cpu VARCHAR(30),
	comment TEXT,
	status VARCHAR(100),

	PRIMARY KEY (id)
);

CREATE TABLE sensor (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(30) NOT NULL,
	datetime TIMESTAMP,
	sensor_type ENUM('topo', 'shallow', 'deep'),

	cat VARCHAR(30),
	fpga_id VARCHAR(30),
	laser_sn VARCHAR(30),
	hv_card_sn VARCHAR(30),
	receiver_unit_sn VARCHAR(30),
	hv_card_2_sn VARCHAR(30),
	receiver_unit_2_sn VARCHAR(30),
	dps_value_input_offset_t0 INTEGER,
	dps_value_input_offset_rec INTEGER,
	dps_value_pulse_width_t0 INTEGER,
	dps_value_pulse_width_rec INTEGER,
	status VARCHAR(100),

	PRIMARY KEY (id)
);

CREATE TABLE hv_card (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(30) NOT NULL,
	datetime TIMESTAMP,

	art_nr VARCHAR(30),
	k_value FLOAT,
	m_value FLOAT,

	PRIMARY KEY (id)
);

CREATE TABLE laser (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(30) NOT NULL,
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

CREATE TABLE receiver_unit (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(30) NOT NULL UNIQUE,
	datetime TIMESTAMP,

	art_nr VARCHAR(30),
	receiver_chip_sn VARCHAR(30),

	PRIMARY KEY (id)
);

CREATE TABLE receiver_chip (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(30) NOT NULL UNIQUE,
	datetime TIMESTAMP,

	breakdown_voltage INTEGER,
	operating_voltage INTEGER,

	PRIMARY KEY (id)
);

CREATE TABLE leica (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	serial_nr VARCHAR(30) NOT NULL UNIQUE,
	datetime TIMESTAMP,

	type ENUM('Camera', 'OC60', 'CC32', 'PAV'),
	firmware VARCHAR(30),

	PRIMARY KEY (id)
);

CREATE TABLE flight (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	datetime TIMESTAMP,

	dataset_id VARCHAR(100),
	location VARCHAR(100),
	system_id VARCHAR(30),
	system_model VARCHAR(30),
	topo_sensor_1_sn VARCHAR(30),
	topo_sensor_2_sn VARCHAR(30),
	shallow_sensor_sn VARCHAR(30),
	deep_sensor_sn VARCHAR(30),
	scu_sn VARCHAR(30),
	imu_1_sn VARCHAR(30),
	imu_2_sn VARCHAR(30),

	ranging TEXT,
	type_of_data VARCHAR(100),
	purpose_of_flight TEXT,
	evaluation_of_flight TEXT,
	flight_logs TEXT,
	data_evaluation TEXT,

	nav_data_processing_log BOOLEAN,
	calibration_file ENUM('None', 'Final', 'Temporary'),
	processing_settings_file BOOLEAN,
	configuration_file BOOLEAN,
	calibration_report BOOLEAN,
	acceptance_report BOOLEAN,
	system_fully_functional BOOLEAN,
	raw_data_in_archive BOOLEAN,
	raw_data_in_back_up_archive BOOLEAN,

	PRIMARY KEY (id)
);

CREATE TABLE isp (

	id SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
	isp_nr VARCHAR(50) NOT NULL UNIQUE,
	datetime TIMESTAMP,

	product VARCHAR(100),
	amount INTEGER,
	value VARCHAR(100),
	receiver VARCHAR(100),
	country VARCHAR(30),
	code VARCHAR(30),
	comment TEXT,

	PRIMARY KEY (id)
);
