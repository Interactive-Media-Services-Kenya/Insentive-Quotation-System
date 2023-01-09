@extends('layouts.backend')
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
@endsection
<!-- Table Section -->
@section('content')
<div class="card-body">
    <div class="table-responsive">
        <table class=" table table-bordered table-striped table-hover datatable" id="ProductTable">
            <thead>
                <tr>
                    <th>
                        ID
                    </th>
                    <th>
                        Company
                    </th>
                    <th>
                        Attention To
                    </th>
                    <th>
                        Order Items
                    </th>
                    <th>
                        Total Amount
                    </th>
                    <th>
                        Date Added
                    </th>
                    <th>
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
@endsection

<!-- Script Section  -->
@section('scripts')
    @include('includes.datatableScripts')
    <script>
        $(document).ready(function() {
            $('#ProductTable').DataTable({
                processing: true,
                method: 'GET',
                serverSide: true,
                ajax: "{{ route('orders.index') }}",
                columns: [
                    // {
                    //     data: 'placeholder',
                    //     name: 'placeholder'
                    // },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'company',
                        name: 'company'
                    },
                    {
                        data: 'attention_to',
                        name: 'attention_to'
                    },
                    {
                        data: 'order_items_count',
                        name: 'order_items_count'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    },
                ],
                dom: 'lBfrtip',
                pageLength: 100,
                buttons: [
                    'copy',
                    {
                        extend: 'excelHtml5',
                        title: 'Merchandise_list',
                        exportOptions: {
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, ':visible']
                            }
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        title: 'merchandise_list',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5]
                        }
                    },
                    'colvis'
                ]
            });
        });
    </script>
@endsection


