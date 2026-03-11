<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; color: #334155; }
        .card { border: 1px solid #e2e8f0; padding: 20px; border-radius: 15px; max-width: 500px; }
        .header { color: #059669; font-weight: bold; font-size: 20px; }
        .detail { margin-top: 10px; font-size: 14px; }
        .label { font-weight: bold; color: #64748b; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">¡Nueva solicitud de reserva!</div>
        <p>Has recibido una nueva solicitud desde la Landing Page:</p>
        
        <div class="detail"><span class="label">Cliente:</span> {{ $reservation->customer_name }}</div>
        <div class="detail"><span class="label">WhatsApp:</span> {{ $reservation->customer_phone }}</div>
        <div class="detail"><span class="label">Fecha:</span> {{ $reservation->event_date }}</div>
        <div class="detail"><span class="label">Tipo:</span> {{ $reservation->event_type }}</div>
        <hr>
        <p>Ingresa al panel administrativo para confirmar y asignar precio.</p>
    </div>
</body>
</html>