@if ($errors->count() > 0)
    @foreach ($errors as $error)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $error->message }}
        </div>
    @endforeach
@endif
@if (\Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
            {!! \Session::get('success') !!}

    </div>
@endif
@if (\Session::has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {!! \Session::get('error') !!}
    </div>
@endif
