<script>
    import Sidebar from '../Components/Sidebar.svelte';
    import Topbar from '../Components/Topbar.svelte';
    import Alert from '../Components/Alert.svelte';
    import { page } from '@inertiajs/svelte';

    let { title = 'Dashboard', children } = $props();
    let sidebarOpen = $state(false);

    let flash = $derived(page.props.flash || {});
</script>

<div class="app-shell">
    <Sidebar bind:open={sidebarOpen} />

    <div class="app-shell__main">
        <Topbar {title} onToggleSidebar={() => (sidebarOpen = !sidebarOpen)} />

        <div class="app-shell__content">
            {#if flash.success}
                <Alert type="success" message={flash.success} />
            {/if}
            {#if flash.error}
                <Alert type="error" message={flash.error} />
            {/if}

            {@render children?.()}
        </div>
    </div>
</div>
