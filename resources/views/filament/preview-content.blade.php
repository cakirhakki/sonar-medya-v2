@if (trim(strip_tags($html)) === '')
  <div class="text-sm text-gray-500 dark:text-gray-400">
    Önizleme için içerik girin (Görsel Editör veya HTML Kaynak).
  </div>
@else
  <div class="prose dark:prose-invert max-w-none">
    {!! $html !!}
  </div>
@endif
