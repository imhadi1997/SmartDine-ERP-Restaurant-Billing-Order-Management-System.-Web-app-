@if(!session()->has('userid'))
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
@endif


@extends('layouts.dashboard')


@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<br>
<h1 style="display: inline; color:#330000;">Employee Ledger</h1>


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

        <!-- Search Order -->
        <div style="display:flex; margin-bottom:15px; width:100%; align-items:center; position:relative;">
            <label for="itemname" style="width:120px; font-weight:bold;">Search Employee:</label>
            <input type="text" id="itemname" name="itemname" placeholder="Enter employee name" autocomplete="off" style="flex:1; padding:8px; border-radius:5px; border:1px solid #ccc;">
            <div id="suggestions" style="position:absolute; top:100%; left:120px; right:0; background:#fff; border:1px solid #ccc; max-height:150px; overflow-y:auto; z-index:1000;"></div>
        </div>

        <!-- Order no -->
        <div style="display:flex; margin-bottom:15px; width:100%; align-items:center;">
            <label for="itemid" style="width:120px; font-weight:bold;">Employee id:</label>
            <input type="text" id="itemid" name="itemid" readonly style="flex:1; padding:8px; border-radius:5px; border:1px solid #ccc;">
        </div>

        <!-- Added By -->
        <div style="display:flex; margin-bottom:15px; width:100%; align-items:center;">
            <label for="mastername" style="width:120px; font-weight:bold;">Name:</label>
            <input type="text" id="mastername" name="mastername" readonly style="flex:1; padding:8px; border-radius:5px; border:1px solid #ccc;">
        </div>

        <!-- Add date/time -->
        <div style="display:flex; margin-bottom:15px; width:100%; align-items:center;">
            <label for="typeitemname" style="width:120px; font-weight:bold;">Contact:</label>
            <input type="text" id="typeitemname" name="typeitemname" readonly style="flex:1; padding:8px; border-radius:5px; border:1px solid #ccc;">
        </div>

        <!-- Cleared By -->
        <div style="display:flex; margin-bottom:15px; width:100%; align-items:center;">
            <label for="mastername" style="width:120px; font-weight:bold;">Status:</label>
            <input type="text" id="mastername2" name="mastername2" readonly style="flex:1; padding:8px; border-radius:5px; border:1px solid #ccc;">
        </div>

        <!-- Add date/time -->
        <div style="display:flex; margin-bottom:15px; width:100%; align-items:center;">
            <label for="typeitemname" style="width:120px; font-weight:bold;">Balance:</label>
            <input type="text" id="typeitemname2" name="typeitemname2" readonly style="flex:1; padding:8px; border-radius:5px; border:1px solid #ccc;">
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
        </form>
        <br>
        <br>
        <h3 style="align-items: center;">Payment details</h3>
        <br>
    </form>

    @if(session('success'))
    <div id="success" style="padding:10px; background:#d4edda; color:#155724; border-radius:5px; margin-bottom:10px;">
        {{ session('success') }}
    </div>
    @endif

    @if(session('Empty'))
        <div id="Empty" style="padding:10px; background:#f8d7da; color:#721c24; border-radius:5px; margin-bottom:10px;">
            {{ session('Empty') }}
        </div>
    @endif

    <form action="{{ route('save_employee_ledger_transaction') }}"  method="POST">
        @csrf
        <input type="hidden" name="itemid" id="save_itemid">
        <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center;">
            <label for="saleprice" style="width: 120px; font-weight: bold;">Amount:</label>
            <input 
                type="text" 
                id="paidamount" 
                name="paidamount" 
                style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;"
                placeholder="Enter amount"
                autocomplete="off"
                oninput="validateDecimal(this)" 
            >
        </div>

        <div style="display:flex; margin-bottom:15px; width:100%; align-items:center;">
            <label for="trans_details" style="width:120px; font-weight:bold;">Details:</label>
            <input type="text" id="trans_details" name="trans_details" autocomplete="off" placeholder="Enter transaction description" style="flex:1; padding:8px; border-radius:5px; border:1px solid #ccc;">
        </div>

        <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center;">
            <label for="saleprice" style="width: 120px; font-weight: bold;">Type:</label>
            <select 
                id="transaction_type" 
                name="transaction_type"
                style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;"
            >
                <option value="">Select</option>
                <option value="Credit">Credit</option>
                <option value="Debit">Debit</option>
            </select>

        </div>

        <div style="display:flex; justify-content:center; align-items:center; margin-bottom:15px; width:100%;">
            <!-- Save Button -->
            <button type="submit" id="savecreditBtn" style="padding: 10px 20px; background: #4a57ed; color:white; border:none; border-radius:5px; cursor:pointer;">Save</button>
        </div>
            
        </div>
    </form>


