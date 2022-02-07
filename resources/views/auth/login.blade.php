<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Prihlásiť sa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">Emailová adresa</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">Heslo</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        </div>
                    </div>

                    @if($errors->has('email') || $errors->has('password'))
                        <script>
                            $(function() {
                                $('#loginModal').modal({
                                    show: true
                                });
                            });
                        </script>
                        @foreach( $errors->all() as $message )
                            <div class="alert alert-danger">
                                <span>{{ $message }}</span>
                            </div>
                        @endforeach
                    @endif

{{--                    <div class="form-group row">--}}
{{--                        <div class="col-md-6 offset-md-4">--}}
{{--                            <div class="form-check">--}}
{{--                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>--}}

{{--                                <label class="form-check-label" for="remember">--}}
{{--                                    {{ __('Remember Me') }}--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <div class="form-group row mb-0">
                        <div class="col-md-10 offset-md-2">
                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif

                            <button type="submit" class="btn btn-primary">
                                Prihlásiť sa
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <span class="outer-link">Ešte nemáte účet? <a href="#" data-toggle="modal" data-target="#registerModal" data-dismiss="modal">Zaregistrujte sa</a></span>
            </div>
        </div>

    </div>
</div>

{{--<script>--}}
{{--     var loginForm = $(".loginForm");--}}
{{--     loginForm.submit(function(e){--}}
{{--         e.preventDefault();--}}
{{--         var formData = loginForm.serialize();--}}
{{--    --}}
{{--         $.ajax({--}}
{{--             url:'/login',--}}
{{--             type:'POST', --}}
{{--             data:formData,--}}
{{--             success:function(response){--}}
{{--                 window.location = response.redirectPath;--}}
{{--             },--}}
{{--             error: function (response) {--}}
{{--                 console.log("zle je");--}}
{{--             }--}}
{{--         });--}}
{{--    });--}}
{{--</script>--}}
