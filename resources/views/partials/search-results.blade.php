@php $communityUrl = auth()->check() ? '/UserCommunity' : '/communaute'; @endphp
@if(!empty($search) && $search['q'] !== '')
<section class="max-w-7xl mx-auto px-4 mb-10">
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 md:p-8">
        <div class="flex items-center justify-between gap-3 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </div>
                <div>
                    <h2 class="text-lg font-black tracking-tight text-gray-900">Résultats pour « {{ $search['q'] }} »</h2>
                    <p class="text-xs text-gray-500">{{ $search['total'] }} résultat(s) — recettes, publications et membres</p>
                </div>
            </div>
            <a href="{{ url()->current() }}" class="text-xs font-bold text-gray-400 hover:text-orange-600 flex items-center gap-1">
                <i data-lucide="x" class="w-4 h-4"></i> Effacer
            </a>
        </div>

        @if($search['total'] === 0)
            <div class="text-center py-10">
                <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="search-x" class="w-7 h-7 text-gray-300"></i>
                </div>
                <p class="text-gray-500 font-medium">Aucun résultat pour « {{ $search['q'] }} ».</p>
                <p class="text-gray-400 text-sm mt-1">Essayez un autre mot-clé (nom de plat, ingrédient, membre…).</p>
            </div>
        @endif

        {{-- Recettes --}}
        @if($search['recettes']->count())
            <div class="mb-6">
                <h3 class="flex items-center gap-2 text-sm font-bold text-gray-700 uppercase tracking-wide mb-3">
                    <i data-lucide="utensils-crossed" class="w-4 h-4 text-orange-500"></i> Recettes ({{ $search['recettes']->count() }})
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($search['recettes'] as $r)
                    <a href="?recette={{ $r->id }}" class="block rounded-2xl border border-gray-100 overflow-hidden hover:shadow-md hover:border-orange-200 transition-all">
                        <div class="h-32 bg-gray-100 overflow-hidden">
                            @if($r->image_path)
                                <img src="{{ asset('storage/' . $r->image_path) }}" alt="{{ $r->titre }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300"><i data-lucide="image" class="w-8 h-8"></i></div>
                            @endif
                        </div>
                        <div class="p-3">
                            <p class="font-bold text-gray-800 text-sm truncate">{{ $r->titre }}</p>
                            <p class="text-xs text-gray-400">par {{ $r->user->name ?? 'Chef' }}</p>
                            <div class="flex items-center gap-1 text-rose-500 text-xs mt-1">
                                <i data-lucide="heart" class="w-3 h-3 fill-current"></i> {{ $r->likes_count }}
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Publications --}}
        @if($search['posts']->count())
            <div class="mb-6">
                <h3 class="flex items-center gap-2 text-sm font-bold text-gray-700 uppercase tracking-wide mb-3">
                    <i data-lucide="message-square" class="w-4 h-4 text-orange-500"></i> Publications ({{ $search['posts']->count() }})
                </h3>
                <div class="space-y-3">
                    @foreach($search['posts'] as $p)
                    <a href="{{ $communityUrl }}" class="flex items-center gap-3 p-3 rounded-2xl border border-gray-100 hover:border-orange-200 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center font-bold shrink-0">
                            {{ strtoupper(substr($p->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-700 truncate">{{ \Illuminate\Support\Str::limit($p->content, 90) }}</p>
                            <p class="text-xs text-gray-400">par {{ $p->user->name ?? 'Membre' }} • {{ $p->likes_count }} j'aime</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Membres --}}
        @if($search['users']->count())
            <div>
                <h3 class="flex items-center gap-2 text-sm font-bold text-gray-700 uppercase tracking-wide mb-3">
                    <i data-lucide="users" class="w-4 h-4 text-orange-500"></i> Membres ({{ $search['users']->count() }})
                </h3>
                <div class="flex flex-wrap gap-3">
                    @foreach($search['users'] as $u)
                    <a href="{{ $communityUrl }}" class="flex items-center gap-3 p-3 rounded-2xl border border-gray-100 hover:border-orange-200 transition-colors">
                        <div class="w-10 h-10 rounded-full overflow-hidden bg-orange-100 text-orange-600 flex items-center justify-center font-bold shrink-0">
                            @if($u->avatar)<img src="{{ asset($u->avatar) }}" class="w-full h-full object-cover" alt="">@else{{ strtoupper(substr($u->name, 0, 1)) }}@endif
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 text-sm">{{ $u->name }}</p>
                            <p class="text-xs text-gray-400">{{ $u->specialty ?? 'Membre' }}{{ $u->city ? ' • '.$u->city : '' }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endif
