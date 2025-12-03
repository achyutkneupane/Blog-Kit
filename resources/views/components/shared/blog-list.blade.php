<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($blogs as $blog)
        <x-shared.blog-component :blog="$blog" />
    @endforeach
</div>
