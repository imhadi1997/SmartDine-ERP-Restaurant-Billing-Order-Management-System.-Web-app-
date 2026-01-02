@if(!session()->has('userid'))
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
@endif

@extends('layouts.dashboard')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<br>
<h1 style="display: inline; color:#330000;">Stock Type of items</h1>

<style>
.main-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding: 40px 20px;
}

.form-container{
    margin-top: 20px;
    background: rgba(255,255,255,0.95);
    padding: 25px;
    border-radius: 12px;
    width: 500px;
    box-shadow: 0px 4px 12px rgba(0,0,0,0.3);
    margin-bottom: 20px;
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

.table-container{
    width: 95%;
    display: flex;
    justify-content: center;
}

table{
    width: 90%;
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

</style>

<!--- User infor --->
<div class="user-info">
    <p>
        User: {{ session('username') }} <br>
        Role: {{ session('usertype') }}
    </p>
</div>

<div class="form-container" style="display: flex; flex-direction: column; align-items: center; padding: 20px; background: rgba(255,255,255,0.95); border-radius:12px; width: 400px; box-shadow:0 4px 12px rgba(0,0,0,0.3);">
    @if(session('success'))
    <div style="padding:10px; background:#d4edda; color:#155724; border-radius:5px; margin-bottom:10px;">
        {{ session('success') }}
    </div>
    @endif

    @if(session('Empty'))
        <div style="padding:10px; background:#f8d7da; color:#721c24; border-radius:5px; margin-bottom:10px;">
            {{ session('Empty') }}
        </div>
    @endif

    <form action="{{ route('save_stock_item') }}" method="POST">
        @csrf
        <!-- Item ID -->
        <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center;">
            <label for="itemid" style="width: 120px; font-weight: bold;">Item ID:</label>
            <input type="text" id="itemid" name="itemid" placeholder="Item ID" readonly style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;">
        </div>

        <!-- Master Name -->
        <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center; position: relative;">
            <label for="mastername" style="width: 120px; font-weight: bold;">Master Name:</label>
            <input type="text" id="mastername" name="mastername" placeholder="Master Name" readonly style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;">
        </div>

        <!-- Item Name -->
        <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center; position: relative;">
            <label for="itemname" style="width: 120px; font-weight: bold;">Item Name:</label>
            <input type="text" id="itemname" name="itemname" placeholder="Enter item name" autocomplete="off"
                style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;">

            <div id="suggestions"
                style="position:absolute; top:100%; left:120px; right:0; background:#fff; border:1px solid #ccc; max-height:150px; overflow-y:auto; z-index:1000;">
            </div>
        </div>

        

        <!-- Type of Item -->
        <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center;">
            <label for="typeitemname" style="width: 120px; font-weight: bold;">Type of Item:</label>
            <input type="text" id="typeitemname" name="typeitemname" placeholder="Enter new type name" autocomplete="off" style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;">
        </div>

        <!-- Sale Price -->
        <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center;">
            <label for="saleprice" style="width: 120px; font-weight: bold;">Price:</label>
            <input 
                type="text" 
                id="saleprice" 
                name="saleprice" 
                placeholder="Enter sale price" 
                style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;"
                autocomplete="off"
                oninput="validateDecimal(this)" 
            >
        </div>

        <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center;">
            <label for="unit" style="width: 120px; font-weight: bold;">Unit:</label>
            <input type="text" id="unit" name="unit" autocomplete="off" placeholder="Enter unit of purchase" style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;">
        </div>


        <!-- Save Button -->
        <button type="submit" style="padding: 10px 20px; background: #ff6600; color:white; border:none; border-radius:5px; cursor:pointer; margin-top:10px;">
            Save
        </button>
    </form>

</div>

<!--- Decimal Validation --->
<script>
function validateDecimal(input) {
    // Remove invalid characters (allow only numbers and dot)
    input.value = input.value.replace(/[^0-9.]/g, '');

    // Only one dot allowed
    const parts = input.value.split('.');
    if(parts.length > 2) {
        input.value = parts[0] + '.' + parts[1];
    }

    // Limit decimal places to 2
    if(parts[1] && parts[1].length > 2) {
        input.value = parts[0] + '.' + parts[1].substring(0,2);
    }

    // Optional: Limit total digits before decimal to 8 (10 total digits including 2 decimals)
    if(parts[0].length > 8) {
        input.value = parts[0].substring(0,8) + (parts[1] ? '.' + parts[1] : '');
    }
}
</script>

<!--- Suggestions of items --->
<script>
const topLevels = JSON.parse('{!! json_encode($topLevels) !!}');
const masterInput = document.getElementById('itemname');
const masterId = document.getElementById('itemid');
const masterNameField = document.getElementById('mastername');
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
        div.textContent = match.cat_name;
        div.style.padding = '6px 8px';
        div.style.cursor = 'pointer';
        div.style.fontSize = '14px';

        if (index === selectedIndex) {
            div.style.background = '#e6e6e6';
        }

        div.addEventListener('click', () => {
            selectItem(index);
        });

        suggestionBox.appendChild(div);
    });
}

