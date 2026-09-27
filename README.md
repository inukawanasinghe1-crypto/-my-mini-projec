# EduHub — University Study Note & Resource Hub

> **Rajarata University of Sri Lanka**  
> Faculty of Technology &bull; Department of ICT  
> **Course:** ICT 2209 – Web Technologies | Mini Project  
> **Author:** Individual Submission  

---

## 📌 1. Project Overview

**EduHub** is an interactive, full-stack academic resource and study note portal tailored for undergraduate students in the Department of ICT, Faculty of Technology, Rajarata University of Sri Lanka. 

The application facilitates peer-to-peer knowledge sharing by allowing students to discover, search, review, and upload lecture guides, lab sheet solutions, summaries, and exam past papers across university course modules (such as **ICT 2209 Web Technologies**, **ICT 2201 Data Structures**, **ICT 2203 Database Systems**, and **ICT 2104 Computer Networks**).

---

## 🚀 2. Key Features & Rubric Checklist

| Feature Category | Implemented Capabilities | Rubric Weight |
|---|---|---|
| **HTML5 & CSS3 Layout** | Modern Bootstrap 5.3 responsive grid, Glassmorphism navbar, custom Indigo/Emerald gradient theme, Google Fonts (`Outfit` & `Inter`), flexible card layouts, and complete cross-device mobile adaptation. | **20%** |
| **JavaScript Features** | 1. **Dynamic Content Updates:** Real-time keystroke live filtering across subjects, titles, and note summaries.<br>2. **Interactive Image Slider:** Auto/manual Bootstrap carousel with indicators and controls.<br>3. **Form Validation:** Real-time client-side regex validation on Registration, Contact, and Note creation.<br>4. **Dynamic Modal Viewer:** Instant DOM data extraction into note viewer modal with 1-click clipboard copy.<br>5. **Smooth Scrolling & Tooltips:** Smooth in-page navigation and auto-dismissing flash alerts. | **15%** |
| **Database Integration** | Normalized MySQL schema (`users`, `resources`, `messages`), PDO connection architecture, prepared statements to eliminate SQL injection vulnerabilities. | **20%** |
| **User Authentication** | Registration with duplicate username/email checks, password hashing via `password_hash()` (BCRYPT), secure session management with `password_verify()`, and clean session destruction in `logout.php`. | **20%** |
| **Contact Form & Inquiries** | Interactive inquiry portal with input validation, storing inquiries directly into the MySQL `messages` table with instant feedback alerts. | **10%** |
| **File Structure & Code Quality** | Clean modular separation (`includes/`, `auth/`, `css/`, `js/`), robust error handling, detailed code comments, and zero external NPM build dependencies. | **10%** |
| **Creativity & Originality** | Custom academic branding tailored to RUSL Faculty of Technology, pre-populated university modules, and an intuitive note viewer. | **5%** |

---

## 📂 3. Project Directory Architecture

```
project/
├── css/
│   └── style.css            # Custom CSS: Design tokens, glassmorphism, animations, cards
├── js/
│   └── main.js              # Vanilla JS: Live filter, client validation, modal viewer, tooltips
├── includes/
│   ├── db.php               # PDO database connection with setup diagnostic prompts
│   ├── functions.php        # Input sanitation, session guards, flash message system
│   ├── header.php           # Global responsive navigation, Google fonts, Bootstrap CDN
│   └── footer.php           # Global footer with university credits and scripts
├── auth/
│   ├── register.php         # User sign-up logic with BCRYPT hashing & session initiation
│   ├── login.php            # User authentication logic with password_verify()
│   └── logout.php           # Secure session destruction and cookie clearing
├── contact.php              # Inquiry form submitting to MySQL 'messages' table
├── index.php                # Landing page: Hero, interactive carousel, stats, recent notes
├── dashboard.php            # Main Resource Hub: Live search, category pills, add/delete note modal
├── database.sql             # Complete MySQL dump with schema and seed test data
└── README.md                # Comprehensive documentation and setup instructions
```

---

## 🛠️ 4. Technology Stack

