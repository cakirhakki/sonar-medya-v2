<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\PostCategory;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class BlogDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $now = Carbon::now();

            /* ---------------- 1) Kategoriler (Picsum görsel) ---------------- */
            $parents = [
                ['name' => 'E-ticaret', 'slug' => 'e-ticaret', 'description' => 'E-ticaret içgörüleri', 'img' => 'https://picsum.photos/seed/cat-e-ticaret/1200/630'],
                ['name' => 'Pazarlama', 'slug' => 'pazarlama', 'description' => 'Performans ve içerik pazarlaması', 'img' => 'https://picsum.photos/seed/cat-pazarlama/1200/630'],
                ['name' => 'Teknik',   'slug' => 'teknik',    'description' => 'Teknik rehberler',                 'img' => 'https://picsum.photos/seed/cat-teknik/1200/630'],
            ];

            $parentIds = [];
            foreach ($parents as $p) {
                $cat = PostCategory::firstOrNew(['slug' => $p['slug']]);
                $cat->fill([
                    'name'        => $p['name'],
                    'parent_id'   => null,
                    'image_path'  => $p['img'],
                    'description' => $p['description'],
                    'is_active'   => true,
                ]);
                if (! $cat->exists) $cat->created_at = $now;
                $cat->updated_at = $now;
                $cat->save();
                $parentIds[$p['slug']] = $cat->id;
            }

            $children = [
                ['name' => 'Reklam',   'slug' => 'reklam',   'parent_slug' => 'pazarlama', 'img' => 'https://picsum.photos/seed/cat-reklam/1200/630'],
                ['name' => 'Analitik', 'slug' => 'analitik', 'parent_slug' => 'teknik',    'img' => 'https://picsum.photos/seed/cat-analitik/1200/630'],
            ];
            foreach ($children as $c) {
                $cat = PostCategory::firstOrNew(['slug' => $c['slug']]);
                $cat->fill([
                    'name'        => $c['name'],
                    'parent_id'   => $parentIds[$c['parent_slug']] ?? null,
                    'image_path'  => $c['img'],
                    'description' => null,
                    'is_active'   => true,
                ]);
                if (! $cat->exists) $cat->created_at = $now;
                $cat->updated_at = $now;
                $cat->save();
                $parentIds[$c['slug']] = $cat->id;
            }

            /* ---------------- 2) Yazılar ---------------- */
            $posts = [
                [
                    'slug'       => 'e-ticarette-ilk-30-gun-kontrol-listesi',
                    'title'      => 'E-ticarette İlk 30 Gün: Kontrol Listesi',
                    'category'   => 'e-ticaret',
                    'excerpt'    => 'Mağazanızı açtıktan sonra ilk ayda atmanız gereken adımlar.',
                    'content'=>'<p><strong>İlk 30 gün</strong> mağazanızın temellerini doğru atma sürecidir. Aşağıdaki adımlar, modern ve şık bir deneyimi güvenli, ölçülebilir ve hızlı biçimde yayına almanız için pratik bir kontrol listesidir.</p><section><h2>1) Alan Adı, SSL ve Ortam Hazırlığı</h2><ol><li><strong>Alan adı ve DNS:</strong> A kaydı, CNAME ve www yönlendirmelerini doğrulayın. <em>CAA</em> kaydı varsa sertifika sağlayıcınızı ekleyin.</li><li><strong>SSL/TLS:</strong> Let’s Encrypt veya kurumsal sertifika ile HTTPS’yi zorunlu kılın. HSTS başlığı ekleyin.</li><li><strong>WAF ve güvenlik başlıkları:</strong> <code>Content-Security-Policy</code>, <code>X-Frame-Options</code>, <code>Referrer-Policy</code>.</li><li><strong>Uygulama ayarları:</strong> Zaman dilimi, para birimi, dil ve <code>.env</code> yapılandırması.</li><li><strong>E-posta teslimi:</strong> SPF, DKIM, DMARC kayıtları ve test gönderimleri.</li></ol><details><summary>İpucu: HSTS nasıl eklenir?</summary><p>Sunucunuzda <code>Strict-Transport-Security: max-age=63072000; includeSubDomains; preload</code> başlığını etkinleştirin.</p></details></section><section><h2>2) Ödeme ve Kargo Entegrasyonları</h2><ol><li><strong>Ödeme ağ geçitleri:</strong> 3D Secure, test kartları, iade ve iptal akışları, muhasebe eşleşmesi.</li><li><strong>Kargo sağlayıcıları:</strong> Hizmet bölgeleri, ücret tabloları, takip bağlantıları ve teslimat e-postaları.</li><li><strong>Vergi ve fatura:</strong> KDV oranları, e-belge numaralandırma ve adres doğrulama.</li></ol><details><summary>Yaygın hata: Testten canlıya geçiş</summary><p>Sandbox anahtarlarını canlı anahtarlarla değiştirdikten sonra webhook adreslerini güncellemeyi unutmayın.</p></details></section><section><h2>3) Ölçümleme: GA4 ve Piksel</h2><ol><li><strong>GTM kurulumu:</strong> GA4 etiketleri ve <em>Consent Mode</em> yapılandırması.</li><li><strong>E-ticaret olayları:</strong> <code>view_item</code>, <code>add_to_cart</code>, <code>begin_checkout</code>, <code>purchase</code> ve para birimi.</li><li><strong>Reklam dönüşümleri:</strong> Google Ads ve Meta Ads dönüşüm eşleştirme, alan adı doğrulama.</li><li><strong>Doğrulama:</strong> GTM Önizleme, GA4 DebugView ve ağ istekleri ile olay parametrelerini kontrol edin.</li></ol><details><summary>Kontrol listesi</summary><ul><li>GTM konteyneri yayınlandı</li><li>GA4 e-ticaret parametreleri doğru</li><li>Reklam pikselleri ve dönüşümler tetikleniyor</li></ul></details></section><section><h2>4) Temel SEO ve İçerik</h2><ol><li><strong>Dizinleme:</strong> Robots.txt, XML site haritası, Search Console mülkiyet doğrulaması.</li><li><strong>Meta ve yapılandırılmış veri:</strong> Başlık ve açıklama, <em>Product</em> ve <em>Breadcrumb</em> şemaları.</li><li><strong>Kanonik ve çok dilli yapı:</strong> Kanonik bağlantılar ve <code>hreflang</code> etiketleri.</li><li><strong>Hız optimizasyonu:</strong> WebP görseller, tembel yükleme, önbellekleme ve kritik CSS.</li></ol></section><section><h2>5) Performans ve Güvenlik</h2><ul><li><strong>Önbellek:</strong> CDN, sayfa ve nesne önbelleği, gzip veya brotli sıkıştırma.</li><li><strong>Yedekleme:</strong> Günlük veritabanı ve haftalık tam yedekleme, kurtarma testleri.</li><li><strong>Günlükleme ve uyarı:</strong> Hata oranı, 5xx hataları, sepet terk ve ödeme başarısızlık oranları için alarmlar.</li></ul></section><section><h2>6) Yayın Öncesi Test (QA)</h2><ul><li>Misafir ve kayıtlı kullanıcı ile uçtan uca ödeme testi</li><li>Kupon, kargo, iade ve iptal akışları</li><li>KVKK ve çerez izinleri, aydınlatma metinleri</li><li>404 ve 500 sayfaları ile arama sonuçları</li></ul></section><section><h2>7) İlk Hafta → İlk 30 Gün Planı</h2><ol><li><strong>1–7. gün:</strong> Hata ve performans izleme, kritik düzeltmeler, ısı haritası ve oturum kayıtları.</li><li><strong>8–21. gün:</strong> Yeniden pazarlama kitleleri, ana kategori sayfaları, A/B başlık ve çağrı testi.</li><li><strong>22–30. gün:</strong> Kanal bazlı rapor, ürün performansı, sonraki ay yol haritası.</li></ol><details><summary>Rapor şablonu</summary><ul><li>Gelir, dönüşüm oranı, ortalama sepet tutarı</li><li>Sepet terk oranı</li><li>En çok aranan ve en çok dönüşüm getiren sorgular</li><li>Öncelikli 5 aksiyon</li></ul></details></section><p><strong>Sonuç:</strong> Bu adımları sistemli biçimde tamamlamak, ilk 30 günde markanızın teknik, pazarlama ve güvenlik temellerini sağlamlaştırır. Ölçülebilir verilerle ilerlemek ve kullanıcı deneyimini sürekli iyileştirmek sürdürülebilir büyümenin anahtarıdır.</p>',
                    'image'      => 'https://picsum.photos/seed/e-ticaret-30-gun/1200/630',
                    'meta_image' => 'https://picsum.photos/seed/e-ticaret-30-gun-meta/1200/630',
                    'tags'       => ['e-ticaret', 'başlangıç', 'checklist'],
                ],
                [
                    'slug'       => 'meta-ve-google-ads-icin-hizli-baslangic',
                    'title'      => 'Meta ve Google Ads için Hızlı Başlangıç',
                    'category'   => 'reklam',
                    'excerpt'    => 'Hesap bağlantıları, piksel ve temel kampanya kurulumu.',
                    'content'=>'<p><strong>Hedef:</strong> Business Manager ve Google Ads hesap bağlantılarını tamamlayıp, GA4 entegrasyonu ve ilk kampanya şablonları ile hızlı yayına geçmek.</p><section><h2>1) Hesap Kurulumu ve Bağlantılar</h2><ol><li><strong>Meta Business Manager:</strong> İşletme, Sayfa, Reklam Hesabı, Piksel ve diğer varlıkları tek çatı altında toplayın. Rol ve izinleri kontrol edin.</li><li><strong>Alan adı doğrulama:</strong> DNS TXT veya meta etiketi ile doğrulayın. Diğer alan adları için ek kayıt yapın.</li><li><strong>Dönüşümler API:</strong> Sunucu taraflı olaylar için erişim belirteci oluşturun. E-ticaret olay iletilerini test edin.</li><li><strong>Google tarafı:</strong> GA4 mülkü, Google Ads hesabı, Search Console bağlantıları. GA4 ↔ Google Ads bağlantısını etkinleştirin.</li><li><strong>Erişimler:</strong> Tüm hesaplarda gerekli en az yetki setlerini (yönetici/düzenleyici) atayın.</li></ol><details><summary>Hızlı kontrol listesi</summary><ul><li>Business Manager aktif, Sayfa ve Piksel bağlı</li><li>Alan adı doğrulandı</li><li>GA4 mülkü oluşturuldu ve Google Ads ile bağlandı</li><li>Reklam hesabı faturalandırma ve para birimi doğru</li></ul></details></section><section><h2>2) Ölçümleme: GA4 ve Etiket Yönetimi</h2><ol><li><strong>Etiket yönetimi:</strong> GTM konteyneri kurun. GA4 yapılandırma ve etkinlik etiketlerini ekleyin.</li><li><strong>E-ticaret etkinlikleri:</strong> <code>view_item</code>, <code>add_to_cart</code>, <code>begin_checkout</code>, <code>purchase</code> ve para birimi alanlarını doldurun.</li><li><strong>Onay Modu (Consent Mode) v2:</strong> Reklam ve analiz izin sinyallerini ayarlayın. Ön izleme ile değişkenleri doğrulayın.</li><li><strong>Test:</strong> GTM Önizleme ve GA4 DebugView ile parametreleri ve olay akışını kontrol edin.</li></ol><details><summary>UTM şablonu</summary><p><code>?utm_source=meta&amp;utm_medium=cpc&amp;utm_campaign=launch&amp;utm_content=primary</code></p></details></section><section><h2>3) Piksel ve Dönüşüm Kurulumu</h2><ul><li><strong>Meta Piksel + CAPI:</strong> Tarayıcı ve sunucu olaylarını eşleştirin. Olay kimliği ile çakışmayı önleyin.</li><li><strong>Google Ads Dönüşümleri:</strong> Satın alma değeri ve para birimini gönderin. Geliştirilmiş dönüşümleri etkinleştirin.</li><li><strong>Doğrulama:</strong> Meta Test Events ve Google Tag Assistant ile tetikleyici ve parametreleri doğrulayın.</li></ul></section><section><h2>4) İlk Kampanya Şablonları</h2><h3>Google Ads</h3><ol><li><strong>Marka Arama:</strong> Marka sorguları. Düşük TBM tavanı, geniş eşleşme + negatif kelimeler.</li><li><strong>Genel Arama:</strong> Ürün kategori sorguları. SKAG yerine tematik reklam grupları, 3 başlık x 2 açıklama.</li><li><strong>Performans Maksimum:</strong> Feed + sinyaller. Hedef kitleler: yeniden pazarlama, tüm kullanıcılar, benzer sinyaller.</li></ol><h3>Meta Reklamları</h3><ol><li><strong>Satış odaklı kampanya:</strong> Geniş hedefleme + sinyal listeleri (sepete ekleme, görüntüleme). 3–5 yaratıcı varyasyon.</li><li><strong>Yeniden pazarlama:</strong> Son 7–14 gün sepete ekleyen ve görüntüleyen kitleler. Dinamik katalog önerileri.</li></ol><details><summary>Adlandırma ve bütçe</summary><ul><li><code>TR|Search|Brand|Exact</code>, <code>TR|Meta|Sales|BAU</code></li><li>Başlangıç bütçesi: ciro hedefinin %5–10’u aralığında dağıtılabilir.</li></ul></details></section><section><h2>5) Görsel ve Metin Setleri</h2><ul><li><strong>Görseller:</strong> 1:1, 4:5, 16:9 oranlarında 4–6 parça. Sade tipografi, net eylem çağrısı (CTA).</li><li><strong>Başlıklar:</strong> En az 3 varyasyon. Fiyat, özellik ve değer önerisi karışımı.</li><li><strong>Açıklamalar:</strong> 2 kısa varyasyon. Kargo, iade, taksit gibi itiraz çözücüler.</li></ul></section><section><h2>6) Yayın Öncesi Kontrol (QA)</h2><ul><li>Kredi kartı testi, kupon ve kargo koşulları</li><li>Para birimi, zaman dilimi, vergi ve KVKK metinleri</li><li>404 ve 500 sayfaları, arama sonuç sayfası</li><li>GA4 ve Ads dönüşüm sayıları kısa süreli test satın alma ile doğrulama</li></ul></section><section><h2>7) İlk 7 Gün Optimizasyon Planı</h2><ol><li><strong>Gün 1–2:</strong> Dönüşüm verisi ve arama terimleri inceleme, negatif kelime güncellemeleri.</li><li><strong>Gün 3–5:</strong> Bütçe dengeleme, zayıf yaratıcıyı durdurma, başarılı yaratıcıyı çoğaltma.</li><li><strong>Gün 6–7:</strong> Açılış sayfası hızı ve eylem çağrısı testleri, yeniden pazarlama pencerelerini gözden geçirme.</li></ol></section><section><h2>Sık Hatalar ve Çözümleri</h2><details><summary>Dönüşüm sayılmıyor</summary><p>Etkinlik adları veya parametreleri eşleşmiyor olabilir. Tag Assistant ile tetikleyici ve olay parametrelerini karşılaştırın.</p></details><details><summary>Yüksek TBM</summary><p>Kalite puanı düşük olabilir. Reklam metni ve sayfa alakalılığını iyileştirin, negatif kelimeler ekleyin.</p></details><details><summary>Meta çapraz atıf</summary><p>Birincil atıf pencerelerini netleştirin. CAPI ve Piksel çakışmalarını kontrol edin.</p></details></section><p><strong>Sonuç:</strong> Bu plan, dijital kampanyalarınızın ilk haftasında sağlam ölçümleme, doğru hedefleme ve etkili içerik temeli kurmanızı sağlar. Böylece reklam bütçenizi verimli kullanır ve dönüşüm oranlarını hızlı biçimde optimize edersiniz.</p>',

                    'image'      => 'https://picsum.photos/seed/ads-hizli-baslangic/1200/630',
                    'meta_image' => 'https://picsum.photos/seed/ads-hizli-baslangic-meta/1200/630',
                    'tags'       => ['reklam', 'meta ads', 'google ads'],
                ],
                [
                    'slug'       => 'ga4-olcumleme-icin-en-iyi-uygulamalar',
                    'title'      => 'GA4 Ölçümleme için En İyi Uygulamalar',
                    'category'   => 'analitik',
                    'excerpt'    => 'Etkinlik mimarisi, e-ticaret parametreleri ve raporlama.',
                    'content'=>'<p><strong>Hedef:</strong> GTM ile olaya dayalı kurgu, e-ticaret parametreleri ve Keşif (Explorations) raporları üzerinden karar süreçlerini güçlendirmek.</p><section><h2>1) Mimari ve Etkinlik Tasarımı</h2><ol><li><strong>Veri katmanı (dataLayer):</strong> Sayfa yüklemeleri ve e-ticaret adımları için tutarlı bir <code>dataLayer</code> şeması oluşturun.</li><li><strong>Etkinlik adlandırma:</strong> <em>GA4 Önerilen Etkinlikler</em> listesini baz alın: <code>view_item</code>, <code>add_to_cart</code>, <code>begin_checkout</code>, <code>add_payment_info</code>, <code>purchase</code>.</li><li><strong>Parametre standardı:</strong> <code>item_id</code>, <code>item_name</code>, <code>currency</code>, <code>value</code>, <code>coupon</code>, <code>shipping_tier</code> gibi alanları her adımda tutarlı gönderin.</li><li><strong>Kimlik ve eşleştirme:</strong> <code>user_id</code> ve <code>client_id</code> alanlarını (müşteri girişi varsa) tanımlayın; cihazlar arası analize zemin hazırlar.</li></ol><details><summary>Minimal e-ticaret dataLayer örneği</summary><pre><code>{ "event": "purchase", "ecommerce": { "transaction_id": "ORD-10245", "currency": "TRY", "value": 1299.90, "shipping": 0, "tax": 0, "items": [ {"item_id":"SKU-123","item_name":"Ürün A","price":1299.90,"quantity":1} ] } }</code></pre></details></section><section><h2>2) GTM Kurulumu ve Etiketler</h2><ol><li><strong>GA4 Yapılandırması:</strong> Tek bir <em>Config</em> etiketiyle tüm sayfalarda çalışın; <code>send_page_view</code> kontrolünü bilinçli yapın.</li><li><strong>GA4 Olay Etiketleri:</strong> Her olay için ayrı etiket ve tetikleyici oluşturun veya <em>dataLayer</em> tabanlı genel bir eşleştirme kuralı yazın.</li><li><strong>Onay Modu (Consent Mode) v2:</strong> <code>ad_storage</code> ve <code>analytics_storage</code> izinlerini CMP üzerinden GTM’ye aktarın ve etiketleri koşullu çalıştırın.</li><li><strong>Alanlar arası izleme (Cross-domain):</strong> GA4 yapılandırmasında alan adları listesi belirleyip <em>linker</em> özelliğini etkinleştirin.</li></ol><details><summary>UTM ve kampanya isimlendirme</summary><p><code>?utm_source=meta&amp;utm_medium=cpc&amp;utm_campaign=launch_q4&amp;utm_content=hero_primary</code></p></details></section><section><h2>3) Doğrulama ve Kontrol (QA)</h2><ul><li><strong>GTM Önizleme:</strong> Tetikleyiciler, değişken değerleri ve veri katmanı içeriği adım adım incelenir.</li><li><strong>GA4 DebugView:</strong> Canlı olay akışı, parametreler ve kullanıcı özellikleri kontrol edilir.</li><li><strong>Tag Assistant:</strong> Etiket çakışmaları, birden fazla sayfa görüntülemesi ve hatalı parametreler tespit edilir.</li></ul><details><summary>Sık hata: eksik <code>currency</code> / <code>value</code></summary><p>GA4 gelir raporları boş görünebilir. Satın alma ve sepet adımlarında <code>currency</code> ve <code>value</code> alanlarını zorunlu tutun.</p></details></section><section><h2>4) Dönüşümler ve Eşikler</h2><ol><li><strong>Dönüşümler:</strong> <code>purchase</code>, <code>generate_lead</code> gibi kritik olayları <em>Dönüşüm olarak işaretle</em>.</li><li><strong>Atıf penceresi:</strong> Varsayılan süreyi ihtiyaca göre güncelleyin (örneğin 7 gün tıklama, 28 gün görüntüleme).</li><li><strong>Gizlilik eşiği:</strong> Düşük hacimlerde rapor gizlemeyi anlayın; BigQuery dışa aktarımıyla ham veriye erişin.</li></ol></section><section><h2>5) Keşif (Explorations) ve Raporlama</h2><ul><li><strong>Hunisel keşif:</strong> <em>view_item → add_to_cart → begin_checkout → purchase</em> akışında kayıp noktalarını tespit edin.</li><li><strong>Segment örtüşmesi:</strong> Trafik kanalı × cihaz × yeni/köken kullanıcı kesişimlerini analiz edin.</li><li><strong>Yol keşfi:</strong> Sepet terk öncesi ve sonrası yolları inceleyin; kritik sayfalarda UX testleri planlayın.</li></ul><details><summary>Hızlı KPI kontrol listesi</summary><ul><li>Dönüşüm oranı (CVR)</li><li>Ortalama sipariş tutarı (AOV)</li><li>Ürüne sepete ekleme oranı</li><li>Ödeme adımı terk oranı</li></ul></details></section><section><h2>6) İleri Düzey: Sunucu Taraflı GTM ve BigQuery</h2><ul><li><strong>Sunucu taraflı GTM:</strong> Çerez dayanıklılığını ve sinyal kalitesini artırmak için işleme katmanı ekleyin.</li><li><strong>BigQuery Aktarımı:</strong> Ham olaya dayalı veriyi günlük olarak aktarın, anomali tespitleri ve yaşam boyu değer (LTV) kohortlarını oluşturun.</li></ul></section><section><h2>7) İlk 14 Gün Optimizasyon Planı</h2><ol><li><strong>Gün 1–3:</strong> DebugView ve gelir uyumu kontrolü (ciro/GA4 karşılaştırması).</li><li><strong>Gün 4–7:</strong> Hunide en yüksek kayıp adımı iyileştirme (kargo, ödeme, form alanları).</li><li><strong>Gün 8–14:</strong> Kanal bazlı segmentlerde A/B denemeleri ve yeniden pazarlama penceresi ayarları.</li></ol></section><p><strong>Sonuç:</strong> Bu yapı, ölçümleme ve veri kalitesini standardize ederek reklam yatırımlarınızı daha doğru analiz etmenizi sağlar. GTM ve GA4 entegrasyonu güçlü bir temel sunar; veri tutarlılığı, e-ticaret performansını sürdürülebilir biçimde artırmanın anahtarıdır.</p>',

                    'image'      => 'https://picsum.photos/seed/ga4-best-practices/1200/630',
                    'meta_image' => 'https://picsum.photos/seed/ga4-best-practices-meta/1200/630',
                    'tags'       => ['ga4', 'gtm', 'analitik'],
                ],
            ];

            // Aktif kullanıcı ya da ilk kullanıcıyı yazar olarak ata
            $authorId = Auth::id() ?? User::query()->orderBy('id')->value('id');

            foreach ($posts as $p) {
                $catId = $parentIds[$p['category']] ?? null;

                $post = Post::withTrashed()->where('slug', $p['slug'])->first();

                if (! $post) {
                    $post = new Post([
                        'title'                     => $p['title'],
                        'slug'                      => $p['slug'],
                        'excerpt'                   => $p['excerpt'],
                        'content'                   => $p['content'],
                        'featured_image_path'       => $p['image'],      // Picsum URL
                        'featured_image_alt'        => $p['title'],
                        'featured_image_caption'    => null,
                        'featured_image_credit_text'=> null,
                        'featured_image_credit_url' => null,
                        'published_at'              => $now,
                        'status'                    => 'published',
                        'author_id'                 => $authorId,
                        'primary_post_category_id'  => $catId,
                        'meta_title'                => $p['title'],
                        'meta_description'          => $p['excerpt'],
                        'meta_image'                => $p['meta_image'], // Picsum URL
                        'created_at'                => $now,
                        'updated_at'                => $now,
                    ]);
                    $post->save();
                } else {
                    if ($post->trashed()) $post->restore();

                    $data = [
                        'title'                    => $p['title'],
                        'excerpt'                  => $p['excerpt'],
                        'content'                  => $p['content'],
                        'featured_image_path'      => $p['image'],
                        'featured_image_alt'       => $p['title'],
                        'status'                   => 'published',
                        'primary_post_category_id' => $catId,
                        'meta_title'               => $p['title'],
                        'meta_description'         => $p['excerpt'],
                        'meta_image'               => $p['meta_image'],
                    ];
                    if (blank($post->author_id)) $data['author_id'] = $authorId;

                    $post->fill($data);
                    if (blank($post->published_at)) $post->published_at = $now;
                    $post->updated_at = $now;
                    $post->save();
                }

                // Etiketler
                $post->syncTags($p['tags']);

                /* -------------- 3) Yorumlar (her posta 5 Türkçe isimli yorum) -------------- */
                $names = ['Ahmet Yılmaz','Ayşe Demir','Mehmet Kaya','Elif Şahin','Can Yıldız',
                          'Zeynep Çelik','Burak Arslan','Merve Koç','Emre Aydın','Selin Aksoy'];

                $templates = [
                    "Çok faydalı bir içerik olmuş. “{title}” konusunda net bir yol haritası verdi.",
                    "Önerileri uygulamaya başladık, kısa sürede somut sonuçlar aldık. Emeğinize sağlık!",
                    "Özellikle pratik ipuçları ve kontrol listesi kısmı harika. Teşekkürler.",
                    "Başlangıçta aklımıza gelmeyen detayları hatırlattı; ekibimizle paylaştım.",
                    "Raporlama ve ölçümleme bölümünü ayrı beğendim. Devamını bekliyoruz!",
                ];

                for ($k = 0; $k < 5; $k++) {
                    $name  = $names[$k];
                    $email = Str::slug($name, '.') . "+{$post->slug}-{$k}@example.com";

                    Comment::updateOrCreate(
                        [
                            'commentable_id'   => $post->id,
                            'commentable_type' => Post::class,
                            'author_email'     => $email,   // idempotent anahtar
                        ],
                        [
                            'author_name' => $name,
                            'content'     => str_replace('{title}', $post->title, $templates[$k]),
                            'status'      => 'approved',
                            'user_id'     => null,
                            'customer_id' => null,
                            'parent_id'   => null,
                            // IP/User-Agent seeder ortamında boş kalabilir; model event null atayabilir
                        ]
                    );
                }
            }
        });
    }
}
