<h1>Olá, {{ $conta->user->name }}!</h1>
<p>Esta é uma notificação de que o pagamento da conta <strong>{{ $conta->fornecedor }}</strong> está agendado para hoje.</p>
<p><strong>Valor:</strong> R$ {{ $conta->valor }}</p>
<p><strong>Descrição:</strong> {{ $conta->descricao ?? '' }}</p>
