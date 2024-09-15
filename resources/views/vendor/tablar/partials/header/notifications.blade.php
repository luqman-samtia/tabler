@auth <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="nav-item dropdown d-none d-md-flex me-3">
        <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
           aria-label="Show notifications">
            <!-- Download SVG icon from http://tabler-icons.io/i/bell -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                 stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                <path
                    d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"/>
                <path d="M9 17v1a3 3 0 0 0 6 0v-1"/>
            </svg>


            {{-- @if (isset($notifications) && $notifications->isNotEmpty())
            @foreach ($notifications as $item) --}}
            @if (isset($notifications) && $notifications->isNotEmpty())
            @php
            $anyNew = $notifications->contains('is_new', 1);
            @endphp
            {{-- @foreach ($notifications as $item) --}}
            <span class="badge {{ $anyNew ? 'bg-red spinner-grow spinner-grow-sm' : 'bg-muted' }}" ></span>
            {{-- @endforeach
            @endif --}}

        </a>
        <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Last updates</h3>
                </div>
                <div class="list-group list-group-flush list-group-hoverable">
                    {{-- @if (isset($notifications) && $notifications->isNotEmpty()) --}}
                    @foreach ($notifications as $item)
                    <div class="list-group-item {{ !$item->is_new ? 'bg-light' : '' }}"  data-id="{{ $item->id }}"
                        onclick="markAsRead({{ $item->id }})">
                        <div class="row align-items-center">
                            <div class="col-auto"><span
                                    class="status-dot  {{ !$item->is_new ? 'bg-muted' : 'bg-red status-dot-animated' }} d-block"></span>
                                </div>
                            <div class="col text-truncate ">
                                <a href="{{ url('/table/' . $item->type)}}" class="text-body d-block">{{$item->type}}</a>
                                <div class="d-block text-muted  mt-n1">
                                    {{$item->data}}
                                </div>
                            </div>
                            <div class="col-auto">
                                <a href="#" class="list-group-item-actions">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon {{ $item->is_new ? 'text-red ' : 'text-muted' }}"
                                         width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                         stroke="currentColor" fill="{{ $item->is_new ? 'text-red' : 'none' }}" stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path
                                            d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else

                            <p>No new notifications</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endif
<script>
    function markAsRead(id) {
        fetch('{{ url("/notifications/mark-as-read") }}/' + id, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({id :id})
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const item = document.querySelector(`.list-group-item[data-id="${id}"]`);
                const badge = document.querySelector(`span[data-id="${id}"]`);
                item.classList.remove('bg-light');
                badge.classList.remove('bg-red');
                badge.classList.add('bg-muted');
                item.querySelector('.status-dot').classList.remove('bg-red');
                item.querySelector('.status-dot').classList.add('bg-muted');

            }
        })
        .catch(error => console.error('Error:', error));
    }


    </script>
