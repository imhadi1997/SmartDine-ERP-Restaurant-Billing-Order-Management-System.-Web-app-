@if(!session()->has('userid'))
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
@endif

@extends('layouts.dashboard')


@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<br>
<br>
<h1 style="display: inline; color:#330000;">Menu Type of items</h1>

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

    <form action="{{ route('save_menu_item') }}" method="POST">
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
            <input type="text" id="typeitemname" name="typeitemname" autocomplete="off" placeholder="Enter new type name" style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;">
        </div>

        <!-- Sale Price -->
        <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center;">
            <label for="saleprice" style="width: 120px; font-weight: bold;">Sale Price:</label>
            <input 
                type="text" 
                id="saleprice" 
                name="saleprice" 
                placeholder="Enter sale price" 
                autocomplete="off"
                style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;"
                oninput="validateDecimal(this)" 
            >
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
            <th>Sale Price</th>
            <th style="width:220px;">Actions</th>
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
            <button class="edit-btn" data-id="{{ $row->item_id }}" style="padding:5px 10px; background:#ff6600; color:white; border:none; border-radius:5px; cursor:pointer;">
                Edit
            </button>

            <form action="{{ route('delete_main_menu_item') }}" method="post" style="display:inline;">
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
    document.querySelectorAll(".edit-input").forEach(input => input.style.display = 'none');
    document.querySelectorAll(".name-text").forEach(span => span.style.display = 'inline-block');

    document.querySelectorAll(".edit-btn").forEach(btn => {
        btn.addEventListener("click", function () {
            let row = this.closest("tr");
            const itemId = this.dataset.id;
            const isPriceBtn = this.textContent.toLowerCase().includes("price");

            // Get tds for name and price
            let tds = row.querySelectorAll("td");
            let nameText = tds[3].querySelector(".name-text");
            let nameInput = tds[3].querySelector(".edit-input");
            let priceText = tds[4].querySelector(".name-text");
            let priceInput = tds[4].querySelector(".edit-input");

            // Hide other open inputs
            document.querySelectorAll(".edit-input").forEach(i => i.style.display='none');
            document.querySelectorAll(".name-text").forEach(s => s.style.display='inline-block');

            if(isPriceBtn){
                nameInput.style.display='none'; nameText.style.display='inline-block';
                priceText.style.display='none'; priceInput.style.display='inline-block';
                priceInput.focus();
            } else {
                priceInput.style.display='none'; priceText.style.display='inline-block';
                nameText.style.display='none'; nameInput.style.display='inline-block';
                nameInput.focus();
            }

            // Block invalid characters while typing for price
            priceInput.addEventListener('input', function(){
                let val = this.value;
                val = val.replace(/[^0-9.]/g,'');
                const parts = val.split('.');
                if(parts.length > 2){
                    val = parts[0] + '.' + parts[1];
                }
                if(parts[1] && parts[1].length > 2){
                    val = parts[0] + '.' + parts[1].substring(0,2);
                }
                if(parts[0].length > 8){
                    val = parts[0].substring(0,8) + (parts[1] ? '.' + parts[1] : '');
                }
                this.value = val;
            });

            function update(isPrice=false){
                let value, field;

                if(isPrice){
                    value = parseFloat(priceInput.value || 0).toFixed(2); // force 2 decimals
                    priceInput.value = value;
                    field = "item_price";

                    // Empty or unchanged → reset
                    if(value === "" || value === priceText.textContent){
                        priceInput.value = priceText.textContent;
                        priceText.style.display='inline-block';
                        priceInput.style.display='none';
                        return;
                    }

                    // Validate format: digits with max 2 decimals
                    if(!/^\d{1,8}(\.\d{0,2})?$/.test(value)){
                        alert("Invalid price! Only numbers with up to 2 decimals allowed.");
                        priceInput.focus();
                        return;
                    }

                } else {
                    value = nameInput.value.trim();
                    field = "name";
                    if(value === "" || value === nameText.textContent){
                        nameInput.value = nameText.textContent;
                        nameText.style.display='inline-block';
                        nameInput.style.display='none';
                        return;
                    }
                }

                fetch("{{ route('update_menu_type_of_item_name_price') }}", {
                    method:"POST",
                    headers:{
                        "Content-Type":"application/json",
                        "Accept":"application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        field: field,
                        new_value: value
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        if(isPrice){
                            priceText.textContent = value;
                            priceText.style.display="inline-block";
                            priceInput.style.display="none";
                        } else {
                            nameText.textContent = value;
                            nameText.style.display="inline-block";
                            nameInput.style.display="none";
                        }

                        const msg = document.getElementById("update-message");
                        msg.textContent = "Updated successfully!";
                        msg.style.display="block";
                        setTimeout(()=>{ msg.style.display="none"; },2000);
                    } else {
                        alert(data.message || "Update failed!");
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Network or server error!");
                });
            }

            if(isPriceBtn){
                priceInput.onkeypress = e => { if(e.key==="Enter") update(true); };
                priceInput.onblur = ()=>update(true);
            } else {
                nameInput.onkeypress = e => { if(e.key==="Enter") update(false); };
                nameInput.onblur = ()=>update(false);
            }
        });
    });
});
</script>

@endsection



















