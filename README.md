# Pulse

Платформа для отслеживания задач в виде канбан доски с разделением на workspaces. Работающий сайт -> pulse.vyache.space

# Архитектура

                 ┌──────────────────────────────┐
                 │          Laravel             │
                 │------------------------------│
                 │  - Workspaces                │
                 │  - Columns                   │
                 │  - Tasks CRUD                │
                 │  - Auth / Policies           │
                 └─────────────┬────────────────┘
                               │
                               │ Redis publish
                               ▼
                 ┌──────────────────────────────┐
                 │            Redis             │
                 │------------------------------│
                 │  Pub/Sub channel:            │
                 │  - new_task                  │
                 │  - task_update               │
                 └─────────────┬────────────────┘
                               │
                               │ subscribe
                               ▼
          ┌────────────────────────────────────────┐
          │              FastAPI WS Server         │
          │----------------------------------------│
          │  - Redis subscriber loop               │
          │  - WebSocket manager                   │
          │  - broadcast to clients                │
          └─────────────┬──────────────────────────┘
                        │
                        │ WebSocket (ws://.../ws)
                        ▼
        ┌──────────────────────────────────────┐
        │            React Frontend            │
        │--------------------------------------│
        │  Kanban UI                           │
        │  useEffect(WebSocket)                │
        │  - add task live                     │
        │  - update task live                  │
        └──────────────────────────────────────┘

# Запуск

# Основные сценарии

1. Зарегистрироваться/ войти через GitHub
2. Create/edit workspace
3. Create/edit/delete колонку
4. Create/edit/delete задачу (название, описание, теги, исполнитель из участников)
# Структура БД
