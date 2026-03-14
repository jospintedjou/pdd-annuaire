@if(session()->get('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{session()->get('error')}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@elseif(session()->get('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{session()->get('success')}}
        @if(session()->get('success_link'))
            &nbsp;<a href="{{ session()->get('success_link') }}" class="alert-link">{{ session()->get('success_link_text', 'Voir') }}</a>
        @endif
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif