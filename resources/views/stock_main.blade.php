@if(!session()->has('userid'))
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
@endif

@extends('layouts.dashboard')


@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<br>
<h1 style="display: inline; color:#330000;">Stock</h1>

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

<!-- Table -->
<div id="print-table" class="table-container">
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>Master id</th>
            <th>Master Input</th>
            <th>Item code</th>
            <th>Item Name</th>
            <th>Price</th>
            <th>Unit</th>
        </tr>

        @foreach($grouped as $masterId => $items)

            <tr>
                <td rowspan="{{ $items->count() }}">{{ $masterId }}</td>
                <td rowspan="{{ $items->count() }}">{{ $items[0]->master_name }}</td>

                {{-- First item --}}
                <td>{{ $items[0]->item_id }}</td>
                <td>{{ $items[0]->cat_name }} - {{ $items[0]->item_name }}</td>
                <td>{{ $items[0]->item_price }}</td>
                <td>{{ $items[0]->unit }}</td>
            </tr>

            {{-- Remaining Items --}}
            @foreach($items->slice(1) as $row)
                <tr>
                    <td>{{ $row->item_id }}</td>
                    <td>{{ $row->cat_name }} - {{ $row->item_name }}</td>
                    <td>{{ $row->item_price }}</td>
                    <td>{{ $row->unit }}</td>
                </tr>
            @endforeach

        @endforeach

    </table>
</div>

<div style="width:95%; text-align:right; margin-bottom:10px;">
    <button onclick="printTable()" 
        style="padding: 10px 20px; background: #ff6600; color:white; border:none; border-radius:5px; cursor:pointer; margin-top:10px";>
        Print
    </button>
</div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }

    #print-table, 
    #print-table * {
        visibility: visible;
    }

    #print-table {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>

<script>
function printTable() {
    window.print();
}
</script>






@endsection