@if(!session()->has('userid'))
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
@endif


@extends('layouts.dashboard')


@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<br>
<h1 style="display: inline; color:#330000;">Purchase Order Updation & Status</h1>


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







</style>

<!--- User Details Div --->
<div class="user-info">
    <p>
        User: {{ session('username') }} <br>
        Role: {{ session('usertype') }}
    </p>
</div>

<div class="order-wrapper">
<div class="form-container" style="display: flex; flex-direction: column; align-items: center; padding: 20px; background: rgba(255,255,255,0.95); border-radius:12px; width: 400px; box-shadow:0 4px 12px rgba(0,0,0,0.3);">
    <div id="ajaxMessage"></div>

    <form action="{{ route('clear_purchase_order_status') }}" id="searchForm" method="post">
        @csrf
        <input type="hidden" name="userid" value="{{ session('userid') }}">

        <!-- Search Order -->
        <div style="display:flex; margin-bottom:15px; width:100%; align-items:center; position:relative;">
            <label for="itemname" style="width:120px; font-weight:bold;">Search Order:</label>
            <input type="text" id="itemname" name="itemname" placeholder="Enter Order no" autocomplete="off" style="flex:1; padding:8px; border-radius:5px; border:1px solid #ccc;">
            <div id="suggestions" style="position:absolute; top:100%; left:120px; right:0; background:#fff; border:1px solid #ccc; max-height:150px; overflow-y:auto; z-index:1000;"></div>
        </div>

        <!-- Order no -->
        <div style="display:flex; margin-bottom:15px; width:100%; align-items:left;">
            <label for="itemid" style="width:120px; font-weight:bold;">Purchase Order no:</label>
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

                <!-- Clear Button -->
                <button type="button" id="clearBtn">Clear</button>
        
    </form>
    <br>
    <br>
    <h3>Add item in existing order</h3>
    <br>
    <form id="form1" method="POST">
        @csrf

        <input type="hidden" name="userid" value="{{ session('userid') }}">
        <!-- Item Name -->
        <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center; position: relative;">
            <label for="itemname1" style="width: 120px; font-weight: bold;">Search item:</label>
            <input type="text" id="itemname1" name="itemname1" placeholder="Enter item name" autocomplete="off"
                style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;">

            <div id="suggestions1"
                style="position:absolute; top:100%; left:120px; right:0; background:#fff; border:1px solid #ccc; max-height:150px; overflow-y:auto; z-index:1000;">
            </div>
        </div>

        <!-- Item ID -->
        <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center;">
            <label for="itemid1" style="width: 120px; font-weight: bold;">Item ID:</label>
            <input type="text" id="itemid1" name="itemid1"  readonly style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;">
        </div>

        <!-- Master Name -->
        <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center; position: relative;">
            <label for="mastername1" style="width: 120px; font-weight: bold;">Item Name:</label>
            <input type="text" id="mastername1" name="mastername1" readonly style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;">
        </div>

        

        <!-- Type of Item -->
        <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center;">
            <label for="typeitemname1" style="width: 120px; font-weight: bold;">Type of Item:</label>
            <input type="text" id="typeitemname1" name="typeitemname1" readonly  style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;">
        </div>


        <!-- Sale Price -->
        <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center;">
            <label for="saleprice1" style="width: 120px; font-weight: bold;">Price:</label>
            <input 
                type="text" 
                id="saleprice1" 
                name="saleprice1" 
                style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;"
                readonly 
                oninput="validateDecimal(this)" 
            >
        </div>

        <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center;">
            <label for="unit" style="width: 120px; font-weight: bold;">Unit:</label>
            <input type="text" id="unit" name="unit" readonly style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;">
        </div>

        <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center;">
            <label for="qtylabel" style="width: 120px; font-weight: bold;">Quantity:</label>
            <input type="text" id="qty1" name="qty1" placeholder="Enter item quantity" autocomplete="off" style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;">
        </div>
        
        <button type="button" id="addItemBtn" style="padding: 10px 20px; background: #4a57ed; color:white; border:none; border-radius:5px; cursor:pointer; margin-top:10px;">
            Add
        </button>
    </form>
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
                <th>Unit</th>
                <th>Qty Received</th>
            </tr>
        </thead>
        <tbody id="itemTableBody"></tbody>
    </table>
    <div id="lcd"><span id="lcdValue">0.00</span></div>
</div>

</div>


<!--- For Order Search/edit/delete ---> 
<script>
const searchInput = document.getElementById('itemname');
const orderNoField = document.getElementById('itemid');
const addedByField = document.getElementById('mastername');
const dateTimeField = document.getElementById('typeitemname');
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
    const o=currentMatches[i];
    searchInput.value=o.order_id;
    orderNoField.value=o.order_id;
    addedByField.value=o.user;
    dateTimeField.value=o.add_date+" / "+o.add_time;
    closeSuggestions();
    fetchOrderDetails(o.order_id);
}

