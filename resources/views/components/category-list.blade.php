@props(['categoryList'])

<div {{ $attributes->merge(['class' => 'category-list flex flex-col md:flex-row text-white bg-slate-700']) }}>
    @if (!empty($categoryList))
        @foreach($categoryList as $category)
            <div class="category-item relative">
                <a href="{{ route('byCategory', $category) }}" class="cursor-pointer py-3 px-6 hover:bg-black/10 block">
                    {{$category->name}}
                </a>
                <x-category-list class="absolute left-0 top-[100%] z-50 hidden flex-col" :category-list="$category->children"/>
            </div>
        @endforeach
    @endif
</div>