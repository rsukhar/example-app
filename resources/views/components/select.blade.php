<select name='assignee_id'>
    @foreach($users as $user)
        <option {{ $selected == $user['id'] ? 'selected' : ''}} 
        value='{{ $user['id'] }}'>{{ $user['id'] }}</option>
    @endforeach
</select>