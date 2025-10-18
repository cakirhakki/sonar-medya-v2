@php
  // Onaylı toplam yorum sayısı (kök + yanıtlar)
  $totalApproved = $post->allComments()->approved()->count();
@endphp

<div class="postbox__comment mb-95">
  <h3 class="postbox__comment-title">Yorumlar ({{ $totalApproved }})</h3>

  <ul>
    @forelse($post->comments as $c) {{-- kök yorumlar (parent_id null) --}}
      @include('frontend.pages.blogs.partials.comment-item', ['comment' => $c])
    @empty
      {{-- Yorum yoksa boş bırakabilir veya bir mesaj gösterebilirsin --}}
    @endforelse
  </ul>
</div>

<div class="postbox__comment-form">
  <h3 class="postbox__comment-form-title">Bir Yorum Bırakın</h3>
  <p>E-posta adresiniz yayınlanmayacaktır. Zorunlu alanlar * ile işaretlenmiştir.</p>

  {{-- Flash & Hata --}}
  @if (session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif
  @if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <form action="{{ route('posts.comments.store', $post) }}" method="post">
    @csrf

    {{-- Honeypot (botları elemek için) --}}
    <input type="text" name="website" style="position:absolute;left:-10000px;top:auto;width:1px;height:1px;opacity:0;" tabindex="-1" autocomplete="off">

    {{-- Yanıt için parent_id (JS ile doldurulacak) --}}
    <input type="hidden" name="parent_id" id="parent_id" value="{{ old('parent_id') }}">

    <div class="row">
      @guest
        <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6">
          <div class="postbox__comment-input">
            <input type="text" name="author_name" value="{{ old('author_name') }}" placeholder="Adınız*">
          </div>
        </div>
        <div class="col-xxl-4 col-xl-4 col-lg-6 col-md-6">
          <div class="postbox__comment-input">
            <input type="email" name="author_email" value="{{ old('author_email') }}" placeholder="E-posta*">
          </div>
        </div>
        <div class="col-xxl-4 col-xl-4 col-lg-12">
          <div class="postbox__comment-input">
            <input type="text" name="url_dummy" placeholder="Website"> {{-- görsel/dummy alan (zorunlu değil) --}}
          </div>
        </div>
      @endguest

      <div class="col-xxl-12">
        <div class="postbox__comment-input">
          <textarea name="content" placeholder="Yorumunuzu Buraya Yazın..." rows="4">{{ old('content') }}</textarea>
        </div>
      </div>

      <div class="col-xxl-12">
        <div class="postbox__comment-agree d-flex align-items-start mb-25">
          <input class="e-check-input" type="checkbox" id="e-agree">
          <label class="e-check-label" for="e-agree">
           Adımı, e-posta adresimi ve web sitemi bir sonraki yorumumda kullanılması için bu tarayıcıya kaydet.  
          </label>
        </div>
      </div>

      <div class="col-xxl-12">
        <div class="postbox__comment-btn">
          <button type="submit" class="tp-btn">Yorumu Gönder</button>
        </div>
      </div>
    </div>
  </form>
</div>

{{-- Basit "Reply" davranışı: tıklanınca parent_id set edilir --}}
<script>
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('.postbox__comment-reply a[data-reply]');
    if (!btn) return;
    e.preventDefault();
    const id = btn.getAttribute('data-reply');
    const input = document.getElementById('parent_id');
    if (input) input.value = id;
    // form alanına odaklan
    const ta = document.querySelector('.postbox__comment-form textarea[name="content"]');
    if (ta) ta.focus();
    // sayfayı ilgili yere taşıyabilirsin (opsiyonel)
    // location.hash = 'reply-'+id;
  }, false);
</script>