// ===== Input Events =====
searchInput.addEventListener("input",function(){
    const q=this.value.toLowerCase();
    orderNoField.value=""; addedByField.value=""; dateTimeField.value="";
    itemTableBody.innerHTML=""; lcdValue.textContent="0.00";
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
            <td contenteditable="true" style="background-color:#fff8dc; cursor:text;">${qty}</td>
            <td>${total.toFixed(2)}</td>
            <td>${item.unit}</td>
            <td>${item.received}</td>
            
        `;
        itemTableBody.appendChild(tr);

        const qtyCell = tr.children[3];
        const totalCell = tr.children[4];
        const statusValue = parseFloat(tr.children[6].textContent.trim()) || 0;


        if(statusValue === 0){
            // ===== Qty edit =====
            qtyCell.addEventListener('blur', () => {
                let newQty = parseFloat(qtyCell.textContent);
                if(isNaN(newQty) || newQty <= 0) newQty = 1;

                if(newQty !== qty){
                    const confirmChange = confirm(
                        `Do you want to change item quantity?\nPrevious Quantity: ${qty}\nNew Quantity: ${newQty}`
                    );

                    if(confirmChange){
                        fetch(`/update_purchase_order_qty/${item.item_id}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ qty: newQty, order_id: orderNoField.value })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if(data.success){
                                qty = newQty;
                                qtyCell.textContent = qty;
                                totalCell.textContent = (salePrice * qty).toFixed(2);
                                updateGrandTotal();

                                // ✅ Show success message
                                ajaxMessage.style.display = 'block';
                                ajaxMessage.style.background = '#d4edda';
                                ajaxMessage.style.color = '#155724';
                                ajaxMessage.textContent = data.message;
                                setTimeout(() => { ajaxMessage.style.display = 'none'; }, 3000);

                            } else {
                                alert('Update failed!');
                                qtyCell.textContent = qty;
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            qtyCell.textContent = qty;
                            alert('Error updating quantity!');
                        });
                    } else {
                        qtyCell.textContent = qty;
                        totalCell.textContent = (salePrice * qty).toFixed(2);
                    }
                } else {
                    qtyCell.textContent = qty;
                    totalCell.textContent = (salePrice * qty).toFixed(2);
                }
            });
        }
        
        qtyCell.addEventListener('keydown', e => {
            if(e.key === 'Enter'){
                e.preventDefault();
                qtyCell.blur();
            }
        });
    });
    

    updateGrandTotal();
}

function updateGrandTotal(){
    let sum = 0;
    itemTableBody.querySelectorAll('tr').forEach(row => {
        const val = parseFloat(row.children[4].textContent) || 0;
        sum += val;
    });
    lcdValue.textContent = sum.toFixed(2);
}
</script>

<!--- Decimal Validation --->
<script>
const qtyInput = document.getElementById('qty1');

qtyInput.addEventListener('input', function() {
    // Allow numbers with up to 2 decimal places (10,2)
    this.value = this.value
        .replace(/[^0-9.]/g, '')          // digits + dot only
        .replace(/(\..*)\./g, '$1');      // only one dot

    // Limit to 2 decimal places
    if (this.value.includes('.')) {
        let parts = this.value.split('.');
        parts[1] = parts[1].slice(0, 2);
        this.value = parts.join('.');
    }

    // Ensure >= 1
    if (this.value === '' || parseFloat(this.value) < 0) {
        this.value = '';
    }
});
</script>

<!--- For Stock item --->
<script>
const topLevelData = JSON.parse('{!! json_encode($topLevelData) !!}');
const masterInput = document.getElementById('itemname1');
const masterId = document.getElementById('itemid1');
const masterNameField = document.getElementById('mastername1');
const typeItemField = document.getElementById('typeitemname1');
const salePriceField = document.getElementById('saleprice1');
const unitField = document.getElementById('unit');
const suggestionBox1 = document.getElementById('suggestions1');

let selectedIndex1 = -1;
let currentMatches1 = [];

function closeSuggestions1() {
    suggestionBox1.innerHTML = '';
    selectedIndex1 = -1;
    currentMatches1 = [];
}

function renderSuggestions1(matches) {
    suggestionBox1.innerHTML = '';
    currentMatches1 = matches;

    matches.forEach((match, index) => {
        const div = document.createElement('div');
        div.textContent = match.master_name + ' - ' + match.item_name;
        div.style.padding = '6px 8px';
        div.style.cursor = 'pointer';
        div.style.fontSize = '14px';

        if (index === selectedIndex1) {
            div.style.background = '#e6e6e6';
            // Scroll the selected item into view
            setTimeout(() => div.scrollIntoView({ block: 'nearest' }), 0);
        }

        div.addEventListener('click', () => selectItem(index));

        suggestionBox1.appendChild(div);
    });
}



