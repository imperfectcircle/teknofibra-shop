<form action="{{ route('public.lang', $lang) }}" method="POST">
    @csrf
    <button class="bg-transparent border-none">
        <span class="fi fi-{{ $nation }}"></span>
    </button>
</form>