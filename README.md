# LAMP Stack Scoring Application

A minimal scoring web application built using the **LAMP** stack (Linux, Apache, MySQL, PHP). It enables predefined judges to submit scores for participants, with a public-facing scoreboard that updates dynamically.

---

## Features

- **Admin Panel**: Add new judges with unique usernames and their names.
- **Judge Portal**: Select a participant and submit a score (1–100).
- **Public Scoreboard**: Displays ranked participant scores with live updates.
- **Responsive UI**: Optimized for mobile and desktop.

---

## Setup Instructions

### Prerequisites

- LAMP stack (Linux, Apache, MySQL, PHP)
  - Local setup via XAMPP, WAMP, or Docker
- PHP 7.0+ and MySQL
- Browser and text editor

### Installation Steps

1. **Clone or Download the Repository**
   - git clone https://github.com/annmulwa/scoreboard.git

2. **Move Project to Web Directory**
    - For XAMPP: Move to htdocs/
    - For Linux: Move to /var/www/html/

3. **Import Database**
    - Access phpMyAdmin or MySQL CLI
    - Create a new database named:
        - CREATE DATABASE scoring_system;
    - Import the provided schema (database.sql) or use the SQL above.

4. **Configure Database Connection**

5. **Start LAMP Services**
    - Use your control panel (XAMPP, WAMP) or run:
        - sudo service apache2 start
        - sudo service mysql start

6. **Access the App**
    - **Admin Panel**: http://localhost/admin.php
    - **Judge Panel**: http://localhost/judge_portal.php
    - **Scoreboard**: http://localhost/scoreboard.php

---

## Database Schema

-- Create participants table
CREATE TABLE participants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

-- Create judges table
CREATE TABLE judges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    display_name VARCHAR(100) NOT NULL
);

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

---

## Assumptions Made

    - Judges are manually added by an admin (no self-registration).

    - Authentication is not implemented (for simplicity).

    - Each judge can score each participant only once.

    - Participants are predefined for this demo.

    - Scores are immutable once submitted.

    - The scoreboard updates via periodic AJAX polling.

---

## Design Choices

### Database Design

    - **Normalization**: Judges, participants, and scores are stored in separate tables for scalability and clarity.

    - **Foreign Keys**: Used to maintain relational integrity.

    - **Score Constraints**: Ensures scores remain within a valid range (1–100).

### PHP Code

    - **Procedural PHP with mysqli**: Chosen for simplicity and compatibility with shared hosting.

    - **Modular Files**: Each page handles one responsibility (admin, judge, scoreboard).

    - **Sanitization**: Basic input validation used to prevent SQL injection (more security can be added).

### Frontend

    - **Responsive Design**: Media queries used to ensure usability across devices.

    - **Live Updates**: JavaScript setInterval() used to refresh scoreboard without full reloads.

---

## Potential Features (If More Time Were Available)

    - Login system for admins and judges

    - Allow judges to edit or delete previous scores

    - Add categories or scoring criteria (e.g., "Creativity", "Execution")

    - Admin dashboard for editing participants and scores
    