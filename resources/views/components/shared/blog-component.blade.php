<article class="p-6 bg-white rounded-lg border border-neutral-200 shadow-md">
    <div class="flex justify-between items-center mb-5 text-neutral-500">
        <div>
            @foreach($blog->categories as $category)
                <a href="#"
                   class="bg-primary-100 text-primary-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded mr-2">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
        <span class="text-sm">
            {{ $blog->published_at->diffForHumans() }}
        </span>
    </div>
    <h2 class="mb-2 text-2xl font-bold tracking-tight text-primary">
        <a href="{{ route('blog.view', $blog) }}">
            {{ $blog->title }}
        </a>
    </h2>
    <p class="mb-5 font-light text-neutral-500">
        {{ $blog->description }}
    </p>
    <div class="flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <span class="font-medium text-neutral-800">
              {{ $blog->author->name }}
          </span>
        </div>
        <a href="{{ route('blog.view', $blog) }}" class="inline-flex items-center font-medium text-primary-600 hover:underline">
            Read more
            <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </a>
    </div>
</article>
