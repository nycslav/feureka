# FEUreka

FEUreka is a web-based Lost and Found Management System developed for the FEU Institute of Technology community. The system provides a centralized platform where students and staff can report found or missing items, browse administrator-approved found item listings, search and filter records by category, and view complete item information. Administrators review submissions, manage item statuses, archive completed records, and manage user accounts through a dedicated administrative dashboard.

The system serves as an information and reporting platform only. Ownership verification and item claiming are handled physically through the FEU Institute of Technology Lost and Found Office.

---

## Features

### User Features

- User Registration (Student / Staff)
- Secure Login and Logout
- User Profile Management
- Browse Approved Found Items
- Search Found Items
- Filter Found Items by Category
- View Item Details
- Report Found Items
- Report Missing Items

### Administrator Features

- Dashboard
- Review Pending Found Item Reports
- Approve / Reject Found Item Reports
- Review Missing Item Reports
- Update Item Status
- Archive Records
- User Management
- Staff Account Deletion

### System Features

- MySQL Relational Database
- Password Hashing
- Session Management
- Prepared SQL Statements
- Student Account Expiration
- Staff Account Management
- Role-Based Access Control
- Shared Backend Functions
- Modular Project Architecture

---

## Technologies Used

- PHP
- MySQL / MariaDB
- HTML5
- CSS3
- JavaScript
- XAMPP (Apache + MySQL)
- Git / GitHub

---

## Project Structure

```text
feureka/
├── assets/
├── config/
├── includes/
├── views/
├── actions/
├── database/
├── README.md
└── index.php
```

---

## Setup Instructions

### 1. Download the Project ZIP

1. Download `feureka.zip`.
2. Extract the ZIP file.
3. Open the extracted `feureka` project folder.

### 2. Create the database

Create a database named:

```text
feureka
```

### 3. Import the database schema

```bash
mysql -u root feureka < database/feureka.sql
```

### 4. Populate the database

```bash
mysql -u root feureka < database/seed.sql
```

### 5. Place the project inside your web server

For XAMPP:

```text
htdocs/feureka
```

### 6. Start Apache and MySQL

Open XAMPP Control Panel and start:

- Apache
- MySQL

### 7. Open the application

```text
http://localhost/feureka/
```

---

## Demo Accounts

### Administrator

Email:

```text
mark.andaya.admin@fit.edu.ph
```

Password:

```text
Admin1@123
```

---

### Student User

Email:

```text
celina.espinola@fit.edu.ph
```

Password:

```text
Student1@123
```

---

### Staff User

Email:

```text
mark.delacruz@fit.edu.ph
```

Password:

```text
Staff1@123
```

---

## User Roles

### User

Users can:

- Browse approved found items
- Search items
- Filter items by category
- View item details
- Report found items
- Report missing items
- Manage their own profile

Student accounts automatically expire based on the expected completion of the fourth academic year.

Staff accounts remain active until manually removed by an administrator.

---

### Administrator

Administrators can:

- Review found item submissions
- Approve or reject reports
- Review missing reports
- Update item statuses
- Archive completed records
- Manage registered users
- Remove inactive staff accounts

---

## Database

The system consists of four main tables:

- users
- found_items
- missing_reports
- categories

The database uses foreign key constraints and `ON DELETE SET NULL` relationships to preserve historical records when user accounts are removed.

---

## Development Team

| Member | Role |
|--------|------|
| Lady Antonette Villorente | Project Lead & Backend Systems Architect |
| Celina Mae Espinola | Authentication & User Management Developer |
| Mark Jastin Andaya | Frontend/UI Developer |
| Syril Marie Celis | Reporting Module Developer |
| Ezekiel Marfil | Administrator Systems Developer |

---

## License

This project was developed as a final project for:

**CCS0043 – Application Development and Emerging Technologies**

FEU Institute of Technology

For educational purposes only.