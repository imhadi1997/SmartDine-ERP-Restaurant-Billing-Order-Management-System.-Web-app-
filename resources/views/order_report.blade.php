@if(!session()->has('userid'))
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
@endif


@extends('layouts.dashboard')


@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<br>
<h1 style="display: inline; color:#330000;">Order Report</h1>


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
    width: 120px;
}

#itemTable th:nth-child(2), #itemTable td:nth-child(2) {
    width: 400px; 
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
button {
    position: relative;
    z-index: 9999;
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
<div class="form-container"  style="display: flex; flex-direction: column; align-items: center; padding: 20px; background: rgba(255,255,255,0.95); border-radius:12px; width: 400px; box-shadow:0 4px 12px rgba(0,0,0,0.3);">
    <div id="ajaxMessage"></div>
        <!-- Search Order -->
        <div style="display:flex; margin-bottom:15px; width:100%; align-items:center; position:relative;">
            <label for="itemname" style="width:120px; font-weight:bold;">Search Order:</label>
            <input type="text" id="itemname" name="itemname" placeholder="Enter Order no" autocomplete="off" style="flex:1; padding:8px; border-radius:5px; border:1px solid #ccc;">
            <div id="suggestions" style="position:absolute; top:100%; left:120px; right:0; background:#fff; border:1px solid #ccc; max-height:150px; overflow-y:auto; z-index:1000;"></div>
        </div>

        <!-- Order no -->
        <div style="display:flex; margin-bottom:15px; width:100%; align-items:center;">
            <label for="itemid" style="width:120px; font-weight:bold;">Order no:</label>
            <input type="text" id="itemid" name="itemid" readonly style="flex:1; padding:8px; border-radius:5px; border:1px solid #ccc;">
        </div>

        <!-- Added By -->
        <div style="display:flex; margin-bottom:15px; width:100%; align-items:center;">
            <label for="mastername" style="width:120px; font-weight:bold;">Add by:</label>
            <input type="text" id="mastername" name="mastername" readonly style="flex:1; padding:8px; border-radius:5px; border:1px solid #ccc;">
        </div>

        <!-- Add date/time -->
        <div style="display:flex; margin-bottom:15px; width:100%; align-items:center;">
            <label for="typeitemname" style="width:120px; font-weight:bold;">Add date/time:</label>
            <input type="text" id="typeitemname" name="typeitemname" readonly style="flex:1; padding:8px; border-radius:5px; border:1px solid #ccc;">
        </div>

        <!-- Cleared By -->
        <div style="display:flex; margin-bottom:15px; width:100%; align-items:center;">
            <label for="mastername" style="width:120px; font-weight:bold;">Clear by:</label>
            <input type="text" id="mastername2" name="mastername2" readonly style="flex:1; padding:8px; border-radius:5px; border:1px solid #ccc;">
        </div>

        <!-- Add date/time -->
        <div style="display:flex; margin-bottom:15px; width:100%; align-items:center;">
            <label for="typeitemname" style="width:120px; font-weight:bold;">Clear date/time:</label>
            <input type="text" id="typeitemname2" name="typeitemname2" readonly style="flex:1; padding:8px; border-radius:5px; border:1px solid #ccc;">
        </div>
    <br>
    <br>
    <h3>Payment details</h3>
    <br>
        <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center;">
                <label for="saleprice" style="width: 120px; font-weight: bold;">Paid Amount:</label>
                <input 
                    type="text" 
                    id="paidamount" 
                    name="paidamount" 
                    style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;"
                    readonly
                    oninput="validateDecimal(this)" 
                >
        </div>

        <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center;">
            <label for="saleprice" style="width: 120px; font-weight: bold;">Discount %:</label>
            <input 
                id="discount" 
                name="discount"
                readonly
                style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;"
            >
        </div>
        
        <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center;">
                <label for="saleprice" style="width: 120px; font-weight: bold;">Payable:</label>
                <input 
                    type="text" 
                    id="saleprice1" 
                    name="saleprice1" 
                    style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;"
                    placeholder="After Discount"
                    oninput="validateDecimal(this)" 
                    readonly
                >
        </div>

        <div style="display:flex; margin-bottom:15px; width:100%; align-items:center;">
            <label for="billtypeitemname" style="width:120px; font-weight:bold;">Bill date/time:</label>
            <input type="text" id="billtypeitemname2" name="billtypeitemname2" readonly style="flex:1; padding:8px; border-radius:5px; border:1px solid #ccc;">
        </div>

        <div style="display:flex; margin-bottom:15px; width:100%; align-items:center;">
            <label for="billmastername" style="width:120px; font-weight:bold;">Bill by:</label>
            <input type="text" id="billmastername2" name="billmastername2" readonly style="flex:1; padding:8px; border-radius:5px; border:1px solid #ccc;">
        </div>
</div>

<div class="table-container">
    <table id="itemTable" border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Item code</th>
                <th>Item Name</th>
                <th>Sale Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="itemTableBody"></tbody>
    </table>
    <div id="lcd"><span id="lcdValue">0.00</span></div>
</div>

<div>
    <button onclick="showBillPopup()" style="padding:8px 20px; background:#ff6600; color:#fff; border:none; border-radius:5px; cursor:pointer;">Print</button>
</div>

</div>

<!-- Bill Receipt Section -->
<div id="billModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6); z-index:9999;">
    <div style="background:#fff; width:600px; max-width:90%; margin:40px auto; padding:20px; font-family:'Courier New', monospace; font-size:14px; white-space:pre;">

        <pre id="billText" style="white-space:pre-wrap; font-size:14px; line-height:1.3;"></pre>
        <div style="display:flex; justify-content:center; gap:10px; margin-top:10px;">
            <button onclick="printBill()" style="padding:8px 20px; background:#ff6600; color:#fff; border:none; border-radius:5px; cursor:pointer;">Print</button>
            <button onclick="closeBill()" style="padding: 10px 20px; background: #4a57ed; color:white; border:none; border-radius:5px; cursor:pointer;">Close</button>
        </div>

        </div>

    </div>
</div>

<!--- Search Clear status Order for payment --->
<script>
const searchInput = document.getElementById('itemname');
const orderNoField = document.getElementById('itemid');
const addedByField = document.getElementById('mastername');
const dateTimeField = document.getElementById('typeitemname');
const clearbyfield = document.getElementById('mastername2');
const clearbydatefield = document.getElementById('typeitemname2');
const suggestionBox = document.getElementById('suggestions');
const itemTableBody = document.querySelector('#itemTable tbody');
const lcdValue = document.getElementById('lcdValue');
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
    const o = currentMatches[i];

    searchInput.value  = o.order_id;
    orderNoField.value = o.order_id;

    closeSuggestions();
    fetchOrderDetails(o.order_id);
}



