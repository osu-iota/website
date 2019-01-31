CREATE TABLE IF NOT EXISTS iota_user
(
  uid               CHAR(15)    NOT NULL,
  u_onid            VARCHAR(20) NOT NULL,
  u_privilege_level SMALLINT    NOT NULL DEFAULT 0,
  u_last_login      DATETIME,
  PRIMARY KEY (uid)
);

CREATE TABLE IF NOT EXISTS iota_resource
(
  rid           CHAR(15)     NOT NULL,
  r_name        VARCHAR(50)  NOT NULL,
  r_description TEXT,
  r_topic       VARCHAR(20)  NOT NULL,
  PRIMARY KEY (rid)
);

CREATE TABLE IF NOT EXISTS iota_resource_data
(
  rdid         CHAR(15) NOT NULL,
  rid          CHAR(15) NOT NULL,
  rd_data      BLOB     NOT NULL,
  rd_extension CHAR(5)  NOT NULL,
  rd_date      DATETIME NOT NULL,
  rd_downloads INT      NOT NULL DEFAULT 0,
  PRIMARY KEY (rdid),
  FOREIGN KEY (rid) REFERENCES iota_resource (rid)
);

CREATE TABLE IF NOT EXISTS iota_participates
(
  pid           CHAR(15)    NOT NULL,
  uid           CHAR(15)    NOT NULL,
  p_type        VARCHAR(20) NOT NULL,
  p_club        VARCHAR(20) NOT NULL,
  p_description TEXT        NOT NULL,
  p_data        CHAR(15),
  PRIMARY KEY (pid),
  FOREIGN KEY (uid) REFERENCES iota_user (uid),
  FOREIGN KEY (p_data) REFERENCES iota_participate_data (pdid)
);

CREATE TABLE IF NOT EXISTS iota_participate_data
(
  pdid         CHAR(15) NOT NULL,
  pd_data      BLOB     NOT NULL,
  pd_extension CHAR(5)  NOT NULL,
  PRIMARY KEY (pdid)
);

CREATE TABLE IF NOT EXISTS iota_contributes
(
  uid     CHAR(15)    NOT NULL,
  rid     VARCHAR(15) NOT NULL,
  cn_date DATETIME    NOT NULL,
  PRIMARY KEY (uid, rid),
  FOREIGN KEY (uid) REFERENCES iota_user (uid),
  FOREIGN KEY (rid) REFERENCES iota_resource (rid)
);


