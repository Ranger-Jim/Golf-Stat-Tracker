USE golf_stats;

DROP TABLE IF EXISTS holestats;
DROP TABLE IF EXISTS rounds;
DROP TABLE IF EXISTS holes;
DROP TABLE IF EXISTS teesets;
DROP TABLE IF EXISTS courses;

CREATE TABLE Courses (
	course_id INT AUTO_INCREMENT PRIMARY KEY,
    external_id VARCHAR(50) NULL,
    course_name VARCHAR(255) NOT NULL,
    club_name VARCHAR(255) NULL,
    city VARCHAR(100) NULL,
    state VARCHAR(50) NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE TeeSets (
	tee_set_id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    tee_name VARCHAR(50) NOT NULL,
    gender ENUM('male', 'female') NULL,
    total_yardage INT NULL,
    total_par INT NULL,
    par3_count INT NULL,
    FOREIGN KEY (course_id) REFERENCES Courses(course_id) ON DELETE CASCADE
);

CREATE TABLE Holes (
	hole_id INT AUTO_INCREMENT PRIMARY KEY,
    tee_set_id INT NOT NULL,
    hole_number INT NOT NULL,
    par INT NOT NULL,
    yardage INT NOT NULL,
    FOREIGN KEY (tee_set_id) REFERENCES TeeSets(tee_set_id) ON DELETE CASCADE,
    UNIQUE KEY unique_hole (tee_set_id, hole_number)
);

CREATE TABLE Rounds (
	round_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tee_set_id INT NOT NULL,
    date DATE NOT NULL,
    total_score INT NOT NULL,
    total_fairways_hit INT NOT NULL,
    total_gir INT NOT NULL,
    total_putts INT NOT NULL,
    FOREIGN KEY(user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY(tee_set_id) REFERENCES teesets(tee_set_id)
);

CREATE TABLE HoleStats (
	hole_stat_id INT AUTO_INCREMENT PRIMARY KEY,
    round_id INT NOT NULL,
    hole_number INT NOT NULL,
    score INT NOT NULL,
    fairways_hit TINYINT NULL,
    gir TINYINT NOT NULL,
    putts INT NOT NULL,
    FOREIGN KEY(round_id) REFERENCES Rounds(round_id) ON DELETE CASCADE,
    UNIQUE KEY unique_round_hole (round_id, hole_number)
);