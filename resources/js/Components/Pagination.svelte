<script>
    import { Link } from '@inertiajs/svelte';
    let { links = [] } = $props();
</script>

{#if links.length > 3}
    <nav class="pagination" aria-label="Pagination">
        {#each links as link}
            {#if link.url}
                <Link
                    href={link.url}
                    class={'pagination__link' + (link.active ? ' is-active' : '')}
                    preserveScroll
                >
                    {@html link.label}
                </Link>
            {:else}
                <span class="pagination__link is-disabled">{@html link.label}</span>
            {/if}
        {/each}
    </nav>
{/if}
