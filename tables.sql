CREATE TABLE IF NOT EXISTS account (
  id INT NOT NULL AUTO_INCREMENT,
  username VARCHAR(200) NOT NULL,
  password VARCHAR(200) CHARSET utf8 COLLATE utf8_bin NOT NULL,
  is_active TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  UNIQUE KEY (username)
) ENGINE=InnoDB DEFAULT CHARSET utf8 COLLATE utf8_general_ci;

# slug is only unique for a particular author
CREATE TABLE IF NOT EXISTS story (
  id INT NOT NULL AUTO_INCREMENT,
  account_id INT NOT NULL,
  slug VARCHAR(200) NOT NULL,
  title VARCHAR(200) NOT NULL,
  description TEXT,
  PRIMARY KEY (id),
  FOREIGN KEY (account_id) REFERENCES account(id) ON DELETE CASCADE
) ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS passage (
  id INT NOT NULL AUTO_INCREMENT,
  story_id INT NOT NULL,
  title VARCHAR(200) NOT NULL,
  passage TEXT,
  PRIMARY KEY (id),
  FOREIGN KEY (story_id) REFERENCES story(id) ON DELETE CASCADE
) ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

# destinationid is the passageid that this links to
CREATE TABLE IF NOT EXISTS link (
  id INT NOT NULL AUTO_INCREMENT,
  passage_id INT NOT NULL,
  choice TEXT NULL,
  destination_id INT,
  PRIMARY KEY (id),
  FOREIGN KEY (passage_id) REFERENCES passage(id) ON DELETE CASCADE
) ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

insert into account (id, username, password) values (1,'rickjolly@hotmail.com','password');
insert into story (id, account_id, slug, title, description) values (1,1,'first-title','First Title','First Description');