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


@if (session('success'))
<div>
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div>
    {{ session('error') }}
</div>
@endif
@if(!empty(session('error')))
<div aria-live="polite" aria-atomic="true" class="position-relative">
    <!-- Position it: -->
    <!-- - `.toast-container` for spacing between toasts -->
    <!-- - `.position-absolute`, `top-0` & `end-0` to position the toasts in the upper right corner -->
    <!-- - `.p-3` to prevent the toasts from sticking to the edge of the container  -->
    <div class="toast-container position-absolute top-0 end-0 p-3">

      <!-- Then put toasts within -->
      <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <img src="..." class="rounded me-2" alt="...">
          <strong class="me-auto">Bootstrap</strong>
          <small class="text-muted">just now</small>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body text-danger">
            {{session('error')}}.
        </div>
      </div>

    </div>
  </div>
  @endif
  @if(!empty(session('success')))
<div aria-live="polite" aria-atomic="true" class="position-relative">
    <!-- Position it: -->
    <!-- - `.toast-container` for spacing between toasts -->
    <!-- - `.position-absolute`, `top-0` & `end-0` to position the toasts in the upper right corner -->
    <!-- - `.p-3` to prevent the toasts from sticking to the edge of the container  -->
    <div class="toast-container position-absolute top-0 end-0 p-3">

      <!-- Then put toasts within -->
      <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
          <img src="..." class="rounded me-2" alt="...">
          <strong class="me-auto">Bootstrap</strong>
          <small class="text-muted">just now</small>
          <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body text-success">
            {{session('success')}}.
        </div>
      </div>

    </div>
  </div>
  @endif
<div class="modal-dialog modal-lg-custom" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Import File</h5>
            {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
        </div>
    <form id="csvForm" action="{{ route('import.csv') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
            <div class="row " >
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label for="csv_file" class="form-label">Choose CSV File</label>
                        <input type="file" class="form-control" name="csv_file" id="csv_file"  data-ms-editor="true" required>
                        @error('csv_file')
                        <div class="text-danger text-lg ">{{$message}}</div>
                        @enderror
                    </div>
                </div>

            </div>
        </div>
        <div class="modal-footer">
            <button form="clear-form" type="submit" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                Cancel
            </button>
            <button  type="submit" href="#" class="btn btn-primary ms-auto" data-bs-dismiss="modal">
               Import
            </button>
        </div>
    </form>
    </div>
</div>
<!-- Progress Bar -->
{{-- <div id="progressWrapper" style="display:none;">
    <div class="progress">
        <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
    </div>
    <p id="progressText">0% completed</p>
</div> --}}
<div class="progress-container" style="display: none;">
    <div class="progress-bar" id="progress-bar">
        <div class="progress" id="progress"></div>
    </div>
</div>
<div id="progress-text"></div>
<form method="get" action="/home" id="clear-form" class="hidden" >
    @csrf
   @method('GET')
</form>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios@0.21.1/dist/axios.min.js"></script>
<script>
const form = document.getElementById('csvForm');
        const progressBar = document.getElementById('progress-bar');
        const progressInner = document.getElementById('progress');
        const progressContainer = document.querySelector('.progress-container');
        const progressText = document.getElementById('progress-text');

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            progressText.textContent = 'Please wait... checking progress.';
            fetch('/import-csv', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                progressContainer.style.display = 'block';
                checkProgress(data.batchId);
            })
            .catch(error => {
                console.error('Error:', error);
                // progressText.textContent = 'Error starting import....';
                progressText.textContent = 'Server Not Supported';
                progressText.style.color = 'red';
            });
        });

        function checkProgress(batchId) {
    fetch(`/import-progress/${batchId}`)
        .then(response => response.json())
        .then(data => {
            updateProgressBar(data.progress);
            if (data.finished) {
                if (data.failed) {
                    showFailureMessage();
                } else {
                    showSuccessMessage();
                }
            } else {
                setTimeout(() => checkProgress(batchId), 1000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            progressText.textContent = 'Error checking progress.';
            progressText.textContent.style.color = 'red';
            // progressText.textContent = 'Error checking progress.';
        });
}

function updateProgressBar(progress) {
    progressBar.style.width = `${progress}%`;
    progressInner.style.width = `${progress}%`;
    progressText.textContent = `Progress: ${progress.toFixed(2)}%`;
    // Calculate color
    let r, g;
    if (progress < 50) {
        // Red to Yellow (0% to 50%)
        r = 255;
        g = Math.round(5.1 * progress);
    } else {
        // Yellow to Green (50% to 100%)
        r = Math.round(510 - 5.1 * progress);
        g = 255;
    }
    const b = 0;

    // Set background color
    progressInner.style.backgroundColor = `rgb(${r}, ${g}, ${b})`;

    // Optional: Change text color for better visibility
    progressInner.style.color = progress > 50 ? 'black' : 'white';
    //progressBar.style.background.red = `Progress: ${progress.toFixed(2)}%`;
}

        function showSuccessMessage(response) {
            progressText.textContent = 'Import completed successfully!😍';
            progressText.style.color = 'yellow';

            toastr.success('Import completed successfully!😍','Success');

            // toastr.success('import completed');
            window.location = '/home';

            localStorage.setItem('importSuccess', 'true');
        }

        function showFailureMessage() {
            progressText.textContent = 'Import failed. Please check the logs for details.';
            progressText.style.color = 'red';
        }

        // $(document).ready(function() {
    // $('#csv-upload-form').on('submit', function(e) {
    //     e.preventDefault(); // Prevent default form submission

    //     var formData = new FormData(this);

    //     $.ajax({
    //         url: $(this).attr('action'), // The URL to send the request to (your controller route)
    //         type: 'POST',
    //         data: formData,
    //         contentType: false,
    //         processData: false,
    //         success: function(response) {
    //             // Check the response status and show the appropriate toaster
    //             if (response.status === 'success') {
    //                 toastr.success(response.message);
    //             } else if (response.status === 'error') {
    //                 toastr.error(response.message);
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             // Display an error toaster for AJAX errors
    //             toastr.error('An error occurred during the file upload. Please try again.');
    //         }
    //     });
    // });
// });
</script>
@endsection
