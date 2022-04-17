<table class="rwd-table">
    <thead>
    <tr>
        <th>#</th>
        <th>Adresa</th>
        <th>Vytvorené dňa</th>
        <th>Kategória problému</th>
        <th>Stav problému</th>
        <th>Stav riešenia</th>
    </tr>
    </thead>
    <tbody>
    @php
        $counter = 1;
    @endphp
    @foreach($problems as $problem)
        <tr class="problem-row" onclick="window.location='{{ route('problem.show', $problem->problem_id) }}'">
            <input type="hidden" value="{{ $problem->problem_id }}"/>
            <td data-th="#" id="hashtagID">{{ $counter }}</td>
            <td data-th="Adresa">{{ $problem->address }}</td>
            <td data-th="Vytvorené dňa">{{ $problem->created_at }}</td>
            <td data-th="Kategória problému">{{ $problem->KategoriaProblemu['nazov'] }}</td>
            <td data-th="Stav problému">{{ $problem->StavProblemu->nazov }}</td>
            @foreach($typy_stavov_riesenia as $typ)
                @if($stavy_riesenia[$counter-1] == $typ->typ_stavu_riesenia_problemu_id)
                    <td data-th="Stav riešenia">{{ $typ->nazov }}</td>
                @endif
            @endforeach
        </tr>
        @php
            $counter++;
        @endphp
    @endforeach
    </tbody>
</table>

<div class="d-flex justify-content-end mr-4 my-3">
    @if ($problems->hasPages())
        <ul class="pagination">
            {{--             Previous Page Link--}}
            @if ($problems->onFirstPage())
                <li class="d-none"><span>Previous</span></li>
            @else
                <li><a class="prev-ref" href="{{ $problems->previousPageUrl() }}" rel="prev"><i class="fas fa-chevron-left"></i></a></li>
            @endif

            @if($problems->currentPage() > 3)
                <li class="hidden"><a href="{{ $problems->url(1) }}">1</a></li>
            @endif
            @if($problems->currentPage() > 4)
                <li><span>...</span></li>
            @endif
            @foreach(range(1, $problems->lastPage()) as $i)
                @if($i >= $problems->currentPage() - 1 && $i <= $problems->currentPage() + 1)
                    @if ($i == $problems->currentPage())
                        <li><span>{{ $i }}</span></li>
                    @else
                        <li><a href="{{ $problems->url($i) }}">{{ $i }}</a></li>
                    @endif
                @endif
            @endforeach
            @if($problems->currentPage() < $problems->lastPage() - 2)
                <li><span>...</span></li>
            @endif
            @if($problems->currentPage() < $problems->lastPage() - 1)
                <li class="hidden"><a href="{{ $problems->url($problems->lastPage()) }}">{{ $problems->lastPage() }}</a></li>
            @endif

            {{--             Next Page Link--}}
            @if ($problems->hasMorePages())
                <li><a href="{{ $problems->nextPageUrl() }}" rel="next"><i class="fas fa-chevron-right"></i></a></li>
            @else
                <li class="d-none"><span><i class="fas fa-chevron-right"></i></span></li>
            @endif
        </ul>
    @endif
</div>

<script>

</script>
