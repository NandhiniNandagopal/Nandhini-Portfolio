# Nandhini Nandagopal — Portfolio Website
CS2301 · Internet Programming Activity

A dynamic personal portfolio built with HTML, CSS, JavaScript, PHP, and MySQL.

## Tech used
- **HTML5** — page structure (`index.html`, `about.html`, `academics.html`, `skills.html`, `projects.html`, `internships.html`, `achievements.html`, `contact.html`)
- **CSS3** — `assets/css/style.css` (soft-pastel design system: lilac / sage / peach on warm ivory, Fraunces + Manrope type)
- **JavaScript** — `assets/js/script.js` (mobile nav, scroll-reveal, client-side form validation, AJAX submit)
- **PHP** — `php/config.php`, `php/contact.php`, and the `admin/` folder
- **MySQL** — `database.sql`

## Dynamic feature (what makes this "dynamic")
The **Contact** page form does real form processing + database connectivity:
1. JavaScript validates the fields in the browser.
2. On submit, it sends the data via `fetch()` to `php/contact.php`.
3. PHP re-validates server-side and inserts the message into the `messages` table in MySQL using PDO.
4. The page shows a success/error message without reloading.

On top of that, there's a small **admin login + dashboard** (`admin/`) that reads the stored
messages back out of MySQL — this covers "user login" and "data management" as well.

## How to run it locally (XAMPP / WAMP / MAMP)

1. **Copy the project folder** into your server's web root, e.g.
   `C:\xampp\htdocs\portfolio\` (Windows) or `/Applications/MAMP/htdocs/portfolio/` (Mac).

2. **Start Apache and MySQL** from the XAMPP/WAMP control panel.

3. **Create the database.** Open phpMyAdmin (`http://localhost/phpmyadmin`), go to the
   **Import** tab, and import `database.sql`. This creates the `portfolio_db` database with
   the `messages` and `admins` tables.
   (Or from a terminal: `mysql -u root -p < database.sql`)

4. **Check your DB credentials** in `php/config.php`. The defaults
   (`host: localhost`, `user: root`, `password: ""`) match a fresh XAMPP install — change
   them if your MySQL setup is different.

5. **Create the admin account.** Visit `http://localhost/portfolio/admin/setup.php` once
   in your browser, choose a username and password, and submit. This page locks itself
   after the first admin is created — you can delete `admin/setup.php` afterwards.

6. **Open the site**: `http://localhost/portfolio/index.html`

7. **Try the contact form** on the Contact page, then log in at
   `http://localhost/portfolio/admin/login.php` to see the message you just sent.

## Folder structure
```
portfolio/
├── index.html            Home
├── about.html             About
├── academics.html         Academic details + certifications
├── skills.html            Skills
├── projects.html          Projects
├── internships.html       Internships (timeline)
├── achievements.html      Achievements
├── contact.html           Contact form (dynamic)
├── assets/
│   ├── css/style.css
│   ├── js/script.js
│   └── img/
├── php/
│   ├── config.php         MySQL connection (PDO)
│   └── contact.php        Validates + stores contact messages
├── admin/
│   ├── setup.php          One-time: create the admin account
│   ├── login.php          Admin login (session-based)
│   ├── dashboard.php      View stored messages (protected)
│   └── logout.php
└── database.sql           Schema: messages + admins tables
```

## Notes for submission
- Take screenshots of each page (Home, About, Academics, Skills, Projects, Internships,
  Achievements, Contact) plus the admin login/dashboard, and of phpMyAdmin showing the
  `messages` table with a submitted entry — this demonstrates the database connectivity.
- Update the social links, phone number, and photo (`assets/img/`) if you'd like to swap
  out the initials placeholder for a real photo.
