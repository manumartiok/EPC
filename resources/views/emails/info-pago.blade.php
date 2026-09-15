<h2>Nuevo comprobante de pago</h2>

<p><strong>Fecha:</strong> {{ $data['fecha'] }}</p>
<p><strong>Importe:</strong> ${{ $data['importe'] }}</p>
<p><strong>Banco:</strong> {{ $data['banco'] }}</p>
<p><strong>Sucursal:</strong> {{ $data['sucursal'] }}</p>

@if(!empty($data['facturas_canceladas']))
<p><strong>Facturas canceladas:</strong> {{ $data['facturas_canceladas'] }}</p>
@endif

@if(!empty($data['observaciones']))
<p><strong>Observaciones:</strong></p>
<p>{{ $data['observaciones'] }}</p>
@endif
