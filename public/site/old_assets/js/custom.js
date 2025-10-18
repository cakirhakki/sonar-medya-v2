// WOW.js güvenli resetAnimation patch'i
(function () {
  if (!window.WOW) return;

  var Proto = window.WOW.prototype;
  var _reset = Proto.resetAnimation;

  // className'i güvenli okuyup-yazan yardımcılar
  function getClassName(el){
    if (typeof el.className === 'string') return el.className;
    if (el.className && typeof el.className.baseVal === 'string') return el.className.baseVal; // SVG
    return ''; // bilinmeyen durum
  }
  function setClassName(el, v){
    if (typeof el.className === 'string') { el.className = v; return; }
    if (el.className && typeof el.className.baseVal === 'string') { el.className.baseVal = v; }
  }

  // resetAnimation'ı override et
  Proto.resetAnimation = function (evt) {
    var el = (evt && evt.target) ? evt.target : this.box;
    var cls = getClassName(el);
    if (!cls || typeof cls.replace !== 'function') return el; // guard: string değilse sus

    var cleaned = cls.replace(this.config.animateClass, '').trim();
    setClassName(el, cleaned);
    return el;
  };
})();

document.addEventListener('DOMContentLoaded', function () {
  // (Senin SVG fix bloğun burada kalabilir)

  // WOW'u tek kez başlat
  if (window.WOW) new WOW().init();

  // -----------------------------
  // Services detail: tab toggle
  // -----------------------------
  (function initServiceTabs(){
    const nav = document.querySelector('.services__widget-tab-2');
    if (!nav) return;

    const links  = Array.from(nav.querySelectorAll('a[href^="#tab-"]'));
    const panels = Array.from(document.querySelectorAll('.services__tab-panel'));
    if (!links.length || !panels.length) return;

    function setA11y() {
      // basit erişilebilirlik öznitelikleri
      links.forEach(a => a.setAttribute('aria-selected', a.classList.contains('active') ? 'true' : 'false'));
      panels.forEach(p => p.setAttribute('aria-hidden', p.classList.contains('is-active') ? 'false' : 'true'));
    }

    function activate(id) {
      links.forEach(a => a.classList.remove('active'));
      panels.forEach(p => p.classList.remove('is-active'));

      const link  = nav.querySelector('[href="#' + id + '"]');
      const panel = document.getElementById(id);

      if (link)  link.classList.add('active');
      if (panel) panel.classList.add('is-active');

      // URL hash'i güncelle (back/forward kirletmeden)
      if (window.history && history.replaceState) {
        history.replaceState(null, '', '#' + id);
      }

      setA11y();
    }

    // Tıklama ile geçiş
    links.forEach(a => {
      a.addEventListener('click', function(e){
        const href = this.getAttribute('href') || '';
        if (!href.startsWith('#tab-')) return;
        e.preventDefault();
        activate(href.substring(1));
      });
    });

    // İlk yükleme: hash varsa onu, yoksa ilk linkin panelini aç
    let initialId = (location.hash && location.hash.startsWith('#tab-'))
      ? location.hash.substring(1)
      : null;

    if (!initialId) {
      const firstHref = links[0] && links[0].getAttribute('href');
      if (firstHref && firstHref.startsWith('#tab-')) {
        initialId = firstHref.substring(1);
      }
    }

    if (initialId) activate(initialId);
    else {
      // güvenli durum: yine de a11y set et
      setA11y();
    }

    // (Opsiyonel) hash değişince tabı güncelle
    window.addEventListener('hashchange', function(){
      if (location.hash && location.hash.startsWith('#tab-')) {
        activate(location.hash.substring(1));
      }
    });
  })();
});
