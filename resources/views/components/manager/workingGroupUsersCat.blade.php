<form class="col-3 assigned-categories form-group" id="changeCatForm" method="POST" action="{{ route('changeAssignedCategories', $selGroup[0]->id) }}">
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

    <button type="button" class="btn btn-sm btn-secondary float-right mt-2" id="changeAssignedCatBtn">
        Zmeň kategórie
    </button>

    <button type="button" class="btn btn-sm btn-danger float-right mt-2 mr-2" id="cancelChangeCat">
        Zrušiť
    </button>
</form>

<script>
    $(document).ready(function () {
        let assignedCat = [];
        @foreach($selGroup[0]->assignedCategories as $category)
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
        @foreach($selGroup[0]->assignedCategories as $category)
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
</script>


