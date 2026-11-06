# PHP Basics - Hands-on Exercises

## Setup (XAMPP / WAMP / Laragon)
1. Copy the `php_outputs` folder into `htdocs` (XAMPP) or `www` (WAMP).
2. Start Apache and MySQL.
3. Open phpMyAdmin > Import > choose `database.sql` (creates the `php_exercises` database, tables and sample data).
4. If your MySQL has a password, edit `output3_4/db.php` and `output5/config/database.php`.

## Open each output
- Output #1   http://localhost/php_outputs/output1/
- Output #2   http://localhost/php_outputs/output2/
- Output #3/4 http://localhost/php_outputs/output3_4/
- Output #5   http://localhost/php_outputs/output5/

## Notes
- Output #2 keeps registered accounts in the PHP session (no database); they reset when the session ends.
- Output #5 follows MVC: models/ (database), views/ (HTML), controllers/ (logic), index.php (router).
- To export your own .sql after testing: phpMyAdmin > select `php_exercises` > Export.
