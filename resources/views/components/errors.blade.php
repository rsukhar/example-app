<div>
    @if($errors->any())
        <ul>
        @foreach($errors->all() as $message)
            <li>
                {{ $message }}
            </li>
        @endforeach
        </ul>
    @endif
</div>