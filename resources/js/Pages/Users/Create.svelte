<script>
    import { useForm } from '@inertiajs/svelte';
    import DashboardLayout from '../../Layouts/DashboardLayout.svelte';
    import TextInput from '../../Components/TextInput.svelte';
    import SelectInput from '../../Components/SelectInput.svelte';
    import Button from '../../Components/Button.svelte';

    const form = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        status: 1,
    });

    const statusOptions = [
        { value: 1, label: 'Active' },
        { value: 0, label: 'Inactive' },
    ];

    function submit(e) {
        e.preventDefault();
        form.post('/users');
    }
</script>

<DashboardLayout title="Create user">
    <div class="page-header">
        <div>
            <h1 class="page-title">Create user</h1>
            <p class="page-subtitle">Add a new account to the system.</p>
        </div>
        <Button variant="secondary" href="/users">Back to list</Button>
    </div>

    <div class="card" style="max-width:720px;">
        <form onsubmit={submit} novalidate>
            <div class="grid-2">
                <TextInput id="name" label="Name" bind:value={form.name} error={form.errors.name} required />
                <TextInput id="email" label="Email" type="email" bind:value={form.email} error={form.errors.email} required />
            </div>

            <div class="grid-2">
                <TextInput
                    id="password"
                    label="Password"
                    type="password"
                    autocomplete="new-password"
                    bind:value={form.password}
                    error={form.errors.password}
                    hint="At least 8 characters"
                    required
                />
                <TextInput
                    id="password_confirmation"
                    label="Confirm password"
                    type="password"
                    autocomplete="new-password"
                    bind:value={form.password_confirmation}
                    required
                />
            </div>

            <SelectInput
                id="status"
                label="Status"
                bind:value={form.status}
                options={statusOptions}
                error={form.errors.status}
            />

            <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:8px;">
                <Button variant="secondary" href="/users">Cancel</Button>
                <Button type="submit" loading={form.processing}>Create user</Button>
            </div>
        </form>
    </div>
</DashboardLayout>
