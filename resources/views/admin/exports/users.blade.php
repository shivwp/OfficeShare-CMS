<table>
    <thead>
    <tr >
        <th>User Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Signup Date</th>
    </tr>
    </thead>
    <tbody>
    @isset($users)
    @foreach($users as $item)
            <tr>
            <td>{{ $item->name }}</td>
            <td>{{ $item->email }}</td>
            <td>{{ $item->phone }}</td>
            <td>{{ \Carbon\Carbon::parse($item['created_at'])->format('d-M-Y') }}</td>
          </tr>
    @endforeach
    @endisset
    </tbody>
</table>