function selectItem(index) {
    if (index < 0 || index >= currentMatches1.length) return;

    const item = currentMatches1[index];

    masterInput.value = item.master_name + ' - ' + item.item_name;
    masterId.value = item.item_id;
    masterNameField.value = item.master_name;
    typeItemField.value = item.item_name;
    salePriceField.value = item.sale_price;
    unitField.value = item.unit

    closeSuggestions1();
}

// ---------- INPUT EVENT ----------
masterInput.addEventListener('input', function() {
    const query = this.value.toLowerCase();
    
    masterId.value = '';
    masterNameField.value = '';
    typeItemField.value = '';
    salePriceField.value = '';
    unitField.value = '';

    if (query.length === 0) return closeSuggestions1();

        const matches = topLevelData.filter(d => 
        (d.master_name + ' - ' + d.item_name).toLowerCase().includes(query)
    );

    if (matches.length === 0) return closeSuggestions1();

    selectedIndex1 = -1;
    renderSuggestions1(matches);
});

// ---------- KEYBOARD EVENTS ----------
masterInput.addEventListener('keydown', function(e) {
    if (currentMatches1.length === 0) return;

    if (e.key === 'ArrowDown') {
        selectedIndex1 = (selectedIndex1 + 1) % currentMatches1.length;
        renderSuggestions1(currentMatches1);
        e.preventDefault();
    } else if (e.key === 'ArrowUp') {
        selectedIndex1 = (selectedIndex1 - 1 + currentMatches1.length) % currentMatches1.length;
        renderSuggestions1(currentMatches1);
        e.preventDefault();
    } else if (e.key === 'Enter') {
        if (selectedIndex1 >= 0) {
            selectItem(selectedIndex1);
            e.preventDefault();
        }
    }
});

// ---------- CLOSE ON CLICK OUTSIDE ----------
document.addEventListener('click', e => {
    if (!e.target.closest('#itemname1') && !e.target.closest('#suggestions1')) {
        closeSuggestions1();
    }
});

</script>


<!--- Add item in existing order --->
<script>
document.getElementById("addItemBtn").addEventListener("click", () => {

    const orderId = document.getElementById('itemid').value;
    const itemId = document.getElementById('itemid1').value;
    const saleprice1 = document.getElementById('saleprice1').value;
    const qty = document.getElementById('qty1').value;

    if(!orderId){
        showMessage('Order ID is empty! Select an order first.', 'red');
        return;
    }
    if(!itemId){
        showMessage('Select an item!', 'red');
        return;
    }

    if(!saleprice1 || parseFloat(saleprice1) <= 0){
        showMessage('Sale price is invalid!', 'red');
        return;
    }

    fetch("{{ route('add_item_exiting_purchase_order') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            order_id: orderId,
            item_id: itemId,
            price: saleprice1,
            qty: parseFloat(qty)
        })
    })
    .then(res => res.json())
    .then(data => {
        showMessage(data.message, data.success ? 'green' : 'red');
        if(data.success){
            fetchOrderDetails(orderId); // reload table
            // Clear fields
            document.getElementById('itemname1').value = '';
            document.getElementById('itemid1').value = '';
            document.getElementById('mastername1').value = '';
            document.getElementById('typeitemname1').value = '';
            document.getElementById('saleprice1').value = '';
            document.getElementById('qty1').value = '';
            document.getElementById('unit').value = '';
        }
    })
    .catch(err => console.error(err));
});
function showMessage(msg, color = 'green') {
    const ajaxMessage = document.getElementById('ajaxMessage');
    ajaxMessage.style.display = 'block';
    ajaxMessage.style.background = color === 'green' ? '#d4edda' : '#f8d7da';
    ajaxMessage.style.color = color === 'green' ? '#155724' : '#721c24';
    ajaxMessage.textContent = msg;
    setTimeout(() => { ajaxMessage.style.display = 'none'; }, 1000);
}


</script>


<!--- For Clear Order Status --->
<script>
    document.getElementById('clearBtn').addEventListener('click', function(e){
    e.preventDefault();

    const form = document.getElementById('searchForm');
    const orderId = document.getElementById('itemid').value;
    const userid = form.querySelector('input[name="userid"]').value;
    const username = @json(session('username'));

    if(!orderId){
        alert('Order ID is empty!');
        return;
    }

    const formData = new FormData();
    formData.append('itemid', orderId);
    formData.append('userid', userid);

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            // Custom popup
            const msg = `Order No : ${orderId}\nClear by : ${username}\nOrder has been cleared`;
            alert(msg);
            location.reload();
        } else {
            alert(data.message || 'Something went wrong!');
        }
    })
    .catch(err => {
        console.error(err);
        alert('Something went wrong! ' + err.message);
    });
});

</script>














@endsection