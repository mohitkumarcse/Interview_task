@extends('layout')


@section('content')
<div class="card">
    <div class="card-header">{{ __('Sign In') }}</div>

    <div class="card-body">
        <form method="POST" action="{{ route('sign-in') }}" id="sign_in_form">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" class="form-control" name="email">

            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" class="form-control " name="password">

            </div>

            <button type="submit" class="btn btn-primary" id="btn_sign_in_form">Sign In</button>
            <a href="{{ route('sign-up') }}"><button type="button" class="btn btn-primary" style="float:right;">Sign
                    Up</button></a>
        </form>
    </div>


</div>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#sign_in_form').on('submit', function(e) {
            e.preventDefault();

            toastr.remove();
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),

                success: function(response) {
                    if(response.status === "success"){
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message
                        }).then(() => {
                            window.location.href = response.redirect_url;
                        });
                     }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        for (var field in errors) {
                            toastr.error(errors[field].join(', '));
                        }
                    } else {
                      toastr.error(xhr.responseJSON.message);

                    }
                }
            });
        });
    });

</script>