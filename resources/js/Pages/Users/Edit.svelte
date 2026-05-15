<script>
    import { useForm } from '@inertiajs/svelte';
    import DashboardLayout from '../../Layouts/DashboardLayout.svelte';
    import TextInput from '../../Components/TextInput.svelte';
    import SelectInput from '../../Components/SelectInput.svelte';
    import Button from '../../Components/Button.svelte';

    let { user } = $props();

    const form = useForm({
        name: user.name,
        email: user.email,
        password: '',
        password_confirmation: '',
        status: user.status,
    });

    const statusOptions = [
        { value: 1, label: 'Active' },
        { value: 0, label: 'Inactive' },
    ];

    function submit(e) {
        e.preventDefault();
        form.put(`/users/${user.id}`);
    }
</script>

<DashboardLayout title="Edit user">
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit user</h1>
            <p class="page-subtitle">Update account details.</p>
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
                    label="New password"
                    type="password"
                    autocomplete="new-password"
                    bind:value={form.password}
                    error={form.errors.password}
                    hint="Leave blank to keep the current password"
                />
                <TextInput
                    id="password_confirmation"
                    label="Confirm new password"
                    type="password"
                    autocomplete="new-password"
                    bind:value={form.password_confirmation}
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
                <Button type="submit" loading={form.processing}>Save changes</Button>
            </div>
        </form>
    </div>
</DashboardLayout>
