@extends('tablar::page')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<!-- Include Toastr CSS and JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif



@if ($importSuccess)
<div class="alert alert-success alert-dismissible fade show" role="alert">
    File Imported Successfully, Highlighted with Red!
   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
     <span aria-hidden="true">&times;</span>
   </button>
 </div>
@endif
  @if (session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{session('success')}}
     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
       <span aria-hidden="true">&times;</span>
     </button>
   </div>
  @endif
<div class="modal-dialog modal-lg-custom" role="document">
    @if ($importSuccess)
    <div class="alert alert-success">
        File imported successfully!
    </div>
@endif
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Tables List</h5>
            <a href="/import-csv" class="btn btn-primary d-none d-sm-inline-block"
                           style="float: right;">
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
            {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
        </div>
    {{-- <form action="{{ route('import.csv') }}" method="POST" enctype="multipart/form-data">
        @csrf --}}
        <div class="modal-body">
            <div class="row w-100" >
                <div class="col-lg-12">
                    <div class="mb-3">

                        @foreach($tables as $index => $table)
                        @php
                            if($table===$lastInsertedTable){
                                $badge = "spinner-grow text-danger spinner-grow-sm";
                                $text = "sr-only";
                                $classes = "btn btn-danger d-none d-sm-inline-block ";
                            }else{
                                $badge = " ";
                                $text = "";
                                $classes = "btn btn-primary d-none d-sm-inline-block";
                            }
                        @endphp


                        {{-- <span class="{{$badge}}">{{$text}}</span> --}}
                        <span class="{{$badge}}" role="status">
                            <span class="{{$text}}"></span>
                        </span>
                       <button onclick="onTableClick('{{$table}}')" href="" class="{{$classes}}" style="margin: 5px;">{{$table}} </button>
                       {{-- {{route('table.show', $table)}} --}}
                       @endforeach
                        {{-- <input type="file" class="form-control" name="csv_file" id="csv_file"  data-ms-editor="true" required> --}}
                    </div>
                </div>
                {{-- <div class="col-lg-6">
                    <div class="mb-3"> --}}
                        {{-- <label for="csv_file" class="form-label">Choose CSV File</label>
                        <input type="file" class="form-control" name="csv_file" id="csv_file"  data-ms-editor="true" required> --}}
                    {{-- </div>
                </div> --}}
            </div>
        </div>
        {{-- <div class="modal-footer">
            <button form="clear-form" type="submit" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                Cancel
            </button>
            <button type="submit" href="#" class="btn btn-primary ms-auto" data-bs-dismiss="modal">
               Import
            </button>
        </div> --}}
    {{-- </form> --}}
    </div>
</div>
<script>


        $(document).ready(function() {
            // Check if the flag is set in localStorage
            if (localStorage.getItem('importSuccess') === 'true') {
                toastr.success('Import completed successfully!😍 View Your Table', 'Success');

                // Clear the flag after showing the notification
                localStorage.removeItem('importSuccess');
            }
        });
        // Base URL with a placeholder for the table name
    const tableBaseUrl = "{{ route('table.show', ['table' => 'TABLE_NAME']) }}";

    function onTableClick(tableName) {
        // Replace 'TABLE_NAME' with the actual table name value
        if (tableName) {
            const tablePageUrl = tableBaseUrl.replace('TABLE_NAME', encodeURIComponent(tableName));
            localStorage.setItem('showTableToast', 'true');
            window.location.href = tablePageUrl;
        } else {
            console.error('Table name is undefined.');
        }
    }


</script>

@endsection
