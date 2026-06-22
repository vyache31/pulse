import { useEffect, useState } from "react";

export default function TaskStream({ workspaceId }) {
    const [tasks, setTasks] = useState([]);

    useEffect(() => {
        const ws = new WebSocket("wss://api.pulse.vyache.space/ws");

        ws.onopen = () => {
            console.log("WS connected");
        };

        ws.onmessage = (event) => {
            const msg = JSON.parse(event.data);

            if (msg.type === "new_task") {
                const task = msg.post;

                if (task.workspace_id && task.workspace_id !== workspaceId) {
                    return;
                }

                setTasks((prev) => [task, ...prev]);
            }
        };

        ws.onerror = (err) => {
            console.error("WS error", err);
        };

        return () => ws.close();
    }, [workspaceId]);

    return (
        <div className="live-task-stream">
            {tasks.map((task) => (
                <div key={task.id} className="task-card">
                    <div className="fw-bold text-white">{task.title}</div>
                    {task.description && (
                        <small className="text-muted">
                            {task.description}
                        </small>
                    )}
                </div>
            ))}
        </div>
    );
}
