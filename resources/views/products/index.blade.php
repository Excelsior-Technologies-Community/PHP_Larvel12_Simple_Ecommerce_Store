@extends('layouts.admin')

@section('title', 'Manage Products')

@section('content')

<div class="container-fluid">

    {{-- =========================================================
         PAGE HEADER
    ========================================================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="mb-1">Products</h2>

            <p class="text-muted mb-0">
                Manage products, stock, status and inventory
            </p>
        </div>

        <a href="{{ route('admin.products.create') }}"
            class="btn btn-primary-glass">
            + Add Product
        </a>

    </div>


    {{-- =========================================================
         SUCCESS MESSAGE
    ========================================================== --}}
    @if(session('success'))

    <div class="alert alert-success alert-dismissible fade show">

        {{ session('success') }}

        <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
        </button>

    </div>

    @endif


    {{-- =========================================================
         ERROR MESSAGE
    ========================================================== --}}
    @if($errors->any())

    <div class="alert alert-danger">

        <ul class="mb-0">

            @foreach($errors->all() as $error)

            <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

    @endif


    {{-- =========================================================
         PRODUCT STATISTICS
    ========================================================== --}}
    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-md-6 col-xl">

            <div class="glass-card p-3 h-100">

                <div class="text-muted small">
                    Total Products
                </div>

                <div class="fs-3 fw-bold">
                    {{ $totalProducts }}
                </div>

            </div>

        </div>


        {{-- Active --}}
        <div class="col-md-6 col-xl">

            <div class="glass-card p-3 h-100">

                <div class="text-muted small">
                    Active Products
                </div>

                <div class="fs-3 fw-bold text-success">
                    {{ $activeProducts }}
                </div>

            </div>

        </div>


        {{-- Inactive --}}
        <div class="col-md-6 col-xl">

            <div class="glass-card p-3 h-100">

                <div class="text-muted small">
                    Inactive Products
                </div>

                <div class="fs-3 fw-bold text-warning">
                    {{ $inactiveProducts }}
                </div>

            </div>

        </div>


        {{-- Out of stock --}}
        <div class="col-md-6 col-xl">

            <div class="glass-card p-3 h-100">

                <div class="text-muted small">
                    Out of Stock
                </div>

                <div class="fs-3 fw-bold text-danger">
                    {{ $outOfStockProducts }}
                </div>

            </div>

        </div>


        {{-- Low stock --}}
        <div class="col-md-6 col-xl">

            <div class="glass-card p-3 h-100">

                <div class="text-muted small">
                    Low Stock
                </div>

                <div class="fs-3 fw-bold text-warning">
                    {{ $lowStockProducts }}
                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         FILTER / SEARCH
    ========================================================== --}}
    <div class="glass-card p-4 mb-4">

        <form method="GET"
            action="{{ route('admin.products.index') }}">

            <div class="row g-3">

                {{-- Search --}}
                <div class="col-md-6 col-lg-3">

                    <label class="form-label">
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        class="form-control glass-form-control"
                        placeholder="Name, SKU, details...">

                </div>


                {{-- Status --}}
                <div class="col-md-6 col-lg-2">

                    <label class="form-label">
                        Status
                    </label>

                    <select name="status"
                        class="form-select glass-form-control">

                        <option value="">
                            All Status
                        </option>

                        <option value="active"
                            {{ ($status ?? '') === 'active' ? 'selected' : '' }}>
                            Active
                        </option>

                        <option value="inactive"
                            {{ ($status ?? '') === 'inactive' ? 'selected' : '' }}>
                            Inactive
                        </option>

                    </select>

                </div>


                {{-- Stock --}}
                <div class="col-md-6 col-lg-2">

                    <label class="form-label">
                        Stock
                    </label>

                    <select name="stock"
                        class="form-select glass-form-control">

                        <option value="">
                            All Stock
                        </option>

                        <option value="in_stock"
                            {{ ($stock ?? '') === 'in_stock' ? 'selected' : '' }}>
                            In Stock
                        </option>

                        <option value="low_stock"
                            {{ ($stock ?? '') === 'low_stock' ? 'selected' : '' }}>
                            Low Stock
                        </option>

                        <option value="out_of_stock"
                            {{ ($stock ?? '') === 'out_of_stock' ? 'selected' : '' }}>
                            Out of Stock
                        </option>

                    </select>

                </div>


                {{-- Minimum Price --}}
                <div class="col-md-6 col-lg-2">

                    <label class="form-label">
                        Min Price
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        name="min_price"
                        value="{{ request('min_price') }}"
                        class="form-control glass-form-control"
                        placeholder="₹ 0">

                </div>


                {{-- Maximum Price --}}
                <div class="col-md-6 col-lg-2">

                    <label class="form-label">
                        Max Price
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        name="max_price"
                        value="{{ request('max_price') }}"
                        class="form-control glass-form-control"
                        placeholder="₹ 10000">

                </div>


                {{-- Sort --}}
                <div class="col-md-6 col-lg-3">

                    <label class="form-label">
                        Sort By
                    </label>

                    <select name="sort"
                        class="form-select glass-form-control">

                        <option value="newest"
                            {{ ($sort ?? '') === 'newest' ? 'selected' : '' }}>
                            Newest First
                        </option>

                        <option value="oldest"
                            {{ ($sort ?? '') === 'oldest' ? 'selected' : '' }}>
                            Oldest First
                        </option>

                        <option value="name_asc"
                            {{ ($sort ?? '') === 'name_asc' ? 'selected' : '' }}>
                            Name A-Z
                        </option>

                        <option value="name_desc"
                            {{ ($sort ?? '') === 'name_desc' ? 'selected' : '' }}>
                            Name Z-A
                        </option>

                        <option value="price_asc"
                            {{ ($sort ?? '') === 'price_asc' ? 'selected' : '' }}>
                            Price Low-High
                        </option>

                        <option value="price_desc"
                            {{ ($sort ?? '') === 'price_desc' ? 'selected' : '' }}>
                            Price High-Low
                        </option>

                        <option value="stock_asc"
                            {{ ($sort ?? '') === 'stock_asc' ? 'selected' : '' }}>
                            Stock Low-High
                        </option>

                        <option value="stock_desc"
                            {{ ($sort ?? '') === 'stock_desc' ? 'selected' : '' }}>
                            Stock High-Low
                        </option>

                    </select>

                </div>


                {{-- Filter buttons --}}
                <div class="col-12 d-flex gap-2">

                    <button type="submit"
                        class="btn btn-primary-glass">

                        Apply Filters

                    </button>

                    <a href="{{ route('admin.products.index') }}"
                        class="btn btn-glass">

                        Reset

                    </a>

                </div>

            </div>

        </form>

    </div>


    {{-- =========================================================
         IMPORT FORM
         IMPORTANT: OUTSIDE BULK DELETE FORM
    ========================================================== --}}
    <form
        id="importForm"
        action="{{ route('admin.products.import') }}"
        method="POST"
        enctype="multipart/form-data"
        style="display:none;">

        @csrf

        <input
            type="file"
            name="file"
            id="importFile"
            accept=".xlsx,.xls,.csv"
            onchange="document.getElementById('importForm').submit()">

    </form>


    {{-- =========================================================
         BULK DELETE FORM
    ========================================================== --}}
    <form method="POST"
        action="{{ route('admin.products.bulkDelete') }}"
        id="bulkDeleteForm">

        @csrf


        <div class="glass-card p-4">


            {{-- =====================================================
                 TABLE TOOLBAR
            ====================================================== --}}
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

                <div class="d-flex align-items-center">

                    <button
                        type="submit"
                        class="btn btn-sm btn-danger bulk-delete-btn"
                        onclick="return confirmBulkDelete()"
                        id="bulkDeleteButton"
                        disabled>

                        Delete Selected

                    </button>

                    <span class="text-muted ms-2"
                        id="selectedCount">

                        0 selected

                    </span>

                </div>


                {{-- Export / Import --}}
                <div class="d-flex gap-2">

                    <a href="{{ route('admin.products.export') }}"
                        class="btn btn-sm btn-glass">

                        Export Excel

                    </a>

                    <button type="button"
                        class="btn btn-sm btn-primary-glass"
                        onclick="document.getElementById('importFile').click()">

                        Import Excel

                    </button>

                </div>

            </div>


            {{-- =====================================================
                 PRODUCT TABLE
            ====================================================== --}}
            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>

                        <tr>

                            <th width="40">

                                <input
                                    type="checkbox"
                                    id="selectAll"
                                    class="form-check-input">

                            </th>

                            <th>
                                Image
                            </th>

                            <th>
                                Product
                            </th>

                            <th>
                                Price
                            </th>

                            <th>
                                Stock
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Created
                            </th>

                            <th class="actions-column">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($products as $product)

                        <tr>

                            {{-- =================================================
                                 CHECKBOX
                            ================================================== --}}
                            <td>

                                <input
                                    type="checkbox"
                                    name="product_ids[]"
                                    value="{{ $product->id }}"
                                    class="form-check-input product-checkbox">

                            </td>


                            {{-- =================================================
                                 IMAGE
                            ================================================== --}}
                            <td>

                                @if($product->image)

                                <img
                                    src="{{ str_starts_with($product->image, 'http')
                                            ? $product->image
                                            : asset('images/' . $product->image) }}"
                                    alt="{{ $product->name }}"
                                    class="product-table-image"
                                    onerror="this.style.display='none';">

                                @else

                                <div class="product-no-image">
                                    N/A
                                </div>

                                @endif

                            </td>


                            {{-- =================================================
                                 PRODUCT
                            ================================================== --}}
                            <td>

                                <strong>
                                    {{ $product->name }}
                                </strong>

                                <br>

                                <small class="text-muted">
                                    SKU:
                                    {{ $product->sku ?? 'No SKU' }}
                                </small>

                            </td>


                            {{-- =================================================
                                 PRICE
                            ================================================== --}}
                            <td>

                                <strong>
                                    ₹ {{ number_format($product->price, 2) }}
                                </strong>

                            </td>


                            {{-- =================================================
                                 STOCK
                            ================================================== --}}
                            <td>

                                @if($product->variants->count() > 0)

                                @php
                                $variantStock =
                                $product->variants->sum('stock_quantity');
                                @endphp


                                @if($variantStock <= 0)

                                    <span class="badge bg-danger">
                                    Out of Stock
                                    </span>

                                    @elseif($product->isLowStock())

                                    <span class="badge bg-warning text-dark">
                                        {{ $variantStock }} units - Low
                                    </span>

                                    @else

                                    <span class="badge bg-success">
                                        {{ $variantStock }} units
                                    </span>

                                    @endif

                                    @else

                                    @if($product->stock_quantity <= 0)

                                        <span class="badge bg-danger">
                                        Out of Stock
                                        </span>

                                        @elseif($product->isLowStock())

                                        <span class="badge bg-warning text-dark">
                                            {{ $product->stock_quantity }} units - Low
                                        </span>

                                        @else

                                        <span class="badge bg-success">
                                            {{ $product->stock_quantity }} units
                                        </span>

                                        @endif

                                        @endif

                            </td>


                            {{-- =================================================
                                 STATUS
                            ================================================== --}}
                            <td>

                                @if($product->status === 'active')

                                <span class="badge bg-success">
                                    Active
                                </span>

                                @else

                                <span class="badge bg-warning text-dark">
                                    Inactive
                                </span>

                                @endif

                            </td>


                            {{-- =================================================
                                 CREATED
                            ================================================== --}}
                            <td>

                                <small>
                                    {{ optional($product->created_at)->format('d M Y') }}
                                </small>

                            </td>


                            {{-- =================================================
                                 ACTIONS
                            ================================================== --}}
                            <td class="actions-column">

                                <div class="product-actions">


                                    {{-- ================================
                                         EDIT
                                    ================================= --}}
                                    <a
                                        href="{{ route('admin.products.edit', $product) }}"
                                        class="product-action action-edit">

                                        Edit

                                    </a>


                                    {{-- ================================
                                         ACTIVATE / DEACTIVATE
                                    ================================= --}}
                                    <form
                                        action="{{ route('admin.products.toggleStatus', $product) }}"
                                        method="POST"
                                        class="action-form">

                                        @csrf

                                        @if($product->status === 'active')

                                        <button
                                            type="submit"
                                            class="product-action action-deactivate"
                                            onclick="return confirm('Deactivate this product?')">

                                            Deactivate

                                        </button>

                                        @else

                                        <button
                                            type="submit"
                                            class="product-action action-activate">

                                            Activate

                                        </button>

                                        @endif

                                    </form>


                                    {{-- ================================
                                         DUPLICATE
                                    ================================= --}}
                                    <form
                                        action="{{ route('admin.products.duplicate', $product) }}"
                                        method="POST"
                                        class="action-form">

                                        @csrf

                                        <button
                                            type="submit"
                                            class="product-action action-duplicate"
                                            onclick="return confirm('Duplicate this product?')">

                                            Duplicate

                                        </button>

                                    </form>


                                    {{-- ================================
                                         DELETE
                                    ================================= --}}
                                    <form
                                        action="{{ route('admin.products.destroy', $product) }}"
                                        method="POST"
                                        class="action-form">

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="product-action action-delete"
                                            onclick="return confirm('Delete this product?')">

                                            Delete

                                        </button>

                                    </form>


                                </div>

                            </td>

                        </tr>


                        @empty

                        <tr>

                            <td colspan="8"
                                class="text-center py-5">

                                <div class="empty-product-icon">
                                    📦
                                </div>

                                <h5>
                                    No products found
                                </h5>

                                <p class="text-muted mb-3">
                                    Try changing your search or filters.
                                </p>

                                <a
                                    href="{{ route('admin.products.create') }}"
                                    class="btn btn-primary-glass">

                                    + Add Product

                                </a>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- =====================================================
                 PAGINATION
            ====================================================== --}}
            @if($products->hasPages())

            <div class="d-flex justify-content-center mt-4">

                {{ $products->links('pagination::bootstrap-5') }}

            </div>

            @endif

        </div>

    </form>