function selectItem(index) {
    if (index < 0 || index >= currentMatches.length) return;

    masterInput.value = currentMatches[index].cat_name;
    masterId.value = currentMatches[index].cat_id;
    masterNameField.value = currentMatches[index].master_name;  // << ADD THIS

    closeSuggestions();
}



masterInput.addEventListener('input', function() {
    const query = this.value.toLowerCase();
    
    masterId.value = '';             // already clearing ID
    masterNameField.value = '';      // now this clears master name

    if (query.length === 0) return closeSuggestions();

    const matches = topLevels.filter(d =>
        d.cat_name.toLowerCase().includes(query)
    );

    if (matches.length === 0) return closeSuggestions();

    selectedIndex = -1;
    renderSuggestions(matches);
});


// ---------- KEYBOARD EVENTS (UP, DOWN, ENTER) ----------
masterInput.addEventListener('keydown', function(e) {
    const items = currentMatches;

    if (items.length === 0) return;

    if (e.key === 'ArrowDown') {
        selectedIndex = (selectedIndex + 1) % items.length;
        renderSuggestions(items);
        e.preventDefault();
    }

    else if (e.key === 'ArrowUp') {
        selectedIndex = (selectedIndex - 1 + items.length) % items.length;
        renderSuggestions(items);
        e.preventDefault();
    }

    else if (e.key === 'Enter') {
        if (selectedIndex >= 0) {
            selectItem(selectedIndex);
            e.preventDefault();
        }
    }
});

// Close suggestion box when clicking outside
document.addEventListener('click', e => {
    if (!e.target.closest('#itemname')) closeSuggestions();
});
</script>


<!-- For AJAX update -->
<div id="update-message" 
     style="background:#28a745; color:white; padding:10px 15px; 
            margin-bottom:15px; border-radius:5px; text-align:center;
            display:none;">
</div>

<!-- Table -->
<div class="table-container">
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Master Input</th>
            <th>Item Name</th>
            <th>Item Category</th>
            <th>Price</th>
            <th>Unit</th>
            <th>Actions</th>
        </tr>
        @foreach ($data as $row)
        <tr>
            <td>{{ $row->item_id }}</td>
            <td>{{ $row->master_name }}</td>
            <td>{{ $row->category_name }}</td>
            <td>
            <span class="name-text">{{ $row->item_name }}</span>
            <input type="text" class="edit-input" value="{{ $row->item_name }}" style="display:none; padding:5px; width:140px;">
            </td>
            <td>
            <span class="name-text">{{ $row->item_price }}</span>
            <input type="text" class="edit-input" value="{{ $row->item_price }}" style="display:none; padding:5px; width:140px;">
            </td>
            <td>
            <span class="name-text">{{ $row->unit }}</span>
            <input type="text" class="edit-input" value="{{ $row->unit }}" style="display:none; padding:5px; width:140px;">
            </td>
            <td>
            <button class="edit-btn" data-id="{{ $row->item_id }}" style="padding:5px 10px; background:#ff6600; color:white; border:none; border-radius:5px; cursor:pointer;">
                Edit
            </button>
            <form action="{{ route('delete_main_stock_item') }}" method="post" style="display:inline;">
                @csrf
                @method('DELETE')
                <input type="hidden" name="item_id" value="{{ $row->item_id }}">
                <button type="submit" onclick="return confirm('Are you sure you want to delete this row?')" style="padding:5px 10px; background:#ff3300; color:white; border:none; border-radius:5px; cursor:pointer;">
                    Delete
                </button>
            </form>
            <button class="edit-btn" data-id="{{ $row->item_id }}" style="padding:5px 10px; background:#4a57ed; color:white; border:none; border-radius:5px; cursor:pointer;">
                Edit Price
            </button>
            </td>
        </tr>
        @endforeach
    </table>
