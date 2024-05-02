@extends ('admin.layouts.app')

<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('libs/data-table/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('libs/data-table/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('libs/data-table/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<title>Travent Report / Hotels</title>
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{ __('All Bookings Report') }}</h1>
        </div>
        <div class="col-md-12 mb-2 d-flex">
            <form action="{{ route('hotel.report.admin') }}" method="GET" role="search">
                <div class="row">
                    <div class="d-flex m-2 align-items-center">
                        <label for="status" class="mx-2">{{ __('Status') }}</label>
                        <select class="form-control"  name="status" id="status">
                            <option @if ($status == 'all') selected @endif value="all">All</option>
                            <option @if ($status == 'publish') selected @endif value="publish">Publish</option>
                            <option @if ($status == 'draft') selected @endif value="draft">Draft</option>
                        </select>
                    </div>
                    <div class="d-flex m-2 align-items-center">
                        <label for="from" class="mx-2">{{ __('From') }}</label>
                        <input type="date" value="{{ old('from') }}" class="form-control" name="from"
                            id="from">
                    </div>
                    <div class="d-flex m-2 align-items-center">
                        <label for="to" class="mx-2">{{ __('To') }}</label>
                        <input type="date" value="{{ old('to') }}" class="form-control" name="to"
                            id="to">
                        <button class="btn btn-info mx-2 p-2" type="submit" title="Search">
                            <span class="fa fa-search"></span>
                        </button>
                        <a href="{{ route('hotel.report.admin') }}" class="btn btn-success text-center p-2"
                            title="Refresh"><span class="fa fa-refresh"></span></a>
                    </div>
                </div>
            </form>
        </div>
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Property') }}</th>
                    <th>{{ __('Owner') }}</th>
                    <th>{{ __('Phone') }}</th>
                    <th>{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                    <?php
                    //if(!setting_item('tour_enable_inbox')) return;
                    $vendor = $row->author;
                    // dd($vendor);
                    ?>
                    <tr>
                        <td>
                            {{ $row->id }}
                        </td>
                        <td>
                            {{ $row->title }}
                        </td>
                        <td>
                            {{ $vendor->first_name }} {{$vendor->last_name}}
                        </td>
                        <td>
                            {{ $vendor->phone }}
                        </td>
                        <td>
                            {{ $row->status }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('js')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('libs/data-table/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('libs/data-table/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('libs/data-table/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('libs/data-table/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('libs/data-table/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('libs/data-table/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('libs/data-table/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('libs/data-table/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('libs/data-table/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('libs/data-table/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('libs/data-table/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('libs/data-table/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script>
        $(function() {
            $("#example1").DataTable({
                // "paging": false,
                "order": [
                    [0, 'desc']
                ],
                "pageLength": 100,
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
