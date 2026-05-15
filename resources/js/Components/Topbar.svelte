<script>
    import { page } from '@inertiajs/svelte';

    let { title = '', onToggleSidebar = null } = $props();

    let user = $derived(page.props.auth?.user);
    let initial = $derived(user?.name ? user.name.charAt(0).toUpperCase() : '?');
</script>

<header class="topbar">
    <button class="topbar__hamburger" type="button" aria-label="Open menu" onclick={onToggleSidebar}>
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round">
            <line x1="3" y1="6" x2="21" y2="6" />
            <line x1="3" y1="12" x2="21" y2="12" />
            <line x1="3" y1="18" x2="21" y2="18" />
        </svg>
    </button>
    <div class="topbar__title">{title}</div>
    <div class="topbar__spacer"></div>
    {#if user}
        <div class="topbar__user">
            <span class="topbar__avatar">{initial}</span>
            <span class="topbar__name">{user.name}</span>
        </div>
    {/if}
</header>
