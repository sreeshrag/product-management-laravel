<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Products - Product Management System</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
          --primary-color: #66c9eaff;
            --secondary-color: #2b86aaff;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .filter-sidebar {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            position: sticky;
            top: 20px;
        }

        .filter-section {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .filter-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .filter-title {
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
            font-size: 16px;
        }

        .category-item {
            padding: 8px 12px;
            margin-bottom: 8px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .category-item:hover {
            background: #f8f9fa;
        }

        .category-item.active {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .product-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #f8f9fa;
        }

        .product-body {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .product-category {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .product-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex-grow: 1;
        }

        .product-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
        }

        .product-price {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
        }

        .no-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 64px;
            color: #ddd;
            margin-bottom: 20px;
        }

        .price-inputs {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .price-inputs input {
            width: 100%;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.8);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .loading-overlay.active {
            display: flex;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .results-info {
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .product-attributes {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }

        .attribute-badge {
            display: inline-block;
            background: #f8f9fa;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            margin-right: 5px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">
                <i class="bi bi-shop"></i> Product Management System
            </span>
            <div>
                <a href="{{ route('login') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-box-arrow-in-right"></i> Admin Login
                </a>
            </div>
        </div>
    </nav>

    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <div class="container mt-4 mb-5">
        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="filter-sidebar">
                    <h5 class="mb-4">
                        <i class="bi bi-funnel"></i> Filters
                    </h5>

                    <div class="filter-section">
                        <div class="filter-title">
                            <i class="bi bi-search"></i> Search
                        </div>
                        <input type="text" 
                               class="form-control" 
                               id="searchInput" 
                               placeholder="Search products...">
                    </div>

                    <div class="filter-section">
                        <div class="filter-title">
                            <i class="bi bi-folder"></i> Categories
                        </div>
                        <div class="category-item active" data-category="">
                            <span>All Categories</span>
                            <span class="badge bg-secondary" id="allCount">{{ $totalProducts }}</span>
                        </div>
                        @foreach($categories as $category)
                            <div class="category-item" data-category="{{ $category->id }}">
                                <span>{{ $category->name }}</span>
                                <span class="badge bg-secondary">{{ $category->products_count }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="filter-section">
                        <div class="filter-title">
                            <i class="bi bi-currency-rupee"></i> Price Range
                        </div>
                        <div class="price-inputs">
                            <input type="number" 
                                   class="form-control form-control-sm" 
                                   id="minPrice" 
                                   placeholder="Min"
                                   min="0"
                                   step="0.01"
                                   value="{{ $priceRange->min_price ?? 0 }}">
                            <span>-</span>
                            <input type="number" 
                                   class="form-control form-control-sm" 
                                   id="maxPrice" 
                                   placeholder="Max"
                                   min="0"
                                   step="0.01"
                                   value="{{ $priceRange->max_price ?? 1000 }}">
                        </div>
                        <button class="btn btn-primary btn-sm w-100 mt-2" onclick="applyFilters()">
                            Apply
                        </button>
                    </div>

                    <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                        <i class="bi bi-arrow-clockwise"></i> Reset Filters
                    </button>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="results-info">
                    <div>
                        <strong id="productCount">0</strong> Products Found
                    </div>
                    <div class="text-muted" id="filterStatus">
                        Showing all products
                    </div>
                </div>

                <div class="row" id="productsContainer">
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let currentFilters = {
            category_id: '',
            min_price: '',
            max_price: '',
            search: ''
        };

        const TOTAL_PRODUCTS = {{ $totalProducts }};

        $(document).ready(function() {
            loadProducts();

            $('.category-item').click(function() {
                $('.category-item').removeClass('active');
                $(this).addClass('active');
                currentFilters.category_id = $(this).data('category');
                loadProducts();
            });

            let searchTimeout;
            $('#searchInput').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    currentFilters.search = $('#searchInput').val();
                    loadProducts();
                }, 500);
            });
        });

        function applyFilters() {
            currentFilters.min_price = $('#minPrice').val();
            currentFilters.max_price = $('#maxPrice').val();
            loadProducts();
        }

        function resetFilters() {
            currentFilters = {
                category_id: '',
                min_price: '',
                max_price: '',
                search: ''
            };
            $('#searchInput').val('');
            $('#minPrice').val('{{ $priceRange->min_price ?? 0 }}');
            $('#maxPrice').val('{{ $priceRange->max_price ?? 1000 }}');
            $('.category-item').removeClass('active');
            $('.category-item[data-category=""]').addClass('active');
            loadProducts();
        }

        function loadProducts() {
            $('#loadingOverlay').addClass('active');

            $.ajax({
                url: '{{ route("products.filter") }}',
                method: 'GET',
                data: currentFilters,
                success: function(response) {
                    displayProducts(response.products);
                    updateResultsInfo(response);
                    $('#loadingOverlay').removeClass('active');
                },
                error: function(xhr) {
                    $('#loadingOverlay').removeClass('active');
                    console.error('Error:', xhr);
                    alert('Error loading products. Please try again.');
                }
            });
        }

        function displayProducts(products) {
            const container = $('#productsContainer');
            container.empty();

            if (products.length === 0) {
                container.html(`
                    <div class="col-12">
                        <div class="empty-state">
                            <i class="bi bi-inbox"></i>
                            <h4>No Products Found</h4>
                            <p class="text-muted">Try adjusting your filters to see more results.</p>
                        </div>
                    </div>
                `);
                return;
            }

            products.forEach(function(product) {
                const imageHtml = product.image 
                    ? `<img src="/storage/${escapeHtml(product.image)}" alt="${escapeHtml(product.name)}" class="product-image">`
                    : `<div class="no-image"><i class="bi bi-image" style="font-size: 48px;"></i></div>`;

                const description = product.description 
                    ? `<p class="product-description">${escapeHtml(product.description)}</p>`
                    : '';

                const attributesHtml = product.attributes && product.attributes.length > 0
                    ? `<div class="product-attributes">
                        ${product.attributes.map(attr => 
                            `<span class="attribute-badge"><strong>${escapeHtml(attr.attribute_key)}:</strong> ${escapeHtml(attr.attribute_value)}</span>`
                        ).join('')}
                       </div>`
                    : '';

                const productCard = `
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="product-card">
                            ${imageHtml}
                            <div class="product-body">
                                <div class="product-category">${escapeHtml(product.category.name)}</div>
                                <h5 class="product-name">${escapeHtml(product.name)}</h5>
                                ${description}
                                ${attributesHtml}
                                <div class="product-footer">
                                    <div class="product-price">₹${parseFloat(product.price).toFixed(2)}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.append(productCard);
            });
        }

        function updateResultsInfo(response) {
            $('#productCount').text(response.count);
            $('#allCount').text(TOTAL_PRODUCTS);

            let filterText = 'Showing all products';
            if (currentFilters.category_id) {
                const categoryName = $(`.category-item[data-category="${currentFilters.category_id}"]`).find('span:first').text();
                filterText = `Filtered by: ${categoryName}`;
            }
            if (currentFilters.search) {
                filterText += ` | Search: "${escapeHtml(currentFilters.search)}"`;
            }
            if (currentFilters.min_price || currentFilters.max_price) {
                filterText += ` | Price: ₹${currentFilters.min_price || '0'} - ₹${currentFilters.max_price || '∞'}`;
            }
            
            $('#filterStatus').text(filterText);
        }

        function escapeHtml(text) {
            if (!text) return '';
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
        }
    </script>
</body>
</html>
