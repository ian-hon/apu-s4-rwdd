CREATE DATABASE ecoquest;

CREATE TABLE ecoquest.ROLES (
    role VARCHAR(20) PRIMARY KEY
);

CREATE TABLE ecoquest.GOAL_TYPE (
    ID VARCHAR(100) PRIMARY KEY,
    term VARCHAR(500),
    unit VARCHAR(50),
    decimal_points INT
);

CREATE TABLE ecoquest.USERS (
    username VARCHAR(200) PRIMARY KEY,
    password VARCHAR(64),
    name VARCHAR(200),
    DOB INT,
    profile_picture BLOB,
    role VARCHAR(20),
    FOREIGN KEY (role) REFERENCES ROLES(role)
);

CREATE TABLE ecoquest.TASK (
    ID VARCHAR(50) PRIMARY KEY,
    title VARCHAR(500),
    description VARCHAR(2000),
    curator_instructions VARCHAR(500),
    active BOOLEAN,

    target INT,
    excess_limit INT,
    reward_rate INT,
    goal_type VARCHAR(100),
    goal_contribution DECIMAL(20,10),
    

    occurance_type VARCHAR(10),
    schedule INT,

    FOREIGN KEY (goal_type) REFERENCES GOAL_TYPE(ID)
);

CREATE TABLE ecoquest.SUBMISSION (
    ID VARCHAR(50) PRIMARY KEY,
    user VARCHAR(200),
    task_ID VARCHAR(50),
    media BLOB,
    submitted_timestamp INT,
    action_count INT,
    status VARCHAR(20),
    curator VARCHAR(200),

    FOREIGN KEY (user) REFERENCES USERS(username),
    FOREIGN KEY (task_ID) REFERENCES TASK(ID),
    FOREIGN KEY (curator) REFERENCES USERS(username)
);

CREATE TABLE ecoquest.POINTS (
    ID VARCHAR(50) PRIMARY KEY,
    amount INT,
    timestamp INT,
    submission VARCHAR(50),

    FOREIGN KEY (submission) REFERENCES SUBMISSION(ID)
);

CREATE TABLE ecoquest.REWARD (
    ID VARCHAR(50) PRIMARY KEY,
    title VARCHAR(200),
    description VARCHAR(2000),
    price INT,
    media BLOB,
    active BOOLEAN,
    remaining INT,
    initial INT
);

CREATE TABLE ecoquest.REDEMPTION (
    ID VARCHAR(50) PRIMARY KEY,
    reward_ID VARCHAR(50),
    user VARCHAR(200),
    timestamp INT,
    price INT,

    FOREIGN KEY (reward_ID) REFERENCES REWARD(ID),
    FOREIGN KEY (user) REFERENCES USERS(username)
);

CREATE TABLE ecoquest.GOALS (
    ID VARCHAR(50) PRIMARY KEY,
    title VARCHAR(200),
    description VARCHAR(2000),
    media VARCHAR(500),
    goal_type VARCHAR(100),
    goal DECIMAL,
    starting_time INT,
    ending_time INT,

    type VARCHAR(100),
    -- either 'personal' or 'global'

    FOREIGN KEY (goal_type) REFERENCES GOAL_TYPE(ID)
);

-- mock data
INSERT INTO ecoquest.ROLES (role) VALUES
('user'),
('curator'),
('admin');

INSERT INTO ecoquest.GOAL_TYPE (ID, term, unit, decimal_points) VALUES
('plastic', 'plastic waste', 'kg', 2),
('carbon', 'CO2 offset', 'metric tonne', 2),
('electric', 'electricity saved', 'kWh', 2),
('trash', 'trash', 'kg', 1);