- **Frontend:** HTML5, CSS3, Bootstrap 5.3.2, Bootstrap Icons, Google Fonts (`Outfit`, `Inter`)
- **Scripting:** Vanilla JavaScript (ES6+)
- **Backend:** PHP 7.4+ / PHP 8.x (Modular Architecture)
- **Database:** MySQL 5.7+ / MariaDB (via PDO with Prepared Statements)
- **Local Server Environment:** XAMPP, WAMP, or LAMP stack

---

## 💾 5. Local Setup & Installation Guide (XAMPP / WAMP)

Follow these steps to deploy and run the project locally on your machine:

### Step 1: Clone or Copy Project Files
Place the project folder into your local web server's document root:
- For **XAMPP (Windows):** `C:\xampp\htdocs\eduhub`
- For **WAMP (Windows):** `C:\wamp64\www\eduhub`

### Step 2: Start Apache and MySQL
1. Launch the **XAMPP Control Panel** (or WAMP).
2. Click **Start** next to **Apache**.
3. Click **Start** next to **MySQL**.

### Step 3: Import the MySQL Database
1. Open your web browser and navigate to:  
   👉 [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. In the left sidebar, click **New** to create a database.
3. Name the database: `eduhub_db` (Collation: `utf8mb4_unicode_ci`), then click **Create**.
4. Select the newly created `eduhub_db` database in the left sidebar.
5. Click on the **Import** tab at the top.
6. Click **Choose File** and select the `database.sql` file located in the root of this project.
7. Scroll to the bottom and click **Go** (or **Import**).  
   *All tables (`users`, `resources`, `messages`) and seed data will be created automatically.*

### Step 4: Open the Application
Open your browser and visit:  
👉 [http://localhost/eduhub](http://localhost/eduhub)

---

## 🔑 6. Default Demo Credentials

For examiner and evaluator convenience, pre-seeded student accounts are ready to use:

| Username | Email | Password | Role |
|---|---|---|---|
| `kamal_ict` | `kamal@rusl.ac.lk` | `password` | Contributor / Student |
| `nimali_tech` | `nimali@rusl.ac.lk` | `password` | Contributor / Student |

> *You can also create a new student account instantly via the **Sign Up** page (`auth/register.php`).*

---

## 🗄️ 7. Database Schema Details

### 1. `users` Table
- `id` (INT, Primary Key, Auto Increment)
- `username` (VARCHAR 50, Unique)
- `email` (VARCHAR 100, Unique)
- `password` (VARCHAR 255, BCRYPT Hash)
- `created_at` (DATETIME)

### 2. `resources` Table
- `id` (INT, Primary Key, Auto Increment)
- `subject_code` (VARCHAR 20, e.g. `ICT 2209`)
- `title` (VARCHAR 150)
- `category` (VARCHAR 50, e.g. `Lecture Notes`, `Past Papers`, `Lab Sheets`, `Summary`)
- `description` (TEXT, detailed note content)
- `user_id` (INT, Foreign Key referencing `users(id)` ON DELETE CASCADE)
- `created_at` (DATETIME)

### 3. `messages` Table
- `id` (INT, Primary Key, Auto Increment)
- `name` (VARCHAR 100)
- `email` (VARCHAR 100)
- `subject` (VARCHAR 150)
- `message` (TEXT)
- `created_at` (DATETIME)

---

## 🔒 8. Security & Code Integrity Highlights

- **SQL Injection Prevention:** 100% of database operations use PDO prepared statements with parameterized input bindings.
- **Cross-Site Scripting (XSS) Prevention:** All dynamic data rendered onto the DOM is sanitized using `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')`.
- **Password Security:** Student passwords are never stored in plaintext; they are hashed using PHP's standard `password_hash($pass, PASSWORD_BCRYPT)`.
- **Session Protection:** Session identifiers are regenerated upon login with `session_regenerate_id(true)` to mitigate session fixation attacks.

---

## 🎓 9. Academic Declaration
This project was developed strictly for academic purposes in partial fulfillment of the requirements for **ICT 2209: Web Technologies Mini Project** at the **Faculty of Technology, Rajarata University of Sri Lanka**.
