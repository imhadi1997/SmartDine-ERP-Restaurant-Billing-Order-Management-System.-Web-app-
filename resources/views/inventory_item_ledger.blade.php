@if(!session()->has('userid'))
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
@endif


@extends('layouts.dashboard')


@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<br>
<h1 style="display: inline; color:#330000;">Stock item Ledger</h1>


<style>
.main-content {
    flex: 1;
    display: flex;
    flex-direction: column;   /* ✅ column */
    align-items: center;      /* ✅ center */
    justify-content: flex-start;
    padding: 40px 20px;
}


.form-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.form-row label{
    width: 40%;
    font-weight: bold;
}

.form-row input{
    width: 55%;
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

.form-container button{
    padding: 10px 20px;
    background: #ff6600;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}


table{
    width: 100%;
    max-width: 1000px;
    margin-top: 30px;
    border-collapse: collapse;
    background: rgba(255,255,255,0.9);
    border-radius: 10px;
    overflow: hidden;

}

table th, table td{
    padding: 10px;
    border: 1px solid #ccc;
    text-align: left;
}

table th{
    background: #330000;
    color: white;
}

#lcd {
    width: 300px;         
    height: 70px;
    background: #1d1d1d;
    border-radius: 8px;
    padding: 10px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    border: 2px solid #333;
}

#lcdValue {
    font-family: 'Digital', monospace;
    font-size: 45px;
    color: #55ff00;  
    letter-spacing: 3px;
    overflow: hidden;
    white-space: nowrap;
}
.user-info{
    position: fixed;
    top: 15px;
    right: 20px;
    background: rgba(0,0,0,0.6);
    color: #fff;
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 13px;
    z-index: 9999;
}

.page-title{
    text-align:center;
    width:100%;
}
.order-wrapper{
    width: 100%;
    display: flex;
    align-items: flex-start;   
    gap: 30px;                 
}

/* Form left */
.form-container{
    width: 400px;
    flex-shrink: 0;            
}

/* Table right */
.table-container{
    flex: 1;               
}

#itemTable{
    width: 100%;
    max-width: 1200px;
    table-layout: fixed;
}

#itemTable th:nth-child(1), #itemTable td:nth-child(1) {
    width: 400px;
}

#itemTable th:nth-child(2), #itemTable td:nth-child(2) {
    width: 120px; 
}

#itemTable th:nth-child(3), #itemTable td:nth-child(3) {
    width: 120px;
}

#itemTable th:nth-child(4), #itemTable td:nth-child(4) {
    width: 120px;
}

#itemTable th:nth-child(5), #itemTable td:nth-child(5) {
    width: 140px;
}

#itemTable th:nth-child(6), #itemTable td:nth-child(6) {
    width: 140px;
}


</style>

<!--- User Details Div --->
<div class="user-info">
    <p>
        User: {{ session('username') }} <br>
        Role: {{ session('usertype') }}
    </p>
</div>

