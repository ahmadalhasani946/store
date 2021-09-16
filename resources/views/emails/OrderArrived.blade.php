@component('mail::message')

    <h1>An Order to {{ auth()->user()->name }} arrived</h1>
    <h2>The Details:</h2>
    @for($i = 0; $i < count($descriptions); $i++)
        <p>{{ $quantities[$i] }} Of {{ $descriptions[$i] }}</p>
    @endfor

@component('vendor.mail.html.button', ['url' => route('orders.show', ['order' => $id])])
    Check Order
@endcomponent
    <p>Thank You</p>
@endcomponent
