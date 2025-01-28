# docfsa

## Document Management System (DMS) for Academic Institutions

## Overview

This project is a **Document Management System (DMS)** developed specifically for the needs of an academic institution, tailored to streamline the management of documents such as student records, administrative paperwork, and academic files. It was initially built for my former university, where I identified inefficiencies in how documents were stored, accessed, and managed. The system provides a centralized platform for managing documents, improving productivity and reducing errors.

## Features

- **User Roles and Permissions:**
  - Admins can manage users and oversee all documents.
  - Faculty can upload, access, and organize academic documents.
  - Students can access specific documents shared with them.
- **Academic Year Management:**
  - A dedicated `academic_years` table in the database manages academic years (e.g., "2018-2019"), along with their statuses and metadata.
- **Document Categorization:**
  - Organize files by categories, such as coursework, administrative, or research documents.
- **Search Functionality:**
  - Quickly find documents using keywords, tags, or metadata.
- **Version Control:**
  - Track changes to documents and maintain version history.
- **Secure Access:**
  - Implements authentication and role-based access control (RBAC).
- **Notifications:**
  - Users are notified of new document uploads or updates relevant to them.

## Technologies Used

- **Backend:** Developed with PHP.
- **Database:** MariaDB for document storage and metadata (SQL dump provided).
- **Web Server:** Apache (configuration managed via `.htaccess`).
- **Frontend:** Utilizes PHP templates for dynamic content rendering.

## How It Works

1. **User Login:**
   - Users log in with their credentials, and access is granted based on their role.
2. **Dashboard:**
   - Users are presented with a personalized dashboard displaying their recent documents and tasks.
3. **Document Management:**
   - Documents can be uploaded, categorized, and shared based on user roles.
4. **Academic Year Management:**
   - Administrators can configure and manage academic years through the `academic_years` table.
5. **Search and Access:**
   - Users can search for specific documents using advanced filters.
6. **Notifications:**
   - Email or in-app notifications keep users informed about changes or additions.

## Installation and Setup

1. Clone the repository from GitHub:
   ```bash
   git clone https://github.com/username/document-management-system.git
   ```
2. Navigate to the project directory:
   ```bash
   cd document-management-system
   ```
3. Set up the database:
   - Import the provided SQL file into your MariaDB instance:
     ```sql
     source docfsa.sql;
     ```
   - Update the database connection details in the PHP configuration file.
4. Configure your web server:
   - Ensure Apache is set up to handle `.htaccess` files.
5. Start the application:
   ```bash
   php -S localhost:8000
   ```
6. Access the application at `http://localhost:8000` (or appropriate port).

## Database Structure

One of the key tables, `academic_years`, manages academic years efficiently:

| Column        | Type         | Description                                     |
|---------------|--------------|-------------------------------------------------|
| `id`          | INT          | Primary key.                                   |
| `session_year`| VARCHAR      | Academic year range (e.g., "2018-2019").      |
| `start_year`  | YEAR         | Start year.                                    |
| `end_year`    | YEAR         | End year.                                      |
| `is_running`  | TINYINT      | Indicates if the academic year is active.      |
| `created_at`  | TIMESTAMP    | Timestamp of record creation.                  |
| `modified_at` | TIMESTAMP    | Timestamp of last modification.                |

## Why This Project Matters

This system was developed to address real-world challenges faced by academic institutions in managing documents efficiently. By automating repetitive tasks, providing secure access, and ensuring document traceability, it significantly reduces administrative workload and enhances productivity.

## Future Improvements

- Integration with third-party tools (e.g., Google Drive, OneDrive).
- Advanced analytics for document usage.
- Support for multi-language interfaces.
- Mobile app development for on-the-go access.

## Contact

If you have questions or would like to collaborate on this project, feel free to reach out:

- **Email:** [alexfunariu01@gmail.com]
- **LinkedIn:** [https://www.linkedin.com/in/stefan-alexandru-funariu/]

---

Feel free to explore the codebase, and thank you for your interest in this project!