<div class="order-wrapper">
<div class="form-container" style="display: flex; flex-direction: column; align-items: center; padding: 20px; background: rgba(255,255,255,0.95); border-radius:12px; width: 425px; box-shadow:0 4px 12px rgba(0,0,0,0.3);">
    <div id="ajaxMessage"></div>

    <form id="searchForm">
        @csrf
        <input type="hidden" name="userid" value="{{ session('userid') }}">

        <!-- Item Name -->
    <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center; position: relative;">
        <label for="itemname" autocomplete="off" style="width: 120px; font-weight: bold;">Search item:</label>
        <input type="text" id="itemname" name="itemname" placeholder="Enter item name" autocomplete="off"
            style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;">

        <div id="suggestions"
            style="position:absolute; top:100%; left:120px; right:0; background:#fff; border:1px solid #ccc; max-height:150px; overflow-y:auto; z-index:1000;">
        </div>
    </div>

    <!-- Item ID -->
    <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center;">
        <label for="itemid" style="width: 120px; font-weight: bold;">Item ID:</label>
        <input type="text" id="itemid" name="itemid"  readonly style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;">
    </div>

    <!-- Master Name -->
    <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center; position: relative;">
        <label for="mastername" style="width: 120px; font-weight: bold;">Item Name:</label>
        <input type="text" id="mastername" name="mastername" readonly style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;">
    </div>

    

    <!-- Type of Item -->
    <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center;">
        <label for="typeitemname" style="width: 120px; font-weight: bold;">Type of Item:</label>
        <input type="text" id="typeitemname" name="typeitemname" readonly  style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;">
    </div>


    <!-- Sale Price -->
    <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center;">
        <label for="saleprice" style="width: 120px; font-weight: bold;">Stock Qty:</label>
        <input 
            type="text" 
            id="saleprice" 
            name="saleprice" 
            style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;"
            readonly 
            oninput="validateDecimal(this)" 
        >
    </div>

    <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center;">
        <label for="unit" style="width: 120px; font-weight: bold;">Unit:</label>
        <input type="text" id="unit" name="unit" readonly style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;">
    </div>

    <!-- Date Range -->
    <div style="display:flex; margin-bottom:15px; width:100%; align-items:center;">
        <label style="width:120px; font-weight:bold;">Date:</label>

        <input type="date" id="from_date" name="from_date"
            style="padding:8px; border-radius:5px; border:1px solid #ccc; margin-right:10px;">

        <span style="margin-right:10px; font-weight:bold;">to</span>

        <input type="date" id="to_date" name="to_date"
            style="padding:8px; border-radius:5px; border:1px solid #ccc;">
    </div>

    <div style="display:flex; justify-content:center; align-items:center; margin-bottom:15px; width:100%;">
        <button type="submit" id="saveBtn">Search</button>

    </div>

</div>

<div class="table-container">
    <table id="itemTable" border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Details</th>
                <th>Stock in</th>
                <th>Stock Out</th>
                <th>Balance</th>
                <th>Date/Time</th>
            </tr>
        </thead>
        <tbody id="itemTableBody"></tbody>
    </table>
</div>
</div>

<!--- Search item --->
<script>
const topLevels = JSON.parse('{!! json_encode($inventory_items) !!}');
const masterInput = document.getElementById('itemname');
const masterId = document.getElementById('itemid');
const masterNameField = document.getElementById('mastername');
const typeItemField = document.getElementById('typeitemname');
const salePriceField = document.getElementById('saleprice');
const unitField = document.getElementById('unit');
const suggestionBox = document.getElementById('suggestions');

const itemTableBody = document.querySelector('#itemTable tbody');

let selectedIndex = -1;
let currentMatches = [];

function closeSuggestions() {
    suggestionBox.innerHTML = '';
    selectedIndex = -1;
    currentMatches = [];
}

function renderSuggestions(matches) {
    suggestionBox.innerHTML = '';
    currentMatches = matches;

    matches.forEach((match, index) => {
        const div = document.createElement('div');
        div.textContent = match.cat_name + ' - ' + match.item_name;
        div.style.padding = '6px 8px';
        div.style.cursor = 'pointer';
        div.style.fontSize = '14px';

        if (index === selectedIndex) {
            div.style.background = '#e6e6e6';
            // Scroll the selected item into view
            setTimeout(() => div.scrollIntoView({ block: 'nearest' }), 0);
        }

        div.addEventListener('click', () => selectItem(index));

        suggestionBox.appendChild(div);
    });
}

function selectItem(index) {
    if (index < 0 || index >= currentMatches.length) return;

    const item = currentMatches[index];

    masterInput.value = item.cat_name + ' - ' + item.item_name;
    masterId.value = item.item_id;
    masterNameField.value = item.cat_name;
    typeItemField.value = item.item_name;
    salePriceField.value = item.qty;
    unitField.value = item.unit;

    closeSuggestions();
}

// ---------- INPUT EVENT ----------
masterInput.addEventListener('input', function() {
    const query = this.value.toLowerCase();
    
    masterId.value = '';
    masterNameField.value = '';
    typeItemField.value = '';
    salePriceField.value = '';
    unitField.value = '';
    itemTableBody.innerHTML = '';

    if (query.length === 0) return closeSuggestions();

    const matches = topLevels.filter(d =>
        (d.cat_name + ' - ' + d.item_name).toLowerCase().includes(query)
    );

    if (matches.length === 0) return closeSuggestions();

    selectedIndex = -1;
    renderSuggestions(matches);
});

