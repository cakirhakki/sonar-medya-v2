@php($hasConsent = request()->cookies->has('cookie_consent'))
@if(!$hasConsent)
<div id="cookie-consent" class="snr-cookie snr-cookie--glass" role="dialog" aria-live="polite">
  <div class="snr-cookie__content">
    <div class="snr-cookie__lead">
      <span class="snr-cookie__icon" aria-hidden="true">🍪</span>
      <div class="snr-cookie__heading">
        <strong>Çerezleri Kullanıyoruz</strong>
        <span>Bu sitede deneyimini iyileştirmek için çerezler kullanıyoruz.
          <a class="snr-cookie__link" href="{{ route('legal.cookies') }}">Çerez Politikası</a>
        </span>
      </div>
    </div>

    <div class="snr-cookie__actions">
      <button type="button" class="snr-cookie__btn snr-cookie__btn--ghost" data-action="decline">Reddet</button>
      <button type="button" class="snr-cookie__btn snr-cookie__btn--primary" data-action="accept">Kabul Et</button>
    </div>
  </div>
</div>

<script>
(function(){
  const el = document.getElementById('cookie-consent');
  el?.addEventListener('click', async (e)=>{
    const btn = e.target.closest('[data-action]');
    if(!btn) return;
    try {
      await fetch('{{ route('cookie-consent.store') }}', {
        method:'POST',
        headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
        body:new URLSearchParams({choice:btn.dataset.action})
      });
    } catch(_){}
    el.classList.add('is-hiding'); setTimeout(()=>el.remove(),220);
  });
})();
</script>
@endif
