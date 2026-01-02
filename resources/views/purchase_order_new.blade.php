@extends('layouts.dashboard')


@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<br>
<h1 style="display: inline; color:#330000;">New Purchase Order</h1>


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

<!--- Stock item information --->
<div class="order-wrapper">
<!--- Menu item search div --->
<div class="form-container" style="display: flex; flex-direction: column; align-items: center; padding: 20px; background: rgba(255,255,255,0.95); border-radius:12px; width: 400px; box-shadow:0 4px 12px rgba(0,0,0,0.3);">
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
    <input type="hidden" id="userid" name="userid" value="{{ session('userid') }}">

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
        <label for="saleprice" style="width: 120px; font-weight: bold;">Price:</label>
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

    <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center;">
        <label for="qtylabel" style="width: 120px; font-weight: bold;">Quantity:</label>
        <input type="text" id="qty" name="qty" placeholder="Enter item quantity" autocomplete="off" autocomplete="off" style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;">
    </div>
    
    <!-- Save Button -->
    
    <button type="button" style="padding: 10px 20px; background: #4a57ed; color:white; border:none; border-radius:5px; cursor:pointer; margin-top:10px;">
        Add
    </button>
</div>


<!--- Table --->
<form id="orderForm">
@csrf
    <div class="table-container">
        <table border="1" cellpadding="6" cellspacing="0" id="itemTable"  style="width:100%; max-width:1200px;">
        <thead>
            <tr>
                <th>Item code</th>
                <th>Item Name</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Unit</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
        </table>
        <div id="lcd">
            <span id="lcdValue">0.00</span>
        </div>
    </div>
    <div>
        <button type="submit" id="saveBtn"  style="padding: 10px 20px; background: #ff6600; color:white; border:none; border-radius:5px; cursor:pointer; margin-top:10px;">
            Save
        </button>
    </div>
</form>
</div>

<!--- Decimal Validation --->
<script>
const qtyInput = document.getElementById('qty');

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