</div>

<!--- Success Message --->
<script>
    setTimeout(() => {
        document.getElementById('success').style.display = 'none';
    }, 1000);
</script>

<!--- Empty Message --->
<script>
    setTimeout(() => {
        document.getElementById('Empty').style.display = 'none';
    }, 1000);
</script>

<script>
window.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll(".edit-input").forEach(i => i.style.display = 'none');
    document.querySelectorAll(".name-text").forEach(s => s.style.display = 'inline-block');

    document.querySelectorAll(".edit-btn").forEach(btn => {

        btn.addEventListener("click", function () {

            let row = this.closest("tr");
            const itemId = this.dataset.id;
            const isPriceBtn = this.textContent.toLowerCase().includes("price");

            let tds = row.querySelectorAll("td");

            // name
            let nameText  = tds[3].querySelector(".name-text");
            let nameInput = tds[3].querySelector(".edit-input");

            // price
            let priceText  = tds[4].querySelector(".name-text");
            let priceInput = tds[4].querySelector(".edit-input");

            // unit
            let unitText  = tds[5].querySelector(".name-text");
            let unitInput = tds[5].querySelector(".edit-input");

            // reset all
            document.querySelectorAll(".edit-input").forEach(i => i.style.display='none');
            document.querySelectorAll(".name-text").forEach(s => s.style.display='inline-block');

            if(isPriceBtn){
                priceText.style.display='none';
                priceInput.style.display='inline-block';
                priceInput.focus();
            } else {
                nameText.style.display='none';
                nameInput.style.display='inline-block';

                unitText.style.display='none';
                unitInput.style.display='inline-block';

                nameInput.focus();
            }

            // price validation
            priceInput.addEventListener('input', function(){
                let val = this.value.replace(/[^0-9.]/g,'');
                let parts = val.split('.');
                if(parts.length > 2) val = parts[0] + '.' + parts[1];
                if(parts[1] && parts[1].length > 2) val = parts[0] + '.' + parts[1].substring(0,2);
                this.value = val;
            });

            function showMsg(){
                const msg = document.getElementById("update-message");
                msg.textContent = "Updated successfully!";
                msg.style.display = "block";
                setTimeout(()=>{ msg.style.display="none"; },2000);
            }

            function ajaxUpdate(field, value, cb){
                fetch("{{ route('update_stock_type_of_item_name_price') }}", {
                    method:"POST",
                    headers:{
                        "Content-Type":"application/json",
                        "Accept":"application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        field: field,
                        new_value: value,
                        unit: value
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        cb();
                        showMsg();
                    } else {
                        alert(data.message || "Update failed!");
                    }
                });
            }

            /* ===== PRICE ===== */
            if(isPriceBtn){
                priceInput.onkeypress = e => { if(e.key==="Enter") priceInput.blur(); };
                priceInput.onblur = () => {
                    let val = parseFloat(priceInput.value || 0).toFixed(2);
                    ajaxUpdate("item_price", val, () => {
                        priceText.textContent = val;
                        priceText.style.display='inline-block';
                        priceInput.style.display='none';
                    });
                };
            }
            /* ===== NAME + UNIT ===== */
            else {
                nameInput.onkeypress = e => { if(e.key==="Enter") nameInput.blur(); };
                nameInput.onblur = () => {
                    let val = nameInput.value.trim();
                    if(val === "" || val === nameText.textContent){
                        nameInput.value = nameText.textContent;
                        nameText.style.display='inline-block';
                        nameInput.style.display='none';
                        return;
                    }
                    ajaxUpdate("name", val, () => {
                        nameText.textContent = val;
                        nameText.style.display='inline-block';
                        nameInput.style.display='none';
                    });
                };

                unitInput.onkeypress = e => { if(e.key==="Enter") unitInput.blur(); };
                unitInput.onblur = () => {
                    let val = unitInput.value.trim();
                    if(val === "" || val === unitText.textContent){
                        unitInput.value = unitText.textContent;
                        unitText.style.display='inline-block';
                        unitInput.style.display='none';
                        return;
                    }
                    ajaxUpdate("unit", val, () => {
                        unitText.textContent = val;
                        unitText.style.display='inline-block';
                        unitInput.style.display='none';
                    });
                };
            }

        });

    });

});
</script>



@endsection



















