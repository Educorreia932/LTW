DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Pets;

CREATE TABLE Users (
    Username TEXT PRIMARY KEY,  
    Password TEXT,
    Name TEXT
);

CREATE TABLE Pets (
    ID INTEGER PRIMARY KEY,
    Name TEXT,
    Gender INTEGER,
    Age INTEGER,
    URL TEXT,
    SpeciesID INTEGER REFERENCES PetSpecies(ID)
);

CREATE TABLE PetSpecies (
    ID INTEGER PRIMARY KEY,
    SpeciesName TEXT,
    Symbol TEXT
);

INSERT INTO PetSpecies
VALUES(
    0,
    "Cat",
    "🐈"    
);

INSERT INTO PetSpecies
VALUES(
    1,
    "Dog",
    "🐕"    
);

INSERT INTO PetSpecies
VALUES(
    2,
    "Hedgehog",
    "🦔"    
);

INSERT INTO Pets
VALUES(
    NULL,
    "Hamilton",
    1,
    1,
    "https://i.insider.com/5654150584307663008b4ed8?width=1100&format=jpeg&auto=webp",
    0
);

INSERT INTO Pets
VALUES(
    NULL,
    "Grumpy Cat",
    1,
    1,
    "https://media.wired.com/photos/5cdefb92b86e041493d389df/1:1/w_988,h_988,c_limit/Culture-Grumpy-Cat-487386121.jpg",
    0
);

INSERT INTO Pets
VALUES(
    NULL,
    "Maya",
    1,
    1,
    "https://ourfunnylittlesite.com/wp-content/uploads/2018/07/1-4.jpg",
    1
);

INSERT INTO Pets
VALUES(
    NULL,
    "Herbee",
    1,
    1,
    "https://i2-prod.mirror.co.uk/incoming/article20090958.ece/ALTERNATES/s1227b/0_JS193011867.jpg",
    2
);

INSERT INTO Pets
VALUES(
    NULL,
    "Kermit",
    1,
    1,
    "https://cdn.discordapp.com/attachments/627876438216933419/773219910331531304/iu.png",
    1
);