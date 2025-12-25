@props(['items' => []])

@if(!empty($items))
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb" style="background: transparent; padding: 0;">
            @foreach($items as $index => $item)
                @if($index === count($items) - 1)
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $item['label'] }}
                    </li>
                @else
                    <li class="breadcrumb-item">
                        @if(isset($item['url']))
                            <a href="{{ $item['url'] }}" class="text-decoration-none" style="color: var(--ceeac-secondary);">
                                {{ $item['label'] }}
                            </a>
                        @else
                            {{ $item['label'] }}
                        @endif
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
