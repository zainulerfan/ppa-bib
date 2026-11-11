@props(['name', 'class' => ''])

<svg class="icon {{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    @switch($name)
        @case('layers')
            <path d="m12 3 9 5-9 5-9-5 9-5Z"/><path d="m3 12 9 5 9-5"/><path d="m3 16 9 5 9-5"/>
            @break
        @case('grid')
            <rect x="3.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.5"/>
            @break
        @case('users')
            <path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
            @break
        @case('graduation')
            <path d="m2 10 10-5 10 5-10 5-10-5Z"/><path d="M6 12v5c3.5 3 8.5 3 12 0v-5"/><path d="M22 10v6"/>
            @break
        @case('wallet')
            <rect x="3" y="5" width="18" height="15" rx="2"/><path d="M3 9h18"/><path d="M16 15h.01"/><path d="M7 5V3h10v2"/>
            @break
        @case('handshake')
            <path d="m11 17 2 2a2 2 0 0 0 3-3"/><path d="m14 14 4 4a2 2 0 0 0 3-3l-6-6-3 1"/><path d="m3 11 4-4h5l2 2-4 4a2 2 0 0 1-3 0l-1-1"/><path d="m3 11-2 2 4 4 2-2"/><path d="m21 11 2 2-3 3"/>
            @break
        @case('chevron')
            <path d="m9 18 6-6-6-6"/>
            @break
        @case('arrow-up-right')
            <path d="M7 17 17 7"/><path d="M7 7h10v10"/>
            @break
        @case('arrow-right')
            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
            @break
        @case('spark')
            <path d="m12 3 1.9 5.8L20 11l-6.1 2.2L12 19l-1.9-5.8L4 11l6.1-2.2L12 3Z"/><path d="m19 14 1.2 2.8L23 18l-2.8 1.2L19 22l-1.2-2.8L15 18l2.8-1.2L19 14Z"/>
            @break
        @case('menu')
            <path d="M4 6h16M4 12h16M4 18h16"/>
            @break
        @case('more')
            <circle cx="5" cy="12" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/>
            @break
        @case('check')
            <path d="m5 12 4 4L19 6"/>
            @break
        @case('pdf')
            <path d="M6 2.75h8l4.25 4.5v13A1.75 1.75 0 0 1 16.5 22h-10A1.75 1.75 0 0 1 4.75 20.25v-15A2.5 2.5 0 0 1 7.25 2.75Z"/>
            <path d="M14 3v5h4"/>
            <text x="12" y="16.5" text-anchor="middle" fill="#d94b4b" stroke="none" font-family="Arial, sans-serif" font-size="5.2" font-weight="700">PDF</text>
            @break
        @case('edit')
            <path d="m15 5 4 4"/><path d="M4 20h4l11-11a2.8 2.8 0 0 0-4-4L4 16v4Z"/>
            @break
        @case('trash')
            <path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="m19 6-1 14H6L5 6"/><path d="M10 11v5M14 11v5"/>
            @break
    @endswitch
</svg>
