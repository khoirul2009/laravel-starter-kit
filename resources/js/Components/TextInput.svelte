<script>
    let {
        label = '',
        type = 'text',
        value = $bindable(''),
        error = '',
        hint = '',
        id = '',
        placeholder = '',
        autocomplete = '',
        required = false,
        ...rest
    } = $props();
</script>

<div class="field {error ? 'field--error' : ''}">
    {#if label}<label class="field__label" for={id}>{label}</label>{/if}
    <input
        class="field__input"
        {type}
        {id}
        {placeholder}
        {autocomplete}
        {required}
        bind:value
        {...rest}
    />
    {#if error}
        <p class="field__error">{error}</p>
    {:else if hint}
        <p class="field__hint">{hint}</p>
    {/if}
</div>
