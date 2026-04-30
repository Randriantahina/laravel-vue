import { router } from '@inertiajs/vue3';
import { beforeEach, describe, expect, it, vi } from 'vitest';
import { useTaskFilters } from '@/composables/useTaskFilters';

vi.mock('@inertiajs/vue3', () => ({
    router: { get: vi.fn() },
}));

vi.mock('@/routes/tasks', () => ({
    index: () => '/tasks',
}));

describe('useTaskFilters', () => {
    beforeEach(() => {
        vi.clearAllMocks();
    });

    it('initializes with the provided status', () => {
        const { activeStatus } = useTaskFilters('todo');
        expect(activeStatus.value).toBe('todo');
    });

    it('initializes with empty string when no status provided', () => {
        const { activeStatus } = useTaskFilters('');
        expect(activeStatus.value).toBe('');
    });

    it('exposes all status options including "Toutes"', () => {
        const { statusOptions } = useTaskFilters('');
        expect(statusOptions).toHaveLength(4);
        expect(statusOptions[0].value).toBe('');
        expect(statusOptions[0].label).toBe('Toutes');
    });

    it('updates activeStatus and navigates when applyFilter is called', () => {
        const { activeStatus, applyFilter } = useTaskFilters('');
        applyFilter('in_progress');

        expect(activeStatus.value).toBe('in_progress');
        expect(router.get).toHaveBeenCalledWith(
            '/tasks',
            { status: 'in_progress' },
            { preserveState: true, replace: true },
        );
    });

    it('sends undefined status when clearing the filter', () => {
        const { applyFilter } = useTaskFilters('todo');
        applyFilter('');

        expect(router.get).toHaveBeenCalledWith(
            '/tasks',
            { status: undefined },
            { preserveState: true, replace: true },
        );
    });
});
