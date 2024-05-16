CREATE DATABASE IF NOT EXISTS db;

USE db;

DROP TABLE IF EXISTS Dilemma;
DROP TABLE IF EXISTS Players;

CREATE TABLE Dilemma
(
    GameId VARCHAR(36) NOT NULL PRIMARY KEY,
    PayoffHistory_Player1 JSON,
    PayoffHistory_Player2 JSON,
    Score_Player1 INT, -- Total score for Pl1
    Score_Player2 INT, 
    Status_Player1 INT, -- Status: -1 if hasn't joined the game yet, 0 if didn't choose yet , 1 if Betrayed, 2 if Cooperated
    Status_Player2 INT,
    Player1_UpToDate BOOLEAN, -- false if visuals need to be updated
    Player2_UpToDate BOOLEAN,
    CurrentRound INT,
    MaxRounds INT, -- Total number of rounds in the game
    GamePhase TEXT -- Status: 'Running' if game is running, 'Finished' if game is finished
);

CREATE TABLE Players
(
    PlayerId VARCHAR(36) NOT NULL PRIMARY KEY,
    Curr_GameId VARCHAR(36),
    Curr_PlayerNum INT -- 1 or 2
);