// ---------- KEYBOARD EVENTS ----------
masterInput.addEventListener('keydown', function(e) {
    if (currentMatches.length === 0) return;

    if (e.key === 'ArrowDown') {
        selectedIndex = (selectedIndex + 1) % currentMatches.length;
        renderSuggestions(currentMatches);
        e.preventDefault();
    } else if (e.key === 'ArrowUp') {
        selectedIndex = (selectedIndex - 1 + currentMatches.length) % currentMatches.length;
        renderSuggestions(currentMatches);
        e.preventDefault();
    } else if (e.key === 'Enter') {
        if (selectedIndex >= 0) {
            selectItem(selectedIndex);
            e.preventDefault();
        }
    }
});

// ---------- CLOSE ON CLICK OUTSIDE ----------
document.addEventListener('click', e => {
    if (!e.target.closest('#itemname')) closeSuggestions();
});



</script>

<!--- Show Stock Item Ledger Transactions in Table --->
<script>
function showMessage(msg, color='red'){
    const ajaxMessage = document.getElementById('ajaxMessage');
    ajaxMessage.textContent = msg;
    ajaxMessage.style.color = color;
    setTimeout(() => { ajaxMessage.textContent = ''; }, 3000);
}

document.getElementById('searchForm').addEventListener('submit', function(e){
    e.preventDefault(); // prevent default form submit

    const item_id = document.getElementById('itemid').value;
    const startdate = document.getElementById('from_date').value;
    const enddate = document.getElementById('to_date').value;
    const itemTableBody = document.querySelector('#itemTable tbody');

    if (!item_id || !startdate || !enddate) {
        showMessage('Required fields are empty', 'red');
        return;
    }

    if (startdate > enddate) {
        showMessage('From date cannot be greater than To date', 'red');
        return;
    }

    fetch("{{ route('stock_item_ledger_details') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            itemid: item_id,
            from_date: startdate,
            to_date: enddate
        })
    })
    .then(res => res.json())
    .then(data => {
        // Clear table first
        itemTableBody.innerHTML = '';

        // --- From-To Date Row ---
        const dateRow = document.createElement('tr');
        dateRow.innerHTML = `
            <td style="font-weight:bold; color:#555;" colspan="5">
                From: ${startdate} &nbsp;&nbsp; To: ${enddate}
            </td>
        `;
        itemTableBody.appendChild(dateRow);

        // --- Opening Balance Row ---
        const openingBalance = parseFloat(data.opening_balance) || 0;
        const openingTr = document.createElement('tr');
        openingTr.innerHTML = `
            <td style="font-weight:bold; color:#555;">Opening Balance</td>
            <td></td>
            <td></td>
            <td>${openingBalance.toFixed(2)}</td>
            <td></td>
        `;
        itemTableBody.appendChild(openingTr);

        const transactions = data.ledger || [];
        if(transactions.length === 0){
            showMessage('No transactions found for selected date range', 'red');
            return;
        }

        // --- Transactions Rows ---
        let runningBalance = openingBalance;
        transactions.forEach(row => {
            if(row.type === 'IN') runningBalance += parseFloat(row.qty);
            else if(row.type === 'OUT') runningBalance -= parseFloat(row.qty);

            const tr = document.createElement('tr');

            let stockIn  = row.type === 'IN'  ? parseFloat(row.qty).toFixed(2) : '';
            let stockOut = row.type === 'OUT' ? parseFloat(row.qty).toFixed(2) : '';

            // Conditional Details
            let details = '';
            if(row.type === 'IN'){
                details = `Purchase Order#: ${row.order_id || '-'} ,  Bill#: ${row.bill_no || '-'}`;
            } else if(row.type === 'OUT'){
                details = `Batch #: ${row.batch_no || '-'}`;
            }

            tr.innerHTML = `
                <td>${details}</td>
                <td>${stockIn}</td>
                <td>${stockOut}</td>
                <td>${runningBalance.toFixed(2)}</td>
                <td>${row.date}</td>
            `;
            itemTableBody.appendChild(tr);
        });
    })
    .catch(err => {
        console.error(err);
        showMessage('Error fetching transactions', 'red');
    });
});
</script>









@endsection