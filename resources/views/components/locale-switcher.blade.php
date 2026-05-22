@php
    $locales = [
        'vi' => ['flag' => '🇻🇳', 'label' => 'VI', 'name' => 'Tiếng Việt'],
        'en' => ['flag' => '🇬🇧', 'label' => 'EN', 'name' => 'English'],
        'zh' => ['flag' => '🇨🇳', 'label' => 'ZH', 'name' => '中文'],
    ];
    $current = app()->getLocale();

    $routeName   = request()->route()?->getName() ?? 'home';
    $routeParams = request()->route()?->parameters() ?? [];
@endphp

<div class="ls-wrap" id="localeSwitcher">
    <button class="ls-btn" type="button" aria-haspopup="listbox" aria-expanded="false" id="localeSwitcherBtn">
        <span class="ls-flag">{{ $locales[$current]['flag'] }}</span>
        <span class="ls-label">{{ $locales[$current]['label'] }}</span>
        <svg class="ls-chevron" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.17l3.71-3.94a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/>
        </svg>
    </button>

    <ul class="ls-dropdown" role="listbox" aria-label="Select language" id="localeSwitcherMenu">
        @foreach($locales as $code => $info)
            @php
                try {
                    $url = route($routeName, array_merge($routeParams, ['locale' => $code]));
                } catch (\Throwable $e) {
                    $url = '/' . $code;
                }
            @endphp
            <li role="option" @if($code === $current) aria-selected="true" @endif>
                <a href="{{ $url }}" class="ls-option {{ $code === $current ? 'ls-active' : '' }}">
                    <span class="ls-option-flag">{{ $info['flag'] }}</span>
                    <span class="ls-option-text">
                        <span class="ls-option-name">{{ $info['name'] }}</span>
                        <span class="ls-option-code">{{ $info['label'] }}</span>
                    </span>
                    @if($code === $current)
                        <svg class="ls-check" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>
</div>

<style>
.ls-wrap {
    position: relative;
    display: inline-block;
}
.ls-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.2);
    color: #fff;
    padding: 6px 12px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
    transition: background .15s, border-color .15s;
    white-space: nowrap;
    user-select: none;
}
.ls-btn:hover,
.ls-btn.ls-open {
    background: rgba(255,255,255,.15);
    border-color: rgba(255,255,255,.45);
}
.ls-flag  { font-size: 16px; line-height: 1; }
.ls-label { letter-spacing: .04em; }
.ls-chevron {
    width: 14px; height: 14px;
    opacity: .7;
    transition: transform .2s;
    flex-shrink: 0;
}
.ls-btn.ls-open .ls-chevron { transform: rotate(180deg); }

/* Dropdown */
.ls-dropdown {
    display: none;
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    background: #1c1b2e;
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 12px;
    padding: 6px;
    min-width: 160px;
    list-style: none;
    margin: 0;
    z-index: 99999;
    box-shadow: 0 12px 32px rgba(0,0,0,.45), 0 2px 8px rgba(0,0,0,.25);
}
.ls-dropdown.ls-open { display: block; }

/* Option */
.ls-option {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 8px;
    text-decoration: none;
    color: rgba(255,255,255,.7);
    transition: background .12s, color .12s;
    cursor: pointer;
}
.ls-option:hover {
    background: rgba(255,255,255,.1);
    color: #fff;
}
.ls-option.ls-active {
    background: rgba(255,255,255,.07);
    color: #fff;
}
.ls-option-flag  { font-size: 20px; line-height: 1; flex-shrink: 0; }
.ls-option-text  { display: flex; flex-direction: column; flex: 1; }
.ls-option-name  { font-size: 13px; font-weight: 500; line-height: 1.3; }
.ls-option-code  { font-size: 11px; opacity: .5; letter-spacing: .05em; }
.ls-check        { width: 15px; height: 15px; color: #7c6fff; flex-shrink: 0; }
</style>

<script>
(function () {
    var wrap  = document.getElementById('localeSwitcher');
    var btn   = document.getElementById('localeSwitcherBtn');
    var menu  = document.getElementById('localeSwitcherMenu');

    function open()  {
        btn.classList.add('ls-open');
        menu.classList.add('ls-open');
        btn.setAttribute('aria-expanded', 'true');
    }
    function close() {
        btn.classList.remove('ls-open');
        menu.classList.remove('ls-open');
        btn.setAttribute('aria-expanded', 'false');
    }
    function toggle() {
        menu.classList.contains('ls-open') ? close() : open();
    }

    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        toggle();
    });

    // Close when clicking outside
    document.addEventListener('click', function (e) {
        if (!wrap.contains(e.target)) close();
    });

    // Close on Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') close();
    });
})();
</script>
