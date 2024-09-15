@extends('tablar::page')

@section('content')
    <!-- Page header -->
    @if(!empty(session('success')))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
       {{session('success')}}.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    @endif
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
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                  <span class="d-none d-sm-inline">
                    <a href="/tables" class="btn btn-white">
                      View Tables
                    </a>
                  </span>
                        <a href="/import-csv" class="btn btn-primary d-none d-sm-inline-block"
                           >
                           {{-- data-bs-toggle="modal" --}}
                           {{-- data-bs-target="#modal-report" --}}
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
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Invoices</h3>
                        </div>
                        <div class="card-body border-bottom py-3">
                            <div class="d-flex">
                                <div class="text-muted">
                                    Show
                                    <div class="mx-2 d-inline-block">
                                        <input type="text" class="form-control form-control-sm" value="8" size="3"
                                               aria-label="Invoices count">
                                    </div>
                                    entries
                                </div>
                                <div class="ms-auto text-muted">
                                    Search:
                                    <div class="ms-2 d-inline-block">
                                        <input type="text" class="form-control form-control-sm"
                                               aria-label="Search invoice">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                <tr>
                                    <th class="w-1"><input class="form-check-input m-0 align-middle" type="checkbox"
                                                           aria-label="Select all invoices"></th>
                                    <th class="w-1">Sr.NO
                                        <!-- Download SVG icon from http://tabler-icons.io/i/chevron-up -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="icon icon-sm text-dark icon-thick" width="24" height="24"
                                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <polyline points="6 15 12 9 18 15"/>
                                        </svg>
                                    </th>
                                    <th>Reg No</th>
                                    <th>Name</th>
                                    <th>CNIC</th>
                                    <th>Mobile No</th>
                                    <th>Tell No</th>
                                    <th>Project Type</th>
                                    <th>Phase</th>
                                    <th>Plot Size</th>
                                    <th>Sector</th>
                                    <th>Plot No</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($btls as $btl)
                                <tr>


                                    <td><input class="form-check-input m-0 align-middle" type="checkbox"
                                               aria-label="Select invoice"></td>
                                    <td><span class="text-muted">{{$btl->id}}</span></td>
                                    <td><a href="#" class="text-reset" tabindex="-1">{{$btl->reg_no}}</a></td>
                                    <td>
                                        <span class="flag flag-country-us"></span>
                                       {{$btl->name}}
                                    </td>
                                    <td>
                                        {{$btl->cnic}}
                                    </td>
                                    <td>
                                        {{$btl->mobile}}
                                    </td>
                                    <td>
                                         {{$btl->tell_no}}
                                    </td>
                                    <td>{{$btl->project_type}}</td>
                                    <td>{{$btl->phase}}</td>
                                    <td>{{$btl->plot_size}}</td>
                                    <td>{{$btl->sector}}</td>
                                    <td>{{$btl->plot_no}}</td>
                                    <td class="text-end">
                            <span class="dropdown">
                              <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport"
                                      data-bs-toggle="dropdown">Actions</button>
                              <div class="dropdown-menu dropdown-menu-end">
                                <a href="/home/edit/{{$btl->id}}" class="dropdown-item" >
                                  Edit
                                </a>
                                <button type="submit"  form="delete-form" class="dropdown-item" >
                                  Delete
                                </button>
                              </div>
                            </span>
                                    </td>
                                </tr>
                                <form method="get" action="/home/delete/{{$btl->id}}" id="delete-form" onsubmit="confirmDelete(event, this)" class="hidden" >
                                    @csrf
                                   @method('GET')
                                </form>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            <p class="m-0 text-muted">Showing <span>1</span> to <span>8</span> of <span>16</span>
                                entries</p>
                            <ul class="pagination m-0 ms-auto">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                        <!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <polyline points="15 6 9 12 15 18"/>
                                        </svg>
                                        prev
                                    </a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item active"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">4</a></li>
                                <li class="page-item"><a class="page-link" href="#">5</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">
                                        next <!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                             viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <polyline points="9 6 15 12 9 18"/>
                                        </svg>
                                    </a>
                                </li>
                            </ul>

                            {{--
                             Built In Paginator Component
                             {!! $modelName->links('tablar::pagination') !!}
                             --}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(event, form) {
                event.preventDefault();
                if (confirm("Are you sure you want to delete this item?")) {
                    form.submit();
                }
            }
    </script>
@endsection
