-- =======================
-- Table: participants
-- =======================

-- Create participants table
CREATE TABLE participants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Insert initial participants
INSERT INTO participants (name) VALUES
('Alice'),
('Bob'),
('Charles'),
('Sam');

-- =================
-- Table: judges
-- =================

-- Create judges table
CREATE TABLE judges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    display_name VARCHAR(100) NOT NULL
);

-- =================
-- Table: scores
-- =================

-- Create scores table
CREATE TABLE scores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judge_id INT NOT NULL,
    participant_id INT NOT NULL,
    score INT NOT NULL CHECK(score >= 0 AND score <= 100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (judge_id) REFERENCES judges(id),
    FOREIGN KEY (participant_id) REFERENCES participants(id)
);

-- Ensure that each judge can only submit one score per participant
ALTER TABLE scores ADD CONSTRAINT unique_judge_participant UNIQUE (judge_id, participant_id);