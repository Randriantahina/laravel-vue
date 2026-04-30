export type TaskStatus = 'todo' | 'in_progress' | 'done';
export type TaskPriority = 'low' | 'medium' | 'high';

export interface Task {
    id: number;
    title: string;
    description: string | null;
    status: TaskStatus;
    priority: TaskPriority;
    due_date: string | null;
    created_at: string;
}

export const STATUS_LABELS: Record<TaskStatus, string> = {
    todo: 'À faire',
    in_progress: 'En cours',
    done: 'Terminé',
};

export const PRIORITY_LABELS: Record<TaskPriority, string> = {
    low: 'Basse',
    medium: 'Moyenne',
    high: 'Haute',
};
