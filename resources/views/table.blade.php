
@extends('tablar::page')

@section('content')
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script> --}}

<!-- Include Toastr CSS and JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>



    <!-- Page header -->
    {{-- @if(!empty(session('success')))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
       {{session('success')}}.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    @endif --}}
    <div class="page-header d-print-none">

        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Overview
                    </div>
                    <h2 class="page-title">
                        Dashboard
                    </h2>
                </div>



                <!-- Page title actions -->
                <div class="col-6 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                  <span class="d-none d-sm-inline">
                    <a href="/home" class="btn btn-white">
                        View Tables
                    </a>
                  </span>
                        <a href="/import-csv" class="btn btn-primary d-none d-sm-inline-block"
                           >
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Import File
                        </a>
                        <a href="#" class="btn btn-primary d-sm-none btn-icon" data-bs-toggle="modal"
                           data-bs-target="#modal-report" aria-label="Create new report">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                        </a>
                    </div>
                </div>

            </div>
            <div>

           {{-- search --}}
           @php
        //    $columns = array_keys((array) $data[0]);
        //    $columns = array_diff($columns, ['id']);
      @endphp
           <form id="search-form" action="{{ route('table.search', $table) }}" method="GET" >

           <div class="row">

            @foreach($columns as $column)
            <div class="col-md-1 col-md-auto  d-print-none">
                <label class="form-label" for="{{$column}}">{{ucfirst($column)}}:</label>
                <input type="text" class="form-control"  id="search-input" name="{{$column}}" value="{{request($column)}}" >
                {{-- <input type="hidden" class="form-control " id="{{$column}}" name="{{$column}}" value="{{request($column)}}" > --}}
            </div>
            @endforeach
        </div>
        <button style="float:right;" class="btn btn-primary d-sm-inline-block mt-2 " type="submit">Search</button>
        <button style="float:right; margin-right:2px" class="btn btn-info d-sm-inline-block mt-2 " type="reset" onclick="resetSearch()">clear</button>
    </form>
            {{-- /search --}}
        </div>
    </div>
    <!-- Page body -->

    {{-- search --}}


    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            {{-- <h1>{{ $table }} Data</h1> --}}
                            <h3 class="card-title">{{ $table }} Data</h3>
                        </div>
                        <div class="card-body border-bottom py-3">
                            <div class="d-flex" style="justify-content: space-between;">
                                <div class="text-muted" style="float: left;">
                                    Show
                                    <div class="mx-2 d-inline-block">
                                        <input type="text" class="form-control form-control-sm" value="{{count($count)}}" size="3"
                                               aria-label="Invoices count" readonly>
                                    </div>
                                    entries
                                </div>

                                    <div class="ms-auto text-muted " style="float:right;">
                                        Go:
                                        <div class="ms-2 d-inline-block">
                                            <input type="text" name="page" class="form-control form-control-sm"
                                                   aria-label="Search invoice" placeholder="Enter Number">
                                        </div>
                                    </div>

                            </div>
                        </div>


                        <div class="table-responsive" id="" >
                             @if (isset($error))
                                <div class="alert alert-danger">
                                    {{ $error }}
                                </div>
                                <script>
                                    toastr.error("No records found for the given search criteria.");
                                </script>
                            @else
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                <tr>

                                    @php
                                         $columns = array_keys((array) $data[0]);
                                         $columns = array_diff($columns, ['id']);
                                    @endphp
                                    @foreach($columns as $key=> $column)

                                    <th>{{ ucfirst($column) }}</th>
                                    @endforeach
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="">
                                    {{-- @foreach ($btls as $btl) --}}
                                    @foreach($data as $row)
                                <tr>

                                    @foreach($columns as $key => $column)


                                    <td style="
                                     max-width:200px;
                                      word-wrap: break-word;
                                      word-break: break-all;
                                      vertical-align: top;
                                      overflow: hidden;
                                     max-height:200px;" > {{ $row->$column }}
                                     </td>

                                     @endforeach

                                    <td class="text-end">

                                {{-- <a href="#" style="text-decoration: none"    > --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" data-bs-toggle="modal" data-bs-target="#modal-repor{{ $row->id }}" width="16" height="16" fill="currentColor" class="icon p-1 rounded rounded-lg bi bi-pencil-square" viewBox="0 0 16 16" style="background-color: #021028; color:white; cursor: pointer;">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                      </svg>
                                {{-- </a> --}}
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" data-bs-toggle="modal" data-bs-target="#modal-report{{ $row->id }}" fill="currentColor" class="icon bg-warning p-1 rounded rounded-lg bi bi-eye-slash-fill" viewBox="0 0 16 16" style="cursor:pointer">
                                    <path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7 7 0 0 0 2.79-.588M5.21 3.088A7 7 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474z"/>
                                    <path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12z"/>
                                  </svg>
                                  <a type="button"  form="delete-form" style="border: none" data-bs-toggle="modal" data-bs-target="#modal-delete{{ $row->id }}">
                                    <svg  xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="icon bg-danger p-1 rounded rounded-lg bi bi-trash3-fill" viewBox="0 0 16 16">
                                        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                                      </svg>
                                    </a>
                                    </td>

                                </tr>
                                {{-- Delete Form  --}}

                                <div class="modal modal-blur fade" id="modal-delete{{ $row->id }}"  tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title"> YOUR ID : {{$row->id}} </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('table.delete', [$table, $row->id]) }}" id="delete-form" onsubmit="confirmDelete(event, this)" class="hidden" >
                                        @csrf
                                       @method('DELETE')
                                       <div style="text-align:center;color:red;margin:10px;">
                                        <svg style="width: 45px;height: 45px;" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-octagon" viewBox="0 0 16 16">
                                            <path d="M4.54.146A.5.5 0 0 1 4.893 0h6.214a.5.5 0 0 1 .353.146l4.394 4.394a.5.5 0 0 1 .146.353v6.214a.5.5 0 0 1-.146.353l-4.394 4.394a.5.5 0 0 1-.353.146H4.893a.5.5 0 0 1-.353-.146L.146 11.46A.5.5 0 0 1 0 11.107V4.893a.5.5 0 0 1 .146-.353zM5.1 1 1 5.1v5.8L5.1 15h5.8l4.1-4.1V5.1L10.9 1z"/>
                                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
                                          </svg>
                                       </div>
                                    <h3 style="text-align: center">Are you sure ? You want to Delete this Item</h3>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger ms-auto" data-bs-dismiss="modal">
                                            Delete
                                        </button>
                                    </div>
                                </form>
                                </div>




                            </div>
                        </div>
                    </div>

                                        {{-- End Delete --}}


                                {{-- <form method="POST" action="{{ route('table.delete', [$table, $row->id]) }}" id="delete-form" onsubmit="confirmDelete(event, this)" class="hidden" >
                                    @csrf
                                   @method('DELETE')
                                </form> --}}
                                @endforeach
                                </tbody>
                            </table>
                        {{-- Edit Model --}}
                        @foreach($data as $row)
                        <div class="modal modal-blur fade" id="modal-repor{{ $row->id }}"  tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                <form method="POST" action="{{ route('table.update', ['table'=>$table,'id'=> $row->id]) }}" >
                                    @csrf
                                    @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit {{$table}} </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <div class="row">
                                        @foreach($columns as $column)
                                        <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">{{ $column }}</label>
                                        <input type="text" class="form-control" id="{{ $column }}" name="{{ $column }}" placeholder="" value="{{ $row->$column }}">
                                        <input type="hidden" name="id" placeholder="" value="{{ $row->id }}">
                                    </div>
                                </div>
                                @endforeach
                                </div>

                                </div>


                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary ms-auto" data-bs-dismiss="modal">
                                        Update
                                    </button>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                        @endforeach
                        {{-- End Edit Model  --}}



                            {{-- Model Box --}}
                @foreach($data as $row)
                <div class="modal modal-blur fade" id="modal-report{{ $row->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                        <h5 class="modal-title">View {{$table}} </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            @foreach($columns as $column)
                            <div class="col-lg-6">
                        <div class="mb-3">
                            <label class="form-label">{{ $column }}</label>
                            <input type="text" class="form-control" name="example-text-input" placeholder="" value="{{ $row->$column }}" readonly>
                        </div>
                    </div>
                    @endforeach
                    </div>
                        {{-- <p><strong>{{ $column }}:</strong> {{ $row->$column }}</p> --}}
                    </div>


                    {{-- <div class="modal-footer">
                        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                            Cancel
                        </a>
                        <a href="#" class="btn btn-primary ms-auto" data-bs-dismiss="modal">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <line x1="12" y1="5" x2="12" y2="19"/>
                                <line x1="5" y1="12" x2="19" y2="12"/>
                            </svg>
                            Create new report
                        </a>
                    </div> --}}
                </div>
            </div>
        </div>
        @endforeach
{{-- End Model Box --}}
                        </div>
                         <!-- Pagination Links -->
                         <div class="pagination">
                            {{-- {{ $data->links() }} --}}
                            {{-- {{ $data->links('vendor.pagination.bootstrap-4') }} --}}
                            {!! $data->links('tablar::pagination') !!}
                        </div>
                        @endif


                        {{-- <div class="card-footer d-flex align-items-center">
                            <p class="m-0 text-muted">Showing <span>1</span> to <span>8</span> of <span>16</span>
                                entries</p>


                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>


            function resetSearch() {
            var inputs = document.querySelectorAll('#search-form input[type="text"]');
            inputs.forEach(input => input.value = '');
            document.getElementById('search-form').submit();
        }
        $(document).ready(function() {
            // Check if the flag is set in localStorage
            if (localStorage.getItem('importSuccess') === 'true') {
                toastr.success('', 'Success');

                // Clear the flag after showing the notification
                localStorage.removeItem('importSuccess');
            }
        });
        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('showTableToast') === 'true') {
                toastr.success('Welcome😋 & Enjoy Data Analysis', 'Welcome');
                localStorage.removeItem('showTableToast');
            }
        });
        @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}");
    @endif

    @if(Session::has('error'))
        toastr.error("{{ Session::get('error') }}");
    @endif


    </script>
@endsection
