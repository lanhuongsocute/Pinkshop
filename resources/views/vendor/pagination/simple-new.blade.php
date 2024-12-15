
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="">
        <ul class="pagination">
            @if ($paginator->onFirstPage())
                <li class="page-item "><a class="page-link" ><<</a></li>
            @else
                <li class="page-item active"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"><<</a></li>
            @endif
            @foreach ($elements as $element)
                        {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li  class="page-item "  aria-current="page">
                                <a   class=" page-link"> {{ $page }}</a>
                            </li>
                        @else
                            <li  class="page-item active"  aria-current="page">
                                <a  href="{{ $url }}"  class=" page-link"> {{ $page }}</a>
                            </li>
                             
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item active" ><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">>></a></li>
            @else
                <li class="page-item " aria-disabled="true"><a class="page-link" >>></a></li>
            @endif
        </ul>
 
    </nav>
@endif
