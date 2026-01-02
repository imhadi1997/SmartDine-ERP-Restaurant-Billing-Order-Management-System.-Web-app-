@if(!session()->has('userid'))
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
@endif


@extends('layouts.dashboard')


@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<h1 style="display: inline; color:#330000;">Purchase Order Bill & Payment</h1>


<style>
.page-title{
    text-align:center;
    width:100%;
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
#itemTable{
    width: 100%;
    max-width: 1200px;
    table-layout: fixed;
}
table th{
    background: #330000;
    color: white;
}
#itemTable th:nth-child(1), #itemTable td:nth-child(1) {
    width: 120px;
}

#itemTable th:nth-child(2), #itemTable td:nth-child(2) {
    width: 350px; 
}

#itemTable th:nth-child(3), #itemTable td:nth-child(3) {
    width: 100px;
}

#itemTable th:nth-child(4), #itemTable td:nth-child(4) {
    width: 100px;
}

#itemTable th:nth-child(5), #itemTable td:nth-child(5) {
    width: 140px;
}

#itemTable th:nth-child(6), #itemTable td:nth-child(6) {
    width: 220px;
}
#itemTable th:nth-child(7), #itemTable td:nth-child(6) {
    width: 150px; 
}
#itemTable th:nth-child(8), #itemTable td:nth-child(6) {
    width: 150px;
}
#itemTable th:nth-child(9), #itemTable td:nth-child(6) {
    width: 120px;
}
#itemTable th:nth-child(10), #itemTable td:nth-child(6) {
    width: 100px;
}

.form-container {
    position: relative;
}

#suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #ccc;
    max-height: 150px;
    overflow-y: auto;
    z-index: 1000;
}

.form-podetails {
    position: center;
}

</style>

<!--- User Details Div --->
<div class="user-info">
    <p>
        User: {{ session('username') }} <br>
        Role: {{ session('usertype') }}
    </p>
</div>


<div class="form-container" style="display: flex; flex-direction: column; align-items: center; padding: 20px; background: rgba(255,255,255,0.95); border-radius:12px; width: 400px; box-shadow:0 4px 12px rgba(0,0,0,0.3);">
    <form id="searchForm" method="post">
        @csrf
        <input type="hidden" name="userid" value="{{ session('userid') }}">

        <!-- Search Order -->
        <label for="itemname" style="width:120px; font-weight:bold;">Search Order:</label>
        <input type="text" id="itemname" name="itemname" placeholder="Enter Order no" autocomplete="off" style="flex:1; padding:8px; border-radius:5px; border:1px solid #ccc;">
        <div id="suggestions" style="position:absolute; top:80%; left:165px; right:0; width: 185px; background:#fff; border:1px solid #ccc; max-height:150px; overflow-y:auto; z-index:1000;"></div>
        
    </form>
</div>


<div id="ajaxMessage" style="display:flex; justify-content:space-between; align-items:center; width:800px; margin:10px auto; padding:10px;"></div>

<!--- New Order Info Div ---> 
<div class="form-podetails" style="display:flex; justify-content:space-between; align-items:center; width:800px; margin:10px auto; padding:10px; background:#330000; color:white; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.2);">
    <div>
        <label style="font-weight:bold;">Purchase Order #: </label>
        <span id="poNumber"></span>
    </div>
    <div>
        <label style="font-weight:bold;">Added By: </label>
        <span id="poAddedBy"></span>
    </div>
    <div>
        <label style="font-weight:bold;">Add Date/Time: </label>
        <span id="poAddedDate"></span>
    </div>

</div>



<div class="table-container">
    <table id="itemTable" border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Item code</th>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Qty Received</th>
                <th>Supplier Details</th>
                <th>Select Supplier</th>
                <th>Total Amount</th>
                <th>New Qty</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="itemTableBody"></tbody>
    </table>
</div>


<!--- For Order Search/edit/delete ---> 
<script>
const searchInput = document.getElementById('itemname');
const suggestionBox = document.getElementById('suggestions');
const itemTableBody = document.querySelector('#itemTable tbody');
const ajaxMessage = document.getElementById('ajaxMessage');
 
let orders = JSON.parse('{!! json_encode($orders) !!}');
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
        div.textContent = order.order_id;
        div.style.padding="6px 8px"; div.style.cursor="pointer"; div.style.fontSize="14px";
        if(i===selectedIndex) div.style.background="#e6e6e6";
        div.addEventListener("click",()=>selectOrder(i));
        suggestionBox.appendChild(div);
    });
}


