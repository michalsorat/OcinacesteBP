@extends('layouts.admin_app')

@section('content')

    @if (Session::has('message'))
        <div class="alert alert-info">{{ Session::get('message') }}</div>
    @endif

    <div id="delete-modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Vymazať záznam</h5>
                </div>
                <div class="modal-body">

                    <p>Ste si istý, že chcete vymazať záznam?</p>
                    <ul class="d-flex align-items-center justify-content-center mt-4">
                        <li>
                            <button type="button" class="btn btn-primary cancel mr-4" data-dismiss="modal"
                                    aria-label="Close">Zrušiť
                            </button>
                        </li>
                        <!--<li><button type="button" class="btn btn-danger delete">Vymazať</button></li>-->
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('status') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <section class="main-container h-100">
        <div class="container-fluid">
            <h5 class="mt-lg-3 mt-2 ml-4 font-weight-bolder">Zoznam používateľov</h5>
            <div class="row">
                <div class="col-xl-2 col-12 px-0 pb-3 bg-light">
                    <div class="row mx-1">
                        <div class="filter-option col-12 input-group">
                            <input id="search-user-input" class="typeahead form-control" type="search"
                                   placeholder="Vyhľadaj používateľa" autocomplete="off">
                            <span class="input-group-append">
                                <button id="search-user-btn" class="btn btn-outline-secondary" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                        <div class="filter-option col-12">
                            <h6 class="p-1 border-bottom">Používateľské roly</h6>
                            @foreach($roles as $role)
                                @if($role->rola_id != 2)
                                    <div class="form-check ml-3">
                                        <input class="role-check form-check-input" type="checkbox" value="{{$role->rola_id}}" id="checkbox" name="checkedRoles[]" checked>
                                        <label class="form-check-label" for="checkbox">
                                            {{$role->nazov}}
                                        </label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="filter-option col-12 col-md-6 col-xl-12">
                            <h6 class="p-1 border-bottom">Zoradiť podľa registrácie</h6>
                            <select id="adminOrderBy" class="input-filter form-input w-100" name="orderBy">
                                <option value="" selected disabled hidden>Vyberte možnosť</option>
                                <option value="1">Od najstaršie registrovaných</option>
                                <option value="2">Od najnovšie registrovaných</option>
                            </select>
                        </div>
                        <div class="filter-option col-12 col-md-6 col-xl-12">
                            <h6 class="p-1 border-bottom">Počet zaregistrovaných</h6>
                            <select id="regUsersCount" class="countSelect input-filter form-input w-100" name="orderBy">
                                <option value="1">Za posledných 24 hodín</option>
                                <option value="7">Za posledných 7 dní</option>
                                <option value="30">Za posledných 30 dní</option>
                                <option value="365">Za posledný rok</option>
                                <option value="" selected>Spolu</option>
                            </select>
                            <div class="pt-2 pl-2">
                                <b class="resultNumber" id="usersNr">{{$usersCount}}</b>
                            </div>
                        </div>
                        <div class="filter-option col-12 col-md-6 col-xl-12">
                            <h6 class="p-1 border-bottom">Počet zaznamenaných problémov</h6>
                            <select id="problemsCount" class="countSelect input-filter form-input w-100" name="orderBy">
                                <option value="1">Za posledných 24 hodín</option>
                                <option value="7">Za posledných 7 dní</option>
                                <option value="30">Za posledných 30 dní</option>
                                <option value="365">Za posledný rok</option>
                                <option value="" selected>Spolu</option>
                            </select>
                            <div class="pt-2 pl-2">
                                <b class="resultNumber" id="problemsNr">{{$problemsCount}}</b>
                            </div>
                        </div>
                        <div class="filter-option col-12 col-md-6 col-xl-12">
                            <h6 class="p-1 border-bottom">Počet vyriešených problémov</h6>
                            <select id="solvedProblemsCount" class="countSelect input-filter form-input w-100" name="orderBy">
                                <option value="1">Za posledných 24 hodín</option>
                                <option value="7">Za posledných 7 dní</option>
                                <option value="30">Za posledných 30 dní</option>
                                <option value="365">Za posledný rok</option>
                                <option value="" selected>Spolu</option>
                            </select>
                            <div class="pt-2 pl-2">
                                <b class="resultNumber" id="solvedProblemsNr">{{$solvedProblemsCount}}</b>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="table-responsive col-xl-10 col-12">
                    @include('components.admin.usersTable')
                </div>

            </div>
        </div>

    </section>

    <script type="text/javascript">
        var path = "{{ route('autocompleteUser') }}";
        $('#search-user-input').typeahead({
            source: function (query, process) {
                return $.get(path, {query: query}, function (data) {
                    return process(data);
                });
            }
        });

        $('input[type="checkbox"]').on('change', function() {
            let checkboxArr = [];
            $('input[type="checkbox"]:checked').each(function() {
                checkboxArr.push($(this).val());
            });
            let selectedOrder = $('#adminOrderBy').val();
            let userNameInput = $('#search-user-input').val();

            $.ajax({
                url:'/filter',
                type:'GET',
                data:{orderBy:selectedOrder, checkedRoles:checkboxArr, nameInput:userNameInput},
                success:function(data){
                    $('.table-responsive').html(data);
                },
                error: function () {
                    $('.table-responsive').html('Something went wrong');
                }
            });
        });

        $('#adminOrderBy').on('change', function() {
            let checkboxArr = [];
            $('input[type="checkbox"]:checked').each(function() {
                checkboxArr.push($(this).val());
            });
            let selectedOrder = $('#adminOrderBy').val();
            let userNameInput = $('#search-user-input').val();

            $.ajax({
                url:'/filter',
                type:'GET',
                data:{orderBy:selectedOrder, checkedRoles:checkboxArr, nameInput:userNameInput},
                success:function(data){
                    $('.table-responsive').html(data);
                },
                error: function () {
                    $('.table-responsive').html('Something went wrong');
                }
            });
        });
        $('#search-user-btn').on('click', function() {
            let checkboxArr = [];
            $('input[type="checkbox"]:checked').each(function() {
                checkboxArr.push($(this).val());
            });
            let selectedOrder = $('#adminOrderBy').val();
            let userNameInput = $('#search-user-input').val();

            $.ajax({
                url:'/filter',
                type:'GET',
                data:{orderBy:selectedOrder, checkedRoles:checkboxArr, nameInput:userNameInput},
                success:function(data){
                    $('.table-responsive').html(data);
                },
                error: function () {
                    $('.table-responsive').html('Something went wrong');
                }
            });
        });

        $('.countSelect').on('change', function() {
            let problemDate = $('#problemsCount').val();
            let problemSolvedDate = $('#solvedProblemsCount').val();
            let userDate = $('#regUsersCount').val();

            $.ajax({
                url:'/updateCounts',
                type:'GET',
                data:{problemDate: problemDate, problemSolvedDate: problemSolvedDate, userDate: userDate},
                success:function(data){
                    $('#problemsNr').html(data.problemsCount);
                    $('#usersNr').html(data.usersCount);
                    $('#solvedProblemsNr').html(data.solvedProblemsCount);
                },
                error: function () {
                    console.log("Something went wrong");
                }
            });
        });

        setInterval(function () {
            $(".alert").fadeOut();
        }, 3000);
    </script>
@endsection
