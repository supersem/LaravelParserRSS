<h1>RSS Feed</h1>
<ul>
    @foreach ($items as $item)
        <li>
            <h2>{{ $item->get_title() }}</h2>
            <p>{{ $item->get_description() }}</p>
            <a href="{{ $item->get_permalink() }}" target="_blank">Read more</a>
        </li>
    @endforeach
</ul>
