{{--android_appka--}}
<script type="text/javascript">

            var typ_stavu_problemu_nazov;
            var stav_riesenia_problemu_id;
            var kategoria_problemu_nazov;
            var typ_stavu_riesenia_problemu_nazov;
            var popis_stavu_riesenia_problemu_nazov;
            var poc = 0;

            @foreach($data['problems'] as $problem)

            @foreach ($data['typy_stavov_problemov'] as $typ_stavu_problemu)
                if ("{{$problem->stav_problemu_id}}" == "{{$typ_stavu_problemu->typ_problemu_id}}") {
                    typ_stavu_problemu_nazov = "{{$typ_stavu_problemu->nazov}}"
                }
            @endforeach

            @foreach ($data['kategorie_problemu'] as $kategoria_problemu1)
                if ("{{$problem->kategoria_problemu_id}}" == "{{$kategoria_problemu1->kategoria_problemu_id}}") {
                    kategoria_problemu_nazov = "{{$kategoria_problemu1->nazov}}"
                }
            @endforeach

            @foreach ($data['stav_riesenia_problemu'] as $stav_riesenia)

                if ("{{$stav_riesenia->problem_id}}" == "{{$problem->problem_id}}") {
                    stav_riesenia_problemu_id = "{{$stav_riesenia->typ_stavu_riesenia_problemu_id}}"
                }
            @endforeach

            @foreach ($data['typ_stavu_riesenia_problemu1'] as $typ_stavu_riesenia_problemu2)

                if ("{{$typ_stavu_riesenia_problemu2->typ_stavu_riesenia_problemu_id}}" ==stav_riesenia_problemu_id) {
                    typ_stavu_riesenia_problemu_nazov = "{{$typ_stavu_riesenia_problemu2->nazov}}"
                }
            @endforeach

            @foreach ($data['popis_stavu_riesenia_problem1'] as $popis_stavu_riesenia_problemu2)

                if ("{{$popis_stavu_riesenia_problemu2->problem_id}}" == "{{$problem->problem_id}}") {
                    popis_stavu_riesenia_problemu_nazov = "{{$popis_stavu_riesenia_problemu2->nazov}}"
                }
            @endforeach

            $arr[poc] = array(
                "position" => "{{$problem->poloha}}",
                "id" => "{{$problem->problem_id}}",
                "kategoria" => kategoria_problemu_nazov,
                "popis" => "{{$problem->popis_problemu}}",
                "stav_problemu" => typ_stavu_problemu_nazov,
                "stav_riesenia_problemu" => typ_stavu_riesenia_problemu_nazov,
                "popis_rieseneho_problemu" => popis_stavu_riesenia_problemu_nazov
            );

            poc++;

            @endforeach

            return $arr;

           /* @foreach($arr as $k =>$a)
            $arr[$k] = json_decode(json_encode($a));
            @endforeach*/

            /*return $problems;
        </script>*/
