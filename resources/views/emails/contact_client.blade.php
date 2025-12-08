<!DOCTYPE html>
<html>
<head>
    <title>{{ $detalii['subiect'] }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    
    <div style="background-color: #f8f9fa; padding: 20px;">
        <h2 style="color: #b91c1c;">CRM Ingineria Programelor Mesaj Nou</h2>
    </div>

    <div style="padding: 20px;">
        <p>Salut <strong>{{ $detalii['nume_client'] }}</strong>,</p>
        
        <p>Ai primit un mesaj nou de la consilierul tau, <strong>{{ $detalii['nume_consilier'] }}</strong>:</p>
        
        <div style="background-color: #fce7f3; border-left: 4px solid #b91c1c; padding: 15px; margin: 20px 0; font-style: italic;">
            {!! nl2br(e($detalii['mesaj'])) !!}
        </div>

        <p>Pentru orice intrebari, poti raspunde direct la acest email.</p>
        
        <br>
        <p>Cu respect,<br>
        CRM Ingineria Programelor</p>
    </div>

</body>
</html>