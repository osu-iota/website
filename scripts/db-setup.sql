CREATE TABLE IF NOT EXISTS iota_user
(
  uid        CHAR(15)    NOT NULL,
  u_name       VARCHAR(50) NOT NULL,
  u_onid       VARCHAR(20) NOT NULL,
  u_role       CHAR(15),
  u_last_login DATETIME,
  PRIMARY KEY (uid)
);

CREATE TABLE IF NOT EXISTS iota_event
(
  eid         CHAR(15)    NOT NULL,
  e_title       VARCHAR(50) NOT NULL,
  e_description TEXT        NOT NULL,
  e_date        DATETIME    NOT NULL,
  e_location    VARCHAR(200),
  e_sponsor     CHAR(15)    NOT NULL,
  PRIMARY KEY (eid),
  FOREIGN KEY (e_sponsor) REFERENCES iota_alliance_member (amid)
);

CREATE TABLE IF NOT EXISTS iota_alliance_member
(
  amid         CHAR(15)    NOT NULL,
  am_name        VARCHAR(50) NOT NULL,
  am_description TEXT        NOT NULL,
  am_url         VARCHAR(100),
  am_head        CHAR(15),
  PRIMARY KEY (amid),
  FOREIGN KEY (am_head) REFERENCES iota_user (uid)
);

CREATE TABLE IF NOT EXISTS iota_material
(
  mid         CHAR(15)     NOT NULL,
  m_name        VARCHAR(50)  NOT NULL,
  m_description TEXT,
  m_type        CHAR(15)     NOT NULL,
  m_file        VARCHAR(100) NOT NULL,
  PRIMARY KEY (mid),
  FOREIGN KEY (m_type) REFERENCES iota_material_type (mtid)
);

CREATE TABLE IF NOT EXISTS iota_material_type
(
  mtid CHAR(15) NOT NULL,
  mt_name VARCHAR(50),
  PRIMARY KEY (mtid)
);

CREATE TABLE IF NOT EXISTS iota_attends
(
  uid      CHAR(15)     NOT NULL,
  eid      CHAR(15)     NOT NULL,
  a_selfie   VARCHAR(100) NOT NULL,
  a_rating   FLOAT,
  a_comments TEXT,
  PRIMARY KEY (uid, eid),
  FOREIGN KEY (uid) REFERENCES iota_user (uid),
  FOREIGN KEY (eid) REFERENCES iota_event (eid)
);

CREATE TABLE IF NOT EXISTS iota_registers_for
(
  uid CHAR(15) NOT NULL,
  eid CHAR(15) NOT NULL,
  PRIMARY KEY (uid, eid),
  FOREIGN KEY (uid) REFERENCES iota_user (uid),
  FOREIGN KEY (eid) REFERENCES iota_event (eid)
);

CREATE TABLE IF NOT EXISTS iota_led_by
(
  eid CHAR(15) NOT NULL,
  uid CHAR(15) NOT NULL,
  PRIMARY KEY (eid, uid),
  FOREIGN KEY (eid) REFERENCES iota_event (eid),
  FOREIGN KEY (uid) REFERENCES iota_user (uid)
);

CREATE TABLE IF NOT EXISTS iota_contributes
(
  uid  CHAR(15)    NOT NULL,
  mid  VARCHAR(15) NOT NULL,
  c_date DATETIME    NOT NULL,
  PRIMARY KEY (uid, mid),
  FOREIGN KEY (uid) REFERENCES iota_user (uid),
  FOREIGN KEY (mid) REFERENCES iota_material (mid)
);

CREATE TABLE IF NOT EXISTS iota_resource_for (
  mid CHAR(15) NOT NULL,
  eid CHAR(15) NOT NULL,
  PRIMARY KEY (mid, eid),
  FOREIGN KEY (mid) REFERENCES iota_material(mid),
  FOREIGN KEY (eid) REFERENCES iota_event(eid)
);
