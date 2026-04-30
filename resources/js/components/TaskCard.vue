<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import { CalendarDays, Pencil, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';
import TaskController from '@/actions/App/Http/Controllers/TaskController';
import TaskForm from '@/components/TaskForm.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { PRIORITY_LABELS, STATUS_LABELS, type Task } from '@/types';

defineProps<{
    task: Task;
}>();

const editOpen = ref(false);

const priorityVariant: Record<Task['priority'], 'default' | 'secondary' | 'destructive'> = {
    low: 'secondary',
    medium: 'default',
    high: 'destructive',
};

const statusVariant: Record<Task['status'], 'default' | 'secondary' | 'outline'> = {
    todo: 'outline',
    in_progress: 'secondary',
    done: 'default',
};
</script>

<template>
    <Card class="flex flex-col">
        <CardHeader class="pb-2">
            <div class="flex items-start justify-between gap-2">
                <CardTitle class="text-base leading-snug">{{ task.title }}</CardTitle>
                <div class="flex shrink-0 gap-1">
                    <Badge :variant="statusVariant[task.status]">
                        {{ STATUS_LABELS[task.status] }}
                    </Badge>
                    <Badge :variant="priorityVariant[task.priority]">
                        {{ PRIORITY_LABELS[task.priority] }}
                    </Badge>
                </div>
            </div>
        </CardHeader>

        <CardContent class="flex-1">
            <p v-if="task.description" class="text-muted-foreground line-clamp-3 text-sm">
                {{ task.description }}
            </p>
        </CardContent>

        <CardFooter class="flex items-center justify-between pt-2">
            <div v-if="task.due_date" class="text-muted-foreground flex items-center gap-1 text-xs">
                <CalendarDays class="h-3 w-3" />
                {{ task.due_date }}
            </div>
            <div v-else />

            <div class="flex gap-1">
                <Dialog v-model:open="editOpen">
                    <DialogTrigger as-child>
                        <Button variant="ghost" size="icon" class="h-8 w-8">
                            <Pencil class="h-4 w-4" />
                        </Button>
                    </DialogTrigger>
                    <DialogContent>
                        <DialogHeader>
                            <DialogTitle>Modifier la tâche</DialogTitle>
                        </DialogHeader>
                        <TaskForm :task="task" @close="editOpen = false" />
                    </DialogContent>
                </Dialog>

                <Form v-bind="TaskController.destroy.form({ task: task.id })">
                    <Button
                        type="submit"
                        variant="ghost"
                        size="icon"
                        class="h-8 w-8 text-destructive hover:text-destructive"
                    >
                        <Trash2 class="h-4 w-4" />
                    </Button>
                </Form>
            </div>
        </CardFooter>
    </Card>
</template>