function selectOrder(i){
    const o=currentMatches[i];
    searchInput.value=o.order_id;

    document.getElementById('poNumber').textContent = o.order_id;
    document.getElementById('poAddedBy').textContent = o.user;
    document.getElementById('poAddedDate').textContent = `${o.add_date} / ${o.add_time}`;

    closeSuggestions();
    fetchOrderDetails(o.order_id);
}
// ===== Input Events =====
searchInput.addEventListener("input",function(){
    const q=this.value.toLowerCase();
    itemTableBody.innerHTML="";
    document.getElementById('poAddedBy').textContent = '';
    document.getElementById('poAddedDate').textContent = '';
    document.getElementById('poNumber').textContent = '';
    if(!q) return closeSuggestions();
    const matches = orders.filter(o=>o.order_id.toLowerCase().includes(q));
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

// ===== Fetch Order Details via AJAX =====
function fetchOrderDetails(orderId){
    fetch(`/post_purchase_order_details/${orderId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(res => res.json())
    .then(data => {
        populateTable(data);
        attachSupplierSearch();
        attachDecimalValidation();
        attachSaveButtonEvents(); 
    })
    .catch(err => console.error(err));
}

// ===== Populate Table =====
function populateTable(details){
    itemTableBody.innerHTML = '';
    let grandTotal = 0;

    details.forEach(item => {
        const salePrice = parseFloat(item.price ?? 0);
        let qty = parseFloat(item.qty ?? 1);
        const total = salePrice * qty;
        grandTotal += total;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${item.item_id}</td>
            <td>${(item.category_name ?? '') + ' - ' + (item.item_name ?? '')}</td>
            <td>${qty}</td>
            <td>${item.unit}</td>
            <td>${item.received}</td>
            <td></td>
            <td contenteditable="true" style="background-color:#fff8dc; cursor:text;"></td>
            <td contenteditable="true" style="background-color:#fff8dc; cursor:text;"></td>
            <td contenteditable="true" style="background-color:#fff8dc; cursor:text;"></td>
            <td><button type="button" class="saveRowBtn" style="padding:5px 10px;background: #4a57ed; color:white;border:none;border-radius:5px;cursor:pointer;">Save</button></td>
        `;
        itemTableBody.appendChild(tr);

        const searchsupplierCell = tr.children[7];

    });
}

</script>


<!--- Supplier Search and Select --->
<script>
/* --- Suggestion Box Setup --- */
const suggestionBox1 = document.createElement('div');
suggestionBox1.classList.add('supplier-suggestions');
suggestionBox1.style.position = 'absolute';
suggestionBox1.style.display = 'none';
suggestionBox1.style.background = '#fff';
suggestionBox1.style.border = '1px solid #ccc';
suggestionBox1.style.maxHeight = '150px';
suggestionBox1.style.overflowY = 'auto';
suggestionBox1.style.zIndex = '9999';
suggestionBox1.style.boxShadow = '0 2px 6px rgba(0,0,0,0.2)';
document.body.appendChild(suggestionBox1);

/* --- Supplier Data from Controller --- */
let supplier = JSON.parse('{!! json_encode($supplier) !!}');
let selectedIndex1 = -1;
let currentMatches1 = [];

/* --- Functions --- */
function closeSuggestions1() {
    suggestionBox1.innerHTML = "";
    suggestionBox1.style.display = 'none';
    selectedIndex1 = -1;
    currentMatches1 = [];
}

function selectSupplier(i, tdCell) {
    const o = currentMatches1[i];
    const row = tdCell.parentElement;
    const supplierCell = row.children[5]; // 6th column
    const selectCell   = tdCell;          // 7th column

    supplierCell.textContent = o.name;       // 6th column update
    selectCell.textContent = o.name;         // 7th column update

    // Data attributes for later use (form submission)
    selectCell.dataset.supplierId = o.supplier_id;
    selectCell.dataset.supplierName = o.name;

    selectCell.textContent = '';
    closeSuggestions1();
}

function attachSupplierSearch() {
    const rows = document.querySelectorAll('#itemTable tbody tr');
    rows.forEach(row => {
        const selectCell = row.children[6]; // 7th column
        selectCell.setAttribute('contenteditable', 'true');

        selectCell.addEventListener('input', function() {
            const query = selectCell.textContent.toLowerCase();
            if(!query) return closeSuggestions1();

            currentMatches1 = supplier.filter(s => s.name.toLowerCase().includes(query));
            if(!currentMatches1.length) return closeSuggestions1();

            // Show suggestion box
            suggestionBox1.style.display = 'block';

            // Position below the cell
            const rect = selectCell.getBoundingClientRect();
            suggestionBox1.style.top = rect.bottom + window.scrollY + 'px';
            suggestionBox1.style.left = rect.left + window.scrollX + 'px';
            suggestionBox1.style.width = rect.width + 'px';
            suggestionBox1.innerHTML = '';

            // Populate suggestions
            currentMatches1.forEach((s, index) => {
                const div = document.createElement('div');
                div.textContent = s.name;
                div.style.padding = '6px 8px';
                div.style.cursor = 'pointer';
                div.style.background = index === selectedIndex1 ? '#e6e6e6' : '#fff';
                div.addEventListener('click', () => selectSupplier(index, selectCell));
                suggestionBox1.appendChild(div);
            });
        });

        // Keyboard navigation
        selectCell.addEventListener('keydown', function(e){
            if(!currentMatches1.length) return;

            if(e.key === 'ArrowDown') {
                selectedIndex1 = (selectedIndex1 + 1) % currentMatches1.length;
                updateSuggestionHighlight();
                e.preventDefault();
            } else if(e.key === 'ArrowUp') {
                selectedIndex1 = (selectedIndex1 - 1 + currentMatches1.length) % currentMatches1.length;
                updateSuggestionHighlight();
                e.preventDefault();
            } else if(e.key === 'Enter') {
                if(selectedIndex1 >= 0){
                    selectSupplier(selectedIndex1, selectCell);
                    e.preventDefault();
                }
            }
        });
    });
}

function updateSuggestionHighlight() {
    const divs = suggestionBox1.querySelectorAll('div');
    divs.forEach((div,i)=>{
        div.style.background = i === selectedIndex1 ? '#e6e6e6' : '#fff';
    });
}

// Close suggestion box on outside click
document.addEventListener('click', e => {
    if(!e.target.closest('#itemTable') && !e.target.closest('.supplier-suggestions')) {
        closeSuggestions1();
    }
});

/* --- IMPORTANT: Call after table is populated --- */
// Example: populateTable(details); attachSupplierSearch();
</script>

<!--- Decimal Validation --->
<script>
function attachDecimalValidation() {
    const rows = document.querySelectorAll('#itemTable tbody tr');
    rows.forEach(row => {
        [row.children[7], row.children[8]].forEach(cell => {
            cell.setAttribute('contenteditable', 'true');

            cell.addEventListener('input', function() {
                let value = this.textContent;

                // Allow numbers with up to 2 decimal places
                value = value.replace(/[^0-9.]/g, '')         // digits + dot only
                             .replace(/(\..*)\./g, '$1');     // only one dot

                if (value.includes('.')) {
                    let parts = value.split('.');
                    parts[1] = parts[1].slice(0, 2);
                    value = parts.join('.');
                }

                if (value === '' || parseFloat(value) < 0) {
                    value = '';
                }

                // Preserve cursor position
                const sel = window.getSelection();
                const range = document.createRange();
                this.textContent = value;

                // Move cursor to end
                range.selectNodeContents(this);
                range.collapse(false);
                sel.removeAllRanges();
                sel.addRange(range);
            });

            // Prevent Enter key from creating new line
            cell.addEventListener('keydown', function(e){
                if(e.key === 'Enter') e.preventDefault();
            });
        });
    });
}
</script>

<!--- Save Button Working --->
<script>
function attachSaveButtonEvents() {
    document.querySelectorAll('.saveRowBtn').forEach(btn => {
        btn.addEventListener('click', function () {
            const row = this.closest('tr');

            // Column indexes (0-based)
            const supplier_id = row.children[6].dataset.supplierId;
            const total_amount = row.children[7].textContent.trim();
            const qty2 = row.children[8].textContent.trim();

            runSecondScript(supplier_id, total_amount, qty2, row);
        });
    });
}
</script>

<!--- Save row data --->
<script>
function runSecondScript(supplier_id, total_amount, qty2, row) {
    const pono = document.getElementById("poNumber").textContent.trim();
    const itemid = row.children[0].textContent.trim();
    if (supplier_id && total_amount && qty2 && pono && itemid) {
        const received_qty = parseFloat(row.children[4].textContent.trim()) || 0;
        const add_qty = parseFloat(qty2) || 0;
        const new_qty = received_qty + add_qty;

        fetch('/save-purchase-order-bill', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                pono: pono,
                itemid: itemid,
                supplier_id: supplier_id,
                total_amount: total_amount,
                qty: qty2
            })
        })
        .then(res => res.json())
        .then(data => {
            showMessage(data.message, data.success ? 'green' : 'red');
            if(data.success){}
                // 10,2 format (2 decimal places)
                row.children[4].textContent = new_qty.toFixed(2);
                // clear only this row
                row.children[6].textContent = '';
                delete row.children[6].dataset.supplierId;

                row.children[7].textContent = '';
                row.children[8].textContent = '';  
                row.children[5].textContent = '';   
        })
        .catch(err => console.error(err));
} else {
    showMessage('Sale price is invalid!', 'red');
}
function showMessage(msg, color = 'green') {
    const ajaxMessage = document.getElementById('ajaxMessage');
    ajaxMessage.style.display = 'block';
    ajaxMessage.style.background = color === 'green' ? '#d4edda' : '#f8d7da';
    ajaxMessage.style.color = color === 'green' ? '#155724' : '#721c24';
    ajaxMessage.textContent = msg;
    setTimeout(() => { ajaxMessage.style.display = 'none'; }, 6000);
}
}
</script>



@endsection