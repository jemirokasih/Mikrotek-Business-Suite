<img align="right" alt="Mikrotek Invoice logo" src="/assets/core/img/favicon.png">

# Mikrotek Invoice `v1.1.1`

_An enhanced, open-source invoicing and business management platform built on top of [InvoicePlane](https://www.invoiceplane.com/)._

---

## 📌 About Mikrotek Invoice

**Mikrotek Invoice** is a customized distribution of [InvoicePlane](https://invoiceplane.com/), tailored for business operations needing dynamic proforma invoicing, multiple client contacts (PIC), reference numbers, project integrations, and custom PDF outputs with Indonesian localized formatting (Terbilang, manual/digital signatures, etc.).

> **Credit & Attribution:** This application is powered by **InvoicePlane** (licensed under the MIT License). We extend our deepest gratitude to the original InvoicePlane team and open-source contributors.

---

## ✨ Features & Extensions in v1.1.1

- 📄 **Dynamic Proforma & Invoice Switching**:
  - Automatically switches labels between `#PROFORMA INVOICE` / `Proforma Invoice Number` and `#INVOICE` / `Invoice Number` in UI & PDF depending on the `is_proforma` flag.
  - Smooth mPDF header replacement without label collision (`strtr`).

- 👥 **Multiple PIC (Person In Charge) Management**:
  - Full CRUD functionality for managing multiple contact persons per client (`ip_client_pics`).
  - Interactive AJAX modal dialog for adding/editing PIC info (name, position, email, phone, notes).
  - Direct mailto links and contact overview embedded in client view.

- 🔢 **Invoice Reference Number**:
  - Dedicated `invoice_reference_number` field in database, models, AJAX save payload, and UI.
  - Automatically rendered under Due Date on both web UI and PDF output.

- 📁 **Project Linkage (Optional)**:
  - Link Invoices and Quotes directly to Projects via optional dropdown selector.
  - Renders linked Project name on both Invoice and Quote PDF documents.

- 🎨 **Mikrotek PDF Template & Layout Polish**:
  - Custom PDF template (`Mikrotek.php`) with dynamic Terbilang ("In Words") box placed cleanly above payment terms.
  - Manual signature space (with materai/stamp area) and Digital signature toggle.
  - Subtotal grid alignment adjustments ensuring clean 4-column output without right-side overflow.

- 🔗 **QR Code & Public Link Accessibility**:
  - Direct access to guest views via unique `url_key` for quick QR code verification.

- 🏷️ **Application Version Footer**:
  - Integrated footer: `Mikrotek Invoice v1.1.1 · Powered by InvoicePlane`.

---

## 🚀 Quick Start & Installation

### 1. Requirements
- PHP 8.2+ with `mysqli`, `gd`, `json`, `mbstring`, `openssl`, `xml`, `zip` extensions.
- MySQL / MariaDB database.
- Web Server (Apache with `mod_rewrite` / Nginx).

### 2. Setup Instructions
1. Clone the repository:
   ```bash
   git clone https://github.com/InvoicePlane/InvoicePlane.git mikrotek-invoice
   cd mikrotek-invoice
   ```
2. Copy configuration file:
   ```bash
   cp ipconfig.php.example ipconfig.php
   ```
3. Edit `ipconfig.php` with your database credentials (`DB_HOSTNAME`, `DB_USERNAME`, `DB_PASSWORD`, `DB_DATABASE`) and site URL (`IP_URL`).
4. Access `http://your-domain.com/index.php/setup` in your browser to complete installation/migration.

---

## 📜 Changelog

All notable changes and release notes are recorded in [CHANGELOG.md](.github/CHANGELOG.md).

---

## ⚖️ License & Credits

- **Mikrotek Invoice** is licensed under the [MIT License](LICENSE.txt).
- **Powered by InvoicePlane**: Copyright (c) InvoicePlane Developers & Contributors.
- For original InvoicePlane documentation and community support, visit [invoiceplane.com](https://invoiceplane.com).
