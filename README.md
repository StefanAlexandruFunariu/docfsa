# 🏫 School Management System – Web-Based Academic DMS

![PHP](https://img.shields.io/badge/PHP-7.x-blue)
![Database](https://img.shields.io/badge/Database-MariaDB-lightblue)
![Status](https://img.shields.io/badge/Project-Finished-success)
![Updated](https://img.shields.io/badge/Last%20Updated-Mar%202024-orange)

---

## 📌 Project Overview

This project is a **Document Management System (DMS)** designed to streamline the operations of academic institutions. It focuses on managing academic years, storing documents, and providing role-based access for faculty, students, and administrators.

The system was built for a real-world academic need and simplifies document workflows, improves access to information, and provides a clean user interface for day-to-day school operations.

---

## 🎯 Objectives

- Centralize document management for academic institutions
- Provide secure access for admins, faculty, and students
- Track academic years, files, and user interactions
- Replace inefficient paper-based systems

---

## ✨ Features

### ✅ User Roles & Authentication
- Admins, Faculty, and Students with separate permissions
- Custom dashboards based on user role

### ✅ Document Management
- Upload and organize academic and administrative documents
- Integrated **WYSIWYG editor** (jQuery-TE) for formatting
- Support for inline **PDF previews** via PDF.js

### ✅ Academic Year Tracking
- Dedicated SQL structure for academic sessions (e.g. 2021–2022)
- Toggle status of each year (active/inactive)

### ✅ UI Components
- Date picker, color picker, and clean visual interface
- Responsive layout built using modular asset structure

---

## 🛠️ Technologies Used

- **Backend:** PHP 7.x
- **Database:** MariaDB / MySQL
- **Frontend:** HTML5, CSS3, JavaScript
- **Libraries:**
  - jQuery
  - jQuery-TE (editor)
  - PDF.js
  - DatePicker
  - ColorPicker
- **Server:** Apache (supports `.htaccess`)

---

## 📂 Project Structure

```
school-management-system/
├── .htaccess                            # Server config
├── default.php                          # Main entry point
├── school_management_system.sql         # Database schema
├── assets/
│   └── vendors/
│       ├── editor/                      # jQuery-TE WYSIWYG
│       ├── pdfjs/                       # PDF preview
│       ├── datepicker/                 # Calendar input
│       └── colorpicker/                # Color theme selector
```

---

## 🧪 How It Works

1. **Users log in** using their assigned role credentials
2. **Dashboard shows** role-specific views and actions
3. **Documents can be added**, categorized, and searched
4. **Admins manage** academic years and configure platform settings

---

## ⚙️ Installation

1. Clone this repository:
   ```bash
   git clone https://github.com/your-username/school-management-system.git
   ```

2. Import the SQL database:
   ```sql
   source school_management_system.sql;
   ```

3. Configure your PHP DB connection:
   - Edit credentials in `default.php` or your config file

4. Serve locally:
   ```bash
   php -S localhost:8000
   ```

5. Or use Apache and access via:
   ```
   http://localhost/school-management-system/
   ```

---

## 🧱 Database Highlight: `academic_years`

| Column         | Type      | Description                        |
|----------------|-----------|------------------------------------|
| `id`           | INT       | Primary Key                        |
| `session_year` | VARCHAR   | Academic Year (e.g. 2021–2022)     |
| `start_year`   | YEAR      | Session Start                      |
| `end_year`     | YEAR      | Session End                        |
| `is_running`   | TINYINT   | 1 = Active, 0 = Inactive           |
| `created_at`   | TIMESTAMP | Creation Time                      |
| `modified_at`  | TIMESTAMP | Last Update                        |

---

## 🔮 Future Improvements

- Add user registration with email verification
- Document version control and rollback
- Mobile-friendly UI and dashboard charts
- Third-party integrations (e.g. Google Drive, Firebase)
- Analytics module for admin usage

---

## 👤 Author

**Stefan Alexandru Funariu**  
📧 [alexfunariu01@gmail.com](mailto:alexfunariu01@gmail.com)  
🔗 [LinkedIn Profile](https://www.linkedin.com/in/stefan-alexandru-funariu/)  
💻 [GitHub Portfolio](https://github.com/stefanf02)

---

> Built for academic impact and real-world efficiency 🎓
