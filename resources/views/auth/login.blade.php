<div class="dropdown-menu dropdown-menu-right p-3" role="menu" style="width: 300px">
    <form class="loginForm" method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group mt-2">
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="Emailová adresa" value="" required>

            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="form-group">
            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Heslo" required>

            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="form-group">
            <input type="submit" name="login-submit" id="login-submit" class="form-control btn btn-success" value="Prihlásiť sa">
        </div>

        <div class="form-group mb-0 text-center">
            <span class="outer-link">Ešte nemáte účet? <a href="{{ route('register') }}">Zaregistrujte sa</a></span>
        </div>
    </form>
</div>

<script>
    var loginForm = $(".loginForm");
    loginForm.submit(function(e){
        e.preventDefault();
        var formData = loginForm.serialize();

        $.ajax({
            url:'/login',
            type:'POST',
            data:formData,
            success:function(response){
                window.location = response.redirectPath;
            },
            error: function (response) {
                console.log("zle je");
            }
        });
    });
</script>
