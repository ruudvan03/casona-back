<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; border: 1px solid #eee; padding: 20px; border-radius: 10px;">
        <h2 style="color: #059669;">¡Hola, {{ $event->customer_name }}!</h2>
        <p>Gracias por interesarte en <strong>Palapa La Casona</strong>. Hemos recibido tu solicitud de reserva.</p>
        
        <div style="background: #f9fafb; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <p><strong>Detalles de tu solicitud:</strong></p>
            <ul>
                <li><strong>Folio:</strong> {{ $event->folio }}</li>
                <li><strong>Fecha:</strong> {{ $event->event_date }}</li>
                <li><strong>Evento:</strong> {{ $event->event_type }}</li>
            </ul>
        </div>

        <p>Nuestro equipo revisará la disponibilidad y te contactaremos vía WhatsApp ({{ $event->customer_phone }}) para confirmar el precio final y los detalles del anticipo.</p>
        
        <p style="font-size: 12px; color: #666;">Este es un correo automático, no es necesario responder.</p>
    </div>
</body>
</html>