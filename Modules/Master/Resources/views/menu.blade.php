@extends('master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Blank Page</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Blank Page</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content pb-1">

        <div class="container-fluid">

            <div class="card" id="container-filter">
                <div class="card-header">
                    <h3 class="card-title">Filter</h3>
                </div>
                <div class="card-body">
                    <form id="form-filter">
                        <div class="container">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Tipe:</label>
                                        <select name="datatable[tipe]" class="form-control select2" style="width: 100%;">
                                            <option value="">Pilih Tipe</option>
                                            <option value="label">Label</option>
                                            <option value="menu">Menu</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Title</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool filter">
                            <i class="fas fa-filter"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <td class="text-center">No</td>
                                <td class="text-center">Nama</td>
                                <td class="text-center">Aksi</td>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </section>
@endsection


@section('js')
    <script>
        $('document').ready(function() {
            let datatable = $('table').DataTable({
                ajax: {
                    url: "/master/menu/datatable"
                },
                columns: [{
                        data: 'id',
                        render: function(data, index, row, meta) {
                            return `${meta.row + 1}`
                        }
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'id',
                        render: function(data, index, row, meta) {
                            return `datanya - ${data}`
                        }
                    }
                ],
                rowCallback: function(row, data, index) {
                    $('td:eq(0)', row).css('width', '15px');
                    $('td:eq(0)', row).css('text-align', 'center');
                }
            });

            $('.filter').on('click', function() {
                $('#container-filter').toggle(500, 'swing')
            });

            $('#form-filter').on('change', 'select', function() {
                console.log($(this).val())
                datatable.ajax.reload();
            });
        });

        function renderAction(data, index, row, meta) {
            return JSON.stringify(row)
        }

        function buildDatatableParam() {
            return $('#form-filter').serializeObject();
        }
    </script>
@endsection
