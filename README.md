# CineMagic — Movie Ticket Booking System

CineMagic is a PHP and MySQL movie-ticket booking application. Customers can browse movies and showtimes, select seats, and place demo bookings. Administrators manage movies, screens, and shows.

## Features

- Movie listings, details, and showtimes
- Seat selection with current booked-seat display
- Checkout and mock payment confirmation
- Customer registration, login, and booking history
- Admin login and management of movies, screens, and shows
- Sales and booking views
- Prepared SQL statements, password hashing, CSRF tokens, and transactional seat reservation
- A database uniqueness constraint prevents the same seat from being reserved twice for one show

## Technology

PHP 8+, MySQL 8+ or MariaDB 10.4+, MySQLi, Bootstrap 5, and Bootstrap Icons.

## Local setup

1. Install PHP with the MySQLi extension and MySQL/MariaDB.
2. Create the database and tables by importing `db.sql`:

   ```bash
   mysql -u root -p < db.sql
   ```

   The script creates `movie_db` if it does not exist and seeds sample movies, shows, a screen, and seats. It does not create a default administrator account or drop an existing database.

3. Configure database connection environment variables. Defaults in `config.php` are intended only for a local development setup.

   | Variable | Default | Purpose |
   |---|---|---|
   | `DB_HOST` | `localhost` | MySQL host |
   | `DB_USER` | `root` | MySQL user |
   | `DB_PASS` | empty | MySQL password |
   | `DB_NAME` | `movie_db` | Database name |

4. Create an administrator password hash:

   ```bash
   php -r "echo password_hash('REPLACE_WITH_A_STRONG_PASSWORD', PASSWORD_DEFAULT), PHP_EOL;"
   ```

   Insert the generated hash into the database (use your chosen admin email):

   ```sql
   INSERT INTO admin_users (email, password_hash)
   VALUES ('admin@example.com', 'PASTE_THE_GENERATED_HASH_HERE');
   ```

5. Start the local PHP server from the project directory:

   ```bash
   php -S 127.0.0.1:8000
   ```

6. Open http://127.0.0.1:8000.

7. Admin sign-in is at http://127.0.0.1:8000/admin/admin_login.php.

## Important booking behavior

- The payment flow is a mock demonstration. It does not charge a card or integrate a payment gateway.
- The server revalidates seat IDs against the selected show's screen before booking.
- The server calculates the booking total from database seat data. Front rows A and B are priced at 1.5× the standard show price.
- A database transaction and unique `(show_id, seat_id)` constraint protect against concurrent double booking.

## Security and production limitations

- Configure a dedicated database account with a strong password; do not use the local root defaults in production.
- Serve over HTTPS so session cookies are marked secure.
- Admin password changes require the current password. If an admin loses access, reset the password through a trusted database administrator process.
- Add rate limiting and account lockout controls to login endpoints before public deployment.
- Review all admin workflows, database backups, error monitoring, and retention policies before handling real users.
- This is a learning/portfolio project, not a production ticketing or payment platform.

## Project structure

```text
CineMagic/
├── admin/
├── config.php
├── helpers.php
├── db.sql
├── index.php
├── login.php
├── register.php
├── select_seats.php
├── checkout.php
├── confirm.php
└── my_bookings.php
```

## License

The repository README previously described an MIT license, but no license file was found during this review. Add a LICENSE file if you intend to grant reuse rights.
