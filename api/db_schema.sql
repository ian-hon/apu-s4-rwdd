CREATE TABLE ROLES (
    role VARCHAR(100) PRIMARY KEY
);

CREATE TABLE GOAL_TYPE (
    ID VARCHAR(100) PRIMARY KEY,
    term VARCHAR(500),
    unit VARCHAR(50),
    decimal_points INT
);

CREATE TABLE USERS (
    username VARCHAR(100) PRIMARY KEY,
    password VARCHAR(100),
    name VARCHAR(200),
    DOB INT,
    profile_picture VARCHAR(500),
    role VARCHAR(100),
    FOREIGN KEY (role) REFERENCES ROLES(role)
);

CREATE TABLE TASK (
    ID VARCHAR(50) PRIMARY KEY,
    title VARCHAR(500),
    description VARCHAR(2000),
    curator_instructions VARCHAR(500),
    active BOOLEAN,

    target INT,
    excess_limit INT,
    reward_rate INT,
    goal_type VARCHAR(100),
    goal_contribution DECIMAL,
    

    occurance_type VARCHAR(10),
    schedule INT,

    FOREIGN KEY (goal_type) REFERENCES GOAL_TYPE(ID)
);

CREATE TABLE SUBMISSION (
    ID VARCHAR(50) PRIMARY KEY,
    user VARCHAR(100),
    task_ID VARCHAR(50),
    media VARCHAR(500),
    submitted_timestamp INT,
    action_count INT,
    status VARCHAR(15),
    curator VARCHAR(100),

    FOREIGN KEY (user) REFERENCES USERS(username),
    FOREIGN KEY (task_ID) REFERENCES TASK(ID),
    FOREIGN KEY (curator) REFERENCES USERS(username)
);

CREATE TABLE POINTS (
    ID VARCHAR(50) PRIMARY KEY,
    amount INT,
    timestamp INT,
    submission VARCHAR(50),

    FOREIGN KEY (submission) REFERENCES SUBMISSION(ID)
);

CREATE TABLE REWARD (
    ID VARCHAR(50) PRIMARY KEY,
    title VARCHAR(50),
    description VARCHAR(2000),
    price INT,
    media VARCHAR(500),
    active BOOLEAN,
    remaining INT,
    initial INT
);

CREATE TABLE REDEMPTION (
    ID VARCHAR(50) PRIMARY KEY,
    reward_ID VARCHAR(50),
    user VARCHAR(100),
    timestamp INT,
    price INT,

    FOREIGN KEY (reward_ID) REFERENCES REWARD(ID),
    FOREIGN KEY (user) REFERENCES USERS(username)
);

CREATE TABLE GLOBAL_GOALS (
    ID VARCHAR(50) PRIMARY KEY,
    title VARCHAR(200),
    description VARCHAR(2000),
    media VARCHAR(500),
    goal_type VARCHAR(100),
    goal DECIMAL,
    starting_time INT,
    ending_time INT,
    FOREIGN KEY (goal_type) REFERENCES GOAL_TYPE(ID)
);

CREATE TABLE PERSONAL_GOALS (
    ID VARCHAR(50) PRIMARY KEY,
    user VARCHAR(100),
    title VARCHAR(200),
    description VARCHAR(2000),
    media VARCHAR(500),
    goal_type VARCHAR(100),
    goal DECIMAL,
    starting_time INT,
    ending_time INT,

    FOREIGN KEY (user) REFERENCES USERS(username),
    FOREIGN KEY (goal_type) REFERENCES GOAL_TYPE(ID)
);