(function() {
    const workspaceId = window.workspaceId;
    if (!workspaceId) {
        console.error('workspaceId не передан');
        return;
    }

    const ws = new WebSocket('wss://api.pulse.vyache.space/ws');

    ws.onopen = () => console.log('WebSocket connected');
    ws.onerror = (err) => console.error(err);
    ws.onclose = () => console.log('WebSocket disconnected');

    function buildTaskHtml(task) {
        const tagsHtml = (Array.isArray(task.tags) && task.tags.length)
            ? `<div class="mt-2">${task.tags.map(tag => `<span class="badge bg-secondary">${tag}</span>`).join('')}</div>`
            : '';
        const doerHtml = task.assigned_to
            ? `<div class="text-info mt-1">👤 ${task.assigned_to}</div>`
            : '';
        const editUrl = `/workspaces/${workspaceId}/columns/${task.column_id}/tasks/${task.id}/edit`;
        return `
            <div class="task-card" data-task-id="${task.id}">
                <div class="d-flex justify-content-between align-items-start">
                    <div style="width:100%">
                        <div class="fw-bold text-white">${task.title}</div>
                        ${task.description ? `<small class="text-muted d-block">${task.description}</small>` : ''}
                        ${tagsHtml}
                        ${doerHtml}
                    </div>
                    <a href="${editUrl}" class="btn btn-sm btn-link text-light p-0">⚙</a>
                </div>
            </div>
        `;
    }

    function findTaskCard(taskId) {
        return document.querySelector(`.task-card[data-task-id="${taskId}"]`);
    }

    function getColumnContainer(columnId) {
        return document.querySelector(`[data-column-id="${columnId}"] .tasks-container`);
    }

    function buildColumnHtml(column) {
        const editColumnUrl = `/workspaces/${workspaceId}/columns/${column.id}/edit`;
        const createTaskUrl = `/workspaces/${workspaceId}/columns/${column.id}/tasks/create`;
        return `
            <div class="kanban-column" data-column-id="${column.id}" data-position="${column.position}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 text-white">${column.title}</h5>
                    <a href="${editColumnUrl}" class="btn btn-sm btn-outline-light">⚙</a>
                </div>
                <div class="tasks-container"></div>
                <a href="${createTaskUrl}" class="btn btn-outline-light w-100 mt-2">+ Задача</a>
            </div>
        `;
    }

    function findColumnElement(columnId) {
        return document.querySelector(`.kanban-column[data-column-id="${columnId}"]`);
    }

    function getBoardContainer() {
        return document.querySelector('.kanban-board');
    }

    function getAddColumnButton() {
        const board = getBoardContainer();
        return board ? board.querySelector('.kanban-column:last-child') : null;
    }

    function insertColumnByPosition(columnHtml, position) {
        const board = getBoardContainer();
        if (!board) return;

        const columns = board.querySelectorAll('.kanban-column:not(:last-child)');
        let inserted = false;
        for (const col of columns) {
            const colPos = parseInt(col.dataset.position, 10);
            if (colPos > position) {
                col.parentNode.insertBefore(columnHtml, col);
                inserted = true;
                break;
            }
        }
        if (!inserted) {

            const addBtn = getAddColumnButton();
            if (addBtn) {
                board.insertBefore(columnHtml, addBtn);
            } else {
                board.appendChild(columnHtml);
            }
        }
    }

    ws.onmessage = (event) => {
        const msg = JSON.parse(event.data);
        console.log('WS message:', msg);

        const data = msg.post;
        if (!data) return;

        // Фильтр по workspace (если есть)
        if (data.workspace_id && data.workspace_id != workspaceId) return;

        switch (msg.type) {
            case 'new_task': {
                const column = getColumnContainer(data.column_id);
                if (column) {
                    column.insertAdjacentHTML('beforeend', buildTaskHtml(data));
                }
                break;
            }

            case 'updated_task': {
                const oldCard = findTaskCard(data.id);
                if (oldCard) {
                    oldCard.remove();
                    const newColumn = getColumnContainer(data.column_id);
                    if (newColumn) {
                        newColumn.insertAdjacentHTML('beforeend', buildTaskHtml(data));
                    } else {
                        // fallback: вставить на место старой
                        oldCard.outerHTML = buildTaskHtml(data);
                    }
                } else {
                    console.warn('Task card not found for update:', data.id);
                }
                break;
            }

            case 'deleted_task': {
                const card = findTaskCard(data.id);
                if (card) card.remove();
                break;
            }

            case 'new_column': {
                const columnHtml = buildColumnHtml(data);
                const temp = document.createElement('div');
                temp.innerHTML = columnHtml;
                const newCol = temp.firstElementChild;
                if (newCol) {
                    insertColumnByPosition(newCol, data.position || 0);
                }
                break;
            }

            case 'updated_column': {
                const oldCol = findColumnElement(data.id);
                if (!oldCol) {
                    const columnHtml = buildColumnHtml(data);
                    const temp = document.createElement('div');
                    temp.innerHTML = columnHtml;
                    const newCol = temp.firstElementChild;
                    if (newCol) {
                        insertColumnByPosition(newCol, data.position || 0);
                    }
                    break;
                }

                const titleEl = oldCol.querySelector('h5');
                if (titleEl) titleEl.textContent = data.title;

                oldCol.dataset.position = data.position;

                const currentPos = parseInt(oldCol.dataset.position, 10);
                const parent = oldCol.parentNode;
                const addBtn = getAddColumnButton();
                parent.removeChild(oldCol);
                insertColumnByPosition(oldCol, data.position || 0);
                break;
            }

            case 'deleted_column': {
                const col = findColumnElement(data.id);
                if (col) col.remove();
                break;
            }

            default:
                console.warn('Unknown message type:', msg.type);
        }
    };
})();
