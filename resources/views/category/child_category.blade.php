<li>
    @if(count($child_category->children) > 0)
        <a href="https://www.facebook.com/">{{ $child_category->name }}<span class="fa arrow"></span></a>
    @else
        <a href="https://www.twitter.com/">{{ $child_category->name }}</a>
    @endif
    @if ($child_category->children)
        <ul class="nav">
            @forelse($child_category->children as $childCategory)
                @include('category.child_category', ['child_category' => $childCategory])
            @empty
            @endforelse
        </ul>
    @endif
</li>
