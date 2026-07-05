# Kasirku - Modern Point of Sale (POS) Application

Kasirku is a responsive, modern Point of Sale (POS) cashier web application, designed with a clean interface and a bright blue color scheme.

## Key Features
1. **Analytical Dashboard**: Equipped with KPI widgets (Total Sales, Transactions, Product Count) and monthly revenue trend charts powered by Chart.js.
2. **Product Management (CRUD)**: Manage barcode data, names, categories, stock, and product prices. Supports direct scanning from barcode scanner devices into the add/edit product form input fields.
3. **Cashier POS Terminal**:
   - Manual product search with autocomplete (live search) based on product name or barcode.
   - Physical barcode scanner listener running automatically in the background (HID keyboard emulation).
   - Instant change calculator with quick-cash options.
   - Instant 58mm/80mm thermal receipt printing using the operating system's default printer.
4. **Sales Reports**: Daily reports (transaction/invoice details) and monthly reports (daily accumulation table) optimized for paper/PDF printing.
5. **Profile Management**: Secure settings for full name, email, and admin password changes.

## Tech Stack
* **Backend**: Laravel 11 / 13
* **Frontend**: HTML5, Javascript, Tailwind CSS v4, Alpine.js, Lucide Icons
* **Database**: SQLite (default) / MySQL
* **Charts**: Chart.js


## Local Installation Guide

1. Clone this repository to your local machine.
2. Open a terminal in the project directory and run:
   ```bash
   composer install
   npm install
   ```
3. Copy the `.env.example` file to `.env` and adjust the configuration if necessary.
4. Run database migrations and seed initial demo data:
   ```bash
   php artisan migrate --seed
   ```
5. Compile frontend assets:
   ```bash
   npm run build
   ```
6. Run the local development server:
   ```bash
   php artisan serve
   ```
   Access the web application at `http://127.0.0.1:8000/login`.
