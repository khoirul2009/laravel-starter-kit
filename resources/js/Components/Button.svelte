<script>
    let {
        type = 'button',
        variant = 'primary',
        size = '',
        loading = false,
        disabled = false,
        href = null,
        onclick = null,
        children,
        ...rest
    } = $props();

    let classes = $derived(
        ['btn',
            variant === 'secondary' ? 'btn--secondary' : '',
            variant === 'ghost' ? 'btn--ghost' : '',
            variant === 'danger' ? 'btn--danger' : '',
            size === 'sm' ? 'btn--sm' : '',
        ].filter(Boolean).join(' ')
    );
</script>

{#if href}
    <a {href} class={classes} {...rest}>
        {#if loading}<span class="btn__spinner"></span>{/if}
        {@render children?.()}
    </a>
{:else}
    <button {type} class={classes} disabled={disabled || loading} {onclick} {...rest}>
        {#if loading}<span class="btn__spinner"></span>{/if}
        {@render children?.()}
    </button>
{/if}
