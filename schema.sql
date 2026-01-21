-- READ THIS FIRST!
-- This SQLite schema 9dima a already made many modification its just to get a conception of what going here
-- you can install slqite visualiser on VScode and click on the codev.db database and it will visualise the tables
-- ou kay3tik 7ta script final li t9der tcreer bih aya table tma


PRAGMA foreign_keys = ON;

-- Users Table
CREATE TABLE IF NOT EXISTS Users (
    user_id INTEGER PRIMARY KEY AUTOINCREMENT,
    first_name TEXT NOT NULL,
    last_name TEXT NOT NULL,
    username TEXT NOT NULL UNIQUE,
    email TEXT NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    role TEXT CHECK(role IN ('Admin', 'Project Manager', 'Developer')) NOT NULL
);

-- Projects Table
CREATE TABLE IF NOT EXISTS Projects (
    project_id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    description TEXT,
    pm_id INTEGER NOT NULL,
    FOREIGN KEY (pm_id) REFERENCES Users(user_id) ON DELETE RESTRICT
);

-- Developers <-> Projects
CREATE TABLE IF NOT EXISTS Project_Assignments (
    assignment_id INTEGER PRIMARY KEY AUTOINCREMENT,
    project_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    FOREIGN KEY (project_id) REFERENCES Projects(project_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    UNIQUE(project_id, user_id)
);

-- Tasks Table
CREATE TABLE IF NOT EXISTS Tasks (
    task_id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    description TEXT,
    status TEXT CHECK(status IN ('To Do', 'In Progress', 'Done')) DEFAULT 'To Do',
    project_id INTEGER NOT NULL,
    assigned_to INTEGER NOT NULL,
    created_by INTEGER NOT NULL,
    FOREIGN KEY (project_id) REFERENCES Projects(project_id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES Users(user_id),
    FOREIGN KEY (created_by) REFERENCES Users(user_id)
);

--makhdemtehach f lkher i didnt achieve the features concerning it but it's here for further development
-- Bugs Table
CREATE TABLE IF NOT EXISTS Bugs (
    bug_id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    description TEXT,
    severity TEXT CHECK(severity IN ('Low', 'Medium', 'High')) NOT NULL,
    status TEXT CHECK(status IN ('Open', 'Fixed', 'Closed')) DEFAULT 'Open',
    project_id INTEGER NOT NULL,
    reported_by INTEGER NOT NULL,
    FOREIGN KEY (project_id) REFERENCES Projects(project_id) ON DELETE CASCADE,
    FOREIGN KEY (reported_by) REFERENCES Users(user_id)
);