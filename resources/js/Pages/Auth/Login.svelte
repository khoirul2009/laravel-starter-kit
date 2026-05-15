<script>
    import { useForm } from '@inertiajs/svelte';
    import AuthLayout from '../../Layouts/AuthLayout.svelte';
    import TextInput from '../../Components/TextInput.svelte';
    import Button from '../../Components/Button.svelte';

    const form = useForm({
        email: '',
        password: '',
        remember: false,
    });

    function submit(e) {
        e.preventDefault();
        form.post('/login', { onFinish: () => form.reset('password') });
    }
</script>

<AuthLayout>
    <h1 class="auth-card__title">Welcome back</h1>
    <p class="auth-card__subtitle">Sign in to your admin dashboard.</p>

    <form onsubmit={submit} novalidate>
        <TextInput
            id="email"
            label="Email"
            type="email"
            autocomplete="username"
            bind:value={form.email}
            error={form.errors.email}
            required
        />

        <TextInput
            id="password"
            label="Password"
            type="password"
            autocomplete="current-password"
            bind:value={form.password}
            error={form.errors.password}
            required
        />

        <label style="display:flex;align-items:center;gap:8px;font-size:13px;margin:0 0 20px;color:var(--color-text-muted);">
            <input type="checkbox" bind:checked={form.remember} />
            Remember me
        </label>

        <Button type="submit" loading={form.processing} style="width:100%;">Sign in</Button>
    </form>
</AuthLayout>
