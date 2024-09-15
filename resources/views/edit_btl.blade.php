@extends('tablar::page')

@section('content')
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Edit Report</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    <form action="{{route('table.update', [$table, $item->id])}}" method="POST">
        @csrf
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Reg No</label>
                        <input type="text" class="form-control" name="reg_no" value="{{$btls->reg_no}}" spellcheck="false" data-ms-editor="true" >
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{$btls->name}}" >
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">CNIC</label>
                        <input type="text" name="cnic" value="{{$btls->cnic}}" class="form-control" spellcheck="false" data-ms-editor="true">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Mobile No</label>
                        <input type="text" name="mobile" value="{{$btls->mobile}}" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Tell No</label>
                        <input type="text" name="tell_no" value="{{$btls->tell_no}}" class="form-control" spellcheck="false" data-ms-editor="true">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Project Type</label>
                        <input type="text" name="project_type" value="{{$btls->project_type}}" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Phase</label>
                        <input type="text" name="phase" value="{{$btls->phase}}" class="form-control" spellcheck="false" data-ms-editor="true">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Plot Size</label>
                        <input type="text" name="plot_size" value="{{$btls->plot_size}}" class="form-control">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Sector</label>
                        <input type="text" name="sector" value="{{$btls->sector}}" class="form-control" spellcheck="false" data-ms-editor="true">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Plot No</label>
                        <input type="text" name="plot_no" value="{{$btls->plot_no}}" class="form-control">
                    </div>
                </div>
                {{-- <div class="col-lg-12">
                    <div>
                        <label class="form-label">Additional information</label>
                        <textarea class="form-control" rows="3" spellcheck="false" data-ms-editor="true"></textarea>
                    </div>
                </div> --}}
            </div>
        </div>
        <div class="modal-footer">
            <button form="clear-form" type="submit" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                Cancel
            </button>
            <button type="submit" href="#" class="btn btn-primary ms-auto" data-bs-dismiss="modal">
                <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                {{-- <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg> --}}
               Update
            </button>
        </div>
    </form>
    </div>
</div>
<form method="get" action="/home" id="clear-form" class="hidden" >
    @csrf
   @method('GET')
</form>

@endsection
