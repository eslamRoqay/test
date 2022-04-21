@if ($errors->any())
    <div class="bg-danger text-center ">
    @foreach ($errors->all() as  $value)
        <p class="p-2 text-white">{{ $value }}</p>
    @endforeach
    </div>
@endif

