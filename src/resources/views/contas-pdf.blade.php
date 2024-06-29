<table>
    <thead>
    <tr>
        <th>Fornecedor</th>
        <th>Valor</th>
        <th>Created At</th>
    </tr>
    </thead>
    <tbody>
    @foreach($contas as $conta)
        <tr>
            <td>{{ $conta->fornecedor }}</td>
            <td>{{ $conta->valor }}</td>
            <td>{{ $conta->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
