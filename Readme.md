# BESEMS-ENROLLMENT

**Empowering Education Through Seamless Enrollment Innovation**

![last commit](https://img.shields.io/badge/last%20commit-today-blue) ![php](https://img.shields.io/badge/php-98.5%25-blue) ![languages](https://img.shields.io/badge/languages-3-blue)

### Built with the tools and technologies:
![JSON](https://img.shields.io/badge/JSON-black?logo=json) ![Composer](https://img.shields.io/badge/Composer-brown?logo=composer) ![PHP](https://img.shields.io/badge/PHP-777BB4?logo=php&logoColor=white)

---

## Table of Contents
* [Overview](#overview)
* [Getting Started](#getting-started)
    * [Prerequisites](#prerequisites)
    * [Installation](#installation)
    * [Usage](#usage)
    * [Testing](#testing)

---

## Overview

**besems-enrollment** is a feature-rich enrollment management system tailored for educational institutions, emphasizing modularity, security, and scalability. It provides a well-structured codebase with clear separation of concerns, making it easy for developers to extend and maintain.

### Why besems-enrollment?
This project aims to streamline school enrollment workflows through a robust, developer-centric architecture. The core features include:

* 🧩 🔧 **Modular Design:** Organized with MVC architecture, ensuring maintainability and scalability.
* 🚦 🔒 **Secure Authentication:** Handles user login, registration, and role-based access control seamlessly.
* 📊 📈 **Role-Based Dashboards:** Provides tailored interfaces for guardians and administrators with real-time insights.
* 📁 💾 **Data Management:** Efficient models and database schemas for users, students, and announcements.
* 🌐 📱 **Responsive UI Components:** Modular views and partials for a cohesive, mobile-friendly user experience.

---

## Getting Started

### Prerequisites
This project requires the following dependencies:
* **Programming Language:** PHP
* **Package Manager:** Composer
* **Local Server:** XAMPP (Apache & MySQL)

1. **Go to your XAMPP htdocs directory:**
   - Open File Explorer and navigate to `C:\xampp\htdocs` (or your XAMPP htdocs path).
2. **Open Command Prompt (CMD) in this directory:**
   - In the htdocs folder, hold Shift and right-click, then select "Open Command Window Here" or "Open PowerShell window here".
3. **Clone the repository:**
   ```bash
   git clone https://github.com/kelllgener/besems-enrollment
   ```
4. **Navigate to the project directory:**
   ```bash
   cd besems-enrollment
   ```
5. **Install the dependencies using Composer:**
   ```bash
   composer install
   ```

### Database Setup (XAMPP)
To set up the project database using XAMPP, follow these steps:

1. **Start XAMPP:** Open the XAMPP Control Panel and start the **Apache** and **MySQL** actions.
2. **Access phpMyAdmin:** Open your browser and go to `http://localhost/phpmyadmin/`.
3. **Create Database:** Click on **New** in the left sidebar and create a database named `enrollment_system`.
4. **Import SQL File:**
   - Select the `enrollment_system` database you just created.
   - Click the **Import** tab at the top.
   - Click **Choose File** and select the `enrollment_system.sql` file located in the project root.
   - Scroll down and click **Import** (or **Go**).
   - Alternatively, you can open the `.sql` file, copy the content, paste it into the **SQL** tab, and click **Go**.

### Installation
Build **besems-enrollment** from the source and install dependencies:

[⬆ Return](#besems-enrollment)
   