@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">Products</div>

                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <a href="{{route('products.create')}}" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-plus"></span> Create Product</a>
                            <a href="{{route('products.export')}}" class="btn btn-success pull-right"><span class="glyphicon glyphicon-download"></span> Export CSV</a>
                            <a href="#" class="btn btn-danger pull-right" id="btn-delete-selected"><span class="glyphicon glyphicon-remove"></span> Delete Selected</a>
                        </div>
                        <hr>
                        <div class="col-sm-12">
                            {{ $html->table() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    {{ $html->scripts() }}
    <script>
        $(function() {
            $('.panel-body').on('click', '.btn-delete-row', function (e) {
                e.preventDefault();
                if (confirm('Are you sure to delete this row?')) {
                    var checkbox = $(this).closest('tr').find('input[type=checkbox]');
                    var id = $(checkbox).val();
                    $.ajax({
                        url: '{{ url('products') }}/'+id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (data) {
                        window.LaravelDataTables.dataTableBuilder.draw();
                    }).fail(function () {
                        alert('Something went wrong.');
                    });
                }
            });

            $('#btn-delete-selected').click(function (e) {
                e.preventDefault();
                var checkboxes = $('input[type=checkbox]:checked');
                if (checkboxes.length > 0 && confirm('Are you sure to delete ' + checkboxes.length + ' row(s)?')) {
                    var elements = [];
                    $(checkboxes).each(function(index, el) {
                        elements.push($(el).val());
                    });
                    $.ajax({
                        url: '{{ route('products.delete.selected') }}',
                        type: 'POST',
                        data: {products: elements},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (data) {
                        window.LaravelDataTables.dataTableBuilder.draw();
                    }).fail(function () {
                        alert('Something went wrong.');
                    });
                }
            });
        });
    </script>
@endpush