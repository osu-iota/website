CREATE TABLE IF NOT EXISTS iota_user
(
  uid        CHAR(15)    NOT NULL,
  name       VARCHAR(50) NOT NULL,
  onid       VARCHAR(20) NOT NULL,
  role       CHAR(15),
  last_login DATETIME,
  PRIMARY KEY (uid)
);

CREATE TABLE IF NOT EXISTS iota_event
(
  eid         CHAR(15)    NOT NULL,
  title       VARCHAR(50) NOT NULL,
  description TEXT        NOT NULL,
  date        DATETIME    NOT NULL,
  location    VARCHAR(200),
  sponsor     CHAR(15)    NOT NULL,
  PRIMARY KEY (eid),
  FOREIGN KEY (sponsor) REFERENCES iota_alliance_member (aid)
);

CREATE TABLE IF NOT EXISTS iota_alliance_member
(
  aid         CHAR(15)    NOT NULL,
  name        VARCHAR(50) NOT NULL,
  description TEXT        NOT NULL,
  url         VARCHAR(100),
  head        CHAR(15),
  PRIMARY KEY (aid),
  FOREIGN KEY (head) REFERENCES iota_user (uid)
);

CREATE TABLE IF NOT EXISTS iota_material
(
  mid         CHAR(15)     NOT NULL,
  name        VARCHAR(50)  NOT NULL,
  description TEXT,
  type        CHAR(15)     NOT NULL,
  file        VARCHAR(100) NOT NULL,
  PRIMARY KEY (mid),
  FOREIGN KEY (type) REFERENCES iota_material_type (mtid)
);

CREATE TABLE IF NOT EXISTS iota_material_type
(
  mtid CHAR(15) NOT NULL,
  name VARCHAR(50),
  PRIMARY KEY (mtid)
);

CREATE TABLE IF NOT EXISTS iota_attends
(
  uid      CHAR(15)     NOT NULL,
  eid      CHAR(15)     NOT NULL,
  selfie   VARCHAR(100) NOT NULL,
  rating   FLOAT,
  comments TEXT,
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
  date DATETIME    NOT NULL,
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
