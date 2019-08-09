CREATE TABLE users (userid INTEGER PRIMARY KEY NOT NULL,
                    first_name TEXT NOT NULL,
                    last_name TEXT NOT NULL,
                    username TEXT NOT NULL,
                    password TEXT NOT NULL,
                    session TEXT UNIQUE);
CREATE TABLE pictures (id INTEGER PRIMARY KEY NOT NULL,
                       title TEXT NOT NULL,
                       picpath TEXT NOT NULL UNIQUE,
                       ext TEXT NOT NULL,
                       credit TEXT,
                       user INTEGER,
                       FOREIGN KEY (user) REFERENCES users(userid));
CREATE TABLE tags (id INTEGER PRIMARY KEY NOT NULL,
                   tag TEXT NOT NULL);
CREATE TABLE pictags(picid INTEGER NOT NULL,
                     tagid INTEGER NOT NULL,
                     FOREIGN KEY (picid) REFERENCES pictures(id) ON DELETE CASCADE,
                     FOREIGN KEY (tagid) REFERENCES tags(id) ON DELETE CASCADE);
/* TODO: initial seed data */
INSERT INTO users (first_name, last_name, username, password) VALUES ('Bruce', 'Wayne', 'BruceWayne','$2y$10$Us1ZWYvuwkbHtqhcwVqlo.nmgSyxX/aFFq/vWmMiZHOTfPjhv2uq.'); /*password: imbatman*/
INSERT INTO users (first_name, last_name, username, password) VALUES ('Clark', 'Kent', 'ClarkKent','$2y$10$p/Btbz129SHbi/iiG4ikruaz668gh9BaZ5V0d/r0nLnlY13jHsiuu'); /* password: superman*/
INSERT INTO pictures (title, picpath, ext, credit, user) VALUES ('Bald Eagle', '/uploads/pictures/bald-eagle-1.jpg', 'jpg', 'https://www.freeimages.com/photo/bald-eagle-1-1400106', (SELECT userid FROM users WHERE username = 'BruceWayne'));
INSERT INTO pictures (title, picpath, ext, credit, user) VALUES ('Bald Eagle', '/uploads/pictures/bald-eagle.jpg', 'jpg', 'https://www.freeimages.com/photo/bald-eagle-1500580', (SELECT userid FROM users WHERE username = 'ClarkKent'));
INSERT INTO pictures (title, picpath, ext, credit, user) VALUES ('Bird', '/uploads/pictures/bird.jpg', 'jpg', 'https://www.freeimages.com/photo/bird-1306747', (SELECT userid FROM users WHERE username = 'BruceWayne'));
INSERT INTO pictures (title, picpath, ext, credit, user) VALUES ('Blackbirds', '/uploads/pictures/blackbirds.jpg', 'jpg', 'https://www.freeimages.com/photo/blackbirds-nest-abandonned-1398130', (SELECT userid FROM users WHERE username = 'ClarkKent'));
INSERT INTO pictures (title, picpath, ext, credit, user) VALUES ('Camel', '/uploads/pictures/camel.jpg', 'jpg', 'https://www.freeimages.com/photo/camel-1501072', (SELECT userid FROM users WHERE username = 'ClarkKent'));
INSERT INTO pictures (title, picpath, ext, credit, user) VALUES ('Euphonium', '/uploads/pictures/euphonium.jpg', 'jpg', 'https://www.freeimages.com/photo/euphonium-1419684', (SELECT userid FROM users WHERE username = 'BruceWayne'));
INSERT INTO pictures (title, picpath, ext, credit) VALUES ('Felted Sheep', '/uploads/pictures/felted-sheep.jpg', 'jpg', 'https://www.freeimages.com/photo/felted-sheep-1638476');
INSERT INTO pictures (title, picpath, ext, credit) VALUES ('Saxophone', '/uploads/pictures/saxophone.jpg', 'jpg', 'https://www.freeimages.com/photo/saxophone-1-1563844');
INSERT INTO pictures (title, picpath, ext, credit) VALUES ('Shoeprint', '/uploads/pictures/shoeprint.jpg', 'jpg', 'https://www.freeimages.com/photo/shoeprint-1425635');
INSERT INTO pictures (title, picpath, ext, credit) VALUES ('Traffic Sign', '/uploads/pictures/traffic-sign.jpg', 'jpg', 'https://www.freeimages.com/photo/traffic-sign-1456966');
INSERT INTO pictures (title, picpath, ext, credit) VALUES ('Walk', '/uploads/pictures/walk.jpg', 'jpg', 'https://www.freeimages.com/photo/walk-1241491');
INSERT INTO tags (tag) VALUES ('Bird');
INSERT INTO tags (tag) VALUES ('Eagle');
INSERT INTO tags (tag) VALUES ('Animals');
INSERT INTO tags (tag) VALUES ('Instrument');
INSERT INTO tags (tag) VALUES ('Shoes');
INSERT INTO tags (tag) VALUES ('Traffic');
INSERT INTO pictags (picid, tagid) VALUES ((SELECT id FROM pictures WHERE picpath = '/uploads/pictures/bald-eagle-1.jpg'),(SELECT id FROM tags WHERE tag = 'Bird'));
INSERT INTO pictags (picid, tagid) VALUES ((SELECT id FROM pictures WHERE picpath = '/uploads/pictures/bald-eagle.jpg'),(SELECT id FROM tags WHERE tag = 'Bird'));
INSERT INTO pictags (picid, tagid) VALUES ((SELECT id FROM pictures WHERE picpath = '/uploads/pictures/bird.jpg'),(SELECT id FROM tags WHERE tag = 'Bird'));
INSERT INTO pictags (picid, tagid) VALUES ((SELECT id FROM pictures WHERE picpath = '/uploads/pictures/blackbirds.jpg'),(SELECT id FROM tags WHERE tag = 'Bird'));
INSERT INTO pictags (picid, tagid) VALUES ((SELECT id FROM pictures WHERE picpath = '/uploads/pictures/bald-eagle-1.jpg'),(SELECT id FROM tags WHERE tag = 'Eagle'));
INSERT INTO pictags (picid, tagid) VALUES ((SELECT id FROM pictures WHERE picpath = '/uploads/pictures/bald-eagle.jpg'),(SELECT id FROM tags WHERE tag = 'Eagle'));
INSERT INTO pictags (picid, tagid) VALUES ((SELECT id FROM pictures WHERE picpath = '/uploads/pictures/bird.jpg'),(SELECT id FROM tags WHERE tag = 'Eagle'));
INSERT INTO pictags (picid, tagid) VALUES ((SELECT id FROM pictures WHERE picpath = '/uploads/pictures/bald-eagle-1.jpg'),(SELECT id FROM tags WHERE tag = 'Animals'));
INSERT INTO pictags (picid, tagid) VALUES ((SELECT id FROM pictures WHERE picpath = '/uploads/pictures/bald-eagle.jpg'),(SELECT id FROM tags WHERE tag = 'Animals'));
INSERT INTO pictags (picid, tagid) VALUES ((SELECT id FROM pictures WHERE picpath = '/uploads/pictures/bird.jpg'),(SELECT id FROM tags WHERE tag = 'Animals'));
INSERT INTO pictags (picid, tagid) VALUES ((SELECT id FROM pictures WHERE picpath = '/uploads/pictures/blackbirds.jpg'),(SELECT id FROM tags WHERE tag = 'Animals'));
INSERT INTO pictags (picid, tagid) VALUES ((SELECT id FROM pictures WHERE picpath = '/uploads/pictures/camel.jpg'),(SELECT id FROM tags WHERE tag = 'Animals'));
INSERT INTO pictags (picid, tagid) VALUES ((SELECT id FROM pictures WHERE picpath = '/uploads/pictures/euphonium.jpg'),(SELECT id FROM tags WHERE tag = 'Instrument'));
INSERT INTO pictags (picid, tagid) VALUES ((SELECT id FROM pictures WHERE picpath = '/uploads/pictures/saxophone.jpg'),(SELECT id FROM tags WHERE tag = 'Instrument'));
INSERT INTO pictags (picid, tagid) VALUES ((SELECT id FROM pictures WHERE picpath = '/uploads/pictures/shoeprint.jpg'),(SELECT id FROM tags WHERE tag = 'Shoes'));
INSERT INTO pictags (picid, tagid) VALUES ((SELECT id FROM pictures WHERE picpath = '/uploads/pictures/traffic-sign.jpg'),(SELECT id FROM tags WHERE tag = 'Traffic'));
INSERT INTO pictags (picid, tagid) VALUES ((SELECT id FROM pictures WHERE picpath = '/uploads/pictures/walk.jpg'),(SELECT id FROM tags WHERE tag = 'Traffic'));
