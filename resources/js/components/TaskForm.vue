<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import TaskController from '@/actions/App/Http/Controllers/TaskController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { PRIORITY_LABELS, STATUS_LABELS, type Task } from '@/types';

const props = defineProps<{
    task?: Task;
}>();

const emit = defineEmits<{
    close: [];
}>();

const isEdit = !!props.task;

const formBinding = isEdit
    ? TaskController.update.form({ task: props.task!.id })
    : TaskController.store.form();
</script>

<template>
    <Form
        v-bind="formBinding"
        class="space-y-4"
        v-slot="{ errors, processing }"
        @success="emit('close')"
    >
        <div class="grid gap-2">
            <Label for="title">Titre <span class="text-destructive">*</span></Label>
            <Input
                id="title"
                name="title"
                :default-value="task?.title"
                placeholder="Nom de la tâche"
                required
            />
            <InputError :message="errors.title" />
        </div>

        <div class="grid gap-2">
            <Label for="description">Description</Label>
            <textarea
                id="description"
                name="description"
                :value="task?.description ?? ''"
                rows="3"
                placeholder="Description optionnelle…"
                class="border-input bg-background placeholder:text-muted-foreground focus-visible:ring-ring flex min-h-[80px] w-full rounded-md border px-3 py-2 text-sm shadow-sm focus-visible:ring-1 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
            />
            <InputError :message="errors.description" />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="grid gap-2">
                <Label for="status">Statut</Label>
                <Select name="status" :default-value="task?.status ?? 'todo'">
                    <SelectTrigger id="status">
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="(label, value) in STATUS_LABELS"
                            :key="value"
                            :value="value"
                        >
                            {{ label }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <InputError :message="errors.status" />
            </div>

            <div class="grid gap-2">
                <Label for="priority">Priorité</Label>
                <Select name="priority" :default-value="task?.priority ?? 'medium'">
                    <SelectTrigger id="priority">
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem
                            v-for="(label, value) in PRIORITY_LABELS"
                            :key="value"
                            :value="value"
                        >
                            {{ label }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <InputError :message="errors.priority" />
            </div>
        </div>

        <div class="grid gap-2">
            <Label for="due_date">Date d'échéance</Label>
            <Input
                id="due_date"
                name="due_date"
                type="date"
                :default-value="task?.due_date ?? ''"
            />
            <InputError :message="errors.due_date" />
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <Button type="button" variant="outline" @click="emit('close')">
                Annuler
            </Button>
            <Button type="submit" :disabled="processing">
                {{ isEdit ? 'Mettre à jour' : 'Créer' }}
            </Button>
        </div>
    </Form>
</template>
