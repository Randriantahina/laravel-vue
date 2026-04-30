export const index = () => '/tasks';
export const store = () => '/tasks';
export const update = ({ task }: { task: number | string }) => `/tasks/${task}`;
export const destroy = ({ task }: { task: number | string }) => `/tasks/${task}`;