<div class="table-container">
    <table id="itemTable" border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Details</th>
                <th>Credit</th>
                <th>Debit</th>
                <th>Balance</th>
                <th>Date/Time</th>
                <th>Add by</th>
            </tr>
        </thead>
        <tbody id="itemTableBody"></tbody>
    </table>
</div>

</div>

<!--- Decimal Validation --->
<script>
function validateDecimal(input) {
    let v = input.value;

    // Remove everything except digits and dot
    v = v
        .replace(/[^0-9.]/g, '')         
        .replace(/(\..*)\./g, '$1');

    // Prevent first character being dot or 0
    if (v.startsWith('0')) {
        v = '';
    }

    // Allow only one dot
    let parts = v.split('.');
    if (parts.length > 2) {
        v = parts[0] + '.' + parts[1];
    }

    // Limit decimals to 2
    if (parts[1] && parts[1].length > 2) {
        parts[1] = parts[1].substring(0, 2);
        v = parts.join('.');
    }

    // Total length limit (10,2)
    const numericOnly = v.replace('.', '');
    if (numericOnly.length > 10) {
        input.value = input.getAttribute("data-last") || '';
        return;
    }

    // Save last valid value
    input.setAttribute("data-last", v);
    input.value = v;
}
</script>


<!--- Employee Search --->
<script>
const searchInput = document.getElementById('itemname');
const orderNoField = document.getElementById('itemid');
const addedByField = document.getElementById('mastername');
const dateTimeField = document.getElementById('typeitemname');
const clearbyfield = document.getElementById('mastername2');
const clearbydatefield = document.getElementById('typeitemname2');
const suggestionBox = document.getElementById('suggestions');
const itemTableBody = document.querySelector('#itemTable tbody');
const ajaxMessage = document.getElementById('ajaxMessage');

let orders = JSON.parse('{!! json_encode($allemployee) !!}');
let selectedIndex = -1;
let currentMatches = [];

// ===== Suggestion Box =====
function closeSuggestions() {
    suggestionBox.innerHTML = "";
    selectedIndex = -1;
    currentMatches = [];
}
function renderSuggestions(list) {
    suggestionBox.innerHTML = "";
    currentMatches = list;
    list.forEach((order,i)=>{
        const div=document.createElement('div');
        div.textContent = order.name;
        div.style.padding="6px 8px"; div.style.cursor="pointer"; div.style.fontSize="14px";
        if(i===selectedIndex) div.style.background="#e6e6e6";
        div.addEventListener("click",()=>selectOrder(i));
        suggestionBox.appendChild(div);
    });
}
function selectOrder(i){
    const o = currentMatches[i];
    searchInput.value = o.name;
    orderNoField.value = o.emp_id;
    document.getElementById('save_itemid').value = o.emp_id;
    addedByField.value = o.name;

    clearbyfield.value = o.status ? 'Active' : 'Deactivate';
    clearbyfield.style.color = o.status ? 'green' : 'red';

    dateTimeField.value = o.cell; 
    clearbydatefield.value = o.current_balance;
    closeSuggestions();
}

