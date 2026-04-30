<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ClipboardList, Plus } from 'lucide-vue-next';
import { ref } from 'vue';
import TaskCard from '@/components/TaskCard.vue';
import TaskForm from '@/components/TaskForm.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { useTaskFilters } from '@/composables/useTaskFilters';
import { index } from '@/routes/tasks';
import type { Task } from '@/types';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Tâches', href: index() },
        ],
    },
});

const props = defineProps<{
    tasks: Task[];
    filters: { status: string };
}>();

const createOpen = ref(false);

const { activeStatus, statusOptions, applyFilter } = useTaskFilters(props.filters.status);
</script>

<template>
    <Head title="Tâches" />

    <div class="flex flex-col gap-6 p-4">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold">Mes tâches</h1>

            <Dialog v-model:open="createOpen">
                <DialogTrigger as-child>
                    <Button>
                        <Plus class="mr-2 h-4 w-4" />
                        Nouvelle tâche
                    </Button>
                </DialogTrigger>
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Créer une tâche</DialogTitle>
                    </DialogHeader>
                    <TaskForm @close="createOpen = false" />
                </DialogContent>
            </Dialog>
        </div>

        <div class="flex gap-2">
            <Button
                v-for="option in statusOptions"
                :key="option.value"
                :variant="activeStatus === option.value ? 'default' : 'outline'"
                size="sm"
                @click="applyFilter(option.value)"
            >
                {{ option.label }}
            </Button>
        </div>

        <div v-if="tasks.length === 0" class="flex flex-col items-center gap-3 py-16 text-center">
            <ClipboardList class="text-muted-foreground h-12 w-12" />
            <p class="text-muted-foreground text-sm">Aucune tâche trouvée.</p>
        </div>

        <div v-else class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <TaskCard v-for="task in tasks" :key="task.id" :task="task" />
        </div>
    </div>
</template>
