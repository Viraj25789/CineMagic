# Movie Ticket Booking System (PHP + MySQL + Bootstrap)

Simple, clean, and **easy-to-understand** project inspired by TicketNew/BookMyShow.

## Features
- Movie list & details
- Upcoming shows per movie
- Seat selection with live availability
- Checkout & mock payment
- Booking confirmation page + "My Bookings"
- Admin: login, manage movies, screens (with auto seat generation), shows
- Secure basics: prepared statements, password hashing, CSRF token, transactions, unique constraint to prevent double booking

## Requirements
- PHP 8+
- MySQL 8+ (or MariaDB 10.4+)
- Web server (Apache/Nginx). For quick testing, you can use `php -S localhost:8000`

## Setup
1. Create DB and tables:
   - Import `db.sql` into MySQL (use phpMyAdmin or `mysql -u root -p < db.sql`).
2. Configure DB credentials in `config.php` if needed.
3. Run the app:
   - From this folder: `php -S localhost:8000`
   - Visit `http://localhost:8000/`
4. Admin panel:
   - `http://localhost:8000/admin/admin_login.php`
   - **Email:** `admin@example.com`  **Password:** `admin123`

## Notes
- This demo uses a very simple mock payment. Replace it with a real gateway for production.
- To add more screens, use the Admin > Screens page. Seat grid rows/cols will be generated for you.
- The system prevents double booking via a unique key on `(show_id, seat_id)` and wraps checkout in a DB transaction.

## Project Structure
```
/ (public root)
  config.php
  helpers.php
  header.php / footer.php
  index.php, movie.php, register.php, login.php, logout.php, my_bookings.php
  select_seats.php, checkout.php, confirm.php, success.php
  /admin
    admin_login.php, dashboard.php
    movies.php, screens.php, shows.php
  db.sql
```

## License
MIT – free to use for learning and projects.