// ===== Input Events =====
searchInput.addEventListener("input",function(){
    const q=this.value.toLowerCase();
    orderNoField.value=""; addedByField.value=""; dateTimeField.value="";
    clearbyfield.value=""; clearbydatefield.value="";
    itemTableBody.innerHTML="";
    document.getElementById('save_itemid').value = '';
    if(!q) return closeSuggestions();
    const matches = orders.filter(o=>o.name.toLowerCase().includes(q));
    if(!matches.length) return closeSuggestions();
    selectedIndex=-1; renderSuggestions(matches);
});
searchInput.addEventListener("keydown",function(e){
    if(!currentMatches.length) return;
    if(e.key==="ArrowDown"){ selectedIndex=(selectedIndex+1)%currentMatches.length; renderSuggestions(currentMatches); e.preventDefault(); }
    else if(e.key==="ArrowUp"){ selectedIndex=(selectedIndex-1+currentMatches.length)%currentMatches.length; renderSuggestions(currentMatches); e.preventDefault(); }
    else if(e.key==="Enter"){ if(selectedIndex>=0){ selectOrder(selectedIndex); e.preventDefault(); } }
});
document.addEventListener("click", e => {
    if(!e.target.closest('#itemname') && !e.target.closest('#suggestions')) {
        closeSuggestions();
    }
});
</script>


<!--- Show Transactions in table --->
<script>
function showMessage(msg, color='red'){
    const ajaxMessage = document.getElementById('ajaxMessage');
    ajaxMessage.textContent = msg;
    ajaxMessage.style.color = color;
    setTimeout(()=>{ ajaxMessage.textContent=''; }, 3000);
}

document.getElementById('searchForm').addEventListener('submit', function(e){
    e.preventDefault(); // prevent default submit

    const emp_id = document.getElementById('itemid').value;
    const startdate = document.getElementById('from_date').value;
    const enddate = document.getElementById('to_date').value;
    const itemTableBody = document.querySelector('#itemTable tbody');

    if (!emp_id || !startdate || !enddate) {
        showMessage('Required fields are empty', 'red');
        return;
    }

    if (startdate > enddate) {
        showMessage('From date cannot be greater than To date', 'red');
        return;
    }

    fetch("{{ route('employee_ledger_details') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            itemid: emp_id,
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
            <td style="font-weight:bold; color:#555;">
                From: ${startdate} &nbsp;&nbsp; To: ${enddate}
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
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
            <td></td>
        `;
        itemTableBody.appendChild(openingTr);

        const transactions = data.transactions || [];

        if(transactions.length === 0){
            showMessage('No transactions found for selected date range', 'red');
            return;
        }

        // --- Transactions Rows ---
        let runningBalance = openingBalance;
        transactions.forEach(row => {
            if(row.type === 'Debit') runningBalance += parseFloat(row.amount);
            else if(row.type === 'Credit') runningBalance -= parseFloat(row.amount);

            const tr = document.createElement('tr');

            let creditVal = row.type === 'Credit' ? parseFloat(row.amount).toFixed(2) : '';
            let debitVal  = row.type === 'Debit' ? parseFloat(row.amount).toFixed(2) : '';

            tr.innerHTML = `
                <td>${row.details}</td>
                <td>${creditVal}</td>
                <td>${debitVal}</td>
                <td>${runningBalance.toFixed(2)}</td>
                <td>${row.date} / ${row.time}</td>
                <td>${row.add_by_user || '-'}</td>
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



<!--- Success Message --->
<script>
setTimeout(() => {
    const success = document.getElementById('success');
    if (success) {
        success.style.display = 'none';
    }
}, 1000);
</script>

<!--- Empty Message --->
<script>
setTimeout(() => {
    const empty = document.getElementById('Empty');
    if (empty) {
        empty.style.display = 'none';
    }
}, 1000);
</script>


@endsection