INSERT INTO ecoquest.USERS (username, password, name, DOB, profile_picture, role) VALUES
('admin1', 'hashed_password_123', 'Emma Green', 19850615, '/images/profiles/admin1.jpg', 'admin'),
('curator1', 'hashed_password_456', 'David Earth', 19900320, '/images/profiles/curator1.jpg', 'curator'),
('user1', 'hashed_password_789', 'Sarah Eco', 19950812, '/images/profiles/user1.jpg', 'user'),
('user2', 'hashed_password_101', 'Mike Recycle', 19880425, '/images/profiles/user2.jpg', 'user'),
('user3', 'hashed_password_112', 'Lisa Clean', 19920703, '/images/profiles/user3.jpg', 'user');

-- TA_XXXX
INSERT INTO ecoquest.TASK (ID, title, description, curator_instructions, active, target, excess_limit, reward_rate, goal_type, goal_contribution, occurance_type, schedule) VALUES
('TA_0001', 'Bottle Recycling Challenge', 'Recycle 10 plastic bottles properly', 'Verify recycling receipt or photo at recycling center', TRUE, 10, 5, 15, 'plastic', 0.50, 'daily', 1),
('TA_0002', 'Can Reuse Project', 'Creatively reuse 5 aluminum cans', 'Check photos of reused cans with creative applications', TRUE, 5, 3, 20, 'plastic', 0.30, 'weekly', 3),
('TA_0003', 'Community Cleanup', 'Pick up 10 pieces of trash from public areas', 'Verify before/after photos and trash collection', TRUE, 10, 10, 25, 'trash', 2.00, 'daily', 1),
('TA_0004', 'Energy Conservation', 'Save 5 kWh of electricity this week', 'Review electricity bill or smart meter readings', TRUE, 5, 2, 30, 'electric', 5.00, 'weekly', 7);

-- RW_XXXX
INSERT INTO ecoquest.REWARD (ID, title, description, price, media, active, remaining, initial) VALUES
('RW_0001', 'Bamboo Toothbrush Set', 'Set of 4 biodegradable bamboo toothbrushes', 150, '/images/rewards/bamboo_brush.jpg', TRUE, 25, 30),
('RW_0002', 'Tree Planting Donation', 'Plant 5 trees through environmental charity', 500, '/images/rewards/tree_donation.jpg', TRUE, 15, 20),
('RW_0003', 'Reusable Water Bottle', 'Stainless steel eco-friendly water bottle', 200, '/images/rewards/water_bottle.jpg', TRUE, 35, 40),
('RW_0004', 'Ocean Cleanup Donation', 'Support ocean plastic removal project', 800, '/images/rewards/ocean_cleanup.jpg', TRUE, 8, 10),
('RW_0005', 'Solar Charger', 'Portable solar phone charger', 400, '/images/rewards/solar_charger.jpg', TRUE, 12, 15);

-- SU_XXXX
INSERT INTO ecoquest.SUBMISSION (ID, user, task_ID, media, submitted_timestamp, action_count, status, curator) VALUES
('SU_0001', 'user1', 'TA_0001', '/uploads/submissions/bottles_recycled_001.jpg', 1700000000, 12, 'approved', 'curator1'),
('SU_0002', 'user2', 'TA_0003', '/uploads/submissions/trash_pickup_001.jpg', 1700010000, 15, 'approved', 'curator1'),
('SU_0003', 'user3', 'TA_0002', '/uploads/submissions/can_reuse_001.jpg', 1700020000, 5, 'pending', NULL),
('SU_0004', 'user1', 'TA_0004', '/uploads/submissions/energy_saved_001.jpg', 1700030000, 6, 'approved', 'curator1'),
('SU_0005', 'user2', 'TA_0001', '/uploads/submissions/bottles_recycled_002.jpg', 1700040000, 8, 'rejected', 'curator1'),

