<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectLine }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f5;font-family:Arial,Helvetica,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f5;padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,.06);">
                    <tr>
                        <td style="background:linear-gradient(135deg,#f97316,#ea580c);padding:28px 32px;color:#fff;">
                            <h1 style="margin:0;font-size:22px;">OURATABLE</h1>
                            <p style="margin:4px 0 0;font-size:13px;opacity:.9;">Message de l'administration</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            @if($recipientName)
                                <p style="margin:0 0 16px;font-size:15px;color:#111;">Bonjour <strong>{{ $recipientName }}</strong>,</p>
                            @endif
                            <h2 style="margin:0 0 12px;font-size:18px;color:#ea580c;">{{ $subjectLine }}</h2>
                            <div style="font-size:15px;line-height:1.6;color:#333;white-space:pre-line;">{{ $bodyMessage }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:20px 32px;border-top:1px solid #eee;color:#888;font-size:12px;">
                            <p style="margin:0;">Cet email vous est envoye par l'equipe OURATABLE. Merci de ne pas y repondre directement.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
