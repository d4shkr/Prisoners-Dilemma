CREATE DATABASE IF NOT EXISTS db;

USE db;

DROP TABLE IF EXISTS Dilemma;
DROP TABLE IF EXISTS Players;

CREATE TABLE Dilemma
(
    GameId VARCHAR(36) NOT NULL PRIMARY KEY,
    CurrentRound INT,
    GamePhase TEXT, -- Status: 'Running' if game is running, 'Finished' if game is finished
    PayoffHistory_Player1 JSON,
    PayoffHistory_Player2 JSON,
    Score_Player1 INT, -- Total score for Pl1
    Score_Player2 INT, 
    Status_Player1 INT, -- Status: -1 if hasn't joined the game yet, 0 if didn't choose yet, 1 if Betrayed, 2 if Cooperated
    Status_Player2 INT,
    Message_Player1 TEXT, -- Message to display to the player
    Message_Player2 TEXT,
    UpToDate_Player1 BOOLEAN, -- false if visuals need to be updated
    UpToDate_Player2 BOOLEAN,
    MaxRounds INT, -- Total number of rounds in the game
    BothBetrayPayoff INT, -- Payoff if both players betray
    BothCooperatePayoff INT, -- Payoff if both players cooperate
    WasBetrayedPayoff INT, -- Payoff if player cooperated and was betrayed
    HasBetrayedPayoff INT, -- Payoff if player betrayed and the other player cooperated
    MaxRoundsKnown BOOLEAN -- false if number of rounds is hidden from the players
);

CREATE TABLE Players
(
    PlayerId VARCHAR(36) NOT NULL PRIMARY KEY,
    Curr_GameId VARCHAR(36) REFERENCES Dilemma(GameId),
    Curr_PlayerNum INT -- 1 or 2
);