// ===== Input Events =====
searchInput.addEventListener("input",function(){
    const q=this.value.toLowerCase();
    orderNoField.value=""; addedByField.value=""; dateTimeField.value="";
    clearbyfield.value=""; clearbydatefield.value="";
    itemTableBody.innerHTML=""; lcdValue.textContent="0.00";
    // Clear payment info fields
    document.getElementById('paidamount').value = "";
    document.getElementById('discount').value = "";
    document.getElementById('saleprice1').value = "";

    // Clear bill info
    document.getElementById('billtypeitemname2').value = "";
    document.getElementById('billmastername2').value = "";
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
    fetch(`/post-order-report/${orderId}`, {
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
        addedByField.value = data[0].user ?? '';
        dateTimeField.value =
            (data[0].add_date ?? '') + ' / ' + (data[0].add_time ?? '');

        clearbyfield.value = data[0].clear_user ?? '';
        clearbydatefield.value =
            (data[0].clear_date ?? '') + ' / ' + (data[0].clear_time ?? '');

        /* ===== PAYMENT DATA ===== */
        document.getElementById('paidamount').value = data[0].paid ?? '0';
        document.getElementById('discount').value = data[0].discount ?? '0';
        
        document.getElementById('saleprice1').value = data[0].discount ?? '0';

        /* ===== BILL INFO ===== */
        document.getElementById('billtypeitemname2').value = 
        (data[0].bill_date ?? '') + ' / ' + (data[0].bill_time ?? '');
        document.getElementById('billmastername2').value = data[0].bill_user ?? '';

        /* ===== TABLE ===== */
        populateTable(data);

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
            <td>${salePrice.toFixed(2)}</td>
            <td contenteditable="false" style="background-color:#fff8dc; cursor:text;">${qty}</td>
            <td>${total.toFixed(2)}</td>
            <td><button type="button" style="display:none;">Delete</button></td>
        `;
        itemTableBody.appendChild(tr);
    });

    // ===== GRAND TOTAL & PAYABLE =====
    updateGrandTotal(details[0].discount ?? 0);
}

// ===== Update Grand Total and Payable =====
function updateGrandTotal(discountPercentage = 0){
    let sum = 0;
    itemTableBody.querySelectorAll('tr').forEach(row => {
        const val = parseFloat(row.children[4].textContent) || 0;
        sum += val;
    });

    lcdValue.textContent = sum.toFixed(2);

    // Discount calculation
    const payable = sum - discountPercentage;
    document.getElementById('saleprice1').value = payable.toFixed(2);
}

</script>


<!--- Bill Print --->
<script>
function showBillPopup(response) {

    const orderNo = document.getElementById('itemid').value;
    const grandTotal = parseFloat(lcdValue.textContent || 0).toFixed(2);
    const username = document.getElementById('billmastername2').value;
    const discount = document.getElementById('discount').value || '0';
    const payable = document.getElementById('saleprice1').value || '0';
    const paid = document.getElementById('paidamount').value || '0';
    const addDateTime = document.getElementById('typeitemname').value;
    const clearDateTime = document.getElementById('billtypeitemname2').value;
    const remain = Number(paid - payable).toFixed(2);

    const ITEM_W  = 40;
    const QTY_W   = 5;
    const PRICE_W = 8;
    const TOTAL_W = 8;


    let itemsText = '';

    itemsText +=
        'Items'.padEnd(ITEM_W) +
        'Qty'.padStart(QTY_W) +
        'Price'.padStart(PRICE_W) +
        'Total'.padStart(TOTAL_W) + '\n';

    itemsText += '-'.repeat(ITEM_W + QTY_W + PRICE_W + TOTAL_W) + '\n';



    document.querySelectorAll('#itemTable tbody tr').forEach(row => {

    let name  = row.children[1].textContent.trim();
    let qty   = row.children[3].textContent.trim();
    let price = parseFloat(row.children[2].textContent || 0).toFixed(2);
    let total = parseFloat(row.children[4].textContent || 0).toFixed(2);

    // ✂ Item name control
    if (name.length > ITEM_W) {
        name = name.substring(0, ITEM_W - 1);
    }

    itemsText +=
        name.padEnd(ITEM_W) +
        qty.padStart(QTY_W) +
        price.padStart(PRICE_W) +
        total.padStart(TOTAL_W) +
        '\n';
});



    const bill = `
                Mehar Baba Restaurant
Baba Paghaa Wala Rajowal Joriya Mehar Toll Plaza Depallpur,
            Okara
                Contact: 0333-4553258
=====================================================================
Order No: ${orderNo}
Date/Time: ${addDateTime}     Clear by: ${username}
${itemsText}
------------------------------------------------------------------------------------------------------------------------
Grand Total    : ${grandTotal.padStart(8)}
Discount      : ${discount.padStart(8)}
Payable        : ${payable.padStart(8)}
Paid           : ${paid.padStart(8)}
Remain         : ${remain.padStart(8)}
=====================================================================

Thank You!
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
    location.reload();
}

function closeBill() {
    document.getElementById('billModal').style.display = 'none';
    location.reload();
}

</script>





@endsection








