('SU_0006', 'user1', 'TA_0001', '/uploads/submissions/streak_day1.jpg', 86400 * (FLOOR(UNIX_TIMESTAMP() / 86400) - 5), 0, 'approved', NULL),
('SU_0007', 'user1', 'TA_0002', '/uploads/submissions/streak_day2.jpg', 86400 * (FLOOR(UNIX_TIMESTAMP() / 86400) - 4), 0, 'approved', NULL),
('SU_0008', 'user1', 'TA_0003', '/uploads/submissions/streak_day3.jpg', 86400 * (FLOOR(UNIX_TIMESTAMP() / 86400) - 3), 0, 'approved', NULL),
('SU_0009', 'user1', 'TA_0001', '/uploads/submissions/streak_day4.jpg', 86400 * (FLOOR(UNIX_TIMESTAMP() / 86400) - 2), 0, 'approved', NULL),
('SU_0010', 'user1', 'TA_0004', '/uploads/submissions/streak_day5.jpg', 86400 * (FLOOR(UNIX_TIMESTAMP() / 86400) - 1), 0, 'approved', NULL),
('SU_0017', 'user1', 'TA_0004', '/uploads/submissions/streak_today.jpg', 86400 * (FLOOR(UNIX_TIMESTAMP() / 86400)), 0, 'approved', NULL),

('SU_0011', 'user2', 'TA_0001', '/uploads/submissions/user2_day1.jpg', 86400 * (FLOOR(UNIX_TIMESTAMP() / 86400) - 5), 0, 'approved', NULL),
('SU_0012', 'user2', 'TA_0002', '/uploads/submissions/user2_day2.jpg', 86400 * (FLOOR(UNIX_TIMESTAMP() / 86400) - 4), 0, 'approved', NULL),
('SU_0013', 'user2', 'TA_0003', '/uploads/submissions/user2_day3.jpg', 86400 * (FLOOR(UNIX_TIMESTAMP() / 86400) - 3), 0, 'approved', NULL),
('SU_0014', 'user2', 'TA_0001', '/uploads/submissions/user2_day5.jpg', 86400 * (FLOOR(UNIX_TIMESTAMP() / 86400) - 1), 0, 'approved', NULL),

('SU_0015', 'user3', 'TA_0001', '/uploads/submissions/user3_yesterday.jpg', 86400 * (FLOOR(UNIX_TIMESTAMP() / 86400) - 1), 0, 'approved', NULL),
('SU_0016', 'user3', 'TA_0002', '/uploads/submissions/user3_today.jpg', 86400 * FLOOR(UNIX_TIMESTAMP() / 86400), 0, 'approved', NULL);

-- PT_XXXX
INSERT INTO ecoquest.POINTS (ID, amount, timestamp, submission) VALUES
('PT_0001', 180, 1700005000, 'SU_0001'),
('PT_0002', 375, 1700015000, 'SU_0002'),
('PT_0003', 180, 1700035000, 'SU_0004');

-- RD_XXXX
INSERT INTO ecoquest.REDEMPTION (ID, reward_ID, user, timestamp, price) VALUES
('RD_0001', 'RW_0001', 'user1', 1700050000, 150),
('RD_0002', 'RW_0003', 'user2', 1700060000, 200),
('RD_0003', 'RW_0002', 'user1', 1700070000, 500);

-- GL_XXXX
INSERT INTO ecoquest.GOALS (ID, title, description, media, goal_type, goal, starting_time, ending_time, type) VALUES
('GL_0001', 'Community Plastic Reduction', 'Reduce 100kg of plastic waste as a community', '/images/goals/plastic_reduction.jpg', 'plastic', 100.00, 1700000000, 1702592000, 'global'),
('GL_0002', 'Carbon Offset Challenge', 'Offset 50 metric tonnes of CO2 collectively', '/images/goals/carbon_offset.jpg', 'carbon', 50.00, 1700000000, 1710000000, 'global'),
('GL_0003', 'Zero Waste Month', 'Reduce personal plastic waste by 10kg', '/images/goals/zero_waste.jpg', 'plastic', 10.00, 1700000000, 1702592000, 'personal'),
('GL_0004', 'Clean Community', 'Collect 50kg of trash from neighborhood', '/images/goals/clean_community.jpg', 'trash', 50.0, 1700000000, 1707782400, 'personal'),
('GL_0005', 'Energy Saver', 'Save 100 kWh of electricity this quarter', '/images/goals/energy_save.jpg', 'electric', 100.00, 1700000000, 1707782400, 'personal');
