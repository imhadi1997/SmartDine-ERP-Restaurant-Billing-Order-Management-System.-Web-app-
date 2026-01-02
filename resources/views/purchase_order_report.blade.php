@if(!session()->has('userid'))
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
@endif


@extends('layouts.dashboard')


@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<h1 style="display: inline; color:#330000;">Purchase Order Report</h1>


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
    width: 120px;
}

#itemTable th:nth-child(6), #itemTable td:nth-child(6) {
    width: 220px;
}
#itemTable th:nth-child(7), #itemTable td:nth-child(6) {
    width: 100px; 
}
#itemTable th:nth-child(8), #itemTable td:nth-child(6) {
    width: 100px;
}
#itemTable th:nth-child(9), #itemTable td:nth-child(6) {
    width: 120px;
}
#itemTable th:nth-child(10), #itemTable td:nth-child(6) {
    width: 100px;
}
#itemTable th:nth-child(11), #itemTable td:nth-child(6) {
    width: 120px;
}
#itemTable th:nth-child(12), #itemTable td:nth-child(6) {
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

<div class="form-podetails" style="display:flex; justify-content:space-between; align-items:center; width:800px; margin:10px auto; padding:10px; background:#330000; color:white; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.2);">
    <div>
    </div>
    <div>
        <label style="font-weight:bold;">Clear By: </label>
        <span id="poclearBy"></span>
    </div>
    <div>
        <label style="font-weight:bold;">Clear Date/Time: </label>
        <span id="poClearDate"></span>
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
                <th>Bill No</th>
                <th>Total Amount</th>
                <th>Bill Qty</th>
                <th>Price(per unit)</th>
                <th>Date/Time</th>
                <th>Add by</th>
            </tr>
        </thead>
        <tbody id="itemTableBody"></tbody>
    </table>
</div>


<div>
    <button onclick="showBillPopup()" style="padding:8px 20px; background:#ff6600; color:#fff; border:none; border-radius:5px; cursor:pointer;">Print</button>
</div>

