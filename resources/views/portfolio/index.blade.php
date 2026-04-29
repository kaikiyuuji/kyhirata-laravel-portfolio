@extends('layouts.app')

@section('content')
@php
    $visibleProjects = $projects ?? collect();
    $experienceItems = $experiences ?? collect();
    $socialItems = $socialLinks ?? collect();

    $technologyUsage = $visibleProjects
        ->flatMap(function ($project) {
            return $project->technologies ?? collect();
        })
        ->groupBy('id')
        ->map(function ($items) {
            return [
                'technology' => $items->first(),
                'count' => $items->count(),
            ];
        })
        ->sortByDesc('count');

    $stackTechnologies = $technologyUsage
        ->map(function ($item) {
            return $item['technology'];
        })
        ->take(8)
        ->values();
@endphp

<style>
    :root {
        scroll-behavior: smooth;
        --portfolio-bg: #f8fafc;
        --portfolio-bg-secondary: #ffffff;
        --portfolio-surface: rgba(255, 255, 255, 0.82);
        --portfolio-surface-strong: rgba(255, 255, 255, 0.94);
        --portfolio-border: rgba(148, 163, 184, 0.2);
        --portfolio-border-strong: rgba(148, 163, 184, 0.28);
        --portfolio-text: #0f172a;
        --portfolio-text-soft: #475569;
        --portfolio-text-muted: #64748b;
        --portfolio-pill: rgba(255, 255, 255, 0.88);
        --portfolio-shadow: 0 30px 90px -52px rgba(15, 23, 42, 0.38);
        --portfolio-shadow-soft: 0 26px 60px -42px rgba(15, 23, 42, 0.24);
        --portfolio-accent: #10b981;
        --portfolio-button: #020617;
        --portfolio-button-text: #ffffff;
    }

    body.portfolio-dark {
        --portfolio-bg: #020617;
        --portfolio-bg-secondary: #0f172a;
        --portfolio-surface: rgba(15, 23, 42, 0.78);
        --portfolio-surface-strong: rgba(15, 23, 42, 0.9);
        --portfolio-border: rgba(148, 163, 184, 0.16);
        --portfolio-border-strong: rgba(148, 163, 184, 0.22);
        --portfolio-text: #f8fafc;
        --portfolio-text-soft: #cbd5e1;
        --portfolio-text-muted: #94a3b8;
        --portfolio-pill: rgba(15, 23, 42, 0.78);
        --portfolio-shadow: 0 30px 90px -52px rgba(2, 6, 23, 0.82);
        --portfolio-shadow-soft: 0 26px 60px -42px rgba(2, 6, 23, 0.62);
        --portfolio-accent: #34d399;
        --portfolio-button: #f8fafc;
        --portfolio-button-text: #020617;
    }

    .portfolio-shell {
        position: relative;
        overflow: hidden;
        background:
            radial-gradient(circle at top left, rgba(244, 114, 182, 0.18), transparent 24rem),
            radial-gradient(circle at top right, rgba(99, 102, 241, 0.16), transparent 28rem),
            linear-gradient(180deg, var(--portfolio-bg) 0%, var(--portfolio-bg-secondary) 100%);
        color: var(--portfolio-text);
        transition: background 0.35s ease, color 0.35s ease;
    }

    .portfolio-grid {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(148, 163, 184, 0.08) 1px, transparent 1px),
            linear-gradient(90deg, rgba(148, 163, 184, 0.08) 1px, transparent 1px);
        background-size: 30px 30px;
        mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.2), transparent 86%);
        pointer-events: none;
        opacity: 0.7;
    }

    .portfolio-bg-orb {
        position: absolute;
        border-radius: 9999px;
        filter: blur(18px);
        pointer-events: none;
        transition: transform 0.2s linear;
    }

    .portfolio-bg-orb-1 {
        top: 8%;
        left: -6rem;
        height: 18rem;
        width: 18rem;
        background: rgba(244, 114, 182, 0.18);
    }

    .portfolio-bg-orb-2 {
        top: 32%;
        right: -7rem;
        height: 22rem;
        width: 22rem;
        background: rgba(99, 102, 241, 0.16);
    }

    .portfolio-bg-orb-3 {
        bottom: 10%;
        left: 22%;
        height: 14rem;
        width: 14rem;
        background: rgba(16, 185, 129, 0.12);
    }

    .portfolio-surface {
        background: var(--portfolio-surface);
        border: 1px solid var(--portfolio-border);
        box-shadow: var(--portfolio-shadow);
        backdrop-filter: blur(18px);
        transition: background 0.35s ease, border-color 0.35s ease, box-shadow 0.35s ease;
    }

    .portfolio-card {
        background: var(--portfolio-surface-strong);
        border: 1px solid var(--portfolio-border);
        box-shadow: var(--portfolio-shadow-soft);
        transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease, background 0.35s ease;
    }

    .portfolio-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 30px 70px -46px rgba(15, 23, 42, 0.4);
    }

    body.portfolio-dark .portfolio-card:hover {
        box-shadow: 0 30px 70px -46px rgba(2, 6, 23, 0.86);
    }

    .reveal {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }

    .reveal.is-visible {
        opacity: 1;
        transform: translateY(0);
    }

    .hero-name {
        letter-spacing: -0.065em;
        line-height: 0.92;
    }

    .hero-role {
        letter-spacing: -0.05em;
        line-height: 0.98;
    }

    .copy-email-feedback {
        transition: opacity 0.25s ease, transform 0.25s ease;
    }

    .theme-toggle {
        position: fixed;
        right: 1rem;
        bottom: 1rem;
        z-index: 50;
        border: 1px solid var(--portfolio-border-strong);
        background: var(--portfolio-surface-strong);
        color: var(--portfolio-text);
        box-shadow: var(--portfolio-shadow-soft);
        backdrop-filter: blur(16px);
        transition: background 0.35s ease, color 0.35s ease, border-color 0.35s ease, transform 0.25s ease;
    }

    .theme-toggle:hover {
        transform: translateY(-2px);
    }

    .stack-chip {
        transition: transform 0.24s ease, border-color 0.24s ease, background 0.24s ease;
    }

    .stack-chip:hover {
        transform: translateY(-2px);
        border-color: var(--portfolio-border-strong);
    }

    .project-card {
        position: relative;
    }

    .project-card::before {
        content: "";
        position: absolute;
        inset: 0;
        border-radius: 1.75rem;
        padding: 1px;
        background: linear-gradient(145deg, rgba(148, 163, 184, 0.2), rgba(255, 255, 255, 0.65));
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        pointer-events: none;
    }

    body.portfolio-dark .project-card::before {
        background: linear-gradient(145deg, rgba(148, 163, 184, 0.18), rgba(15, 23, 42, 0.6));
    }

    .project-thumb::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, transparent 0%, rgba(15, 23, 42, 0.14) 100%);
    }

    .portfolio-text {
        color: var(--portfolio-text);
    }

    .portfolio-text-soft {
        color: var(--portfolio-text-soft);
    }

    .portfolio-text-muted {
        color: var(--portfolio-text-muted);
    }

    .portfolio-pill {
        background: var(--portfolio-pill);
        border: 1px solid var(--portfolio-border);
        color: var(--portfolio-text-soft);
        transition: background 0.35s ease, border-color 0.35s ease, color 0.35s ease;
    }

    .portfolio-button-dark {
        background: var(--portfolio-button);
        color: var(--portfolio-button-text);
        transition: background 0.35s ease, color 0.35s ease;
    }

    .portfolio-avatar {
        filter: drop-shadow(0 34px 40px rgba(15, 23, 42, 0.12));
    }

    body.portfolio-dark .portfolio-avatar {
        filter: drop-shadow(0 34px 44px rgba(2, 6, 23, 0.4));
    }

    @media (max-width: 639px) {
        .mobile-badge-stack {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.75rem;
            width: 100%;
        }

        .mobile-badge-stack .portfolio-pill {
            justify-content: flex-start;
            width: 100%;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .reveal {
            opacity: 1;
            transform: none;
            transition: none;
        }

        .portfolio-card:hover,
        .theme-toggle:hover,
        .stack-chip:hover {
            transform: none;
        }

        .portfolio-bg-orb {
            transition: none;
        }
    }
</style>

<div class="portfolio-shell min-h-screen">
    <div class="portfolio-grid"></div>
    <div class="portfolio-bg-orb portfolio-bg-orb-1" data-speed="0.08"></div>
    <div class="portfolio-bg-orb portfolio-bg-orb-2" data-speed="-0.12"></div>
    <div class="portfolio-bg-orb portfolio-bg-orb-3" data-speed="0.06"></div>

    <button
        type="button"
        id="theme-toggle"
        class="theme-toggle inline-flex items-center gap-2 rounded-2xl px-4 py-3 text-sm font-semibold"
        aria-label="Alternar tema"
    >
        <i class="fas fa-moon" id="theme-toggle-icon"></i>
        <span id="theme-toggle-label">Tema escuro</span>
    </button>

    <div class="relative mx-auto max-w-7xl px-4 py-6 sm:px-6 sm:py-8 lg:px-8 lg:py-12">
        <section class="portfolio-surface mb-12 rounded-[2rem] p-5 sm:p-8 lg:mb-16 lg:p-10">
            <div class="mb-8 flex flex-col gap-4 border-b border-[var(--portfolio-border)] pb-6 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <span class="portfolio-pill inline-flex items-center gap-2 rounded-full px-3 py-1 text-[0.68rem] font-semibold uppercase tracking-[0.28em] shadow-sm">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Portfólio
                    </span>
                </div>

                <div class="mobile-badge-stack flex flex-wrap gap-2.5">
                    @if(isset($aboutMe) && $aboutMe->location)
                        <span class="portfolio-pill inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium">
                            <i class="fas fa-location-dot text-slate-400"></i>
                            {{ $aboutMe->location }}
                        </span>
                    @endif

                    @if(isset($aboutMe) && $aboutMe->is_available_for_work)
                        <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-medium text-emerald-700">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                            Disponível para trabalho
                        </span>
                    @endif
                </div>
            </div>

            <div class="grid gap-8 lg:grid-cols-[1.12fr_0.88fr] lg:items-center">
                <!-- Avatar para Mobile (aparece primeiro no mobile) -->
                <aside class="reveal flex justify-center order-first lg:order-last lg:justify-end">
                    <div class="portfolio-avatar">
                        @if(isset($aboutMe) && $aboutMe->avatar_path)
                            <img
                                src="{{ Storage::url($aboutMe->avatar_path) }}"
                                alt="{{ $aboutMe->name }}"
                                class="h-72 w-72 object-contain sm:h-80 sm:w-80 lg:h-[26rem] lg:w-[26rem]"
                            >
                        @elseif(isset($aboutMe))
                            <div class="portfolio-card flex h-72 w-72 items-center justify-center rounded-[2.25rem] text-6xl font-bold portfolio-text sm:h-80 sm:w-80 lg:h-[26rem] lg:w-[26rem]">
                                {{ substr($aboutMe->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                </aside>

                <div class="reveal is-visible">
                    @if(isset($aboutMe))
                        <h1 class="hero-name portfolio-text text-5xl font-semibold sm:text-6xl lg:text-7xl">
                            {{ $aboutMe->name }}
                        </h1>
                        <h2 class="hero-role portfolio-text-soft mt-4 max-w-3xl text-2xl font-medium sm:text-3xl lg:text-[2.7rem]">
                            {{ $aboutMe->title }}
                        </h2>
                        <p class="portfolio-text-muted mt-5 max-w-2xl text-sm leading-7 sm:text-base">
                            Criando experiências digitais elegantes, sólidas e funcionais, com atenção a qualidade visual, arquitetura e consistência entre desktop e mobile.
                        </p>

                        @if($aboutMe->email)
                            <div class="mt-5 flex flex-wrap gap-3">
                                <button
                                    type="button"
                                    data-copy-email="{{ $aboutMe->email }}"
                                    class="portfolio-pill inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium transition hover:border-[var(--portfolio-border-strong)] hover:text-[var(--portfolio-text)]"
                                >
                                    <i class="fas fa-copy text-slate-400"></i>
                                    <span>{{ $aboutMe->email }}</span>
                                </button>
                            </div>
                        @endif
                    @endif

                    <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                        @if(isset($aboutMe) && $aboutMe->email)
                            <a href="mailto:{{ $aboutMe->email }}" class="portfolio-button-dark inline-flex items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-semibold shadow-[0_16px_30px_-18px_rgba(15,23,42,0.7)]">
                                <i class="fas fa-paper-plane"></i>
                                Entrar em contato
                            </a>
                        @endif

                        @if($visibleProjects->count() > 0)
                            <a href="#projetos" class="portfolio-pill inline-flex items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-semibold shadow-sm transition hover:border-[var(--portfolio-border-strong)] hover:text-[var(--portfolio-text)]">
                                <i class="fas fa-arrow-down"></i>
                                Ver projetos
                            </a>
                        @endif
                    </div>

                    @if(isset($aboutMe) && $aboutMe->email)
                        <p id="copy-email-feedback" class="copy-email-feedback mt-3 text-sm text-emerald-600 opacity-0 translate-y-1">
                            E-mail copiado.
                        </p>
                    @endif
                </div>
            </div>
        </section>

        @if($stackTechnologies->count() > 0)
            <section class="mb-12 lg:mb-16">
                <div class="reveal is-visible mb-5">
                    <p class="portfolio-text-muted text-sm font-semibold uppercase tracking-[0.28em]">Stack</p>
                    <h3 class="portfolio-text mt-2 text-2xl font-semibold tracking-tight sm:text-3xl">
                        Especialidades Técnicas
                    </h3>
                </div>

                <div class="portfolio-card reveal is-visible rounded-[1.75rem] p-4 sm:p-5">
                    <div class="grid grid-cols-2 gap-3 md:grid-cols-3 xl:grid-cols-4">
                        @foreach($stackTechnologies as $technology)
                            <div class="stack-chip flex min-h-[10rem] flex-col items-center justify-center gap-4 rounded-2xl border border-[var(--portfolio-border)] bg-[var(--portfolio-surface)] px-3 py-6 text-center">
                                <span class="flex h-24 w-24 items-center justify-center rounded-2xl bg-[var(--portfolio-bg-secondary)] text-[3.2rem] shadow-sm sm:h-28 sm:w-28 sm:text-[3.8rem]">
                                    <i
                                        class="{{ $technology->display_icon }}"
                                        @if($technology->color) style="color: {{ $technology->color }};" @endif
                                    ></i>
                                </span>
                                <p class="portfolio-text text-sm font-semibold sm:text-[1rem]">{{ $technology->name }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if(isset($aboutMe))
            <section class="mb-12 lg:mb-16">
                <div class="reveal is-visible mb-6">
                    <p class="portfolio-text-muted text-sm font-semibold uppercase tracking-[0.28em]">Sobre mim</p>
                    <h3 class="portfolio-text mt-2 text-2xl font-semibold tracking-tight sm:text-3xl">
                        Quem está por trás do portfólio
                    </h3>
                </div>

                <article class="portfolio-card reveal rounded-[1.75rem] p-6 sm:p-8">
                    <div class="mb-5 flex items-center gap-3">
                        <span class="portfolio-button-dark flex h-11 w-11 items-center justify-center rounded-2xl">
                            <i class="fas fa-user"></i>
                        </span>
                        <div>
                            <p class="portfolio-text text-lg font-semibold">Sobre Mim</p>
                            <p class="portfolio-text-muted text-sm">Resumo profissional</p>
                        </div>
                    </div>

                    <p class="portfolio-text-soft whitespace-pre-line text-sm leading-8 sm:text-base">{{ $aboutMe->bio }}</p>
                </article>
            </section>
        @endif

        @if($experienceItems->count() > 0)
            <section class="mb-12 lg:mb-16">
                <div class="reveal is-visible mb-6">
                    <p class="portfolio-text-muted text-sm font-semibold uppercase tracking-[0.28em]">Trajetória</p>
                    <h3 class="portfolio-text mt-2 text-2xl font-semibold tracking-tight sm:text-3xl">
                        Experiência profissional
                    </h3>
                </div>

                <div class="grid gap-4">
                    @foreach($experienceItems as $experience)
                        <article class="portfolio-card reveal rounded-[1.5rem] p-5 sm:p-6">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                <div>
                                    <div class="flex flex-wrap items-center gap-3">
                                        <h4 class="portfolio-text text-lg font-semibold sm:text-xl">{{ $experience->role }}</h4>
                                        <span class="portfolio-button-dark rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em]">
                                            {{ $experience->company }}
                                        </span>
                                    </div>
                                    <p class="portfolio-text-soft mt-3 max-w-3xl text-sm leading-7 sm:text-base">
                                        {{ $experience->description }}
                                    </p>
                                </div>

                                <div class="portfolio-pill inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium">
                                    <i class="far fa-calendar"></i>
                                    {{ \Carbon\Carbon::parse($experience->started_at)->format('m/Y') }}
                                    <span class="text-slate-300">•</span>
                                    {{ $experience->ended_at ? \Carbon\Carbon::parse($experience->ended_at)->format('m/Y') : 'Atual' }}
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        @if($visibleProjects->count() > 0)
            <section id="projetos" class="mb-12 lg:mb-16">
                <div class="reveal is-visible mb-6 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="portfolio-text-muted text-sm font-semibold uppercase tracking-[0.28em]">Projetos</p>
                        <h3 class="portfolio-text mt-2 text-2xl font-semibold tracking-tight sm:text-3xl">
                            Trabalhos em destaque
                        </h3>
                    </div>
                </div>

                <div class="grid gap-5 lg:grid-cols-2">
                    @foreach($visibleProjects as $project)
                        <article class="project-card portfolio-card reveal flex h-full flex-col overflow-hidden rounded-[1.75rem]">
                            <div class="project-thumb relative">
                                @if($project->thumbnail_path)
                                    <img src="{{ Storage::url($project->thumbnail_path) }}" alt="{{ $project->title }}" class="h-52 w-full object-cover sm:h-60">
                                @else
                                    <div class="flex h-52 w-full items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-slate-400 sm:h-60">
                                        <i class="fas fa-image text-5xl"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-1 flex-col p-5 sm:p-6">
                                <div>
                                    <div class="mb-3 flex items-start justify-between gap-3">
                                        <h4 class="portfolio-text text-xl font-semibold tracking-tight sm:text-2xl">{{ $project->title }}</h4>
                                        <span class="portfolio-pill inline-flex shrink-0 rounded-full px-3 py-1 text-[0.68rem] font-semibold uppercase tracking-[0.22em]">
                                            Projeto
                                        </span>
                                    </div>

                                    <p class="portfolio-text-soft text-sm leading-7 sm:text-base">
                                        {{ $project->description }}
                                    </p>
                                </div>

                                <div class="mt-5">
                                    @if($project->technologies->count() > 0)
                                        <div class="flex min-h-[3.75rem] flex-wrap content-start gap-2">
                                            @foreach($project->technologies as $tech)
                                                <span
                                                    class="inline-flex items-center gap-2 rounded-full px-3.5 py-2 text-sm font-semibold shadow-sm"
                                                    style="background-color: {{ $tech->color ?? '#e2e8f0' }}; color: {{ $tech->color ? '#ffffff' : '#334155' }};"
                                                >
                                                    <i class="{{ $tech->display_icon }} text-[1.18rem]"></i>
                                                    {{ $tech->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="min-h-[3.75rem]"></div>
                                    @endif
                                </div>

                                <div class="mt-auto flex min-h-[4.5rem] items-end pt-6">
                                    <div class="flex flex-wrap items-center gap-3">
                                        @if($project->github_url)
                                            <a
                                                href="{{ $project->github_url }}"
                                                target="_blank"
                                                class="portfolio-pill inline-flex h-12 w-12 items-center justify-center rounded-2xl text-[1.58rem] transition hover:border-[var(--portfolio-border-strong)] hover:text-[var(--portfolio-text)]"
                                                aria-label="GitHub de {{ $project->title }}"
                                            >
                                                <i class="fab fa-github"></i>
                                            </a>
                                        @endif

                                        @if($project->demo_url && $project->show_project_button)
                                            <a
                                                href="{{ $project->demo_url }}"
                                                target="_blank"
                                                class="portfolio-button-dark inline-flex items-center gap-2 rounded-2xl px-5 py-3 text-sm font-semibold"
                                            >
                                                <i class="fas fa-arrow-up-right-from-square"></i>
                                                Ver projeto
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        @if($socialItems->count() > 0)
            <section class="portfolio-card reveal rounded-[1.75rem] px-5 py-8 text-center sm:px-8 sm:py-10">
                <p class="portfolio-text-muted text-sm font-semibold uppercase tracking-[0.28em]">Contato</p>
                <h3 class="portfolio-text mt-2 text-2xl font-semibold tracking-tight sm:text-3xl">
                    Redes sociais e canais profissionais
                </h3>

                <div class="mt-6 flex flex-wrap justify-center gap-3">
                    @foreach($socialItems as $link)
                        <a
                            href="{{ $link->url }}"
                            target="_blank"
                            class="portfolio-pill inline-flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition hover:border-[var(--portfolio-border-strong)] hover:text-[var(--portfolio-text)]"
                            title="{{ $link->platform }}"
                        >
                            <i class="{{ $link->display_icon }} text-lg"></i>
                            {{ $link->platform }}
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const body = document.body;
        const themeToggle = document.getElementById('theme-toggle');
        const themeToggleIcon = document.getElementById('theme-toggle-icon');
        const themeToggleLabel = document.getElementById('theme-toggle-label');
        const storedTheme = localStorage.getItem('portfolio-theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const initialDark = storedTheme ? storedTheme === 'dark' : prefersDark;
        const copyEmailButton = document.querySelector('[data-copy-email]');
        const copyEmailFeedback = document.getElementById('copy-email-feedback');
        const backgroundOrbs = document.querySelectorAll('[data-speed]');

        function applyTheme(isDark) {
            body.classList.toggle('portfolio-dark', isDark);

            if (themeToggleIcon) {
                themeToggleIcon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
            }

            if (themeToggleLabel) {
                themeToggleLabel.textContent = isDark ? 'Tema claro' : 'Tema escuro';
            }
        }

        applyTheme(initialDark);

        if (themeToggle) {
            themeToggle.addEventListener('click', function () {
                const isDark = !body.classList.contains('portfolio-dark');
                applyTheme(isDark);
                localStorage.setItem('portfolio-theme', isDark ? 'dark' : 'light');
            });
        }

        if (copyEmailButton) {
            copyEmailButton.addEventListener('click', async function () {
                const email = copyEmailButton.getAttribute('data-copy-email');

                try {
                    await navigator.clipboard.writeText(email);

                    if (copyEmailFeedback) {
                        copyEmailFeedback.classList.remove('opacity-0', 'translate-y-1');
                        copyEmailFeedback.classList.add('opacity-100', 'translate-y-0');

                        window.clearTimeout(window.__copyEmailFeedbackTimeout);
                        window.__copyEmailFeedbackTimeout = window.setTimeout(function () {
                            copyEmailFeedback.classList.add('opacity-0', 'translate-y-1');
                            copyEmailFeedback.classList.remove('opacity-100', 'translate-y-0');
                        }, 1800);
                    }
                } catch (error) {
                    console.error('Não foi possível copiar o e-mail.', error);
                }
            });
        }

        function updateOrbPositions() {
            const scrollY = window.scrollY;

            backgroundOrbs.forEach(function (orb) {
                const speed = parseFloat(orb.getAttribute('data-speed') || '0');
                orb.style.transform = 'translate3d(0, ' + (scrollY * speed) + 'px, 0)';
            });
        }

        updateOrbPositions();
        window.addEventListener('scroll', updateOrbPositions, { passive: true });

        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.querySelectorAll('.reveal').forEach(function (element) {
                element.classList.add('is-visible');
            });
            return;
        }

        const elements = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.12,
            rootMargin: '0px 0px -36px 0px'
        });

        elements.forEach(function (element, index) {
            element.style.transitionDelay = (index % 4) * 70 + 'ms';
            observer.observe(element);
        });
    });
</script>
@endsection
