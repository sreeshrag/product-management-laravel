Product Management API Docs
Version 1.0

Base URL: http://localhost:8000/api
No authentication required.

QUICK START

List all products:
curl http://localhost:8000/api/products

Get product #1:
curl http://localhost:8000/api/products/1

Create product:
curl -X POST http://localhost:8000/api/products \
  -H "Content-Type: application/json" \
  -d '{"category_id":1,"name":"Test Laptop","price":999.99,"status":"active"}'

Update product:
curl -X PUT http://localhost:8000/api/products/1 \
  -H "Content-Type: application/json" \
  -d '{"name":"Updated Laptop","price":1099.99}'

Delete product:
curl -X DELETE http://localhost:8000/api/products/1

ENDPOINTS

1. GET /products
List active products (15 per page)

Filters:
?category_id=1
?min_price=100  
?max_price=1000
?search=laptop
?page=2

Response:
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "MacBook Pro",
      "price": 1999.99,
      "image_url": "http://localhost:8000/storage/products/macbook.jpg",
      "category": {"id": 1, "name": "Electronics"},
      "attributes": [{"key": "RAM", "value": "16GB"}]
    }
  ],
  "pagination": {"current_page": 1, "total": 42}
}

2. GET /products/{id}
Get single product details. Returns 404 if not found.

3. POST /products
Create new product

JSON payload:
{
  "category_id": 1,
  "name": "New iPhone",
  "description": "Latest model", 
  "price": 899.99,
  "status": "active",
  "attributes": [{"key": "Color", "value": "Space Gray"}]
}

For image upload use form-data with 'image' field.

Returns 201 with new product data.

4. PUT /products/{id} or POST /products/{id} (_method=PUT)
Update existing product. Same payload as POST.

Old image is automatically deleted if new image uploaded.

5. DELETE /products/{id}
Delete product, image, and all attributes. Returns success message.

VALIDATION

Required fields:
- category_id (must exist)
- name (max 255 chars) 
- price (number >= 0)
- status (active/inactive)

Optional:
- description (max 1000 chars)
- image (JPG/PNG/GIF <= 2MB)
- attributes (max 10 items)

Validation error returns 422:
{
  "success": false,
  "errors": {"price": ["The price must be at least 0."]}
}

STATUS CODES
200 - Success
201 - Created
404 - Not found
422 - Validation error
500 - Server error

NOTES
- List endpoint shows only ACTIVE products
- Images stored in storage/app/public/products/
- Attributes are automatically deleted with product
- Search works on name and description fields
- Pagination is always 15 items per page

TESTING
php artisan db:seed --class=ProductSeeder

Updated: Nov 30, 2025
