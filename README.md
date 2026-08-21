<img align="right" alt="Mikrotek Business Suite logo" src="/assets/core/img/favicon.png">

# Mikrotek Business Suite `v1.7.4`

_An enhanced, open-source business operations, invoicing, multi-company, employee management, attendance tracking, and role-based platform built on top of [InvoicePlane](https://www.invoiceplane.com/)._

---

## 📌 About Mikrotek Business Suite

**Mikrotek Business Suite** is a customized distribution of [InvoicePlane](https://invoiceplane.com/), tailored for comprehensive business operations. It expands standard invoicing capabilities with custom Role-Based Access Control (RBAC), multi-company management, Employee/HR management, Attendance & Geolocation Clock-In/Out tracking, Indonesian receipt/kwitansi generation with auto-terbilang, dynamic proforma invoicing, multiple client contacts (PIC), bank account management, project integrations, and custom PDF outputs.

> **Credit & Attribution:** This application is powered by **InvoicePlane** (licensed under the MIT License). We extend our deepest gratitude to the original InvoicePlane team and open-source contributors.

---

## ✨ Features & Extensions in v1.4.0

- ⏱️ **Attendance & Clock-In/Out Module (`attendance`)**:
  - **Employee Self-Service Clock Portal (`attendance/clock`)**: 1-Click Clock In / Clock Out with real-time **HTML5 GPS Geolocation & IP Address tracking**.
  - **Admin Daily Attendance Dashboard (`attendance/index`)**: Real-time KPI summary cards (Present, Late, Absent, Leave/Sick), date filtering, and employee status tracking.
  - **Admin Manual Attendance**: Manual attendance entry & adjustment modal for managers (`is_manual`).
  - **Monthly Attendance Reports (`attendance/report`)**: Exportable/printable monthly summary per employee.

- 👥 **Employee / HR Management Module (`employees`)**:
  - Employee master data (Personal, Contact, Employment, and Bank/Payroll details).
  - Single-page stacked panel form layout for seamless user experience.
  - Auto-generated employee numbers (`EMP-0001`).
  - **On-Demand User Account Provisioning & Auto-Link**: Optionally create or automatically link existing `ip_users` accounts by email.

- 🔐 **Role & Multi-User Access Control (RBAC)**:
  - Custom roles with modular permissions matrix (Invoices, Quotes, Clients, Payments, Products, Projects, Receipts, Reports, Settings, Users, Roles).
  - Staff / Custom Role dashboard & navigation routing.

- 🏢 **Multi-Company Management**:
  - Centralized master company records (`ip_companies`).
  - Company-scoped user assignments and default company auto-seeding.

- 🧾 **Indonesian Kwitansi / Receipt Module**:
  - Integrated kwitansi generation directly from Invoices & Payments.
  - Automatic **Terbilang** conversion (e.g. *Satu Juta Lima Ratus Ribu Rupiah*).
  - Indonesian standardized PDF receipt layout with company letterhead, signature space, and verification QR code.

- 📄 **Dynamic Proforma & Invoice Switching**:
  - Automatically switches labels between `#PROFORMA INVOICE` / `Proforma Invoice Number` and `#INVOICE` / `Invoice Number` in UI & PDF depending on the `is_proforma` flag.
  - Smooth 1-click conversion from Proforma to Official Invoice.

- 👥 **Multiple PIC (Person In Charge) Management**:
  - Full CRUD functionality for managing multiple contact persons per client (`ip_client_pics`).
  - Interactive AJAX modal dialog for adding/editing PIC info.

- 🏦 **Multi-Bank Account Management**:
  - Manage company bank accounts (`ip_bank_accounts`) linked to payment methods.

- 📁 **Project Linkage & Custom PDF Templates**:
  - Link Invoices and Quotes directly to Projects.
  - Custom PDF templates (`Mikrotek.php`) with Terbilang box, digital/manual signatures, and sanitized HTML headers.

- 🏷️ **Application Footer**:
  - Integrated footer: `Mikrotek Business Suite v1.2.0 · Powered by InvoicePlane`.

---

## 🚀 Quick Start & Installation

### 1. Requirements
- PHP 8.2+ with `mysqli`, `gd`, `json`, `mbstring`, `openssl`, `xml`, `zip` extensions.
- MySQL / MariaDB database.
- Web Server (Apache with `mod_rewrite` / Nginx).

### 2. Setup Instructions
1. Clone the repository:
   ```bash
   git clone https://github.com/jemirokasih/Mikrotek-Invoice.git
   cd Mikrotek-Invoice
   ```
2. Copy configuration file:
   ```bash
   cp ipconfig.php.example ipconfig.php
   ```
3. Edit `ipconfig.php` with your database credentials (`DB_HOSTNAME`, `DB_USERNAME`, `DB_PASSWORD`, `DB_DATABASE`) and site URL (`IP_URL`).
4. Access `http://your-domain.com/index.php/setup` in your browser to complete installation/migration.

---

## 📜 Changelog

All notable changes and release notes are recorded in [MIKROTEK_CHANGELOG.md](MIKROTEK_CHANGELOG.md) and [.github/CHANGELOG.md](.github/CHANGELOG.md).

---

## ⚖️ License & Credits

- **Mikrotek Business Suite** is licensed under the [MIT License](LICENSE.txt).
- **Powered by InvoicePlane**: Copyright (c) InvoicePlane Developers & Contributors.
- For original InvoicePlane documentation and community support, visit [invoiceplane.com](https://invoiceplane.com).
