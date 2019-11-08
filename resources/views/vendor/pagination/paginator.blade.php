@if ($paginator->hasPages())
    <div class="w3-show-inline-block">
	<div class="w3-bar w3-border">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <button class="w3-bar-item w3-button">&laquo;</button>
        @else
			<a href="{{ $paginator->previousPageUrl() }}" class="w3-bar-item w3-button">&laquo;</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
						<a href="{{url('/')}}" class="w3-bar-item w3-button w3-theme">{{$page}}</a>
                    @else
						<a href="{{ $url }}" class="w3-bar-item w3-button">{{$page}}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
			<a href="{{ $paginator->nextPageUrl() }}" class="w3-bar-item w3-button">&raquo;</a>
        @else
            <button class="w3-bar-item w3-button">&raquo;</button>
        @endif
    </div>
	</div>
@endif
