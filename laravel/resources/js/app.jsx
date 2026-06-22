import React from "react";
import { createRoot } from "react-dom/client";
import TaskStream from "./components/TaskStream";

const el = document.getElementById("task-stream");

if (el) {
    const workspaceId = el.dataset.workspace;

    createRoot(el).render(
        <TaskStream workspaceId={workspaceId} />
    );
}