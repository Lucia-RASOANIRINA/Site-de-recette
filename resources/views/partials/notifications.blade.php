{{-- Système de notifications unifié : même style et même durée partout --}}
@php
    $notifications = [];
    $successKeys = ['success', 'admin_success', 'vote_success', 'modal_success', 'status'];
    $errorKeys   = ['error', 'admin_error', 'vote_error', 'modal_error'];
    $warningKeys = ['modal_warning', 'warning'];
    foreach ($successKeys as $k) { if (session($k)) $notifications[] = ['type' => 'success', 'msg' => session($k)]; }
    foreach ($errorKeys as $k)   { if (session($k)) $notifications[] = ['type' => 'error',   'msg' => session($k)]; }
    foreach ($warningKeys as $k) { if (session($k)) $notifications[] = ['type' => 'warning', 'msg' => session($k)]; }
@endphp

@if(count($notifications))
<div id="oura-toasts" class="fixed top-5 right-5 z-[200] flex flex-col gap-3 w-[92%] max-w-sm">
    @foreach($notifications as $n)
        @php
            // Code couleur de la marque (orange) partout, sauf les erreurs en rouge
            $conf = [
                'success' => ['icon' => 'check-circle', 'bar' => 'bg-orange-500', 'iconCls' => 'text-orange-500'],
                'error'   => ['icon' => 'alert-circle', 'bar' => 'bg-red-500',    'iconCls' => 'text-red-500'],
                'warning' => ['icon' => 'alert-triangle', 'bar' => 'bg-orange-500', 'iconCls' => 'text-orange-500'],
            ][$n['type']];
        @endphp
        <div class="oura-toast translate-x-[120%] opacity-0 transition-all duration-300 ease-out bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden flex">
            <div class="w-1.5 {{ $conf['bar'] }} shrink-0"></div>
            <div class="flex items-start gap-3 p-4 flex-1">
                <i data-lucide="{{ $conf['icon'] }}" class="w-5 h-5 {{ $conf['iconCls'] }} shrink-0 mt-0.5"></i>
                <p class="text-sm text-gray-700 flex-1 leading-snug">{{ $n['msg'] }}</p>
                <button onclick="this.closest('.oura-toast').remove()" class="text-gray-300 hover:text-gray-500 shrink-0">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    @endforeach
</div>

<script>
(function () {
    const DURATION = 4500; // même durée pour toutes les notifications
    if (window.lucide) lucide.createIcons();
    const toasts = document.querySelectorAll('#oura-toasts .oura-toast');
    toasts.forEach((t, i) => {
        setTimeout(() => { t.classList.remove('translate-x-[120%]', 'opacity-0'); }, 80 + i * 120);
        setTimeout(() => {
            t.classList.add('translate-x-[120%]', 'opacity-0');
            setTimeout(() => t.remove(), 320);
        }, DURATION + i * 120);
    });
})();
</script>
@endif
