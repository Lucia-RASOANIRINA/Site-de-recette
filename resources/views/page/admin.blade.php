@extends('layouts.AdminHeader')

@section('title', 'Tableau de bord')

@section('content')
    @include('partials.search-results')

    {{-- Erreurs de validation (les autres notifications passent par le toast unifié) --}}
    @if($errors->any())
        <div class="mb-4 p-4 rounded-2xl bg-red-50 border border-red-200 text-red-700">
            <ul class="list-disc pl-5 text-sm">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="mb-6 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-gray-900">Tableau de bord</h1>
            <p class="text-sm text-gray-500">Gestion complète de la plateforme OURATABLE.</p>
        </div>
        <div class="flex items-center gap-2">
            <div class="relative">
                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input id="admin-search" type="text" placeholder="Rechercher dans la table…"
                    class="pl-9 pr-3 py-2 rounded-xl border border-gray-200 text-sm focus:ring-2 focus:ring-orange-500 outline-none w-full md:w-64">
            </div>
            <a href="{{ route('admin.users.export') }}" class="px-3 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold flex items-center gap-1.5 whitespace-nowrap">
                <i data-lucide="file-text" class="w-4 h-4"></i> Export PDF
            </a>
        </div>
    </div>

    {{-- Cartes statistiques --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        @php
            $cards = [
                ['Utilisateurs', $stats['users'], 'users', 'from-blue-500 to-blue-600'],
                ['Recettes', $stats['recettes'], 'utensils-crossed', 'from-orange-500 to-orange-600'],
                ['Publications', $stats['posts'], 'message-square', 'from-purple-500 to-purple-600'],
                ['Commentaires', $stats['comments'], 'message-circle', 'from-emerald-500 to-emerald-600'],
                ['Coups de cœur', $stats['likes'], 'heart', 'from-rose-500 to-rose-600'],
            ];
        @endphp
        @foreach($cards as $c)
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $c[3] }} text-white flex items-center justify-center mb-3 shadow-sm">
                    <i data-lucide="{{ $c[2] }}" class="w-5 h-5"></i>
                </div>
                <p class="text-2xl font-black">{{ $c[1] }}</p>
                <p class="text-xs text-gray-500">{{ $c[0] }}</p>
            </div>
        @endforeach
    </div>

    {{-- Onglets --}}
    <div class="flex flex-wrap gap-2 mb-4" id="tabs">
        <button data-tab="users" class="tab-btn active px-4 py-2 rounded-xl bg-white shadow-sm text-sm font-semibold border border-gray-100">Utilisateurs</button>
        <button data-tab="recettes" class="tab-btn px-4 py-2 rounded-xl bg-white shadow-sm text-sm font-semibold border border-gray-100">Recettes</button>
        <button data-tab="posts" class="tab-btn px-4 py-2 rounded-xl bg-white shadow-sm text-sm font-semibold border border-gray-100">Publications</button>
        <button data-tab="comments" class="tab-btn px-4 py-2 rounded-xl bg-white shadow-sm text-sm font-semibold border border-gray-100">Commentaires</button>
        <button data-tab="comm" class="tab-btn px-4 py-2 rounded-xl bg-white shadow-sm text-sm font-semibold border border-gray-100">Communication</button>
    </div>

    {{-- ===================== UTILISATEURS ===================== --}}
    <section data-panel="users" class="panel bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-left">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Nom</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Rôle</th>
                        <th class="px-4 py-3">Vérifié</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($users as $u)
                    <tr class="hover:bg-orange-50/30">
                        <td class="px-4 py-3 text-gray-400">{{ $u->id }}</td>
                        <td class="px-4 py-3 font-medium">{{ $u->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $u->email }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 rounded-full text-xs {{ $u->isAdmin() ? 'bg-orange-100 text-orange-700' : 'bg-gray-100 text-gray-600' }}">{{ $u->role }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if($u->email_verified_at)<span class="text-green-600">✓</span>@else<span class="text-gray-400">—</span>@endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                @if($u->id !== auth()->id())
                                <form action="{{ route('admin.users.toggle-role', $u->id) }}" method="POST">@csrf
                                    <button class="px-2 py-1 rounded-lg text-xs bg-blue-50 text-blue-600 hover:bg-blue-100">{{ $u->isAdmin() ? 'Retirer admin' : 'Promouvoir admin' }}</button>
                                </form>
                                @endif
                                @if(!$u->isAdmin())
                                <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Supprimer cet utilisateur et toutes ses données ?')">
                                    @csrf @method('DELETE')
                                    <button class="px-2 py-1 rounded-lg text-xs bg-red-50 text-red-600 hover:bg-red-100">Supprimer</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    {{-- ===================== RECETTES ===================== --}}
    <section data-panel="recettes" class="panel hidden bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-left">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Titre</th>
                        <th class="px-4 py-3">Auteur</th>
                        <th class="px-4 py-3">Coups de cœur</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recettes as $r)
                    <tr class="hover:bg-orange-50/30">
                        <td class="px-4 py-3 text-gray-400">{{ $r->id }}</td>
                        <td class="px-4 py-3 font-medium">{{ $r->titre }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $r->user->name ?? '—' }}</td>
                        <td class="px-4 py-3">{{ $r->likes_count }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $r->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <form action="{{ route('admin.recettes.destroy', $r->id) }}" method="POST" onsubmit="return confirm('Supprimer cette recette ?')">
                                @csrf @method('DELETE')
                                <button class="px-2 py-1 rounded-lg text-xs bg-red-50 text-red-600 hover:bg-red-100">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">Aucune recette.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- ===================== PUBLICATIONS ===================== --}}
    <section data-panel="posts" class="panel hidden bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-left">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Auteur</th>
                        <th class="px-4 py-3">Contenu</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Comm.</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($posts as $p)
                    <tr class="hover:bg-orange-50/30">
                        <td class="px-4 py-3 text-gray-400">{{ $p->id }}</td>
                        <td class="px-4 py-3 font-medium">{{ $p->user->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600 max-w-xs truncate">{{ \Illuminate\Support\Str::limit($p->content, 80) }}</td>
                        <td class="px-4 py-3"><span class="px-2 py-1 rounded-full text-xs bg-gray-100">{{ $p->type }}</span></td>
                        <td class="px-4 py-3">{{ $p->comments_count }}</td>
                        <td class="px-4 py-3 text-right">
                            <form action="{{ route('admin.posts.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Supprimer cette publication ?')">
                                @csrf @method('DELETE')
                                <button class="px-2 py-1 rounded-lg text-xs bg-red-50 text-red-600 hover:bg-red-100">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">Aucune publication.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- ===================== COMMENTAIRES ===================== --}}
    <section data-panel="comments" class="panel hidden bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-left">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Auteur</th>
                        <th class="px-4 py-3">Commentaire</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($comments as $c)
                    <tr class="hover:bg-orange-50/30">
                        <td class="px-4 py-3 text-gray-400">{{ $c->id }}</td>
                        <td class="px-4 py-3 font-medium">{{ $c->user->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-gray-600 max-w-md truncate">{{ \Illuminate\Support\Str::limit($c->content, 100) }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $c->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <form action="{{ route('admin.comments.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Supprimer ce commentaire ?')">
                                @csrf @method('DELETE')
                                <button class="px-2 py-1 rounded-lg text-xs bg-red-50 text-red-600 hover:bg-red-100">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">Aucun commentaire.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- ===================== COMMUNICATION ===================== --}}
    <section data-panel="comm" class="panel hidden grid md:grid-cols-3 gap-4">
        {{-- Email direct --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-9 h-9 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center"><i data-lucide="mail" class="w-5 h-5"></i></div>
                <h3 class="font-semibold">Email direct</h3>
            </div>
            <p class="text-xs text-gray-500 mb-3">Envoyé via {{ config('mail.from.address') }}.</p>
            <form action="{{ route('admin.send-email') }}" method="POST" class="space-y-3">@csrf
                <select name="target" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm" required>
                    <option value="all">Tous les utilisateurs</option>
                    @foreach($users as $u)@if(!$u->isAdmin())<option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>@endif @endforeach
                </select>
                <input name="subject" placeholder="Objet" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm" required>
                <textarea name="message" rows="5" placeholder="Votre message..." class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm" required></textarea>
                <button class="w-full py-2 rounded-lg bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold">Envoyer l'email</button>
            </form>
        </div>

        {{-- Message privé --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-9 h-9 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center"><i data-lucide="send" class="w-5 h-5"></i></div>
                <h3 class="font-semibold">Message privé</h3>
            </div>
            <p class="text-xs text-gray-500 mb-3">Apparaît dans la messagerie communauté.</p>
            <form action="{{ route('admin.send-message') }}" method="POST" class="space-y-3">@csrf
                <select name="user_id" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm" required>
                    <option value="">— Choisir un utilisateur —</option>
                    @foreach($users as $u)@if($u->id !== auth()->id())<option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>@endif @endforeach
                </select>
                <textarea name="content" rows="5" placeholder="Votre message privé..." class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm" required></textarea>
                <button class="w-full py-2 rounded-lg bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold">Envoyer le message</button>
            </form>
        </div>

        {{-- Annonce communauté --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-9 h-9 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center"><i data-lucide="megaphone" class="w-5 h-5"></i></div>
                <h3 class="font-semibold">Annonce communauté</h3>
            </div>
            <p class="text-xs text-gray-500 mb-3">Publiée pour toute la communauté.</p>
            <form action="{{ route('admin.broadcast') }}" method="POST" enctype="multipart/form-data" class="space-y-3">@csrf
                <textarea name="content" rows="6" placeholder="Votre annonce..." class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm" required></textarea>
                <input type="file" name="image" accept="image/*" class="w-full text-xs">
                <button class="w-full py-2 rounded-lg bg-purple-500 hover:bg-purple-600 text-white text-sm font-semibold">Publier l'annonce</button>
            </form>
        </div>
    </section>
@endsection

@section('scripts')
<style>.tab-btn.active{background:#ea580c;color:#fff;border-color:#ea580c}</style>
<script>
    const tabs = document.querySelectorAll('.tab-btn');
    const panels = document.querySelectorAll('.panel');
    tabs.forEach(btn => btn.addEventListener('click', () => {
        tabs.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const t = btn.dataset.tab;
        panels.forEach(p => p.classList.toggle('hidden', p.dataset.panel !== t));
    }));

    // Recherche : filtre les lignes du tableau actuellement visible
    const search = document.getElementById('admin-search');
    if (search) {
        search.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            document.querySelectorAll('.panel:not(.hidden) tbody tr').forEach(tr => {
                tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    }
</script>
@endsection
