{{--
    Affichage in-page d'une recette sélectionnée.

    Inclus en haut des pages d'accueil (publique et membre) lorsqu'un paramètre
    « recette » est présent. Permet de consulter une recette (image, ingrédients,
    préparation) sans quitter la page courante.

    Variables attendues :
      - $selectedRecette : instance Recette (avec ingredients, user, likes_count)
--}}
@if(!empty($selectedRecette))
    @php
        preg_match('/^\[(.+?)\]\s*(.*)$/', $selectedRecette->description, $m);
        $pays = $m[1] ?? null;
        $desc = $m[2] ?? $selectedRecette->description;
        $canLike = auth()->check() && !auth()->user()->isAdmin();
    @endphp
    <section id="recette-inline" class="max-w-7xl mx-auto px-4 mb-10 scroll-mt-28">
        <div class="bg-white rounded-[32px] border border-gray-100 shadow-lg overflow-hidden">
            {{-- Visuel --}}
            <div class="relative h-64 md:h-80 bg-gray-200">
                @if($selectedRecette->image_path)
                    <img src="{{ asset('storage/' . $selectedRecette->image_path) }}" alt="{{ $selectedRecette->titre }}" class="w-full h-full object-cover">
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

                <a href="{{ url()->current() }}" class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/90 hover:bg-white text-gray-700 flex items-center justify-center shadow-lg" title="Fermer">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </a>

                <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8 text-white">
                    @if($pays)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-orange-500 text-white text-xs font-bold uppercase tracking-widest mb-3">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> {{ $pays }}
                        </span>
                    @endif
                    <h2 class="text-3xl md:text-4xl font-black tracking-tighter">{{ $selectedRecette->titre }}</h2>
                    <div class="flex flex-wrap items-center gap-4 mt-2 text-sm text-white/90">
                        <span class="flex items-center gap-1.5"><i data-lucide="chef-hat" class="w-4 h-4"></i> {{ $selectedRecette->user->name ?? 'Chef OuraTable' }}</span>
                        <span class="flex items-center gap-1.5"><i data-lucide="heart" class="w-4 h-4 text-rose-400 fill-rose-400"></i> <span id="inline-like-count">{{ $selectedRecette->likes_count }}</span></span>
                        <span class="flex items-center gap-1.5"><i data-lucide="list" class="w-4 h-4"></i> {{ $selectedRecette->ingredients->count() }} ingrédients</span>
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-6 p-6 md:p-8">
                {{-- Ingrédients --}}
                <div class="md:col-span-1">
                    <h3 class="flex items-center gap-2 text-base font-black tracking-tight text-gray-900 mb-4">
                        <i data-lucide="shopping-basket" class="w-5 h-5 text-orange-500"></i> Ingrédients
                    </h3>
                    <ul class="space-y-3">
                        @forelse($selectedRecette->ingredients as $ing)
                            <li class="flex items-center justify-between gap-3 pb-3 border-b border-gray-50 last:border-0">
                                <span class="flex items-center gap-2 text-gray-700 text-sm">
                                    <i data-lucide="check-circle-2" class="w-4 h-4 text-orange-400 shrink-0"></i> {{ $ing->nom }}
                                </span>
                                <span class="text-xs font-bold text-orange-600 bg-orange-50 px-2 py-1 rounded-lg whitespace-nowrap">{{ $ing->quantite }}</span>
                            </li>
                        @empty
                            <li class="text-gray-400 text-sm">Aucun ingrédient renseigné.</li>
                        @endforelse
                    </ul>
                </div>

                {{-- Description + préparation --}}
                <div class="md:col-span-2 space-y-5">
                    <div>
                        <h3 class="flex items-center gap-2 text-base font-black tracking-tight text-gray-900 mb-2">
                            <i data-lucide="info" class="w-5 h-5 text-orange-500"></i> À propos
                        </h3>
                        <p class="text-gray-600 leading-relaxed">{{ $desc }}</p>
                    </div>
                    <div>
                        <h3 class="flex items-center gap-2 text-base font-black tracking-tight text-gray-900 mb-2">
                            <i data-lucide="utensils-crossed" class="w-5 h-5 text-orange-500"></i> Préparation
                        </h3>
                        <div class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $selectedRecette->instructions }}</div>
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        @if($canLike)
                            <button id="inline-like-btn" data-id="{{ $selectedRecette->id }}" data-liked="{{ $selectedRecette->is_liked_by_current ? '1' : '0' }}"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl font-bold text-sm transition-all {{ $selectedRecette->is_liked_by_current ? 'bg-rose-500 text-white' : 'bg-rose-50 text-rose-600 hover:bg-rose-100' }}">
                                <i data-lucide="heart" class="w-4 h-4 {{ $selectedRecette->is_liked_by_current ? 'fill-current' : '' }}"></i>
                                <span id="inline-like-label">{{ $selectedRecette->is_liked_by_current ? 'Aimé' : "J'aime cette recette" }}</span>
                            </button>
                        @elseif(!auth()->check())
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-orange-500 text-white font-bold text-sm hover:bg-orange-600">
                                <i data-lucide="log-in" class="w-4 h-4"></i> Connectez-vous pour aimer
                            </a>
                        @endif
                        <a href="{{ url()->current() }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-gray-100 text-gray-600 font-bold text-sm hover:bg-gray-200">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i> Revenir aux recettes
                        </a>
                    </div>
                </div>
            </div>

            {{-- Petite recommandation : autres recettes à découvrir --}}
            @if($selectedRecette->relationLoaded('autres') && $selectedRecette->autres->count())
                <div class="px-6 md:px-8 pb-8">
                    <h3 class="flex items-center gap-2 text-base font-black tracking-tight text-gray-900 mb-4">
                        <i data-lucide="sparkles" class="w-5 h-5 text-orange-500"></i> Recommandé pour vous
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($selectedRecette->autres as $r)
                            <a href="?recette={{ $r->id }}" class="block rounded-2xl border border-gray-100 overflow-hidden hover:shadow-md hover:border-orange-200 transition-all">
                                <div class="h-24 bg-gray-100 overflow-hidden">
                                    @if($r->image_path)<img src="{{ asset('storage/' . $r->image_path) }}" class="w-full h-full object-cover" alt="">@endif
                                </div>
                                <div class="p-3">
                                    <p class="font-bold text-gray-800 text-sm truncate">{{ $r->titre }}</p>
                                    <p class="text-xs text-gray-400 flex items-center gap-1">
                                        <i data-lucide="heart" class="w-3 h-3 text-rose-400 fill-rose-400"></i> {{ $r->likes_count }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <script>
    (function () {
        // Met la recette sélectionnée en évidence à l'ouverture.
        var el = document.getElementById('recette-inline');
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });

        var btn = document.getElementById('inline-like-btn');
        if (!btn) return;
        btn.addEventListener('click', function () {
            btn.disabled = true;
            fetch('{{ url('/recettes') }}/' + btn.dataset.id + '/like', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                if (d.success) {
                    document.getElementById('inline-like-count').textContent = d.likes_count;
                    document.getElementById('inline-like-label').textContent = d.liked ? 'Aimé' : "J'aime cette recette";
                    btn.className = 'inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl font-bold text-sm transition-all ' + (d.liked ? 'bg-rose-500 text-white' : 'bg-rose-50 text-rose-600 hover:bg-rose-100');
                    btn.querySelector('i').classList.toggle('fill-current', d.liked);
                }
            })
            .finally(function () { btn.disabled = false; });
        });
    })();
    </script>
@endif
