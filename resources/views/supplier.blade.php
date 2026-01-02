@if(!session()->has('userid'))
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
@endif

@extends('layouts.dashboard')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<br>
<h1 style="display: inline; color:#330000;">Supplier / Vendor</h1>

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
    <div id="success" style="padding:10px; background:#d4edda; color:#155724; border-radius:5px; margin-bottom:10px;">
        {{ session('success') }}
    </div>
    @endif

    @if(session('Empty'))
    <div id="Empty" style="padding:10px; background:#f8d7da; color:#721c24; border-radius:5px; margin-bottom:10px;">
        {{ session('Empty') }}
    </div>
    @endif

    <form action="{{ route('save_supplier') }}" method="POST">
        @csrf

        <!-- Master Name -->
        <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center; position: relative;">
            <label for="mastername" style="width: 120px; font-weight: bold;">Supplier Name:</label>
            <input type="text" id="mastername" name="mastername" placeholder="Enter New Supplier Name" autocomplete="off" style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;">
        </div>

        <!-- Item Name -->
        <div style="display: flex; margin-bottom: 15px; width: 100%; align-items: center; position: relative;">
            <label for="itemname" style="width: 120px; font-weight: bold;">Contact No:</label>
            <input type="text" id="itemname" name="itemname" placeholder="Enter contact details" autocomplete="off"
                style="flex: 1; padding: 8px; border-radius:5px; border:1px solid #ccc;">
        </div>

        <!-- Save Button -->
        <button type="submit" style="padding: 10px 20px; background: #ff6600; color:white; border:none; border-radius:5px; cursor:pointer; margin-top:10px;">
            Save
        </button>
    </form>

</div>


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
            <th>Supplier Name</th>
            <th>Contact No</th>
            <th>Action</th>
        </tr>
        @foreach ($data as $row)
        <tr>
            <td>{{ $row->supplier_id }}</td>
            <td>
            <span class="name-text">{{ $row->name }}</span>
            <input type="text" class="edit-input" value="{{ $row->name }}" style="display:none; padding:5px; width:140px;">
            </td>
            <td>
            <span class="name-text">{{ $row->cell }}</span>
            <input type="text" class="edit-input" value="{{ $row->cell }}" style="display:none; padding:5px; width:140px;">
            </td>
            <td>
            <button class="edit-btn" data-id="{{ $row->supplier_id }}" style="padding:5px 10px; background:#ff6600; color:white; border:none; border-radius:5px; cursor:pointer;">
                Edit
            </button>
            <form action="{{ route('delete_supplier') }}" method="post" style="display:inline;">
                @csrf
                @method('DELETE')
                <input type="hidden" name="item_id" value="{{ $row->supplier_id }}">
                <button type="submit" onclick="return confirm('Are you sure you want to delete this row?')" style="padding:5px 10px; background:#ff3300; color:white; border:none; border-radius:5px; cursor:pointer;">
                    Delete
                </button>
            </form>
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
            const supplierId = this.dataset.id;

            let tds = row.querySelectorAll("td");

            // Supplier Name (td index 1)
            let nameText  = tds[1].querySelector(".name-text");
            let nameInput = tds[1].querySelector(".edit-input");

            // Contact No (td index 2)
            let contactText  = tds[2].querySelector(".name-text");
            let contactInput = tds[2].querySelector(".edit-input");

            // reset all rows
            document.querySelectorAll(".edit-input").forEach(i => i.style.display='none');
            document.querySelectorAll(".name-text").forEach(s => s.style.display='inline-block');

            // show both fields
            nameText.style.display='none';
            nameInput.style.display='inline-block';

            contactText.style.display='none';
            contactInput.style.display='inline-block';

            nameInput.focus();

            function showMsg(){
                const msg = document.getElementById("update-message");
                if(!msg) return;
                msg.textContent = "Updated successfully!";
                msg.style.display = "block";
                setTimeout(()=>{ msg.style.display="none"; },2000);
            }

            function ajaxUpdate(field, value, cb){
                fetch("{{ route('update_supplier') }}", {
                    method:"POST",
                    headers:{
                        "Content-Type":"application/json",
                        "Accept":"application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        supplier_id: supplierId,
                        field: field,
                        new_value: value
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

            /* ===== SUPPLIER NAME ===== */
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

            /* ===== CONTACT ===== */
            contactInput.onkeypress = e => { if(e.key==="Enter") contactInput.blur(); };
            contactInput.onblur = () => {
                let val = contactInput.value.trim();
                if(val === "" || val === contactText.textContent){
                    contactInput.value = contactText.textContent;
                    contactText.style.display='inline-block';
                    contactInput.style.display='none';
                    return;
                }
                ajaxUpdate("contact", val, () => {
                    contactText.textContent = val;
                    contactText.style.display='inline-block';
                    contactInput.style.display='none';
                });
            };

        });

    });

});
</script>

@endsection

