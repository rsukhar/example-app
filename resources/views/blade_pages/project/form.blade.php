@csrf
<input type="text" name="title">
<select name="assignee_id">
    @foreach($users as $user)
        <option value="{{ $user['id'] }}">{{ $user['id'] }}</option>
    @endforeach
</select>
<input type="date" name="deadline_date">
