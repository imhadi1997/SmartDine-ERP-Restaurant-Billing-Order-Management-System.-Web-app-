@if(!session()->has('userid'))
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
@endif

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mehar Baba Dashboard - @yield('title')</title>

<style>
    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }

    html, body{
        min-height: 100%;
        width: 100%;
    }

    body{
        background-image: url('{{ asset("/dashboard.png") }}');
        background-repeat: no-repeat;
        background-size: cover;   
        background-position: center 15%;
        background-attachment: fixed;
        min-height: 100vh;
    }

    /* ===== TOP HORIZONTAL MENU ===== */
    .topbar {
        width: 100%;
        background: rgba(0,0,0,0.7);
        display: flex;
        align-items: center;
        padding: 0 10px;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 999;
        height: 50px;
    }

    .topbar .menu-item {
        position: relative; /* ✅ parent relative */
        display: inline-block; /* horizontal alignment */
    }

    .topbar a {
        color: #fff;
        background-color: #330000;
        text-decoration: none;
        padding: 12px 15px;
        display: block;
        font-weight: bold;
        margin-right: 5px;
        transition: background 0.3s, color 0.3s;
    }

    .topbar a:hover{
        background: #ff6600;
        color: black;
    }

    
    .dropdown {
        display: none;
        flex-direction: column;
        position: absolute;
        top: 100%; /* ✅ directly below parent */
        left: 0;    /* aligned with parent */
        min-width: 180px;
        background: rgba(0,0,0,0.8);
        color: white;
        border: 1px solid rgba(0,0,0,0.2);
        z-index: 1000;
        visibility: visible;
        opacity: 1;
    }

    .dropdown a{
        color: white;
        padding: 10px 15px;
        font-weight: normal;
    }

    .dropdown a:hover{
        background: #ff6600;
        color: white;
    }

    .menu-item:hover .dropdown {
    display: flex;
    visibility: visible;
    opacity: 1;
}

    /* ===== MAIN CONTENT ===== */
    .main-content{
        flex: 1;
        min-height: 100vh;
        padding: 80px 30px 30px 30px; /* top padding for fixed topbar */
    }

    .main-content h1{
        margin-bottom: 20px;
        color: white;
        text-shadow: 1px 1px 3px #000;
        font-size: 32px;
    }

    .user-info{
        position: fixed;
        top: 55px; /* below topbar */
        right: 20px;
        background: rgba(0,0,0,0.6);
        color: #fff;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 13px;
        z-index: 9999;
    }

.dashboard-card {
    flex-wrap: wrap; 
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 360px;
    height: 110px;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 14px;
    text-decoration: none;
    background: linear-gradient(135deg, #28a745, #5ddf7a);
    color: black;
    box-shadow: 0 8px 20px rgba(0,0,0,0.25);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    flex-wrap: wrap;
    gap: 20px;    
    width: calc(50% - 10px);
    max-width: 420px;
}

.dashboard-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(0,0,0,0.35);
}

.card-title {
    font-size: 16px;
    font-weight: 600;
    max-width: 65%;
    line-height: 1.3;
}

.card-count {
    font-size: 42px;
    font-weight: 800;
    background: rgba(255,255,255,0.2);
    padding: 10px 16px;
    border-radius: 10px;
}

