CREATE TABLE ROLE (
    role VARCHAR PRIMARY KEY
);

CREATE TABLE GOAL_TYPE (
    ID VARCHAR PRIMARY KEY,
    term VARCHAR,
    unit VARCHAR,
    significant_figures INT
);

CREATE TABLE USER (
    username VARCHAR PRIMARY KEY,
    password VARCHAR,
    name VARCHAR,
    DOB TIMESTAMP,
    profile_picture VARCHAR,
    role VARCHAR FOREIGN KEY REFERENCES(ROLE.role),
);

CREATE TABLE TASK (
    ID VARCHAR PRIMARY KEY,
    title VARCHAR,
    description VARCHAR,
    curator_instructions VARCHAR,
    active BOOLEAN,

    target INT,
    excess_limit INT,
    reward_rate INT,
    goal_type VARCHAR FOREIGN KEY REFERENCES(GOAL_TYPE.ID),
    goal_contribution DECIMAL,
    
    occurance_type VARCHAR,
    schedule INT
);

CREATE TABLE SUBMISSION (
    ID VARHAR PRIMARY KEY,
    user VARCHAR FOREIGN KEY REFERENCES(USER.username),
    task_ID VARCHAR FOREIGN KEY REFERENCES(TASK.ID)
    media VARCHAR,
    submitted_timestamp TIMESTAMP,
    action_count INT,
    status VARCHAR,
    curator VARCHAR NULLABLE FOREIGN KEY REFERENCES(USER.username)
);

CREATE TABLE POINTS (
    ID VARCHAR PRIMARY KEY,
    amount INT,
    timestamp TIMESTAMP,
    submission VARCHAR FOREIGN KEY REFERENCES(SUBMISSION.ID)
);

CREATE TABLE REWARD (
    ID VARCHAR PRIMARY KEY,
    title VARCHAR,
    description VARCHAR,
    price INT,
    media VARCHAR,
    active BOOLEAN,
    remaining INT,
    initial INT
);

CREATE TABLE REDEMPTION (
    ID VARCHAR PRIMARY KEY,
    reward_ID VARCHAR FOREIGN KEY REFERENCES(REWARD.ID),
    user VARCHAR FOREIGN KEY REFERENCES(USER.username),
    timestamp TIMESTAMP,
    price INT
);

CREATE TABLE GLOBAL_GOALS (
    ID VARCHAR PRIMARY KEY,
    title VARCHAR,
    description VARCHAR,
    media VARCHAR,
    goal_type VARCHAR FOREIGN KEY REFERENCES(GOAL_TYPE.ID),
    goal DECIMAL,
    starting_time TIMESTAMP,
    ending_time TIMESTAMP
);

CREATE TABLE PERSONAL_GOALS (
    ID VARCHAR PRIMARY KEY,
    user VARCHAR FOREIGN KEY REFERENCES(USER.username)
    title VARCHAR,
    description VARCHAR,
    media VARCHAR,
    goal_type VARCHAR FOREIGN KEY REFERENCES(GOAL_TYPE.ID),
    goal DECIMAL,
    starting_time TIMESTAMP,
    ending_time TIMESTAMP
);