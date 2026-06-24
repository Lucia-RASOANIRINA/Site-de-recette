<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { color: #1f2937; font-size: 11px; }
        .header { border-bottom: 3px solid #f97316; padding-bottom: 12px; margin-bottom: 18px; }
        .brand { font-size: 22px; font-weight: bold; color: #ea580c; }
        .brand span { color: #1f2937; }
        .sub { font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: 2px; }
        .meta { font-size: 10px; color: #6b7280; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th { background: #f97316; color: #fff; text-align: left; padding: 7px 8px; font-size: 10px; text-transform: uppercase; }
        td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        tr:nth-child(even) td { background: #fff7ed; }
        .role-admin { color: #ea580c; font-weight: bold; }
        .footer { margin-top: 20px; font-size: 9px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">Oura<span>Table</span></div>
        <div class="sub">Liste des utilisateurs</div>
        <div class="meta">Généré le {{ now()->format('d/m/Y à H:i') }} — {{ $users->count() }} utilisateur(s)</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Rôle</th>
                <th>Ville</th>
                <th>Spécialité</th>
                <th>Vérifié</th>
                <th>Inscrit le</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $u)
            <tr>
                <td>{{ $u->id }}</td>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->phone ?: '—' }}</td>
                <td class="{{ $u->role === 'admin' ? 'role-admin' : '' }}">{{ $u->role }}</td>
                <td>{{ $u->city ?: '—' }}</td>
                <td>{{ $u->specialty ?: '—' }}</td>
                <td>{{ $u->email_verified_at ? 'Oui' : 'Non' }}</td>
                <td>{{ optional($u->created_at)->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">OURATABLE — Document confidentiel réservé à l'administration.</div>
</body>
</html>
