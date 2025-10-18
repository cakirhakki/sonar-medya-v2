@if(($site?->show_whatsapp_fab ?? false) && $site?->whatsapp_e164)
    @php
        $prefill = rawurlencode('Merhaba, ' . ($site->site_title ?? config('app.name')) . ' websitesinden yazıyorum: ' . url()->current());
        $href = "{$site->whatsapp_url}?text={$prefill}";
    @endphp

    <a href="{{ $href }}" target="_blank" rel="noopener"
       class="whatsapp-fab" aria-label="WhatsApp ile yazın">
        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
    </a>

    <style>
        :root{
            --fab-size:56px;
            --fab-gap:18px;
            --fab-c1:#25D366; /* ana */
            --fab-c2:#1ebe57; /* gradient */
        }

        .whatsapp-fab{
            position: fixed;
            left: var(--fab-gap);
            bottom: var(--fab-gap);
            z-index: 999;
            width: var(--fab-size);
            height: var(--fab-size);
            border-radius: 50%;
            display: grid;               /* 🔥 tam merkezleme */
            place-items: center;         /* 🔥 tam merkezleme */
            background: linear-gradient(135deg, var(--fab-c1), var(--fab-c2));
            color: #fff;
            text-decoration: none;
            box-shadow:
                0 10px 28px rgba(37,211,102,.35), /* ana gölge */
                0 0 0 8px rgba(37,211,102,.08);   /* yumuşak ring */
            transform: translateZ(0);
            transition: transform .16s ease, box-shadow .16s ease, filter .16s ease;
        }

        /* FA ikon hizası (bazı fontlarda baseline kayması olur) */
        .whatsapp-fab i{
            font-size: 22px;   /* 24 istersen büyüt */
            line-height: 1;
            display: block;    /* baseline etkisini sıfırla */
        }

        .whatsapp-fab:hover,
        .whatsapp-fab:focus-visible{
            transform: translateY(-2px);
            box-shadow:
                0 16px 36px rgba(37,211,102,.44),
                0 0 0 10px rgba(37,211,102,.10);
        }

        @media (max-width: 640px){
            :root{ --fab-size:52px; --fab-gap:14px; }
        }

        @media (prefers-reduced-motion: reduce){
            .whatsapp-fab{ transition:none; }
        }
    </style>
@endif
