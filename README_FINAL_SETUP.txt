# MobileCare — final project package

## Local run
1. Keep XAMPP Apache and MySQL running.
2. Place this folder at `C:\xampp\htdocs\mobile_shop` (or keep your existing folder path).
3. Make sure `config/database.php` contains the correct local MySQL credentials. This file is ignored by Git so it should not be pushed publicly.
4. Open `http://localhost/mobile_shop/`.
5. Admin login: open `http://localhost/mobile_shop/admin/login.php` and use the admin account already stored in your local `mobile_shop` database. No credentials are included in this file.

## What's updated
- Added local SVG product category illustrations for chargers, cables, phone covers, screen protectors, earbuds and power banks.
- Product listing now uses a category illustration when a product has no valid uploaded image.
- Updated shop details for Anand Mobile and Electrics, owner Anand Singh, Sachin, Surat, and 10 AM–9 PM hours.
- Updated About and Contact pages and added a Maps search link (location is approximate because no full street address was supplied).
- Public product prices now show “Price on request” because actual shop prices were not provided.
- Improved mobile/responsive styling and admin product image previews.
- Removed the screenshot that had been stored as a product image; its old filename is safely ignored by the product display code.

## Important
The product illustrations are clean local SVG category visuals, not verified photos of the shop's actual stock. Replace them with real product photos when the shop provides them. Product availability and prices should be confirmed with the shop.

The SQL file is the initial schema/sample data. Do not re-import it into your existing database unless you intentionally want to reset/recreate the database. Your existing database and admin login are stored in MySQL, not inside this folder.
