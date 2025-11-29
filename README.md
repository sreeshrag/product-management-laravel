Product Management System
Laravel API + Admin Panel

Complete product management system with admin dashboard, public storefront, and full REST API.

Features
- Admin CRUD for Products & Categories
- Image upload 
- Dynamic product attributes (Color, Size, etc.)
- Public product listing with AJAX filters
- REST API (all CRUD operations)
- Laravel API Resources
- Responsive Bootstrap UI
- Search, pagination, price range filters

Tech Stack
- Laravel 11.x
- MySQL
- Bootstrap 5
- jQuery

Setup (5 minutes)

1. Clone & Install
git clone <your-repo>
cd product-management
composer install
cp .env.example .env

2. Database
php artisan key:generate
php artisan migrate
php artisan db:seed

3. Storage
php artisan storage:link

4. Run
php artisan serve

Admin: http://localhost:8000/admin (admin@admin.com / password)
Public: http://localhost:8000  
API: http://localhost:8000/api/products

Database
php artisan migrate:fresh --seed
php artisan db:seed --class=ProductSeeder

Tables:
- categories
- products  
- product_attributes
- users

Usage

Admin Panel: http://localhost:8000/admin
Public Storefront: http://localhost:8000
REST API:
GET /api/products
GET /api/products/1
POST /api/products
PUT /api/products/1
DELETE /api/products/1

Full API docs: API_DOCUMENTATION.md

Commands
php artisan migrate
php artisan db:seed
php artisan storage:link
php artisan serve

.env required:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=product_db
DB_USERNAME=root
DB_PASSWORD=
APP_URL=http://localhost:8000

Deployment
1. Update .env for production DB
2. Run migrations
3. php artisan storage:link
4. php artisan config:cache
5. php artisan route:cache

Troubleshooting
Image not showing? php artisan storage:link
404 on API? php artisan route:clear
Admin login fail? php artisan db:seed --class=UserSeeder



Built: Nov 30, 2025
