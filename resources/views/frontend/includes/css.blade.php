<!-- CSS here -->
<link rel="stylesheet" href="{{ asset('site/assets/css/bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('site/assets/css/meanmenu.css') }}">
<link rel="stylesheet" href="{{ asset('site/assets/css/animate.css') }}">
<link rel="stylesheet" href="{{ asset('site/assets/css/swiper-bundle.css') }}">
<link rel="stylesheet" href="{{ asset('site/assets/css/slick.css') }}">
<link rel="stylesheet" href="{{ asset('site/assets/css/nouislider.css') }}">
<link rel="stylesheet" href="{{ asset('site/assets/css/backtotop.css') }}">
<link rel="stylesheet" href="{{ asset('site/assets/css/magnific-popup.css') }}">
<link rel="stylesheet" href="{{ asset('site/assets/css/nice-select.css') }}">
<link rel="stylesheet" href="{{ asset('site/assets/css/font-awesome-pro.css') }}">
<link rel="stylesheet" href="{{ asset('site/assets/css/elegant-icon.css') }}">
<link rel="stylesheet" href="{{ asset('site/assets/css/spacing.css') }}">
<link rel="stylesheet" href="{{ asset('site/assets/css/main.css') }}">
<link rel="stylesheet" href="{{ asset('site/assets/css/custom.css') }}">
<style>
  .snr-cookie{
  position:fixed; left:16px; right:16px; bottom:16px; z-index:9999;
  display:flex; justify-content:center; pointer-events:none;
}
.snr-cookie--glass .snr-cookie__content{
  pointer-events:auto; max-width:1040px; width:100%;
  background:rgba(9,14,23,.78); /* koyu cam */
  backdrop-filter:saturate(150%) blur(10px);
  border:1px solid rgba(255,255,255,.08);
  border-radius:16px; box-shadow:0 18px 50px rgba(2,6,23,.25);
  padding:16px 18px; display:flex; gap:16px; align-items:center;
  color:#fff;
}
.snr-cookie__lead{display:flex; gap:12px; align-items:center; flex:1 1 auto;}
.snr-cookie__icon{font-size:22px; line-height:1;}
.snr-cookie__heading{display:flex; flex-direction:column; gap:4px;}
.snr-cookie__heading strong{font-weight:800; font-size:15px; letter-spacing:.2px;}
.snr-cookie__heading span{opacity:.95; font-size:14px; line-height:1.55;}
.snr-cookie__link{color:#7dd3fc; text-decoration:underline;}
.snr-cookie__link:hover{color:#a5e3ff;}
.snr-cookie__actions{display:flex; gap:10px; flex:0 0 auto;}
.snr-cookie__btn{
  appearance:none; border:0; cursor:pointer; border-radius:999px; padding:10px 16px;
  font-weight:700; font-size:14px; transition:transform .06s ease, background .2s ease, box-shadow .2s ease;
}
.snr-cookie__btn:active{transform:translateY(1px);}
.snr-cookie__btn--primary{background:#111827; color:#fff; box-shadow:inset 0 0 0 1px rgba(255,255,255,.12);}
.snr-cookie__btn--primary:hover{background:#0b1220;}
.snr-cookie__btn--ghost{background:transparent; color:#e5e7eb; box-shadow:inset 0 0 0 1px rgba(255,255,255,.22);}
.snr-cookie__btn--ghost:hover{box-shadow:inset 0 0 0 1px rgba(255,255,255,.36);}
.snr-cookie.is-hiding{opacity:0; transform:translateY(8px); transition:all .2s ease;}
@media (max-width:640px){
  .snr-cookie__content{flex-direction:column; align-items:stretch; gap:12px; padding:14px;}
  .snr-cookie__actions{width:100%;}
  .snr-cookie__btn{width:100%;}
}
@media (prefers-color-scheme: light){
  .snr-cookie--glass .snr-cookie__content{
    background:rgba(255,255,255,.92); color:#0f172a; border-color:rgba(2,6,23,.08);
  }
  .snr-cookie__btn--ghost{color:#0f172a; box-shadow:inset 0 0 0 1px rgba(2,6,23,.18);}
  .snr-cookie__btn--primary{background:#0f172a;}
  .snr-cookie__link{color:#0284c7;}
}
    
</style>