<!--- Purchase Order Search --->
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
    document.getElementById('poclearBy').textContent = '';
    document.getElementById('poClearDate').textContent = '';
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
    fetch(`/post-purchase-order-report/${orderId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {

        if (!data || data.length === 0) {
            console.warn('No order data found');
            return;
        }

        /* ===== HEADER DATA (FROM MODEL) ===== */
        document.getElementById('poAddedBy').textContent = data[0].user ?? '';
        document.getElementById('poAddedDate').textContent = (data[0].add_date ?? '') + ' / ' + (data[0].add_time ?? '');

        document.getElementById('poclearBy').textContent = data[0].clear_user ?? '';
        document.getElementById('poClearDate').textContent =(data[0].clear_date ?? '') + ' / ' + (data[0].clear_time ?? '');
        populateOrderTable(data);
    })
}

</script>


<script>
function populateOrderTable(data) {
    const tbody = document.getElementById('itemTableBody');
    tbody.innerHTML = '';

    // Create a tree structure: items as keys
    const tree = {};
    data.forEach(row => {
        const itemId = row.item_id;
        if (!tree[itemId]) {
            tree[itemId] = {
                item_id: row.item_id,
                item_name: (row.category_name) + ' ' + (row.item_name),
                unit: row.unit ?? '',
                qty: row.qty,
                
                bills: []
            };
        }

        // Add bill info
        if (row.bill_no) {
            tree[itemId].bills.push({
                supplier_name: row.supplier_name ?? '',
                bill_no: row.bill_no,
                bill_qty: row.bill_qty,
                bill_price: row.bill_price,
                bill_date: row.bill_date,
                bill_time: row.bill_time,
                bill_user: row.bill_user
            });
        }
    });

    // Loop through tree to create table rows
    Object.values(tree).forEach(item => {
        const totalBillQty = item.bills.reduce((sum, bill) => sum + parseFloat(bill.bill_qty || 0), 0);
        const itemRow = document.createElement('tr');
        itemRow.innerHTML = `
            <td>${item.item_id}</td>
            <td>${item.item_name}</td>
            <td>${item.qty}</td>
            <td>${item.unit}</td>
            <td>${totalBillQty.toFixed(2)}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        `;
        tbody.appendChild(itemRow);

        // Add nested rows for bills
        item.bills.forEach(bill => {
            const billRow = document.createElement('tr');
            const billdatetime = (bill.bill_date) + ' / ' + (bill.bill_time);
            billRow.style.background = '#f9f9f9';
            billRow.innerHTML = `
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>${bill.supplier_name}</td>
                <td>${bill.bill_no}</td>
                <td>${parseFloat(bill.bill_price).toFixed(2)}</td>
                <td>${parseFloat(bill.bill_qty).toFixed(2)}</td>
                <td>${parseFloat(bill.bill_price / bill.bill_qty).toFixed(2)}</td>
                <td>${billdatetime}</td>
                <td>${bill.bill_user}</td>
            `;
            tbody.appendChild(billRow);
        });
    });
}


</script>

<!-- Bill Receipt Section -->
<div id="billModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999;">
    <div style="background:#fff;width:600px;max-width:90%;max-height:85vh;margin:40px auto;display:flex;flex-direction:column;border-radius:8px;">
        <pre id="billText" style="flex:1;overflow-y:auto;padding:15px;white-space:pre-wrap;font-size:14px;line-height:1.3;"></pre>
        <div style="display:flex;justify-content:center;gap:10px;padding:10px;border-top:1px solid #ddd;background:#fff;position:sticky;bottom:0;">
            <button onclick="printBill()" style="padding:8px 20px; background:#ff6600; color:#fff; border:none; border-radius:5px; cursor:pointer;">Print</button>
            <button onclick="closeBill()" style="padding: 10px 20px; background: #4a57ed; color:white; border:none; border-radius:5px; cursor:pointer;">Close</button>
        </div>

        </div>

    </div>
</div>


<!--- Bill Print --->
<script>
function showBillPopup(response) {

    let bill = `
Mehar Baba Restaurant
Baba Paghaa Wala Rajowal Joriya Mehar Toll Plaza Depallpur,
Okara
Contact: 0333-4553258
============================================================
ITEM DETAILS
------------------------------------------------------------
`;

    const rows = document.querySelectorAll('#itemTableBody tr');

    rows.forEach(row => {
        const cells = row.children;

        // 👉 Item main row (item_id exists)
        if (cells[0].textContent.trim() !== '') {
            bill += `
Item: ${cells[1].textContent}
Qty: ${cells[2].textContent} ${cells[3].textContent}
Received: ${cells[4].textContent}
------------------------------------------------------------
`;
        } 
        // 👉 Bill row (supplier / bill info)
        else if (cells[5].textContent.trim() !== '') {
            bill += `
  Supplier : ${cells[5].textContent}
  Bill No  : ${cells[6].textContent}
  Bill Qty : ${cells[8].textContent}
  Amount   : ${cells[7].textContent}
  Rate     : ${cells[9].textContent}
  Date     : ${cells[10].textContent}
  Add By   : ${cells[11].textContent}
------------------------------------------------------------
`;
        }
    });

    bill += `
============================================================
`;

    document.getElementById('billText').textContent = bill;
    document.getElementById('billModal').style.display = 'block';
}

function printBill() {
    const billContent = document.getElementById('billText').textContent;

    // Create a temporary window for printing
    const printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write(`
        <html>
            <head>
                <title>Print Bill</title>
                <style>
                    body { font-family: 'Courier New', monospace; font-size: 14px; white-space: pre; }
                    pre { line-height: 1.3; }
                    button { display: none; } /* hide buttons in print */
                </style>
            </head>
            <body>
                <pre>${billContent}</pre>
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
}

function closeBill() {
    document.getElementById('billModal').style.display = 'none';
}

</script>

@endsection
















