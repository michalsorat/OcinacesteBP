<div id="edit-modal-{{ Auth::user()->id }}" class="modal edit-modal" tabindex="-1"
     role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Zmena používateľského konta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">
                <form action="{{ route('pouzivatelia.update', Auth::user()->id) }}"
                      method="POST" class="w-100">
                    @csrf
                    @method('PUT')

                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Meno a Priezvisko</label>

                        <div class="col-md-6">
                            <input id="name" class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{Auth::user()->name}}" required autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">Emailová adresa</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{Auth::user()->email}}" required autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">Nové heslo</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Heslo znovu</label>

                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control @error('password') is-invalid @enderror" name="password_confirmation">
                        </div>
                    </div>

                    @if($errors->has('email') || $errors->has('password'))
                        <script>
                            $(function() {
                                $('#edit-modal-{{Auth::user()->id}}').modal({
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
                    <div class="form-group d-flex justify-content-center">
                        <button type="button" class="btn btn-primary cancel mr-4"
                                data-dismiss="modal"
                                aria-label="Close">Zrušiť
                        </button>

                        <button type="submit" class="btn btn-primary">
                            Aktualizovať zmeny
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
