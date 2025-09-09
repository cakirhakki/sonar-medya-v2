<div
    x-data="{
        prices: @js($prices),
        items: @entangle('data.items').live,
        discount: @entangle('data.discount_percent').live,
        sum: 0, final: 0,
        fmt(n){ return new Intl.NumberFormat('tr-TR',{minimumFractionDigits:2,maximumFractionDigits:2}).format(Number(n||0)) + ' ₺'; },
        pct(n){ n = Number(n||0); return n.toFixed(2).replace('.', ','); },
        rows(){
            // Repeater bazen array, bazen {'uuid': {...}} gelebiliyor
            return Array.isArray(this.items) ? this.items : Object.values(this.items ?? {});
        },
        recalc(){
            let s = 0;
            this.rows().forEach((row) => {
                const id = Number(row?.service_id || 0);
                const q  = Math.max(1, Number(row?.quantity || 1));
                const p  = Number(this.prices[id] ?? 0);
                s += p * q;
            });
            const d = Math.max(0, Number(this.discount || 0));
            this.sum = s;
            this.final = Math.max(s * (1 - d / 100), 0);
        }
    }"
    x-init="recalc()"
    x-effect="recalc()"
    class="p-4 rounded-2xl border border-gray-700/40 bg-gray-900/30"
>
    <div class="text-sm leading-6">
        <div class="font-medium mb-1">Özet</div>
        <div x-show="rows().length === 0" class="text-gray-400">Henüz hizmet seçilmedi.</div>
        <template x-if="rows().length > 0">
            <div>
                Toplam Hizmet Tutarı:
                <span class="font-semibold" x-text="fmt(sum)"></span>
                · İndirim: %<span class="font-semibold" x-text="pct(discount)"></span>
                · Hesaplanan Fiyat:
                <span class="font-semibold" x-text="fmt(final)"></span>
            </div>
        </template>
    </div>
</div>
