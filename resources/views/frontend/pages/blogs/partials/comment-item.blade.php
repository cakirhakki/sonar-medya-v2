@php
  /** @var \App\Models\Comment $comment */

  // İsim / e-posta tespiti
  $author = $comment->customer->name
         ?? $comment->user->name
         ?? $comment->author_name
         ?? 'Misafir';

  $email  = $comment->customer->email
         ?? $comment->user->email
         ?? $comment->author_email
         ?? null;

  // 1) Customer avatar_url (varsa en öncelikli)
  $customerAvatar = $comment->customer?->avatar_url;

  // 2) User avatar (projenizde user için benzer accessor varsa kullanın;
  // yoksa avatar_path → Storage::url)
  $userAvatarPath = $comment->user->avatar_path ?? null;
  if ($userAvatarPath && !\Illuminate\Support\Str::startsWith($userAvatarPath, ['http','/'])) {
      $userAvatarPath = \Storage::url($userAvatarPath);
  }
  $userAvatar = $userAvatarPath;

  // 3) Gravatar (email varsa, yoksa 404 dönsün ki initials’a düşelim)
  $gravatar = $email
      ? 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . '?s=96&d=404'
      : null;

  // 4) Initials (Ad Soyad baş harfleri)
  $parts = preg_split('/\s+/u', trim($author), -1, PREG_SPLIT_NO_EMPTY);
  $first = mb_strtoupper(mb_substr($parts[0] ?? '', 0, 1, 'UTF-8'), 'UTF-8');
  $last  = mb_strtoupper(mb_substr($parts[count($parts)-1] ?? '', 0, 1, 'UTF-8'), 'UTF-8');
  // Sadece adın baş harfi istersen: $initials = $first ?: 'M';
  $initials = ($first ?: 'M') . ($last ?: '');

  // İsimden stabil renk
  $palette = ['#E9D5FF','#FFE4E6','#DBEAFE','#DCFCE7','#FEF9C3','#FCE7F3','#E2E8F0'];
  $bg = $palette[ crc32($author) % count($palette) ];

  $date = optional($comment->created_at)->translatedFormat('F d, Y') ?? '';
@endphp

<li id="c{{ $comment->id }}">
  <div class="postbox__comment-box d-sm-flex align-items-start">
    <div class="postbox__comment-info">
      <div class="postbox__comment-avater" style="width:64px;height:64px;">
        @if($customerAvatar)
          <img src="{{ $customerAvatar }}" alt="{{ $author }}" width="64" height="64">
        @elseif($userAvatar)
          <img src="{{ $userAvatar }}" alt="{{ $author }}" width="64" height="64">
        @elseif($gravatar)
          <img src="{{ $gravatar }}" alt="{{ $author }}" width="64" height="64"
               onerror="this.replaceWith(this.nextElementSibling)">
          <div class="cm-initials" style="display:none"></div>
        @else
          <div class="cm-initials"></div>
        @endif
      </div>
    </div>

    <div class="postbox__comment-text">
      <div class="postbox__comment-name">
        <span class="post-meta">{{ $date }}</span>
        <h5><a href="#c{{ $comment->id }}">{{ $author }}</a></h5>
      </div>

      <p>{{ $comment->content }}</p>

      <div class="postbox__comment-reply">
        <a href="#reply-{{ $comment->id }}" data-reply="{{ $comment->id }}">Cevapla</a>
      </div>
    </div>
  </div>

  @if($comment->children->isNotEmpty())
    <ul class="children">
      @foreach($comment->children as $child)
        @include('frontend.pages.blogs.partials.comment-item', ['comment' => $child])
      @endforeach
    </ul>
  @endif
</li>

{{-- Initials fallback --}}
<script>
  (function(){
    const container = document.currentScript.previousElementSibling;
    const box = container.querySelector('.cm-initials');
    if (!box) return;
    box.style.cssText =
      'width:64px;height:64px;border-radius:9999px;display:flex;align-items:center;justify-content:center;' +
      'font-weight:700;letter-spacing:.5px;background: {{ $bg }}; color:#111; user-select:none;';
    box.textContent = @json($initials);
  })();
</script>
