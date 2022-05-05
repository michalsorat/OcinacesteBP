@if($users != null)
<table class="adminTable table main-table table-hover">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">ID</th>
        <th scope="col">Meno</th>
        <th scope="col">Email</th>
        <th scope="col">Rola</th>
        <th scope="col">Vytvorené dňa</th>
        <th scope="col">Uprav rolu</th>
        <th scope="col">Vymaž používateľa</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $counter=>$user)

        @if($user->id <= 2)
            @continue
        @endif

        <tr>
            <td>{{ $counter }}</td>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->Rola['nazov'] }}</td>
            <td>{{ $user->created_at }}</td>
            <td class="pl-5">
                @if($user->rola_id != 3)
                    <button type="button" class="editUserRoleBtn" data-toggle="modal" data-id="{{ $user->id }}" data-target="#editUserRole-modal">
                        <i class="fas fa-edit"></i>
                    </button>
                @endif
            </td>
            <td class="pl-5">
                @if($user->rola_id != 3)
                    <button type="button" class="deleteUserBtn" data-toggle="modal" data-id="{{ $user->id }}" data-target="#deleteUser-modal">
                        <i class="far fa-trash-alt"></i>
                    </button>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<div id="editUserRole-modal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Zmeň rolu používateľovi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @include('partials.admin.admin_editRole')
        </div>
    </div>
</div>

<div id="deleteUser-modal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Vymazať používateľa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Ste si istý, že chcete vymazať tento účet?</p>
                <div class="form-group d-flex justify-content-center mt-4">
                    <button type="button" class="btn btn-primary cancel mr-4"
                            data-dismiss="modal"
                            aria-label="Close">Zrušiť
                    </button>

                    <form action="{{ route('deleteUser') }}" method="POST">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            Vymazať
                        </button>
                        <input type="text" id="userID" name="userID" value="" hidden/>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@else
    <h2 class="text-center my-3">Žiadne výsledky</h2>
@endif


@if(!empty(Session::get('success')))
    <div class="alert alert-success"> {{ Session::get('success') }}</div>
@endif
@if(!empty(Session::get('error')))
    <div class="alert alert-danger"> {{ Session::get('error') }}</div>
@endif

<script>
    $('.editUserRoleBtn').on('click', function (){
        let clickedUserID = ($(this).data('id'));

        $.ajax({
            url:'/userRoleInfo/'+ clickedUserID,
            type:'GET',
            success:function(data){
                let editRoleModal = $('#editUserRole-modal');
                editRoleModal.find('.modal-body').html(data);
                editRoleModal.modal('show');
            },
            error: function () {
                let editRoleModal = $('#editUserRole-modal');
                editRoleModal.html('Something went wrong!');
                editRoleModal.modal('show');
            }
        });
    });

    $('.deleteUserBtn').on('click', function (){
        $('#deleteUser-modal').find('.modal-body').find('#userID').val($(this).data('id'));
    });
</script>
