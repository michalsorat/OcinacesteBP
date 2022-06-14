@extends('layouts.app')

@section('content')
    <div class="about-project-bnr">

    </div>

    <div class="about-section container">
        <h1 class="text-center font-weight-bold">O PROJEKTE</h1>

        <p class="about-info">Webová aplikácia Oči na ceste vzniká v spolupráci so Správou a údržbou ciest Trnavského samosprávneho kraja (SÚC&nbsp;TTSK) s cieľom zjednodušiť, zrýchliť a skvalitniť celkové fungovanie verejnej správy.</p>
        <p class="about-info">Jej podstata spočíva v zapojení verejnosti do procesu získavania údajov o stave cestnej infraštruktúry a problémoch na nej. Tieto informácie sú zaznamenávané v systéme pomocou hlásení o problémoch a sprostredkované správcom ciest Trnavského
            samosprávneho kraja.</p>

        <h2 class="text-center font-weight-bold mt-5 mb-4">Na vývoji tohto projektu sa podielali:</h2>
        <div class="d-md-flex justify-content-around d-inline-block mb-3">
            <div class="card mb-md-0 mb-3" style="width: 18rem">
                <img class="card-img-top" src="img/developer.jpg" alt="Card image cap">
                <div class="card-body px-1 py-3">
                    <h6 class="card-title mb-1 font-weight-bold">Michal Sorát</h6>
                    <p class="title font-italic">Vývojár</p>
                    <p class="card-text">Študent 3. ročníku na FIIT STU v Bratislave. Vývojár nadstavby bakalárskeho projektu Oči&nbsp;na&nbsp;ceste.</p>
                </div>
            </div>

            <div class="card mb-md-0 mb-3" style="width: 18rem">
                <img class="card-img-top" src="img/TK.jpg" alt="Card image cap">
                <div class="card-body px-1 py-3">
                    <h6 class="card-title mb-1 font-weight-bold">Ing. Tomáš Kováčik, PhD</h6>
                    <p class="title font-italic">Supervízor</p>
                    <p class="card-text">Vedúci bakalárskeho projektu Oči&nbsp;na&nbsp;ceste, koordinátor a kreatívny člen tímu.</p>
                </div>
            </div>
        </div>

        <div class="d-md-flex justify-content-around d-inline-block mb-3">
            <div class="card mb-md-0 mb-3" style="width: 18rem">
                <img class="card-img-top" src="img/user.png" alt="Card image cap">
                <div class="card-body px-1 py-3">
                    <h6 class="card-title mb-1 font-weight-bold">Bc. Denis Piovarči</h6>
                    <p class="title font-italic">Vývojár</p>
                    <p class="card-text">Študent na FIIT STU v Bratislave. Vývojár pôvodnej verzie bakalárskeho projektu Oči&nbsp;na&nbsp;ceste v rokoch 2019/2020.</p>
                </div>
            </div>

            <div class="card mb-md-0 mb-3" style="width: 18rem">
                <img class="card-img-top" src="img/user.png" alt="Card image cap">
                <div class="card-body px-1 py-3">
                    <h6 class="card-title mb-1 font-weight-bold">Bc. Šimon Drienik</h6>
                    <p class="title font-italic">Vývojár</p>
                    <p class="card-text">Študent FIIT STU v Bratislave. Vývojár mobilnej aplikácie Oči&nbsp;na&nbsp;ceste v rámci bakalárskeho projektu v rokoch 2020/2021.</p>
                </div>
            </div>
        </div>
    </div>


@endsection
