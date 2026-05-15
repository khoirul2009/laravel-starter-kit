<script>
    import { Link, page, router } from '@inertiajs/svelte';

    let { open = $bindable(false) } = $props();

    let current = $derived(page.url || '/');

    function isActive(prefix) {
        return current === prefix || current.startsWith(prefix + '/') || current.startsWith(prefix + '?');
    }

    function logout(e) {
        e.preventDefault();
        router.post('/logout');
    }

    function closeOnNav() {
        open = false;
    }
</script>

{#if open}
    <button class="sidebar__backdrop" aria-label="Close menu" onclick={() => (open = false)}></button>
{/if}

<aside class="sidebar" class:is-open={open}>
    <div class="sidebar__brand">
        <span class="sidebar__brand-mark">L</span>
        <span>Laravel Observe</span>
    </div>

    <Link href="/dashboard" class={'sidebar__link' + (isActive('/dashboard') ? ' is-active' : '')} onclick={closeOnNav}>
        <span>🏠</span>
        <span>Dashboard</span>
    </Link>

    <Link href="/users" class={'sidebar__link' + (isActive('/users') ? ' is-active' : '')} onclick={closeOnNav}>
        <span>👥</span>
        <span>Users</span>
    </Link>

    <div class="sidebar__spacer"></div>

    <button class="sidebar__link" type="button" onclick={logout}>
        <span>🚪</span>
        <span>Logout</span>
    </button>
</aside>
