@if(!session()->has('userid'))
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
@endif

@extends('layouts.dashboard')


@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<br>
<h1 style="display: inline; color:#330000;">Stock Master input</h1>



<style>
/* ===== MAIN CONTENT STYLES ===== */
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
    width: 350px;
    box-shadow: 0px 4px 12px rgba(0,0,0,0.3);
    margin-bottom: 20px;
}

.form-container label{
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-container input{
    width: 100%;
    padding: 8px;
    margin-bottom: 15px;
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
    max-width: 900px;
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

.btn {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 4px;
    font-size: 14px;
    text-decoration: none;
    cursor: pointer;
}

.btn-edit {
    background: #007bff;
    color: #fff;
    border: none;
}

.btn-edit:hover { background: #0069d9; }

.btn-delete {
    background: #dc3545;
    color: #fff;
    border: none;
}

.btn-delete:hover { background: #c82333; }

.inline-form { display:inline-block; margin:0; padding:0; }
.inline-form button { border: none; padding: 6px 10px; border-radius:4px; cursor:pointer; }

#update-message { 
    display:none; 
    background:#4CAF50; 
    color:white; 
    padding:10px; 
    margin-bottom:15px; 
    border-radius:5px; 
    text-align:center;
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

<!--- User Details Div --->
<div class="user-info">
    <p>
        User: {{ session('username') }} <br>
        Role: {{ session('usertype') }}
    </p>
</div>

<!-- Form -->
<div class="form-container">
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
    <form action="{{route('save_master_input_stock')}}" method="POST">
        @csrf
        <label for="masterinput">Master Input:</label>
        <input type="text" id="masterinput" autocomplete="off" name="masterinput" placeholder="Enter new master input" required>
        <button type="submit">Save</button>
    </form>
</div>

<!-- For AJAX update -->
<div id="update-message" 
     style="background:#28a745; color:white; padding:10px 15px; 
            margin-bottom:15px; border-radius:5px; text-align:center;
            display:none;">
</div>

<!--- Table --->
<div class="table-container">
<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Actions</th>
    </tr>

    @foreach($data as $row)
    <tr>
        <td>{{ $row->level_id }}</td>
        <td>
            <span class="name-text">{{ $row->name }}</span>
            <input type="text" class="edit-input" value="{{ $row->name }}" style="display:none; padding:5px; width:140px;">
        </td>

        <td>
            <button class="edit-btn" data-id="{{ $row->level_id }}" style="padding:5px 10px; background:#ff6600; color:white; border:none; border-radius:5px; cursor:pointer;">
                Edit
            </button>

            <form action="{{ route('delete_stock_master_input') }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <input type="hidden" name="level_id" value="{{ $row->level_id }}">
                <button type="submit" onclick="return confirm('Are you sure you want to delete this row?')" style="padding:5px 10px; background:#ff3300; color:white; border:none; border-radius:5px; cursor:pointer;">
                    Delete
                </button>
            </form>
        </td>
    </tr>
    @endforeach

</table>
</div>


<!--- for Save message --->
<script>
    setTimeout(function () {
        let msg = document.getElementById('success');
        if (msg) {
            msg.style.transition = "opacity 0.5s";
            msg.style.opacity = 0;
            setTimeout(() => msg.remove(), 500);
        }
    }, 1000);
</script>

<!--- for empty message --->
<script>
    setTimeout(function () {
        let msg = document.getElementById('Empty');
        if (msg) {
            msg.style.transition = "opacity 0.5s";
            msg.style.opacity = 0;
            setTimeout(() => msg.remove(), 500);
        }
    }, 1000);
</script>

<!--- Ajax Edit Name table column(2) --->
<script>
window.addEventListener('DOMContentLoaded', function () {
    // Initial setup: hide all inputs, show all spans
    document.querySelectorAll(".edit-input").forEach(input => input.style.display = 'none');
    document.querySelectorAll(".name-text").forEach(span => span.style.display = 'inline-block');

    document.querySelectorAll(".edit-btn").forEach(btn => {
        btn.addEventListener("click", function () {
            let row = this.closest("tr");
            let nameText = row.querySelector(".name-text");
            let input = row.querySelector(".edit-input");

            // Reset any other open edits
            document.querySelectorAll(".edit-input").forEach(i => i.style.display='none');
            document.querySelectorAll(".name-text").forEach(s => s.style.display='inline-block');

            // Show input for current row
            nameText.style.display = "none";
            input.style.display = "inline-block";
            input.focus();

            function update() {
                if(input.value.trim() === "" || input.value === nameText.textContent) {
                    // No change or empty → reset input
                    input.value = nameText.textContent;
                    nameText.style.display = "inline-block";
                    input.style.display = "none";
                    return;
                }

                // Value changed → save to DB
                fetch("{{ route('update_stock_master_input') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        level_id: btn.dataset.id,
                        new_name: input.value
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        nameText.textContent = input.value;
                        nameText.style.display = "inline-block";
                        input.style.display = "none";

                        // Show temporary success message
                        const msg = document.getElementById("update-message");
                        msg.textContent = "Updated successfully!";
                        msg.style.display = "block";
                        setTimeout(() => { msg.style.display = "none"; }, 500);
                    }
                })
                .catch(err => console.error(err));
            }

            // Update on Enter key
            input.onkeypress = function(e) { if(e.key === "Enter") update(); };
            // Update on blur
            input.onblur = update;
        });
    });
});
</script>




@endsection
