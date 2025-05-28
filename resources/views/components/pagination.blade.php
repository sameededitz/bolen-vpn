@php
    if (!isset($scrollTo)) {
        $scrollTo = 'body';
    }

    $scrollIntoViewJsSnippet = ($scrollTo !== false)
        ? <<<JS
           (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
        JS
        : '';
@endphp
<div>
    @if ($paginator->hasPages())
        <nav class="d-flex justify-content-between align-items-center">
            <div class="d-flex justify-content-between flex-fill d-sm-none">
                <ul class="pagination d-flex align-items-center gap-2 justify-content-between mt-24 w-100">
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled">
                            <a class="page-link bg-primary-50 text-secondary-light fw-medium rounded-circle border-0 px-20 py-10 d-flex align-items-center justify-content-center h-48-px w-48-px"
                                href="javascript:void(0)">
                                <iconify-icon icon="ep:d-arrow-left"class="text-xl"></iconify-icon>
                            </a>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link bg-primary-50 text-secondary-light fw-medium rounded-circle border-0 px-20 py-10 d-flex align-items-center justify-content-center h-48-px w-48-px"
                                href="javascript:void(0)"
                                dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}"
                                wire:click="previousPage('{{ $paginator->getPageName() }}')"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}">
                                <iconify-icon icon="ep:d-arrow-left" class="text-xl"></iconify-icon>
                            </a>
                        </li>
                    @endif

                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link bg-primary-50 text-secondary-light fw-medium rounded-circle border-0 px-20 py-10 d-flex align-items-center justify-content-center h-48-px w-48-px"
                                href="javascript:void(0)" wire:click="nextPage('{{ $paginator->getPageName() }}')"
                                dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}">
                                <iconify-icon icon="ep:d-arrow-right"class="text-xl"></iconify-icon>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <a class="page-link bg-primary-50 text-secondary-light fw-medium rounded-circle border-0 px-20 py-10 d-flex align-items-center justify-content-center h-48-px w-48-px"
                                href="javascript:void(0)"><iconify-icon icon="ep:d-arrow-right"
                                    class="text-xl"></iconify-icon></a>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="d-none d-sm-flex flex-fill align-items-center justify-content-between">
                <p class="small mb-0">
                    Showing
                    <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                    to
                    <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                    of
                    <span class="fw-semibold">{{ $paginator->total() }}</span>
                    results
                </p>

                <ul class="pagination d-flex flex-wrap align-items-center gap-2 justify-content-center">

                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled">
                            <a class="page-link bg-primary-50 text-secondary-light fw-medium rounded-circle border-0 px-20 py-10 d-flex align-items-center justify-content-center h-48-px w-48-px"
                                href="javascript:void(0)">
                                <iconify-icon icon="ep:d-arrow-left"class="text-xl"></iconify-icon>
                            </a>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link bg-primary-50 text-secondary-light fw-medium rounded-circle border-0 px-20 py-10 d-flex align-items-center justify-content-center h-48-px w-48-px"
                                href="javascript:void(0)"
                                dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}"
                                wire:click="previousPage('{{ $paginator->getPageName() }}')"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}">
                                <iconify-icon icon="ep:d-arrow-left" class="text-xl"></iconify-icon>
                            </a>
                        </li>
                    @endif

                    {{-- Page Number Links --}}
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <li class="page-item disabled">
                                <a class="page-link bg-primary-50 text-secondary-light fw-medium rounded-circle border-0 px-20 py-10 d-flex align-items-center justify-content-center h-48-px w-48-px"
                                    href="javascript:void(0);">{{ $element }}</a>
                            </li>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li>
                                        <a href="javascript:void(0);"
                                            class="page-link bg-primary-50 text-secondary-light fw-medium rounded-circle border-0 px-20 py-10 d-flex align-items-center justify-content-center h-48-px w-48-px bg-primary-600 text-white"
                                            wire:key="paginator-{{ $paginator->getPageName() }}-page-{{ $page }}">{{ $page }}</a>
                                    </li>
                                @else
                                    <li>
                                        <a class="page-link bg-primary-50 text-secondary-light fw-medium rounded-circle border-0 px-20 py-10 d-flex align-items-center justify-content-center h-48-px w-48-px"
                                            href="javascript:void(0);"
                                            wire:key="paginator-{{ $paginator->getPageName() }}-page-{{ $page }}"
                                            wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                            x-on:click="{{ $scrollIntoViewJsSnippet }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link bg-primary-50 text-secondary-light fw-medium rounded-circle border-0 px-20 py-10 d-flex align-items-center justify-content-center h-48-px w-48-px"
                                href="javascript:void(0)"
                                dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}"
                                wire:click="nextPage('{{ $paginator->getPageName() }}')"
                                x-on:click="{{ $scrollIntoViewJsSnippet }}">
                                <iconify-icon icon="ep:d-arrow-right"class="text-xl"></iconify-icon>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <a class="page-link bg-primary-50 text-secondary-light fw-medium rounded-circle border-0 px-20 py-10 d-flex align-items-center justify-content-center h-48-px w-48-px"
                                href="javascript:void(0)"><iconify-icon icon="ep:d-arrow-right"
                                    class="text-xl"></iconify-icon></a>
                        </li>
                    @endif

                </ul>
            </div>
        </nav>
    @endif
</div>