</div>


{{-- =============================================================
     ACTION BUTTON CSS
============================================================= --}}
<style>
    /*
    |--------------------------------------------------------------------------
    | Actions column
    |--------------------------------------------------------------------------
    */

    .actions-column {
        min-width: 360px;
        white-space: nowrap;
    }


    /*
    |--------------------------------------------------------------------------
    | Action button container
    |--------------------------------------------------------------------------
    */

    .product-actions {
        display: flex;
        align-items: center;
        gap: 7px;
        flex-wrap: wrap;
    }


    /*
    |--------------------------------------------------------------------------
    | Action forms
    |--------------------------------------------------------------------------
    */

    .action-form {
        display: inline-flex;
        margin: 0;
        padding: 0;
    }


    /*
    |--------------------------------------------------------------------------
    | Base action button
    |--------------------------------------------------------------------------
    */

    .product-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-height: 34px;

        padding: 6px 12px;

        border-radius: 8px;

        border: 1px solid transparent;

        font-size: 12px;

        font-weight: 600;

        line-height: 1.2;

        text-decoration: none;

        white-space: nowrap;

        cursor: pointer;

        transition:
            background-color 0.2s ease,
            color 0.2s ease,
            border-color 0.2s ease,
            transform 0.2s ease,
            box-shadow 0.2s ease;
    }


    /*
    |--------------------------------------------------------------------------
    | Hover
    |--------------------------------------------------------------------------
    */

    .product-action:hover {
        transform: translateY(-1px);

        box-shadow:
            0 4px 10px rgba(0, 0, 0, 0.10);

        text-decoration: none;
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    .action-edit {
        color: #0d6efd;

        background: rgba(13, 110, 253, 0.08);

        border-color: rgba(13, 110, 253, 0.25);
    }

    .action-edit:hover {
        color: #ffffff;

        background: #0d6efd;

        border-color: #0d6efd;
    }


    /*
    |--------------------------------------------------------------------------
    | ACTIVATE
    |--------------------------------------------------------------------------
    */

    .action-activate {
        color: #198754;

        background: rgba(25, 135, 84, 0.08);

        border-color: rgba(25, 135, 84, 0.25);
    }

    .action-activate:hover {
        color: #ffffff;

        background: #198754;

        border-color: #198754;
    }


    /*
    |--------------------------------------------------------------------------
    | DEACTIVATE
    |--------------------------------------------------------------------------
    */

    .action-deactivate {
        color: #fd7e14;

        background: rgba(253, 126, 20, 0.08);

        border-color: rgba(253, 126, 20, 0.25);
    }

    .action-deactivate:hover {
        color: #ffffff;

        background: #fd7e14;

        border-color: #fd7e14;
    }


    /*
    |--------------------------------------------------------------------------
    | DUPLICATE
    |--------------------------------------------------------------------------
    */

    .action-duplicate {
        color: #6f42c1;

        background: rgba(111, 66, 193, 0.08);

        border-color: rgba(111, 66, 193, 0.25);
    }

    .action-duplicate:hover {
        color: #ffffff;

        background: #6f42c1;

        border-color: #6f42c1;
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    .action-delete {
        color: #dc3545;

        background: rgba(220, 53, 69, 0.08);

        border-color: rgba(220, 53, 69, 0.25);
    }

    .action-delete:hover {
        color: #ffffff;

        background: #dc3545;

        border-color: #dc3545;
    }


    /*
    |--------------------------------------------------------------------------
    | PRODUCT IMAGE
    |--------------------------------------------------------------------------
    */

    .product-table-image {
        width: 55px;

        height: 55px;

        object-fit: cover;

        border-radius: 10px;

        border: 1px solid rgba(0, 0, 0, 0.08);
    }


    /*
    |--------------------------------------------------------------------------
    | NO IMAGE
    |--------------------------------------------------------------------------
    */

    .product-no-image {
        width: 55px;

        height: 55px;

        border-radius: 10px;

        background: #f1f3f5;

        display: flex;

        align-items: center;

        justify-content: center;

        color: #adb5bd;

        font-size: 12px;

        font-weight: 600;
    }


    /*
    |--------------------------------------------------------------------------
    | EMPTY PRODUCT
    |--------------------------------------------------------------------------
    */

    .empty-product-icon {
        font-size: 42px;

        margin-bottom: 10px;
    }


    /*
    |--------------------------------------------------------------------------
    | BULK DELETE
    |--------------------------------------------------------------------------
    */

    .bulk-delete-btn:disabled {
        opacity: 0.45;

        cursor: not-allowed;

        transform: none;

        box-shadow: none;
    }


    /*
    |--------------------------------------------------------------------------
    | MOBILE
    |--------------------------------------------------------------------------
    */

    @media (max-width: 992px) {

        .actions-column {
            min-width: 300px;
        }

        .product-action {
            padding: 6px 10px;

            font-size: 11px;
        }

    }


    @media (max-width: 576px) {

        .actions-column {
            min-width: 280px;
        }

        .product-actions {
            gap: 5px;
        }

        .product-action {
            min-height: 32px;

            padding: 5px 8px;

            font-size: 10px;
        }

    }
</style>


{{-- =============================================================
     JAVASCRIPT
============================================================= --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const selectAll =
            document.getElementById('selectAll');

        const checkboxes =
            document.querySelectorAll('.product-checkbox');

        const deleteButton =
            document.getElementById('bulkDeleteButton');

        const selectedCount =
            document.getElementById('selectedCount');


        /*
        |--------------------------------------------------------------------------
        | UPDATE SELECTED COUNT
        |--------------------------------------------------------------------------
        */

        function updateSelectedCount() {

            const selected =
                document.querySelectorAll(
                    '.product-checkbox:checked'
                ).length;


            selectedCount.textContent =
                selected + ' selected';


            deleteButton.disabled =
                selected === 0;

        }


        /*
        |--------------------------------------------------------------------------
        | SELECT ALL
        |--------------------------------------------------------------------------
        */

        if (selectAll) {

            selectAll.addEventListener('change', function() {

                checkboxes.forEach(function(checkbox) {

                    checkbox.checked =
                        selectAll.checked;

                });

                selectAll.indeterminate = false;

                updateSelectedCount();

            });

        }


        /*
        |--------------------------------------------------------------------------
        | INDIVIDUAL CHECKBOX
        |--------------------------------------------------------------------------
        */

        checkboxes.forEach(function(checkbox) {

            checkbox.addEventListener('change', function() {

                const checkedCount =
                    document.querySelectorAll(
                        '.product-checkbox:checked'
                    ).length;


                const totalCount =
                    checkboxes.length;


                if (selectAll) {

                    selectAll.checked =
                        totalCount > 0 &&
                        checkedCount === totalCount;


                    selectAll.indeterminate =
                        checkedCount > 0 &&
                        checkedCount < totalCount;

                }


                updateSelectedCount();

            });

        });


        /*
        |--------------------------------------------------------------------------
        | INITIAL COUNT
        |--------------------------------------------------------------------------
        */

        updateSelectedCount();

    });


    /*
    |--------------------------------------------------------------------------
    | BULK DELETE CONFIRMATION
    |--------------------------------------------------------------------------
    */

    function confirmBulkDelete() {

        const selected =
            document.querySelectorAll(
                '.product-checkbox:checked'
            ).length;


        if (selected === 0) {

            alert(
                'Please select at least one product.'
            );

            return false;

        }


        return confirm(
            'Are you sure you want to delete ' +
            selected +
            ' selected product(s)?'
        );

    }
</script>

@endsection