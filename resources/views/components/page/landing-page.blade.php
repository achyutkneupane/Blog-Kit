<x-layouts.app>
    <x-section.hero-section />
    <x-shared.blog-list title="Featured Blogs" :blogs="$featured" />
    <x-shared.blog-list title="Latest Blogs" :blogs="$latest" />
</x-layouts.app>
