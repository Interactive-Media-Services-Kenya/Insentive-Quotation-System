@extends('layouts.backend')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title"> Generate Invoice </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Invoice</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Generate Invoice</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <h5 class="card-description"> Basic Client Information </h5>
                        <form class="forms-sample" id="formItem">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="exampleInputName1">Company Name</label>
                                    <input type="text" name="company" class="form-control" id="company">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="exampleInputName1">Attention To</label>
                                    <input type="text" name="attention_to" class="form-control" id="attention_to">
                                </div>
                            </div>
                            <h5 class="card-description">Add Item</h4>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item" id="formDataCount">Number of Items Added on Invoice: <span>0</span></li>
                                </ol>
                            </nav>
                            <div class="form-group">
                                <label for="exampleInputName1">Item Name</label>
                                <input type="text" name="name" class="form-control" id="name">
                            </div>
                            <div class="form-group">
                                <label for="exampleTextarea1">Description</label>
                                <textarea name="description" id="description" class="form-control" id="exampleTextarea1" rows="4"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail3">Quantity</label>
                                <input name="quantity" type="number" min="1" class="form-control" id="quantity">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword4">Prize Per Unit</label>
                                <input type="number" name="prize" class="form-control" id="prize">
                            </div>
                            <div class="form-group">
                                <label for="exampleSelectGender">Incentive Type</label>
                                <select name="type" id="type" class="form-control" id="exampleSelectGender">
                                    <option value="cash">Cash</option>
                                    <option value="voucher">Voucher</option>
                                </select>
                            </div>
                            <button type="reset" class="btn btn-secondary me-2">Reset</button>
                            <button class="btn btn-warning me-2" onclick="addMore();">Add More</button>
                            <button type="submit" class="btn btn-primary me-2">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script>
        var formData = [];

        function addMore() {
            event.preventDefault();
            if($("#name").val() == ''){
                alert('Fill All the form fields');
                return false;
            }
            var items = {
                "name": $("#name").val(),
                "description": $("#description").val(),
                "quantity": $("#quantity").val(),
                "type": $("#type").val(),
                "prize": $("#prize").val()
            }



            formData.push(items);
            $("#name").val('');
            $("#description").val('');
            $("#quantity").val('');
            $("#type").val('');
            $("#prize").val('')

            $("#formDataCount span").text(formData.length);

        }
    </script>

    <script>
        // Bind a submit event to the form element
        $("#formItem").submit(function(event) {
            // Prevent the default form submission behavior
            event.preventDefault();
            var data = {
                "company": $("#company").val(),
                "attention_to": $("#attention_to").val(),
            }
            // formData.push(data);
            //console.log(formData);
            finalData = {"postData":formData,"company_info":data};
            console.log(finalData);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Send an HTTP POST request to the URL with the form data as the request body
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: "{{ route('orders.store') }}",
                data: JSON.stringify(finalData),
                success: function(response) {
                    // Handle the response from the server
                    console.log("Form submission successful: " + response);
                    if (response.statusCode == 200) {
                        alert('Order Invoice Generated Successfully');
                        var url = "{{ route('orders.index') }}";
                        $(location).attr('href',url);
                    }else{
                        alert('Failed! Order Invoice Not Generated');
                        var url = "{{ route('orders.create') }}";
                        $(location).attr('href',url);
                    }

                }
            });
            $("#formItem")[0].reset();
        });
    </script>
@endsection
