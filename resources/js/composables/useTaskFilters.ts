import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { index } from '@/routes/tasks';
import type { TaskStatus } from '@/types';

export function useTaskFilters(initialStatus: string) {
    const activeStatus = ref<TaskStatus | ''>(initialStatus as TaskStatus | '');

    const statusOptions: { value: TaskStatus | ''; label: string }[] = [
        { value: '', label: 'Toutes' },
        { value: 'todo', label: 'À faire' },
        { value: 'in_progress', label: 'En cours' },
        { value: 'done', label: 'Terminé' },
    ];

    function applyFilter(status: TaskStatus | '') {
        activeStatus.value = status;
        router.get(
            index(),
            { status: status || undefined },
            { preserveState: true, replace: true },
        );
    }

    return { activeStatus, statusOptions, applyFilter };
}
