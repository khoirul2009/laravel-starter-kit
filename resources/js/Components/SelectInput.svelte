<script>
    let {
        label = '',
        value = $bindable(''),
        options = [],
        error = '',
        id = '',
        ...rest
    } = $props();
</script>

<div class="field {error ? 'field--error' : ''}">
    {#if label}<label class="field__label" for={id}>{label}</label>{/if}
    <select class="field__select" {id} bind:value {...rest}>
        {#each options as opt}
            <option value={opt.value}>{opt.label}</option>
        {/each}
    </select>
    {#if error}<p class="field__error">{error}</p>{/if}
</div>
