CREATE TABLE IF NOT EXISTS iota_user
(
  uid               CHAR(15)    NOT NULL,
  u_onid            VARCHAR(20) NOT NULL UNIQUE,
  u_privilege_level SMALLINT    NOT NULL DEFAULT 0,
  u_last_login      INT,
  PRIMARY KEY (uid)
);

CREATE INDEX iota_user_u_onid USING HASH ON iota_user (u_onid);

CREATE TABLE IF NOT EXISTS iota_resource
(
  rid           CHAR(15)    NOT NULL,
  r_name        VARCHAR(50) NOT NULL,
  r_description TEXT,
  PRIMARY KEY (rid)
);

CREATE TABLE IF NOT EXISTS iota_resource_data
(
  rdid         CHAR(15)    NOT NULL,
  rid          CHAR(15)    NOT NULL,
  rd_extension CHAR(5)     NOT NULL,
  rd_mime      VARCHAR(50) NOT NULL,
  rd_date      INT         NOT NULL,
  rd_downloads INT         NOT NULL DEFAULT 0,
  rd_active    BOOL        NOT NULL,
  PRIMARY KEY (rdid),
  FOREIGN KEY (rid) REFERENCES iota_resource (rid)
);

CREATE TABLE IF NOT EXISTS iota_resource_topic
(
  rtid    CHAR(15)    NOT NULL,
  rt_name VARCHAR(100) NOT NULL,
  PRIMARY KEY (rtid)
);

CREATE TABLE IF NOT EXISTS iota_resource_for
(
  rid  CHAR(15) NOT NULL,
  rtid CHAR(15) NOT NULL,
  PRIMARY KEY (rid, rtid),
  FOREIGN KEY (rid) REFERENCES iota_resource (rid),
  FOREIGN KEY (rtid) REFERENCES iota_resource_topic (rtid)
);

CREATE TABLE IF NOT EXISTS iota_participates_data
(
  pdid         CHAR(15) NOT NULL,
  pd_extension CHAR(5)  NOT NULL,
  pd_mime      CHAR(20) NOT NULL,
  PRIMARY KEY (pdid)
);

CREATE TABLE IF NOT EXISTS iota_participates
(
  pid           CHAR(15)    NOT NULL,
  uid           CHAR(15)    NOT NULL,
  p_name        VARCHAR(50),
  p_type        VARCHAR(20) NOT NULL,
  p_club        VARCHAR(20),
  p_description TEXT        NOT NULL,
  p_data        CHAR(15),
  PRIMARY KEY (pid),
  FOREIGN KEY (uid) REFERENCES iota_user (uid),
  FOREIGN KEY (p_data) REFERENCES iota_participates_data (pdid)
);

CREATE TABLE IF NOT EXISTS iota_contributes
(
  uid     CHAR(15) NOT NULL,
  rdid    CHAR(15) NOT NULL,
  cn_date INT      NOT NULL,
  PRIMARY KEY (uid, rdid),
  FOREIGN KEY (uid) REFERENCES iota_user (uid),
  FOREIGN KEY (rdid) REFERENCES iota_resource_data (rdid)
);


