@if(!session()->has('userid'))
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
@endif

@extends('layouts.dashboard')


@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<br>
<h1 style="display: inline; color:#330000;">Inventory</h1>



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

#itemTable{
    width: 100%;
    max-width: 1200px;
    table-layout: fixed;
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
    z-index: 99
    99;
}

#itemTable th:nth-child(1), #itemTable td:nth-child(1) {
    width: 120px;
}

#itemTable th:nth-child(2), #itemTable td:nth-child(2) {
    width: 100px;
}

#itemTable th:nth-child(3), #itemTable td:nth-child(3) {
    width: 350px; 
}
#itemTable th:nth-child(4), #itemTable td:nth-child(4) {
    width: 100px;
}
#itemTable th:nth-child(5), #itemTable td:nth-child(5) {
    width: 100px;
}

#itemTable th:nth-child(6), #itemTable td:nth-child(6) {
    width: 80px;
}
#itemTable th:nth-child(7), #itemTable td:nth-child(7) {
    width: 110px; 
}
#itemTable th:nth-child(8), #itemTable td:nth-child(8) {
    width: 100px;
}
#itemTable th:nth-child(9), #itemTable td:nth-child(9) {
    width: 80px;
}

</style>

<!--- User Details Div --->
<div class="user-info">
    <p>
        User: {{ session('username') }} <br>
        Role: {{ session('usertype') }}
    </p>
</div>

<div class="table-container">
    <table id="itemTable" border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>Master Input</th>
            <th>Item code</th>
            <th>Item Name</th>
            <th>Item Qty</th>
            <th>Item Value</th>
            <th>Unit</th>
            <th>Batch No</th>
            <th>Batch Qty</th>
            <th>Price</th>
        </tr>

        @php
            $grandTotal = 0;
        @endphp

        @foreach($totalstock as $master_name => $itemsByItem)
            @php
                $masterRowCount = $itemsByItem->sum(function($itemGroup) {
                    return $itemGroup->count() + 1;
                });
                $masterPrinted = false;
            @endphp

            @foreach($itemsByItem as $item_id => $batches)
                @php
                    $itemValue = $batches->sum(function($batch) {
                        return $batch->batch_price;
                    });
                    $grandTotal += $itemValue;
                @endphp

                {{-- Item row --}}
                <tr>
                    @if(!$masterPrinted)
                        <td rowspan="{{ $masterRowCount }}">{{ $master_name }}</td>
                        @php $masterPrinted = true; @endphp
                    @endif

                    <td>{{ $batches[0]->item_id }}</td>
                    <td>{{ $batches[0]->cat_name }} {{ $batches[0]->item_name }}</td>
                    <td>{{ $batches[0]->qty }}</td>
                    <td>{{ $itemValue }}</td>
                    <td>{{ $batches[0]->unit }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                {{-- Batch rows --}}
                @foreach($batches as $batch)
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td> 
                        <td></td> 
                        <td style="background-color:#e8e8e1;">{{ $batch->batch_no }}</td>
                        <td style="background-color:#e8e8e1;">{{ $batch->batch_qty }}</td>
                        <td style="background-color:#e8e8e1;">{{ $batch->batch_price }}</td>
                    </tr>
                @endforeach

            @endforeach
        @endforeach

        {{-- Grand total row --}}
        <tr>
            <td colspan="4" style="text-align:right;font-weight:bold;">Grand Total:</td>
            <td style="font-weight:bold;">{{ $grandTotal }}</td>
            <td colspan="4"></td>
        </tr>

    </table>
</div>







<script>
const $totalstock = JSON.parse('{!! json_encode($totalstock) !!}');



</script>


@endsection