.dashboard-cards {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}
/* default = red */
.dashboard-card {
     background: linear-gradient(135deg, #dc3545, #ff6b6b); 
}

.dashboard-card:nth-child(-n+4) {
    background: linear-gradient(135deg, #28a745, #5ddf7a);
}
.topbar .topbar-link {
    background-color: #330000;
    color: #fff;
    border: none;
    padding: 12px 15px;
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
    display: block;
    transition: background 0.3s, color 0.3s;
}

.topbar .topbar-link:hover {
    background: #ff6600;
    color: black;
}


</style>

</head>
<body>

<div class="topbar">
    <div class="menu-item"><a href="{{ route('dashboard') }}">Home</a></div>

    <div class="menu-item">
        <a href="#">Menu</a>
        <div class="dropdown">
            <a href="{{ route('menu_top_level') }}">Master Input</a>
            <a href="{{ route('menu_items') }}">Menu item</a>
            <a href="{{ route('menu_type_of_items') }}">Menu types of item</a>
            <a href="{{ route('main_menu_complete') }}">Main Menu</a>
        </div>
    </div>

    <div class="menu-item">
        <a href="#">Order</a>
        <div class="dropdown">
            <a href="{{ route('new_order') }}">New Order</a>
            <a href="{{ route('order_update_status') }}">Order Status</a>
            <a href="{{ route('order_bill') }}">Order Bill & Payment</a>
            <a href="{{ route('order_report') }}">Order Report</a>
        </div>
    </div>

    <div class="menu-item">
        <a href="#">Stock</a>
        <div class="dropdown">
            <a href="{{ route('stock_master_input') }}">Stock Master input</a>
            <a href="{{ route('stock_items') }}">Stock item</a>
            <a href="{{ route('stock_type_of_items') }}">Stock types of item</a>
            <a href="{{ route('main_stock_complete') }}">Stock</a>
        </div>
    </div>

    <div class="menu-item">
        <a href="#">Purchase Order</a>
        <div class="dropdown">
            <a href="{{ route('new_purchase_order') }}">New Purchase Order</a>
            <a href="{{ route('purchase_order_update_status') }}">Purchase Order Status</a>
            <a href="{{ route('purchase_order_bill') }}">Purchase Order Bill & Payment</a>
            <a href="{{ route('purchase_order_report') }}">Purchase Order Report</a>
        </div>
    </div>

    <div class="menu-item">
        <a href="#">Inventory</a>
        <div class="dropdown">
            <a href="{{ route('inventory') }}">Inventory</a>
            <a href="{{ route('inventory_out') }}">Inventory Out</a>
            <a href="{{ route('inventory_report') }}">Stock Report</a>
        </div>
    </div>

    <div class="menu-item">
        <a href="#">Account</a>
        <div class="dropdown">
            <a href="{{ route('employee') }}">Employee</a>    
            <a href="{{ route('supplier') }}">Vendor/Supplier</a>
            <a href="{{ route('employee_ledger') }}">Employee Ledger</a>
            <a href="{{ route('supplier_ledger') }}">Vendor/Supplier Ledger</a>
        </div>
    </div>

    <div class="menu-item">
        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="topbar-link">Log out</button>
        </form>
    </div>

</div>

<div class="user-info">
    <p>
        User: {{ session('username') }} <br>
        Role: {{ session('usertype') }}
    </p>
</div>


<div class="main-content">
    @if(request()->routeIs('dashboard') || request()->routeIs('login_check'))
        <h1 style="display: inline; color:#330000;">Welcome to Dashboard, {{ session('username') }}</h1>
        <h2 style="display: inline; color:#330000;">(Role: {{ session('usertype') }})</h2>
        <br><br><br>

        <div class="dashboard-cards">
            <a href="#" class="dashboard-card green">
                <div class="card-title">Unclear & Unpaid Orders</div>
                <div class="card-count">{{ $count_unpaid_unclear }}</div>
            </a>

            <a href="#" class="dashboard-card green">
                <div class="card-title">Clear & Unpaid Orders</div>
                <div class="card-count">{{ $count_clear_unpaid }}</div>
            </a>

            <a href="#" class="dashboard-card green">
                <div class="card-title">Pending Purchase Orders</div>
                <div class="card-count">{{ $pending_purchase_order }}</div>
            </a>

            <a href="#" class="dashboard-card green">
                <div class="card-title">Total Supplier</div>
                <div class="card-count">{{ $total_supplier }}</div>
            </a>

            <a href="#" class="dashboard-card green">
                <div class="card-title">Active Employees</div>
                <div class="card-count">{{ $active_emp }}</div>
            </a>

            <a href="#" class="dashboard-card green">
                <div class="card-title">Employees<br>Balance</div>
                <div class="card-count">{{ $employees_receivable }}</div>
            </a>

            <a href="#" class="dashboard-card green">
                <div class="card-title">Suppliers<br>Balance</div>
                <div class="card-count">{{ $supplier_balance }}</div>
            </a>

            
        </div>
    @endif

    @yield('content')
</div>


</body>
</html>
