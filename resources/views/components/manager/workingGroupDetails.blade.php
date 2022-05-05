<div class="row">
    <div class="col-lg-6 px-4">
        @if(($workingGroup->users)->isEmpty())
            <h5 class="text-center mt-4 font-weight-bolder">Pracovná čata nemá priradených zamestnancov</h5>
        @else
            <h6 class="mt-4 mb-3 font-weight-bolder">Členovia pracovnej čaty</h6>
            <form class="tableHolderSlider" id="removeUsersForm" method="POST" action="{{ route('removeGroupUsers', $workingGroup->id) }}">
                @csrf
                @method('PUT')
                <table class="table table-hover usersTable">
                    <thead>
                    <tr>
                        <th scope="col">Meno</th>
                        <th scope="col">Emailová adresa</th>
                        <th scope="col">Vybrať</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($workingGroup->users as $user)
                        <tr class="group-user-row-remove">
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td class="pl-4">
                                <input type="checkbox" class="remove-user-cb" name="selected_users[]" value="{{$user->id}}">
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </form>
        @endif

        <button type="button" class="btn btn-sm btn-danger float-right mt-2 mr-2" id="removeUsersBtn">
            Odobrať zamestnancov
        </button>
    </div>

    <div class="col-lg-6 px-4">
        @if(($availUsers)->isEmpty())
            <h5 class="text-center mt-4 font-weight-bolder">Žiadny zamestnanci na priradenie</h5>
        @else
            <h6 class="mt-4 mb-3 font-weight-bolder">Dostupní zamestnanci na priradenie pracovnej čate</h6>
            <form class="tableHolderSlider" id="addUsersForm" method="POST" action="{{ route('addGroupUsers', $workingGroup->id) }}">
                @csrf
                @method('PUT')
                <table class="table table-hover usersTable">
                    <thead>
                    <tr>
                        <th scope="col">Meno</th>
                        <th scope="col">Emailová adresa</th>
                        <th scope="col">Vybrať</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($availUsers as $user)
                        <tr class="group-user-row-add">
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td class="pl-4">
                                <input type="checkbox" class="add-user-cb" name="selected_users[]" value="{{$user->id}}">
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </form>
            <button type="button" class="btn btn-sm btn-secondary float-right mt-2" id="addUsersBtn">
                Pridať zamestnancov
            </button>
        @endif
    </div>

    <form class="col-md-3 mt-lg-4 mt-xl-5 assigned-categories form-group" id="changeCatForm" method="POST" action="{{ route('changeAssignedCategories', $workingGroup->id) }}">
        @csrf
        @method('PUT')

        <h6 class="mt-4 mb-3 font-weight-bolder">Kategórie riešených problémov</h6>

        @foreach($categories as $category)
            <div class="form-check my-1">
                <input class="form-check-input categories-input" type="checkbox" name="newCategories[]" value="{{$category->kategoria_problemu_id}}"/>
                <label class="form-check-label">
                    {{$category->nazov}}
                </label>
            </div>
        @endforeach

        <button type="button" class="btn btn-sm btn-secondary float-right mt-2 mb-4" id="changeAssignedCatBtn">
            Zmeň kategórie
        </button>

        <button type="button" class="btn btn-sm btn-danger float-right mt-2 mr-2" id="cancelChangeCat">
            Zrušiť
        </button>

        <button type="button" class="btn btn-secondary btn-block mt-5" data-toggle="modal" data-target="#historyModal">
            História pracovnej čaty
        </button>
    </form>
</div>

@include('partials.manager.manager_workingGroupHistory')

<script>
    $(document).ready(function () {
        let assignedCat = [];
        @foreach($workingGroup->assignedCategories as $category)
            assignedCat.push({{$category->kategoria_problemu_id}});
        @endforeach
        $('.categories-input[type=checkbox]').each(function () {
            if (assignedCat.includes(parseInt($(this).val()))) {
                $(this).prop('checked', true);
            }
            else {
                $(this).prop('checked', false);
            }
            $(this).prop('disabled', true);
        });
    });

    $('#changeAssignedCatBtn').on('click', function() {
        if ($(this).hasClass('clicked')) {
            $('#changeCatForm').submit();
        }
        else {
            $(this).html('Uložiť');
            $(this).removeClass('btn-secondary');
            $(this).addClass('btn-primary');
            $(this).toggleClass('clicked');
            $('#cancelChangeCat').show();
            $('.categories-input[type=checkbox]').each(function () {
                $(this).prop('disabled', false);
            });
        }
    });

    $('#cancelChangeCat').on('click', function() {
        let prevBtn = $('#changeAssignedCatBtn');
        prevBtn.html('Zmeň kategórie');
        prevBtn.removeClass('btn-primary');
        prevBtn.removeClass('clicked');
        prevBtn.addClass('btn-secondary');
        let assignedCat = [];
        @foreach($workingGroup->assignedCategories as $category)
            assignedCat.push({{$category->kategoria_problemu_id}});
        @endforeach
        $('.categories-input[type=checkbox]').each(function () {
            if (assignedCat.includes(parseInt($(this).val()))) {
                $(this).prop('checked', true);
            }
            else {
                $(this).prop('checked', false);
            }
            $(this).prop('disabled', true);
        });
        $(this).hide();
    });

    $('.group-user-row-remove').on('click', function() {
        let cb = $(this).find('.remove-user-cb');
        if (cb.is(':checked')) {
            cb.prop('checked', false);
        }
        else {
            cb.prop('checked', true);
        }
        if ($('.remove-user-cb').is(':checked')) {
            $('#removeUsersBtn').show();
        }
        else {
            $('#removeUsersBtn').hide();
        }
    });

    $('.group-user-row-add').on('click', function() {
        let cb = $(this).find('.add-user-cb');
        if (cb.is(':checked')) {
            cb.prop('checked', false);
        }
        else {
            cb.prop('checked', true);
        }
        if ($('.add-user-cb').is(':checked')) {
            $('#addUsersBtn').show();
        }
        else {
            $('#addUsersBtn').hide();
        }
    });

    $('#removeUsersBtn').on('click', function() {
        $('#removeUsersForm').submit();
    });

    $('#addUsersBtn').on('click', function() {
        $('#addUsersForm').submit();
    });

</script>