<!--- Suggestions of items --->
<script>
const topLevels = JSON.parse('{!! json_encode($topLevels) !!}');
const masterInput = document.getElementById('itemname');
const masterId = document.getElementById('itemid');
const masterNameField = document.getElementById('mastername');
const typeItemField = document.getElementById('typeitemname');
const salePriceField = document.getElementById('saleprice');
const unitField = document.getElementById('unit');
const suggestionBox = document.getElementById('suggestions');

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
        div.textContent = match.master_name + ' - ' + match.item_name;
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

    masterInput.value = item.master_name + ' - ' + item.item_name;
    masterId.value = item.item_id;
    masterNameField.value = item.master_name;
    typeItemField.value = item.item_name;
    salePriceField.value = item.sale_price;
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

    if (query.length === 0) return closeSuggestions();

    const matches = topLevels.filter(d =>
        (d.master_name + ' - ' + d.item_name).toLowerCase().includes(query)
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

<!--- item add in table --->
<script>
const addBtn = document.querySelector('.form-container button[type="button"]');
const table = document.getElementById('itemTable');
const tbody = table.querySelector('tbody');
const grandTotalSpan = document.getElementById('grandTotal');

// function for overall grand total
function updateGrandTotal() {
    let sum = 0;
    const rows = tbody.querySelectorAll("tr");
    rows.forEach((row) => {
        let totalCell = row.children[4];
        if (totalCell) {
            let val = parseFloat(totalCell.textContent);
            if (!isNaN(val)) sum += val;
        }
    });

    document.getElementById("lcdValue").textContent = sum.toFixed(2);
}




function calculateTotal(price, qty) {
    price = parseFloat(price);
    qty = parseFloat(qty);

    if (isNaN(price) || isNaN(qty)) return "0.00";
    return (price * qty).toFixed(2);
}

addBtn.addEventListener('click', function(e) {
    e.preventDefault();

    const id = document.getElementById('itemid').value;
    const masterName = document.getElementById('mastername').value;
    const typeItem = document.getElementById('typeitemname').value;
    const salePrice = document.getElementById('saleprice').value;
    const qty = document.getElementById('qty').value;
    const unit = document.getElementById('unit').value;

    if (!id || !masterName || !typeItem || !salePrice || !qty) {
        alert("Please fill all fields!");
        return;
    }

    // Prevent duplicate
    const existingRows = table.querySelectorAll('tr td:first-child');
    for (let td of existingRows) {
        if (td.textContent === id) {
            alert("Item already exists!");
            return;
        }
    }

    const tr = document.createElement('tr');

    tr.innerHTML = `
        <td>${id}</td>
        <td>${masterName} - ${typeItem}</td>
        <td>${salePrice}</td>
        <td contenteditable="true" style="background-color:#fff8dc; cursor:text;">${qty}</td>
        <td>${(parseFloat(salePrice)*parseFloat(qty)).toFixed(2)}</td>
        <td>${unit}</td>
        <td><button type="button" style="padding:5px 10px;background:#dc3545;color:#fff;border:none;border-radius:5px;cursor:pointer;">Delete</button></td>
    `;

    tbody.appendChild(tr);

    // Update grand total immediately
    updateGrandTotal();

    // Delete button handler
    tr.querySelector('button').addEventListener('click', () => {
        tr.remove();
        updateGrandTotal();
    });

    // Qty editable handler
    const tdQty = tr.children[3];
    const tdTotal = tr.children[4];

    tdQty.addEventListener('input', function() {
        let val = this.textContent;

        // Remove invalid characters (allow only digits and one dot)
        val = val.replace(/[^0-9.]/g, '');

        // Only allow one decimal point
        const parts = val.split('.');
        if (parts.length > 2) {
            val = parts[0] + '.' + parts[1];
        }

        // Limit to 2 decimal places
        if (parts[1] && parts[1].length > 2) {
            val = parts[0] + '.' + parts[1].substring(0,2);
        }

        // Update the cell text
        this.textContent = val;

        // Move cursor to the end
        const range = document.createRange();
        const sel = window.getSelection();
        range.selectNodeContents(this);
        range.collapse(false);
        sel.removeAllRanges();
        sel.addRange(range);
    });

    tdQty.addEventListener('blur', function() {
        let val = parseFloat(this.textContent);
        if (isNaN(val) || val <= 0) val = 1;
        this.textContent = val;
        tdTotal.textContent = (parseFloat(salePrice)*val).toFixed(2);
        updateGrandTotal();
    });

    tdQty.addEventListener('keydown', function(e) {
        if (e.key === "Enter") {
            e.preventDefault();
            tdQty.blur();
        }
    });

    document.getElementById('itemname').value = '';
    document.getElementById('itemid').value = '';
    document.getElementById('mastername').value = '';
    document.getElementById('typeitemname').value = '';
    document.getElementById('saleprice').value = '';
    document.getElementById('qty').value = '';
    document.getElementById('unit').value = '';

    document.getElementById('itemname').focus();
});


</script>

<!--- Table data transfer to controller --->
<script>
const orderForm = document.getElementById('orderForm');
const saveBtn = document.getElementById('saveBtn');

saveBtn.addEventListener('click', function(e) {
    e.preventDefault();

    const rows = document.querySelectorAll('#itemTable tbody tr');
    if(rows.length === 0){
        alert('Please add at least one item!');
        return;
    }

    // Collect table data
    const items = [];
    rows.forEach((row, index) => {
        const itemId = row.children[0].textContent.trim();
        const itemName = row.children[1].textContent.trim();
        const price = parseFloat(row.children[2].textContent.trim());
        const qty = parseFloat(row.children[3].textContent.trim());
        const total = parseFloat(row.children[4].textContent.trim());

        items.push({
            item_id: itemId,
            item_name: itemName,
            price: price,
            qty: qty,
            total: total
        });
    });

    // CSRF token
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // AJAX POST request
    fetch('{{ route("save_new_purchase_order") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({
            items: items,
            userid: document.getElementById('userid').value

        })
    })
    
    .then(response => response.json())
    .then(data => {
        if(data.success){
            alert(data.message);
            // Clear table
            document.querySelector('#itemTable tbody').innerHTML = '';
            document.getElementById('lcdValue').textContent = '0.00';
        } else {
            alert('Error saving order: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(err => {
        console.error(err);
        alert('AJAX request failed.');
    });
});
</script>

























@endsection