<table>
    <thead>
    <tr>
        <th>Recebimento</th>
        <th>Pagamento</th>
        <th>Created At</th>
    </tr>
    </thead>
    <tbody>
    @foreach($lancamentos as $lancamento)
        <tr>
            <td>{{ $lancamento->recebimento }}</td>
            <td>{{ $lancamento->pagamento }}</td>
            <td>{{ $lancamento->created_at }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
